<?php

namespace Database\Seeders;

use App\Models\Blog;
use Illuminate\Database\Seeder;

class Article16Seeder extends Seeder
{
    public function run(): void
    {
        $title = 'How to Calculate Stitch Counts for Custom Hats and Jackets';

        if (Blog::where('title', $title)->exists()) {
            $this->command->info('Article 16 already exists — skipping.');
            return;
        }

        $content = <<<'HTML'
<p>Before you even send artwork to a digitizer, you can get a surprisingly accurate stitch count estimate by factoring in design size, how much of that area is solid fill, stitch density, and whether anything like 3D puff or appliqué is involved. Left chest logos typically land between 8,000 and 15,000 stitches. Cap fronts usually come in at 5,000 to 12,000. Full jacket backs? Anywhere from 50,000 to 250,000, depending on how complex the design is.</p>

<h2>Why This Skill Pays for Itself</h2>

<p>Every embroidery shop owner knows the situation. A client sends over a logo and asks for a quote on 200 embroidered hats — and they need the number today. You want to give them a real price, but your decoration cost depends on machine time, machine time depends on stitch count, and you don't have the digitized file yet.</p>

<p>The shops that quote confidently without hesitation aren't guessing. They've learned to estimate stitch counts from the artwork alone. It's not magic, and you don't need specialized digitizing software to do it. What you need is a solid understanding of how design size, fill coverage, and density relate to each other — plus a couple of simple formulas you can run in your head.</p>

<h2>The Core Formula</h2>

<p>For fill stitch areas, this is the starting point:</p>

<p><em>Stitch count = (Fill area in mm²) ÷ (Density in mm) × (Average stitch length in mm)</em></p>

<p>Standard fill at 0.40mm density with a 4mm average stitch length gives you:</p>

<p>4 ÷ 0.40 = <strong>10 stitches per mm²</strong></p>

<p>That's the number worth memorizing. Ten stitches per square millimeter of solid fill, at typical production density. Everything else builds from there.</p>

<p>For outlines and running stitch details, the math is simpler: divide the total path length in mm by your average stitch length. At 2mm per stitch, that's one stitch for every two millimeters of line — or 0.5 stitches per mm.</p>

<h2>Figuring Out Fill Coverage</h2>

<p>This is honestly the trickiest part, because it varies so much between designs. You need to estimate what percentage of the design's bounding rectangle is actually covered by solid stitching — not the whole box, just the filled areas.</p>

<p>Rough categories to work with:</p>

<p><strong>High fill (70–90% of the bounding area):</strong> Designs that are mostly solid — big filled text, logos made of thick color blocks. A team name in block letters that dominates the entire design area is a good example.</p>

<p><strong>Medium fill (40–60%):</strong> Mixed designs with a balance of filled elements and outlines, logos where there's meaningful open space between the color blocks.</p>

<p><strong>Low fill (10–30%):</strong> Outline-heavy work, logos that are primarily running stitch, designs with a lot of negative space.</p>

<p>You don't need to be precise here. Print the artwork at actual embroidery dimensions and eyeball it. Categorizing it as high, medium, or low is enough for a quoting estimate.</p>

<h2>Real-World Stitch Count Ranges by Placement</h2>

<p>These aren't theoretical — they reflect the realistic range across most commercial orders at each placement. Unusually simple or unusually complex designs will land outside these windows, but the majority of everyday work falls in here.</p>

<h3>Cap Front</h3>
<p>The curved surface and small design area keep cap fronts simple by necessity. Most land between 50mm and 80mm wide.</p>
<ul>
  <li>Simple outline or single-color text: 3,000–6,000</li>
  <li>Standard multi-color logo with moderate fill: 6,000–12,000</li>
  <li>Complex, dense logo: 12,000–20,000</li>
  <li>3D puff elements: add 20–40% to the fill stitch counts (denser capping stitches)</li>
</ul>
<p>At 800 stitches per minute, a 10,000-stitch cap runs about 12–13 minutes a piece.</p>

<h3>Left Chest</h3>
<p>The workhorse placement. Most left chest designs are 80–100mm wide by 60–80mm tall.</p>
<ul>
  <li>Simple text or single-element logo: 5,000–10,000</li>
  <li>Standard corporate logo, 3–5 colors: 8,000–18,000</li>
  <li>Complex logo with fine detail and many colors: 15,000–30,000</li>
</ul>
<p>At 800–1,000 SPM, a 12,000-stitch design runs about 12–15 minutes per piece.</p>

<h3>Full Front or Full Back</h3>
<p>These accumulate stitches fast, especially with any real fill coverage. Typical dimensions run 250–300mm wide.</p>
<ul>
  <li>Simple text or outline-dominant design: 20,000–50,000</li>
  <li>Medium complexity logo or graphic: 50,000–120,000</li>
  <li>Dense, detailed design with heavy fill: 120,000–250,000</li>
</ul>
<p>A 150,000-stitch full back at 1,000 SPM is roughly 2.5 hours across a 10-head machine for a 25-piece run — that's a number worth having before you schedule production.</p>

<h3>Jacket Back</h3>
<p>Same size range as full back, but jacket backs are commonly done with appliqué for the large fill elements, which makes a huge difference. A design that would run 180,000 stitches as pure fill embroidery might drop to 55,000–70,000 stitches with appliqué construction.</p>

<h3>Sleeve or Cuff</h3>
<p>Similar size to left chest, stitch counts are in roughly the same range or slightly below for equivalent complexity.</p>

<h3>Pocket (Workwear, Staff Shirts)</h3>
<p>Also similar to left chest in terms of stitch counts. The additional fabric layers from the pocket itself affect how the file should be digitized (more aggressive underlay, sometimes slightly reduced density to keep the pocket from going rigid), but don't change the stitch count meaningfully.</p>

<h2>A Fast Estimation Method for Quotes</h2>

<p>For everyday quoting, here's the six-step process that gets you close enough without overthinking it:</p>

<ol>
  <li>Lock in the actual embroidered dimensions — not the artwork size, the size it'll actually sew out.</li>
  <li>Calculate the bounding area: width × height in mm.</li>
  <li>Estimate coverage: High = 80%, Medium = 50%, Low = 20%.</li>
  <li>Fill area = bounding area × coverage percentage.</li>
  <li>Multiply fill area by 10 (stitches per mm²).</li>
  <li>Add 20–30% of that fill count as a rough running stitch allowance for outlines and details.</li>
</ol>

<p>Walk-through example: A corporate logo at 90 × 70mm = 6,300mm² bounding area. Medium fill coverage (50%) gives 3,150mm² of fill. At 10 stitches per mm², that's 31,500 fill stitches. Add 25% for running stitches and you land at roughly 39,000 stitches.</p>

<p>Is that exactly what the digitized file will show? Maybe not. But for quoting purposes, estimates from this method typically land within 20–30% of the actual count — which is more than good enough to price a client job before spending money on digitizing.</p>

<h2>Using Stitch Count to Check Delivered Files</h2>

<p>Once the digitized file arrives, your embroidery software will show the exact stitch count. Comparing that to your pre-digitizing estimate is a quick sanity check on whether the file was digitized appropriately.</p>

<p>If the actual count is significantly higher than your estimate, the file may be over-dense — more stitches than the design actually needs. This sometimes happens with auto-digitizing tools that apply aggressive default density settings. Pull up the density values in your software and take a look.</p>

<p>If the count is close to your estimate, the digitizing is probably solid.</p>

<p>If the actual count is significantly lower, the file may be under-dense — not enough stitches to achieve full coverage, which will show up as thin, gappy fill areas on the finished garment. Worth going back to your digitizer.</p>

<p>You don't need to be a digitizing expert to run this check. You just need to know roughly what the design should require, which is exactly what this estimation method gives you.</p>

<h2>Stitch Count and Production Planning</h2>

<p>For scheduling, stitch count translates to run time pretty directly:</p>

<ul>
  <li>600 SPM (slower speed for difficult fabrics or fine detail): 1,000 stitches ≈ 1 minute</li>
  <li>800 SPM (standard production speed): 1,000 stitches ≈ 0.75 minutes</li>
  <li>1,000 SPM (fast speed for simple designs on stable fabric): 1,000 stitches ≈ 0.6 minutes</li>
</ul>

<p>These don't include color changes (10–20 seconds each), trims (2–3 seconds each), or any stops for thread breaks. For multi-head runs, multiply per-piece time by piece count and divide by active head count to get total machine time.</p>

<h2>Wrapping Up</h2>

<p>Stitch count estimation isn't complicated once you understand what drives it. You're really just answering two questions: how big is the design, and how much of that space is solid fill? From there, 10 stitches per mm² of fill coverage gets you to a working estimate.</p>

<p>That estimate lets you quote client jobs before committing digitizing costs, plan machine time before locking in your schedule, and quickly sense-check digitized files when they arrive. It's one of those foundational skills that quietly makes everything else in the shop run a little smoother.</p>
HTML;

        Blog::create([
            'title'            => $title,
            'slug'             => Blog::generateSlug($title),
            'excerpt'          => 'Estimating stitch counts before placing a digitizing order lets you quote clients accurately and check delivered files. Key variables: design size, fill coverage, stitch density, and specialty elements like 3D puff or appliqué.',
            'content'          => $content,
            'hero_image'       => '',
            'hero_image_alt'   => 'Embroidery stitch count estimation for custom hats and jackets',
            'author_name'      => '1 Dollar Digitizing',
            'category'         => 'Digitizing Tips',
            'tags'             => 'stitch count, embroidery, hats, jackets, cap front, left chest, digitizing',
            'status'           => 'draft',
            'meta_title'       => 'How to Calculate Stitch Counts for Custom Hats and Jackets',
            'meta_description' => 'Learn to estimate embroidery stitch counts before digitizing. Use this simple formula to quote clients, plan production time, and evaluate delivered files.',
            'og_image'         => null,
            'published_at'     => null,
            'date'             => now()->format('Y-m-d'),
            'decription'       => 'Estimating stitch counts before placing a digitizing order lets you quote clients accurately and check delivered files.',
        ]);

        $this->command->info('Article 16 inserted as draft.');
    }
}
