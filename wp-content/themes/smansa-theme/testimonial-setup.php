<?php
/**
 * CPT: testimonial
 * ACF: group_testimonial
 * Fields: testi_role, testi_quote, testi_avatar_url, testi_avatar_bg, testi_avatar_color
 */

// ── CPT Registration ─────────────────────────────────────────────────────────
add_action( 'init', 'smansa_register_testimonial_cpt' );
function smansa_register_testimonial_cpt() {
    register_post_type( 'testimonial', array(
        'labels'        => array(
            'name'               => 'Testimonial',
            'singular_name'      => 'Testimonial',
            'add_new'            => 'Tambah Testimonial',
            'add_new_item'       => 'Tambah Testimonial Baru',
            'edit_item'          => 'Edit Testimonial',
            'new_item'           => 'Testimonial Baru',
            'view_item'          => 'Lihat Testimonial',
            'search_items'       => 'Cari Testimonial',
            'not_found'          => 'Testimonial tidak ditemukan',
            'not_found_in_trash' => 'Testimonial tidak ditemukan di sampah',
            'menu_name'          => 'Testimonial',
            'all_items'          => 'Semua Testimonial',
        ),
        'public'        => false,
        'show_ui'       => true,
        'show_in_menu'  => true,
        'has_archive'   => false,
        'menu_position' => 24,
        'menu_icon'     => 'dashicons-format-quote',
        'supports'      => array( 'title', 'page-attributes' ),
        'show_in_rest'  => false,
    ) );
}

// ── Admin Columns ─────────────────────────────────────────────────────────────
add_filter( 'manage_testimonial_posts_columns', 'smansa_testimonial_columns' );
function smansa_testimonial_columns( $cols ) {
    $new = array();
    foreach ( $cols as $k => $v ) {
        $new[ $k ] = $v;
        if ( $k === 'title' ) {
            $new['testi_role']  = 'Jabatan / Alumni';
            $new['testi_quote'] = 'Kutipan';
        }
    }
    return $new;
}

add_action( 'manage_testimonial_posts_custom_column', 'smansa_testimonial_column_values', 10, 2 );
function smansa_testimonial_column_values( $col, $post_id ) {
    switch ( $col ) {
        case 'testi_role':
            echo esc_html( get_post_meta( $post_id, 'testi_role', true ) );
            break;
        case 'testi_quote':
            $q = get_post_meta( $post_id, 'testi_quote', true );
            echo esc_html( mb_substr( $q, 0, 80 ) . ( mb_strlen( $q ) > 80 ? '...' : '' ) );
            break;
    }
}

// ── ACF Field Group ───────────────────────────────────────────────────────────
add_action( 'acf/init', 'smansa_register_testimonial_acf' );
function smansa_register_testimonial_acf() {
    if ( ! function_exists( 'acf_add_local_field_group' ) ) return;

    acf_add_local_field_group( array(
        'key'    => 'group_testimonial',
        'title'  => 'Detail Testimonial',
        'fields' => array(
            array(
                'key'          => 'field_testi_role',
                'label'        => 'Jabatan / Status',
                'name'         => 'testi_role',
                'type'         => 'text',
                'required'     => 1,
                'placeholder'  => 'Alumni 2005 | Dosen ITB',
                'instructions' => 'Contoh: Alumni 2018 | Software Engineer, Siswa XII MIPA 1, Orang Tua Siswa',
            ),
            array(
                'key'          => 'field_testi_quote',
                'label'        => 'Kutipan Testimoni',
                'name'         => 'testi_quote',
                'type'         => 'textarea',
                'required'     => 1,
                'rows'         => 4,
                'placeholder'  => 'Tuliskan testimoni di sini...',
                'instructions' => 'Tanpa tanda kutip — sudah otomatis ditambahkan.',
            ),
            array(
                'key'          => 'field_testi_avatar_url',
                'label'        => 'URL Foto (opsional)',
                'name'         => 'testi_avatar_url',
                'type'         => 'url',
                'required'     => 0,
                'placeholder'  => 'https://...',
                'instructions' => 'Kosongkan untuk menggunakan avatar otomatis berdasarkan nama.',
            ),
            array(
                'key'          => 'field_testi_avatar_bg',
                'label'        => 'Warna Latar Avatar',
                'name'         => 'testi_avatar_bg',
                'type'         => 'select',
                'required'     => 0,
                'choices'      => array(
                    '661012' => 'Merah (SMANSA)',
                    'd4a953' => 'Emas',
                    '1a365d' => 'Biru Tua',
                    '1e6f3e' => 'Hijau',
                    '4a1d96' => 'Ungu',
                    '92400e' => 'Coklat',
                ),
                'default_value' => '661012',
                'ui'            => 0,
            ),
        ),
        'location' => array(
            array(
                array(
                    'param'    => 'post_type',
                    'operator' => '==',
                    'value'    => 'testimonial',
                ),
            ),
        ),
        'menu_order'     => 0,
        'position'       => 'normal',
        'style'          => 'default',
        'hide_on_screen' => array(
            'the_content', 'excerpt', 'discussion', 'comments', 'revisions',
            'slug', 'author', 'format', 'categories', 'tags',
            'send-trackbacks', 'featured_image',
        ),
    ) );
}
