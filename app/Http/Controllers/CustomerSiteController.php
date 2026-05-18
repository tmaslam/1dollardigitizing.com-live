<?php

namespace App\Http\Controllers;

use App\Support\PortalMailer;
use App\Support\PublicSitePricing;
use App\Support\SiteContext;
use App\Support\SignupOfferService;
use App\Support\CustomerPublicRateLimit;
use App\Support\EmailValidation;
use App\Support\TurnstileVerifier;
use Illuminate\Http\Request;

class CustomerSiteController extends Controller
{
    private const SERVICE_PAGES = [
        'embroidery-digitizing' => [
            'title' => 'Custom Embroidery Digitizing Service',
            'image' => '/images/embroidery-digitizing-services-1.webp',
            'banner_image' => '/images/banner-embroidery-%20digitizing-%20services.webp',
            'page_heading' => 'Professional Embroidery Digitizing — Starting at $1',
            'meta_description' => 'Custom embroidery digitizing from $1 per design. Production-ready DST, PES, EXP, VP3 files. 12-hour turnaround, free revisions, satisfaction guaranteed.',
            'schema_price' => '1.00',
            'schema_price_spec' => 'Starting at $1.00 per 1,000 stitches. $6 minimum per design.',
            'paragraphs' => [
                'Embroidery digitizing takes your logo or artwork and turns it into a stitch file the machine can actually run. The tricky part isn\'t the conversion itself — it\'s all the decisions behind it. Stitch density, underlay type, pull compensation, path sequencing. Get those wrong and the design puckers, bleeds color, or breaks needles mid-run. Get them right and it comes off the machine looking exactly like the original artwork.',
                'Every file we build is done by hand. No auto-digitizing software, no batch processing. A real digitizer looks at your design, considers the fabric and machine type, and builds the file accordingly. We deliver in whatever format you need — DST for Tajima, PES for Brother, VP3 for Pfaff/Husqvarna, EXP for Melco, JEF for Janome, XXX for Singer, and more.',
                'Standard turnaround is 24 hours. We also do 12-hour Priority and 8-hour Super Rush for tight deadlines. Every order comes with free revisions — if the file doesn\'t stitch right because of how we built it, we fix it. No argument, no charge.',
                'Simple chest logos, complex multi-color jacket backs, lettering on cap brims, team uniforms — we\'ve done all of it. Over a million designs since 2005. Send us the artwork and tell us what you\'re working with.',
            ],
            'service_offers_title' => 'File formats included with every order:',
            'service_offers' => [
                'DST (Tajima) — industry standard for commercial machines',
                'PES (Brother) — for Brother and Babylock machines',
                'EXP (Melco) — for Melco and Ameco machines',
                'VP3 (Pfaff) — for Pfaff and Husqvarna Viking machines',
                'HUS, JEF, XXX, SEW — all other formats available on request',
            ],
            'gallery_columns' => 3,
            'hide_highlights' => true,
            'gallery_images' => [
                ['src' => '/images/embroidery-digitizing-services-1.webp', 'alt' => 'Custom embroidery digitizing — left chest logo on polo shirt'],
                ['src' => '/images/embroidery-digitizing-services-2.webp', 'alt' => 'Production-ready DST embroidery file output for Tajima commercial machines'],
                ['src' => '/images/embroidery-digitizing-services-3.webp', 'alt' => 'Multi-color embroidery design digitized for commercial embroidery machines'],
            ],
        ],
        '3d-puff-embroidery-digitizing' => [
            'title' => '3D Puff Embroidery Digitizing',
            'image' => '/images/3d-puff-embroidery-digitizing-services-1.webp',
            'banner_image' => '/images/banner-3d-puff-embroidery.webp',
            'page_heading' => '3D Puff Embroidery Digitizing — Bold Raised Designs for Caps & Apparel',
            'meta_description' => '3D puff embroidery digitizing for caps, jackets and hoodies. Correct foam specification, clean satin edges, no blowout. Starting at $1, fast turnaround.',
            'schema_price' => '1.00',
            'schema_price_spec' => 'Starting at $1.00 per 1,000 stitches. $6 minimum per design.',
            'paragraphs' => [
                '3D puff embroidery raises your design off the surface using foam beneath the stitches. It\'s what you see on premium snapbacks and structured caps — the bold, dimensional look that makes a logo pop from across the room. Flat embroidery can\'t replicate it. The foam creates the height; the digitizing determines whether it actually looks good.',
                'Most puff problems come from the digitizing, not the machine. Foam blowout at the edges usually means the satin angles weren\'t right. Foam showing through means the density was too low. The design leaning or collapsing means the stitch direction wasn\'t planned for the raised surface. Our digitizers have built tens of thousands of puff files and know exactly how to avoid all of it.',
                'We spec the correct foam thickness for your design size, angle the satin stitches to fully cover the edges, and set density tight enough that no foam bleeds through. The result is a clean, sharp-edged raised design that holds its shape through washing and wear.',
            ],
            'content_blocks' => [
                [
                    'title' => 'Best garments for 3D puff embroidery:',
                    'list' => [
                        'Snapback and fitted caps — the classic puff application',
                        'Hoodies and sweatshirts — chest and sleeve logos',
                        'Jackets and outerwear — bold brand statements',
                        'Sports uniforms — team names and numbers',
                        'Workwear and hi-vis garments — company branding',
                    ],
                ],
                [
                    'title' => 'What makes our 3D puff digitizing different:',
                    'list' => [
                        'Correct foam thickness specification included with every file',
                        'Satin stitches precisely angled for complete foam coverage',
                        'No foam blowout — edges are always clean and sharp',
                        'Optimized stitch density to prevent show-through',
                        'Compatible with all commercial embroidery machines',
                        'Free revisions until the design stitches perfectly',
                    ],
                ],
            ],
            'gallery_columns' => 3,
            'hide_highlights' => true,
            'gallery_images' => [
                ['src' => '/images/3d-puff-embroidery-digitizing-services-1.webp', 'alt' => '3D puff embroidery digitizing — raised logo on structured snapback cap'],
                ['src' => '/images/3d-puff-embroidery-digitizing-services-2.webp', 'alt' => '3D puff embroidery sample — clean foam coverage with sharp satin edges'],
                ['src' => '/images/3d-puff-embroidery-digitizing-services-3.webp', 'alt' => '3D puff cap embroidery — bold raised lettering on fitted hat'],
            ],
        ],
        'applique-embroidery-digitizing' => [
            'title' => 'Applique Embroidery Digitizing',
            'image' => '/images/applique-embroidery-digitizing-1.webp',
            'banner_image' => '/images/banner-applique-embroidery-digitizing%20.webp',
            'page_heading' => 'Applique Embroidery Digitizing — Save Thread, Reduce Time, Keep Quality',
            'meta_description' => 'Applique embroidery digitizing with precise placement stitches, clean tack-down runs, and sharp satin borders. Affordable from $1 per design. Fast turnaround.',
            'schema_price' => '2.00',
            'schema_price_spec' => 'Starting at $2.00 per 1,000 stitches. $6 minimum per design.',
            'paragraphs' => [
                'Applique works by sewing fabric directly onto the garment instead of filling the entire shape with stitches. A large logo that would take 40,000 stitches as a fill might take 8,000 as applique — same visual coverage, fraction of the run time. That difference matters on a production run.',
                'The digitizing has to be sequenced correctly or the whole thing falls apart. Placement stitch first — marks where the fabric goes. Tack-down next — locks it flat without distorting it. Satin border last — covers the raw edge clean. Miss any step or get the order wrong and you\'re re-doing the piece. We\'ve done this enough times to know what works.',
                'Applique is the right call for large jacket backs, big team logos, and anything where stitch count is driving up your cost per piece. The fabric-on-fabric look also has a premium quality to it that dense fill can\'t match. If you\'re not sure whether applique makes sense for your design, send it over and we\'ll tell you.',
            ],
            'content_blocks' => [
                [
                    'title' => 'Why choose applique over fill stitch:',
                    'list' => [
                        'Drastically reduces stitch count on large fills — saving time and thread',
                        'Faster machine run time means lower production cost per piece',
                        'Creates a premium fabric-on-fabric aesthetic',
                        'Less stress on the garment — ideal for lightweight fabrics',
                        'Works perfectly on jackets, bags, hats, and uniforms',
                    ],
                ],
                [
                    'title' => 'Our three-step applique digitizing process:',
                    'list' => [
                        'Step 1 — Placement stitch: marks exactly where the fabric piece goes',
                        'Step 2 — Tack-down stitch: secures the applique fabric flat to the garment',
                        'Step 3 — Satin border: covers the raw edge for a clean, professional finish',
                    ],
                ],
            ],
            'gallery_columns' => 3,
            'hide_highlights' => true,
            'gallery_images' => [
                ['src' => '/images/applique-embroidery-digitizing-1.webp', 'alt' => 'Applique embroidery digitizing — fabric placement with clean satin border finish'],
                ['src' => '/images/applique-embroidery-digitizing-2.webp', 'alt' => 'Team jacket back applique — large logo with secure tack-down stitching'],
                ['src' => '/images/applique-embroidery-digitizing-3.webp', 'alt' => 'Applique embroidery sample — multi-fabric design with precise edge coverage'],
            ],
        ],
        'chain-stitch-embroidery-digitizing' => [
            'title' => 'Chain Stitch Embroidery Digitizing',
            'image' => '/images/Chain-Stitch-Embroidery-Digitizing(1).webp',
            'banner_image' => '/images/banner-chain-stich-embroidery%20.webp',
            'page_heading' => 'Chain Stitch Embroidery Digitizing — Classic Style, Expert Execution',
            'meta_description' => 'Chain stitch embroidery digitizing for vintage logos, denim and workwear. Correct stitch path sequencing for single-needle machines. Affordable and fast.',
            'schema_price' => '1.50',
            'schema_price_spec' => 'Starting at $1.50 per 1,000 stitches. $6 minimum per design.',
            'paragraphs' => [
                'Chain stitch has a looped, rope-like texture that you can feel as much as see. Each stitch hooks into the one before it, forming a continuous chain. It\'s the look you see on vintage workwear, premium denim, and western-style garments — something that reads as handcrafted because the technique itself is different from anything a standard multi-needle machine produces.',
                'Digitizing for chain stitch is its own discipline. Unlike regular embroidery, a chain stitch machine can\'t jump between sections. The entire design has to be planned as a single continuous path — stitch direction, travel routes, sequencing all worked out from the beginning. If the path logic is wrong, the machine either jams or produces a mess. Most standard digitizers don\'t know how to do this correctly.',
                'Our chain stitch files are built for single-needle chainstitch machines, with clean path logic and correct stitch length throughout. The file runs smooth, the texture comes out even, and the result looks exactly how the technique is supposed to look.',
            ],
            'content_blocks' => [
                [
                    'title' => 'Best applications for chain stitch embroidery:',
                    'list' => [
                        'Vintage and retro brand logos — the authentic heritage look',
                        'Denim jackets and workwear — the classic chain stitch canvas',
                        'Premium garment branding — elevated tactile quality',
                        'Western and Americana-style designs',
                        'Decorative lettering and script work',
                    ],
                ],
            ],
            'hide_highlights' => true,
            'gallery_images' => [
                ['src' => '/images/Chain-Stitch-Embroidery-Digitizing(1).webp', 'alt' => 'Chain stitch embroidery digitizing — vintage logo on denim jacket'],
                ['src' => '/images/Chain-Stitch-Embroidery-Digitizing(2).webp', 'alt' => 'Chain stitch embroidery sample — decorative script lettering with looped texture'],
            ],
        ],
        'photo-digitizing' => [
            'title' => 'Photo Digitizing Service',
            'image' => '/images/Photo-Digitizing-Services-1.webp',
            'banner_image' => '/images/banner-photo%20-digitizing-services.webp',
            'page_heading' => 'Photo Digitizing — Turn Photographs into Embroidery-Ready Designs',
            'meta_description' => 'Photo digitizing service for portraits, pet photos, memorials and detailed artwork. Expert conversion to clean embroidery designs. Custom quote required.',
            'paragraphs' => [
                'Photo digitizing isn\'t the same as logo digitizing. A logo has clean lines and defined shapes — you can trace it. A photograph has gradients, shadows, skin tones, and depth that a machine can\'t reproduce stitch-for-stitch. The job is to look at the photo, understand what makes it recognizable, and reconstruct that in stitches at a size that actually reads on a garment.',
                'We\'ve done portraits, pet photos, memorial designs, and detailed illustrations. The process is always the same: study the image, simplify what can\'t translate to thread, preserve what gives the likeness its character. It\'s not auto-trace. It\'s decision-making, and it takes someone who understands both embroidery and what makes a face or animal recognizable.',
                'Before we start, we look at your photo and tell you what\'s realistic. Some images work better than others at typical embroidery sizes. If yours needs a different approach or a size adjustment, we\'ll tell you upfront — not after we\'ve already built something that doesn\'t work.',
            ],
            'service_offers_title' => 'Photo and artwork services we offer:',
            'service_offers' => [
                'Portrait and face digitizing',
                'Pet photo digitizing',
                'Memorial and tribute designs',
                'Logo vectorization from photos',
                'Logo cleanup and redraw',
                'Photo restoration',
                'Color separation for screen printing',
                'Custom logo creation from brief',
            ],
            'gallery_columns' => 3,
            'hide_highlights' => true,
            'gallery_images' => [
                ['src' => '/images/Photo-Digitizing-Services-1.webp', 'alt' => 'Photo digitizing service — portrait converted to embroidery-ready stitches'],
                ['src' => '/images/Photo-Digitizing-Services-3.webp', 'alt' => 'Pet photo digitizing — dog portrait recreated as detailed embroidery design'],
                ['src' => '/images/Photo-Digitizing-Services-2.webp', 'alt' => 'Photo digitizing sample — detailed facial features rendered in embroidery stitches'],
            ],
        ],
        'vector-art' => [
            'title' => 'Vector Art Conversion Service',
            'image' => '/images/vector-art-services-1.webp',
            'banner_image' => '/images/banner-vector-art-services%20.webp',
            'page_heading' => 'Vector Art Conversion — Clean, Scalable Files for Any Medium',
            'meta_description' => 'Vector art conversion service — raster images to clean AI, EPS, SVG or PDF. Perfect for screen printing, DTG, vinyl cutting and embroidery. From $6/hour.',
            'schema_price' => '6.00',
            'schema_price_spec' => '$6.00 per hour. Custom quotes available for complex artwork.',
            'paragraphs' => [
                'A JPG or PNG is made of pixels. Zoom in far enough and it falls apart. A vector file is built from paths — math, not pixels — so it scales to any size without losing sharpness. Same file, business card or building wrap, it\'ll be clean either way. That\'s why print shops, sign makers, and screen printers all ask for vector.',
                'We redraw logos and artwork manually — no auto-trace. Auto-trace produces hundreds of messy anchor points, rough edges, and artifacts that any production vendor is going to push back on. Manual redraw takes longer but produces a file that\'s actually usable: clean paths, correct color separations, organized layers.',
                'If you\'re sending artwork to a screen printer, vinyl cutter, laser engraver, embroidery shop, or sign maker and they\'ve asked for a vector file — this is what you need. Send us whatever you have, even a low-res JPG, and we\'ll build a proper vector from it.',
            ],
            'content_blocks' => [
                [
                    'title' => 'What we convert to vector:',
                    'list' => [
                        'Logos from photos or scanned images',
                        'Hand-drawn artwork and rough sketches',
                        'Low-resolution or blurry raster images',
                        'Old or corrupted vector files that need a full rebuild',
                        'Complex illustrations requiring careful manual redraw',
                    ],
                ],
                [
                    'title' => 'Vector output formats we deliver:',
                    'list' => [
                        'AI (Adobe Illustrator) — for print studios and designers',
                        'EPS — universal vector format accepted everywhere',
                        'SVG — for web, cutting machines and laser engravers',
                        'PDF — print-ready with embedded fonts and paths',
                    ],
                ],
            ],
            'hide_highlights' => true,
            'gallery_images' => [
                ['src' => '/images/vector-art-services-1.webp', 'alt' => 'Vector art conversion — raster logo redrawn as clean scalable AI vector file'],
                ['src' => '/images/vector-art-services-2.webp', 'alt' => 'Vector art service sample — hand-drawn artwork converted to professional EPS vector'],
            ],
        ],
        'hat-cap-digitizing' => [
            'title' => 'Hat & Cap Embroidery Digitizing',
            'image' => '/images/hat-cap-digitizing-1.webp',
            'banner_image' => '/images/banner-hat-cap-digitizing.webp',
            'page_heading' => 'Hat & Cap Embroidery Digitizing — Built for the Curve',
            'meta_description' => 'Hat and cap embroidery digitizing from $1. Correct underlay for curved surfaces, structured and unstructured caps, all machine formats. 24-hour turnaround.',
            'schema_price' => '1.00',
            'schema_price_spec' => 'Starting at $1.00 per 1,000 stitches. $6 minimum per design.',
            'paragraphs' => [
                'Cap embroidery is not the same job as flat garment work. The curved surface, the way caps hoop, the compression against the brim — all of it changes how a design stitches out. A file built for a jacket chest will gap, distort, or peel on a structured cap if the digitizing wasn\'t planned with the cap in mind from the start.',
                'Most caps have a readable front panel between 2.5 and 4 inches wide. At that size, fine details close up fast. Thin serifs blur together. Multi-element logos become unreadable. The job is knowing what simplifies cleanly and what holds at cap scale — so the logo still reads correctly on the finished hat, not just in the preview.',
                'We build cap files with heavier underlay to stabilize the fabric before the top stitches go down, orient stitch direction to follow the crown curve rather than fight it, and optimize density for the specific cap fabric you\'re running. Tell us the cap type and we\'ll set the file up accordingly. Structured front, unstructured dad hat, fitted stretch twill — each one gets a different approach.',
            ],
            'service_offers_title' => 'Cap styles we regularly digitize for:',
            'service_offers' => [
                'Structured snapbacks — the most common cap embroidery application',
                'Fitted caps — 6-panel, flexfit, and stretch twill styles',
                'Dad hats — unstructured cotton, lighter underlay required',
                'Trucker caps — foam front, mesh back, medium structured panels',
                'Beanies and knit caps — tension considerations differ from woven caps',
                'Bucket hats — front, side, and rear panel placements',
            ],
            'content_blocks' => [
                [
                    'title' => 'Cap digitizing problems we solve:',
                    'list' => [
                        'Designs pulling or distorting toward the brim',
                        'Small text that blurs together below half an inch',
                        'Stitches gapping or misaligning across the center seam',
                        'Thread breaking on stiff or structured front panels',
                        'Files that run fine on a flat frame but fail on a cap frame',
                    ],
                ],
                [
                    'title' => 'What\'s included with every cap digitizing order:',
                    'list' => [
                        'Correct underlay weight and type for your cap fabric',
                        'Stitch direction planned for the curved surface',
                        'Topping recommendation when required for texture',
                        'All machine formats — DST, PES, EXP, VP3, JEF and more',
                        'Free revisions if the file needs adjustment after test sew',
                    ],
                ],
            ],
            'gallery_columns' => 3,
            'hide_highlights' => true,
            'gallery_images' => [
                ['src' => '/images/hat-cap-digitizing-1.webp', 'alt' => 'Hat cap embroidery digitizing — structured snapback with clean logo placement'],
                ['src' => '/images/hat-cap-digitizing-2.webp', 'alt' => 'Cap digitizing sample — fitted hat with multi-color logo, correct underlay for curved surface'],
                ['src' => '/images/hat-cap-digitizing-3.webp', 'alt' => 'Dad hat embroidery digitizing — unstructured cap with left-panel text logo'],
            ],
        ],
        'left-chest-digitizing' => [
            'title' => 'Left Chest Logo Digitizing',
            'image' => '/images/left-chest-digitizing-1.webp',
            'banner_image' => '/images/banner-left-chest-digitizing.webp',
            'page_heading' => 'Left Chest Logo Digitizing — The Standard Placement, Done Right',
            'meta_description' => 'Left chest embroidery digitizing from $1. Correct sizing, fabric-specific underlay, small-detail optimization for polos, dress shirts, jackets and workwear.',
            'schema_price' => '1.00',
            'schema_price_spec' => 'Starting at $1.00 per 1,000 stitches. $6 minimum per design.',
            'paragraphs' => [
                'The left chest is the most common embroidery placement in corporate, workwear, and uniform programs. The standard area is roughly 3.5 inches wide, sometimes up to 4, positioned above the left pocket or above the heart. It goes on polos, dress shirts, jackets, fleece vests, and hi-vis gear. Small as it sounds, it\'s one of the harder placements to get right — because every detail in the original logo gets compressed into a space where fine lines either hold or they don\'t.',
                'Polo shirts and woven dress shirts behave differently under the hoop. Polo pique has give and can distort under tension if the underlay isn\'t right. Woven oxford fabric is tighter and needs a denser foundation stitch to stabilize it before the top stitches go down. The same digitized file won\'t perform identically on both fabrics. That\'s something the digitizing should account for — not something you discover on the machine.',
                'We size the design for the stated placement area, simplify details that won\'t survive compression to that scale, and set underlay and density for the fabric type you specify. If you\'re running the same logo on multiple garment types — polo, fleece, jacket — we can build adjusted versions of the file so each one is actually optimized for what it\'s going on.',
            ],
            'service_offers_title' => 'What\'s included with left chest digitizing:',
            'service_offers' => [
                'Correct sizing for standard 3.5" × 3.5" or custom placement areas',
                'Fabric-specific underlay and density settings on request',
                'Small-text and fine-detail legibility check before delivery',
                'Logo simplification recommendations when details are too fine to hold',
                'All machine formats included — DST, PES, EXP, VP3, JEF and more',
                'Free revisions if the stitching needs adjustment',
            ],
            'content_blocks' => [
                [
                    'title' => 'Garment types we optimize left chest logos for:',
                    'list' => [
                        'Polo shirts — pique and jersey knit, accounts for fabric stretch',
                        'Dress and oxford shirts — tightly woven, higher density and underlay',
                        'Performance and moisture-wicking fabrics — lower density to avoid bleed-through',
                        'Fleece jackets and soft shells — heavier underlay to stabilize the pile',
                        'Vests and branded workwear — standard placement with consistent margins',
                        'Hi-vis and safety workwear — optimized for reflective and mesh panels',
                    ],
                ],
            ],
            'gallery_columns' => 3,
            'hide_highlights' => true,
            'gallery_images' => [
                ['src' => '/images/left-chest-digitizing-1.webp', 'alt' => 'Left chest logo digitizing — corporate polo shirt with embroidered company logo'],
                ['src' => '/images/left-chest-digitizing-2.webp', 'alt' => 'Left chest embroidery sample — dress shirt with small multi-color logo placement'],
                ['src' => '/images/left-chest-digitizing-3.webp', 'alt' => 'Workwear left chest embroidery — hi-vis vest with clean logo at standard 3.5 inch width'],
            ],
        ],
        'patch-digitizing' => [
            'title' => 'Embroidered Patch Digitizing',
            'image' => '/images/patch-digitizing-1.webp',
            'banner_image' => '/images/banner-patch-digitizing.webp',
            'page_heading' => 'Embroidered Patch Digitizing — Built for the Patch, Not the Garment',
            'meta_description' => 'Embroidered patch digitizing from $1. Iron-on, sew-on, military spec, and chenille patches. Correct density, border planning, and merrowed-edge support.',
            'schema_price' => '1.00',
            'schema_price_spec' => 'Starting at $1.00 per 1,000 stitches. $6 minimum per design.',
            'paragraphs' => [
                'Patches are digitized differently from garment embroidery, and most shops find this out the hard way. On a garment, the hoop holds the fabric under tension and the backing stabilizes it. On a patch, you\'re stitching onto felt, twill, or organza — and the whole thing has to hold together as a standalone object. The rules around density, sequencing, and border handling are different.',
                'Stitch density can go higher on patches because there\'s no garment compression pulling against the design. But the sequencing has to be right: background fill first, then the design elements on top, then the border last — always the border last. The border path also has to be planned for the finish, whether that\'s a merrowed edge, a folded edge, or die-cut. A file that ignores the border finish will have exposed raw fabric at the edge or thread that catches during finishing.',
                'We\'ve built iron-on patches, sew-on patches, military specification patches, chenille patches, and embroidered badges. Each type has its own requirements. Tell us the backing material, the edge finish, and how the patch will be attached, and we\'ll build the file to match — not a generic patch file that needs to be reworked.',
            ],
            'service_offers_title' => 'Patch types we digitize:',
            'service_offers' => [
                'Iron-on embroidered patches — heat seal backing, correct density for adhesion',
                'Sew-on patches for uniforms and workwear',
                'Military specification patches — precise border handling and color accuracy',
                'Chenille patches — outline digitizing for the chenille fill application',
                'Embroidered badges and insignia in any shape',
                'Merrowed-edge patches with border path planning included',
            ],
            'content_blocks' => [
                [
                    'title' => 'How patch digitizing differs from garment work:',
                    'list' => [
                        'Higher stitch density — no garment compression fighting the fill',
                        'Sequencing order matters: background, elements, border — always in that order',
                        'Border path planned for merrowed, folded, or die-cut edge finish',
                        'Backing material changes underlay type and pull compensation settings',
                        'No jump stitches visible on the back — patch underside is often exposed',
                    ],
                ],
                [
                    'title' => 'Patch backing materials we build files for:',
                    'list' => [
                        'Felt — standard backing for most decorative and fashion patches',
                        'Twill — more durable, the go-to for uniform and workwear patches',
                        'Organza — semi-transparent backing used for overlay and lace-style patches',
                        'Heat-seal and iron-on backing — specify at time of order',
                        'Velcro attachment patches — no change to digitizing, handled at finishing',
                    ],
                ],
            ],
            'gallery_columns' => 3,
            'hide_highlights' => true,
            'gallery_images' => [
                ['src' => '/images/patch-digitizing-1.webp', 'alt' => 'Embroidered patch digitizing — custom iron-on patch with clean merrowed border'],
                ['src' => '/images/patch-digitizing-2.webp', 'alt' => 'Military specification patch digitizing — precise embroidered badge with accurate color'],
                ['src' => '/images/patch-digitizing-3.webp', 'alt' => 'Sew-on embroidered patch sample — twill backing with high-density fill and logo detail'],
            ],
        ],
        'jacket-back-digitizing' => [
            'title' => 'Jacket Back Embroidery Digitizing',
            'image' => '/images/jacket-back-digitizing-1.webp',
            'banner_image' => '/images/banner-jacket-back-digitizing.webp',
            'page_heading' => 'Jacket Back Embroidery Digitizing — Large Format, No Shortcuts',
            'meta_description' => 'Jacket back embroidery digitizing from $1. Large format designs for varsity jackets, outerwear and team apparel. Correct fill sequencing, color planning, fabric-specific settings.',
            'schema_price' => '1.00',
            'schema_price_spec' => 'Starting at $1.00 per 1,000 stitches. $6 minimum per design.',
            'paragraphs' => [
                'Jacket backs are the largest, most complex embroidery jobs most shops run. The design area is typically 10 to 13 inches across — sometimes bigger on extended sizes — and at that scale, every decision made during digitizing shows up clearly in the finished piece. Stitch direction, how fills are broken into sections, color sequencing — you can see every shortcut in the finished jacket.',
                'Large fill areas have to be split into directional sections to prevent fabric pull and distortion across the back panel. The direction of each section needs to flow with adjacent sections, not clash against them. Color sequencing has to be planned to minimize unnecessary machine stops. On a design with eight or ten colors, a poorly planned sequence can add fifteen to twenty minutes per jacket to the production run — which matters a lot when you\'re running fifty pieces.',
                'We\'ve digitized varsity jacket backs, motorcycle club patches, team outerwear, concert merchandise, and corporate jacket programs. A varsity design looks and stitches completely differently from a biker jacket. We plan the approach based on the actual design and the garment fabric — not a one-size template that gets applied to everything.',
            ],
            'service_offers_title' => 'Jacket back applications we regularly digitize:',
            'service_offers' => [
                'Varsity and letterman jacket designs — wool body, leather sleeve fabrics',
                'Team and sports outerwear programs',
                'Motorcycle club and biker jacket patches',
                'Corporate and branded jacket programs',
                'Concert and event merchandise outerwear',
                'Custom large-format embroidery designs of any shape',
            ],
            'content_blocks' => [
                [
                    'title' => 'Why large jacket backs need specialist digitizing:',
                    'list' => [
                        'Fill areas split into directional sections to prevent back-panel pull',
                        'Color sequence planned to cut unnecessary machine stops',
                        'Jump stitches minimized across large travel distances in the design',
                        'Outerwear fabrics — nylon, polyester, fleece — each need different density settings',
                        'Multi-hooping guidance provided when the design exceeds standard hoop size',
                    ],
                ],
                [
                    'title' => 'Outerwear types we\'ve built jacket back files for:',
                    'list' => [
                        'Varsity jackets — wool body, leather sleeves, high-tension surface',
                        'Nylon and polyester windbreakers — lower density to prevent show-through',
                        'Fleece and softshell jackets — heavier underlay to stabilize the pile',
                        'Leather jackets — topping required, lower stitch speed recommended',
                        'Bomber jackets and stadium coats',
                        'Hi-vis outerwear — standard density, avoid stitching over reflective tape',
                    ],
                ],
            ],
            'gallery_columns' => 3,
            'hide_highlights' => true,
            'gallery_images' => [
                ['src' => '/images/jacket-back-digitizing-1.webp', 'alt' => 'Jacket back embroidery digitizing — large team logo on varsity jacket'],
                ['src' => '/images/jacket-back-digitizing-2.webp', 'alt' => 'Jacket back embroidery sample — motorcycle club patch with bold fill and clean border'],
                ['src' => '/images/jacket-back-digitizing-3.webp', 'alt' => 'Corporate jacket back digitizing — branded outerwear with multi-color large format design'],
            ],
        ],
    ];

    public function home(Request $request)
    {
        /** @var SiteContext $site */
        $site = $request->attributes->get('siteContext');

        return view('customer.site-home', [
            'site' => $site,
            'customerPortalEnabled' => (bool) config('sites.customer_portal_enabled', false),
            'signupOfferSummary' => SignupOfferService::offerSummary(SignupOfferService::activeSignupOffer($site)),
        ]);
    }

    public function workProcess(Request $request)
    {
        return view('public.work-process', [
            'site' => $request->attributes->get('siteContext'),
        ]);
    }

    public function about(Request $request)
    {
        return view('public.about', [
            'site' => $request->attributes->get('siteContext'),
        ]);
    }

    public function quality(Request $request)
    {
        return view('public.quality', [
            'site' => $request->attributes->get('siteContext'),
        ]);
    }

    public function services(Request $request)
    {
        return view('public.services', [
            'site' => $request->attributes->get('siteContext'),
        ]);
    }

    public function servicePage(Request $request, string $section)
    {
        $service = self::SERVICE_PAGES[$section] ?? null;

        if (! $service) {
            abort(404);
        }

        return view('public.service-detail', [
            'site' => $request->attributes->get('siteContext'),
            'service' => array_merge(self::resolveServiceImages($service), ['slug' => $section]),
        ]);
    }

    public function pricing(Request $request)
    {
        /** @var SiteContext $site */
        $site = $request->attributes->get('siteContext');

        return view('public.pricing', [
            'site' => $site,
            'pricing' => PublicSitePricing::forSite($site),
        ]);
    }

    public function formats(Request $request)
    {
        return view('public.formats', [
            'site' => $request->attributes->get('siteContext'),
        ]);
    }

    public function paymentOptions(Request $request)
    {
        return redirect(url('/contact-us.php'));
    }

    public function robots(Request $request)
    {
        $body = implode("\n", [
            'User-agent: *',
            'Allow: /',
            'Sitemap: '.$this->absoluteUrl($request, '/sitemap.xml'),
            '',
        ]);

        return response($body, 200, [
            'Content-Type' => 'text/plain; charset=UTF-8',
        ]);
    }

    public function sitemap(Request $request)
    {
        return response()
            ->view('public.sitemap', [
                'urls' => $this->publicSiteUrls($request),
            ])
            ->header('Content-Type', 'application/xml; charset=UTF-8');
    }

    public function contact(Request $request)
    {
        return view('public.contact', [
            'site' => $request->attributes->get('siteContext'),
        ]);
    }

    public function privacyPolicy(Request $request)
    {
        return view('public.privacy-policy', [
            'site' => $request->attributes->get('siteContext'),
        ]);
    }

    public function terms(Request $request)
    {
        return view('public.terms', [
            'site' => $request->attributes->get('siteContext'),
        ]);
    }

    public function bookAMeeting(Request $request)
    {
        return view('public.book-a-meeting');
    }

    public function sendContact(Request $request)
    {
        /** @var SiteContext $site */
        $site = $request->attributes->get('siteContext');

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'email' => ['required', EmailValidation::rule(), 'max:190'],
            'company' => ['nullable', 'string', 'max:150'],
            'phone' => ['nullable', 'string', 'max:50'],
            'subject' => ['required', 'string', 'max:180'],
            'message' => ['required', 'string', 'max:5000'],
            'website_url' => ['nullable', 'string', 'max:1'],
        ]);

        if (trim((string) ($validated['website_url'] ?? '')) !== '') {
            return back()->with('success', 'Thanks. Your message has been received.');
        }

        if (! TurnstileVerifier::verify($request, 'public-contact')) {
            return back()->withErrors(['contact' => 'Please complete the security verification and try again.'])->withInput();
        }

        if (CustomerPublicRateLimit::tooManyAttempts($request, 'contact', $site->legacyKey, strtolower(trim((string) $validated['email'])), 5, 600)) {
            return back()->withErrors(['contact' => 'Too many messages were sent from this connection. Please try again later.'])->withInput();
        }

        $recipient = (string) config('mail.admin_alert_address', $site->supportEmail);
        $subject = '['.$site->displayLabel().'] '.trim((string) $validated['subject']);
        $body = view('customer.emails.contact-message', [
            'siteContext' => $site,
            'payload' => array_merge($validated, [
                'ip_address' => (string) ($request->ip() ?? '127.0.0.1'),
            ]),
        ])->render();

        $sent = PortalMailer::sendHtml($recipient, $subject, $body);

        return $sent
            ? back()->with('success', 'Thanks. Your message has been received.')
            : back()->withErrors(['contact' => 'We could not send your message right now. Please try again or email support directly.']);
    }

    private function publicSiteUrls(Request $request): array
    {
        $urls = [
            ['path' => '/', 'changefreq' => 'weekly', 'priority' => '1.0'],
            ['path' => '/about-us.php', 'changefreq' => 'monthly', 'priority' => '0.6'],
            ['path' => '/our-quality.php', 'changefreq' => 'monthly', 'priority' => '0.7'],
            ['path' => '/work-process.php', 'changefreq' => 'monthly', 'priority' => '0.7'],
            ['path' => '/price-plan.php', 'changefreq' => 'weekly', 'priority' => '0.9'],
            ['path' => '/formats.php', 'changefreq' => 'monthly', 'priority' => '0.7'],
            ['path' => '/contact-us.php', 'changefreq' => 'monthly', 'priority' => '0.7'],
            ['path' => '/privacy-policy.php', 'changefreq' => 'yearly', 'priority' => '0.3'],
            ['path' => '/terms.php', 'changefreq' => 'yearly', 'priority' => '0.3'],
        ];

        foreach (array_keys(self::SERVICE_PAGES) as $slug) {
            $urls[] = [
                'path' => '/'.$slug.'.php',
                'changefreq' => 'weekly',
                'priority' => '0.8',
            ];
        }

        return array_map(function (array $url) use ($request): array {
            return [
                'loc' => $this->absoluteUrl($request, $url['path']),
                'changefreq' => $url['changefreq'],
                'priority' => $url['priority'],
            ];
        }, $urls);
    }

    private static function resolveServiceImages(array $service): array
    {
        foreach (['image', 'banner_image'] as $key) {
            if (! empty($service[$key])) {
                $service[$key] = url($service[$key]);
            }
        }

        if (! empty($service['gallery_images'])) {
            $service['gallery_images'] = array_map(function (array $img): array {
                return ['src' => url($img['src']), 'alt' => $img['alt']];
            }, $service['gallery_images']);
        }

        return $service;
    }

    private function absoluteUrl(Request $request, string $path): string
    {
        $base = rtrim(url('/'), '/');

        if ($path === '' || $path === '/') {
            return $base.'/';
        }

        return $base.'/'.ltrim($path, '/');
    }
}
