<?php
/**
 * CPT: school_achievement
 * ACF: group_school_achievement
 * Fields: ach_medal, ach_icon, ach_event, ach_student, ach_year
 */

// ── CPT Registration ─────────────────────────────────────────────────────────
add_action( 'init', 'smansa_register_achievement_cpt' );
function smansa_register_achievement_cpt() {
    register_post_type( 'school_achievement', array(
        'labels'        => array(
            'name'               => 'Prestasi Siswa',
            'singular_name'      => 'Prestasi',
            'add_new'            => 'Tambah Prestasi',
            'add_new_item'       => 'Tambah Prestasi Baru',
            'edit_item'          => 'Edit Prestasi',
            'new_item'           => 'Prestasi Baru',
            'view_item'          => 'Lihat Prestasi',
            'search_items'       => 'Cari Prestasi',
            'not_found'          => 'Prestasi tidak ditemukan',
            'not_found_in_trash' => 'Prestasi tidak ditemukan di sampah',
            'menu_name'          => 'Prestasi Siswa',
            'all_items'          => 'Semua Prestasi',
        ),
        'public'        => true,
        'has_archive'   => true,
        'rewrite'       => array( 'slug' => 'prestasi' ),
        'menu_position' => 23,
        'menu_icon'     => 'dashicons-awards',
        'supports'      => array( 'title', 'thumbnail', 'page-attributes' ),
        'show_in_rest'  => false,
    ) );
}

// ── Admin Columns ─────────────────────────────────────────────────────────────
add_filter( 'manage_school_achievement_posts_columns', 'smansa_achievement_columns' );
function smansa_achievement_columns( $cols ) {
    $new = array();
    foreach ( $cols as $k => $v ) {
        $new[ $k ] = $v;
        if ( $k === 'title' ) {
            $new['ach_medal']   = 'Medali';
            $new['ach_event']   = 'Event / Kompetisi';
            $new['ach_student'] = 'Siswa / Tim';
            $new['ach_year']    = 'Tahun';
        }
    }
    return $new;
}

add_action( 'manage_school_achievement_posts_custom_column', 'smansa_achievement_column_values', 10, 2 );
function smansa_achievement_column_values( $col, $post_id ) {
    switch ( $col ) {
        case 'ach_medal':
            $medal = get_post_meta( $post_id, 'ach_medal', true );
            $labels = array( 'gold' => '🥇 Emas', 'silver' => '🥈 Perak', 'bronze' => '🥉 Perunggu' );
            echo isset( $labels[ $medal ] ) ? esc_html( $labels[ $medal ] ) : esc_html( $medal );
            break;
        case 'ach_event':
            echo esc_html( get_post_meta( $post_id, 'ach_event', true ) );
            break;
        case 'ach_student':
            echo esc_html( get_post_meta( $post_id, 'ach_student', true ) );
            break;
        case 'ach_year':
            echo esc_html( get_post_meta( $post_id, 'ach_year', true ) );
            break;
    }
}

// ── ACF Field Group ───────────────────────────────────────────────────────────
add_action( 'acf/init', 'smansa_register_achievement_acf' );
function smansa_register_achievement_acf() {
    if ( ! function_exists( 'acf_add_local_field_group' ) ) return;

    acf_add_local_field_group( array(
        'key'      => 'group_school_achievement',
        'title'    => 'Detail Prestasi',
        'fields'   => array(
            array(
                'key'           => 'field_ach_medal',
                'label'         => 'Jenis Medali',
                'name'          => 'ach_medal',
                'type'          => 'select',
                'required'      => 1,
                'choices'       => array(
                    'gold'   => '🥇 Emas',
                    'silver' => '🥈 Perak',
                    'bronze' => '🥉 Perunggu',
                ),
                'default_value' => 'gold',
                'ui'            => 0,
            ),
            array(
                'key'           => 'field_ach_icon',
                'label'         => 'Icon (Font Awesome class)',
                'name'          => 'ach_icon',
                'type'          => 'text',
                'required'      => 0,
                'placeholder'   => 'fas fa-medal',
                'instructions'  => 'Kosongkan untuk gunakan ikon default sesuai jenis medali.',
                'default_value' => '',
            ),
            array(
                'key'           => 'field_ach_event',
                'label'         => 'Event / Kompetisi',
                'name'          => 'ach_event',
                'type'          => 'text',
                'required'      => 1,
                'placeholder'   => 'Olimpiade Sains Nasional 2025',
            ),
            array(
                'key'           => 'field_ach_student',
                'label'         => 'Nama Siswa / Tim',
                'name'          => 'ach_student',
                'type'          => 'text',
                'required'      => 1,
                'placeholder'   => 'Ahmad Fauzi - XII MIPA 1',
            ),
            array(
                'key'           => 'field_ach_level',
                'label'         => 'Tingkat Lomba',
                'name'          => 'ach_level',
                'type'          => 'select',
                'required'      => 0,
                'choices'       => array(
                    'internasional' => 'Internasional',
                    'nasional'      => 'Nasional',
                    'provinsi'      => 'Provinsi',
                    'kabupaten'     => 'Kabupaten / Kota',
                    'sekolah'       => 'Sekolah',
                ),
                'default_value' => 'nasional',
                'ui'            => 0,
            ),
            array(
                'key'           => 'field_ach_year',
                'label'         => 'Tahun',
                'name'          => 'ach_year',
                'type'          => 'text',
                'required'      => 0,
                'placeholder'   => '2025',
                'default_value' => date( 'Y' ),
            ),
            array(
                'key'         => 'field_ach_instagram_url',
                'label'       => 'Link Post Instagram',
                'name'        => 'ach_instagram_url',
                'type'        => 'url',
                'required'    => 0,
                'placeholder' => 'https://www.instagram.com/p/XXXXX/',
                'instructions'=> 'Opsional. Tempel URL postingan Instagram yang menampilkan prestasi ini. Akan ditampilkan sebagai tombol di kartu prestasi.',
                'wrapper'     => array( 'width' => '100' ),
            ),
        ),
        'location' => array(
            array(
                array(
                    'param'    => 'post_type',
                    'operator' => '==',
                    'value'    => 'school_achievement',
                ),
            ),
        ),
        'menu_order'            => 0,
        'position'              => 'normal',
        'style'                 => 'default',
        'hide_on_screen'        => array(
            'the_content', 'excerpt', 'discussion', 'comments', 'revisions',
            'slug', 'author', 'format', 'categories', 'tags',
            'send-trackbacks',
            /* featured_image intentionally shown — used as card thumbnail */
        ),
    ) );
}
