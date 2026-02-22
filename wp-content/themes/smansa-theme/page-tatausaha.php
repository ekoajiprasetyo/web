<?php
/**
 * Template Name: Halaman Tata Usaha
 *
 * Menampilkan daftar staf Tata Usaha SMAN 1 Purwokerto dari CPT school_staff
 * dengan department taxonomy slug = 'tata-usaha'.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// ── Data query ────────────────────────────────────────────────────────────────
$staff_query = smansa_get_staff( 'tata-usaha' );
$total       = $staff_query->found_posts;

// Collect unique bidang for filter dropdown
$all_bidang = array();
if ( $staff_query->have_posts() ) {
    foreach ( $staff_query->posts as $sp ) {
        $sub = get_post_meta( $sp->ID, '_staff_subjects', true );
        if ( $sub ) {
            foreach ( array_map( 'trim', explode( ',', $sub ) ) as $s ) {
                if ( $s !== '' && ! in_array( $s, $all_bidang, true ) ) {
                    $all_bidang[] = $s;
                }
            }
        }
    }
    sort( $all_bidang );
}

// ── Hero extra HTML ───────────────────────────────────────────────────────────
ob_start();
?>
<div class="iph-page-meta">
    <span class="iph-page-meta-item">
        <i class="fas fa-users-cog" aria-hidden="true"></i>
        <?php echo (int) $total; ?> Staf Tata Usaha
    </span>
</div>
<?php
$_extra = ob_get_clean();

get_header();

get_template_part( 'template-parts/inner-page-hero', null, array(
    'eyebrow_icon' => 'fas fa-users-cog',
    'eyebrow_text' => '',
    'title'        => 'Tata',
    'title_accent' => 'Usaha',
    'description'  => '',
    'breadcrumb'   => 'Tata Usaha',
    'extra_html'   => $_extra,
) );
?>

<!-- ═══════════════════════════════════════════════════════════════════════════
     FILTER BAR
═══════════════════════════════════════════════════════════════════════════ -->
<?php if ( $total > 0 ) : ?>
<section class="staff-filter-section">
    <div class="container">
        <div class="staff-filter-bar">

            <!-- Search -->
            <div class="staff-filter-search-wrap">
                <i class="fas fa-search" aria-hidden="true"></i>
                <input type="text"
                       id="staffSearch"
                       class="staff-filter-search"
                       placeholder="Cari nama staf…"
                       autocomplete="off">
            </div>

            <!-- Filter by Bidang Tugas -->
            <?php if ( $all_bidang ) : ?>
            <div class="staff-filter-select-wrap">
                <i class="fas fa-sitemap" aria-hidden="true"></i>
                <select id="staffSubjectFilter" class="staff-filter-select">
                    <option value="all">Semua Bidang Tugas</option>
                    <?php foreach ( $all_bidang as $bidang ) : ?>
                    <option value="<?php echo esc_attr( $bidang ); ?>"><?php echo esc_html( $bidang ); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <?php endif; ?>

            <!-- Per-page selector -->
            <div class="staff-filter-select-wrap">
                <i class="fas fa-list-ol" aria-hidden="true"></i>
                <select id="staffPerPage" class="staff-filter-select">
                    <option value="12">12 / halaman</option>
                    <option value="24">24 / halaman</option>
                    <option value="48">48 / halaman</option>
                    <option value="-1">Semua</option>
                </select>
            </div>

            <!-- Count display -->
            <div class="staff-filter-count" id="staffCount">
                Menampilkan <strong><?php echo (int) $total; ?></strong> staf
            </div>

        </div>
    </div>
</section>

<!-- ═══════════════════════════════════════════════════════════════════════════
     STAFF GRID
═══════════════════════════════════════════════════════════════════════════ -->
<section class="staff-section">
    <div class="container">
        <div class="staff-grid staff-grid--tu" id="staffGrid">

            <?php
            $staff_query->rewind_posts();
            while ( $staff_query->have_posts() ) :
                $staff_query->the_post();
                $sid       = get_the_ID();
                $name      = get_the_title();
                $position  = get_post_meta( $sid, '_staff_position',  true );
                $nip       = get_post_meta( $sid, '_staff_nip',       true );
                $education = get_post_meta( $sid, '_staff_education', true );
                $subjects  = get_post_meta( $sid, '_staff_subjects',  true );
                $quote     = get_post_meta( $sid, '_staff_quote',     true );
                $has_img   = has_post_thumbnail();
                $initial   = mb_strtoupper( mb_substr( $name, 0, 1 ) );
            ?>
            <article class="staff-card staff-card--tu"
                     data-name="<?php echo esc_attr( strtolower( $name ) ); ?>"
                     data-subjects="<?php echo esc_attr( strtolower( $subjects ) ); ?>"
                     data-aos="fade-up">

                <!-- Photo -->
                <div class="staff-card-photo">
                    <?php if ( $has_img ) : ?>
                        <?php the_post_thumbnail( 'medium', array(
                            'alt'     => esc_attr( $name ),
                            'loading' => 'lazy',
                        ) ); ?>
                    <?php else : ?>
                        <div class="staff-card-initial" aria-hidden="true"><?php echo esc_html( $initial ); ?></div>
                    <?php endif; ?>
                </div>

                <!-- Info -->
                <div class="staff-card-info">
                    <h3 class="staff-card-name"><?php echo esc_html( $name ); ?></h3>

                    <?php if ( $position ) : ?>
                    <p class="staff-card-position">
                        <i class="fas fa-id-badge" aria-hidden="true"></i>
                        <?php echo esc_html( $position ); ?>
                    </p>
                    <?php endif; ?>

                    <?php if ( $subjects ) : ?>
                    <div class="staff-card-subjects">
                        <?php
                        foreach ( array_map( 'trim', explode( ',', $subjects ) ) as $s ) {
                            if ( $s !== '' ) {
                                echo '<span class="staff-subject-badge staff-subject-badge--tu">' . esc_html( $s ) . '</span>';
                            }
                        }
                        ?>
                    </div>
                    <?php endif; ?>

                    <?php if ( $education ) : ?>
                    <p class="staff-card-edu">
                        <i class="fas fa-graduation-cap" aria-hidden="true"></i>
                        <?php echo esc_html( $education ); ?>
                    </p>
                    <?php endif; ?>

                    <?php if ( $nip ) : ?>
                    <p class="staff-card-nip">NIP: <?php echo esc_html( $nip ); ?></p>
                    <?php endif; ?>

                    <?php if ( $quote ) : ?>
                    <blockquote class="staff-card-quote">"<?php echo esc_html( $quote ); ?>"</blockquote>
                    <?php endif; ?>
                </div>

            </article>
            <?php endwhile; wp_reset_postdata(); ?>

        </div><!-- .staff-grid -->

        <!-- Empty state -->
        <div class="staff-empty-state" id="staffEmpty" style="display:none;">
            <i class="fas fa-search" aria-hidden="true"></i>
            <p>Tidak ditemukan staf yang sesuai dengan pencarian.</p>
            <button type="button" onclick="staffResetFilter()">Reset Filter</button>
        </div>

        <!-- Pagination -->
        <div class="staff-pagination" id="staffPagination"></div>

    </div><!-- .container -->
</section>

<?php else : ?>
<section class="staff-section">
    <div class="container">
        <div class="staff-empty-state">
            <i class="fas fa-users-cog" aria-hidden="true"></i>
            <p>Belum ada data staf Tata Usaha. <a href="<?php echo admin_url('post-new.php?post_type=school_staff'); ?>">Tambahkan melalui WP Admin</a>.</p>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- ═══════════════════════════════════════════════════════════════════════════
     FILTER JS — Self-contained IIFE
═══════════════════════════════════════════════════════════════════════════ -->
<script>
(function () {
    var searchEl  = document.getElementById('staffSearch');
    var filterEl  = document.getElementById('staffSubjectFilter');
    var perPageEl = document.getElementById('staffPerPage');
    var countEl   = document.getElementById('staffCount');
    var emptyEl   = document.getElementById('staffEmpty');
    var paginEl   = document.getElementById('staffPagination');
    var cards     = Array.from( document.querySelectorAll('#staffGrid .staff-card') );
    var state     = { search: '', subject: 'all', perPage: 12, page: 1 };

    function applyFilters() {
        var q        = state.search.toLowerCase().trim();
        var sub      = state.subject.toLowerCase();
        var perPage  = state.perPage;
        var page     = state.page;

        var matched = cards.filter(function (card) {
            var name     = (card.dataset.name     || '').toLowerCase();
            var subjects = (card.dataset.subjects || '').toLowerCase();
            return (!q   || name.indexOf(q)   !== -1) &&
                   (sub === 'all' || subjects.indexOf(sub) !== -1);
        });

        var total    = matched.length;
        var maxPages = perPage === -1 ? 1 : Math.ceil(total / perPage);
        if (page > maxPages) page = state.page = maxPages || 1;
        var start    = perPage === -1 ? 0     : (page - 1) * perPage;
        var end      = perPage === -1 ? total : start + perPage;
        var shown    = matched.slice(start, end);

        var shownSet = new Set(shown);
        cards.forEach(function (card) {
            card.style.display = shownSet.has(card) ? '' : 'none';
        });

        if (countEl) {
            if (perPage === -1 || maxPages <= 1) {
                countEl.innerHTML = 'Menampilkan <strong>' + total + '</strong> staf';
            } else {
                countEl.innerHTML = 'Menampilkan <strong>' + shown.length + '</strong> dari <strong>' + total + '</strong> staf';
            }
        }

        if (emptyEl) emptyEl.style.display = total === 0 ? '' : 'none';
        renderPagination(page, maxPages);
    }

    function renderPagination(currentPage, maxPages) {
        if (!paginEl) return;
        if (maxPages <= 1) { paginEl.innerHTML = ''; return; }

        var html = '<div class="staff-pagination-inner">';
        html += '<button class="staff-page-btn staff-page-prev" ' +
                (currentPage <= 1 ? 'disabled' : '') +
                ' onclick="staffGoPage(' + (currentPage - 1) + ')">' +
                '<i class="fas fa-chevron-left"></i></button>';

        buildPageRange(currentPage, maxPages).forEach(function (p) {
            if (p === '...') {
                html += '<span class="staff-page-ellipsis">&hellip;</span>';
            } else {
                html += '<button class="staff-page-btn' + (p === currentPage ? ' staff-page-active' : '') + '" ' +
                        'onclick="staffGoPage(' + p + ')">' + p + '</button>';
            }
        });

        html += '<button class="staff-page-btn staff-page-next" ' +
                (currentPage >= maxPages ? 'disabled' : '') +
                ' onclick="staffGoPage(' + (currentPage + 1) + ')">' +
                '<i class="fas fa-chevron-right"></i></button>';
        html += '</div>';
        paginEl.innerHTML = html;
    }

    function buildPageRange(current, max) {
        if (max <= 7) {
            var arr = [];
            for (var i = 1; i <= max; i++) arr.push(i);
            return arr;
        }
        var result = [1];
        if (current > 3) result.push('...');
        var from = Math.max(2, current - 1);
        var to   = Math.min(max - 1, current + 1);
        for (var i = from; i <= to; i++) result.push(i);
        if (current < max - 2) result.push('...');
        result.push(max);
        return result;
    }

    window.staffGoPage = function (p) {
        state.page = p;
        applyFilters();
        var section = document.querySelector('.staff-section');
        if (section) section.scrollIntoView({ behavior: 'smooth', block: 'start' });
    };

    if (searchEl) {
        searchEl.addEventListener('input', function () {
            state.search = this.value; state.page = 1; applyFilters();
        });
    }
    if (filterEl) {
        filterEl.addEventListener('change', function () {
            state.subject = this.value; state.page = 1; applyFilters();
        });
    }
    if (perPageEl) {
        perPageEl.addEventListener('change', function () {
            state.perPage = parseInt(this.value); state.page = 1; applyFilters();
        });
    }

    window.staffResetFilter = function () {
        state = { search: '', subject: 'all', perPage: 12, page: 1 };
        if (searchEl)  searchEl.value  = '';
        if (filterEl)  filterEl.value  = 'all';
        if (perPageEl) perPageEl.value = '12';
        applyFilters();
    };

    applyFilters();
})();
</script>

<?php get_footer(); ?>
