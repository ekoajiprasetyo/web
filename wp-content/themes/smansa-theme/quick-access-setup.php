<?php
/**
 * Card "Akses Cepat / Sistem Informasi" — CPT + ACF Field Group
 *
 * Admin path: WP Admin → Akses Cepat → Tambah Card Baru
 * Fields per card: Icon FA, Judul, Deskripsi, URL, Highlight (ya/tidak)
 * Urutan diatur via "Urutan" (Order) di dalam editor.
 */

// =========================================================
// 1. Register Custom Post Type: quick_access_card
// =========================================================
add_action( 'init', function () {
    register_post_type( 'quick_access_card', array(
        'labels' => array(
            'name'               => 'Akses Cepat',
            'singular_name'      => 'Card Akses Cepat',
            'add_new'            => 'Tambah Card',
            'add_new_item'       => 'Tambah Card Baru',
            'edit_item'          => 'Edit Card',
            'new_item'           => 'Card Baru',
            'view_item'          => 'Lihat Card',
            'search_items'       => 'Cari Card',
            'not_found'          => 'Tidak ada card ditemukan.',
            'not_found_in_trash' => 'Tidak ada card di Trash.',
            'menu_name'          => 'Akses Cepat',
        ),
        'public'            => false,
        'show_ui'           => true,
        'show_in_menu'      => true,
        'show_in_nav_menus' => false,
        'show_in_admin_bar' => true,
        'menu_position'     => 21,
        'menu_icon'         => 'dashicons-grid-view',
        'supports'          => array( 'title', 'page-attributes' ),
        'has_archive'       => false,
        'rewrite'           => false,
        'capability_type'   => 'post',
    ) );
} );

// =========================================================
// 2. Kolom tambahan di admin list: Icon, Deskripsi, URL, Highlight
// =========================================================
add_filter( 'manage_quick_access_card_posts_columns', function ( $cols ) {
    $new = array();
    foreach ( $cols as $key => $label ) {
        $new[ $key ] = $label;
        if ( $key === 'title' ) {
            $new['qa_icon']      = 'Icon FA';
            $new['qa_desc']      = 'Deskripsi';
            $new['qa_highlight'] = 'Highlight';
        }
    }
    return $new;
} );

add_action( 'manage_quick_access_card_posts_custom_column', function ( $col, $post_id ) {
    if ( $col === 'qa_icon' ) {
        $icon = get_post_meta( $post_id, 'qa_icon', true );
        echo $icon ? '<code>' . esc_html( $icon ) . '</code>' : '—';
    }
    if ( $col === 'qa_desc' ) {
        echo esc_html( get_post_meta( $post_id, 'qa_desc', true ) ?: '—' );
    }
    if ( $col === 'qa_highlight' ) {
        $val = get_post_meta( $post_id, 'qa_highlight', true );
        echo $val ? '<span style="color:#b91c1c;font-weight:600;">✔ Ya</span>' : '—';
    }
}, 10, 2 );

// =========================================================
// 3. ACF Field Group per card
// =========================================================
add_action( 'acf/init', function () {
    if ( ! function_exists( 'acf_add_local_field_group' ) ) {
        return;
    }

    acf_add_local_field_group( array(
        'key'   => 'group_quick_access_card',
        'title' => '🔗 Detail Card Akses Cepat',
        'fields' => array(

            array(
                'key'           => 'field_qa_icon',
                'label'         => 'Icon Font Awesome',
                'name'          => 'qa_icon',
                'type'          => 'text',
                'default_value' => 'fas fa-link',
                'instructions'  => 'Masukkan kelas FA, contoh: <code>fas fa-laptop-code</code>. Cari icon di <a href="https://fontawesome.com/icons" target="_blank">fontawesome.com/icons</a>.',
                'required'      => 1,
                'wrapper'       => array( 'width' => '40' ),
            ),

            array(
                'key'           => 'field_qa_title',
                'label'         => 'Judul Card',
                'name'          => 'qa_title',
                'type'          => 'text',
                'instructions'  => 'Nama layanan / fitur. Diambil dari "Judul" post di atas jika dikosongkan.',
                'wrapper'       => array( 'width' => '60' ),
            ),

            array(
                'key'           => 'field_qa_desc',
                'label'         => 'Deskripsi Singkat',
                'name'          => 'qa_desc',
                'type'          => 'text',
                'default_value' => '',
                'instructions'  => 'Teks kecil di bawah judul. Contoh: "Sistem pembelajaran daring".',
                'wrapper'       => array( 'width' => '70' ),
            ),

            array(
                'key'           => 'field_qa_url',
                'label'         => 'URL Tujuan',
                'name'          => 'qa_url',
                'type'          => 'url',
                'default_value' => '#',
                'instructions'  => 'Link saat card diklik.',
                'wrapper'       => array( 'width' => '30' ),
            ),

            array(
                'key'           => 'field_qa_highlight',
                'label'         => 'Tampilkan sebagai Highlight (latar merah)',
                'name'          => 'qa_highlight',
                'type'          => 'true_false',
                'default_value' => 0,
                'ui'            => 1,
                'ui_on_text'    => 'Ya',
                'ui_off_text'   => 'Tidak',
                'instructions'  => 'Aktifkan untuk card dengan latar merah (biasanya SPMB / pendaftaran).',
            ),

        ),

        'location' => array(
            array(
                array(
                    'param'    => 'post_type',
                    'operator' => '==',
                    'value'    => 'quick_access_card',
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
