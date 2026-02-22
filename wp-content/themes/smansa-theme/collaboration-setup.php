<?php
/**
 * Kolaborasi & Mitra — Custom Post Type
 *
 * Mengelola daftar tautan kolaborasi/kemitraan yang muncul
 * di kolom "Kolaborasi" pada footer beranda.
 *
 * Admin path : WP Admin → Kolaborasi → Tambah Mitra Baru
 * Fields     : Nama mitra (judul), URL, Deskripsi singkat, Logo (featured image)
 * Urutan     : via field "Urutan" (page-attributes / menu_order)
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// =========================================================
// 1. Register Custom Post Type: collaboration_link
// =========================================================
add_action( 'init', 'sman1_register_collaboration_cpt' );

function sman1_register_collaboration_cpt() {
    register_post_type( 'collaboration_link', array(
        'labels' => array(
            'name'               => 'Kolaborasi',
            'singular_name'      => 'Mitra Kolaborasi',
            'add_new'            => 'Tambah Mitra',
            'add_new_item'       => 'Tambah Mitra Baru',
            'edit_item'          => 'Edit Mitra',
            'new_item'           => 'Mitra Baru',
            'view_item'          => 'Lihat Mitra',
            'search_items'       => 'Cari Mitra',
            'not_found'          => 'Tidak ada mitra ditemukan.',
            'not_found_in_trash' => 'Tidak ada mitra di Trash.',
            'menu_name'          => 'Kolaborasi',
            'all_items'          => 'Semua Mitra',
        ),
        'public'            => false,
        'show_ui'           => true,
        'show_in_menu'      => true,
        'show_in_nav_menus' => false,
        'show_in_admin_bar' => true,
        'menu_position'     => 28,
        'menu_icon'         => 'dashicons-admin-links',
        'supports'          => array( 'title', 'thumbnail', 'page-attributes' ),
        'has_archive'       => false,
        'rewrite'           => false,
        'capability_type'   => 'post',
    ) );
}

// =========================================================
// 2. Meta Box: URL + Deskripsi Singkat
// =========================================================
add_action( 'add_meta_boxes', 'sman1_collab_meta_boxes' );

function sman1_collab_meta_boxes() {
    add_meta_box(
        'sman1_collab_detail',
        '🔗 Detail Mitra Kolaborasi',
        'sman1_collab_meta_box_html',
        'collaboration_link',
        'normal',
        'high'
    );
}

function sman1_collab_meta_box_html( $post ) {
    wp_nonce_field( 'sman1_collab_save', 'sman1_collab_nonce' );
    $url  = get_post_meta( $post->ID, '_collab_url',  true );
    $desc = get_post_meta( $post->ID, '_collab_desc', true );
    $icon = get_post_meta( $post->ID, '_collab_icon', true ) ?: 'fas fa-link';

    // Suggested icon presets
    $presets = array(
        'fas fa-university'         => 'Universitas',
        'fas fa-school'             => 'Sekolah',
        'fas fa-landmark'           => 'Lembaga',
        'fas fa-building'           => 'Instansi',
        'fas fa-hospital'           => 'Rumah Sakit',
        'fas fa-flask'              => 'Riset / Lab',
        'fas fa-briefcase'          => 'Perusahaan',
        'fas fa-handshake'          => 'Kemitraan',
        'fas fa-globe'              => 'Situs Web',
        'fas fa-link'               => 'Tautan Umum',
        'fas fa-book-open'          => 'Perpustakaan',
        'fas fa-chalkboard-teacher' => 'Pendidikan',
        'fas fa-atom'               => 'Sains / STEM',
        'fas fa-palette'            => 'Seni / Budaya',
        'fas fa-trophy'             => 'Prestasi',
        'fas fa-leaf'               => 'Lingkungan',
        'fas fa-heartbeat'          => 'Kesehatan',
        'fas fa-gavel'              => 'Hukum / Pemerintah',
        'fas fa-industry'           => 'Industri',
        'fas fa-satellite-dish'     => 'Media / Komunikasi',
    );
    ?>
    <table class="form-table" style="margin: 0.5rem 0;">
        <tr>
            <th scope="row" style="width: 160px;">
                <label for="collab_icon">Icon Font Awesome</label>
            </th>
            <td>
                <div style="display:flex;align-items:center;gap:10px;margin-bottom:6px;">
                    <span id="collab_icon_preview" style="font-size:1.3rem;color:#9E1B1E;width:26px;text-align:center;">
                        <i class="<?php echo esc_attr( $icon ); ?>"></i>
                    </span>
                    <input type="text"
                        id="collab_icon"
                        name="collab_icon"
                        value="<?php echo esc_attr( $icon ); ?>"
                        class="regular-text"
                        placeholder="fas fa-link"
                        style="font-family:monospace;"
                        oninput="document.querySelector('#collab_icon_preview i').className=this.value||'fas fa-link'">
                </div>
                <p class="description">
                    Kelas Font Awesome, contoh: <code>fas fa-university</code>.
                    Cari di <a href="https://fontawesome.com/icons" target="_blank">fontawesome.com/icons</a>.
                    Atau pilih preset:
                </p>
                <div style="display:flex;flex-wrap:wrap;gap:5px;margin-top:8px;">
                    <?php foreach ( $presets as $cls => $label ) : ?>
                    <button type="button"
                        onclick="document.getElementById('collab_icon').value='<?php echo esc_js( $cls ); ?>';document.querySelector('#collab_icon_preview i').className='<?php echo esc_js( $cls ); ?>';"
                        title="<?php echo esc_attr( $cls ); ?>"
                        style="display:flex;align-items:center;gap:5px;padding:4px 10px;border:1px solid #ddd;border-radius:5px;background:#f9f9f9;cursor:pointer;font-size:12px;">
                        <i class="<?php echo esc_attr( $cls ); ?>" style="color:#9E1B1E;"></i>
                        <?php echo esc_html( $label ); ?>
                    </button>
                    <?php endforeach; ?>
                </div>
            </td>
        </tr>
        <tr>
            <th scope="row" style="width: 160px;">
                <label for="collab_url">
                    <strong>URL / Tautan</strong>
                    <span style="color: #d63638;">*</span>
                </label>
            </th>
            <td>
                <input type="url"
                    id="collab_url"
                    name="collab_url"
                    value="<?php echo esc_attr( $url ); ?>"
                    class="large-text"
                    placeholder="https://situspartner.ac.id/">
                <p class="description">URL lengkap situs mitra (diawali https://). Wajib diisi.</p>
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label for="collab_desc">Keterangan Singkat</label>
            </th>
            <td>
                <input type="text"
                    id="collab_desc"
                    name="collab_desc"
                    value="<?php echo esc_attr( $desc ); ?>"
                    class="regular-text"
                    placeholder="contoh: Universitas Jenderal Soedirman">
                <p class="description">Opsional — ditampilkan sebagai tooltip atau sub-teks di footer.</p>
            </td>
        </tr>
    </table>
    <p style="color: #666; font-size: 12px; margin-top: 8px; border-top: 1px solid #eee; padding-top: 8px;">
        <strong>💡 Tips:</strong>
        Upload logo mitra via kotak <em>Featured Image</em> (gambar unggulan) di sebelah kanan. Urutan tampil diatur lewat kolom <em>Urutan</em> di bawah.
    </p>
    <?php
}

add_action( 'save_post_collaboration_link', 'sman1_collab_save_meta' );

function sman1_collab_save_meta( $post_id ) {
    if (
        ! isset( $_POST['sman1_collab_nonce'] ) ||
        ! wp_verify_nonce( $_POST['sman1_collab_nonce'], 'sman1_collab_save' )
    ) {
        return;
    }
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    if ( isset( $_POST['collab_url'] ) ) {
        update_post_meta( $post_id, '_collab_url', esc_url_raw( trim( $_POST['collab_url'] ) ) );
    }
    if ( isset( $_POST['collab_desc'] ) ) {
        update_post_meta( $post_id, '_collab_desc', sanitize_text_field( $_POST['collab_desc'] ) );
    }
    if ( isset( $_POST['collab_icon'] ) ) {
        // Allow only valid FA class characters: letters, numbers, spaces, hyphens
        $raw_icon = preg_replace( '/[^a-z0-9\s\-]/i', '', $_POST['collab_icon'] );
        update_post_meta( $post_id, '_collab_icon', sanitize_text_field( trim( $raw_icon ) ) );
    }
}

// =========================================================
// 3. Admin list columns: Logo, URL, Deskripsi, Urutan
// =========================================================
add_filter( 'manage_collaboration_link_posts_columns', 'sman1_collab_columns' );

function sman1_collab_columns( $cols ) {
    $new = array();
    foreach ( $cols as $key => $label ) {
        if ( $key === 'title' ) {
            $new['collab_logo']  = 'Logo';
        }
        $new[ $key ] = $label;
        if ( $key === 'title' ) {
            $new['collab_url']   = 'URL';
            $new['collab_desc']  = 'Keterangan';
            $new['collab_order'] = 'Urutan';
        }
    }
    unset( $new['date'] );
    return $new;
}

add_action( 'manage_collaboration_link_posts_custom_column', 'sman1_collab_column_values', 10, 2 );

function sman1_collab_column_values( $col, $post_id ) {
    switch ( $col ) {
        case 'collab_logo':
            if ( has_post_thumbnail( $post_id ) ) {
                echo get_the_post_thumbnail( $post_id, array( 64, 36 ), array( 'style' => 'border-radius:4px;object-fit:contain;background:#f0f0f1;border:1px solid #ddd;' ) );
            } else {
                echo '<span style="color:#aaa;font-size:11px;">—</span>';
            }
            break;
        case 'collab_url':
            $url  = get_post_meta( $post_id, '_collab_url', true );
            $icon = get_post_meta( $post_id, '_collab_icon', true ) ?: 'fas fa-link';
            if ( $url ) {
                echo '<div style="display:flex;align-items:center;gap:8px;">';
                echo '<i class="' . esc_attr( $icon ) . '" style="color:#9E1B1E;font-size:1.1rem;width:18px;text-align:center;"></i>';
                echo '<a href="' . esc_url( $url ) . '" target="_blank" rel="noopener" style="max-width:190px;display:block;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;font-size:12px;">' . esc_html( $url ) . '</a>';
                echo '</div>';
            } else {
                echo '<span style="color:#d63638;">⚠ Belum diisi</span>';
            }
            break;
        case 'collab_desc':
            echo esc_html( get_post_meta( $post_id, '_collab_desc', true ) ?: '—' );
            break;
        case 'collab_order':
            echo (int) get_post_field( 'menu_order', $post_id );
            break;
    }
}

// Make order column sortable
add_filter( 'manage_edit-collaboration_link_sortable_columns', function( $cols ) {
    $cols['collab_order'] = 'menu_order';
    return $cols;
} );
