<?php
/**
 * Program Unggulan — CPT school_program + ACF Fields
 *
 * Admin path: WP Admin → Program Unggulan → Tambah Program Baru
 * Fields per program: Icon FA, Deskripsi, URL, Featured (badge unggulan)
 * Ordering: gunakan field "Urutan" (Order) di editor.
 */

// =========================================================
// 1. Register Custom Post Type: school_program
// =========================================================
add_action( 'init', function () {
    register_post_type( 'school_program', array(
        'labels' => array(
            'name'               => 'Program Unggulan',
            'singular_name'      => 'Program',
            'add_new'            => 'Tambah Program',
            'add_new_item'       => 'Tambah Program Baru',
            'edit_item'          => 'Edit Program',
            'new_item'           => 'Program Baru',
            'view_item'          => 'Lihat Program',
            'search_items'       => 'Cari Program',
            'not_found'          => 'Tidak ada program ditemukan.',
            'not_found_in_trash' => 'Tidak ada program di Trash.',
            'menu_name'          => 'Program Unggulan',
        ),
        'public'            => false,
        'show_ui'           => true,
        'show_in_menu'      => true,
        'show_in_nav_menus' => false,
        'show_in_admin_bar' => true,
        'menu_position'     => 22,
        'menu_icon'         => 'dashicons-star-filled',
        'supports'          => array( 'title', 'page-attributes' ),
        'has_archive'       => false,
        'rewrite'           => false,
        'capability_type'   => 'post',
    ) );
} );

// =========================================================
// 2. Custom admin columns: Icon, Deskripsi, Featured, Urutan
// =========================================================
add_filter( 'manage_school_program_posts_columns', function ( $cols ) {
    $new = array();
    foreach ( $cols as $key => $label ) {
        $new[ $key ] = $label;
        if ( $key === 'title' ) {
            $new['sp_icon']     = 'Icon FA';
            $new['sp_desc']     = 'Deskripsi';
            $new['sp_featured'] = 'Unggulan';
        }
    }
    return $new;
} );

add_action( 'manage_school_program_posts_custom_column', function ( $col, $post_id ) {
    if ( $col === 'sp_icon' ) {
        $icon = get_post_meta( $post_id, 'sp_icon', true );
        echo $icon ? '<code>' . esc_html( $icon ) . '</code>' : '—';
    }
    if ( $col === 'sp_desc' ) {
        $desc = get_post_meta( $post_id, 'sp_desc', true );
        echo $desc ? esc_html( wp_trim_words( $desc, 10, '...' ) ) : '—';
    }
    if ( $col === 'sp_featured' ) {
        $val = get_post_meta( $post_id, 'sp_featured', true );
        echo $val ? '<span style="color:#d4a017;font-weight:700;">★ Unggulan</span>' : '—';
    }
}, 10, 2 );

// =========================================================
// 3. ACF Field Group
// =========================================================
add_action( 'acf/init', function () {
    if ( ! function_exists( 'acf_add_local_field_group' ) ) {
        return;
    }

    acf_add_local_field_group( array(
        'key'    => 'group_school_program',
        'title'  => '⭐ Detail Program Unggulan',
        'fields' => array(

            array(
                'key'           => 'field_sp_icon',
                'label'         => 'Icon Font Awesome',
                'name'          => 'sp_icon',
                'type'          => 'text',
                'default_value' => 'fas fa-star',
                'required'      => 1,
                'instructions'  => 'Kelas icon FA, contoh: <code>fas fa-mosque</code>. Cari di <a href="https://fontawesome.com/icons" target="_blank">fontawesome.com/icons</a>.',
                'wrapper'       => array( 'width' => '35' ),
            ),
            array(
                'key'           => 'field_sp_url',
                'label'         => 'URL (opsional)',
                'name'          => 'sp_url',
                'type'          => 'url',
                'default_value' => '#',
                'instructions'  => 'Link halaman detail program. Biarkan # jika belum ada.',
                'wrapper'       => array( 'width' => '40' ),
            ),
            array(
                'key'           => 'field_sp_featured',
                'label'         => 'Tandai sebagai Unggulan',
                'name'          => 'sp_featured',
                'type'          => 'true_false',
                'default_value' => 0,
                'ui'            => 1,
                'ui_on_text'    => 'Ya',
                'ui_off_text'   => 'Tidak',
                'instructions'  => 'Card unggulan tampil dengan border emas dan badge khusus.',
                'wrapper'       => array( 'width' => '25' ),
            ),
            array(
                'key'          => 'field_sp_desc',
                'label'        => 'Deskripsi Program',
                'name'         => 'sp_desc',
                'type'         => 'textarea',
                'rows'         => 3,
                'required'     => 1,
                'instructions' => 'Penjelasan singkat tentang program ini (2-3 kalimat).',
                'new_lines'    => 'br',
            ),

        ),

        'location' => array(
            array(
                array(
                    'param'    => 'post_type',
                    'operator' => '==',
                    'value'    => 'school_program',
                ),
            ),
        ),
        'menu_order'            => 0,
        'position'              => 'normal',
        'style'                 => 'default',
        'label_placement'       => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen'        => array(
            'the_content', 'excerpt', 'discussion', 'comments',
            'revisions', 'slug', 'author', 'format', 'categories',
            'tags', 'send-trackbacks', 'featured_image',
        ),
    ) );
} );
