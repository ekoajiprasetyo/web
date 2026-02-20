<?php
/**
 * home.php — Posts archive page (displayed at /berita/)
 *
 * WordPress uses this template when "Posts page" is set in
 * Settings → Reading. It lists all published posts.
 */

$indo_months = array(
    1=>'Januari', 2=>'Februari', 3=>'Maret',    4=>'April',
    5=>'Mei',     6=>'Juni',     7=>'Juli',      8=>'Agustus',
    9=>'September',10=>'Oktober',11=>'November', 12=>'Desember',
);

$cat_icons = array(
    'pengumuman'      => 'fas fa-bullhorn',
    'prestasi'        => 'fas fa-trophy',
    'akademik'        => 'fas fa-book',
    'kegiatan'        => 'fas fa-flag',
    'ppdb'            => 'fas fa-user-plus',
    'ekstrakurikuler' => 'fas fa-users',
    'berita'          => 'fas fa-newspaper',
    'uncategorized'   => 'fas fa-newspaper',
);

get_header();
?>

<main id="berita-page">

    <!-- ── Page Hero ── -->
    <div class="page-hero">
        <div class="container">
            <div class="page-hero-content">
                <div class="section-subtitle">
                    <i class="fas fa-newspaper"></i>
                    <span>Berita &amp; Informasi</span>
                </div>
                <h1>Kabar Terbaru <span class="text-accent">Sekolah</span></h1>
                <p>Kumpulan berita, pengumuman, dan informasi terkini dari SMAN 1 Purwokerto.</p>
            </div>
        </div>
    </div>

    <!-- ── Posts Grid ── -->
    <section class="section" style="background:var(--gray-50);">
        <div class="container">
            <?php if ( have_posts() ) : ?>

            <div class="news-grid news-grid-archive">
                <?php
                $idx = 0;
                while ( have_posts() ) :
                    the_post();

                    $thumb_url  = get_the_post_thumbnail_url( get_the_ID(), $idx === 0 ? 'large' : 'medium_large' );
                    $placeholder = get_template_directory_uri() . '/images/news-placeholder.svg';
                    if ( ! $thumb_url ) $thumb_url = $placeholder;

                    $cats      = get_the_category();
                    $cat_name  = ! empty( $cats ) ? $cats[0]->name : 'Berita';
                    $cat_slug  = ! empty( $cats ) ? $cats[0]->slug : 'berita';
                    $cat_icon  = isset( $cat_icons[ $cat_slug ] ) ? $cat_icons[ $cat_slug ] : 'fas fa-newspaper';
                    $cat_url   = ! empty( $cats ) ? get_category_link( $cats[0]->term_id ) : '#';

                    $author    = get_the_author_meta( 'display_name' );
                    $ts        = strtotime( get_the_date('Y-m-d') );
                    $date      = date( 'j', $ts ) . ' ' . $indo_months[ (int) date( 'n', $ts ) ] . ' ' . date( 'Y', $ts );

                    $is_featured = ( $idx === 0 );
                    $card_class  = 'news-card' . ( $is_featured ? ' news-featured' : '' );
                    $delay       = min( ($idx + 1) * 100, 600 );
                ?>
                <article class="<?php echo $card_class; ?>" data-aos="fade-up" data-aos-delay="<?php echo $delay; ?>">
                    <div class="news-image">
                        <a href="<?php the_permalink(); ?>">
                            <img src="<?php echo esc_url( $thumb_url ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>">
                        </a>
                        <div class="news-category">
                            <a href="<?php echo esc_url( $cat_url ); ?>" style="color:inherit;text-decoration:none;">
                                <i class="<?php echo esc_attr( $cat_icon ); ?>"></i>
                                <?php echo esc_html( $cat_name ); ?>
                            </a>
                        </div>
                    </div>
                    <div class="news-content">
                        <div class="news-meta">
                            <span><i class="fas fa-calendar"></i> <?php echo esc_html( $date ); ?></span>
                            <span><i class="fas fa-user"></i> <?php echo esc_html( $author ); ?></span>
                        </div>
                        <h3 class="news-title">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h3>
                        <p class="news-excerpt"><?php echo wp_trim_words( get_the_excerpt() ?: strip_tags( get_the_content() ), 20, '...' ); ?></p>
                        <a href="<?php the_permalink(); ?>" class="news-read-more">
                            Baca Selengkapnya <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </article>
                <?php
                    $idx++;
                endwhile;
                ?>
            </div>

            <!-- Pagination -->
            <div class="news-pagination" data-aos="fade-up">
                <?php
                echo paginate_links( array(
                    'prev_text' => '<i class="fas fa-chevron-left"></i> Sebelumnya',
                    'next_text' => 'Berikutnya <i class="fas fa-chevron-right"></i>',
                    'before_page_number' => '',
                    'after_page_number'  => '',
                ) );
                ?>
            </div>

            <?php else : ?>
            <div style="text-align:center;padding:4rem 0;color:var(--gray-500);">
                <i class="fas fa-newspaper" style="font-size:3rem;margin-bottom:1rem;display:block;opacity:.3;"></i>
                <h3>Belum ada berita</h3>
                <p>Berita akan muncul di sini setelah diterbitkan.</p>
                <?php if ( current_user_can( 'publish_posts' ) ) : ?>
                <a href="<?php echo esc_url( admin_url('post-new.php') ); ?>" class="btn btn-primary" style="margin-top:1.5rem;">
                    <i class="fas fa-plus"></i> <span>Tambah Berita Pertama</span>
                </a>
                <?php endif; ?>
            </div>
            <?php endif; ?>

        </div>
    </section>

</main>

<?php get_footer(); ?>
