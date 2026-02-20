<?php
/**
 * archive-school_achievement.php
 * Archive template for CPT school_achievement → /prestasi/
 */

$level_labels = array(
    'internasional' => 'Internasional',
    'nasional'      => 'Nasional',
    'provinsi'      => 'Provinsi',
    'kabupaten'     => 'Kabupaten/Kota',
    'sekolah'       => 'Sekolah',
);

$medal_icons = array(
    'gold'   => 'fas fa-medal',
    'silver' => 'fas fa-medal',
    'bronze' => 'fas fa-medal',
);

$active_level = isset( $_GET['level'] ) ? sanitize_text_field( $_GET['level'] ) : '';
$active_year  = isset( $_GET['year'] )  ? absint( $_GET['year'] )               : 0;

// Build query args
$ach_args = array(
    'post_type'      => 'school_achievement',
    'posts_per_page' => 12,
    'paged'          => max( 1, get_query_var('paged') ),
    'orderby'        => 'menu_order date',
    'order'          => 'DESC',
    'post_status'    => 'publish',
);

if ( $active_level ) {
    $ach_args['meta_query'][] = array(
        'key'     => 'ach_level',
        'value'   => $active_level,
        'compare' => '=',
    );
}
if ( $active_year ) {
    $ach_args['meta_query'][] = array(
        'key'     => 'ach_year',
        'value'   => (string) $active_year,
        'compare' => '=',
    );
}

$ach_query = new WP_Query( $ach_args );

// Collect available years for filter
$all_years_raw = $GLOBALS['wpdb']->get_col(
    "SELECT DISTINCT meta_value FROM {$GLOBALS['wpdb']->postmeta}
     WHERE meta_key = 'ach_year' AND meta_value != ''
     ORDER BY meta_value DESC"
);
$all_years = array_filter( array_map( 'absint', $all_years_raw ) );

get_header();
?>

<main id="prestasi-page">

    <!-- ── Page Hero ── -->
    <div class="page-hero prestasi-hero">
        <div class="container">
            <div class="page-hero-content">
                <div class="section-subtitle">
                    <i class="fas fa-medal"></i>
                    <span>Prestasi Terbaru</span>
                </div>
                <h1>Kebanggaan <span class="text-accent">SMANSA</span></h1>
                <p>Deretan prestasi membanggakan yang diraih siswa-siswi SMAN 1 Purwokerto di berbagai kompetisi daerah, nasional, dan internasional.</p>
            </div>
        </div>
    </div>

    <!-- ── Filter Bar ── -->
    <div class="ach-filter-bar">
        <div class="container">
            <div class="ach-filters">
                <div class="ach-filter-group">
                    <span class="ach-filter-label"><i class="fas fa-layer-group"></i> Tingkat:</span>
                    <a href="<?php echo esc_url( get_post_type_archive_link('school_achievement') . ( $active_year ? '?year=' . $active_year : '' ) ); ?>"
                       class="ach-filter-btn <?php echo ! $active_level ? 'active' : ''; ?>">Semua</a>
                    <?php foreach ( $level_labels as $slug => $label ) :
                        $url = add_query_arg( array_filter( array( 'level' => $slug, 'year' => $active_year ?: null ) ), get_post_type_archive_link('school_achievement') );
                    ?>
                    <a href="<?php echo esc_url( $url ); ?>"
                       class="ach-filter-btn <?php echo $active_level === $slug ? 'active' : ''; ?>">
                        <?php echo esc_html( $label ); ?>
                    </a>
                    <?php endforeach; ?>
                </div>

                <?php if ( ! empty( $all_years ) ) : ?>
                <div class="ach-filter-group">
                    <span class="ach-filter-label"><i class="fas fa-calendar"></i> Tahun:</span>
                    <a href="<?php echo esc_url( get_post_type_archive_link('school_achievement') . ( $active_level ? '?level=' . $active_level : '' ) ); ?>"
                       class="ach-filter-btn <?php echo ! $active_year ? 'active' : ''; ?>">Semua</a>
                    <?php foreach ( $all_years as $year ) :
                        $url = add_query_arg( array_filter( array( 'year' => $year, 'level' => $active_level ?: null ) ), get_post_type_archive_link('school_achievement') );
                    ?>
                    <a href="<?php echo esc_url( $url ); ?>"
                       class="ach-filter-btn <?php echo $active_year === $year ? 'active' : ''; ?>">
                        <?php echo esc_html( $year ); ?>
                    </a>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- ── Achievements Grid ── -->
    <section class="section" style="background: var(--gray-50);">
        <div class="container">
            <?php if ( $ach_query->have_posts() ) : ?>

            <div class="achievements-archive-grid">
                <?php
                $idx = 0;
                while ( $ach_query->have_posts() ) :
                    $ach_query->the_post();
                    $post_id = get_the_ID();
                    $medal   = get_post_meta( $post_id, 'ach_medal',   true ) ?: 'gold';
                    $icon    = get_post_meta( $post_id, 'ach_icon',    true ) ?: 'fas fa-medal';
                    $event   = get_post_meta( $post_id, 'ach_event',   true ) ?: '';
                    $student = get_post_meta( $post_id, 'ach_student', true ) ?: '';
                    $level   = get_post_meta( $post_id, 'ach_level',   true ) ?: '';
                    $year    = get_post_meta( $post_id, 'ach_year',    true ) ?: '';
                    $delay   = min( ( $idx % 4 + 1 ) * 100, 400 );
                ?>
                <div class="achievement-card" data-aos="fade-up" data-aos-delay="<?php echo $delay; ?>">
                    <div class="achievement-medal <?php echo esc_attr( $medal ); ?>">
                        <i class="<?php echo esc_attr( $icon ?: 'fas fa-medal' ); ?>"></i>
                    </div>
                    <div class="achievement-content">
                        <?php if ( $level || $year ) : ?>
                        <div class="ach-meta">
                            <?php if ( $level && isset( $level_labels[$level] ) ) : ?>
                            <span class="ach-level-badge ach-level-<?php echo esc_attr( $level ); ?>">
                                <?php echo esc_html( $level_labels[$level] ); ?>
                            </span>
                            <?php endif; ?>
                            <?php if ( $year ) : ?>
                            <span class="ach-year"><?php echo esc_html( $year ); ?></span>
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>
                        <h4><?php the_title(); ?></h4>
                        <p><?php echo esc_html( $event ); ?></p>
                        <span class="achievement-student"><?php echo esc_html( $student ); ?></span>
                    </div>
                </div>
                <?php
                    $idx++;
                endwhile;
                wp_reset_postdata();
                ?>
            </div>

            <!-- Pagination -->
            <div class="news-pagination" data-aos="fade-up">
                <?php
                echo paginate_links( array(
                    'total'    => $ach_query->max_num_pages,
                    'prev_text' => '<i class="fas fa-chevron-left"></i> Sebelumnya',
                    'next_text' => 'Berikutnya <i class="fas fa-chevron-right"></i>',
                ) );
                ?>
            </div>

            <?php else : ?>
            <div style="text-align:center;padding:4rem 0;color:var(--gray-500);">
                <i class="fas fa-trophy" style="font-size:3rem;margin-bottom:1rem;display:block;opacity:.3;"></i>
                <h3>Belum ada prestasi yang ditampilkan</h3>
                <p>Coba filter yang berbeda atau kembali ke semua prestasi.</p>
                <?php if ( current_user_can( 'publish_posts' ) ) : ?>
                <a href="<?php echo esc_url( admin_url('post-new.php?post_type=school_achievement') ); ?>" class="btn btn-primary" style="margin-top:1.5rem;">
                    <i class="fas fa-plus"></i> <span>Tambah Prestasi</span>
                </a>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>
    </section>

</main>

<?php get_footer(); ?>
