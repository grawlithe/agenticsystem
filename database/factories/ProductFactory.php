<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        /**
         * Category-keyed product definitions.
         * Each entry contains a list of [name, description] pairs.
         *
         * @var array<string, array<int, array{0: string, 1: string}>>
         */
        $catalog = [
            'Electronics' => [
                ['Wireless Noise-Cancelling Headphones', 'Premium over-ear headphones with active noise cancellation, 30-hour battery life, and crystal-clear sound for commuters and audiophiles.'],
                ['4K Smart LED TV 55"', '55-inch 4K UHD Smart TV with HDR10 support, built-in streaming apps, and a slim bezel design for a cinematic home experience.'],
                ['Mechanical Gaming Keyboard', 'Tactile mechanical switches with RGB backlighting, anti-ghosting, and a durable aluminum frame for serious gamers.'],
                ['Portable Bluetooth Speaker', 'Compact waterproof speaker with 360-degree surround sound, 12-hour playback, and USB-C charging for on-the-go listening.'],
                ['USB-C Laptop Docking Station', '7-in-1 USB-C hub with HDMI, USB 3.0, SD card reader, and 100W pass-through charging for laptop productivity.'],
            ],
            'Clothing & Apparel' => [
                ["Men's Merino Wool Crewneck Sweater", 'Soft and breathable 100% merino wool sweater, perfect for layering in cool weather. Available in multiple classic colors.'],
                ["Women's High-Waist Yoga Leggings", 'Four-way stretch fabric with moisture-wicking technology and a wide waistband for maximum comfort during workouts.'],
                ['Classic Slim-Fit Chinos', 'Versatile stretch cotton chinos with a modern slim fit, suitable for both office wear and casual outings.'],
                ['Puffer Winter Jacket', 'Lightweight yet warm puffer jacket with a water-resistant shell and a removable hood, ideal for cold climates.'],
                ['Unisex Cotton Graphic Tee', 'Pre-shrunk 100% organic cotton t-shirt with a unique artistic print. Comfortable everyday essential.'],
            ],
            'Home & Garden' => [
                ['Bamboo Cutting Board Set', 'Set of 3 eco-friendly bamboo cutting boards in different sizes, featuring juice grooves and non-slip feet.'],
                ['Stainless Steel Cookware Set', '10-piece tri-ply stainless steel cookware set with ergonomic handles and oven-safe construction up to 500 degrees.'],
                ['Solar-Powered Garden Lights', 'Pack of 8 weatherproof LED garden path lights that charge via solar panel and auto-illuminate at dusk.'],
                ['Memory Foam Pillow', 'Contour memory foam pillow with a cooling gel layer and removable washable cover for better sleep quality.'],
                ['Cordless Electric Screwdriver', 'Compact 3.6V cordless screwdriver with 6Nm torque, LED light, and a magnetic bit holder for home repairs.'],
            ],
            'Sports & Outdoors' => [
                ['4-Person Camping Tent', 'Lightweight 4-person dome tent with a rainfly, gear loft, and easy clip-pole setup system for weekend camping.'],
                ['Adjustable Dumbbell Set', 'Space-saving adjustable dumbbells ranging from 5 to 52.5 lbs with a quick-dial weight selection system.'],
                ['Hydration Running Vest', 'Lightweight running vest with a 1.5L water bladder, multiple pockets, and reflective strips for trail runners.'],
                ['Folding Camping Chair', 'Heavy-duty folding chair with a 300lb capacity, cup holder, side pocket, and a carry bag for outdoor events.'],
                ['Yoga Mat with Alignment Lines', 'Non-slip 6mm thick TPE yoga mat with alignment lines, extra-wide dimensions, and a carrying strap.'],
            ],
            'Health & Beauty' => [
                ['Vitamin C Serum 20%', 'Stabilized 20% Vitamin C face serum with hyaluronic acid and Vitamin E to brighten skin and reduce fine lines.'],
                ['Electric Facial Cleansing Brush', 'Silicone sonic facial brush with 3 speed settings for deep pore cleansing, gentle exfoliation, and massage.'],
                ['Collagen Peptide Powder', 'Unflavored grass-fed collagen peptides powder that dissolves easily in hot or cold beverages to support joints and skin.'],
                ['Natural Deodorant Stick', '72-hour odor protection natural deodorant made with baking soda-free formula, suitable for sensitive skin.'],
                ['Aromatherapy Essential Oil Diffuser', 'Ultrasonic cool mist diffuser with 7 LED color modes and auto-shutoff for home aromatherapy and humidification.'],
            ],
            'Toys & Games' => [
                ['STEM Building Blocks Set', '500-piece magnetic building blocks set designed to foster creativity and engineering skills in children aged 6 and up.'],
                ['Strategy Board Game', 'Award-winning strategy board game for 2-4 players featuring area control, resource management, and replayable scenarios.'],
                ['Remote Control Off-Road Car', 'High-speed 1:16 scale RC car with 4WD, independent suspension, and 30+ mph top speed for off-road adventures.'],
                ['Wooden Puzzle 1000 Pieces', '1000-piece premium wooden jigsaw puzzle featuring vibrant nature artwork, packaged in a reusable keepsake box.'],
                ["Kids' Art Supply Kit", 'Comprehensive 80-piece art kit including colored pencils, watercolors, markers, and sketch pads for young artists.'],
            ],
            'Books & Media' => [
                ['The Art of Strategic Thinking', 'A best-selling business and leadership book covering mental models, decision frameworks, and long-term thinking strategies.'],
                ['Learn Python in 30 Days', 'Beginner-friendly programming guide with hands-on projects, exercises, and a companion website for aspiring developers.'],
                ['Classic World Atlas Hardcover', 'Beautifully illustrated hardcover world atlas with detailed maps, geographical facts, and cultural insights for every country.'],
                ['Mindfulness & Meditation Journal', 'Guided daily mindfulness journal with prompts, gratitude exercises, and weekly reflection pages for mental well-being.'],
                ['Documentary Box Set: Nature Series', "6-disc Blu-ray documentary box set narrated by award-winning filmmakers, capturing Earth's most breathtaking ecosystems."],
            ],
            'Automotive' => [
                ['Car Dash Cam 4K', 'Front and rear 4K dual-channel dash cam with night vision, GPS logging, Wi-Fi connectivity, and loop recording.'],
                ['Portable Car Jump Starter', '2000A peak lithium jump starter pack for up to 8L gas and 6L diesel engines, with USB charging ports and LED flashlight.'],
                ['Seat Cover Set Universal Fit', 'Full set of waterproof seat covers in premium leatherette with universal fit for most sedans, SUVs, and trucks.'],
                ['Tire Inflator Cordless', 'Portable cordless tire inflator with digital pressure gauge, preset inflation, and auto-shutoff for cars and bikes.'],
                ['Car Organizer Trunk Storage', 'Collapsible trunk organizer with 4 compartments, non-slip base, and carry handles for road trips and grocery runs.'],
            ],
            'Food & Beverages' => [
                ['Organic Green Tea (100 bags)', 'USDA-certified organic green tea bags sourced from Japanese mountain farms, rich in antioxidants and subtle umami flavor.'],
                ['Cold Brew Coffee Concentrate', 'Ready-to-dilute cold brew coffee concentrate made from single-origin beans, smooth and low-acidity for iced coffee lovers.'],
                ['Mixed Nuts Gift Tin', 'Premium roasted and lightly salted mixed nuts tin including cashews, almonds, pecans, and macadamia nuts.'],
                ['Hot Sauce Variety Pack', 'Set of 5 artisanal hot sauces ranging from mild to extra hot, crafted from fresh peppers with no artificial additives.'],
                ['Protein Bar Box (24-Pack)', 'High-protein snack bars with 20g protein each, low sugar, and available in chocolate peanut butter and vanilla almond flavors.'],
            ],
            'Office Supplies' => [
                ['Ergonomic Mesh Office Chair', 'Fully adjustable mesh office chair with lumbar support, 4D armrests, and a breathable back for all-day comfort.'],
                ['Standing Desk Converter', 'Pneumatic height-adjustable desk converter with a large work surface and a monitor riser for sit-stand workflows.'],
                ['Multi-Function Laser Printer', 'Compact wireless all-in-one laser printer with print, scan, and copy functions, supporting mobile printing via app.'],
                ['Hardcover Dotted Notebook A5', 'Premium lay-flat hardcover notebook with 200 dotted pages, a ribbon bookmark, and an elastic closure band.'],
                ['Desk Organizer with Wireless Charger', 'Modern desktop organizer with compartments for pens and clips, and a 15W wireless charging pad built into the base.'],
            ],
        ];

        $category = fake()->randomElement(array_keys($catalog));
        [$name, $description] = fake()->randomElement($catalog[$category]);

        return [
            'sku' => fake()->unique()->bothify('SKU-####??'),
            'name' => $name,
            'category' => $category,
            'description' => $description,
            'price' => fake()->randomFloat(2, 10, 500),
            'stock' => fake()->numberBetween(0, 1000),
            'reorder_level' => fake()->numberBetween(5, 50),
            'status' => fake()->randomElement(['active', 'inactive', 'discontinued']),
        ];
    }
}
