<?php
/**
 * Template Part: Unified Inner-Page Hero Banner
 *
 * Shared by Berita, Galeri, Prestasi, and all default/fallback pages.
 * Reference design language: page-kontak.php hero.
 *
 * Usage (WP 5.5+):
 *   get_template_part( 'template-parts/inner-page-hero', null, [
 *       'eyebrow_icon' => 'fas fa-newspaper',
 *       'eyebrow_text' => 'Berita & Informasi',
 *       'title'        => 'Kabar Terbaru',
 *       'title_accent' => 'Sekolah',
 *       'description'  => 'Kumpulan berita...',
 *       'breadcrumb'   => 'Berita',
 *       'extra_html'   => '<span ...>stat badge</span>',
 *   ] );
 *
 * @param string $eyebrow_icon  FontAwesome class string  (default 'fas fa-file-alt')
 * @param string $eyebrow_text  Label inside eyebrow pill (omit to hide pill)
 * @param string $title         H1 main text
 * @param string $title_accent  H1 accent word (displayed with orange gradient after $title)
 * @param string $description   Subtitle paragraph (supports basic kses HTML)
 * @param string $breadcrumb    Current-page label for breadcrumb trail
 * @param string $extra_html    Optional raw HTML injected after the paragraph (badge row etc.)
 */

$eyebrow_icon = $args['eyebrow_icon'] ?? 'fas fa-file-alt';
$eyebrow_text = $args['eyebrow_text'] ?? '';
$title        = $args['title']        ?? get_the_title();
$title_accent = $args['title_accent'] ?? '';
$description  = $args['description']  ?? '';
$breadcrumb   = $args['breadcrumb']   ?? $title;
$extra_html   = $args['extra_html']   ?? '';
?>
<section class="iph-hero">

    <div class="iph-bg" aria-hidden="true"></div>

    <div class="iph-shapes" aria-hidden="true">
        <span class="iph-shape iph-shape-1"></span>
        <span class="iph-shape iph-shape-2"></span>
        <span class="iph-shape iph-shape-3"></span>
    </div>

    <div class="container iph-content">

        <!-- ── Breadcrumb ── -->
        <nav class="iph-breadcrumb" aria-label="Breadcrumb">
            <a href="<?php echo esc_url( home_url('/') ); ?>">
                <i class="fas fa-home" aria-hidden="true"></i> Beranda
            </a>
            <i class="fas fa-chevron-right" aria-hidden="true"></i>
            <span><?php echo esc_html( $breadcrumb ); ?></span>
        </nav>

        <!-- ── Body ── -->
        <div class="iph-body" data-aos="fade-up">

            <?php if ( $eyebrow_text ) : ?>
            <span class="iph-eyebrow">
                <i class="<?php echo esc_attr( $eyebrow_icon ); ?>" aria-hidden="true"></i>
                <?php echo esc_html( $eyebrow_text ); ?>
            </span>
            <?php endif; ?>

            <h1>
                <?php echo esc_html( $title ); ?>
                <?php if ( $title_accent ) : ?>
                <span><?php echo esc_html( $title_accent ); ?></span>
                <?php endif; ?>
            </h1>

            <?php if ( $description ) : ?>
            <p><?php echo wp_kses_post( $description ); ?></p>
            <?php endif; ?>

            <?php if ( $extra_html ) : ?>
            <div class="iph-extra" data-aos="fade-up" data-aos-delay="150">
                <?php echo $extra_html; // Already escaped by caller ?>
            </div>
            <?php endif; ?>

        </div><!-- .iph-body -->

    </div><!-- .iph-content -->

</section><!-- .iph-hero -->
