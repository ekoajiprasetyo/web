<?php
/**
 * single.php — Individual post template (Berita)
 *
 * Features:
 *  - Unified inner-page hero (title only, no subtitle)
 *  - Post meta bar: category, date, author + avatar, view count
 *  - Featured image full width
 *  - Two-column layout: article content + sticky TOC sidebar (desktop)
 *  - Mobile: collapsible TOC accordion above content
 *  - Tag pills
 *  - Share buttons (Facebook, Twitter/X, WhatsApp, Telegram, Copy URL)
 *  - Author bio card (shown when author has a bio)
 *  - Related posts (3 cards from same category)
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// ── Grab the post ─────────────────────────────────────────────────────────────
if ( ! have_posts() ) {
    get_header();
    get_footer();
    exit;
}
the_post();

// ── Track views BEFORE any output ────────────────────────────────────────────
sman1_track_post_views( get_the_ID() );

// ── Build data variables ──────────────────────────────────────────────────────
$post_id    = get_the_ID();

// Category
$cats       = get_the_category();
$cat_name   = ! empty( $cats ) ? $cats[0]->name : 'Berita';
$cat_url    = ! empty( $cats ) ? get_category_link( $cats[0]->term_id ) : '#';

// Date
$post_ts    = strtotime( get_the_date('Y-m-d') );
$date_str   = sman1_indo_date( $post_ts );

// Author
$author_id  = (int) get_the_author_meta('ID');
$author     = get_the_author_meta('display_name');
$author_bio = get_the_author_meta('description');
$avatar_url = get_avatar_url( $author_id, array( 'size' => 48 ) );

// Views
$views_str  = sman1_get_post_views( $post_id );

// TOC from raw content
$raw_content = get_the_content();
$toc_data    = sman1_generate_toc( $raw_content );
$has_toc     = ! empty( $toc_data['items'] );

// Share URLs
$share_url   = rawurlencode( get_permalink() );
$share_title = rawurlencode( get_the_title() );

// ── Build meta HTML for hero extra_html slot ────────────────────────────────
ob_start();
?>
<div class="iph-page-meta">
    <a href="<?php echo esc_url( $cat_url ); ?>" class="iph-page-meta-cat">
        <i class="fas fa-tag" aria-hidden="true"></i>
        <?php echo esc_html( $cat_name ); ?>
    </a>
    <span class="iph-page-meta-sep" aria-hidden="true"></span>
    <span class="iph-page-meta-item">
        <i class="fas fa-calendar-alt" aria-hidden="true"></i>
        <?php echo esc_html( $date_str ); ?>
    </span>
    <span class="iph-page-meta-sep" aria-hidden="true"></span>
    <span class="iph-page-meta-item">
        <img src="<?php echo esc_url( $avatar_url ); ?>"
             alt="<?php echo esc_attr( $author ); ?>"
             class="iph-page-meta-avatar"
             loading="lazy">
        <?php echo esc_html( $author ); ?>
    </span>
    <span class="iph-page-meta-sep" aria-hidden="true"></span>
    <span class="iph-page-meta-item">
        <i class="fas fa-eye" aria-hidden="true"></i>
        <?php echo esc_html( $views_str ); ?> dilihat
    </span>
</div>
<?php
$_post_extra = ob_get_clean();

// ── Header ───────────────────────────────────────────────────────────────────
get_header();

// ── Unified inner-page hero ───────────────────────────────────────────────────
get_template_part( 'template-parts/inner-page-hero', null, array(
    'eyebrow_icon' => 'fas fa-newspaper',
    'eyebrow_text' => $cat_name,
    'title'        => get_the_title(),
    'title_accent' => '',
    'description'  => '',
    'breadcrumb'   => $cat_name,
    'extra_html'   => $_post_extra,
) );
?>

<div class="single-post-wrap">
<div class="container">

    <!-- ═══════════════════════════════════════════════════
         FEATURED IMAGE
    ═══════════════════════════════════════════════════ -->
    <?php if ( has_post_thumbnail() ) : ?>
    <div class="spost-featured-img" data-aos="fade-up">
        <?php the_post_thumbnail( 'large', array(
            'alt'     => esc_attr( get_the_title() ),
            'loading' => 'eager',
        ) ); ?>
    </div>
    <?php endif; ?>

    <!-- ═══════════════════════════════════════════════════
         TWO-COLUMN LAYOUT  (article + TOC sidebar)
    ═══════════════════════════════════════════════════ -->
    <div class="spost-layout<?php echo $has_toc ? ' spost-has-toc' : ''; ?>">

        <!-- ─── MAIN ARTICLE ─────────────────────────── -->
        <article id="post-<?php the_ID(); ?>" <?php post_class('spost-content'); ?>>

            <!-- ─── Decorative top stripe ────────────── -->
            <div class="ppage-card-stripe" aria-hidden="true"></div>

            <!-- Mobile TOC accordion (visible only on small screens) -->
            <?php if ( $has_toc ) : ?>
            <div class="spost-toc-mobile" data-aos="fade-up">
                <button class="spost-toc-toggle" aria-expanded="false" aria-controls="spost-toc-mobile-body">
                    <span>
                        <i class="fas fa-list-ul" aria-hidden="true"></i>
                        Daftar Isi
                    </span>
                    <i class="fas fa-chevron-down spost-toc-chevron" aria-hidden="true"></i>
                </button>
                <nav id="spost-toc-mobile-body" class="spost-toc-body" aria-label="Daftar isi" hidden>
                    <?php echo $toc_data['toc_html']; ?>
                </nav>
            </div>
            <?php endif; ?>

            <!-- Body content -->
            <div class="spost-body-text ppage-body" data-aos="fade-up">
                <?php echo apply_filters( 'the_content', $toc_data['modified_content'] ); ?>
            </div>

            <!-- ── Tag pills ── -->
            <?php $tags = get_the_tags(); if ( $tags ) : ?>
            <div class="spost-tags">
                <i class="fas fa-hashtag" aria-hidden="true"></i>
                <?php foreach ( $tags as $tag ) : ?>
                <a href="<?php echo esc_url( get_tag_link( $tag ) ); ?>" class="spost-tag">
                    <?php echo esc_html( $tag->name ); ?>
                </a>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>

            <!-- ═══════════════════════════════════════════
                 SHARE BUTTONS  (req #3)
            ═══════════════════════════════════════════ -->
            <div class="spost-share" data-aos="fade-up">
                <p class="spost-share-label">
                    <i class="fas fa-share-alt" aria-hidden="true"></i>
                    Bagikan Artikel
                </p>
                <div class="spost-share-btns">

                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $share_url; ?>"
                       target="_blank" rel="noopener noreferrer"
                       class="spost-share-btn spost-share-fb"
                       aria-label="Bagikan ke Facebook">
                        <i class="fab fa-facebook-f" aria-hidden="true"></i>
                        <span>Facebook</span>
                    </a>

                    <a href="https://twitter.com/intent/tweet?url=<?php echo $share_url; ?>&text=<?php echo $share_title; ?>"
                       target="_blank" rel="noopener noreferrer"
                       class="spost-share-btn spost-share-tw"
                       aria-label="Bagikan ke Twitter/X">
                        <i class="fab fa-twitter" aria-hidden="true"></i>
                        <span>Twitter</span>
                    </a>

                    <a href="https://wa.me/?text=<?php echo $share_title; ?>%20<?php echo $share_url; ?>"
                       target="_blank" rel="noopener noreferrer"
                       class="spost-share-btn spost-share-wa"
                       aria-label="Bagikan ke WhatsApp">
                        <i class="fab fa-whatsapp" aria-hidden="true"></i>
                        <span>WhatsApp</span>
                    </a>

                    <a href="https://t.me/share/url?url=<?php echo $share_url; ?>&text=<?php echo $share_title; ?>"
                       target="_blank" rel="noopener noreferrer"
                       class="spost-share-btn spost-share-tg"
                       aria-label="Bagikan ke Telegram">
                        <i class="fab fa-telegram" aria-hidden="true"></i>
                        <span>Telegram</span>
                    </a>

                    <button type="button"
                            class="spost-share-btn spost-share-copy"
                            data-url="<?php echo esc_attr( get_permalink() ); ?>"
                            aria-label="Salin tautan artikel">
                        <i class="fas fa-link" aria-hidden="true"></i>
                        <span>Salin Tautan</span>
                    </button>

                </div><!-- .spost-share-btns -->
            </div><!-- .spost-share -->

            <!-- ── Author bio card (only when bio is set) ── -->
            <?php if ( $author_bio ) : ?>
            <div class="spost-author-card" data-aos="fade-up">
                <img src="<?php echo esc_url( $avatar_url ); ?>"
                     alt="<?php echo esc_attr( $author ); ?>"
                     class="spost-author-avatar"
                     loading="lazy">
                <div class="spost-author-info">
                    <span class="spost-author-role">Penulis</span>
                    <strong class="spost-author-name"><?php echo esc_html( $author ); ?></strong>
                    <p><?php echo esc_html( $author_bio ); ?></p>
                </div>
            </div>
            <?php endif; ?>

        </article><!-- .spost-content -->

        <!-- ─── DESKTOP TOC SIDEBAR ──────────────────── -->
        <?php if ( $has_toc ) : ?>
        <aside class="spost-sidebar" aria-label="Navigasi Artikel">
            <div class="spost-toc-desktop sticky-toc">
                <div class="spost-toc-header">
                    <i class="fas fa-list-ul" aria-hidden="true"></i>
                    Daftar Isi
                </div>
                <nav class="spost-toc-body" aria-label="Daftar isi">
                    <?php echo $toc_data['toc_html']; ?>
                </nav>
            </div>
        </aside>
        <?php endif; ?>

    </div><!-- .spost-layout -->

    <!-- ═══════════════════════════════════════════════════
         RELATED POSTS
    ═══════════════════════════════════════════════════ -->
    <?php
    if ( ! empty( $cats ) ) :
        $related_q = new WP_Query( array(
            'post_type'      => 'post',
            'post_status'    => 'publish',
            'posts_per_page' => 3,
            'post__not_in'   => array( $post_id ),
            'category__in'   => wp_list_pluck( $cats, 'term_id' ),
            'orderby'        => 'rand',
        ) );

        $short_months = array(
            1=>'Jan',2=>'Feb',3=>'Mar',4=>'Apr',5=>'Mei',6=>'Jun',
            7=>'Jul',8=>'Agt',9=>'Sep',10=>'Okt',11=>'Nov',12=>'Des',
        );

        if ( $related_q->have_posts() ) :
    ?>
    <section class="spost-related" data-aos="fade-up">
        <h3 class="spost-related-title">
            <i class="fas fa-newspaper" aria-hidden="true"></i>
            Berita Terkait
        </h3>
        <div class="spost-related-grid">
            <?php while ( $related_q->have_posts() ) : $related_q->the_post();
                $r_ts   = strtotime( get_the_date('Y-m-d') );
                $r_date = (int) date('j', $r_ts) . ' '
                        . $short_months[ (int) date('n', $r_ts) ] . ' '
                        . date('Y', $r_ts);
                $r_img  = get_the_post_thumbnail_url( get_the_ID(), 'medium_large' );
            ?>
            <a href="<?php the_permalink(); ?>" class="spost-related-card">
                <div class="spost-related-img">
                    <?php if ( $r_img ) : ?>
                    <img src="<?php echo esc_url( $r_img ); ?>"
                         alt="<?php echo esc_attr( get_the_title() ); ?>"
                         loading="lazy">
                    <?php else : ?>
                    <div class="spost-related-placeholder">
                        <i class="fas fa-newspaper" aria-hidden="true"></i>
                    </div>
                    <?php endif; ?>
                </div>
                <div class="spost-related-body">
                    <span class="spost-related-date">
                        <i class="fas fa-calendar-alt" aria-hidden="true"></i>
                        <?php echo esc_html( $r_date ); ?>
                    </span>
                    <h4><?php the_title(); ?></h4>
                </div>
            </a>
            <?php endwhile; wp_reset_postdata(); ?>
        </div>
    </section>
    <?php
        endif; // have related posts
    endif;   // have categories
    ?>

</div><!-- .container -->
</div><!-- .single-post-wrap -->

<!-- ─────────────────────────────────────────────────────────
     INLINE JS: TOC accordion + copy button + active section
     ───────────────────────────────────────────────────────── -->
<script>
(function () {
    'use strict';

    /* ── Mobile TOC accordion ── */
    var toggleBtn = document.querySelector('.spost-toc-toggle');
    var tocMobileBody = document.getElementById('spost-toc-mobile-body');
    if (toggleBtn && tocMobileBody) {
        toggleBtn.addEventListener('click', function () {
            var expanded = this.getAttribute('aria-expanded') === 'true';
            this.setAttribute('aria-expanded', String(!expanded));
            if (expanded) {
                tocMobileBody.setAttribute('hidden', '');
            } else {
                tocMobileBody.removeAttribute('hidden');
            }
            var chevron = this.querySelector('.spost-toc-chevron');
            if (chevron) chevron.style.transform = expanded ? '' : 'rotate(180deg)';
        });
    }

    /* ── Copy URL button ── */
    var copyBtn = document.querySelector('.spost-share-copy');
    if (copyBtn) {
        copyBtn.addEventListener('click', function () {
            var url = this.dataset.url;
            var span = this.querySelector('span');
            var origText = span ? span.textContent : '';
            if (navigator.clipboard && url) {
                navigator.clipboard.writeText(url).then(function () {
                    if (span) span.textContent = 'Tersalin!';
                    copyBtn.classList.add('spost-share-copied');
                    setTimeout(function () {
                        if (span) span.textContent = origText;
                        copyBtn.classList.remove('spost-share-copied');
                    }, 2000);
                });
            }
        });
    }

    /* ── Highlight active TOC item while scrolling ── */
    var tocLinks = document.querySelectorAll('.spost-toc-list a[href^="#"]');
    if (tocLinks.length) {
        var headings = [];
        tocLinks.forEach(function (a) {
            var target = document.querySelector(a.getAttribute('href'));
            if (target) headings.push({ el: target, link: a });
        });

        function onScroll() {
            var scrollY = window.scrollY + 120;
            var active = null;
            for (var i = 0; i < headings.length; i++) {
                if (headings[i].el.getBoundingClientRect().top + window.scrollY <= scrollY) {
                    active = headings[i];
                }
            }
            tocLinks.forEach(function (a) { a.classList.remove('toc-active'); });
            if (active) active.link.classList.add('toc-active');
        }

        window.addEventListener('scroll', onScroll, { passive: true });
        onScroll(); // run once on load
    }
})();
</script>

<?php get_footer(); ?>
