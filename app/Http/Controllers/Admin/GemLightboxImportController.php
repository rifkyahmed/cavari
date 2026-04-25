<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GemLightboxImportController extends Controller
{
    /**
     * Fetch product data from a GemLightbox shared product link.
     * The page is a Next.js App Router SPA; all product data is embedded
     * in the HTML as RSC (React Server Component) inline payloads in
     *   self.__next_f.push([1, "...JSON..."])  script tags.
     * We parse those payloads to find "initData" which contains the full
     * media list.
     */
    public function fetch(Request $request)
    {
        $request->validate(['url' => 'required|url']);

        $url = trim($request->input('url'));

        // Accept multiple URL formats:
        //   https://gembox.app/s/bOrM9eJG0j
        //   https://hub.gemlightbox.com/s/bOrM9eJG0j
        //   https://gallery.cloud.picupmedia.com/c/some-uuid
        if (!preg_match('#/(s|c)/([A-Za-z0-9_\-]+)(?:\?.*)?$#', $url, $m)) {
            return response()->json(['error' => 'Invalid GemLightbox URL. Expected format: https://gembox.app/s/XXXXXXXX'], 422);
        }

        // Fetch the HTML page
        $ctx = stream_context_create([
            'http' => [
                'method'        => 'GET',
                'header'        => "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/122.0.0.0 Safari/537.36\r\nAccept: text/html,application/xhtml+xml\r\n",
                'timeout'       => 15,
                'ignore_errors' => true,
            ],
        ]);

        $html = @file_get_contents($url, false, $ctx);

        if (!$html) {
            return response()->json(['error' => 'Could not reach GemLightbox. Check the URL or try again.'], 422);
        }

        // -----------------------------------------------------------------------
        // 1. Extract the product title from the <title> tag
        // -----------------------------------------------------------------------
        $title = null;
        if (preg_match('/<title>([^<]+)<\/title>/', $html, $tm)) {
            $title = html_entity_decode(trim($tm[1]));
            // GemLightbox uses a generic default title for all pages; discard it
            if (in_array($title, ['Exquisite Jewelry Collection', 'GemLightbox', 'Picup Media'])) {
                $title = null;
            }
        }

        // -----------------------------------------------------------------------
        // 2. Extract the OG description
        // -----------------------------------------------------------------------
        $description = null;
        if (preg_match('/property="og:description" content="([^"]+)"/i', $html, $dm)) {
            $desc = html_entity_decode(trim($dm[1]));
            // Also discard the generic default description
            if (strpos($desc, 'premium materials and gemstones') === false) {
                $description = $desc;
            }
        }

        // -----------------------------------------------------------------------
        // 3. Parse the RSC inline payloads to find "initData"
        //    GemLightbox embeds the full product JSON as part of the server
        //    rendering. It appears in a script push as:
        //      self.__next_f.push([1,"...<JSON with initData>..."]);
        // -----------------------------------------------------------------------
        $initData = null;
        preg_match_all('/<script>(.*?)<\/script>/s', $html, $scriptMatches);

        foreach ($scriptMatches[1] as $scriptContent) {
            // Find all RSC push payloads
            preg_match_all('/self\.__next_f\.push\(\[1,"(.*?)"\]\)/s', $scriptContent, $pushes, PREG_SET_ORDER);

            foreach ($pushes as $push) {
                // RSC payloads have escaped JSON strings inside — decode the JS string
                $raw = $push[1];
                // Unescape JS string escapes (\n, \", \\ etc.)
                $decoded = json_decode('"' . $raw . '"');
                if ($decoded === null) continue;

                // Look for the initData key in this payload
                if (strpos($decoded, '"initData"') !== false) {
                    // Try to extract just the initData value from the RSC JSON
                    // The string looks like: "8:[\"$\",\"$L15\",null,{\"params\":{...},\"initData\":{...}}]\n"
                    // We need to find the JSON object that contains initData
                    if (preg_match('/"initData"\s*:\s*(\{.*\})\s*,\s*"linkResult"/s', $decoded, $idMatch)) {
                        $initData = json_decode($idMatch[1], true);
                        if ($initData !== null) break 2;
                    }
                    // Fallback: try broader match
                    if (preg_match('/"initData"\s*:\s*(\{[^}]+(?:\{[^}]*\}[^}]*)*\})/s', $decoded, $idMatch2)) {
                        $initData = json_decode($idMatch2[1], true);
                        if ($initData !== null) break 2;
                    }
                }
            }
        }

        // -----------------------------------------------------------------------
        // 4. Extract title from initData attributes if not found in <title>
        // -----------------------------------------------------------------------
        if ($initData !== null) {
            // initData.title is the real product name set in GemLightbox
            if (empty($title) && !empty($initData['title'])) {
                $title = $initData['title'];
            }
            // Also check the attributes array — some products store the title there
            if (empty($title) && !empty($initData['attributes'])) {
                foreach ($initData['attributes'] as $attr) {
                    if (in_array(strtolower($attr['name'] ?? ''), ['name', 'title', 'product_name', 'product name'])
                        && !empty($attr['value'])) {
                        $title = $attr['value'];
                        break;
                    }
                }
            }
            // NOTE: We do NOT fall back to SKU — SKU is an internal reference, not a product name.
            if (empty($description) && !empty($initData['description'])) {
                $description = $initData['description'];
            }
        }

        // -----------------------------------------------------------------------
        // 5. Build images and video lists
        // -----------------------------------------------------------------------
        $images = [];
        $video  = null;

        if (!empty($initData['medias']) && is_array($initData['medias'])) {
            // Sort by mediaPosition
            usort($initData['medias'], fn($a, $b) => ($a['mediaPosition'] ?? 0) <=> ($b['mediaPosition'] ?? 0));

            foreach ($initData['medias'] as $media) {
                $type = $media['type'] ?? '';
                $file = $media['file'] ?? [];

                if ($type === 'video360' || $type === 'video') {
                    // We want the smallest version that is actually a VIDEO (contains .mp4)
                    // This prevents accidentally grabbing a 'small' thumbnail image.
                    $candidates = [
                        $file['small'] ?? null,
                        $file['compressed'] ?? null,
                        $file['medium'] ?? null,
                        $file['original'] ?? null
                    ];

                    foreach ($candidates as $url) {
                        if ($url && (str_contains(strtolower($url), '.mp4') || str_contains(strtolower($url), '.webm') || str_contains(strtolower($url), '.mov'))) {
                            $video = $url;
                            break; // Stop at the first (smallest) real video found
                        }
                    }
                    
                    // Final fallback if no .mp4 was found in the specific keys
                    if (!$video) {
                        $video = $file['medium'] ?? $file['original'] ?? null;
                    }
                } elseif ($type === 'image') {
                    // Use original for best quality (it's a PNG from GemLightbox)
                    $imgUrl = $file['original'] ?? $file['medium'] ?? null;
                    if ($imgUrl) {
                        $images[] = $imgUrl;
                    }
                }
            }
        }

        // -----------------------------------------------------------------------
        // 6. Fallback: if we couldn't get initData, extract media URLs from HTML
        // -----------------------------------------------------------------------
        if (empty($images) && empty($video)) {
            // Extract all static.cloud.picupmedia.com image/video URLs from the HTML
            preg_match_all(
                '#https://static\.cloud\.picupmedia\.com/gallery/\d+/[^\s"\'\\\\)]+retouch(?:medium|small)?\.(?:png|jpg|jpeg|webp)#i',
                $html,
                $fallbackImgs
            );
            $images = array_values(array_unique($fallbackImgs[0] ?? []));

            preg_match_all(
                '#https://static\.cloud\.picupmedia\.com/gallery/\d+/[^\s"\'\\\\)]+compressed\.mp4#i',
                $html,
                $fallbackVids
            );
            if (!empty($fallbackVids[0])) {
                $video = $fallbackVids[0][0];
            }
        }

        // If nothing at all was found, give up
        if (empty($images) && empty($video)) {
            return response()->json(['error' => 'Could not extract media from this GemLightbox link. Make sure the product has at least one image or video.'], 422);
        }

        return response()->json([
            'success'     => true,
            'title'       => $title,
            'description' => $description,
            'images'      => $images,
            'video'       => $video,
        ]);
    }
}
