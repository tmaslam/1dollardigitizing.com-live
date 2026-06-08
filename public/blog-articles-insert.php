<?php
// One-time blog article insertion script — deletes itself after running.
// Token: change this to invalidate the script if needed.
define('SECRET', 'b1d-insert-2026-a16a17');

if (($_GET['t'] ?? '') !== SECRET) {
    http_response_code(404);
    exit('Not found');
}

// Parse .env for DB credentials
$envPath = __DIR__ . '/../.env';
if (!file_exists($envPath)) {
    exit('ERROR: .env not found');
}

$env = [];
foreach (file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
    $line = trim($line);
    if ($line === '' || $line[0] === '#') continue;
    if (strpos($line, '=') === false) continue;
    [$k, $v] = explode('=', $line, 2);
    $v = trim($v, '"\'');
    $env[trim($k)] = $v;
}

$dsn  = 'mysql:host=' . ($env['DB_HOST'] ?? '127.0.0.1')
      . ';port=' . ($env['DB_PORT'] ?? '3306')
      . ';dbname=' . ($env['DB_DATABASE'] ?? '')
      . ';charset=utf8mb4';

try {
    $pdo = new PDO($dsn, $env['DB_USERNAME'] ?? '', $env['DB_PASSWORD'] ?? '', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);
} catch (Exception $e) {
    exit('DB connection failed: ' . $e->getMessage());
}

$results = [];
$now = date('Y-m-d H:i:s');
$today = date('Y-m-d');

// ── Article 16 ────────────────────────────────────────────────────────────────
$title16 = 'How to Calculate Stitch Counts for Custom Hats and Jackets';
$exists16 = $pdo->prepare("SELECT COUNT(*) FROM blogs WHERE title = ?");
$exists16->execute([$title16]);
if ((int) $exists16->fetchColumn() > 0) {
    $results[] = 'Article 16: already exists — skipped.';
} else {
    $content16 = <<<'HTML'
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

    $slug16 = 'how-to-calculate-stitch-counts-for-custom-hats-and-jackets';
    $slugCheck = $pdo->prepare("SELECT COUNT(*) FROM blogs WHERE slug = ?");
    $slugCheck->execute([$slug16]);
    if ((int) $slugCheck->fetchColumn() > 0) {
        $slug16 .= '-2';
    }

    $stmt = $pdo->prepare("INSERT INTO blogs
        (title, slug, excerpt, content, hero_image, hero_image_alt, author_name, category, tags, status,
         meta_title, meta_description, og_image, published_at, date, description, created_at, updated_at)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        $title16,
        $slug16,
        'Estimating stitch counts before placing a digitizing order lets you quote clients accurately and check delivered files. Key variables: design size, fill coverage, stitch density, and specialty elements like 3D puff or appliqué.',
        $content16,
        '',
        'Embroidery stitch count estimation for custom hats and jackets',
        '1 Dollar Digitizing',
        'Digitizing Tips',
        'stitch count, embroidery, hats, jackets, cap front, left chest, digitizing',
        'draft',
        'How to Calculate Stitch Counts for Custom Hats and Jackets',
        'Learn to estimate embroidery stitch counts before digitizing. Use this simple formula to quote clients, plan production time, and evaluate delivered files.',
        null,
        null,
        $today,
        'Estimating stitch counts before placing a digitizing order lets you quote clients accurately and check delivered files.',
        $now,
        $now,
    ]);
    $results[] = 'Article 16: inserted (ID ' . $pdo->lastInsertId() . ')';
}

// ── Article 17 ────────────────────────────────────────────────────────────────
$title17 = 'Flat Rate vs. Stitch Count Pricing — Which Is Better for Your Embroidery Shop?';
$exists17 = $pdo->prepare("SELECT COUNT(*) FROM blogs WHERE title = ?");
$exists17->execute([$title17]);
if ((int) $exists17->fetchColumn() > 0) {
    $results[] = 'Article 17: already exists — skipped.';
} else {
    $content17 = <<<'HTML'
<p>Most shops end up in a pricing model by accident. They found a digitizing service early on, it worked well enough, and they never really stopped to ask whether the pricing structure was doing them any favors. This article is that question.</p>

<p>There are two ways digitizing services charge you. Flat rate: one price per job, typically somewhere between $10 and $25, no matter how complicated the design is. Or per-stitch: a rate per thousand stitches, usually $3 to $6 on quality manual work. They feel similar from a distance. The difference gets real pretty fast once you look at what your shop actually runs through in a given month.</p>

<h2>What You're Really Buying with Flat Rate</h2>

<p>Flat rate sells simplicity as much as it sells digitizing. One price, every job, no surprises on the invoice. If your bread and butter is 20 corporate left-chest logos a month — polo shirts, 8,000 to 12,000 stitches, nothing wild — flat rate probably makes complete sense for you. Predictable monthly digitizing spend, easy to bake into client pricing, no invoice that comes back higher because something was more involved than it looked.</p>

<p>That predictability is genuinely valuable. Don't underestimate it, especially if you're still getting your footing with cash flow and quoting.</p>

<p>The problem shows up at the edges. Every flat rate service has a complexity ceiling — they just don't always advertise where it is. Send a 90,000-stitch jacket back to a $15-per-design service and one of three things tends to happen: they charge extra (which quietly turns "flat rate" into something of a misnomer), they do it at the flat rate and the quality suffers, or they tell you it's out of scope. Finding out which scenario you're dealing with after you've already committed to a client deadline is a rough position to be in.</p>

<h2>What You're Actually Paying For with Stitch Count Pricing</h2>

<p>Per-stitch pricing is proportional, which is the main thing to understand about it. A 10,000-stitch left chest logo costs less than a 50,000-stitch athletic design. A 150,000-stitch jacket back costs more than both. That's not complicated — it reflects the actual work involved. The digitizer spends more time on the complex job, and you pay more for it.</p>

<p>The friction is variability. If you don't have a habit of estimating stitch counts before you order, invoices can come in higher than you expected. That's a workflow issue more than a pricing issue, but it's a real friction point for shops that haven't built stitch count awareness into how they operate.</p>

<p>Once you do know your numbers — and after a few months of recording stitch counts from delivered files, you'll have a solid feel for them — per-stitch pricing becomes very transparent. You can estimate your cost before placing the order. You know what you're paying for before you commit to it.</p>

<h2>What Actually Determines Which Model Works for You</h2>

<p>It comes down to your design mix, pretty much entirely.</p>

<p>Shops running simple, predictable work at consistent size ranges do fine on flat rate. Corporate uniform programs, school apparel, basic team setups — if that describes 80% of your volume, flat rate is probably both cheaper and easier to manage. There's no argument to be made against it for that kind of shop.</p>

<p>Shops with real design variety are a different story. Left-chest logos on Monday, full jacket backs midweek, multi-element patches at the end of the week — wide complexity range means you'll end up either overpaying on flat rate for simple work or accepting compromised quality on the complex stuff. Neither outcome is a good deal.</p>

<p>Here's a concrete example. Take a shop with this monthly output:</p>

<ul>
  <li>15 simple left-chest logos at roughly 10,000 stitches each</li>
  <li>5 multi-color mid-complexity logos at around 25,000 stitches each</li>
  <li>2 jacket backs at around 120,000 stitches each</li>
</ul>

<p>Flat rate at $15 per design comes to about $330 — but the jacket backs will almost certainly trigger surcharges or quality issues, so that number isn't actually real.</p>

<p>Stitch count at $4 per 1,000: $600 for the left-chest work, $500 for the mid-complexity logos, $960 for the jacket backs. Total comes to around $2,060.</p>

<p>Now split the work: simple logos to a flat rate service ($225), everything else on per-stitch pricing ($1,460). Total lands at $1,685, with better quality on the jobs that needed it most.</p>

<p>That's not hypothetical — plenty of shops run two service relationships, each used for what it's actually good at.</p>

<h2>Questions Worth Asking About Your Current Setup</h2>

<p>Are you actually satisfied with the quality on your most complex jobs? Not the simple stuff; that tends to come out fine on any model. Your hardest jobs, the most detailed or the highest stitch count. If the honest answer is anything less than yes, the pricing structure you're using for those jobs is probably part of the reason.</p>

<p>Do you know the stitch counts on your typical designs? If not, pull a month's worth of delivered files and write them down. One afternoon of work and you'll have a clear picture of your actual complexity distribution — that information is worth more than any generic pricing advice.</p>

<p>Are you sending complex work to a flat rate service out of habit, even though you've had some quality concerns along the way? It's one of the more common patterns in shops that have been running for a while. It's also one of the more expensive ones, because the cost doesn't show up on the digitizing invoice — it shows up in client complaints and reprints.</p>

<p>Neither model is universally better. The right one is the model that fits the work you actually do. A lot of shops would make a different choice if they stopped to look at the question directly instead of just staying with whatever they started with.</p>
HTML;

    $slug17 = 'flat-rate-vs-stitch-count-pricing-which-is-better-for-your-embroidery-shop';
    $slugCheck2 = $pdo->prepare("SELECT COUNT(*) FROM blogs WHERE slug = ?");
    $slugCheck2->execute([$slug17]);
    if ((int) $slugCheck2->fetchColumn() > 0) {
        $slug17 .= '-2';
    }

    $stmt2 = $pdo->prepare("INSERT INTO blogs
        (title, slug, excerpt, content, hero_image, hero_image_alt, author_name, category, tags, status,
         meta_title, meta_description, og_image, published_at, date, description, created_at, updated_at)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt2->execute([
        $title17,
        $slug17,
        'Most shops fall into a pricing model by accident. This article breaks down flat rate vs. stitch count pricing — what each model actually costs, where each one fails, and how to know which one fits your shop.',
        $content17,
        '',
        'Flat rate vs stitch count pricing for embroidery digitizing',
        '1 Dollar Digitizing',
        'Business Tips',
        'pricing, flat rate, stitch count, digitizing, embroidery business',
        'draft',
        'Flat Rate vs. Stitch Count Pricing for Embroidery Shops',
        'Flat rate or per-stitch pricing — which digitizing model fits your shop? We break down the real costs, tradeoffs, and which setup works best by design mix.',
        null,
        null,
        $today,
        'Most shops fall into a pricing model by accident. This article breaks down flat rate vs. stitch count pricing.',
        $now,
        $now,
    ]);
    $results[] = 'Article 17: inserted (ID ' . $pdo->lastInsertId() . ')';
}

// ── Article 18 ────────────────────────────────────────────────────────────────
$title18 = 'How Outsourcing Digitizing Increases Daily Machine Run Time';
$exists18 = $pdo->prepare("SELECT COUNT(*) FROM blogs WHERE title = ?");
$exists18->execute([$title18]);
if ((int) $exists18->fetchColumn() > 0) {
    $results[] = 'Article 18: already exists — skipped.';
} else {
    $content18 = <<<'HTML'
<p>The most expensive thing in an embroidery shop isn't thread or hoops or even the machines themselves. It's a machine sitting still.</p>

<p>When a 15-head isn't running, you're losing somewhere between $40 and $70 per head per hour depending on what you're decorating. Real money leaving the table every time a needle stops moving — and it happens more often than most shop owners realize until they actually sit down and track it.</p>

<p>This is about where that idle time really comes from, and why outsourcing digitizing fixes more of it than most people expect.</p>

<h2>What Your Machines Are Doing When They're Not Running</h2>

<p>Ask most shop owners what's causing their downtime and they'll say changeovers, staffing, scheduling. Those are real. But spend a day watching the production floor and you'll see a different list.</p>

<p>Thread break. Operator walks over. Rethread. Find where it broke. Re-hoop if needed. Restart. That's 5 to 10 minutes gone. On a busy day it happens six, eight, ten times. Per head.</p>

<p>Trim buildup from a file with sloppy pathing, so somebody has to stand there babysitting the machine between elements instead of loading the next frame on a different head.</p>

<p>The morning wait — digitizing isn't done yet, so nothing starts at shift open. Maybe 30 minutes. Maybe an hour. Every day.</p>

<p>The end-of-day wall — tomorrow's jobs aren't prepped because your digitizer was also managing production and ran out of hours.</p>

<p>None of that makes the headline cost list. It doesn't show up as a line item. It just quietly eats your machine capacity day after day.</p>

<h2>The In-House Digitizing Math Most Shops Don't Run</h2>

<p>In-house digitizing looks cheap because the obvious costs are low — software, maybe a course, and the time of someone already on payroll. But that calculation misses the real number, which is what that person isn't doing while they're digitizing.</p>

<p>If your lead operator or production manager spends two hours a day at the computer, and your machines run at $45 per head per hour on a 10-head, you're looking at $900 in potential machine revenue that never got captured. Every single day. Five days a week: $4,500. Per month: $18,000. Not as a hard loss — as capacity that never got used.</p>

<p>And that's before quality enters the picture.</p>

<p>In-house digitizers — even experienced ones — aren't touching hundreds of designs a month the way a specialist service does. That skill gap shows up in production: more thread breaks, more trimming problems, more re-runs on difficult fabrics. A 50-piece re-run because the file didn't hold on the customer's fleece isn't just a materials problem. It's 50 pieces of machine time that got consumed twice.</p>

<h2>How Cleaner Files Change What Your Machines Can Actually Do</h2>

<p>The clearest place to see this is operator-to-head ratio.</p>

<p>When a file throws frequent thread breaks and generates trim buildup, one operator can realistically manage maybe 4 to 6 heads before something gets missed or a piece gets damaged. The other heads are either waiting or running unsupervised in ways that create quality problems down the line.</p>

<p>When a file runs cleanly — well-pathed, minimal jumps, density that suits the fabric — one operator can manage 10, 12, sometimes 15 heads. Loading finished pieces on one end, staying ahead of the queue on the other. The machine isn't waiting for the operator. The operator is staying ahead of the machine.</p>

<p>On a 15-head running an 8-hour shift, the gap between 5-head and 12-head effective utilization is somewhere around 400 to 500 machine hours per month. At even modest decoration values, that's not a rounding error.</p>

<p>Thread break reduction alone is worth putting into numbers. Cutting from 6 breaks per day down to 2 — on a 10-head, at 5 to 8 minutes per break — recovers 200 to 400 minutes of run time daily. That's 3 to 6 extra production hours without changing anything else.</p>

<h2>Tracking What You're Actually Losing</h2>

<p>Before assuming outsourced digitizing will help, spend a week recording what your machines are actually doing. Not what they're scheduled to do — what they're doing.</p>

<p>Track how many hours per day the needles are actually moving. Most shops, when they do this honestly for the first time, find their real utilization somewhere between 50% and 65% of available shift hours. Well-run operations hit 80% to 90%.</p>

<p>Then break down what's eating the gap. Changeovers are expected and don't compress much. But digitizing waits, thread break recovery, and trim-related operator attention are all compressible. In most shops, somewhere between 30% and 50% of non-changeover idle time has some connection to digitizing quality or availability.</p>

<p>Once you have that number, compare it against what professional outsourced digitizing actually costs at your volume. In nearly every case, the recovered machine time is worth significantly more than the digitizing cost — often by a factor of 5 to 10 or more.</p>

<h2>Making the Transition Work</h2>

<p>The workflow shift isn't complicated, but it needs some consistency to stick.</p>

<p>Get all new digitizing requests to your service by end of business each day, with files returned before the next morning's shift starts. That one change — files ready before production begins rather than during it — eliminates the reactive wait completely. Machines start running when the shift starts, not when the digitizing catches up.</p>

<p>Build a file library as designs come back. Most jobs get rerun. An organized archive by client and design name means you're paying for digitizing once per design, not every time the client reorders.</p>

<p>The hours that used to go toward in-house digitizing don't disappear — they go somewhere else. More floor time, more heads per operator, better attention on complex jobs. That reallocation is usually where shops feel the biggest difference day to day.</p>

<p>Your machines are what the whole business runs on. Finding more capacity doesn't always mean buying another one. Sometimes it means looking at what's stopping the ones you have from running at what they're actually capable of.</p>
HTML;

    $slug18 = 'how-outsourcing-digitizing-increases-daily-machine-run-time';
    $slugCheck3 = $pdo->prepare("SELECT COUNT(*) FROM blogs WHERE slug = ?");
    $slugCheck3->execute([$slug18]);
    if ((int) $slugCheck3->fetchColumn() > 0) {
        $slug18 .= '-2';
    }

    $stmt3 = $pdo->prepare("INSERT INTO blogs
        (title, slug, excerpt, content, hero_image, hero_image_alt, author_name, category, tags, status,
         meta_title, meta_description, og_image, published_at, date, description, created_at, updated_at)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt3->execute([
        $title18,
        $slug18,
        'A machine sitting still is the most expensive thing in your shop. This article breaks down where idle time actually comes from — and how outsourcing digitizing fixes more of it than most shop owners expect.',
        $content18,
        '',
        'Embroidery machine run time and production efficiency through outsourced digitizing',
        '1 Dollar Digitizing',
        'Business Tips',
        'outsourcing, digitizing, machine run time, production efficiency, embroidery business',
        'draft',
        'How Outsourcing Digitizing Increases Machine Run Time',
        'A stopped machine is your biggest cost. Learn how outsourcing digitizing cuts idle time, thread breaks, and morning waits to maximize your daily machine capacity.',
        null,
        null,
        $today,
        'A machine sitting still is the most expensive thing in your shop. Learn how outsourcing digitizing recovers lost machine capacity.',
        $now,
        $now,
    ]);
    $results[] = 'Article 18: inserted (ID ' . $pdo->lastInsertId() . ')';
}

// Self-delete
@unlink(__FILE__);

echo '<pre>' . implode("\n", $results) . "\nDone. Script deleted itself.</pre>";
