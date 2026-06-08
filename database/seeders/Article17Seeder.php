<?php

namespace Database\Seeders;

use App\Models\Blog;
use Illuminate\Database\Seeder;

class Article17Seeder extends Seeder
{
    public function run(): void
    {
        $title = 'Flat Rate vs. Stitch Count Pricing — Which Is Better for Your Embroidery Shop?';

        if (Blog::where('title', $title)->exists()) {
            $this->command->info('Article 17 already exists — skipping.');
            return;
        }

        $content = <<<'HTML'
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
            'og_image'         => null,
            'published_at'     => null,
            'date'             => now()->format('Y-m-d'),
            'decription'       => 'Most shops fall into a pricing model by accident. This article breaks down flat rate vs. stitch count pricing.',
        ]);

        $this->command->info('Article 17 inserted as draft.');
    }
}
