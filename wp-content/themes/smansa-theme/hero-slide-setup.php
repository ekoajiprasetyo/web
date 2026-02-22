<?php
/**
 * Hero Slide — Custom Post Type + ACF Field Group
 *
 * Admin path: WP Admin → Hero Slides → Add New
 * Fields editable per-slide: Badge Icon, Badge Text, Title, Subtitle,
 *   Button 1 & 2 (label / URL / icon), Background Image.
 */

// =========================================================
// 1. Register Custom Post Type: hero_slide
// =========================================================
add_action( 'init', function () {
    register_post_type( 'hero_slide', array(
        'labels'             => array(
            'name'               => 'Hero Slides',
            'singular_name'      => 'Hero Slide',
            'add_new'            => 'Tambah Slide',
            'add_new_item'       => 'Tambah Hero Slide Baru',
            'edit_item'          => 'Edit Hero Slide',
            'new_item'           => 'Slide Baru',
            'view_item'          => 'Lihat Slide',
            'search_items'       => 'Cari Slide',
            'not_found'          => 'Tidak ada slide ditemukan.',
            'not_found_in_trash' => 'Tidak ada slide di Trash.',
            'menu_name'          => 'Hero Slides',
        ),
        'public'             => false,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'show_in_nav_menus'  => false,
        'show_in_admin_bar'  => true,
        'menu_position'      => 20,
        'menu_icon'          => 'dashicons-slides',
        'supports'           => array( 'title', 'page-attributes' ),
        'has_archive'        => false,
        'rewrite'            => false,
        'capability_type'    => 'post',
    ) );
} );

// =========================================================
// 2. Register ACF Field Group (programmatic — no JSON needed)
// =========================================================
add_action( 'acf/init', function () {
    if ( ! function_exists( 'acf_add_local_field_group' ) ) {
        return;
    }

    acf_add_local_field_group( array(
        'key'      => 'group_hero_slide',
        'title'    => 'Hero Slide Fields',
        'fields'   => array(

            // ---- Badge ----
            array(
                'key'           => 'field_slide_badge_icon',
                'label'         => 'Badge — Icon (Font Awesome class)',
                'name'          => 'slide_badge_icon',
                'type'          => 'text',
                'instructions'  => 'Contoh: <code>fas fa-school</code>  atau  <code>fas fa-lightbulb</code>',
                'default_value' => 'fas fa-school',
                'placeholder'   => 'fas fa-school',
                'wrapper'       => array( 'width' => '40' ),
            ),
            array(
                'key'           => 'field_slide_badge_text',
                'label'         => 'Badge — Teks',
                'name'          => 'slide_badge_text',
                'type'          => 'text',
                'default_value' => 'Website Resmi SMAN 1 Purwokerto',
                'placeholder'   => 'Website Resmi SMAN 1 Purwokerto',
                'wrapper'       => array( 'width' => '60' ),
            ),

            // ---- Content ----
            array(
                'key'           => 'field_slide_title',
                'label'         => 'Judul Slide (H1)',
                'name'          => 'slide_title',
                'type'          => 'text',
                'required'      => 1,
                'default_value' => 'Mewujudkan Generasi Emas Berkarakter Pancasila',
                'placeholder'   => 'Judul utama hero...',
                'wrapper'       => array( 'width' => '70' ),
            ),
            array(
                'key'           => 'field_slide_title_accent',
                'label'         => 'Kata Aksen (warna emas)',
                'name'          => 'slide_title_accent',
                'type'          => 'text',
                'instructions'  => 'Ketikkan bagian teks dari Judul di atas yang ingin ditampilkan dengan warna emas. Harus sama persis (huruf kapital diperhatikan). Kosongkan jika tidak diperlukan.',
                'default_value' => '',
                'placeholder'   => 'mis: SMAN 1 Purwokerto',
                'wrapper'       => array( 'width' => '30' ),
            ),
            array(
                'key'           => 'field_slide_subtitle',
                'label'         => 'Sub-judul / Deskripsi',
                'name'          => 'slide_subtitle',
                'type'          => 'textarea',
                'rows'          => 3,
                'default_value' => 'SMAN 1 Purwokerto berkomitmen mencetak lulusan unggul dalam prestasi, luhur dalam budi pekerti, dan siap bersaing di era global.',
                'placeholder'   => 'Deskripsi singkat...',
            ),

            // ---- Background Image ----
            array(
                'key'           => 'field_slide_background',
                'label'         => 'Background Image',
                'name'          => 'slide_background',
                'type'          => 'image',
                'instructions'  => 'Ukuran optimal: 1920 × 900 px. Jika kosong, warna solid dari tema akan digunakan.',
                'return_format' => 'url',
                'preview_size'  => 'medium',
                'library'       => 'all',
            ),

            // ---- Button 1 ----
            array(
                'key'     => 'field_slide_btn1_tab',
                'label'   => 'Tombol Utama (Kiri)',
                'name'    => '',
                'type'    => 'tab',
                'placement' => 'left',
            ),
            array(
                'key'           => 'field_slide_btn1_label',
                'label'         => 'Label Tombol 1',
                'name'          => 'slide_btn1_label',
                'type'          => 'text',
                'default_value' => 'Jelajahi Profil',
                'placeholder'   => 'Jelajahi Profil',
                'wrapper'       => array( 'width' => '30' ),
            ),
            array(
                'key'           => 'field_slide_btn1_link_source',
                'label'         => 'Sumber Link Tombol 1',
                'name'          => 'slide_btn1_link_source',
                'type'          => 'select',
                'choices'       => array(
                    'url'  => 'URL Manual',
                    'page' => 'Pilih Halaman',
                ),
                'default_value' => 'url',
                'ui'            => 1,
                'return_format' => 'value',
                'wrapper'       => array( 'width' => '20' ),
            ),
            array(
                'key'           => 'field_slide_btn1_url',
                'label'         => 'URL Tombol 1 (manual)',
                'name'          => 'slide_btn1_url',
                'type'          => 'url',
                'default_value' => '#profil',
                'placeholder'   => '#profil  atau  https://...',
                'conditional_logic' => array(
                    array(
                        array(
                            'field'    => 'field_slide_btn1_link_source',
                            'operator' => '==',
                            'value'    => 'url',
                        ),
                    ),
                ),
                'wrapper'       => array( 'width' => '30' ),
            ),
            array(
                'key'           => 'field_slide_btn1_page',
                'label'         => 'Halaman Tombol 1',
                'name'          => 'slide_btn1_page',
                'type'          => 'page_link',
                'post_type'     => array( 'page' ),
                'allow_null'    => 1,
                'allow_archives'=> 0,
                'multiple'      => 0,
                'conditional_logic' => array(
                    array(
                        array(
                            'field'    => 'field_slide_btn1_link_source',
                            'operator' => '==',
                            'value'    => 'page',
                        ),
                    ),
                ),
                'wrapper'       => array( 'width' => '30' ),
            ),
            array(
                'key'           => 'field_slide_btn1_icon',
                'label'         => 'Icon Tombol 1',
                'name'          => 'slide_btn1_icon',
                'type'          => 'text',
                'default_value' => 'fas fa-arrow-right',
                'placeholder'   => 'fas fa-arrow-right',
                'wrapper'       => array( 'width' => '20' ),
            ),

            // ---- Button 2 ----
            array(
                'key'     => 'field_slide_btn2_tab',
                'label'   => 'Tombol Sekunder (Kanan)',
                'name'    => '',
                'type'    => 'tab',
                'placement' => 'left',
            ),
            array(
                'key'           => 'field_slide_btn2_label',
                'label'         => 'Label Tombol 2',
                'name'          => 'slide_btn2_label',
                'type'          => 'text',
                'default_value' => 'Hubungi Kami',
                'placeholder'   => 'Hubungi Kami',
                'wrapper'       => array( 'width' => '30' ),
            ),
            array(
                'key'           => 'field_slide_btn2_link_source',
                'label'         => 'Sumber Link Tombol 2',
                'name'          => 'slide_btn2_link_source',
                'type'          => 'select',
                'choices'       => array(
                    'url'  => 'URL Manual',
                    'page' => 'Pilih Halaman',
                ),
                'default_value' => 'url',
                'ui'            => 1,
                'return_format' => 'value',
                'wrapper'       => array( 'width' => '20' ),
            ),
            array(
                'key'           => 'field_slide_btn2_url',
                'label'         => 'URL Tombol 2 (manual)',
                'name'          => 'slide_btn2_url',
                'type'          => 'url',
                'default_value' => '#kontak',
                'placeholder'   => '#kontak  atau  https://...',
                'conditional_logic' => array(
                    array(
                        array(
                            'field'    => 'field_slide_btn2_link_source',
                            'operator' => '==',
                            'value'    => 'url',
                        ),
                    ),
                ),
                'wrapper'       => array( 'width' => '30' ),
            ),
            array(
                'key'           => 'field_slide_btn2_page',
                'label'         => 'Halaman Tombol 2',
                'name'          => 'slide_btn2_page',
                'type'          => 'page_link',
                'post_type'     => array( 'page' ),
                'allow_null'    => 1,
                'allow_archives'=> 0,
                'multiple'      => 0,
                'conditional_logic' => array(
                    array(
                        array(
                            'field'    => 'field_slide_btn2_link_source',
                            'operator' => '==',
                            'value'    => 'page',
                        ),
                    ),
                ),
                'wrapper'       => array( 'width' => '30' ),
            ),
            array(
                'key'           => 'field_slide_btn2_icon',
                'label'         => 'Icon Tombol 2',
                'name'          => 'slide_btn2_icon',
                'type'          => 'text',
                'default_value' => 'fas fa-envelope',
                'placeholder'   => 'fas fa-envelope',
                'wrapper'       => array( 'width' => '20' ),
            ),

        ),
        'location' => array(
            array(
                array(
                    'param'    => 'post_type',
                    'operator' => '==',
                    'value'    => 'hero_slide',
                ),
            ),
        ),
        'menu_order'            => 0,
        'position'              => 'normal',
        'style'                 => 'default',
        'label_placement'       => 'top',
        'instruction_placement' => 'label',
    ) );
} );
