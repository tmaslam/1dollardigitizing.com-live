<?php

namespace App\Console\Commands;

use App\Models\Blog;
use Illuminate\Console\Command;

class InsertBlogArticles extends Command
{
    protected $signature   = 'blog:insert-articles';
    protected $description = 'One-time insert of blog articles 16, 17 and 18 as drafts.';

    public function handle(): int
    {
        $this->insertArticle16();
        $this->insertArticle17();
        $this->insertArticle18();
        return self::SUCCESS;
    }

    private function insertArticle16(): void
    {
        $title = 'How to Calculate Stitch Counts for Custom Hats and Jackets';
        if (Blog::where('title', $title)->exists()) {
            $this->info('Article 16: already exists — skipped.');
            return;
        }

        $content  = '<p>Before you even send artwork to a digitizer, you can get a surprisingly accurate stitch count estimate by factoring in design size, how much of that area is solid fill, stitch density, and whether anything like 3D puff or appliqué is involved. Left chest logos typically land between 8,000 and 15,000 stitches. Cap fronts usually come in at 5,000 to 12,000. Full jacket backs? Anywhere from 50,000 to 250,000, depending on how complex the design is.</p>';
        $content .= '<h2>Why This Skill Pays for Itself</h2>';
        $content .= '<p>Every embroidery shop owner knows the situation. A client sends over a logo and asks for a quote on 200 embroidered hats — and they need the number today. You want to give them a real price, but your decoration cost depends on machine time, machine time depends on stitch count, and you don\'t have the digitized file yet.</p>';
        $content .= '<p>The shops that quote confidently without hesitation aren\'t guessing. They\'ve learned to estimate stitch counts from the artwork alone. It\'s not magic, and you don\'t need specialized digitizing software to do it. What you need is a solid understanding of how design size, fill coverage, and density relate to each other — plus a couple of simple formulas you can run in your head.</p>';
        $content .= '<h2>The Core Formula</h2>';
        $content .= '<p>For fill stitch areas, this is the starting point:</p>';
        $content .= '<p><em>Stitch count = (Fill area in mm²) ÷ (Density in mm) × (Average stitch length in mm)</em></p>';
        $content .= '<p>Standard fill at 0.40mm density with a 4mm average stitch length gives you 4 ÷ 0.40 = <strong>10 stitches per mm²</strong>. That\'s the number worth memorizing. Ten stitches per square millimeter of solid fill, at typical production density. Everything else builds from there.</p>';
        $content .= '<p>For outlines and running stitch details, the math is simpler: divide the total path length in mm by your average stitch length. At 2mm per stitch, that\'s one stitch for every two millimeters of line — or 0.5 stitches per mm.</p>';
        $content .= '<h2>Figuring Out Fill Coverage</h2>';
        $content .= '<p>This is honestly the trickiest part, because it varies so much between designs. You need to estimate what percentage of the design\'s bounding rectangle is actually covered by solid stitching — not the whole box, just the filled areas.</p>';
        $content .= '<p><strong>High fill (70–90% of the bounding area):</strong> Designs that are mostly solid — big filled text, logos made of thick color blocks.</p>';
        $content .= '<p><strong>Medium fill (40–60%):</strong> Mixed designs with a balance of filled elements and outlines, logos where there\'s meaningful open space between the color blocks.</p>';
        $content .= '<p><strong>Low fill (10–30%):</strong> Outline-heavy work, logos that are primarily running stitch, designs with a lot of negative space.</p>';
        $content .= '<p>You don\'t need to be precise here. Print the artwork at actual embroidery dimensions and eyeball it. Categorizing it as high, medium, or low is enough for a quoting estimate.</p>';
        $content .= '<h2>Real-World Stitch Count Ranges by Placement</h2>';
        $content .= '<h3>Cap Front</h3><p>The curved surface and small design area keep cap fronts simple by necessity. Most land between 50mm and 80mm wide.</p><ul><li>Simple outline or single-color text: 3,000–6,000</li><li>Standard multi-color logo with moderate fill: 6,000–12,000</li><li>Complex, dense logo: 12,000–20,000</li><li>3D puff elements: add 20–40% to the fill stitch counts</li></ul><p>At 800 stitches per minute, a 10,000-stitch cap runs about 12–13 minutes a piece.</p>';
        $content .= '<h3>Left Chest</h3><p>The workhorse placement. Most left chest designs are 80–100mm wide by 60–80mm tall.</p><ul><li>Simple text or single-element logo: 5,000–10,000</li><li>Standard corporate logo, 3–5 colors: 8,000–18,000</li><li>Complex logo with fine detail and many colors: 15,000–30,000</li></ul><p>At 800–1,000 SPM, a 12,000-stitch design runs about 12–15 minutes per piece.</p>';
        $content .= '<h3>Full Front or Full Back</h3><p>These accumulate stitches fast. Typical dimensions run 250–300mm wide.</p><ul><li>Simple text or outline-dominant design: 20,000–50,000</li><li>Medium complexity logo or graphic: 50,000–120,000</li><li>Dense, detailed design with heavy fill: 120,000–250,000</li></ul><p>A 150,000-stitch full back at 1,000 SPM is roughly 2.5 hours across a 10-head machine for a 25-piece run.</p>';
        $content .= '<h3>Jacket Back</h3><p>Same size range as full back, but jacket backs are commonly done with appliqué for the large fill elements. A design that would run 180,000 stitches as pure fill might drop to 55,000–70,000 stitches with appliqué construction.</p>';
        $content .= '<h3>Sleeve or Cuff</h3><p>Similar size to left chest, stitch counts are in roughly the same range or slightly below for equivalent complexity.</p>';
        $content .= '<h3>Pocket (Workwear, Staff Shirts)</h3><p>Also similar to left chest in terms of stitch counts. Additional fabric layers affect digitizing requirements but don\'t change the stitch count meaningfully.</p>';
        $content .= '<h2>A Fast Estimation Method for Quotes</h2>';
        $content .= '<ol><li>Lock in the actual embroidered dimensions.</li><li>Calculate the bounding area: width × height in mm.</li><li>Estimate coverage: High = 80%, Medium = 50%, Low = 20%.</li><li>Fill area = bounding area × coverage percentage.</li><li>Multiply fill area by 10 (stitches per mm²).</li><li>Add 20–30% as a running stitch allowance for outlines and details.</li></ol>';
        $content .= '<p>Example: A corporate logo at 90 × 70mm = 6,300mm² bounding area. Medium fill (50%) = 3,150mm² fill area. At 10 stitches per mm²: 31,500 fill stitches. Add 25% running stitch = roughly 39,000 stitches total.</p>';
        $content .= '<h2>Using Stitch Count to Check Delivered Files</h2>';
        $content .= '<p>Once the digitized file arrives, your embroidery software shows the exact stitch count. Comparing that to your pre-digitizing estimate is a quick sanity check.</p>';
        $content .= '<p>Actual count significantly higher than estimate: the file may be over-dense. Check density values in your software.</p>';
        $content .= '<p>Actual count close to estimate: the digitizing is probably solid.</p>';
        $content .= '<p>Actual count significantly lower: the file may be under-dense, which will show up as thin, gappy fill areas on the finished garment. Worth going back to your digitizer.</p>';
        $content .= '<h2>Stitch Count and Production Planning</h2>';
        $content .= '<ul><li>600 SPM (slow speed for difficult fabrics): 1,000 stitches ≈ 1 minute</li><li>800 SPM (standard production speed): 1,000 stitches ≈ 0.75 minutes</li><li>1,000 SPM (fast speed for simple designs): 1,000 stitches ≈ 0.6 minutes</li></ul>';
        $content .= '<p>These don\'t include color changes (10–20 seconds each) or trims (2–3 seconds each). For multi-head runs, multiply per-piece time by piece count and divide by active heads to get total machine time.</p>';
        $content .= '<h2>Wrapping Up</h2>';
        $content .= '<p>Stitch count estimation isn\'t complicated once you understand what drives it. You\'re really just answering two questions: how big is the design, and how much of that space is solid fill? From there, 10 stitches per mm² of fill coverage gets you to a working estimate that lets you quote jobs, plan production, and evaluate delivered files.</p>';

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
            'date'             => now()->format('Y-m-d'),
        ]);

        $this->info('Article 16: inserted.');
    }

    private function insertArticle17(): void
    {
        $title = 'Flat Rate vs. Stitch Count Pricing — Which Is Better for Your Embroidery Shop?';
        if (Blog::where('title', $title)->exists()) {
            $this->info('Article 17: already exists — skipped.');
            return;
        }

        $content  = '<p>Most shops end up in a pricing model by accident. They found a digitizing service early on, it worked well enough, and they never really stopped to ask whether the pricing structure was doing them any favors. This article is that question.</p>';
        $content .= '<p>There are two ways digitizing services charge you. Flat rate: one price per job, typically somewhere between $10 and $25, no matter how complicated the design is. Or per-stitch: a rate per thousand stitches, usually $3 to $6 on quality manual work. They feel similar from a distance. The difference gets real pretty fast once you look at what your shop actually runs through in a given month.</p>';
        $content .= '<h2>What You\'re Really Buying with Flat Rate</h2>';
        $content .= '<p>Flat rate sells simplicity as much as it sells digitizing. One price, every job, no surprises on the invoice. If your bread and butter is 20 corporate left-chest logos a month — polo shirts, 8,000 to 12,000 stitches, nothing wild — flat rate probably makes complete sense for you. Predictable monthly digitizing spend, easy to bake into client pricing, no invoice that comes back higher because something was more involved than it looked.</p>';
        $content .= '<p>That predictability is genuinely valuable. Don\'t underestimate it, especially if you\'re still getting your footing with cash flow and quoting.</p>';
        $content .= '<p>The problem shows up at the edges. Every flat rate service has a complexity ceiling — they just don\'t always advertise where it is. Send a 90,000-stitch jacket back to a $15-per-design service and one of three things tends to happen: they charge extra (which quietly turns "flat rate" into something of a misnomer), they do it at the flat rate and the quality suffers, or they tell you it\'s out of scope. Finding out which scenario you\'re dealing with after you\'ve already committed to a client deadline is a rough position to be in.</p>';
        $content .= '<h2>What You\'re Actually Paying For with Stitch Count Pricing</h2>';
        $content .= '<p>Per-stitch pricing is proportional, which is the main thing to understand about it. A 10,000-stitch left chest logo costs less than a 50,000-stitch athletic design. A 150,000-stitch jacket back costs more than both. That\'s not complicated — it reflects the actual work involved. The digitizer spends more time on the complex job, and you pay more for it.</p>';
        $content .= '<p>The friction is variability. If you don\'t have a habit of estimating stitch counts before you order, invoices can come in higher than you expected. That\'s a workflow issue more than a pricing issue, but it\'s a real friction point for shops that haven\'t built stitch count awareness into how they operate.</p>';
        $content .= '<p>Once you do know your numbers — and after a few months of recording stitch counts from delivered files, you\'ll have a solid feel for them — per-stitch pricing becomes very transparent. You can estimate your cost before placing the order.</p>';
        $content .= '<h2>What Actually Determines Which Model Works for You</h2>';
        $content .= '<p>It comes down to your design mix, pretty much entirely.</p>';
        $content .= '<p>Shops running simple, predictable work at consistent size ranges do fine on flat rate. Corporate uniform programs, school apparel, basic team setups — if that describes 80% of your volume, flat rate is probably both cheaper and easier to manage.</p>';
        $content .= '<p>Shops with real design variety are a different story. Wide complexity range means you\'ll end up either overpaying on flat rate for simple work or accepting compromised quality on the complex stuff. Neither outcome is a good deal.</p>';
        $content .= '<p>Here\'s a concrete example. Take a shop with this monthly output:</p>';
        $content .= '<ul><li>15 simple left-chest logos at roughly 10,000 stitches each</li><li>5 multi-color mid-complexity logos at around 25,000 stitches each</li><li>2 jacket backs at around 120,000 stitches each</li></ul>';
        $content .= '<p>Flat rate at $15 per design comes to about $330 — but the jacket backs will almost certainly trigger surcharges or quality issues, so that number isn\'t actually real.</p>';
        $content .= '<p>Stitch count at $4 per 1,000: $600 for the left-chest work, $500 for the mid-complexity logos, $960 for the jacket backs. Total: around $2,060.</p>';
        $content .= '<p>Split the work: simple logos to a flat rate service ($225), everything else on per-stitch pricing ($1,460). Total lands at $1,685, with better quality on the jobs that needed it most. That\'s not hypothetical — plenty of shops run two service relationships, each used for what it\'s actually good at.</p>';
        $content .= '<h2>Questions Worth Asking About Your Current Setup</h2>';
        $content .= '<p>Are you actually satisfied with the quality on your most complex jobs? Not the simple stuff; that tends to come out fine on any model. Your hardest jobs, the most detailed or the highest stitch count. If the honest answer is anything less than yes, the pricing structure you\'re using for those jobs is probably part of the reason.</p>';
        $content .= '<p>Do you know the stitch counts on your typical designs? If not, pull a month\'s worth of delivered files and write them down. One afternoon of work and you\'ll have a clear picture of your actual complexity distribution.</p>';
        $content .= '<p>Are you sending complex work to a flat rate service out of habit, even though you\'ve had some quality concerns? It\'s one of the more expensive habits a shop can have, because the cost doesn\'t show up on the digitizing invoice — it shows up in client complaints and reprints.</p>';
        $content .= '<p>Neither model is universally better. The right one is the model that fits the work you actually do.</p>';

        Blog::create([
            'title'            => $title,
            'slug'             => Blog::generateSlug($title),
            'excerpt'          => 'Most shops fall into a pricing model by accident. This article breaks down flat rate vs. stitch count pricing — what each model actually costs, where each one fails, and how to know which one fits your shop.',
            'content'          => $content,
            'hero_image'       => '',
            'hero_image_alt'   => 'Flat rate vs stitch count pricing for embroidery digitizing',
            'author_name'      => '1 Dollar Digitizing',
            'category'         => 'Business Tips',
            'tags'             => 'pricing, flat rate, stitch count, digitizing, embroidery business',
            'status'           => 'draft',
            'meta_title'       => 'Flat Rate vs. Stitch Count Pricing for Embroidery Shops',
            'meta_description' => 'Flat rate or per-stitch pricing — which digitizing model fits your shop? We break down the real costs, tradeoffs, and which setup works best by design mix.',
            'date'             => now()->format('Y-m-d'),
        ]);

        $this->info('Article 17: inserted.');
    }

    private function insertArticle18(): void
    {
        $title = 'How Outsourcing Digitizing Increases Daily Machine Run Time';
        if (Blog::where('title', $title)->exists()) {
            $this->info('Article 18: already exists — skipped.');
            return;
        }

        $content  = '<p>The most expensive thing in an embroidery shop isn\'t thread or hoops or even the machines themselves. It\'s a machine sitting still.</p>';
        $content .= '<p>When a 15-head isn\'t running, you\'re losing somewhere between $40 and $70 per head per hour depending on what you\'re decorating. Real money leaving the table every time a needle stops moving — and it happens more often than most shop owners realize until they actually sit down and track it.</p>';
        $content .= '<h2>What Your Machines Are Doing When They\'re Not Running</h2>';
        $content .= '<p>Ask most shop owners what\'s causing their downtime and they\'ll say changeovers, staffing, scheduling. Those are real. But spend a day watching the production floor and you\'ll see a different list.</p>';
        $content .= '<p>Thread break. Operator walks over. Rethread. Find where it broke. Re-hoop if needed. Restart. That\'s 5 to 10 minutes gone. On a busy day it happens six, eight, ten times. Per head.</p>';
        $content .= '<p>Trim buildup from a file with sloppy pathing, so somebody has to stand there babysitting the machine between elements instead of loading the next frame on a different head.</p>';
        $content .= '<p>The morning wait — digitizing isn\'t done yet, so nothing starts at shift open. Maybe 30 minutes. Maybe an hour. Every day.</p>';
        $content .= '<p>The end-of-day wall — tomorrow\'s jobs aren\'t prepped because your digitizer was also managing production and ran out of hours.</p>';
        $content .= '<p>None of that makes the headline cost list. It doesn\'t show up as a line item. It just quietly eats your machine capacity day after day.</p>';
        $content .= '<h2>The In-House Digitizing Math Most Shops Don\'t Run</h2>';
        $content .= '<p>In-house digitizing looks cheap because the obvious costs are low — software, maybe a course, and the time of someone already on payroll. But that calculation misses the real number, which is what that person isn\'t doing while they\'re digitizing.</p>';
        $content .= '<p>If your lead operator or production manager spends two hours a day at the computer, and your machines run at $45 per head per hour on a 10-head, you\'re looking at $900 in potential machine revenue that never got captured. Every single day. Five days a week: $4,500. Per month: $18,000. Not as a hard loss — as capacity that never got used.</p>';
        $content .= '<p>In-house digitizers — even experienced ones — aren\'t touching hundreds of designs a month the way a specialist service does. That skill gap shows up in production: more thread breaks, more trimming problems, more re-runs on difficult fabrics. A 50-piece re-run because the file didn\'t hold on the customer\'s fleece isn\'t just a materials problem. It\'s 50 pieces of machine time that got consumed twice.</p>';
        $content .= '<h2>How Cleaner Files Change What Your Machines Can Actually Do</h2>';
        $content .= '<p>The clearest place to see this is operator-to-head ratio.</p>';
        $content .= '<p>When a file throws frequent thread breaks and generates trim buildup, one operator can realistically manage maybe 4 to 6 heads before something gets missed. When a file runs cleanly — well-pathed, minimal jumps, density that suits the fabric — one operator can manage 10, 12, sometimes 15 heads.</p>';
        $content .= '<p>On a 15-head running an 8-hour shift, the gap between 5-head and 12-head effective utilization is somewhere around 400 to 500 machine hours per month. At even modest decoration values, that\'s not a rounding error.</p>';
        $content .= '<p>Thread break reduction alone is worth putting into numbers. Cutting from 6 breaks per day down to 2 — on a 10-head, at 5 to 8 minutes per break — recovers 200 to 400 minutes of run time daily. That\'s 3 to 6 extra production hours without changing anything else.</p>';
        $content .= '<h2>Tracking What You\'re Actually Losing</h2>';
        $content .= '<p>Before assuming outsourced digitizing will help, spend a week recording what your machines are actually doing. Not what they\'re scheduled to do — what they\'re doing.</p>';
        $content .= '<p>Track how many hours per day the needles are actually moving. Most shops find their real utilization somewhere between 50% and 65% of available shift hours. Well-run operations hit 80% to 90%.</p>';
        $content .= '<p>Then break down what\'s eating the gap. Digitizing waits, thread break recovery, and trim-related operator attention are all compressible. In most shops, 30% to 50% of non-changeover idle time has some connection to digitizing quality or availability.</p>';
        $content .= '<p>Once you have that number, compare it against what professional outsourced digitizing actually costs at your volume. In nearly every case, the recovered machine time is worth significantly more than the digitizing cost — often by a factor of 5 to 10 or more.</p>';
        $content .= '<h2>Making the Transition Work</h2>';
        $content .= '<p>Get all new digitizing requests to your service by end of business each day, with files returned before the next morning\'s shift starts. That one change eliminates the reactive wait completely. Machines start running when the shift starts, not when the digitizing catches up.</p>';
        $content .= '<p>Build a file library as designs come back. Most jobs get rerun. An organized archive by client and design name means you\'re paying for digitizing once per design, not every time the client reorders.</p>';
        $content .= '<p>The hours that used to go toward in-house digitizing don\'t disappear — they go somewhere else. More floor time, more heads per operator, better attention on complex jobs. That reallocation is usually where shops feel the biggest difference day to day.</p>';
        $content .= '<p>Your machines are what the whole business runs on. Finding more capacity doesn\'t always mean buying another one. Sometimes it means looking at what\'s stopping the ones you have from running at what they\'re actually capable of.</p>';

        Blog::create([
            'title'            => $title,
            'slug'             => Blog::generateSlug($title),
            'excerpt'          => 'A machine sitting still is the most expensive thing in your shop. This article breaks down where idle time actually comes from — and how outsourcing digitizing fixes more of it than most shop owners expect.',
            'content'          => $content,
            'hero_image'       => '',
            'hero_image_alt'   => 'Embroidery machine run time and production efficiency through outsourced digitizing',
            'author_name'      => '1 Dollar Digitizing',
            'category'         => 'Business Tips',
            'tags'             => 'outsourcing, digitizing, machine run time, production efficiency, embroidery business',
            'status'           => 'draft',
            'meta_title'       => 'How Outsourcing Digitizing Increases Machine Run Time',
            'meta_description' => 'A stopped machine is your biggest cost. Learn how outsourcing digitizing cuts idle time, thread breaks, and morning waits to maximize your daily machine capacity.',
            'date'             => now()->format('Y-m-d'),
        ]);

        $this->info('Article 18: inserted.');
    }
}
