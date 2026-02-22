<?php
/**
 * category.php — Category archive template
 *
 * Displays posts belonging to a single category.
 * Features:
 *  - Hero with category name as title + post count badge
 *  - Keyword search filter (category is fixed in URL)
 *  - Per-page selector (10 / 50 / 100 / Semua)
 *  - Elegant 3-column card grid
 *  - Custom pagination preserving filter params
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

// ── Current category object ───────────────────────────────────
$cat         = get_queried_object();   // WP_Term
$cat_id      = (int) $cat->term_id;
$cat_name    = $cat->name;
$cat_slug    = $cat->slug;
$cat_desc    = $cat->description;
$cat_url     = get_category_link( $cat_id );
$cat_icon    = $cat_icons[ $cat_slug ] ?? 'fas fa-newspaper';
$total_in_cat = (int) $cat->count;     // total posts in this category

// ── Filter params ─────────────────────────────────────────────
$f_search = sanitize_text_field( wp_unslash( $_GET['csearch'] ?? '' ) );
$f_per    = (int) ( $_GET['per_page'] ?? 10 );
if ( ! in_array( $f_per, array( 10, 50, 100, -1 ), true ) ) $f_per = 10;
$f_paged  = max( 1, (int) ( $_GET['cpaged'] ?? 1 ) );

// ── WP_Query ──────────────────────────────────────────────────
$q_args = array(
    'post_type'           => 'post',
    'post_status'         => 'publish',
    'cat'                 => $cat_id,
    'posts_per_page'      => $f_per,
    'paged'               => $f_paged,
    'orderby'             => 'date',
    'order'               => 'DESC',
    'ignore_sticky_posts' => true,
);
if ( $f_search !== '' ) {
    $q_args['s'] = $f_search;
}

$cat_q       = new WP_Query( $q_args );
$total_pages = (int) $cat_q->max_num_pages;
$total_found = (int) $cat_q->found_posts;

// ── Pagination base URL ───────────────────────────────────────
$pg_args = array();
if ( $f_search !== '' ) $pg_args['csearch']  = $f_search;
if ( $f_per    !== 10 ) $pg_args['per_page'] = $f_per;
$pg_args['cpaged'] = '%#%';
$paginate_base = add_query_arg( $pg_args, $cat_url );

// ── Range info ────────────────────────────────────────────────
$range_start = $f_per === -1 ? 1 : ( ( $f_paged - 1 ) * $f_per ) + 1;
$range_end   = $f_per === -1 ? $total_found : min( $f_paged * $f_per, $total_found );

// ── Form action (clean URL) ───────────────────────────────────
$form_action = remove_query_arg( array( 'csearch', 'per_page', 'cpaged' ), $cat_url );

// ── Build hero extra_html — count badge + optional description ─
ob_start(); ?>
<div class="iph-page-meta catarch-hero-meta">
    <span class="iph-page-meta-item">
        <i class="<?php echo esc_attr( $cat_icon ); ?>" aria-hidden="true"></i>
        <?php echo esc_html( $total_in_cat ); ?> postingan
    </span>
    <?php if ( $cat_desc ) : ?>
    <span class="iph-page-meta-sep" aria-hidden="true"></span>
    <span class="iph-page-meta-item catarch-hero-desc">
        <?php echo esc_html( $cat_desc ); ?>
    </span>
    <?php endif; ?>
</div>
<?php
$_hero_extra = ob_get_clean();

// ── Title / accent split: use full category name ──────────────
// For multi-word categories (e.g. "Berita Sekolah"), accent the last word
$words = explode( ' ', $cat_name );
if ( count( $words ) > 1 ) {
    $title_accent = array_pop( $words );
    $title_base   = implode( ' ', $words );
} else {
    $title_base   = $cat_name;
    $title_accent = '';
}

get_header();
?>

<main id="category-archive-page">

    <!-- ── Hero ── -->
    <?php
    get_template_part( 'template-parts/inner-page-hero', null, array(
        'eyebrow_icon' => 'fas fa-tag',
        'eyebrow_text' => 'Arsip Kategori',
        'title'        => $title_base,
        'title_accent' => $title_accent,
        'description'  => '',
        'breadcrumb'   => $cat_name,
        'extra_html'   => $_hero_extra,
    ) );
    ?>

    <!-- ── Archive body ── -->
    <section class="catarch-section">
        <div class="container">

            <!-- ═══════════════════════════════════════════════
                 FILTER BAR
            ═══════════════════════════════════════════════ -->
            <div class="catarch-filter-row" data-aos="fade-up">
                <form method="GET" action="<?php echo esc_url( $form_action ); ?>" class="berita-filter-bar catarch-filter-bar">

                    <!-- Search -->
                    <div class="berita-filter-search">
                        <i class="fas fa-search berita-filter-search-icon" aria-hidden="true"></i>
                        <input type="text"
                               name="csearch"
                               value="<?php echo esc_attr( $f_search ); ?>"
                               placeholder="Cari di kategori ini…"
                               class="berita-filter-input"
                               aria-label="Cari postingan">
                        <?php if ( $f_search ) : ?>
                        <a href="<?php echo esc_url( $f_per !== 10 ? add_query_arg( 'per_page', $f_per, $cat_url ) : $cat_url ); ?>"
                           class="berita-filter-clear" aria-label="Hapus pencarian">
                            <i class="fas fa-times"></i>
                        </a>
                        <?php endif; ?>
                    </div>

                    <!-- Submit -->
                    <button type="submit" class="berita-filter-btn">
                        <i class="fas fa-search" aria-hidden="true"></i>
                        <span>Cari</span>
                    </button>

                </form>

                <!-- Per-page selector (separate small form) -->
                <form method="GET" action="<?php echo esc_url( $form_action ); ?>" class="berita-perpage-form catarch-perpage">
                    <?php if ( $f_search ) : ?>
                    <input type="hidden" name="csearch" value="<?php echo esc_attr( $f_search ); ?>">
                    <?php endif; ?>
                    <label for="cat-per-page" class="berita-perpage-label">
                        <i class="fas fa-list" aria-hidden="true"></i>
                        Tampilkan:
                    </label>
                    <select id="cat-per-page" name="per_page"
                            class="berita-filter-select berita-perpage-select"
                            aria-label="Jumlah postingan per halaman"
                            onchange="this.form.submit()">
                        <option value="10"  <?php selected( $f_per, 10  ); ?>>10 per halaman</option>
                        <option value="50"  <?php selected( $f_per, 50  ); ?>>50 per halaman</option>
                        <option value="100" <?php selected( $f_per, 100 ); ?>>100 per halaman</option>
                        <option value="-1"  <?php selected( $f_per, -1  ); ?>>Semua</option>
                    </select>
                </form>
            </div><!-- .catarch-filter-row -->

            <!-- ═══════════════════════════════════════════════
                 RESULTS INFO
            ═══════════════════════════════════════════════ -->
            <?php if ( $cat_q->have_posts() ) : ?>
            <div class="berita-results-bar catarch-results-bar" data-aos="fade-up">
                <p class="berita-results-info">
                    Menampilkan <strong><?php echo $range_start; ?>&ndash;<?php echo $range_end; ?></strong>
                    dari <strong><?php echo $total_found; ?></strong> postingan
                    <?php if ( $f_search ) : ?>
                        untuk <em>&ldquo;<?php echo esc_html( $f_search ); ?>&rdquo;</em>
                    <?php endif; ?>
                    dalam kategori <em><?php echo esc_html( $cat_name ); ?></em>
                </p>
            </div>

            <!-- ═══════════════════════════════════════════════
                 POSTS GRID
            ═══════════════════════════════════════════════ -->
            <div class="catarch-grid" data-aos="fade-up">
                <?php
                $idx = 0;
                while ( $cat_q->have_posts() ) :
                    $cat_q->the_post();

                    $thumb     = get_the_post_thumbnail_url( get_the_ID(), 'medium_large' );
                    $ph        = get_template_directory_uri() . '/images/news-placeholder.svg';
                    $has_thumb = (bool) $thumb;
                    if ( ! $thumb ) $thumb = $ph;

                    $post_cats  = get_the_category();
                    $pc_name    = ! empty( $post_cats ) ? $post_cats[0]->name : $cat_name;
                    $pc_slug    = ! empty( $post_cats ) ? $post_cats[0]->slug : $cat_slug;
                    $pc_url     = ! empty( $post_cats ) ? get_category_link( $post_cats[0]->term_id ) : $cat_url;
                    $pc_icon    = $cat_icons[ $pc_slug ] ?? 'fas fa-newspaper';

                    $author     = get_the_author_meta( 'display_name' );
                    $avatar_url = get_avatar_url( (int) get_the_author_meta('ID'), array( 'size' => 40 ) );
                    $ts         = strtotime( get_the_date('Y-m-d') );
                    $date_str   = date( 'j', $ts ) . ' ' . $indo_months[ (int) date( 'n', $ts ) ] . ' ' . date( 'Y', $ts );
                    $views      = sman1_get_post_views( get_the_ID() );
                    $excerpt    = wp_trim_words( get_the_excerpt() ?: strip_tags( get_the_content() ), 22, '…' );

                    $delay = min( ( $idx % 3 ) * 100 + 100, 400 );
                ?>
                <article <?php post_class('catarch-card'); ?> data-aos="fade-up" data-aos-delay="<?php echo $delay; ?>">

                    <!-- Image -->
                    <a href="<?php the_permalink(); ?>" class="catarch-card-imgwrap" aria-label="<?php echo esc_attr( get_the_title() ); ?>">
                        <img src="<?php echo esc_url( $thumb ); ?>"
                             alt="<?php echo esc_attr( get_the_title() ); ?>"
                             loading="<?php echo $idx < 3 ? 'eager' : 'lazy'; ?>"
                             class="catarch-card-img<?php echo $has_thumb ? '' : ' catarch-card-img--placeholder'; ?>">
                        <!-- Category badge overlay -->
                        <span class="catarch-card-cat">
                            <i class="<?php echo esc_attr( $pc_icon ); ?>" aria-hidden="true"></i>
                            <?php echo esc_html( $pc_name ); ?>
                        </span>
                    </a>

                    <!-- Body -->
                    <div class="catarch-card-body">

                        <!-- Meta row -->
                        <div class="catarch-card-meta">
                            <span class="catarch-card-meta-item">
                                <i class="fas fa-calendar-alt" aria-hidden="true"></i>
                                <?php echo esc_html( $date_str ); ?>
                            </span>
                            <span class="catarch-card-meta-sep" aria-hidden="true"></span>
                            <span class="catarch-card-meta-item">
                                <img src="<?php echo esc_url( $avatar_url ); ?>"
                                     alt="<?php echo esc_attr( $author ); ?>"
                                     class="catarch-card-avatar">
                                <?php echo esc_html( $author ); ?>
                            </span>
                            <span class="catarch-card-meta-sep" aria-hidden="true"></span>
                            <span class="catarch-card-meta-item">
                                <i class="fas fa-eye" aria-hidden="true"></i>
                                <?php echo esc_html( $views ); ?>
                            </span>
                        </div>

                        <!-- Title -->
                        <h3 class="catarch-card-title">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h3>

                        <!-- Excerpt -->
                        <p class="catarch-card-excerpt"><?php echo esc_html( $excerpt ); ?></p>

                        <!-- CTA -->
                        <a href="<?php the_permalink(); ?>" class="catarch-card-btn">
                            Baca Selengkapnya
                            <i class="fas fa-arrow-right" aria-hidden="true"></i>
                        </a>

                    </div><!-- .catarch-card-body -->

                </article>
                <?php
                    $idx++;
                endwhile;
                wp_reset_postdata();
                ?>
            </div><!-- .catarch-grid -->

            <!-- ═══════════════════════════════════════════════
                 PAGINATION
            ═══════════════════════════════════════════════ -->
            <?php if ( $total_pages > 1 ) : ?>
            <div class="news-pagination catarch-pagination" data-aos="fade-up">
                <?php
                echo paginate_links( array(
                    'base'    => $paginate_base,
                    'format'  => '',
                    'current' => $f_paged,
                    'total'   => $total_pages,
                    'prev_text' => '<i class="fas fa-chevron-left"></i> Sebelumnya',
                    'next_text' => 'Berikutnya <i class="fas fa-chevron-right"></i>',
                ) );
                ?>
            </div>
            <?php endif; ?>

            <?php else : ?>
            <!-- Empty state -->
            <div class="berita-empty-state catarch-empty" data-aos="fade-up">
                <div class="berita-empty-icon">
                    <i class="<?php echo esc_attr( $cat_icon ); ?>" aria-hidden="true"></i>
                </div>
                <?php if ( $f_search ) : ?>
                    <h3>Tidak ditemukan postingan</h3>
                    <p>Tidak ada postingan yang cocok dengan pencarian <em>&ldquo;<?php echo esc_html( $f_search ); ?>&rdquo;</em> di kategori ini.</p>
                    <a href="<?php echo esc_url( $cat_url ); ?>" class="catarch-card-btn" style="display:inline-flex;margin-top:1.75rem;">
                        <i class="fas fa-times" aria-hidden="true"></i>
                        Hapus Pencarian
                    </a>
                <?php else : ?>
                    <h3>Belum ada postingan</h3>
                    <p>Kategori <em><?php echo esc_html( $cat_name ); ?></em> belum memiliki postingan.</p>
                <?php endif; ?>
            </div>
            <?php endif; ?>

        </div>
    </section>

</main>

<?php get_footer(); ?>
