<?php
/**
 * Template Name: Halaman Galeri
 * Description: Full gallery page with filter, masonry grid, and lightbox
 */

get_header();

// Fetch ALL published gallery items grouped by category
$all_gallery = new WP_Query([
    'post_type'      => 'gallery_item',
    'posts_per_page' => -1,
    'post_status'    => 'publish',
    'orderby'        => 'date',
    'order'          => 'DESC',
]);

// Build lightbox data array
$lightbox_items = [];
$gallery_by_cat = ['all' => []]; // slug => [items]
$cat_terms_used = []; // slug => name

if ($all_gallery->have_posts()) {
    while ($all_gallery->have_posts()) {
        $all_gallery->the_post();
        $pid      = get_the_ID();
        $full     = get_the_post_thumbnail_url($pid, 'full');
        $large    = get_the_post_thumbnail_url($pid, 'large') ?: $full;
        $caption  = get_post_meta($pid, '_gallery_caption', true) ?: get_the_title();
        $terms    = get_the_terms($pid, 'gallery_category');
        $cat_slug = ($terms && ! is_wp_error($terms)) ? $terms[0]->slug : 'lainnya';
        $cat_name = ($terms && ! is_wp_error($terms)) ? $terms[0]->name : 'Lainnya';

        if ($cat_slug !== 'lainnya') {
            $cat_terms_used[$cat_slug] = $cat_name;
        }

        $item = compact('pid', 'full', 'large', 'caption', 'cat_slug');
        $gallery_by_cat['all'][] = $item;
        $gallery_by_cat[$cat_slug][] = $item;
        $lightbox_items[] = ['src' => $full, 'caption' => $caption, 'cat' => $cat_slug];
    }
    wp_reset_postdata();
}

// Preferred order for categories
$cat_order = ['academic' => 'Akademik', 'sports' => 'Olahraga', 'arts' => 'Seni', 'events' => 'Kegiatan'];
// Merge explicitly ordered + any extra
$cat_filter_list = [];
foreach ($cat_order as $slug => $name) {
    if (isset($cat_terms_used[$slug])) $cat_filter_list[$slug] = $name;
}
foreach ($cat_terms_used as $slug => $name) {
    if (! isset($cat_filter_list[$slug])) $cat_filter_list[$slug] = $name;
}
?>

<div class="gallery-page">

    <!-- ==================== PAGE HERO (unified design) ==================== -->
    <?php
    // Build stat badges for hero extra row
    $iph_extra = '';
    if ( ! empty( $gallery_by_cat['all'] ) ) {
        $iph_extra  = '<span class="iph-stat-badge"><i class="fas fa-images" aria-hidden="true"></i> '
                    . count( $gallery_by_cat['all'] ) . ' Foto</span>';
        $iph_extra .= '<span class="iph-stat-badge"><i class="fas fa-tags" aria-hidden="true"></i> '
                    . count( $cat_filter_list ) . ' Kategori</span>';
    }
    get_template_part( 'template-parts/inner-page-hero', null, array(
        'eyebrow_icon' => 'fas fa-images',
        'eyebrow_text' => 'Dokumentasi Sekolah',
        'title'        => 'Galeri',
        'title_accent' => 'Foto',
        'description'  => 'Dokumentasi kegiatan, prestasi, dan momen berkesan SMAN 1 Purwokerto.',
        'breadcrumb'   => 'Galeri',
        'extra_html'   => $iph_extra,
    ) );
    ?>

    <!-- ==================== GALLERY MAIN ==================== -->
    <section class="gallery-page-main section">
        <div class="container">

            <!-- Filter Bar -->
            <div class="gallery-page-filter-wrap" data-aos="fade-up">
                <div class="gallery-page-filter">
                    <button class="filter-btn active" data-filter="all">
                        <i class="fas fa-th"></i> Semua
                        <span class="filter-count"><?php echo count($gallery_by_cat['all']); ?></span>
                    </button>
                    <?php foreach ($cat_filter_list as $slug => $name) :
                        $count = isset($gallery_by_cat[$slug]) ? count($gallery_by_cat[$slug]) : 0;
                        if ($count === 0) continue;
                        $icon_map = [
                            'academic' => 'fa-graduation-cap',
                            'sports'   => 'fa-running',
                            'arts'     => 'fa-palette',
                            'events'   => 'fa-calendar-star',
                        ];
                        $icon = 'fas ' . ($icon_map[$slug] ?? 'fa-tag');
                    ?>
                    <button class="filter-btn" data-filter="<?php echo esc_attr($slug); ?>">
                        <i class="<?php echo esc_attr($icon); ?>"></i> <?php echo esc_html($name); ?>
                        <span class="filter-count"><?php echo $count; ?></span>
                    </button>
                    <?php endforeach; ?>
                </div>

                <div class="gallery-page-filter-right">
                    <!-- Per-page selector -->
                    <div class="gallery-perpage-wrap">
                        <label for="galleryPerPage" class="gallery-perpage-label">Tampilkan:</label>
                        <div class="acharchive-select-wrap">
                            <select id="galleryPerPage" class="acharchive-select" aria-label="Jumlah foto per halaman">
                                <option value="12">12 foto</option>
                                <option value="24">24 foto</option>
                                <option value="48">48 foto</option>
                                <option value="-1">Semua</option>
                            </select>
                        </div>
                    </div>
                    <!-- Search -->
                    <div class="gallery-search-wrap">
                        <i class="fas fa-search"></i>
                        <input type="text" id="gallerySearch" placeholder="Cari foto..." autocomplete="off">
                    </div>
                </div>
            </div>

            <!-- Grid -->
            <?php if (empty($gallery_by_cat['all'])) : ?>
            <div class="gallery-empty" data-aos="fade-up">
                <i class="fas fa-images"></i>
                <h3>Galeri Masih Kosong</h3>
                <p>Belum ada foto yang ditambahkan. Silakan upload foto melalui menu <strong>Galeri</strong> di WordPress Admin.</p>
                <?php if (current_user_can('manage_options')) : ?>
                <a href="<?php echo esc_url(admin_url('post-new.php?post_type=gallery_item')); ?>" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tambah Foto Pertama
                </a>
                <?php endif; ?>
            </div>
            <?php else : ?>
            <div class="gallery-page-grid" id="galleryPageGrid" data-aos="fade-up">
                <?php foreach ($gallery_by_cat['all'] as $idx => $item) : ?>
                <div class="gallery-page-item"
                     data-category="<?php echo esc_attr($item['cat_slug']); ?>"
                     data-lightbox-index="<?php echo $idx; ?>"
                     data-caption="<?php echo esc_attr($item['caption']); ?>"
                     role="button"
                     tabindex="0"
                     aria-label="Buka preview: <?php echo esc_attr($item['caption']); ?>">
                    <img src="<?php echo esc_url($item['large']); ?>"
                         alt="<?php echo esc_attr($item['caption']); ?>"
                         loading="lazy">
                    <div class="gallery-overlay">
                        <i class="fas fa-expand-alt"></i>
                        <span><?php echo esc_html($item['caption']); ?></span>
                        <?php if (isset($cat_filter_list[$item['cat_slug']])) : ?>
                        <small class="gallery-overlay-cat"><?php echo esc_html($cat_filter_list[$item['cat_slug']]); ?></small>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- No results message (hidden until JS filter empties grid) -->
            <div class="gallery-no-results" id="galleryNoResults" style="display:none;">
                <i class="fas fa-search"></i>
                <p>Tidak ada foto yang cocok.</p>
            </div>

            <!-- Load More (if needed) -->
            <div class="text-center" style="margin-top:2.5rem;" data-aos="fade-up">
                <p class="gallery-result-count" id="galleryResultCount">
                    Menampilkan <strong><?php echo count($gallery_by_cat['all']); ?></strong> foto
                </p>
            </div>
            <?php endif; ?>

        </div>
    </section>

</div><!-- .gallery-page -->

<!-- ==================== LIGHTBOX ==================== -->
<div class="gallery-lightbox" id="galleryLightbox" role="dialog" aria-modal="true" aria-label="Preview Foto" style="display:none;">
    <div class="lightbox-backdrop" id="lightboxBackdrop"></div>
    <div class="lightbox-container" role="document">
        <button class="lightbox-close" id="lightboxClose" aria-label="Tutup preview">
            <i class="fas fa-times"></i>
        </button>
        <button class="lightbox-nav lightbox-prev" id="lightboxPrev" aria-label="Foto sebelumnya">
            <i class="fas fa-chevron-left"></i>
        </button>
        <button class="lightbox-nav lightbox-next" id="lightboxNext" aria-label="Foto berikutnya">
            <i class="fas fa-chevron-right"></i>
        </button>
        <div class="lightbox-image-wrap">
            <div class="lightbox-spinner" id="lightboxSpinner">
                <i class="fas fa-circle-notch fa-spin"></i>
            </div>
            <img src="" alt="" id="lightboxImg" class="lightbox-img">
        </div>
        <div class="lightbox-footer">
            <span class="lightbox-caption" id="lightboxCaption"></span>
            <span class="lightbox-counter" id="lightboxCounter"></span>
        </div>
        <div class="lightbox-thumbs" id="lightboxThumbs"></div>
    </div>
</div>

<!-- Pass lightbox data to JS -->
<script>
    window.pageGalleryItems = <?php echo json_encode($lightbox_items, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>;
</script>

<!-- Self-contained gallery page filter/search/per-page logic -->
<script>
(function () {
    var state = { filter: 'all', perPage: 12, search: '' };

    function applyFilters() {
        var items    = Array.prototype.slice.call(document.querySelectorAll('.gallery-page-item'));
        var noRes    = document.getElementById('galleryNoResults');
        var countEl  = document.getElementById('galleryResultCount');
        var shown    = 0;
        var matched  = 0;
        var q        = state.search.toLowerCase();
        var lightboxVisible = [];

        items.forEach(function (item) {
            var cat     = item.getAttribute('data-category') || '';
            var caption = (item.getAttribute('data-caption') || '').toLowerCase();
            var catMatch    = (state.filter === 'all' || cat === state.filter);
            var searchMatch = (!q || caption.includes(q) || cat.includes(q));

            if (catMatch && searchMatch) {
                matched++;
                var withinLimit = (state.perPage === -1 || shown < state.perPage);
                if (withinLimit) {
                    item.classList.remove('is-hidden');
                    item.setAttribute('data-lightbox-index', shown);
                    // mirror into lightbox array
                    var origIdx = parseInt(item.getAttribute('data-orig-index') || item.getAttribute('data-lightbox-index'), 10);
                    if (window.pageGalleryItems && window.pageGalleryItems[origIdx]) {
                        lightboxVisible.push(window.pageGalleryItems[origIdx]);
                    }
                    shown++;
                } else {
                    item.classList.add('is-hidden');
                }
            } else {
                item.classList.add('is-hidden');
            }
        });

        // Update lightbox items to only visible set
        if (window.pageGalleryItems) {
            window._galleryPageVisible = lightboxVisible;
        }

        if (noRes)   noRes.style.display   = (matched === 0) ? 'block' : 'none';
        if (countEl) {
            var label = state.perPage === -1 || shown >= matched
                ? 'Menampilkan <strong>' + shown + '</strong> foto'
                : 'Menampilkan <strong>' + shown + '</strong> dari <strong>' + matched + '</strong> foto';
            countEl.innerHTML = label;
        }
    }

    function init() {
        // Store original indices once
        var items = document.querySelectorAll('.gallery-page-item');
        items.forEach(function (item, i) {
            item.setAttribute('data-orig-index', i);
        });

        // Filter buttons
        var filterBtns = document.querySelectorAll('.gallery-page-filter .filter-btn');
        filterBtns.forEach(function (btn) {
            btn.addEventListener('click', function () {
                filterBtns.forEach(function (b) { b.classList.remove('active'); });
                this.classList.add('active');
                state.filter = this.getAttribute('data-filter');
                applyFilters();
            });
        });

        // Per-page select
        var perPageSel = document.getElementById('galleryPerPage');
        if (perPageSel) {
            perPageSel.addEventListener('change', function () {
                state.perPage = parseInt(this.value, 10);
                applyFilters();
            });
        }

        // Search
        var searchEl = document.getElementById('gallerySearch');
        if (searchEl) {
            searchEl.addEventListener('input', function () {
                state.search = this.value.trim();
                applyFilters();
            });
        }

        applyFilters();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
</script>

<?php get_footer(); ?>
