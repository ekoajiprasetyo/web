<?php
/**
 * Gallery Item — Custom Post Type, Taxonomy, Meta Boxes & Admin UI
 * File: gallery-setup.php
 */

// =====================================================================
// 1. CPT: gallery_item
// =====================================================================
function sman1_register_gallery_cpt() {
    $labels = [
        'name'               => 'Galeri',
        'singular_name'      => 'Item Galeri',
        'menu_name'          => 'Galeri',
        'add_new'            => 'Tambah Foto',
        'add_new_item'       => 'Tambah Item Galeri Baru',
        'edit_item'          => 'Edit Item Galeri',
        'new_item'           => 'Item Baru',
        'view_item'          => 'Lihat Item',
        'search_items'       => 'Cari Galeri',
        'not_found'          => 'Tidak ada item galeri ditemukan',
        'not_found_in_trash' => 'Tidak ada item galeri di sampah',
        'all_items'          => 'Semua Foto',
    ];

    register_post_type('gallery_item', [
        'labels'              => $labels,
        'public'              => true,
        'has_archive'         => false,
        'show_in_rest'        => true,
        'menu_icon'           => 'dashicons-format-gallery',
        'menu_position'       => 25,
        'supports'            => ['title', 'thumbnail'],
        'rewrite'             => ['slug' => 'galeri-foto'],
        'show_in_nav_menus'   => false,
    ]);
}
add_action('init', 'sman1_register_gallery_cpt');

// =====================================================================
// 2. Taxonomy: gallery_category
// =====================================================================
function sman1_register_gallery_taxonomy() {
    $labels = [
        'name'          => 'Kategori Galeri',
        'singular_name' => 'Kategori Galeri',
        'menu_name'     => 'Kategori',
        'all_items'     => 'Semua Kategori',
        'add_new_item'  => 'Tambah Kategori Baru',
        'edit_item'     => 'Edit Kategori',
        'search_items'  => 'Cari Kategori',
    ];

    register_taxonomy('gallery_category', 'gallery_item', [
        'labels'            => $labels,
        'hierarchical'      => true,
        'show_in_rest'      => true,
        'show_admin_column' => false, // we handle column manually
        'rewrite'           => ['slug' => 'kategori-galeri'],
    ]);
}
add_action('init', 'sman1_register_gallery_taxonomy');

// =====================================================================
// 3. Insert default taxonomy terms (runs once, safe to re-run)
// =====================================================================
function sman1_insert_gallery_default_terms() {
    $terms = [
        'Akademik' => 'academic',
        'Olahraga' => 'sports',
        'Seni'     => 'arts',
        'Kegiatan' => 'events',
    ];
    foreach ($terms as $name => $slug) {
        if (! term_exists($slug, 'gallery_category')) {
            wp_insert_term($name, 'gallery_category', ['slug' => $slug]);
        }
    }
}
add_action('init', 'sman1_insert_gallery_default_terms');

// =====================================================================
// 4. Meta Box: Caption / Keterangan Foto
// =====================================================================
function sman1_gallery_add_meta_boxes() {
    add_meta_box(
        'gallery_item_details',
        '📷 Detail Foto',
        'sman1_gallery_meta_box_render',
        'gallery_item',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'sman1_gallery_add_meta_boxes');

function sman1_gallery_meta_box_render($post) {
    wp_nonce_field('sman1_gallery_save', 'sman1_gallery_nonce');

    $caption   = get_post_meta($post->ID, '_gallery_caption',  true);
    $featured  = get_post_meta($post->ID, '_gallery_featured', true);
    $has_thumb = has_post_thumbnail($post->ID);
    ?>
    <style>
        .sman1-meta-table { width:100%; border-collapse:collapse; }
        .sman1-meta-table th { width:160px; padding:10px 0; font-weight:600; text-align:left; vertical-align:top; color:#1d2327; }
        .sman1-meta-table td { padding:10px 0; }
        .sman1-meta-table input[type="text"] { width:100%; max-width:500px; }
        .sman1-notice-thumb { background:#fff3cd; border-left:4px solid #e8b200; padding:10px 14px; margin-bottom:14px; border-radius:4px; font-size:13px; }
    </style>

    <?php if (! $has_thumb): ?>
        <div class="sman1-notice-thumb">
            ⚠️ <strong>Foto belum dipilih.</strong> Harap atur <em>Featured Image</em> di panel sebelah kanan agar gambar muncul di galeri.
        </div>
    <?php endif; ?>

    <table class="sman1-meta-table">
        <tr>
            <th><label for="gallery_caption">Keterangan Foto</label></th>
            <td>
                <input type="text"
                       id="gallery_caption"
                       name="gallery_caption"
                       value="<?php echo esc_attr($caption); ?>"
                       placeholder="Contoh: Olimpiade Sains Nasional 2025">
                <p class="description">Teks singkat yang tampil saat foto di-hover dan di lightbox preview.</p>
            </td>
        </tr>
        <tr>
            <th><label for="gallery_featured">Tampil Besar di Homepage</label></th>
            <td>
                <label style="display:flex;align-items:center;gap:8px;cursor:pointer;">
                    <input type="checkbox"
                           id="gallery_featured"
                           name="gallery_featured"
                           value="1"
                           <?php checked($featured, '1'); ?>>
                    <span>Tampilkan foto ini dalam ukuran besar (<code>.large</code>) di section Galeri homepage</span>
                </label>
                <p class="description" style="margin-top:4px;">Hanya satu foto yang efektif tampil besar per tampilan — pilih foto yang paling menarik.</p>
            </td>
        </tr>
    </table>
    <?php
}

function sman1_gallery_save_meta($post_id) {
    if (! isset($_POST['sman1_gallery_nonce']) ||
        ! wp_verify_nonce($_POST['sman1_gallery_nonce'], 'sman1_gallery_save')) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (! current_user_can('edit_post', $post_id)) return;

    if (isset($_POST['gallery_caption'])) {
        update_post_meta(
            $post_id,
            '_gallery_caption',
            sanitize_text_field($_POST['gallery_caption'])
        );
    }

    // Checkbox: save '1' when ticked, delete meta when unticked
    if (isset($_POST['gallery_featured']) && $_POST['gallery_featured'] === '1') {
        update_post_meta($post_id, '_gallery_featured', '1');
    } else {
        delete_post_meta($post_id, '_gallery_featured');
    }
}
add_action('save_post_gallery_item', 'sman1_gallery_save_meta');

// =====================================================================
// 5. Admin List Columns: Foto Thumbnail | Keterangan | Kategori | Urutan
// =====================================================================
function sman1_gallery_admin_columns($columns) {
    $new = [];
    foreach ($columns as $key => $label) {
        if ($key === 'cb') {
            $new[$key] = $label;
            continue;
        }
        if ($key === 'title') {
            $new['gallery_thumb']    = 'Foto';
            $new[$key]               = $label;
            $new['gallery_caption']  = 'Keterangan';
            $new['gallery_cat']      = 'Kategori';
            $new['gallery_featured'] = '⭐ Besar';
            continue;
        }
        if ($key === 'date') {
            $new[$key] = $label;
            continue;
        }
        $new[$key] = $label;
    }
    return $new;
}
add_filter('manage_gallery_item_posts_columns', 'sman1_gallery_admin_columns');

function sman1_gallery_admin_column_values($column, $post_id) {
    switch ($column) {
        case 'gallery_thumb':
            $thumb = get_the_post_thumbnail($post_id, [70, 54]);
            echo $thumb
                ? '<div style="line-height:0;border-radius:6px;overflow:hidden;width:70px;">' . $thumb . '</div>'
                : '<span style="color:#aaa;font-size:12px;">No image</span>';
            break;

        case 'gallery_caption':
            $cap = get_post_meta($post_id, '_gallery_caption', true);
            echo $cap ? esc_html($cap) : '<em style="color:#aaa;">—</em>';
            break;

        case 'gallery_cat':
            $terms = get_the_terms($post_id, 'gallery_category');
            if ($terms && ! is_wp_error($terms)) {
                $links = array_map(function ($t) {
                    return '<a href="' . esc_url(add_query_arg(['post_type' => 'gallery_item', 'gallery_category' => $t->slug], admin_url('edit.php'))) . '">' . esc_html($t->name) . '</a>';
                }, $terms);
                echo implode(', ', $links);
            } else {
                echo '<em style="color:#aaa;">—</em>';
            }
            break;

        case 'gallery_featured':
            $val = get_post_meta($post_id, '_gallery_featured', true);
            echo $val === '1'
                ? '<span title="Tampil Besar" style="font-size:16px;">⭐</span>'
                : '<span style="color:#ccc;font-size:16px;">—</span>';
            break;
    }
}
add_action('manage_gallery_item_posts_custom_column', 'sman1_gallery_admin_column_values', 10, 2);

// Make thumbnail column narrow
function sman1_gallery_admin_column_styles() {
    $screen = get_current_screen();
    if ($screen && $screen->post_type === 'gallery_item') {
        echo '<style>
            .column-gallery_thumb { width:80px !important; }
            .column-gallery_caption { width:30%; }
            .column-gallery_cat { width:150px; }
            .column-gallery_thumb img { border-radius:6px; display:block; }
        </style>';
    }
}
add_action('admin_head', 'sman1_gallery_admin_column_styles');

// Quick filter by category in admin list
function sman1_gallery_category_filter() {
    global $typenow;
    if ($typenow !== 'gallery_item') return;

    $taxonomy = 'gallery_category';
    $selected = isset($_GET[$taxonomy]) ? sanitize_text_field($_GET[$taxonomy]) : '';
    $terms    = get_terms(['taxonomy' => $taxonomy, 'hide_empty' => false]);

    if (empty($terms) || is_wp_error($terms)) return;

    echo '<select name="' . esc_attr($taxonomy) . '">';
    echo '<option value="">Semua Kategori</option>';
    foreach ($terms as $term) {
        printf(
            '<option value="%s"%s>%s (%d)</option>',
            esc_attr($term->slug),
            selected($selected, $term->slug, false),
            esc_html($term->name),
            $term->count
        );
    }
    echo '</select>';
}
add_action('restrict_manage_posts', 'sman1_gallery_category_filter');

// =====================================================================
// 6. Settings page: "Halaman Galeri" link config
// =====================================================================
function sman1_gallery_admin_submenu() {
    add_submenu_page(
        'edit.php?post_type=gallery_item',
        'Pengaturan Galeri',
        '⚙ Pengaturan',
        'manage_options',
        'sman1-gallery-settings',
        'sman1_gallery_settings_page'
    );
}
add_action('admin_menu', 'sman1_gallery_admin_submenu');

function sman1_gallery_settings_page() {
    $saved     = false;
    $page_id   = get_option('sman1_gallery_page_id', 0);
    $home_limit = get_option('sman1_gallery_home_limit', 6);

    if (isset($_POST['sman1_gallery_settings_save'])) {
        check_admin_referer('sman1_gallery_settings_nonce');
        $page_id    = intval($_POST['sman1_gallery_page_id']);
        $home_limit = intval($_POST['sman1_gallery_home_limit']);
        update_option('sman1_gallery_page_id', $page_id);
        update_option('sman1_gallery_home_limit', max(1, min(20, $home_limit)));
        $saved = true;
    }

    $pages = get_pages(['sort_column' => 'post_title']);
    ?>
    <div class="wrap">
        <h1 style="display:flex;align-items:center;gap:8px;">
            <span class="dashicons dashicons-format-gallery" style="font-size:28px;width:28px;height:28px;color:#9E1B1E;"></span>
            Pengaturan Galeri
        </h1>

        <?php if ($saved): ?>
            <div class="notice notice-success is-dismissible"><p>✅ Pengaturan berhasil disimpan.</p></div>
        <?php endif; ?>

        <form method="post" style="max-width:600px;margin-top:20px;">
            <?php wp_nonce_field('sman1_gallery_settings_nonce'); ?>

            <table class="form-table">
                <tr>
                    <th scope="row">
                        <label for="sman1_gallery_page_id">Halaman "Lihat Semua Galeri"</label>
                    </th>
                    <td>
                        <select name="sman1_gallery_page_id" id="sman1_gallery_page_id" class="regular-text">
                            <option value="0">— Pilih Halaman —</option>
                            <?php foreach ($pages as $p): ?>
                                <option value="<?php echo esc_attr($p->ID); ?>"
                                    <?php selected($page_id, $p->ID); ?>>
                                    <?php echo esc_html($p->post_title); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <p class="description">
                            Halaman yang dituju saat tombol "Lihat Semua Galeri" di homepage diklik.
                            <?php if ($page_id > 0): ?>
                                &nbsp;<a href="<?php echo esc_url(get_permalink($page_id)); ?>" target="_blank">Lihat halaman →</a>
                            <?php endif; ?>
                        </p>
                        <p class="description" style="margin-top:6px;color:#c00;">
                            💡 <strong>Cara membuat halaman galeri:</strong> Buat halaman baru di <em>Halaman → Tambah Baru</em>,
                            pilih template <strong>"Halaman Galeri"</strong> di panel Page Attributes, lalu pilih halaman tersebut di sini.
                        </p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="sman1_gallery_home_limit">Jumlah Foto di Homepage</label>
                    </th>
                    <td>
                        <input type="number" id="sman1_gallery_home_limit" name="sman1_gallery_home_limit"
                               value="<?php echo esc_attr($home_limit); ?>"
                               min="1" max="20" class="small-text">
                        <p class="description">Berapa foto yang ditampilkan di section Galeri homepage (default: 6).</p>
                    </td>
                </tr>
            </table>

            <p class="submit">
                <input type="submit" name="sman1_gallery_settings_save"
                       class="button button-primary" value="Simpan Pengaturan">
            </p>
        </form>

        <hr>
        <h2>Panduan Cepat</h2>
        <ol style="max-width:580px;line-height:1.9;">
            <li>Klik <strong>Tambah Foto</strong> → isi judul foto → klik <strong>Set Featured Image</strong> untuk upload/pilih foto.</li>
            <li>Isi kolom <strong>Keterangan Foto</strong> (teks yang muncul saat hover & di lightbox).</li>
            <li>Pilih <strong>Kategori</strong> di panel kanan (Akademik, Olahraga, Seni, atau Kegiatan).</li>
            <li>Klik <strong>Terbitkan</strong> — foto otomatis muncul di homepage dan halaman galeri.</li>
        </ol>
    </div>
    <?php
}
