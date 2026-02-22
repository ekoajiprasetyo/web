<?php
/**
 * home.php — Posts archive page (displayed at /berita/)
 *
 * Features:
 *  - Search bar (keyword)
 *  - Category filter dropdown
 *  - Per-page selector (10 / 50 / 100 / Semua)
 *  - Paginated custom WP_Query
 */

// ── Helper data ───────────────────────────────────────────────
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
    'spmb'            => 'fas fa-user-plus',
    'ekstrakurikuler' => 'fas fa-users',
    'berita'          => 'fas fa-newspaper',
    'uncategorized'   => 'fas fa-newspaper',
);

// ── Read filter params from URL ───────────────────────────────
$f_search = sanitize_text_field( wp_unslash( $_GET['bsearch']  ?? '' ) );
$f_cat    = sanitize_key( $_GET['bcat'] ?? '' );
$f_per    = (int) ( $_GET['per_page'] ?? 10 );
if ( ! in_array( $f_per, array( 10, 50, 100, -1 ), true ) ) $f_per = 10;
$f_paged  = max( 1, (int) ( $_GET['bpaged'] ?? 1 ) );

// ── Build WP_Query args ───────────────────────────────────────
$q_args = array(
    'post_type'           => 'post',
    'post_status'         => 'publish',
    'posts_per_page'      => $f_per,
    'paged'               => $f_paged,
    'orderby'             => 'date',
    'order'               => 'DESC',
    'ignore_sticky_posts' => true,
);
if ( $f_search !== '' ) {
    $q_args['s'] = $f_search;
}
if ( $f_cat !== '' ) {
    $term = get_term_by( 'slug', $f_cat, 'category' );
    if ( $term ) $q_args['cat'] = $term->term_id;
}

$news_q      = new WP_Query( $q_args );
$total_pages = (int) $news_q->max_num_pages;
$total_posts = (int) $news_q->found_posts;

// ── All categories for dropdown ───────────────────────────────
$all_cats = get_categories( array( 'hide_empty' => true, 'orderby' => 'count', 'order' => 'DESC' ) );

// ── Base URL for pagination (posts page) ─────────────────────
$posts_page_id  = (int) get_option( 'page_for_posts' );
$posts_page_url = $posts_page_id ? get_permalink( $posts_page_id ) : home_url( '/berita/' );

// Build pagination base with current filters preserved
$pg_base_args = array();
if ( $f_search !== '' ) $pg_base_args['bsearch']  = $f_search;
if ( $f_cat    !== '' ) $pg_base_args['bcat']      = $f_cat;
if ( $f_per    !== 10 ) $pg_base_args['per_page']  = $f_per;
$pg_base_args['bpaged'] = '%#%';
$paginate_base = add_query_arg( $pg_base_args, $posts_page_url );

// ── Result range info ─────────────────────────────────────────
$is_filter_active = ( $f_search !== '' || $f_cat !== '' );
$is_show_featured = ( $f_paged === 1 && ! $is_filter_active );  // featured card only on default view p1

if ( $f_per === -1 ) {
    $range_start = 1;
    $range_end   = $total_posts;
} else {
    $range_start = ( ( $f_paged - 1 ) * $f_per ) + 1;
    $range_end   = min( $f_paged * $f_per, $total_posts );
}
$per_label = $f_per === -1 ? 'Semua' : $f_per;

// ── Form action URL (strip existing filter params) ────────────
$form_action = remove_query_arg(
    array( 'bsearch', 'bcat', 'per_page', 'bpaged' ),
    $posts_page_url
);

// ── Header ────────────────────────────────────────────────────
get_header();
?>

<main id="berita-page">

    <!-- ── Page Hero ── -->
    <?php
    get_template_part( 'template-parts/inner-page-hero', null, array(
        'eyebrow_icon' => 'fas fa-newspaper',
        'eyebrow_text' => 'Berita & Informasi',
        'title'        => 'Kabar Terbaru',
        'title_accent' => 'Sekolah',
        'description'  => 'Kumpulan berita, pengumuman, dan informasi terkini dari SMAN 1 Purwokerto.',
        'breadcrumb'   => 'Berita',
    ) );
    ?>

    <!-- ── Posts Archive ── -->
    <section class="berita-archive-section section">
        <div class="container">

            <!-- ═══════════════════════════════════════════════
                 FILTER BAR
            ═══════════════════════════════════════════════ -->
            <form method="GET" action="<?php echo esc_url( $form_action ); ?>" class="berita-filter-bar" data-aos="fade-up">

                <!-- Search -->
                <div class="berita-filter-search">
                    <i class="fas fa-search berita-filter-search-icon" aria-hidden="true"></i>
                    <input type="text"
                           name="bsearch"
                           value="<?php echo esc_attr( $f_search ); ?>"
                           placeholder="Cari berita…"
                           class="berita-filter-input"
                           aria-label="Cari berita">
                    <?php if ( $f_search ) : ?>
                    <a href="<?php echo esc_url( add_query_arg( array_filter( array( 'bcat' => $f_cat ?: null, 'per_page' => $f_per !== 10 ? $f_per : null ) ), $posts_page_url ) ); ?>"
                       class="berita-filter-clear" aria-label="Hapus pencarian" title="Hapus pencarian">
                        <i class="fas fa-times"></i>
                    </a>
                    <?php endif; ?>
                </div>

                <!-- Category -->
                <div class="berita-filter-select-wrap">
                    <i class="fas fa-tag berita-filter-select-icon" aria-hidden="true"></i>
                    <select name="bcat" class="berita-filter-select" aria-label="Filter kategori" onchange="this.form.submit()">
                        <option value="">Semua Kategori</option>
                        <?php foreach ( $all_cats as $cat ) : ?>
                        <option value="<?php echo esc_attr( $cat->slug ); ?>"
                            <?php selected( $f_cat, $cat->slug ); ?>>
                            <?php echo esc_html( $cat->name ); ?>
                            (<?php echo (int) $cat->count; ?>)
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Submit -->
                <button type="submit" class="berita-filter-btn">
                    <i class="fas fa-search" aria-hidden="true"></i>
                    <span>Cari</span>
                </button>

            </form><!-- .berita-filter-bar -->

            <!-- ═══════════════════════════════════════════════
                 RESULTS INFO + PER-PAGE SELECTOR
            ═══════════════════════════════════════════════ -->
            <?php if ( $news_q->have_posts() ) : ?>
            <div class="berita-results-bar" data-aos="fade-up">
                <p class="berita-results-info">
                    <?php if ( $total_posts > 0 ) : ?>
                        Menampilkan <strong><?php echo $range_start; ?>&ndash;<?php echo $range_end; ?></strong>
                        dari <strong><?php echo $total_posts; ?></strong> berita
                        <?php if ( $f_search ) : ?>
                            untuk <em>&ldquo;<?php echo esc_html( $f_search ); ?>&rdquo;</em>
                        <?php endif; ?>
                        <?php if ( $f_cat ) : ?>
                            dalam kategori <em><?php echo esc_html( get_term_by( 'slug', $f_cat, 'category' )->name ?? $f_cat ); ?></em>
                        <?php endif; ?>
                    <?php endif; ?>
                </p>

                <!-- Per-page selector -->
                <form method="GET" action="<?php echo esc_url( $form_action ); ?>" class="berita-perpage-form">
                    <?php if ( $f_search ) : ?><input type="hidden" name="bsearch" value="<?php echo esc_attr( $f_search ); ?>"><?php endif; ?>
                    <?php if ( $f_cat ) : ?><input type="hidden" name="bcat" value="<?php echo esc_attr( $f_cat ); ?>"><?php endif; ?>
                    <label for="berita-per-page" class="berita-perpage-label">
                        <i class="fas fa-list" aria-hidden="true"></i>
                        Tampilkan:
                    </label>
                    <select id="berita-per-page" name="per_page" class="berita-filter-select berita-perpage-select"
                            aria-label="Jumlah berita per halaman" onchange="this.form.submit()">
                        <option value="10"  <?php selected( $f_per, 10  ); ?>>10 per halaman</option>
                        <option value="50"  <?php selected( $f_per, 50  ); ?>>50 per halaman</option>
                        <option value="100" <?php selected( $f_per, 100 ); ?>>100 per halaman</option>
                        <option value="-1"  <?php selected( $f_per, -1  ); ?>>Semua</option>
                    </select>
                </form>
            </div>

            <!-- ═══════════════════════════════════════════════
                 POSTS GRID
            ═══════════════════════════════════════════════ -->
            <div class="news-grid news-grid-archive">
                <?php
                $idx = 0;
                while ( $news_q->have_posts() ) :
                    $news_q->the_post();

                    $thumb_url   = get_the_post_thumbnail_url( get_the_ID(), $idx === 0 && $is_show_featured ? 'large' : 'medium_large' );
                    $placeholder = get_template_directory_uri() . '/images/news-placeholder.svg';
                    if ( ! $thumb_url ) $thumb_url = $placeholder;

                    $cats      = get_the_category();
                    $cat_name  = ! empty( $cats ) ? $cats[0]->name : 'Berita';
                    $cat_slug  = ! empty( $cats ) ? $cats[0]->slug : 'berita';
                    $cat_icon  = $cat_icons[ $cat_slug ] ?? 'fas fa-newspaper';
                    $cat_url   = ! empty( $cats ) ? get_category_link( $cats[0]->term_id ) : '#';

                    $author   = get_the_author_meta( 'display_name' );
                    $ts       = strtotime( get_the_date('Y-m-d') );
                    $date_str = date( 'j', $ts ) . ' ' . $indo_months[ (int) date( 'n', $ts ) ] . ' ' . date( 'Y', $ts );

                    $is_featured = ( $idx === 0 && $is_show_featured );
                    $card_class  = 'news-card' . ( $is_featured ? ' news-featured' : '' );
                    $delay       = min( ( $idx + 1 ) * 100, 600 );
                ?>
                <article class="<?php echo $card_class; ?>" data-aos="fade-up" data-aos-delay="<?php echo $delay; ?>">
                    <div class="news-image">
                        <a href="<?php the_permalink(); ?>">
                            <img src="<?php echo esc_url( $thumb_url ); ?>"
                                 alt="<?php echo esc_attr( get_the_title() ); ?>"
                                 loading="<?php echo $idx < 2 ? 'eager' : 'lazy'; ?>">
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
                            <span><i class="fas fa-calendar"></i> <?php echo esc_html( $date_str ); ?></span>
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
                wp_reset_postdata();
                ?>
            </div><!-- .news-grid -->

            <!-- ═══════════════════════════════════════════════
                 PAGINATION
            ═══════════════════════════════════════════════ -->
            <?php if ( $total_pages > 1 ) : ?>
            <div class="news-pagination" data-aos="fade-up">
                <?php
                echo paginate_links( array(
                    'base'               => $paginate_base,
                    'format'             => '',
                    'current'            => $f_paged,
                    'total'              => $total_pages,
                    'prev_text'          => '<i class="fas fa-chevron-left"></i> Sebelumnya',
                    'next_text'          => 'Berikutnya <i class="fas fa-chevron-right"></i>',
                    'before_page_number' => '',
                    'after_page_number'  => '',
                ) );
                ?>
            </div>
            <?php endif; ?>

            <?php else : ?>
            <!-- Empty state -->
            <div class="berita-empty-state" data-aos="fade-up">
                <div class="berita-empty-icon">
                    <i class="fas fa-newspaper" aria-hidden="true"></i>
                </div>
                <?php if ( $is_filter_active ) : ?>
                    <h3>Tidak ditemukan berita</h3>
                    <p>Tidak ada berita yang cocok dengan pencarian Anda. Coba kata kunci lain atau hapus filter.</p>
                    <a href="<?php echo esc_url( $posts_page_url ); ?>" class="btn btn-primary" style="margin-top:1.5rem;">
                        <i class="fas fa-times"></i> <span>Hapus Filter</span>
                    </a>
                <?php else : ?>
                    <h3>Belum ada berita</h3>
                    <p>Berita akan muncul di sini setelah diterbitkan.</p>
                    <?php if ( current_user_can( 'publish_posts' ) ) : ?>
                    <a href="<?php echo esc_url( admin_url('post-new.php') ); ?>" class="btn btn-primary" style="margin-top:1.5rem;">
                        <i class="fas fa-plus"></i> <span>Tambah Berita Pertama</span>
                    </a>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
            <?php endif; ?>

        </div>
    </section>

</main>

<?php get_footer(); ?>
