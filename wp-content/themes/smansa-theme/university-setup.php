<?php
/**
 * Universitas Tujuan Alumni
 * - CPT for university logo entries
 * - Admin columns + meta box
 * - Settings submenu for fixed logo size
 */

// =========================================================
// 1) Register CPT: accepted_university
// =========================================================
add_action('init', function () {
    register_post_type('accepted_university', array(
        'labels' => array(
            'name'               => 'Universitas',
            'singular_name'      => 'Universitas',
            'add_new'            => 'Tambah Universitas',
            'add_new_item'       => 'Tambah Universitas Baru',
            'edit_item'          => 'Edit Universitas',
            'new_item'           => 'Universitas Baru',
            'view_item'          => 'Lihat Universitas',
            'search_items'       => 'Cari Universitas',
            'not_found'          => 'Tidak ada data universitas.',
            'not_found_in_trash' => 'Tidak ada data universitas di sampah.',
            'all_items'          => 'Semua Universitas',
            'menu_name'          => 'Universitas',
        ),
        'public'              => false,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_admin_bar'   => true,
        'show_in_nav_menus'   => false,
        'menu_position'       => 26,
        'menu_icon'           => 'dashicons-welcome-learn-more',
        'supports'            => array('title', 'thumbnail', 'page-attributes'),
        'has_archive'         => false,
        'rewrite'             => false,
        'exclude_from_search' => true,
        'show_in_rest'        => false,
    ));
});

// =========================================================
// 2) Meta box: website URL + short name
// =========================================================
add_action('add_meta_boxes', function () {
    add_meta_box(
        'sman1_uni_meta_box',
        'Detail Universitas',
        'sman1_render_uni_meta_box',
        'accepted_university',
        'normal',
        'high'
    );
});

function sman1_render_uni_meta_box($post) {
    wp_nonce_field('sman1_uni_meta_save', 'sman1_uni_meta_nonce');

    $url       = get_post_meta($post->ID, '_uni_url', true);
    $shortname = get_post_meta($post->ID, '_uni_short_name', true);
    ?>
    <table class="form-table" role="presentation">
        <tr>
            <th scope="row"><label for="uni_short_name">Nama Singkat</label></th>
            <td>
                <input type="text" class="regular-text" id="uni_short_name" name="uni_short_name" value="<?php echo esc_attr($shortname); ?>" placeholder="Contoh: UI, UGM, ITB">
                <p class="description">Opsional. Jika kosong, akan menggunakan Judul Universitas.</p>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="uni_url">Website Universitas</label></th>
            <td>
                <input type="url" class="regular-text" id="uni_url" name="uni_url" value="<?php echo esc_attr($url); ?>" placeholder="https://www.example.ac.id">
                <p class="description">Opsional. Jika diisi, logo akan menjadi tautan yang dapat diklik.</p>
            </td>
        </tr>
        <tr>
            <th scope="row">Logo</th>
            <td>
                <p class="description">Set logo melalui panel <strong>Featured Image</strong> di sisi kanan editor.</p>
            </td>
        </tr>
    </table>
    <?php
}

add_action('save_post_accepted_university', function ($post_id) {
    if (!isset($_POST['sman1_uni_meta_nonce']) || !wp_verify_nonce($_POST['sman1_uni_meta_nonce'], 'sman1_uni_meta_save')) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    $shortname = isset($_POST['uni_short_name']) ? sanitize_text_field($_POST['uni_short_name']) : '';
    $url       = isset($_POST['uni_url']) ? esc_url_raw($_POST['uni_url']) : '';

    update_post_meta($post_id, '_uni_short_name', $shortname);
    update_post_meta($post_id, '_uni_url', $url);
});

// =========================================================
// 3) Admin columns
// =========================================================
add_filter('manage_accepted_university_posts_columns', function ($columns) {
    $new = array();
    foreach ($columns as $key => $label) {
        if ($key === 'cb') {
            $new[$key] = $label;
            continue;
        }
        if ($key === 'title') {
            $new['uni_logo'] = 'Logo';
            $new[$key]       = $label;
            $new['uni_short_name'] = 'Nama Singkat';
            $new['uni_url']  = 'Website';
            continue;
        }
        $new[$key] = $label;
    }
    return $new;
});

add_action('manage_accepted_university_posts_custom_column', function ($column, $post_id) {
    if ($column === 'uni_logo') {
        if (has_post_thumbnail($post_id)) {
            echo '<div style="width:72px;height:48px;border:1px solid #e5e7eb;border-radius:8px;display:flex;align-items:center;justify-content:center;background:#fff;overflow:hidden;">';
            echo get_the_post_thumbnail($post_id, array(72, 48), array('style' => 'max-width:100%;max-height:100%;object-fit:contain;'));
            echo '</div>';
        } else {
            echo '<span style="color:#9ca3af;">No logo</span>';
        }
    }

    if ($column === 'uni_short_name') {
        $shortname = get_post_meta($post_id, '_uni_short_name', true);
        echo $shortname ? esc_html($shortname) : '<span style="color:#9ca3af;">—</span>';
    }

    if ($column === 'uni_url') {
        $url = get_post_meta($post_id, '_uni_url', true);
        if ($url) {
            echo '<a href="' . esc_url($url) . '" target="_blank" rel="noopener noreferrer">Buka Link</a>';
        } else {
            echo '<span style="color:#9ca3af;">—</span>';
        }
    }
}, 10, 2);

add_action('admin_head', function () {
    $screen = function_exists('get_current_screen') ? get_current_screen() : null;
    if (!$screen || $screen->post_type !== 'accepted_university') {
        return;
    }
    echo '<style>
        .column-uni_logo { width: 90px; }
        .column-uni_short_name { width: 140px; }
        .column-uni_url { width: 120px; }
    </style>';
});

// =========================================================
// 4) Settings submenu (fixed logo size)
// =========================================================
add_action('admin_menu', function () {
    add_submenu_page(
        'edit.php?post_type=accepted_university',
        'Pengaturan Logo Universitas',
        '⚙ Pengaturan Logo',
        'manage_options',
        'sman1-university-logo-settings',
        'sman1_render_university_logo_settings'
    );
});

function sman1_render_university_logo_settings() {
    if (!current_user_can('manage_options')) {
        return;
    }

    $saved = false;
    if (isset($_POST['sman1_uni_size_save'])) {
        check_admin_referer('sman1_uni_size_nonce');

        $w = isset($_POST['sman1_uni_logo_width']) ? intval($_POST['sman1_uni_logo_width']) : 140;
        $h = isset($_POST['sman1_uni_logo_height']) ? intval($_POST['sman1_uni_logo_height']) : 80;

        $w = max(80, min(260, $w));
        $h = max(40, min(180, $h));

        update_option('sman1_uni_logo_width', $w);
        update_option('sman1_uni_logo_height', $h);
        $saved = true;
    }

    $w = intval(get_option('sman1_uni_logo_width', 140));
    $h = intval(get_option('sman1_uni_logo_height', 80));
    ?>
    <div class="wrap">
        <h1>Pengaturan Logo Universitas</h1>
        <?php if ($saved) : ?>
            <div class="notice notice-success is-dismissible"><p>Pengaturan ukuran logo berhasil disimpan.</p></div>
        <?php endif; ?>

        <form method="post" style="max-width:640px;">
            <?php wp_nonce_field('sman1_uni_size_nonce'); ?>
            <table class="form-table" role="presentation">
                <tr>
                    <th scope="row"><label for="sman1_uni_logo_width">Lebar Logo (px)</label></th>
                    <td>
                        <input type="number" id="sman1_uni_logo_width" name="sman1_uni_logo_width" min="80" max="260" value="<?php echo esc_attr($w); ?>" class="small-text"> px
                        <p class="description">Rekomendasi: 120–160 px</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="sman1_uni_logo_height">Tinggi Logo (px)</label></th>
                    <td>
                        <input type="number" id="sman1_uni_logo_height" name="sman1_uni_logo_height" min="40" max="180" value="<?php echo esc_attr($h); ?>" class="small-text"> px
                        <p class="description">Rekomendasi: 64–96 px</p>
                    </td>
                </tr>
            </table>

            <p class="submit">
                <button type="submit" name="sman1_uni_size_save" class="button button-primary">Simpan Pengaturan</button>
            </p>
        </form>

        <hr>
        <h2>Tips Upload Logo</h2>
        <ul style="line-height:1.8;">
            <li>Gunakan file PNG transparan untuk hasil terbaik.</li>
            <li>Rasio logo bebas, sistem akan menyesuaikan otomatis dengan <code>object-fit: contain</code>.</li>
            <li>Gunakan latar belakang putih/terang agar terlihat jelas.</li>
        </ul>
    </div>
    <?php
}
