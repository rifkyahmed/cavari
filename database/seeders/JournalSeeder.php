<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Journal;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class JournalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $journals = [
            [
                'title' => 'The Complete Guide to Ethical Gemstone Sourcing and Sustainable Mining',
                'excerpt' => 'An in-depth look at ethical gemstone sourcing, sustainable mining practices, and how Cavari guarantees conflict-free diamonds and precious colored gems for your bespoke jewelry.',
                'image_url' => 'https://images.unsplash.com/photo-1605100804763-247f67b254a6?q=80&w=1200&auto=format&fit=crop', // Real Unsplash Image (Mining/Rough crystals)
                'filename' => 'ethical_sourcing.jpg',
                'content' => '
                    <p>In today\'s deeply conscious luxury market, the question of provenance is just as critical as the physical beauty of a gemstone. At Cavari, we believe that true luxury must be synonymous with absolute integrity, which is why <strong>ethical gemstone sourcing</strong> and <strong>sustainable mining</strong> are the foundational pillars of our operations.</p>
                    
                    <h2>What is Ethical Gemstone Sourcing?</h2>
                    <p>Ethical gemstone sourcing refers to the comprehensive process of tracking a gemstone from the moment it is extracted from the earth to the moment it is set into a breathtaking piece of fine jewelry. It guarantees that the gems—whether they are <strong>conflict-free diamonds</strong> or vividly colored sapphires, rubies, and emeralds—are mined under safe, humane conditions without funding civil conflict, terrorism, or systemic human rights abuses.</p>
                    
                    <h3>The Human Impact of Sustainable Mining</h3>
                    <p>Unlike massive industrial mining conglomerates, sustainable gemstone extraction heavily relies on artisanal and small-scale mining (ASM). By working directly with ASM communities in regions like Sri Lanka, Madagascar, and Colombia, we ensure that the capital generated from these precious stones goes directly back into the local communities. This fosters economic growth, funds local education initiatives, and guarantees fair, livable wages for the miners and their families.</p>
                    
                    <blockquote>"A gemstone\'s true beauty is not just evaluated by its color or clarity, but by the profound, positive impact it leaves on the earth and its people."</blockquote>
                    
                    <h2>Our Rigorous Traceability Standards</h2>
                    <p>To eliminate the opaque nature of the traditional jewelry industry, Cavari employs strict traceability protocols. We require a clear chain of custody for every <strong>precious gemstone</strong> in our <a href="/shop">Treasury Collection</a>. This commitment to transparency ensures our clients can wear their bespoke pieces with absolute peace of mind.</p>
                    
                    <h3>Environmental Stewardship</h3>
                    <p>Ethical sourcing also demands a fierce commitment to environmental conservation. We exclusively partner with miners who practice ecological restoration—ensuring that once mining concludes in a specific area, the land is refilled, replanted, and returned to its natural, thriving state. This drastically mitigates deforestation and protects vital local watersheds.</p>
                    
                    <p>By choosing Cavari, you are making a profound investment not only in a timeless, handcrafted heirloom but in a more transparent, equitable, and sustainable global gemstone industry. Contact our master jewelers today to begin crafting your ethical <a href="/shop/custom-design">custom design</a>.</p>
                ',
                'meta_title' => 'Ethical Gemstone Sourcing & Sustainable Mining | Cavari',
                'meta_description' => 'Discover Cavari\'s strict ethical gemstone sourcing protocols. Learn how we ensure conflict-free diamonds and sustainable mining for all luxury bespoke jewelry.',
            ],
            [
                'title' => 'Mastering the 4Cs of Diamond Quality: An Expert Buyer\'s Guide',
                'excerpt' => 'Learn how to perfectly evaluate Cut, Color, Clarity, and Carat weight before purchasing an engagement ring. The definitive 4Cs of Diamonds guide by Cavari Master Jewelers.',
                'image_url' => 'https://images.unsplash.com/photo-1515562141207-7a88fb7ce338?q=80&w=1200&auto=format&fit=crop', // Real Unsplash Image (Diamond)
                'filename' => 'four_cs_diamond.jpg',
                'content' => '
                    <p>Selecting a diamond—whether for a brilliant engagement ring or a timeless anniversary necklace—is an incredibly personal and significant investment. To make an informed decision, one must deeply understand the <strong>4Cs of diamond quality</strong>, a universal grading system developed by the Gemological Institute of America (GIA).</p>
                    
                    <h2>1. Diamond Cut: The Ultimate Sparkle Factor</h2>
                    <p>While often confused with the shape of the stone (e.g., oval, pear, emerald), the <strong>diamond cut</strong> refers to the precise physical proportions, symmetry, and polish of the facets. It is universally considered the most critical of the 4Cs. A masterfully cut diamond acts as a prism, capturing light, refracting it internally, and dispersing it back to the eye in a dazzling display of brilliance, fire, and scintillation. Even if a diamond has perfect color and clarity, a poor cut will leave it appearing dull and lifeless.</p>
                    
                    <h2>2. Diamond Color: The Purest White</h2>
                    <p>In the context of white diamonds, the term "color" actually measures the <em>absence</em> of color. The GIA scale ranges alphabetically from D (absolutely colorless) to Z (light yellow or brown). At Cavari, we adhere to an exceptionally strict standard, exclusively sourcing diamonds within the highly desirable D-to-H range to ensure a bright, pristine, icy-white aesthetic.</p>
                    
                    <h2>3. Diamond Clarity: Flawless Natural Beauty</h2>
                    <p>Because diamonds are formed under extreme heat and pressure deep within the earth, nearly all of them contain unique internal features called inclusions and external irregularities called blemishes. <strong>Diamond clarity</strong> evaluates the size, nature, and position of these characteristics. While a "Flawless" (FL) diamond is phenomenally rare and priced accordingly, stones graded Very Slightly Included (VS1 and VS2) offer extraordinary value, as their microscopic inclusions are invisible to the naked human eye.</p>
                    
                    <h2>4. Diamond Carat: Weight vs. Size</h2>
                    <p><strong>Carat</strong> is the standard unit of measurement for a diamond\'s physical weight, with one carat equaling exactly 200 milligrams. However, carat weight does not perfectly dictate the visual size of the stone. A diamond with a shallower cut or a different shape (such as an elongated oval or marquise) can appear physically larger on the finger than a round brilliant diamond of the exact same carat weight.</p>
                    
                    <blockquote>"A diamond is a miracle of time, pressure, and space. Understanding its grading ensures you secure a miracle that you will cherish for eternity."</blockquote>
                    
                    <p>If you are ready to find the perfect stone, explore our exquisite <a href="/shop/jewelry">Diamond Jewelry Collection</a> or speak with our master gemologists today.</p>
                ',
                'meta_title' => 'The 4Cs of Diamonds: A Complete Expert Guide | Cavari',
                'meta_description' => 'Master the 4Cs of diamonds—Cut, Color, Clarity, and Carat weight—with Cavari\'s comprehensive guide to purchasing the perfect certified luxury engagement ring.',
            ],
            [
                'title' => 'The Royal Allure of Colombian Emeralds',
                'excerpt' => 'Dive into the rich history, vibrant green hues, and global prestige of genuine Colombian emeralds, the crown jewel of modern luxury bespoke jewelry.',
                'image_url' => '', 
                'filename' => 'ceylon_sapphire.jpg',
                'content' => '
                    <p>When one envisions a truly majestic green gemstone, the image that invariably comes to mind is the legendary <strong>Colombian emerald</strong>. Mined exclusively from the lush, mineral-rich soils of the Andes mountains, these exquisite precious stones have captivated royalty, aristocrats, and luxury collectors for thousands of years.</p>
                    
                    <h2>A History Steeped in Royalty</h2>
                    <p>The prestige of the Colombian emerald is deeply interwoven with royal heritage. From the treasuries of the Spanish Empire to the modern crown jewels of European monarchs, the vivid, glowing green of a perfectly cut emerald has always been considered the ultimate symbol of wealth, rebirth, and eternal love.</p>
                    
                    <h2>The Unparalleled "Muzo Green"</h2>
                    <p>What mathematically separates <strong>Colombian emeralds</strong> from emeralds mined in other regions (such as Zambia or Brazil) is their striking luminosity and distinctive color profiles. They are globally revered for their deeply saturated, pure green hue with a very slight bluish tint—often referred to in the industry as "Muzo green" after the famous Muzo mining district. These stones dramatically maintain their vivid color even in dim, ambient lighting environments.</p>
                    
                    <h3>The Value of "Jardin"</h3>
                    <p>Unlike diamonds where flawless clarity is expected, natural emeralds almost always contain unique internal features and inclusions known beautifully as <em>jardin</em> (the French word for garden). These organic microscopic inclusions serve as an eternal fingerprint, proving the gemstone\'s natural origin directly from the earth. A rich, vibrant color is far more prized in an emerald than absolute clarity.</p>
                    
                    <blockquote>"Owning a Colombian emerald is like possessing a completely unaltered, immortal piece of the earth\'s most ancient forest."</blockquote>
                    
                    <h2>Sourcing Your Legacy</h2>
                    <p>At Cavari, our gemologists travel directly to the global markets to hand-select the finest untreated and ethically sourced emeralds available. Whether you are seeking a vibrant emerald engagement ring or a rare pendant, explore our <a href="/shop/gems">Loose Gemstone Treasury</a> to discover a stone that speaks to your soul.</p>
                ',
                'meta_title' => 'Colombian Emeralds: History, Color & Quality | Cavari',
                'meta_description' => 'Explore the deep history and stunning quality of Colombian emeralds. Learn why these vivid green gemstones are favored by global royalty.',
            ]
        ];

        foreach ($journals as $index => $data) {
            
            // Try downloading image to public path
            $storageDir = storage_path('app/public/journals');
            if(!file_exists($storageDir)) {
                mkdir($storageDir, 0755, true);
            }
            
            $imagePath = 'journals/' . $data['filename'];
            
            try {
                $imageContent = file_get_contents($data['image_url']);
                if ($imageContent) {
                    file_put_contents(storage_path('app/public/' . $imagePath), $imageContent);
                }
            } catch (\Exception $e) {
                // Ignore fallback to old if download fails, we at least have proper paths
            }

            Journal::updateOrCreate(
                ['title' => $data['title']],
                [
                    'slug' => Str::slug($data['title']),
                    'excerpt' => $data['excerpt'],
                    'content' => $data['content'],
                    'meta_title' => $data['meta_title'],
                    'meta_description' => $data['meta_description'],
                    'cover_image' => '/storage/' . $imagePath,
                    'is_published' => true,
                    'is_permanent' => true,
                    'published_at' => now()->subDays($index * 5),
                ]
            );
        }
    }
}
