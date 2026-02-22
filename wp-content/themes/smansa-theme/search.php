<?php
/**
 * search.php — Search results template
 *
 * Features:
 *  - Hero: "Hasil Pencarian" title + keyword accent
 *  - Total results count badge in hero (via extra_html slot)
 *  - Refined search bar in body
 *  - Elegant 3-column card grid (reuses catarch-card pattern)
 *  - Pagination with per-page selector
 */

if ( ! defined( 'ABSPATH' ) ) exit;

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

// post_type icon map (for non-post types)
$type_icons = array(
    'post'          => 'fas fa-newspaper',
    'page'          => 'fas fa-file-alt',
    'school_program'=> 'fas fa-star',
);

// ── Search params ─────────────────────────────────────────────
$search_query = trim( get_search_query() );   // from WP's main query (?s=)
$f_per        = (int) ( $_GET['per_page'] ?? 10 );
if ( ! in_array( $f_per, array( 10, 50, 100, -1 ), true ) ) $f_per = 10;
$f_paged      = max( 1, (int) ( $_GET['spaged'] ?? get_query_var( 'paged', 1 ) ) );

// ── Custom query so we control paged + per_page ───────────────
$q_args = array(
    's'                   => $search_query,
    'posts_per_page'      => $f_per,
    'paged'               => $f_paged,
    'post_status'         => 'publish',
    'post_type'           => array( 'post', 'page' ),
    'orderby'             => 'relevance',
    'ignore_sticky_posts' => true,
);

$srch_q      = new WP_Query( $q_args );
$total_found = (int) $srch_q->found_posts;
$total_pages = (int) $srch_q->max_num_pages;

// ── Pagination base URL ───────────────────────────────────────
$search_page_url = home_url( '/?s=' . rawurlencode( $search_query ) );
$pg_args = array();
if ( $f_per !== 10 ) $pg_args['per_page'] = $f_per;
$pg_args['spaged'] = '%#%';
$paginate_base = add_query_arg( $pg_args, $search_page_url );

// ── Range info ────────────────────────────────────────────────
$range_start = $f_per === -1 ? 1 : ( ( $f_paged - 1 ) * $f_per ) + 1;
$range_end   = $f_per === -1 ? $total_found : min( $f_paged * $f_per, $total_found );

// ── Build hero extra_html — total count badge ─────────────────
ob_start(); ?>
<div class="iph-page-meta srch-hero-meta">
    <?php if ( $total_found > 0 ) : ?>
    <span class="iph-page-meta-item">
        <i class="fas fa-check-circle" aria-hidden="true"></i>
        <?php echo number_format( $total_found ); ?> hasil ditemukan
    </span>
    <?php else : ?>
    <span class="iph-page-meta-item" style="color:rgba(255,255,255,.55);">
        <i class="fas fa-search" aria-hidden="true"></i>
        Tidak ada hasil untuk kata kunci ini
    </span>
    <?php endif; ?>
</div>
<?php
$_hero_extra = ob_get_clean();

get_header();

get_template_part( 'template-parts/inner-page-hero', null, array(
    'eyebrow_icon' => 'fas fa-search',
    'eyebrow_text' => 'Pencarian',
    'title'        => 'Hasil Pencarian',
    'title_accent' => $search_query ? '&ldquo;' . esc_html( $search_query ) . '&rdquo;' : '',
    'description'  => '',
    'breadcrumb'   => 'Pencarian',
    'extra_html'   => $_hero_extra,
) );
?>

<main id="search-results-page">
<section class="srch-section">
<div class="container">

    <!-- ═══════════════════════════════════════════════
         REFINE SEARCH BAR
    ═══════════════════════════════════════════════ -->
    <div class="srch-filter-row" data-aos="fade-up">
        <form role="search" method="GET" action="<?php echo esc_url( home_url('/') ); ?>"
              class="berita-filter-bar srch-filter-bar">
            <div class="berita-filter-search">
                <i class="fas fa-search berita-filter-search-icon" aria-hidden="true"></i>
                <input type="text"
                       name="s"
                       value="<?php echo esc_attr( $search_query ); ?>"
                       placeholder="Ketik ulang kata kunci pencarian…"
                       class="berita-filter-input"
                       aria-label="Kata kunci pencarian"
                       autocomplete="off">
                <?php if ( $search_query ) : ?>
                <a href="<?php echo esc_url( home_url('/') ); ?>"
                   class="berita-filter-clear" aria-label="Hapus pencarian" title="Hapus pencarian">
                    <i class="fas fa-times"></i>
                </a>
                <?php endif; ?>
            </div>
            <button type="submit" class="berita-filter-btn">
                <i class="fas fa-search" aria-hidden="true"></i>
                <span>Cari</span>
            </button>
        </form>

        <!-- Per-page selector -->
        <?php if ( $total_found > 0 ) : ?>
        <form method="GET" action="<?php echo esc_url( $search_page_url ); ?>"
              class="berita-perpage-form srch-perpage">
            <label for="srch-per-page" class="berita-perpage-label">
                <i class="fas fa-list" aria-hidden="true"></i>
                Tampilkan:
            </label>
            <select id="srch-per-page" name="per_page"
                    class="berita-filter-select berita-perpage-select"
                    aria-label="Jumlah hasil per halaman"
                    onchange="this.form.submit()">
                <option value="10"  <?php selected( $f_per, 10  ); ?>>10 per halaman</option>
                <option value="50"  <?php selected( $f_per, 50  ); ?>>50 per halaman</option>
                <option value="100" <?php selected( $f_per, 100 ); ?>>100 per halaman</option>
                <option value="-1"  <?php selected( $f_per, -1  ); ?>>Semua</option>
            </select>
        </form>
        <?php endif; ?>
    </div>

    <!-- ═══════════════════════════════════════════════
         RESULTS INFO
    ═══════════════════════════════════════════════ -->
    <?php if ( $srch_q->have_posts() ) : ?>
    <div class="berita-results-bar srch-results-bar" data-aos="fade-up">
        <p class="berita-results-info">
            Menampilkan <strong><?php echo $range_start; ?>&ndash;<?php echo $range_end; ?></strong>
            dari <strong><?php echo number_format( $total_found ); ?></strong> hasil
            <?php if ( $search_query ) : ?>
                untuk <em>&ldquo;<?php echo esc_html( $search_query ); ?>&rdquo;</em>
            <?php endif; ?>
        </p>
    </div>

    <!-- ═══════════════════════════════════════════════
         RESULTS GRID
    ═══════════════════════════════════════════════ -->
    <div class="catarch-grid srch-grid">
        <?php
        $idx = 0;
        while ( $srch_q->have_posts() ) :
            $srch_q->the_post();

            $post_type  = get_post_type();
            $is_post    = ( $post_type === 'post' );

            // Thumbnail
            $thumb     = get_the_post_thumbnail_url( get_the_ID(), 'medium_large' );
            $ph        = get_template_directory_uri() . '/images/news-placeholder.svg';
            $has_thumb = (bool) $thumb;
            if ( ! $thumb ) $thumb = $ph;

            // Category / type label
            $label_icon = $type_icons[ $post_type ] ?? 'fas fa-file-alt';
            $label_name = '';
            $label_url  = get_permalink();
            if ( $is_post ) {
                $cats       = get_the_category();
                $label_name = ! empty( $cats ) ? $cats[0]->name : 'Berita';
                $cat_slug   = ! empty( $cats ) ? $cats[0]->slug : 'berita';
                $label_icon = $cat_icons[ $cat_slug ] ?? 'fas fa-newspaper';
                $label_url  = ! empty( $cats ) ? get_category_link( $cats[0]->term_id ) : '#';
            } else {
                $label_name = __( 'Halaman', 'sman1' );
            }

            // Meta
            $author     = get_the_author_meta( 'display_name' );
            $avatar_url = get_avatar_url( (int) get_the_author_meta('ID'), array( 'size' => 40 ) );
            $ts         = strtotime( get_the_date('Y-m-d') );
            $date_str   = date( 'j', $ts ) . ' ' . $indo_months[ (int) date( 'n', $ts ) ] . ' ' . date( 'Y', $ts );
            $views      = $is_post ? sman1_get_post_views( get_the_ID() ) : '';
            $excerpt    = wp_trim_words( get_the_excerpt() ?: strip_tags( get_the_content() ), 22, '…' );

            $delay = min( ( $idx % 3 ) * 100 + 100, 400 );
        ?>
        <article <?php post_class('catarch-card srch-card'); ?> data-aos="fade-up" data-aos-delay="<?php echo $delay; ?>">

            <!-- Image -->
            <a href="<?php the_permalink(); ?>" class="catarch-card-imgwrap"
               aria-label="<?php echo esc_attr( get_the_title() ); ?>">
                <img src="<?php echo esc_url( $thumb ); ?>"
                     alt="<?php echo esc_attr( get_the_title() ); ?>"
                     loading="<?php echo $idx < 3 ? 'eager' : 'lazy'; ?>"
                     class="catarch-card-img<?php echo $has_thumb ? '' : ' catarch-card-img--placeholder'; ?>">
                <span class="catarch-card-cat">
                    <i class="<?php echo esc_attr( $label_icon ); ?>" aria-hidden="true"></i>
                    <?php echo esc_html( $label_name ); ?>
                </span>
            </a>

            <!-- Body -->
            <div class="catarch-card-body">

                <!-- Meta -->
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
                    <?php if ( $views ) : ?>
                    <span class="catarch-card-meta-sep" aria-hidden="true"></span>
                    <span class="catarch-card-meta-item">
                        <i class="fas fa-eye" aria-hidden="true"></i>
                        <?php echo esc_html( $views ); ?>
                    </span>
                    <?php endif; ?>
                </div>

                <!-- Title with keyword highlight -->
                <h3 class="catarch-card-title">
                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                </h3>

                <!-- Excerpt -->
                <p class="catarch-card-excerpt"><?php echo esc_html( $excerpt ); ?></p>

                <!-- CTA -->
                <a href="<?php the_permalink(); ?>" class="catarch-card-btn">
                    <?php echo $post_type === 'page' ? 'Lihat Halaman' : 'Baca Selengkapnya'; ?>
                    <i class="fas fa-arrow-right" aria-hidden="true"></i>
                </a>

            </div><!-- .catarch-card-body -->

        </article>
        <?php
            $idx++;
        endwhile;
        wp_reset_postdata();
        ?>
    </div><!-- .srch-grid -->

    <!-- ═══════════════════════════════════════════════
         PAGINATION
    ═══════════════════════════════════════════════ -->
    <?php if ( $total_pages > 1 ) : ?>
    <div class="news-pagination srch-pagination" data-aos="fade-up">
        <?php
        echo paginate_links( array(
            'base'      => $paginate_base,
            'format'    => '',
            'current'   => $f_paged,
            'total'     => $total_pages,
            'prev_text' => '<i class="fas fa-chevron-left"></i> Sebelumnya',
            'next_text' => 'Berikutnya <i class="fas fa-chevron-right"></i>',
        ) );
        ?>
    </div>
    <?php endif; ?>

    <?php else : ?>
    <!-- ═══════════════════════════════════════════════
         EMPTY / NO RESULTS
    ═══════════════════════════════════════════════ -->
    <div class="berita-empty-state srch-empty" data-aos="fade-up">
        <div class="berita-empty-icon">
            <i class="fas fa-search" aria-hidden="true"></i>
        </div>
        <?php if ( $search_query ) : ?>
            <h3>Tidak ada hasil ditemukan</h3>
            <p>
                Tidak ada konten yang cocok dengan kata kunci
                <em>&ldquo;<?php echo esc_html( $search_query ); ?>&rdquo;</em>.
                Coba gunakan kata kunci yang lebih umum atau periksa ejaan Anda.
            </p>
            <!-- Suggestions -->
            <ul class="srch-suggestions">
                <li><i class="fas fa-check" aria-hidden="true"></i> Cek ejaan kata kunci Anda</li>
                <li><i class="fas fa-check" aria-hidden="true"></i> Gunakan kata kunci yang lebih umum</li>
                <li><i class="fas fa-check" aria-hidden="true"></i> Coba kata kunci yang berbeda</li>
            </ul>
        <?php else : ?>
            <h3>Masukkan kata kunci</h3>
            <p>Ketik kata kunci di kolom pencarian di atas untuk mulai mencari.</p>
        <?php endif; ?>
        <a href="<?php echo esc_url( home_url('/') ); ?>" class="catarch-card-btn" style="display:inline-flex;margin-top:1.75rem;">
            <i class="fas fa-home" aria-hidden="true"></i>
            Kembali ke Beranda
        </a>
    </div>
    <?php endif; ?>

</div><!-- .container -->
</section>
</main>

<?php get_footer(); ?>
