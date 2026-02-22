<?php
/**
 * index.php — Universal fallback template
 *
 * WordPress uses this template as a last resort when no more specific
 * template exists (front-page.php, home.php, page.php, single.php,
 * archive.php, 404.php, etc.).
 *
 * Hero content is determined dynamically from the current WP context so
 * the design language is always consistent with the rest of the site.
 */

// ── Resolve hero config for current page context ───────────────────────────
if ( is_home() || is_front_page() ) {
    // Normally handled by home.php / front-page.php
    $iph_icon       = 'fas fa-newspaper';
    $iph_eyebrow    = 'Berita & Informasi';
    $iph_title      = 'Kabar Terbaru';
    $iph_accent     = 'Sekolah';
    $iph_desc       = 'Kumpulan berita, pengumuman, dan informasi terkini dari SMAN 1 Purwokerto.';
    $iph_breadcrumb = 'Beranda';

} elseif ( is_404() ) {
    $iph_icon       = 'fas fa-exclamation-triangle';
    $iph_eyebrow    = 'Halaman Tidak Ditemukan';
    $iph_title      = 'Error';
    $iph_accent     = '404';
    $iph_desc       = 'Maaf, halaman yang Anda cari tidak dapat ditemukan. Mungkin sudah dipindahkan, dihapus, atau URL yang Anda masukkan salah.';
    $iph_breadcrumb = '404';

} elseif ( is_search() ) {
    $iph_icon       = 'fas fa-search';
    $iph_eyebrow    = 'Pencarian';
    $iph_title      = 'Hasil Pencarian';
    $iph_accent     = '"' . get_search_query() . '"';
    $iph_desc       = '';
    $iph_breadcrumb = 'Pencarian';

} elseif ( is_archive() ) {
    $iph_icon        = 'fas fa-folder-open';
    $iph_eyebrow     = is_category() ? 'Kategori' : ( is_tag() ? 'Tag' : ( is_author() ? 'Penulis' : 'Arsip' ) );
    $iph_title       = get_the_archive_title();
    $iph_accent      = '';
    $iph_desc        = wp_strip_all_tags( get_the_archive_description() );
    $iph_breadcrumb  = get_the_archive_title();

} elseif ( is_single() ) {
    $iph_icon        = 'fas fa-newspaper';
    $cats            = get_the_category();
    $iph_eyebrow     = ! empty( $cats ) ? esc_html( $cats[0]->name ) : 'Artikel';
    $iph_title       = get_the_title();
    $iph_accent      = '';
    $iph_desc        = wp_trim_words( wp_strip_all_tags( get_the_excerpt() ?: get_the_content() ), 22, '...' );
    $iph_breadcrumb  = get_the_title();

} elseif ( is_page() ) {
    $iph_icon        = 'fas fa-file-alt';
    $iph_eyebrow     = '';              // badge 'Halaman' dihilangkan — handled by page.php
    $iph_title       = get_the_title();
    $iph_accent      = '';
    $iph_desc        = wp_trim_words( wp_strip_all_tags( get_the_excerpt() ?: '' ), 22, '...' );
    $iph_breadcrumb  = get_the_title();

} else {
    $iph_icon        = 'fas fa-file-alt';
    $iph_eyebrow     = '';
    $iph_title       = get_the_title() ?: get_bloginfo('name');
    $iph_accent      = '';
    $iph_desc        = '';
    $iph_breadcrumb  = get_the_title() ?: 'Halaman';
}

get_header();

// ── Unified hero banner ────────────────────────────────────────────────────
get_template_part( 'template-parts/inner-page-hero', null, array(
    'eyebrow_icon' => $iph_icon,
    'eyebrow_text' => $iph_eyebrow,
    'title'        => $iph_title,
    'title_accent' => $iph_accent,
    'description'  => $iph_desc,
    'breadcrumb'   => $iph_breadcrumb,
) );
?>

<div class="iph-page-content">
    <div class="container">

        <?php if ( is_404() ) : ?>
        <!-- ── 404 special content ── -->
        <div style="text-align:center;padding:3rem 0 5rem;">
            <p style="font-size:1.1rem;color:var(--gray-500);margin-bottom:2rem;">
                Coba kembali ke beranda atau gunakan pencarian untuk menemukan konten yang Anda inginkan.
            </p>
            <div style="display:flex;gap:1rem;justify-content:center;flex-wrap:wrap;">
                <a href="<?php echo esc_url( home_url('/') ); ?>" class="btn btn-lg btn-primary">
                    <i class="fas fa-home"></i> <span>Kembali ke Beranda</span>
                </a>
                <a href="<?php echo esc_url( home_url('/?s=') ); ?>" class="btn btn-lg btn-outline">
                    <i class="fas fa-search"></i> <span>Cari Konten</span>
                </a>
            </div>
        </div>

        <?php elseif ( have_posts() ) : ?>

            <?php while ( have_posts() ) : the_post(); ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class('iph-article'); ?>>

                <?php if ( has_post_thumbnail() && is_singular() ) : ?>
                <div class="iph-article-thumb">
                    <?php the_post_thumbnail( 'large' ); ?>
                </div>
                <?php endif; ?>

                <div class="entry-content">
                    <?php
                    if ( is_singular() ) {
                        the_content();
                        wp_link_pages( array(
                            'before' => '<nav class="page-links"><span>' . __('Halaman:', 'sman1') . '</span>',
                            'after'  => '</nav>',
                        ) );
                    } else {
                        the_excerpt();
                        echo '<a href="' . esc_url( get_permalink() ) . '" class="btn btn-sm btn-primary" style="margin-top:1rem;">'
                           . '<span>Baca Selengkapnya</span> <i class="fas fa-arrow-right"></i></a>';
                    }
                    ?>
                </div>

            </article>
            <?php endwhile; ?>

            <!-- Pagination -->
            <div class="news-pagination" style="margin-top:3rem;margin-bottom:1rem;">
                <?php
                echo paginate_links( array(
                    'prev_text' => '<i class="fas fa-chevron-left"></i> Sebelumnya',
                    'next_text' => 'Berikutnya <i class="fas fa-chevron-right"></i>',
                ) );
                ?>
            </div>

        <?php else : ?>

            <div style="text-align:center;padding:5rem 0;color:var(--gray-400);">
                <i class="fas fa-folder-open" style="font-size:3.5rem;display:block;margin-bottom:1rem;opacity:.35;"></i>
                <h3 style="color:var(--gray-600);margin-bottom:.5rem;">Tidak Ada Konten</h3>
                <p>Konten yang Anda cari belum tersedia.</p>
                <a href="<?php echo esc_url( home_url('/') ); ?>" class="btn btn-primary" style="margin-top:1.5rem;">
                    <i class="fas fa-home"></i> <span>Kembali ke Beranda</span>
                </a>
            </div>

        <?php endif; ?>

    </div><!-- .container -->
</div><!-- .iph-page-content -->

<?php get_footer(); ?>
