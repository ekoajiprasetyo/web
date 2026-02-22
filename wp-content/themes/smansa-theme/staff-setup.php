<?php
/**
 * CPT : school_staff
 * Tax : staff_department  (slug: guru | tata-usaha)
 * Meta: _staff_position, _staff_nip, _staff_education, _staff_subjects, _staff_quote
 *
 * Admin menus:
 *   WP Admin → Guru & Staf → Semua Staf / Tambah Baru / Departemen
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// ─────────────────────────────────────────────────────────────────────────────
// 1. CPT Registration
// ─────────────────────────────────────────────────────────────────────────────
add_action( 'init', 'smansa_register_staff_cpt' );
function smansa_register_staff_cpt() {
    register_post_type( 'school_staff', array(
        'labels' => array(
            'name'               => 'Guru &amp; Staf',
            'singular_name'      => 'Staf',
            'add_new'            => 'Tambah Baru',
            'add_new_item'       => 'Tambah Staf Baru',
            'edit_item'          => 'Edit Data Staf',
            'new_item'           => 'Staf Baru',
            'view_item'          => 'Lihat Profil',
            'search_items'       => 'Cari Staf',
            'not_found'          => 'Staf tidak ditemukan',
            'not_found_in_trash' => 'Tidak ada data di sampah',
            'menu_name'          => 'Guru &amp; Staf',
            'all_items'          => 'Semua Staf',
        ),
        'public'             => false,
        'publicly_queryable' => false,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'has_archive'        => false,
        'menu_position'      => 25,
        'menu_icon'          => 'dashicons-groups',
        'supports'           => array( 'title', 'thumbnail', 'page-attributes' ),
        'show_in_rest'       => false,
    ) );
}

// ─────────────────────────────────────────────────────────────────────────────
// 2. Taxonomy: staff_department
// ─────────────────────────────────────────────────────────────────────────────
add_action( 'init', 'smansa_register_staff_taxonomy' );
function smansa_register_staff_taxonomy() {
    register_taxonomy( 'staff_department', 'school_staff', array(
        'labels' => array(
            'name'              => 'Departemen',
            'singular_name'     => 'Departemen',
            'search_items'      => 'Cari Departemen',
            'all_items'         => 'Semua Departemen',
            'edit_item'         => 'Edit Departemen',
            'update_item'       => 'Perbarui Departemen',
            'add_new_item'      => 'Tambah Departemen Baru',
            'new_item_name'     => 'Nama Departemen Baru',
            'menu_name'         => 'Departemen',
        ),
        'hierarchical'      => false,
        'public'            => false,
        'show_ui'           => true,
        'show_admin_column' => true,
        'rewrite'           => false,
    ) );
}

// Seed default terms (runs once — term_exists prevents duplication)
add_action( 'init', 'smansa_staff_seed_terms', 20 );
function smansa_staff_seed_terms() {
    if ( ! taxonomy_exists( 'staff_department' ) ) return;
    if ( ! term_exists( 'guru', 'staff_department' ) ) {
        wp_insert_term( 'Guru', 'staff_department', array( 'slug' => 'guru' ) );
    }
    if ( ! term_exists( 'tata-usaha', 'staff_department' ) ) {
        wp_insert_term( 'Tata Usaha', 'staff_department', array( 'slug' => 'tata-usaha' ) );
    }
}

// ─────────────────────────────────────────────────────────────────────────────
// 3. Meta Box
// ─────────────────────────────────────────────────────────────────────────────
add_action( 'add_meta_boxes', 'smansa_staff_meta_boxes' );
function smansa_staff_meta_boxes() {
    add_meta_box(
        'smansa_staff_details',
        '📋 Detail Staf / Guru',
        'smansa_staff_meta_box_html',
        'school_staff',
        'normal',
        'high'
    );
}

function smansa_staff_meta_box_html( $post ) {
    wp_nonce_field( 'smansa_staff_save', 'smansa_staff_nonce' );
    $position  = get_post_meta( $post->ID, '_staff_position',  true );
    $nip       = get_post_meta( $post->ID, '_staff_nip',       true );
    $education = get_post_meta( $post->ID, '_staff_education', true );
    $subjects  = get_post_meta( $post->ID, '_staff_subjects',  true );
    $quote     = get_post_meta( $post->ID, '_staff_quote',     true );
    ?>
    <style>
    .smansa-smb-grid { display:grid; grid-template-columns:1fr 1fr; gap:.9rem 1.5rem; padding:.6rem 0 .25rem; }
    .smansa-smb-grid .smb-full { grid-column: 1 / -1; }
    .smansa-smb-grid label { display:block; font-weight:600; font-size:12px; text-transform:uppercase; letter-spacing:.04em; color:#50575e; margin-bottom:.3rem; }
    .smansa-smb-grid input[type="text"] { width:100%; padding:7px 10px; border:1px solid #c3c4c7; border-radius:4px; font-size:13px; line-height:1.5; transition:border-color .15s; }
    .smansa-smb-grid input[type="text"]:focus { border-color:#2271b1; box-shadow:0 0 0 1px #2271b1; outline:none; }
    .smansa-smb-grid .smb-desc { font-size:11px; color:#646970; margin-top:.3rem; line-height:1.4; }
    .smansa-smb-grid .smb-required { color:#d63638; margin-left:2px; }
    .smansa-smb-notice { background:#f0f6fc; border-left:4px solid #2271b1; padding:.6rem .9rem; margin-bottom:.9rem; font-size:12px; color:#2271b1; border-radius:0 4px 4px 0; }
    </style>

    <p class="smansa-smb-notice">
        📸 <strong>Foto</strong> diatur melalui kotak <em>"Featured Image"</em> di sebelah kanan. &nbsp;|&nbsp;
        🏷️ <strong>Departemen</strong> (Guru / Tata Usaha) diatur melalui kotak <em>"Departemen"</em> di sebelah kanan.
    </p>

    <div class="smansa-smb-grid">
        <div class="smb-full">
            <label>Jabatan / Posisi <span class="smb-required">*</span></label>
            <input type="text" name="staff_position" value="<?php echo esc_attr( $position ); ?>"
                   placeholder="cth: Guru Matematika  /  Kepala Tata Usaha  /  Staf Administrasi">
        </div>

        <div>
            <label>NIP</label>
            <input type="text" name="staff_nip" value="<?php echo esc_attr( $nip ); ?>"
                   placeholder="19XXXXXXXXXXXXXXXXX">
            <p class="smb-desc">Nomor Induk Pegawai (opsional, untuk PNS)</p>
        </div>

        <div>
            <label>Pendidikan Terakhir</label>
            <input type="text" name="staff_education" value="<?php echo esc_attr( $education ); ?>"
                   placeholder="cth: S1 Pendidikan Matematika, UGM">
        </div>

        <div class="smb-full">
            <label>Mata Pelajaran / Bidang Tugas</label>
            <input type="text" name="staff_subjects" value="<?php echo esc_attr( $subjects ); ?>"
                   placeholder="cth: Matematika, Fisika  /  Administrasi Kepegawaian">
            <p class="smb-desc">
                Untuk <strong>Guru</strong>: isi mata pelajaran yang diajarkan. &nbsp;
                Untuk <strong>Tata Usaha</strong>: isi bidang tugas (opsional).
            </p>
        </div>

        <div class="smb-full">
            <label>Motto / Kutipan Inspiratif</label>
            <input type="text" name="staff_quote" value="<?php echo esc_attr( $quote ); ?>"
                   placeholder="Kutipan atau motto pribadi (opsional)">
        </div>
    </div>
    <?php
}

add_action( 'save_post_school_staff', 'smansa_staff_save_meta' );
function smansa_staff_save_meta( $post_id ) {
    if ( ! isset( $_POST['smansa_staff_nonce'] ) ) return;
    if ( ! wp_verify_nonce( $_POST['smansa_staff_nonce'], 'smansa_staff_save' ) ) return;
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if ( ! current_user_can( 'edit_post', $post_id ) ) return;

    $fields = array(
        '_staff_position'  => 'staff_position',
        '_staff_nip'       => 'staff_nip',
        '_staff_education' => 'staff_education',
        '_staff_subjects'  => 'staff_subjects',
        '_staff_quote'     => 'staff_quote',
    );
    foreach ( $fields as $meta_key => $field_name ) {
        if ( array_key_exists( $field_name, $_POST ) ) {
            update_post_meta( $post_id, $meta_key, sanitize_text_field( wp_unslash( $_POST[ $field_name ] ) ) );
        }
    }
}

// ─────────────────────────────────────────────────────────────────────────────
// 4. Admin List Columns
// ─────────────────────────────────────────────────────────────────────────────
add_filter( 'manage_school_staff_posts_columns', 'smansa_staff_columns' );
function smansa_staff_columns( $cols ) {
    return array(
        'cb'             => $cols['cb'],
        'staff_thumb'    => 'Foto',
        'title'          => 'Nama Lengkap',
        'staff_position' => 'Jabatan',
        'staff_subjects' => 'Mata Pelajaran / Bidang',
        'staff_nip'      => 'NIP',
        'menu_order'     => 'Urutan',
    );
}

add_action( 'manage_school_staff_posts_custom_column', 'smansa_staff_column_values', 10, 2 );
function smansa_staff_column_values( $col, $post_id ) {
    switch ( $col ) {
        case 'staff_thumb':
            $thumb_id = get_post_thumbnail_id( $post_id );
            if ( $thumb_id ) {
                $img = wp_get_attachment_image( $thumb_id, array( 52, 52 ), false, array(
                    'style' => 'border-radius:50%;object-fit:cover;width:52px;height:52px;',
                ) );
                echo $img;
            } else {
                $name    = get_the_title( $post_id );
                $initial = mb_strtoupper( mb_substr( $name, 0, 1 ) );
                echo '<div style="width:52px;height:52px;border-radius:50%;background:#9E1B1E;color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:20px;line-height:1;">' . esc_html( $initial ) . '</div>';
            }
            break;
        case 'staff_position':
            echo esc_html( get_post_meta( $post_id, '_staff_position', true ) ?: '—' );
            break;
        case 'staff_subjects':
            echo esc_html( get_post_meta( $post_id, '_staff_subjects', true ) ?: '—' );
            break;
        case 'staff_nip':
            $nip = get_post_meta( $post_id, '_staff_nip', true );
            echo $nip ? '<code style="font-size:11px;">' . esc_html( $nip ) . '</code>' : '<span style="color:#999">—</span>';
            break;
        case 'menu_order':
            echo '<span style="font-weight:600;color:#2271b1;">' . (int) get_post_field( 'menu_order', $post_id ) . '</span>';
            break;
    }
}

// Make menu_order column sortable
add_filter( 'manage_edit-school_staff_sortable_columns', 'smansa_staff_sortable_columns' );
function smansa_staff_sortable_columns( $sortable ) {
    $sortable['menu_order'] = 'menu_order';
    return $sortable;
}

// Thumbnail column width
add_action( 'admin_head', 'smansa_staff_admin_css' );
function smansa_staff_admin_css() {
    $screen = get_current_screen();
    if ( ! $screen || $screen->post_type !== 'school_staff' ) return;
    echo '<style>
        .column-staff_thumb { width:68px !important; }
        .column-menu_order  { width:72px !important; text-align:center; }
        .column-staff_nip   { width:160px !important; }
        .smansa-smb-grid .smb-full { grid-column: 1 / -1; }
    </style>';
}

// ─────────────────────────────────────────────────────────────────────────────
// 5. Helper: query staff by department slug
// ─────────────────────────────────────────────────────────────────────────────
function smansa_get_staff( $department_slug, $per_page = -1 ) {
    return new WP_Query( array(
        'post_type'      => 'school_staff',
        'posts_per_page' => $per_page,
        'post_status'    => 'publish',
        'orderby'        => 'menu_order',
        'order'          => 'ASC',
        'tax_query'      => array( array(
            'taxonomy' => 'staff_department',
            'field'    => 'slug',
            'terms'    => $department_slug,
        ) ),
    ) );
}
