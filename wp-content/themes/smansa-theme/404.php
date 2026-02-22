<?php
/**
 * 404.php — Halaman Error 404 Not Found
 * SMAN 1 Purwokerto
 */

if ( ! defined( 'ABSPATH' ) ) exit;

get_header();
?>

<main id="main" class="site-main">
    <div class="e404-wrap">

        <!-- Background decorative circles -->
        <div class="e404-bg-circle e404-bg-circle--1" aria-hidden="true"></div>
        <div class="e404-bg-circle e404-bg-circle--2" aria-hidden="true"></div>
        <div class="e404-bg-circle e404-bg-circle--3" aria-hidden="true"></div>

        <div class="e404-inner">

            <!-- Large 404 number -->
            <div class="e404-number" aria-hidden="true">
                <span class="e404-digit">4</span>
                <span class="e404-digit e404-digit--zero">
                    <span class="e404-zero-icon">
                        <i class="fas fa-search"></i>
                    </span>
                </span>
                <span class="e404-digit">4</span>
            </div>

            <!-- Label -->
            <div class="e404-badge">
                <i class="fas fa-exclamation-triangle" aria-hidden="true"></i>
                Halaman Tidak Ditemukan
            </div>

            <!-- Message -->
            <h1 class="e404-title">Oops! Halaman ini tidak ada</h1>
            <p class="e404-desc">
                Halaman yang Anda cari mungkin telah dipindahkan, dihapus,<br class="e404-br">
                atau alamatnya sudah berganti.
            </p>

            <!-- Single CTA -->
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="e404-btn">
                <i class="fas fa-home" aria-hidden="true"></i>
                Kembali ke Beranda
            </a>

        </div><!-- .e404-inner -->
    </div><!-- .e404-wrap -->
</main>

<?php get_footer(); ?>
