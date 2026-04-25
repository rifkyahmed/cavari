<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GenerateSitemap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sitemap:generate';
    protected $description = 'Generate the sitemap.xml for SEO';

    public function handle()
    {
        $products = \App\Models\Product::all();
        $categories = \App\Models\Category::all();

        $xml = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"></urlset>');

        // Home
        $url = $xml->addChild('url');
        $url->addChild('loc', route('home'));
        $url->addChild('lastmod', now()->toAtomString());
        $url->addChild('changefreq', 'daily');
        $url->addChild('priority', '1.0');

        // Shop / Products Index
        $url = $xml->addChild('url');
        $url->addChild('loc', route('products.index'));
        $url->addChild('changefreq', 'daily');
        $url->addChild('priority', '0.9');

        // Journal Index
        $url = $xml->addChild('url');
        $url->addChild('loc', route('journal.index'));
        $url->addChild('changefreq', 'weekly');
        $url->addChild('priority', '0.8');

        // Journals
        $journals = \App\Models\Journal::where('is_published', true)->get();
        foreach ($journals as $journal) {
            $url = $xml->addChild('url');
            $url->addChild('loc', route('journal.show', $journal->slug));
            $url->addChild('lastmod', $journal->updated_at->toAtomString());
            $url->addChild('changefreq', 'monthly');
            $url->addChild('priority', '0.7');
        }

        // Products
        foreach ($products as $product) {
            $url = $xml->addChild('url');
            $url->addChild('loc', route('products.show', $product->slug));
            $url->addChild('lastmod', $product->updated_at->toAtomString());
            $url->addChild('changefreq', 'weekly');
            $url->addChild('priority', '0.8');
        }

        $xml->asXML(public_path('sitemap.xml'));
        
        $this->info('Sitemap generated successfully.');
    }
}
