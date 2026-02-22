<?php
/**
 * archive-school_achievement.php
 * Archive template for CPT school_achievement → /prestasi/
 *
 * Improvements:
 *  - Hero with total count badge (extra_html)
 *  - Tingkat filter: <select> auto-submit (instant)
 *  - Tahun filter: <select> dropdown (scales with years)
 *  - Per-page selector (12 / 24 / 50 / Semua)
 *  - Custom WP_Query with preserved-param pagination
 *  - Redesigned modern card grid with featured image + Instagram badge
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$level_labels = array(
    'internasional' => 'Internasional',
    'nasional'      => 'Nasional',
    'provinsi'      => 'Provinsi',
    'kabupaten'     => 'Kabupaten/Kota',
    'sekolah'       => 'Sekolah',
);

$medal_colors = array(
    'gold'   => array( 'bg' => 'linear-gradient(135deg,#ffd700,#ff9f00)', 'text' => '#7a5c00', 'label' => 'Emas'    ),
    'silver' => array( 'bg' => 'linear-gradient(135deg,#c0c0c0,#909090)', 'text' => '#404040', 'label' => 'Perak'   ),
    'bronze' => array( 'bg' => 'linear-gradient(135deg,#cd7f32,#a0522d)', 'text' => '#fff',    'label' => 'Perunggu'),
);

// ── Filter params ─────────────────────────────────────────────
$active_level = sanitize_text_field( $_GET['level'] ?? '' );
$active_year  = absint( $_GET['year'] ?? 0 );
$f_per        = (int) ( $_GET['per_page'] ?? 12 );
if ( ! in_array( $f_per, array( 12, 24, 50, -1 ), true ) ) $f_per = 12;
$f_paged      = max( 1, (int) ( $_GET['achpaged'] ?? 1 ) );

// ── Build query args ──────────────────────────────────────────
$ach_args = array(
    'post_type'      => 'school_achievement',
    'posts_per_page' => $f_per,
    'paged'          => $f_paged,
    'orderby'        => 'menu_order date',
    'order'          => 'DESC',
    'post_status'    => 'publish',
);

$meta_query = array();
if ( $active_level ) {
    $meta_query[] = array( 'key' => 'ach_level', 'value' => $active_level, 'compare' => '=' );
}
if ( $active_year ) {
    $meta_query[] = array( 'key' => 'ach_year', 'value' => (string) $active_year, 'compare' => '=' );
}
if ( ! empty( $meta_query ) ) {
    $ach_args['meta_query'] = $meta_query;
}

$ach_query   = new WP_Query( $ach_args );
$total_found = (int) $ach_query->found_posts;
$total_pages = (int) $ach_query->max_num_pages;

// ── Range info ────────────────────────────────────────────────
$range_start = $f_per === -1 ? 1 : ( ( $f_paged - 1 ) * $f_per ) + 1;
$range_end   = $f_per === -1 ? $total_found : min( $f_paged * $f_per, $total_found );

// ── Available years for dropdown ──────────────────────────────
$all_years_raw = $GLOBALS['wpdb']->get_col(
    "SELECT DISTINCT meta_value FROM {$GLOBALS['wpdb']->postmeta}
     WHERE meta_key = 'ach_year' AND meta_value != ''
     ORDER BY meta_value DESC"
);
$all_years = array_filter( array_map( 'absint', $all_years_raw ) );

// ── URLs ──────────────────────────────────────────────────────
$archive_url = get_post_type_archive_link( 'school_achievement' );

$pg_args = array();
if ( $active_level ) $pg_args['level']    = $active_level;
if ( $active_year  ) $pg_args['year']     = $active_year;
if ( $f_per !== 12 ) $pg_args['per_page'] = $f_per;
$pg_args['achpaged'] = '%#%';
$paginate_base = add_query_arg( $pg_args, $archive_url );

// ── Hero extra_html: count badge ──────────────────────────────
ob_start(); ?>
<div class="iph-page-meta acharchive-hero-meta">
    <span class="iph-page-meta-item">
        <i class="fas fa-trophy" aria-hidden="true"></i>
        <?php echo number_format( $total_found ); ?> prestasi
        <?php echo ( $active_level || $active_year ) ? '<span style="opacity:.6;margin-left:.25rem;">ditampilkan</span>' : '<span style="opacity:.6;margin-left:.25rem;">tercatat</span>'; ?>
    </span>
    <?php if ( $active_level && isset( $level_labels[ $active_level ] ) ) : ?>
    <span class="iph-page-meta-sep" aria-hidden="true"></span>
    <span class="iph-page-meta-item">
        <i class="fas fa-layer-group" aria-hidden="true"></i>
        <?php echo esc_html( $level_labels[ $active_level ] ); ?>
    </span>
    <?php endif; ?>
    <?php if ( $active_year ) : ?>
    <span class="iph-page-meta-sep" aria-hidden="true"></span>
    <span class="iph-page-meta-item">
        <i class="fas fa-calendar-alt" aria-hidden="true"></i>
        <?php echo esc_html( $active_year ); ?>
    </span>
    <?php endif; ?>
</div>
<?php
$_hero_extra = ob_get_clean();

get_header();

get_template_part( 'template-parts/inner-page-hero', null, array(
    'eyebrow_icon' => 'fas fa-medal',
    'eyebrow_text' => 'Prestasi Sekolah',
    'title'        => 'Kebanggaan',
    'title_accent' => 'SMANSA',
    'description'  => 'Deretan prestasi membanggakan yang diraih siswa-siswi SMAN 1 Purwokerto di berbagai kompetisi daerah, nasional, dan internasional.',
    'breadcrumb'   => 'Prestasi',
    'extra_html'   => $_hero_extra,
) );
?>

<main id="prestasi-page">

    <!-- ── Sticky Filter Bar ── -->
    <div class="acharchive-filter-bar">
        <div class="container">
            <form method="GET" action="<?php echo esc_url( $archive_url ); ?>"
                  class="acharchive-filter-form" id="achFilterForm">

                <!-- Tingkat (instant auto-submit) -->
                <div class="acharchive-filter-group">
                    <label for="ach-level-select" class="acharchive-filter-label">
                        <i class="fas fa-layer-group" aria-hidden="true"></i> Tingkat
                    </label>
                    <div class="acharchive-select-wrap">
                        <select id="ach-level-select" name="level"
                                class="acharchive-select" onchange="achSubmit()"
                                aria-label="Filter tingkat lomba">
                            <option value="">Semua Tingkat</option>
                            <?php foreach ( $level_labels as $slug => $label ) : ?>
                            <option value="<?php echo esc_attr( $slug ); ?>" <?php selected( $active_level, $slug ); ?>>
                                <?php echo esc_html( $label ); ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <!-- Tahun dropdown -->
                <?php if ( ! empty( $all_years ) ) : ?>
                <div class="acharchive-filter-group">
                    <label for="ach-year-select" class="acharchive-filter-label">
                        <i class="fas fa-calendar-alt" aria-hidden="true"></i> Tahun
                    </label>
                    <div class="acharchive-select-wrap">
                        <select id="ach-year-select" name="year"
                                class="acharchive-select" onchange="achSubmit()"
                                aria-label="Filter tahun prestasi">
                            <option value="">Semua Tahun</option>
                            <?php foreach ( $all_years as $yr ) : ?>
                            <option value="<?php echo esc_attr( $yr ); ?>" <?php selected( $active_year, $yr ); ?>>
                                <?php echo esc_html( $yr ); ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Per-page -->
                <div class="acharchive-filter-group acharchive-perpage-group">
                    <label for="ach-per-page" class="acharchive-filter-label">
                        <i class="fas fa-list" aria-hidden="true"></i> Tampilkan
                    </label>
                    <div class="acharchive-select-wrap">
                        <select id="ach-per-page" name="per_page"
                                class="acharchive-select" onchange="achSubmit()"
                                aria-label="Jumlah per halaman">
                            <option value="12"  <?php selected( $f_per, 12  ); ?>>12 per halaman</option>
                            <option value="24"  <?php selected( $f_per, 24  ); ?>>24 per halaman</option>
                            <option value="50"  <?php selected( $f_per, 50  ); ?>>50 per halaman</option>
                            <option value="-1"  <?php selected( $f_per, -1  ); ?>>Semua</option>
                        </select>
                    </div>
                </div>

                <?php if ( $active_level || $active_year || $f_per !== 12 ) : ?>
                <a href="<?php echo esc_url( $archive_url ); ?>" class="acharchive-reset-btn" title="Reset semua filter">
                    <i class="fas fa-times" aria-hidden="true"></i> Reset
                </a>
                <?php endif; ?>

            </form>
        </div>
    </div><!-- .acharchive-filter-bar -->

    <!-- ── Body ── -->
    <section class="acharchive-section">
        <div class="container">

            <?php if ( $ach_query->have_posts() ) : ?>

            <!-- Results info -->
            <div class="berita-results-bar acharchive-results-bar" data-aos="fade-up">
                <p class="berita-results-info">
                    Menampilkan <strong><?php echo $range_start; ?>&ndash;<?php echo $range_end; ?></strong>
                    dari <strong><?php echo number_format( $total_found ); ?></strong> prestasi
                    <?php if ( $active_level && isset( $level_labels[ $active_level ] ) ) : ?>
                        tingkat <em><?php echo esc_html( $level_labels[ $active_level ] ); ?></em>
                    <?php endif; ?>
                    <?php if ( $active_year ) echo 'tahun <em>' . esc_html( $active_year ) . '</em>'; ?>
                </p>
            </div>

            <!-- Cards grid -->
            <div class="acharchive-grid">
                <?php
                $idx = 0;
                while ( $ach_query->have_posts() ) :
                    $ach_query->the_post();
                    $post_id      = get_the_ID();
                    $medal        = get_post_meta( $post_id, 'ach_medal',         true ) ?: 'gold';
                    $icon         = get_post_meta( $post_id, 'ach_icon',          true ) ?: 'fas fa-medal';
                    $event        = get_post_meta( $post_id, 'ach_event',         true ) ?: '';
                    $student      = get_post_meta( $post_id, 'ach_student',       true ) ?: '';
                    $level        = get_post_meta( $post_id, 'ach_level',         true ) ?: '';
                    $year         = get_post_meta( $post_id, 'ach_year',          true ) ?: '';
                    $ig_url       = get_post_meta( $post_id, 'ach_instagram_url', true ) ?: '';

                    $medal_cfg    = $medal_colors[ $medal ] ?? $medal_colors['gold'];
                    $delay        = min( ( $idx % 3 ) * 100 + 100, 400 );
                ?>
                <div class="acharchive-card acharchive-medal-border-<?php echo esc_attr( $medal ); ?>" data-aos="fade-up" data-aos-delay="<?php echo $delay; ?>">

                    <!-- Top row: medal pip + label + level + year + ig icon -->
                    <div class="acharchive-card-top">
                        <div class="acharchive-medal-pip acharchive-medal-<?php echo esc_attr( $medal ); ?>"
                             style="background:<?php echo esc_attr( $medal_cfg['bg'] ); ?>;" aria-hidden="true">
                            <i class="<?php echo esc_attr( $icon ); ?>"
                               style="color:<?php echo esc_attr( $medal_cfg['text'] ); ?>;"></i>
                        </div>
                        <div class="acharchive-card-pills">
                            <?php if ( $level && isset( $level_labels[ $level ] ) ) : ?>
                            <span class="ach-level-badge ach-level-<?php echo esc_attr( $level ); ?>">
                                <?php echo esc_html( $level_labels[ $level ] ); ?>
                            </span>
                            <?php endif; ?>
                            <?php if ( $year ) : ?>
                            <span class="acharchive-card-year"><?php echo esc_html( $year ); ?></span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Title -->
                    <h3 class="acharchive-card-title"><?php the_title(); ?></h3>

                    <?php if ( $event ) : ?>
                    <p class="acharchive-card-event">
                        <i class="fas fa-flag" aria-hidden="true"></i>
                        <?php echo esc_html( $event ); ?>
                    </p>
                    <?php endif; ?>

                    <?php if ( $student ) : ?>
                    <p class="acharchive-card-student">
                        <i class="fas fa-user-graduate" aria-hidden="true"></i>
                        <?php echo esc_html( $student ); ?>
                    </p>
                    <?php endif; ?>

                    <?php if ( $ig_url ) : ?>
                    <a href="<?php echo esc_url( $ig_url ); ?>" target="_blank" rel="noopener noreferrer"
                       class="acharchive-card-ig-icon" aria-label="Lihat di Instagram">
                        <i class="fab fa-instagram" aria-hidden="true"></i>
                        Lihat di Instagram
                    </a>
                    <?php endif; ?>

                </div><!-- .acharchive-card -->
                <?php
                    $idx++;
                endwhile;
                wp_reset_postdata();
                ?>
            </div><!-- .acharchive-grid -->

            <!-- Pagination -->
            <?php if ( $total_pages > 1 ) : ?>
            <div class="news-pagination acharchive-pagination" data-aos="fade-up">
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

            <!-- Empty state -->
            <div class="berita-empty-state" data-aos="fade-up">
                <div class="berita-empty-icon">
                    <i class="fas fa-trophy" aria-hidden="true"></i>
                </div>
                <h3>Belum ada prestasi yang ditampilkan</h3>
                <?php if ( $active_level || $active_year ) : ?>
                <p>Tidak ada prestasi untuk filter yang dipilih. Coba ubah filter di atas.</p>
                <a href="<?php echo esc_url( $archive_url ); ?>"
                   class="catarch-card-btn" style="display:inline-flex;margin-top:1.5rem;">
                    <i class="fas fa-times" aria-hidden="true"></i> Reset Filter
                </a>
                <?php else : ?>
                <p>Prestasi akan muncul di sini setelah ditambahkan.</p>
                <?php if ( current_user_can( 'publish_posts' ) ) : ?>
                <a href="<?php echo esc_url( admin_url('post-new.php?post_type=school_achievement') ); ?>"
                   class="catarch-card-btn" style="display:inline-flex;margin-top:1.5rem;">
                    <i class="fas fa-plus" aria-hidden="true"></i> Tambah Prestasi
                </a>
                <?php endif; ?>
                <?php endif; ?>
            </div>

            <?php endif; ?>

        </div>
    </section>

</main>

<script>
function achSubmit() { document.getElementById('achFilterForm').submit(); }
</script>

<?php get_footer(); ?>
