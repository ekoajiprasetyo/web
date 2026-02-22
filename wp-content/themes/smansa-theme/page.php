<?php
/**
 * page.php — WordPress Page template
 *
 * Handles all standard WP Pages (post_type = 'page') that don't have
 * a dedicated template (page-kontak.php, page-galeri.php, etc. take
 * priority in the template hierarchy over this file).
 *
 * Features:
 *  - Unified inner-page hero  (NO "Halaman" badge per req)
 *  - Meta bar: date published · author + avatar · view count
 *  - Featured image (optional)
 *  - Elegant card-based body content
 *  - Share buttons (identical pattern to single.php)
 */

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! have_posts() ) {
    get_header();
    get_footer();
    exit;
}
the_post();

// ── Track page views ──────────────────────────────────────────────────────────
sman1_track_post_views( get_the_ID() );

// ── Meta data ─────────────────────────────────────────────────────────────────
$page_id     = get_the_ID();
$page_ts     = strtotime( get_the_date('Y-m-d') );
$date_str    = sman1_indo_date( $page_ts );
$author_id   = (int) get_the_author_meta('ID');
$author      = get_the_author_meta('display_name');
$avatar_url  = get_avatar_url( $author_id, array( 'size' => 48 ) );
$views_str   = sman1_get_post_views( $page_id );

// Share URLs
$share_url   = rawurlencode( get_permalink() );
$share_title = rawurlencode( get_the_title() );

// ── Build meta HTML for hero extra_html slot ────────────────────────────────
ob_start();
?>
<div class="iph-page-meta">
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
$_page_extra = ob_get_clean();

// ── Header + Hero ─────────────────────────────────────────────────────────────
get_header();

get_template_part( 'template-parts/inner-page-hero', null, array(
    'eyebrow_icon' => 'fas fa-file-alt',
    'eyebrow_text' => '',          // no badge above title
    'title'        => get_the_title(),
    'title_accent' => '',
    'description'  => '',          // no subtitle text below title
    'breadcrumb'   => get_the_title(),
    'extra_html'   => $_page_extra, // meta row rendered inside hero
) );
?>

<div class="ppage-wrap">
<div class="container">

    <!-- ═══════════════════════════════════════════════════
         FEATURED IMAGE
    ═══════════════════════════════════════════════════ -->
    <?php if ( has_post_thumbnail() ) : ?>
    <div class="ppage-featured-img" data-aos="fade-up">
        <?php the_post_thumbnail( 'large', array(
            'alt'     => esc_attr( get_the_title() ),
            'loading' => 'eager',
        ) ); ?>
    </div>
    <?php endif; ?>

    <!-- ═══════════════════════════════════════════════════
         ARTICLE CARD
    ═══════════════════════════════════════════════════ -->
    <article id="post-<?php the_ID(); ?>" <?php post_class('ppage-card'); ?> data-aos="fade-up">

        <!-- ─── Decorative header strip ─────────────── -->
        <div class="ppage-card-stripe" aria-hidden="true"></div>

        <!-- ─── Body text ───────────────────────────── -->
        <div class="ppage-body">
            <?php
            the_content();
            wp_link_pages( array(
                'before'    => '<nav class="page-links"><span>' . __( 'Halaman:', 'sman1' ) . '</span>',
                'after'     => '</nav>',
                'link_before' => '<span>',
                'link_after'  => '</span>',
            ) );
            ?>
        </div>

        <!-- ─── Share buttons ───────────────────────── -->
        <div class="spost-share ppage-share" data-aos="fade-up">
            <p class="spost-share-label">
                <i class="fas fa-share-alt" aria-hidden="true"></i>
                Bagikan Halaman
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
                        aria-label="Salin tautan halaman">
                    <i class="fas fa-link" aria-hidden="true"></i>
                    <span>Salin Tautan</span>
                </button>

            </div>
        </div>

    </article><!-- .ppage-card -->

</div><!-- .container -->
</div><!-- .ppage-wrap -->

<!-- Copy-button inline JS — identical pattern to single.php -->
<script>
(function () {
    var copyBtn = document.querySelector('.spost-share-copy');
    if (copyBtn) {
        copyBtn.addEventListener('click', function () {
            var url  = this.dataset.url;
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
})();
</script>

<?php get_footer(); ?>
