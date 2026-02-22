<?php
/**
 * Template Name: Sitemap
 *
 * Halaman Sitemap HTML SMAN 1 Purwokerto
 * Membantu mesin pencari seperti Google mengindeks seluruh konten situs.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

get_header();

get_template_part( 'template-parts/inner-page-hero', null, array(
    'eyebrow_icon' => 'fas fa-sitemap',
    'eyebrow_text' => '',
    'title'        => 'Sitemap',
    'title_accent' => '',
    'description'  => '',
    'breadcrumb'   => 'Sitemap',
    'extra_html'   => '',
) );
?>

<div class="ppage-wrap">
<div class="container">

    <article class="ppage-card" data-aos="fade-up">
        <div class="ppage-card-stripe" aria-hidden="true"></div>
        <div class="ppage-body">

            <p class="sitemap-intro">Halaman ini menampilkan seluruh struktur konten situs web SMA Negeri 1 Purwokerto untuk memudahkan navigasi dan pengindeksan oleh mesin pencari. Sitemap XML otomatis tersedia di <a href="<?php echo esc_url( home_url( '/wp-sitemap.xml' ) ); ?>" target="_blank" rel="nofollow">/wp-sitemap.xml</a>.</p>

            <div class="sitemap-grid">

                <!-- ═══ HALAMAN UTAMA ═══ -->
                <div class="sitemap-section">
                    <h2><i class="fas fa-home" aria-hidden="true"></i> Halaman Utama</h2>
                    <ul>
                        <li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><i class="fas fa-angle-right" aria-hidden="true"></i> Beranda</a></li>
                        <?php
                        // Standard WordPress pages, excluding the sitemap page itself and pages with dedicated templates
                        $pages = get_pages( array(
                            'sort_column'  => 'menu_order',
                            'sort_order'   => 'ASC',
                            'hierarchical' => 0,
                            'exclude'      => array( get_the_ID() ),
                        ) );
                        foreach ( $pages as $p ) :
                            // skip pages with a dedicated template that we handle separately
                            $tpl = get_page_template_slug( $p->ID );
                            $skip_templates = array(
                                'page-galeri.php',
                                'page-kontak.php',
                                'page-program.php',
                                'page-kebijakan-privasi.php',
                                'page-syarat-ketentuan.php',
                            );
                            if ( in_array( $tpl, $skip_templates, true ) ) continue;
                        ?>
                        <li><a href="<?php echo esc_url( get_permalink( $p->ID ) ); ?>"><i class="fas fa-angle-right" aria-hidden="true"></i> <?php echo esc_html( $p->post_title ); ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <!-- ═══ HALAMAN INFORMASI ═══ -->
                <div class="sitemap-section">
                    <h2><i class="fas fa-info-circle" aria-hidden="true"></i> Informasi Sekolah</h2>
                    <ul>
                        <?php
                        $info_pages = array(
                            'page-galeri.php'  => array( 'slug' => 'galeri',  'label' => 'Galeri', 'icon' => 'fas fa-images' ),
                            'page-kontak.php'  => array( 'slug' => 'kontak',  'label' => 'Kontak', 'icon' => 'fas fa-envelope' ),
                            'page-program.php' => array( 'slug' => 'program', 'label' => 'Program Unggulan', 'icon' => 'fas fa-star' ),
                        );
                        foreach ( $info_pages as $tpl => $data ) :
                            $found = get_pages( array( 'meta_key' => '_wp_page_template', 'meta_value' => $tpl, 'number' => 1 ) );
                            $url   = $found ? get_permalink( $found[0]->ID ) : home_url( '/' . $data['slug'] . '/' );
                            $label = $found ? $found[0]->post_title : $data['label'];
                        ?>
                        <li><a href="<?php echo esc_url( $url ); ?>"><i class="<?php echo esc_attr( $data['icon'] ); ?>" aria-hidden="true"></i> <?php echo esc_html( $label ); ?></a></li>
                        <?php endforeach; ?>
                        <li><a href="<?php echo esc_url( get_post_type_archive_link( 'school_achievement' ) ); ?>"><i class="fas fa-trophy" aria-hidden="true"></i> Prestasi</a></li>
                    </ul>
                </div>

                <!-- ═══ BERITA & ARTIKEL ═══ -->
                <div class="sitemap-section">
                    <h2><i class="fas fa-newspaper" aria-hidden="true"></i> Berita &amp; Artikel</h2>
                    <ul>
                        <li><a href="<?php echo esc_url( home_url( '/berita/' ) ); ?>"><i class="fas fa-angle-right" aria-hidden="true"></i> Semua Berita</a></li>
                        <?php
                        $categories = get_categories( array( 'hide_empty' => true, 'number' => 20 ) );
                        foreach ( $categories as $cat ) :
                        ?>
                        <li>
                            <a href="<?php echo esc_url( get_category_link( $cat->term_id ) ); ?>">
                                <i class="fas fa-folder-open" aria-hidden="true"></i>
                                <?php echo esc_html( $cat->name ); ?>
                                <span class="sitemap-count">(<?php echo (int) $cat->count; ?>)</span>
                            </a>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <!-- ═══ BERITA TERBARU ═══ -->
                <div class="sitemap-section">
                    <h2><i class="fas fa-clock" aria-hidden="true"></i> Berita Terbaru</h2>
                    <ul>
                        <?php
                        $recent_posts = new WP_Query( array(
                            'posts_per_page' => 10,
                            'post_status'    => 'publish',
                            'orderby'        => 'date',
                            'order'          => 'DESC',
                        ) );
                        if ( $recent_posts->have_posts() ) :
                            while ( $recent_posts->have_posts() ) : $recent_posts->the_post();
                        ?>
                        <li>
                            <a href="<?php the_permalink(); ?>">
                                <i class="fas fa-angle-right" aria-hidden="true"></i>
                                <?php the_title(); ?>
                            </a>
                            <span class="sitemap-date"><?php echo get_the_date('d M Y'); ?></span>
                        </li>
                        <?php
                            endwhile;
                            wp_reset_postdata();
                        endif;
                        ?>
                    </ul>
                    <?php
                    $total_posts = wp_count_posts()->publish;
                    if ( $total_posts > 10 ) :
                    ?>
                    <p class="sitemap-viewall"><a href="<?php echo esc_url( home_url( '/berita/' ) ); ?>">Lihat semua <?php echo (int) $total_posts; ?> artikel &rarr;</a></p>
                    <?php endif; ?>
                </div>

                <!-- ═══ GALERI KATEGORI ═══ -->
                <?php
                $gal_terms = get_terms( array( 'taxonomy' => 'gallery_category', 'hide_empty' => true ) );
                if ( ! is_wp_error( $gal_terms ) && $gal_terms ) :
                ?>
                <div class="sitemap-section">
                    <h2><i class="fas fa-images" aria-hidden="true"></i> Galeri Kategori</h2>
                    <ul>
                        <?php
                        $galeri_found = get_pages( array( 'meta_key' => '_wp_page_template', 'meta_value' => 'page-galeri.php', 'number' => 1 ) );
                        $galeri_base  = $galeri_found ? get_permalink( $galeri_found[0]->ID ) : home_url( '/galeri/' );
                        foreach ( $gal_terms as $term ) :
                        ?>
                        <li>
                            <a href="<?php echo esc_url( $galeri_base . '?filter=' . urlencode( $term->slug ) ); ?>">
                                <i class="fas fa-tag" aria-hidden="true"></i>
                                <?php echo esc_html( $term->name ); ?>
                                <span class="sitemap-count">(<?php echo (int) $term->count; ?>)</span>
                            </a>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php endif; ?>

                <!-- ═══ PRESTASI ═══ -->
                <?php
                $ach_years = get_posts( array(
                    'post_type'  => 'school_achievement',
                    'fields'     => 'ids',
                    'nopaging'   => true,
                    'post_status'=> 'publish',
                ) );
                $years_list = array();
                foreach ( $ach_years as $aid ) {
                    $y = get_post_meta( $aid, 'ach_year', true );
                    if ( $y ) $years_list[] = (int) $y;
                }
                $years_list = array_unique( $years_list );
                rsort( $years_list );

                if ( $years_list ) :
                    $ach_base = get_post_type_archive_link( 'school_achievement' ) ?: home_url( '/prestasi/' );
                ?>
                <div class="sitemap-section">
                    <h2><i class="fas fa-trophy" aria-hidden="true"></i> Prestasi per Tahun</h2>
                    <ul>
                        <li><a href="<?php echo esc_url( $ach_base ); ?>"><i class="fas fa-list" aria-hidden="true"></i> Semua Prestasi</a></li>
                        <?php foreach ( $years_list as $yr ) : ?>
                        <li>
                            <a href="<?php echo esc_url( add_query_arg( 'year', $yr, $ach_base ) ); ?>">
                                <i class="fas fa-calendar-alt" aria-hidden="true"></i>
                                Prestasi <?php echo esc_html( $yr ); ?>
                            </a>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php endif; ?>

                <!-- ═══ LEGAL ═══ -->
                <div class="sitemap-section">
                    <h2><i class="fas fa-gavel" aria-hidden="true"></i> Informasi Legal</h2>
                    <ul>
                        <?php
                        $legal_pages = array(
                            'page-kebijakan-privasi.php' => array( 'label' => 'Kebijakan Privasi', 'icon' => 'fas fa-shield-alt', 'slug' => 'kebijakan-privasi' ),
                            'page-syarat-ketentuan.php'  => array( 'label' => 'Syarat & Ketentuan',  'icon' => 'fas fa-file-contract', 'slug' => 'syarat-ketentuan' ),
                        );
                        foreach ( $legal_pages as $tpl => $data ) :
                            $found_pg = get_pages( array( 'meta_key' => '_wp_page_template', 'meta_value' => $tpl, 'number' => 1 ) );
                            $url_pg   = $found_pg ? get_permalink( $found_pg[0]->ID ) : home_url( '/' . $data['slug'] . '/' );
                            $lbl_pg   = $found_pg ? $found_pg[0]->post_title : $data['label'];
                        ?>
                        <li><a href="<?php echo esc_url( $url_pg ); ?>"><i class="<?php echo esc_attr( $data['icon'] ); ?>" aria-hidden="true"></i> <?php echo esc_html( $lbl_pg ); ?></a></li>
                        <?php endforeach; ?>
                        <li><a href="<?php echo esc_url( home_url( '/wp-sitemap.xml' ) ); ?>" target="_blank" rel="nofollow"><i class="fas fa-code" aria-hidden="true"></i> XML Sitemap</a></li>
                    </ul>
                </div>

            </div><!-- .sitemap-grid -->

        </div><!-- .ppage-body -->
    </article><!-- .ppage-card -->

</div><!-- .container -->
</div><!-- .ppage-wrap -->

<?php get_footer(); ?>
