<?php
/**
 * Tentang Kami Section — ACF Field Group
 *
 * Fields are attached to the static WordPress front page.
 * Admin path: Dashboard → Pages → [Front Page title] → Edit
 * All "Tentang Kami" fields appear below the editor.
 *
 * NOTE: Uses 6 fixed feature field sets instead of Repeater
 *       to maintain compatibility with ACF Free (no Pro required).
 */
add_action( 'acf/init', function () {
    if ( ! function_exists( 'acf_add_local_field_group' ) ) {
        return;
    }

    acf_add_local_field_group( array(
        'key'    => 'group_about_section',
        'title'  => '📌 Tentang Kami — Konten Section',
        'fields' => array(

            // ---- Subtitle label above H2 ----
            array(
                'key'           => 'field_about_subtitle_icon',
                'label'         => 'Subtitle — Icon FA',
                'name'          => 'about_subtitle_icon',
                'type'          => 'text',
                'default_value' => 'fas fa-school',
                'instructions'  => 'Contoh: <code>fas fa-school</code>',
                'wrapper'       => array( 'width' => '30' ),
            ),
            array(
                'key'           => 'field_about_subtitle_text',
                'label'         => 'Subtitle — Teks',
                'name'          => 'about_subtitle_text',
                'type'          => 'text',
                'default_value' => 'Tentang Kami',
                'wrapper'       => array( 'width' => '70' ),
            ),

            // ---- Heading ----
            array(
                'key'           => 'field_about_title',
                'label'         => 'Judul — Bagian Biasa',
                'name'          => 'about_title',
                'type'          => 'text',
                'default_value' => 'Membangun Generasi',
                'instructions'  => 'Teks sebelum kata yang di-highlight.',
                'wrapper'       => array( 'width' => '50' ),
            ),
            array(
                'key'           => 'field_about_title_accent',
                'label'         => 'Judul — Bagian Highlight (merah)',
                'name'          => 'about_title_accent',
                'type'          => 'text',
                'default_value' => 'Unggul & Berkarakter',
                'instructions'  => 'Teks yang tampil dengan warna aksen.',
                'wrapper'       => array( 'width' => '50' ),
            ),

            // ---- Body text ----
            array(
                'key'           => 'field_about_text',
                'label'         => 'Paragraf Deskripsi',
                'name'          => 'about_text',
                'type'          => 'textarea',
                'rows'          => 4,
                'default_value' => 'SMA Negeri 1 Purwokerto berdiri sejak tahun 1960 dan menjadi salah satu sekolah unggulan di Jawa Tengah dengan komitmen menghadirkan pendidikan berkualitas dan berdaya saing global.',
            ),

            // ---- Image ----
            array(
                'key'           => 'field_about_image',
                'label'         => 'Foto / Gambar',
                'name'          => 'about_image',
                'type'          => 'image',
                'instructions'  => 'Ukuran ideal: 600 × 450 px. Jika kosong, gambar placeholder akan digunakan.',
                'return_format' => 'url',
                'preview_size'  => 'medium',
                'library'       => 'all',
                'wrapper'       => array( 'width' => '50' ),
            ),
            array(
                'key'           => 'field_about_video_url',
                'label'         => 'URL Video Profil (opsional)',
                'name'          => 'about_video_url',
                'type'          => 'url',
                'instructions'  => 'Link YouTube/Vimeo. Jika kosong, overlay "Video Profil" disembunyikan.',
                'placeholder'   => 'https://www.youtube.com/watch?v=...',
                'wrapper'       => array( 'width' => '50' ),
            ),

            // =========================================================
            // TAB: Badge Akreditasi
            // =========================================================
            array(
                'key'   => 'field_about_badge_tab',
                'label' => 'Badge Akreditasi',
                'name'  => '',
                'type'  => 'tab',
            ),
            array(
                'key'           => 'field_about_badge_number',
                'label'         => 'Badge — Nilai / Huruf',
                'name'          => 'about_badge_number',
                'type'          => 'text',
                'default_value' => 'A',
                'instructions'  => 'Ditampilkan besar di pojok gambar. Contoh: A, A+, 98',
                'wrapper'       => array( 'width' => '30' ),
            ),
            array(
                'key'           => 'field_about_badge_text',
                'label'         => 'Badge — Label Bawah',
                'name'          => 'about_badge_text',
                'type'          => 'text',
                'default_value' => 'Akreditasi',
                'wrapper'       => array( 'width' => '70' ),
            ),

            // =========================================================
            // TAB: Fitur / Keunggulan  (6 fixed rows — ACF Free compat)
            // =========================================================
            array(
                'key'   => 'field_about_features_tab',
                'label' => 'Fitur / Keunggulan',
                'name'  => '',
                'type'  => 'tab',
            ),
            array(
                'key'     => 'field_feat_instructions',
                'label'   => 'Petunjuk',
                'name'    => '',
                'type'    => 'message',
                'message' => 'Isi hingga 6 keunggulan di bawah ini. Kosongkan <strong>Judul</strong> pada baris yang ingin disembunyikan. Icon menggunakan kelas Font Awesome, contoh: <code>fas fa-star</code>.',
            ),

            // --- Keunggulan 1 ---
            array(
                'key'           => 'field_feat_1_icon',
                'label'         => 'Keunggulan 1 — Icon FA',
                'name'          => 'feat_1_icon',
                'type'          => 'text',
                'default_value' => 'fas fa-graduation-cap',
                'instructions'  => 'Contoh: fas fa-star',
                'wrapper'       => array( 'width' => '25' ),
            ),
            array(
                'key'           => 'field_feat_1_title',
                'label'         => 'Keunggulan 1 — Judul',
                'name'          => 'feat_1_title',
                'type'          => 'text',
                'default_value' => 'Kurikulum Merdeka',
                'required'      => 1,
                'wrapper'       => array( 'width' => '35' ),
            ),
            array(
                'key'           => 'field_feat_1_desc',
                'label'         => 'Keunggulan 1 — Deskripsi',
                'name'          => 'feat_1_desc',
                'type'          => 'text',
                'default_value' => 'Pembelajaran berbasis proyek dan kompetensi.',
                'wrapper'       => array( 'width' => '40' ),
            ),

            // --- Keunggulan 2 ---
            array(
                'key'           => 'field_feat_2_icon',
                'label'         => 'Keunggulan 2 — Icon FA',
                'name'          => 'feat_2_icon',
                'type'          => 'text',
                'default_value' => 'fas fa-flask',
                'instructions'  => 'Contoh: fas fa-flask',
                'wrapper'       => array( 'width' => '25' ),
            ),
            array(
                'key'           => 'field_feat_2_title',
                'label'         => 'Keunggulan 2 — Judul',
                'name'          => 'feat_2_title',
                'type'          => 'text',
                'default_value' => 'Fasilitas Lengkap',
                'required'      => 1,
                'wrapper'       => array( 'width' => '35' ),
            ),
            array(
                'key'           => 'field_feat_2_desc',
                'label'         => 'Keunggulan 2 — Deskripsi',
                'name'          => 'feat_2_desc',
                'type'          => 'text',
                'default_value' => 'Lab modern, perpustakaan digital, dan WiFi kampus.',
                'wrapper'       => array( 'width' => '40' ),
            ),

            // --- Keunggulan 3 ---
            array(
                'key'           => 'field_feat_3_icon',
                'label'         => 'Keunggulan 3 — Icon FA',
                'name'          => 'feat_3_icon',
                'type'          => 'text',
                'default_value' => 'fas fa-chalkboard-teacher',
                'instructions'  => 'Contoh: fas fa-chalkboard-teacher',
                'wrapper'       => array( 'width' => '25' ),
            ),
            array(
                'key'           => 'field_feat_3_title',
                'label'         => 'Keunggulan 3 — Judul',
                'name'          => 'feat_3_title',
                'type'          => 'text',
                'default_value' => 'Guru Berkualitas',
                'required'      => 1,
                'wrapper'       => array( 'width' => '35' ),
            ),
            array(
                'key'           => 'field_feat_3_desc',
                'label'         => 'Keunggulan 3 — Deskripsi',
                'name'          => 'feat_3_desc',
                'type'          => 'text',
                'default_value' => 'Guru tersertifikasi dan berpengalaman.',
                'wrapper'       => array( 'width' => '40' ),
            ),

            // --- Keunggulan 4 ---
            array(
                'key'           => 'field_feat_4_icon',
                'label'         => 'Keunggulan 4 — Icon FA',
                'name'          => 'feat_4_icon',
                'type'          => 'text',
                'default_value' => 'fas fa-trophy',
                'instructions'  => 'Contoh: fas fa-trophy',
                'wrapper'       => array( 'width' => '25' ),
            ),
            array(
                'key'           => 'field_feat_4_title',
                'label'         => 'Keunggulan 4 — Judul',
                'name'          => 'feat_4_title',
                'type'          => 'text',
                'default_value' => 'Prestasi Nasional',
                'wrapper'       => array( 'width' => '35' ),
            ),
            array(
                'key'           => 'field_feat_4_desc',
                'label'         => 'Keunggulan 4 — Deskripsi',
                'name'          => 'feat_4_desc',
                'type'          => 'text',
                'default_value' => 'Juara di berbagai olimpiade dan kompetisi.',
                'wrapper'       => array( 'width' => '40' ),
            ),

            // --- Keunggulan 5 ---
            array(
                'key'           => 'field_feat_5_icon',
                'label'         => 'Keunggulan 5 — Icon FA',
                'name'          => 'feat_5_icon',
                'type'          => 'text',
                'default_value' => 'fas fa-users',
                'instructions'  => 'Contoh: fas fa-users',
                'wrapper'       => array( 'width' => '25' ),
            ),
            array(
                'key'           => 'field_feat_5_title',
                'label'         => 'Keunggulan 5 — Judul',
                'name'          => 'feat_5_title',
                'type'          => 'text',
                'default_value' => 'Ekstrakurikuler',
                'wrapper'       => array( 'width' => '35' ),
            ),
            array(
                'key'           => 'field_feat_5_desc',
                'label'         => 'Keunggulan 5 — Deskripsi',
                'name'          => 'feat_5_desc',
                'type'          => 'text',
                'default_value' => 'Lebih dari 25 kegiatan pengembangan bakat.',
                'wrapper'       => array( 'width' => '40' ),
            ),

            // --- Keunggulan 6 ---
            array(
                'key'           => 'field_feat_6_icon',
                'label'         => 'Keunggulan 6 — Icon FA',
                'name'          => 'feat_6_icon',
                'type'          => 'text',
                'default_value' => 'fas fa-globe',
                'instructions'  => 'Contoh: fas fa-globe',
                'wrapper'       => array( 'width' => '25' ),
            ),
            array(
                'key'           => 'field_feat_6_title',
                'label'         => 'Keunggulan 6 — Judul',
                'name'          => 'feat_6_title',
                'type'          => 'text',
                'default_value' => 'Wawasan Global',
                'wrapper'       => array( 'width' => '35' ),
            ),
            array(
                'key'           => 'field_feat_6_desc',
                'label'         => 'Keunggulan 6 — Deskripsi',
                'name'          => 'feat_6_desc',
                'type'          => 'text',
                'default_value' => 'Program pertukaran pelajar dan kerja sama internasional.',
                'wrapper'       => array( 'width' => '40' ),
            ),

            // =========================================================
            // TAB: Tombol
            // =========================================================
            array(
                'key'   => 'field_about_btns_tab',
                'label' => 'Tombol',
                'name'  => '',
                'type'  => 'tab',
            ),
            // --- Tombol 1 ---
            array(
                'key'           => 'field_about_btn1_label',
                'label'         => 'Tombol 1 — Label',
                'name'          => 'about_btn1_label',
                'type'          => 'text',
                'default_value' => 'Selengkapnya',
                'wrapper'       => array( 'width' => '25' ),
            ),
            array(
                'key'           => 'field_about_btn1_link_source',
                'label'         => 'Tombol 1 — Sumber Link',
                'name'          => 'about_btn1_link_source',
                'type'          => 'select',
                'choices'       => array( 'url' => 'URL Manual', 'page' => 'Pilih Halaman' ),
                'default_value' => 'url',
                'ui'            => 1,
                'instructions'  => 'URL Manual atau pilih halaman WP.',
                'wrapper'       => array( 'width' => '20' ),
            ),
            array(
                'key'              => 'field_about_btn1_url',
                'label'            => 'Tombol 1 — URL Manual',
                'name'             => 'about_btn1_url',
                'type'             => 'url',
                'default_value'    => '#profil',
                'wrapper'          => array( 'width' => '30' ),
                'conditional_logic' => array(
                    array(
                        array(
                            'field'    => 'field_about_btn1_link_source',
                            'operator' => '==',
                            'value'    => 'url',
                        ),
                    ),
                ),
            ),
            array(
                'key'              => 'field_about_btn1_page',
                'label'            => 'Tombol 1 — Pilih Halaman',
                'name'             => 'about_btn1_page',
                'type'             => 'page_link',
                'post_type'        => array( 'page' ),
                'allow_null'       => 1,
                'multiple'         => 0,
                'wrapper'          => array( 'width' => '30' ),
                'conditional_logic' => array(
                    array(
                        array(
                            'field'    => 'field_about_btn1_link_source',
                            'operator' => '==',
                            'value'    => 'page',
                        ),
                    ),
                ),
            ),
            array(
                'key'           => 'field_about_btn1_icon',
                'label'         => 'Tombol 1 — Icon FA',
                'name'          => 'about_btn1_icon',
                'type'          => 'text',
                'default_value' => 'fas fa-arrow-right',
                'wrapper'       => array( 'width' => '25' ),
            ),

            // --- Tombol 2 ---
            array(
                'key'           => 'field_about_btn2_label',
                'label'         => 'Tombol 2 — Label (kosongkan untuk disembunyikan)',
                'name'          => 'about_btn2_label',
                'type'          => 'text',
                'default_value' => 'Visi & Misi',
                'wrapper'       => array( 'width' => '25' ),
            ),
            array(
                'key'           => 'field_about_btn2_link_source',
                'label'         => 'Tombol 2 — Sumber Link',
                'name'          => 'about_btn2_link_source',
                'type'          => 'select',
                'choices'       => array( 'url' => 'URL Manual', 'page' => 'Pilih Halaman' ),
                'default_value' => 'url',
                'ui'            => 1,
                'wrapper'       => array( 'width' => '20' ),
            ),
            array(
                'key'              => 'field_about_btn2_url',
                'label'            => 'Tombol 2 — URL Manual',
                'name'             => 'about_btn2_url',
                'type'             => 'url',
                'default_value'    => '#visi-misi',
                'wrapper'          => array( 'width' => '30' ),
                'conditional_logic' => array(
                    array(
                        array(
                            'field'    => 'field_about_btn2_link_source',
                            'operator' => '==',
                            'value'    => 'url',
                        ),
                    ),
                ),
            ),
            array(
                'key'              => 'field_about_btn2_page',
                'label'            => 'Tombol 2 — Pilih Halaman',
                'name'             => 'about_btn2_page',
                'type'             => 'page_link',
                'post_type'        => array( 'page' ),
                'allow_null'       => 1,
                'multiple'         => 0,
                'wrapper'          => array( 'width' => '30' ),
                'conditional_logic' => array(
                    array(
                        array(
                            'field'    => 'field_about_btn2_link_source',
                            'operator' => '==',
                            'value'    => 'page',
                        ),
                    ),
                ),
            ),
            array(
                'key'           => 'field_about_btn2_icon',
                'label'         => 'Tombol 2 — Icon FA (opsional)',
                'name'          => 'about_btn2_icon',
                'type'          => 'text',
                'default_value' => '',
                'wrapper'       => array( 'width' => '25' ),
            ),

        ), // end fields

        // Tampilkan di halaman yang di-set sebagai Front Page
        'location' => array(
            array(
                array(
                    'param'    => 'page_type',
                    'operator' => '==',
                    'value'    => 'front_page',
                ),
            ),
        ),
        'menu_order'            => 5,
        'position'              => 'normal',
        'style'                 => 'default',
        'label_placement'       => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen'        => array(
            'the_content', 'excerpt', 'discussion', 'comments',
            'revisions', 'slug', 'author', 'format', 'page_attributes',
            'featured_image', 'categories', 'tags', 'send-trackbacks',
        ),
    ) );
} );
