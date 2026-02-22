<?php get_header(); ?>

    <!-- ===================== HERO SECTION ===================== -->
    <?php
    // Query Hero Slides CPT — ordered by menu_order (drag-to-sort in admin)
    $hero_slides = new WP_Query( array(
        'post_type'      => 'hero_slide',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'orderby'        => 'menu_order',
        'order'          => 'ASC',
    ) );

    // Fallback slide when no CPT posts exist yet
    $fallback_slides = array(
        array(
            'badge_icon'    => 'fas fa-school',
            'badge_text'    => 'Website Resmi SMAN 1 Purwokerto',
            'title'         => get_theme_mod( 'hero_title', 'Mewujudkan Generasi Emas Berkarakter Pancasila' ),
            'title_accent'  => 'Generasi Emas',
            'subtitle'      => get_theme_mod( 'hero_subtitle', 'SMAN 1 Purwokerto berkomitmen mencetak lulusan unggul dalam prestasi, luhur dalam budi pekerti, dan siap bersaing di era global.' ),
            'bg_url'        => get_template_directory_uri() . '/images/hero-slide-1.svg',
            'btn1_label'    => 'Jelajahi Profil',
            'btn1_url'      => '#profil',
            'btn1_icon'     => 'fas fa-arrow-right',
            'btn2_label'    => 'Hubungi Kami',
            'btn2_url'      => '#kontak',
            'btn2_icon'     => 'fas fa-envelope',
        ),
    );

    // Build slides array from CPT or fallback
    $slides = array();
    if ( $hero_slides->have_posts() ) {
        while ( $hero_slides->have_posts() ) {
            $hero_slides->the_post();
            $bg_url = get_field( 'slide_background' );
            if ( ! $bg_url ) {
                $bg_url = get_template_directory_uri() . '/images/hero-slide-1.svg';
            }

            $btn1_source = get_field( 'slide_btn1_link_source' ) ?: 'url';
            $btn1_manual = get_field( 'slide_btn1_url' );
            $btn1_page   = get_field( 'slide_btn1_page' );
            $btn1_url    = ( $btn1_source === 'page' && ! empty( $btn1_page ) ) ? $btn1_page : $btn1_manual;

            $btn2_source = get_field( 'slide_btn2_link_source' ) ?: 'url';
            $btn2_manual = get_field( 'slide_btn2_url' );
            $btn2_page   = get_field( 'slide_btn2_page' );
            $btn2_url    = ( $btn2_source === 'page' && ! empty( $btn2_page ) ) ? $btn2_page : $btn2_manual;

            $slides[] = array(
                'badge_icon'   => get_field( 'slide_badge_icon' )     ?: 'fas fa-school',
                'badge_text'   => get_field( 'slide_badge_text' )     ?: 'Website Resmi SMAN 1 Purwokerto',
                'title'        => get_field( 'slide_title' )           ?: get_the_title(),
                'title_accent' => (string) get_field( 'slide_title_accent' ),
                'subtitle'     => get_field( 'slide_subtitle' )        ?: '',
                'bg_url'       => $bg_url,
                'btn1_label'   => get_field( 'slide_btn1_label' )      ?: 'Jelajahi Profil',
                'btn1_url'     => $btn1_url ?: '#profil',
                'btn1_icon'    => get_field( 'slide_btn1_icon' )       ?: 'fas fa-arrow-right',
                'btn2_label'   => get_field( 'slide_btn2_label' )      ?: '',
                'btn2_url'     => $btn2_url ?: '',
                'btn2_icon'    => get_field( 'slide_btn2_icon' )       ?: 'fas fa-envelope',
            );
        }
        wp_reset_postdata();
    } else {
        $slides = $fallback_slides;
    }

    $total_slides = count( $slides );
    ?>
    <section class="hero" id="home">
        <div class="hero-shapes">
            <div class="shape shape-1"></div>
            <div class="shape shape-2"></div>
            <div class="shape shape-3"></div>
        </div>
        <div class="hero-pattern"></div>

        <div class="hero-slider" id="heroSlider" aria-label="Hero Slider">
            <?php foreach ( $slides as $i => $slide ) : ?>
            <div class="hero-slide <?php echo $i === 0 ? 'active' : ''; ?>" aria-hidden="<?php echo $i === 0 ? 'false' : 'true'; ?>">
                <div class="slide-bg" style="background-image: url('<?php echo esc_url( $slide['bg_url'] ); ?>');"></div>
                <div class="hero-overlay"></div>
                <div class="container hero-content">
                    <div class="hero-text-wrapper" data-aos="fade-right">
                        <?php if ( $slide['badge_text'] ) : ?>
                        <span class="hero-badge">
                            <i class="<?php echo esc_attr( $slide['badge_icon'] ); ?>"></i>
                            <?php echo esc_html( $slide['badge_text'] ); ?>
                        </span>
                        <?php endif; ?>
                        <h1><?php
                            $h1_title  = esc_html( $slide['title'] );
                            $h1_accent = esc_html( trim( $slide['title_accent'] ?? '' ) );
                            if ( $h1_accent !== '' && strpos( $h1_title, $h1_accent ) !== false ) {
                                echo str_replace(
                                    $h1_accent,
                                    '<span class="hero-title-accent">' . $h1_accent . '</span>',
                                    $h1_title
                                );
                            } else {
                                echo $h1_title;
                            }
                        ?></h1>
                        <?php if ( $slide['subtitle'] ) : ?>
                        <p><?php echo esc_html( $slide['subtitle'] ); ?></p>
                        <?php endif; ?>
                        <div class="hero-buttons">
                            <?php if ( $slide['btn1_label'] ) : ?>
                            <a href="<?php echo esc_url( $slide['btn1_url'] ); ?>" class="btn btn-lg btn-primary">
                                <span><?php echo esc_html( $slide['btn1_label'] ); ?></span>
                                <i class="<?php echo esc_attr( $slide['btn1_icon'] ); ?>"></i>
                            </a>
                            <?php endif; ?>
                            <?php if ( $slide['btn2_label'] ) : ?>
                            <a href="<?php echo esc_url( $slide['btn2_url'] ); ?>" class="btn btn-lg btn-outline-light">
                                <span><?php echo esc_html( $slide['btn2_label'] ); ?></span>
                                <i class="<?php echo esc_attr( $slide['btn2_icon'] ); ?>"></i>
                            </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <?php if ( $total_slides > 1 ) : ?>
        <!-- Controls: [prev] [dots] [next] — grouped bottom-center like template -->
        <div class="hero-controls" aria-label="Navigasi Slide">
            <button class="hero-prev" id="heroPrev" aria-label="Slide Sebelumnya">
                <i class="fas fa-chevron-left"></i>
            </button>
            <div class="hero-dots" aria-label="Indikator Slide">
                <?php for ( $d = 0; $d < $total_slides; $d++ ) : ?>
                <button class="hero-dot <?php echo $d === 0 ? 'active' : ''; ?>" aria-label="Slide <?php echo $d + 1; ?>"></button>
                <?php endfor; ?>
            </div>
            <button class="hero-next" id="heroNext" aria-label="Slide Berikutnya">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>
        <?php endif; ?>

    </section>

    <!-- ===================== STATS SECTION ===================== -->
    <section class="stats-section">
        <div class="container">
            <div class="stats-grid">
                <div class="stat-card" data-aos="fade-up" data-aos-delay="100">
                    <div class="stat-icon"><i class="fas fa-history"></i></div>
                    <div class="stat-content">
                        <span class="stat-number" data-count="60">0</span>
                        <span class="stat-label">Tahun Pengalaman</span>
                    </div>
                </div>
                <div class="stat-card" data-aos="fade-up" data-aos-delay="200">
                    <div class="stat-icon"><i class="fas fa-chalkboard-teacher"></i></div>
                    <div class="stat-content">
                        <span class="stat-number" data-count="<?php echo intval(get_theme_mod('stats_guru', '120')); ?>">0</span>
                        <span class="stat-label">Guru Profesional</span>
                    </div>
                </div>
                 <div class="stat-card" data-aos="fade-up" data-aos-delay="300">
                    <div class="stat-icon"><i class="fas fa-user-graduate"></i></div>
                    <div class="stat-content">
                        <span class="stat-number" data-count="<?php echo intval(get_theme_mod('stats_siswa', '1500')); ?>">0</span>
                        <span class="stat-label">Siswa Aktif</span>
                    </div>
                </div>
                <div class="stat-card" data-aos="fade-up" data-aos-delay="400">
                    <div class="stat-icon"><i class="fas fa-trophy"></i></div>
                    <div class="stat-content">
                        <span class="stat-number" data-count="500">0</span>
                        <span class="stat-label">Prestasi Diraih</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ===================== ABOUT SECTION ===================== -->
    <?php
    // Read ACF fields; fallback to hardcoded defaults if not set yet
    $about_subtitle_icon  = get_field('about_subtitle_icon')  ?: 'fas fa-school';
    $about_subtitle_text  = get_field('about_subtitle_text')  ?: 'Tentang Kami';
    $about_title          = get_field('about_title')          ?: 'Membangun Generasi';
    $about_title_accent   = get_field('about_title_accent')   ?: 'Unggul &amp; Berkarakter';
    $about_text           = get_field('about_text')           ?: 'SMA Negeri 1 Purwokerto berdiri sejak tahun 1960 dan menjadi salah satu sekolah unggulan di Jawa Tengah dengan komitmen menghadirkan pendidikan berkualitas dan berdaya saing global.';
    $about_image          = get_field('about_image')          ?: 'https://images.unsplash.com/photo-1580582932707-520aed937b7b?w=600&h=450&fit=crop';
    $about_video_url      = get_field('about_video_url')      ?: '';
    $about_video_embed    = sman1_video_embed_url( $about_video_url );
    $about_badge_number   = get_field('about_badge_number')   ?: 'A';
    $about_badge_text     = get_field('about_badge_text')     ?: 'Akreditasi';
    // Button 1 — resolve URL from source selector (url manual vs pilih halaman)
    $about_btn1_label     = get_field('about_btn1_label')     ?: 'Selengkapnya';
    $about_btn1_icon      = get_field('about_btn1_icon')      ?: 'fas fa-arrow-right';
    $_abt1_source         = get_field('about_btn1_link_source') ?: 'url';
    $_abt1_manual         = get_field('about_btn1_url');
    $_abt1_page           = get_field('about_btn1_page');
    $about_btn1_url       = ( $_abt1_source === 'page' && ! empty( $_abt1_page ) ) ? $_abt1_page : ( $_abt1_manual ?: '#profil' );
    // Button 2
    $about_btn2_label     = get_field('about_btn2_label')     ?: 'Visi & Misi';
    $about_btn2_icon      = get_field('about_btn2_icon')      ?: '';
    $_abt2_source         = get_field('about_btn2_link_source') ?: 'url';
    $_abt2_manual         = get_field('about_btn2_url');
    $_abt2_page           = get_field('about_btn2_page');
    $about_btn2_url       = ( $_abt2_source === 'page' && ! empty( $_abt2_page ) ) ? $_abt2_page : ( $_abt2_manual ?: '#visi-misi' );

    // Build features array from 6 fixed ACF fields (ACF Free compatible)
    $feat_defaults = array(
        1 => array( 'fas fa-graduation-cap',      'Kurikulum Merdeka',  'Pembelajaran berbasis proyek dan kompetensi.' ),
        2 => array( 'fas fa-flask',               'Fasilitas Lengkap',  'Lab modern, perpustakaan digital, dan WiFi kampus.' ),
        3 => array( 'fas fa-chalkboard-teacher',  'Guru Berkualitas',   'Guru tersertifikasi dan berpengalaman.' ),
        4 => array( 'fas fa-trophy',              'Prestasi Nasional',  'Juara di berbagai olimpiade dan kompetisi.' ),
        5 => array( 'fas fa-users',               'Ekstrakurikuler',    'Lebih dari 25 kegiatan pengembangan bakat.' ),
        6 => array( 'fas fa-globe',               'Wawasan Global',     'Program pertukaran pelajar dan kerja sama internasional.' ),
    );
    $about_features_raw = array();
    for ( $i = 1; $i <= 6; $i++ ) {
        $title = get_field( "feat_{$i}_title" );
        if ( $title === null ) {
            // ACF field not yet saved — fall back to default
            $title = $feat_defaults[ $i ][1];
        }
        if ( $title === '' ) continue; // empty title = hidden row
        $about_features_raw[] = array(
            'feat_icon'  => get_field( "feat_{$i}_icon" )  ?: $feat_defaults[ $i ][0],
            'feat_title' => $title,
            'feat_desc'  => get_field( "feat_{$i}_desc" )  !== null
                                ? get_field( "feat_{$i}_desc" )
                                : $feat_defaults[ $i ][2],
        );
    }
    ?>
    <section class="about-section section" id="about">
        <div class="container">
            <div class="about-grid">
                <!-- Left: Image -->
                <div class="about-image" data-aos="fade-right">
                    <div class="about-image-wrapper">
                        <img src="<?php echo esc_url( $about_image ); ?>" alt="<?php bloginfo('name'); ?>">
                        <?php if ( $about_video_embed ) : ?>
                        <button class="about-image-overlay" type="button"
                            data-video-embed="<?php echo esc_attr( $about_video_embed ); ?>"
                            aria-label="Putar Video Profil">
                            <div class="play-btn"><i class="fas fa-play"></i></div>
                            <span>Video Profil</span>
                        </button>
                        <?php endif; ?>
                    </div>
                    <div class="about-badge">
                        <span class="badge-number"><?php echo esc_html( $about_badge_number ); ?></span>
                        <span class="badge-text"><?php echo esc_html( $about_badge_text ); ?></span>
                    </div>
                </div>

                <!-- Right: Content -->
                <div class="about-content" data-aos="fade-left">
                    <div class="section-subtitle">
                        <i class="<?php echo esc_attr( $about_subtitle_icon ); ?>"></i>
                        <span><?php echo esc_html( $about_subtitle_text ); ?></span>
                    </div>
                    <h2 class="section-title">
                        <?php echo esc_html( $about_title ); ?>
                        <?php if ( $about_title_accent ) : ?>
                        <span class="text-accent"><?php echo esc_html( $about_title_accent ); ?></span>
                        <?php endif; ?>
                    </h2>
                    <p class="about-text"><?php echo esc_html( $about_text ); ?></p>

                    <?php if ( ! empty( $about_features_raw ) ) : ?>
                    <div class="about-features">
                        <?php foreach ( $about_features_raw as $feat ) : ?>
                        <div class="feature-item">
                            <div class="feature-icon"><i class="<?php echo esc_attr( $feat['feat_icon'] ?: 'fas fa-check-circle' ); ?>"></i></div>
                            <div class="feature-content">
                                <h4><?php echo esc_html( $feat['feat_title'] ); ?></h4>
                                <?php if ( $feat['feat_desc'] ) : ?>
                                <p><?php echo esc_html( $feat['feat_desc'] ); ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>

                    <div class="about-buttons">
                        <?php if ( $about_btn1_label ) : ?>
                        <a href="<?php echo esc_url( $about_btn1_url ); ?>" class="btn btn-primary">
                            <span><?php echo esc_html( $about_btn1_label ); ?></span>
                            <?php if ( $about_btn1_icon ) : ?><i class="<?php echo esc_attr( $about_btn1_icon ); ?>"></i><?php endif; ?>
                        </a>
                        <?php endif; ?>
                        <?php if ( $about_btn2_label ) : ?>
                        <a href="<?php echo esc_url( $about_btn2_url ); ?>" class="btn btn-outline">
                            <span><?php echo esc_html( $about_btn2_label ); ?></span>
                            <?php if ( $about_btn2_icon ) : ?><i class="<?php echo esc_attr( $about_btn2_icon ); ?>"></i><?php endif; ?>
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ── Video Profil Modal ── -->
    <div id="video-modal" class="vmodal" role="dialog" aria-modal="true" aria-label="Video Profil" hidden>
        <div class="vmodal-backdrop"></div>
        <div class="vmodal-box">
            <button class="vmodal-close" type="button" aria-label="Tutup video">
                <i class="fas fa-times" aria-hidden="true"></i>
            </button>
            <div class="vmodal-ratio">
                <iframe id="video-modal-iframe"
                    src=""
                    title="Video Profil SMAN 1 Purwokerto"
                    frameborder="0"
                    allow="autoplay; fullscreen; picture-in-picture"
                    allowfullscreen
                ></iframe>
            </div>
        </div>
    </div>

    <script>
    (function () {
        var modal   = document.getElementById('video-modal');
        var iframe  = document.getElementById('video-modal-iframe');
        var trigger = document.querySelector('.about-image-overlay[data-video-embed]');
        var closeBtn;

        if (!modal || !iframe || !trigger) return;
        closeBtn = modal.querySelector('.vmodal-close');

        function openModal(embedUrl) {
            iframe.src = embedUrl + (embedUrl.indexOf('?') >= 0 ? '&' : '?') + 'autoplay=1&rel=0';
            modal.hidden = false;
            document.body.classList.add('vmodal-open');
            if (closeBtn) closeBtn.focus();
        }

        function closeModal() {
            iframe.src = '';
            modal.hidden = true;
            document.body.classList.remove('vmodal-open');
            trigger.focus();
        }

        trigger.addEventListener('click', function () {
            openModal(this.dataset.videoEmbed);
        });

        if (closeBtn) {
            closeBtn.addEventListener('click', closeModal);
        }

        modal.querySelector('.vmodal-backdrop').addEventListener('click', closeModal);

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && !modal.hidden) {
                closeModal();
            }
        });
    })();
    </script>

    <!-- QUICK ACCESS (Static for now, editable via Menu or ACF) -->
    <?php
    // --- Quick Access Cards: query CPT, fall back to defaults if empty ---
    $qa_query = new WP_Query( array(
        'post_type'      => 'quick_access_card',
        'posts_per_page' => 12,
        'orderby'        => 'menu_order',
        'order'          => 'ASC',
        'post_status'    => 'publish',
    ) );
    $qa_cards = array();
    if ( $qa_query->have_posts() ) {
        while ( $qa_query->have_posts() ) {
            $qa_query->the_post();
            $pid = get_the_ID();
            $qa_cards[] = array(
                'icon'      => get_post_meta( $pid, 'qa_icon', true )      ?: 'fas fa-link',
                'title'     => get_post_meta( $pid, 'qa_title', true )     ?: get_the_title(),
                'desc'      => get_post_meta( $pid, 'qa_desc', true )      ?: '',
                'url'       => get_post_meta( $pid, 'qa_url', true )       ?: '#',
                'highlight' => (bool) get_post_meta( $pid, 'qa_highlight', true ),
            );
        }
        wp_reset_postdata();
    } else {
        $qa_cards = array(
            array( 'icon' => 'fas fa-laptop-code', 'title' => 'E-Learning',   'desc' => 'Sistem pembelajaran daring',   'url' => '#', 'highlight' => false ),
            array( 'icon' => 'fas fa-edit',         'title' => 'Ujian Online', 'desc' => 'Platform ujian digital',       'url' => '#', 'highlight' => false ),
            array( 'icon' => 'fas fa-user-circle',  'title' => 'Portal Siswa', 'desc' => 'Data & rapor digital',         'url' => '#', 'highlight' => false ),
            array( 'icon' => 'fas fa-medal',        'title' => 'Prestasi',     'desc' => 'Rekam jejak prestasi',         'url' => '#', 'highlight' => false ),
            array( 'icon' => 'fas fa-book-reader',  'title' => 'Perpustakaan', 'desc' => 'Katalog & peminjaman',         'url' => '#', 'highlight' => false ),
            array( 'icon' => 'fas fa-user-plus',    'title' => 'SPMB Online',  'desc' => 'Pendaftaran Murid Baru',       'url' => get_option( 'sman1_spmb_url', '#spmb' ), 'highlight' => true  ),
        );
    }
    ?>
    <section class="quick-access-section section">
        <div class="container">
            <div class="section-header text-center" data-aos="fade-up">
                <div class="section-subtitle">
                    <i class="fas fa-laptop"></i>
                    <span>Sistem Informasi</span>
                </div>
                <h2 class="section-title">Akses Cepat <span class="text-accent">Layanan Digital</span></h2>
                <p>Platform terintegrasi untuk kemudahan siswa, guru, dan orang tua</p>
            </div>
            <div class="quick-access-grid">
                <?php foreach ( $qa_cards as $idx => $card ) :
                    $delay     = ( $idx + 1 ) * 100;
                    $highlight = $card['highlight'] ? ' qa-card-highlight' : '';
                ?>
                <a href="<?php echo esc_url( $card['url'] ); ?>" class="quick-access-card<?php echo $highlight; ?>" data-aos="zoom-in" data-aos-delay="<?php echo $delay; ?>">
                    <div class="qa-icon">
                        <i class="<?php echo esc_attr( $card['icon'] ); ?>"></i>
                    </div>
                    <h4><?php echo esc_html( $card['title'] ); ?></h4>
                    <?php if ( $card['desc'] ) : ?>
                    <p><?php echo esc_html( $card['desc'] ); ?></p>
                    <?php endif; ?>
                    <span class="qa-arrow"><i class="fas fa-arrow-right"></i></span>
                </a>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- ===================== NEWS SECTION ===================== -->
    <?php
    // Map category slug → Font Awesome icon class
    $news_cat_icons = array(
        'pengumuman'  => 'fas fa-bullhorn',
        'prestasi'    => 'fas fa-trophy',
        'akademik'    => 'fas fa-book',
        'kegiatan'    => 'fas fa-flag',
        'ppdb'        => 'fas fa-user-plus',
        'spmb'        => 'fas fa-user-plus',
        'ekstrakurikuler' => 'fas fa-users',
        'berita'      => 'fas fa-newspaper',
        'uncategorized' => 'fas fa-newspaper',
    );

    // "Semua Berita" link — uses the configured Posts page, falls back to /berita/
    $news_page_id  = (int) get_option( 'page_for_posts' );
    $news_page_url = $news_page_id ? get_permalink( $news_page_id ) : home_url( '/berita/' );

    // Placeholder image (used when post has no featured image)
    $news_placeholder = get_template_directory_uri() . '/images/news-placeholder.svg';

    // Query latest 4 published posts
    $news_query = new WP_Query( array(
        'post_type'      => 'post',
        'post_status'    => 'publish',
        'posts_per_page' => 4,
        'orderby'        => 'date',
        'order'          => 'DESC',
        'ignore_sticky_posts' => false,
    ) );
    $news_posts = $news_query->posts;
    wp_reset_postdata();
    ?>
    <section class="news-section section" id="news">
        <div class="container">
            <div class="section-header" data-aos="fade-up">
                <div class="section-header-left">
                    <div class="section-subtitle">
                        <i class="fas fa-newspaper"></i>
                        <span>Berita &amp; Informasi</span>
                    </div>
                    <h2 class="section-title">Kabar Terbaru <span class="text-accent">Sekolah</span></h2>
                    <p class="section-desc">Informasi terkini seputar kegiatan, pengumuman, dan pencapaian terbaru dari SMAN 1 Purwokerto.</p>
                </div>
                <div class="section-header-right">
                    <a href="<?php echo esc_url( $news_page_url ); ?>" class="btn btn-outline">
                        <span>Semua Berita</span>
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>

            <?php if ( ! empty( $news_posts ) ) : ?>
            <div class="news-grid">
                <?php foreach ( $news_posts as $idx => $post ) :
                    setup_postdata( $post );

                    // Featured image
                    $thumb_url = get_the_post_thumbnail_url( $post->ID, $idx === 0 ? 'large' : 'medium_large' );
                    if ( ! $thumb_url ) $thumb_url = $news_placeholder;

                    // Category: first assigned category
                    $cats      = get_the_category( $post->ID );
                    $cat_name  = ! empty( $cats ) ? $cats[0]->name  : 'Berita';
                    $cat_slug  = ! empty( $cats ) ? $cats[0]->slug  : 'berita';
                    $cat_icon  = isset( $news_cat_icons[ $cat_slug ] ) ? $news_cat_icons[ $cat_slug ] : 'fas fa-newspaper';

                    // Author
                    $author    = get_the_author_meta( 'display_name', $post->post_author );

                    // Date in Indonesian format
                    $indo_months = array(
                        1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',
                        7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember',
                    );
                    $ts   = strtotime( $post->post_date );
                    $date = date( 'j', $ts ) . ' ' . $indo_months[ (int) date( 'n', $ts ) ] . ' ' . date( 'Y', $ts );

                    $is_featured = ( $idx === 0 );
                    $delay       = ( $idx + 1 ) * 100;
                    $card_class  = 'news-card' . ( $is_featured ? ' news-featured' : '' );
                ?>
                <article class="<?php echo $card_class; ?>" data-aos="fade-up" data-aos-delay="<?php echo $delay; ?>">
                    <div class="news-image">
                        <img src="<?php echo esc_url( $thumb_url ); ?>" alt="<?php echo esc_attr( get_the_title( $post->ID ) ); ?>">
                        <div class="news-category">
                            <i class="<?php echo esc_attr( $cat_icon ); ?>"></i>
                            <?php echo esc_html( $cat_name ); ?>
                        </div>
                    </div>
                    <div class="news-content">
                        <div class="news-meta">
                            <span><i class="fas fa-calendar"></i> <?php echo esc_html( $date ); ?></span>
                            <?php if ( $is_featured ) : ?>
                            <span><i class="fas fa-user"></i> <?php echo esc_html( $author ); ?></span>
                            <?php endif; ?>
                        </div>
                        <h3 class="news-title">
                            <a href="<?php echo esc_url( get_permalink( $post->ID ) ); ?>"><?php echo esc_html( get_the_title( $post->ID ) ); ?></a>
                        </h3>
                        <?php if ( $is_featured ) :
                            $excerpt = $post->post_excerpt ?: wp_trim_words( strip_tags( $post->post_content ), 25, '...' );
                        ?>
                        <p class="news-excerpt"><?php echo esc_html( $excerpt ); ?></p>
                        <?php endif; ?>
                        <a href="<?php echo esc_url( get_permalink( $post->ID ) ); ?>" class="news-read-more">
                            Baca Selengkapnya <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </article>
                <?php endforeach; wp_reset_postdata(); ?>
            </div>
            <?php else : ?>
            <div class="news-empty" style="text-align:center;padding:3rem 0;color:var(--gray-500);">
                <i class="fas fa-newspaper" style="font-size:3rem;margin-bottom:1rem;display:block;opacity:.3;"></i>
                <p>Belum ada berita. <a href="<?php echo esc_url( admin_url('post-new.php') ); ?>">Tambah berita pertama</a>.</p>
            </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- ===================== PROGRAMS SECTION ===================== -->
    <?php
    $prog_query = new WP_Query( array(
        'post_type'      => 'school_program',
        'posts_per_page' => 12,
        'orderby'        => 'menu_order',
        'order'          => 'ASC',
        'post_status'    => 'publish',
    ) );
    $programs = $prog_query->posts;
    wp_reset_postdata();

    if ( empty( $programs ) ) {
        // Fallback default 8 programs
        $programs = array(
            (object)array('ID'=>0,'post_title'=>'Sekolah Sehat',                             'sp_icon'=>'fas fa-hospital',       'sp_desc'=>'Mewujudkan lingkungan belajar yang bersih, sehat, dan nyaman melalui program UKS aktif, kantin bergizi, dan gerakan hidup sehat seluruh warga sekolah.',   'sp_url'=>'#','sp_featured'=>false),
            (object)array('ID'=>0,'post_title'=>'Sekolah Ramah Anak',                        'sp_icon'=>'fas fa-child',          'sp_desc'=>'Menciptakan lingkungan sekolah yang aman, inklusif, dan menyenangkan yang menghargai hak-hak anak serta mendukung tumbuh kembang optimal siswa.',       'sp_url'=>'#','sp_featured'=>false),
            (object)array('ID'=>0,'post_title'=>'Sekolah Berintegritas',                     'sp_icon'=>'fas fa-shield-halved',  'sp_desc'=>'Membangun budaya kejujuran, transparansi, dan tanggung jawab dalam seluruh aspek kehidupan warga sekolah sebagai fondasi karakter yang unggul.',        'sp_url'=>'#','sp_featured'=>false),
            (object)array('ID'=>0,'post_title'=>'Sekolah Riset',                             'sp_icon'=>'fas fa-microscope',     'sp_desc'=>'Membudayakan penelitian ilmiah dan inovasi berbasis masalah nyata, mendorong siswa menjadi ilmuwan muda yang berpikir kritis, analitis, dan kreatif.',  'sp_url'=>'#','sp_featured'=>false),
            (object)array('ID'=>0,'post_title'=>'Sekolah Prestasi',                          'sp_icon'=>'fas fa-trophy',         'sp_desc'=>'Membina dan mengembangkan potensi siswa di bidang akademik, seni, dan olahraga untuk meraih prestasi membanggakan di tingkat nasional maupun internasional.','sp_url'=>'#','sp_featured'=>false),
            (object)array('ID'=>0,'post_title'=>'Gerakan Kepeloporan',                       'sp_icon'=>'fas fa-rocket',         'sp_desc'=>'Menumbuhkan jiwa pemimpin, keberanian berinovasi, dan semangat menjadi pelopor perubahan positif bagi lingkungan, masyarakat, dan bangsa.',              'sp_url'=>'#','sp_featured'=>false),
            (object)array('ID'=>0,'post_title'=>'Gerakan Literasi',                          'sp_icon'=>'fas fa-book-open',      'sp_desc'=>'Membangun budaya baca-tulis yang kuat melalui membaca harian, pojok buku, dan penulisan kreatif untuk meningkatkan kemampuan literasi siswa.',           'sp_url'=>'#','sp_featured'=>false),
            (object)array('ID'=>0,'post_title'=>'Gerakan Pembiasaan Anak Indonesia Hebat',   'sp_icon'=>'fas fa-seedling',       'sp_desc'=>'Menanamkan kebiasaan hidup sehat sejak dini melalui olahraga rutin, pola makan bergizi, dan kesehatan mental sebagai investasi terbaik untuk masa depan.','sp_url'=>'#','sp_featured'=>false),
        );
        // Mark as fallback so we read dummy fields directly
        $prog_is_fallback = true;
    } else {
        $prog_is_fallback = false;
    }
    ?>
    <section class="programs-section section" id="program">
        <div class="programs-bg"></div>
        <div class="container">
            <div class="section-header text-center" data-aos="fade-up">
                <div class="section-subtitle">
                    <i class="fas fa-star"></i>
                    <span>Program Unggulan</span>
                </div>
                <h2 class="section-title">NAWA BRATA <span class="text-accent">WIDYAKARYA</span></h2>
                <p class="section-desc">Delapan program unggulan sebagai pilar utama transformasi pendidikan menuju sekolah berkualitas global.</p>
            </div>

            <div class="programs-grid">
                <?php foreach ( $programs as $idx => $prog ) :
                    if ( $prog_is_fallback ) {
                        $is_featured = $prog->sp_featured;
                        $icon        = $prog->sp_icon;
                        $desc        = $prog->sp_desc;
                        $url         = $prog->sp_url;
                        $title       = $prog->post_title;
                    } else {
                        $is_featured = (bool) get_post_meta( $prog->ID, 'sp_featured', true );
                        $icon        = get_post_meta( $prog->ID, 'sp_icon', true ) ?: 'fas fa-star';
                        $desc        = get_post_meta( $prog->ID, 'sp_desc', true ) ?: '';
                        $url         = get_post_meta( $prog->ID, 'sp_url', true )  ?: '#';
                        $title       = get_the_title( $prog->ID );
                    }
                    $num   = sprintf( '%02d', $idx + 1 );
                    $delay = min( ( $idx + 1 ) * 100, 800 );
                    $card_class = 'program-card' . ( $is_featured ? ' program-card-featured' : '' );
                ?>
                <div class="<?php echo $card_class; ?>" data-aos="fade-up" data-aos-delay="<?php echo $delay; ?>">
                    <?php if ( $is_featured ) : ?>
                    <span class="program-badge">Unggulan</span>
                    <?php endif; ?>
                    <span class="program-card-number"><?php echo $num; ?></span>
                    <div class="program-icon">
                        <i class="<?php echo esc_attr( $icon ); ?>"></i>
                    </div>
                    <h3><?php echo esc_html( $title ); ?></h3>
                    <p><?php echo esc_html( $desc ); ?></p>
                    <a href="<?php echo esc_url( $url ); ?>" class="btn-program-link">
                        Selengkapnya <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <?php
    // ── Achievements Query ────────────────────────────────────────────────────
    $ach_query = new WP_Query( array(
        'post_type'      => 'school_achievement',
        'posts_per_page' => 4,
        'orderby'        => 'menu_order date',
        'order'          => 'DESC',
        'post_status'    => 'publish',
    ) );
    $achievements    = $ach_query->posts;
    wp_reset_postdata();
    $ach_archive_url = get_post_type_archive_link( 'school_achievement' );
    if ( empty( $achievements ) ) {
        $achievements = array(
            (object)array('ID'=>0,'post_title'=>'Medali Emas OSN Matematika',       'ach_medal'=>'gold',   'ach_icon'=>'fas fa-medal',  'ach_event'=>'Olimpiade Sains Nasional 2025',             'ach_student'=>'Ahmad Fauzi - XII MIPA 1'),
            (object)array('ID'=>0,'post_title'=>'Juara 2 Debat Bahasa Inggris',     'ach_medal'=>'silver', 'ach_icon'=>'fas fa-medal',  'ach_event'=>'National English Debate Competition',       'ach_student'=>'Tim Debat SMANSA'),
            (object)array('ID'=>0,'post_title'=>'Juara 1 Paduan Suara',             'ach_medal'=>'gold',   'ach_icon'=>'fas fa-trophy', 'ach_event'=>'Karangturi International Choir Competition', 'ach_student'=>'Balakosa Janitra Voice'),
            (object)array('ID'=>0,'post_title'=>'Medali Perunggu OSN Fisika',       'ach_medal'=>'bronze', 'ach_icon'=>'fas fa-medal',  'ach_event'=>'Olimpiade Sains Nasional 2025',             'ach_student'=>'Budi Santoso - XII MIPA 2'),
        );
        $ach_is_fallback = true;
    } else {
        $ach_is_fallback = false;
    }
    ?>
    <!-- ===================== ACHIEVEMENTS SECTION ===================== -->
    <section class="achievements-section section" id="achievements">
        <div class="container">
            <div class="section-header" data-aos="fade-up">
                <div class="section-header-left">
                    <div class="section-subtitle"><i class="fas fa-medal"></i><span>Prestasi Terbaru</span></div>
                    <h2 class="section-title">Kebanggaan <span class="text-accent">SMANSA</span></h2>
                    <p class="section-desc">Deretan prestasi membanggakan yang diraih siswa-siswi SMAN 1 Purwokerto di berbagai kompetisi daerah, nasional, dan internasional.</p>
                </div>
                <div class="section-header-right">
                    <a href="<?php echo esc_url( $ach_archive_url ?: '#' ); ?>" class="btn btn-outline"><span>Semua Prestasi</span><i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
            <div class="achievements-slider" data-aos="fade-up">
                <?php foreach ( $achievements as $ach ) :
                    if ( $ach_is_fallback ) {
                        $title   = $ach->post_title;
                        $medal   = $ach->ach_medal;
                        $icon    = $ach->ach_icon;
                        $event   = $ach->ach_event;
                        $student = $ach->ach_student;
                    } else {
                        $title   = get_the_title( $ach->ID );
                        $medal   = get_post_meta( $ach->ID, 'ach_medal',   true ) ?: 'gold';
                        $icon    = get_post_meta( $ach->ID, 'ach_icon',    true ) ?: 'fas fa-medal';
                        $event   = get_post_meta( $ach->ID, 'ach_event',   true ) ?: '';
                        $student = get_post_meta( $ach->ID, 'ach_student', true ) ?: '';
                    }
                    if ( ! $icon ) $icon = 'fas fa-medal';
                ?>
                <div class="achievement-card">
                    <div class="achievement-medal <?php echo esc_attr( $medal ); ?>">
                        <i class="<?php echo esc_attr( $icon ); ?>"></i>
                    </div>
                    <div class="achievement-content">
                        <h4><?php echo esc_html( $title ); ?></h4>
                        <p><?php echo esc_html( $event ); ?></p>
                        <span class="achievement-student"><?php echo esc_html( $student ); ?></span>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- TESTIMONIALS (Swiper) -->
    <?php
    // ── Testimonials Query ────────────────────────────────────────────────────
    $testi_query = new WP_Query( array(
        'post_type'      => 'testimonial',
        'posts_per_page' => 10,
        'orderby'        => 'menu_order',
        'order'          => 'ASC',
        'post_status'    => 'publish',
    ) );
    $testimonials    = $testi_query->posts;
    wp_reset_postdata();
    if ( empty( $testimonials ) ) {
        $testimonials = array(
            (object)array('ID'=>0,'post_title'=>'Dr. Andi Rahman, M.Sc.',   'testi_role'=>'Alumni 2005 | Dosen ITB',            'testi_quote'=>'SMANSA telah memberikan fondasi yang kuat untuk karier saya. Guru-guru yang berdedikasi dan lingkungan belajar yang kondusif membuat saya bisa meraih cita-cita.','testi_avatar_url'=>'','testi_avatar_bg'=>'1a365d'),
            (object)array('ID'=>0,'post_title'=>'Ibu Siti Nurhaliza',       'testi_role'=>'Orang Tua Siswa',                    'testi_quote'=>'Fasilitas lengkap, guru profesional, dan budaya prestasi yang kuat. SMANSA adalah pilihan tepat untuk anak-anak yang ingin meraih masa depan cemerlang.','testi_avatar_url'=>'','testi_avatar_bg'=>'d4a953'),
            (object)array('ID'=>0,'post_title'=>'Putri Ayu Lestari',        'testi_role'=>'Siswa XI MIPA 3',                    'testi_quote'=>'Di SMANSA saya tidak hanya belajar akademik, tapi juga mengembangkan soft skill melalui berbagai ekstrakurikuler yang sangat mendukung masa depan.','testi_avatar_url'=>'','testi_avatar_bg'=>'1a365d'),
            (object)array('ID'=>0,'post_title'=>'Bapak Rahmat Hidayat',     'testi_role'=>'Orang Tua Siswa Kelas XII',          'testi_quote'=>'Perkembangan anak saya luar biasa sejak masuk SMANSA. Program sekolah ramah anak dan dukungan konseling membuat anak lebih percaya diri dan mandiri.','testi_avatar_url'=>'','testi_avatar_bg'=>'1e6f3e'),
            (object)array('ID'=>0,'post_title'=>'Rizka Amalia Putri',       'testi_role'=>'Alumni 2022 | Mahasiswa UGM',        'testi_quote'=>'Bekal ilmu dan karakter dari SMANSA sangat terasa saat kuliah. Program literasi dan riset yang diterapkan membuat saya lebih siap menghadapi dunia akademik.','testi_avatar_url'=>'','testi_avatar_bg'=>'661012'),
        );
        $testi_is_fallback = true;
    } else {
        $testi_is_fallback = false;
    }
    ?>
    <section class="testimonials-section section">
        <div class="container">
            <div class="section-header text-center" data-aos="fade-up">
                <div class="section-subtitle">
                    <i class="fas fa-quote-left"></i>
                    <span>Testimonial</span>
                </div>
                <h2 class="section-title">Kata Mereka Tentang <span class="text-accent">SMANSA</span></h2>
                <p class="section-desc" style="margin: 0 auto;">Pengalaman nyata dari alumni, siswa, dan orang tua yang merasakan langsung keunggulan pendidikan di SMAN 1 Purwokerto.</p>
            </div>

            <!-- Swiper Slider -->
            <div class="swiper testimonialSwiper">
                <div class="swiper-wrapper">
                    <?php foreach ( $testimonials as $testi ) :
                        if ( $testi_is_fallback ) {
                            $name       = $testi->post_title;
                            $role       = $testi->testi_role;
                            $quote      = $testi->testi_quote;
                            $avatar_url = $testi->testi_avatar_url;
                            $avatar_bg  = $testi->testi_avatar_bg ?: '661012';
                        } else {
                            $name       = get_the_title( $testi->ID );
                            $role       = get_post_meta( $testi->ID, 'testi_role',       true ) ?: '';
                            $quote      = get_post_meta( $testi->ID, 'testi_quote',      true ) ?: '';
                            $avatar_url = get_post_meta( $testi->ID, 'testi_avatar_url', true ) ?: '';
                            $avatar_bg  = get_post_meta( $testi->ID, 'testi_avatar_bg',  true ) ?: '661012';
                        }
                        $initials   = implode( '', array_map( fn($w) => mb_strtoupper( mb_substr($w,0,1) ), array_slice( explode(' ', $name), 0, 2 ) ) );
                        $avatar_src = $avatar_url ?: 'https://ui-avatars.com/api/?name=' . urlencode($name) . '&background=' . $avatar_bg . '&color=fff&size=80';
                    ?>
                    <div class="swiper-slide">
                        <div class="testimonial-card">
                            <div class="testimonial-content">
                                <div class="quote-icon"><i class="fas fa-quote-left"></i></div>
                                <p>"<?php echo esc_html( $quote ); ?>"</p>
                            </div>
                            <div class="testimonial-author">
                                <img src="<?php echo esc_url( $avatar_src ); ?>" alt="<?php echo esc_attr( $name ); ?>">
                                <div class="author-info">
                                    <h5><?php echo esc_html( $name ); ?></h5>
                                    <span><?php echo esc_html( $role ); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <!-- Pagination -->
                <div class="swiper-pagination"></div>
            </div>
        </div>
    </section>

    <!-- ===================== GALLERY SECTION ===================== -->
    <?php
    $gallery_page_id  = intval(get_option('sman1_gallery_page_id', 0));
    $gallery_page_url = $gallery_page_id ? get_permalink($gallery_page_id) : '#';

    // Fetch ALL published gallery items so every category filter works correctly.
    $gallery_limit = intval(get_option('sman1_gallery_home_limit', 6));
    $gallery_query = new WP_Query([
        'post_type'      => 'gallery_item',
        'posts_per_page' => -1,
        'post_status'    => 'publish',
        'orderby'        => 'date',
        'order'          => 'DESC',
    ]);

    // Collect available categories (only terms that actually have posts).
    $gallery_terms = get_terms([
        'taxonomy'   => 'gallery_category',
        'hide_empty' => true,
        'orderby'    => 'name',
        'order'      => 'ASC',
    ]);

    // Build lightbox data array for JS
    $lightbox_items = [];
    if ($gallery_query->have_posts()) {
        while ($gallery_query->have_posts()) {
            $gallery_query->the_post();
            $full_src   = get_the_post_thumbnail_url(get_the_ID(), 'full');
            $caption    = get_post_meta(get_the_ID(), '_gallery_caption', true) ?: get_the_title();
            $terms      = get_the_terms(get_the_ID(), 'gallery_category');
            $cat_slug   = ($terms && ! is_wp_error($terms)) ? $terms[0]->slug : 'all';
            $lightbox_items[] = [
                'src'     => $full_src,
                'caption' => $caption,
                'cat'     => $cat_slug,
            ];
        }
        wp_reset_postdata();
    }
    ?>
    <section class="gallery-section section" id="gallery">
        <div class="container">
            <div class="section-header text-center" data-aos="fade-up">
                <div class="section-subtitle"><i class="fas fa-images"></i><span>Galeri</span></div>
                <h2 class="section-title">Momen <span class="text-accent">Berkesan</span></h2>
                <p>Dokumentasi kegiatan dan prestasi SMAN 1 Purwokerto</p>
            </div>
            <div class="gallery-filter" data-aos="fade-up">
                <button class="filter-btn active" data-filter="all">Semua</button>
                <?php if (! is_wp_error($gallery_terms) && ! empty($gallery_terms)) :
                    foreach ($gallery_terms as $term) : ?>
                <button class="filter-btn" data-filter="<?php echo esc_attr($term->slug); ?>">
                    <?php echo esc_html($term->name); ?>
                </button>
                <?php   endforeach;
                endif; ?>
            </div>

            <div class="gallery-grid" data-aos="fade-up" id="homeGalleryGrid">
                <?php if ($gallery_query->post_count > 0) :
                    $gallery_query->rewind_posts();
                    $idx = 0;
                    while ($gallery_query->have_posts()) :
                        $gallery_query->the_post();
                        $thumb_src  = get_the_post_thumbnail_url(get_the_ID(), 'large');
                        $caption    = get_post_meta(get_the_ID(), '_gallery_caption', true) ?: get_the_title();
                        $terms      = get_the_terms(get_the_ID(), 'gallery_category');
                        $cat_slug   = ($terms && ! is_wp_error($terms)) ? $terms[0]->slug : '';
                        $is_large   = get_post_meta(get_the_ID(), '_gallery_featured', true) === '1';
                        $large_cls  = $is_large ? ' large' : '';
                ?>
                        <div class="gallery-item<?php echo $large_cls; ?>"
                             data-category="<?php echo esc_attr($cat_slug); ?>"
                             data-featured="<?php echo $is_large ? '1' : '0'; ?>"
                             data-lightbox-index="<?php echo $idx; ?>"
                             role="button"
                             tabindex="0"
                             aria-label="Buka preview: <?php echo esc_attr($caption); ?>">
                            <?php if ($thumb_src) : ?>
                                <img src="<?php echo esc_url($thumb_src); ?>"
                                     alt="<?php echo esc_attr($caption); ?>"
                                     loading="lazy">
                            <?php else : ?>
                                <div style="background:var(--gray-200);width:100%;min-height:200px;"></div>
                            <?php endif; ?>
                            <div class="gallery-overlay">
                                <i class="fas fa-expand-alt"></i>
                                <span><?php echo esc_html($caption); ?></span>
                            </div>
                        </div>
                <?php   $idx++;
                    endwhile;
                    wp_reset_postdata();
                else : ?>
                    <!-- Empty state: no gallery items in WP Admin yet -->
                    <div class="gallery-empty-state" style="grid-column:1/-1;padding:3rem 1rem;text-align:center;color:var(--gray-400);">
                        <i class="fas fa-images" style="font-size:3rem;display:block;margin-bottom:1rem;color:var(--gray-300);"></i>
                        <p style="font-size:0.95rem;">Belum ada foto. Tambahkan melalui <strong>Galeri</strong> di WordPress Admin.</p>
                    </div>
                <?php endif; ?>
            </div>

            <div class="text-center" style="margin-top: 3rem;">
                <a href="<?php echo esc_url($gallery_page_url); ?>" class="btn btn-primary">
                    <i class="fas fa-images"></i> Lihat Semua Galeri
                </a>
            </div>
        </div>
    </section>

    <?php if (! empty($lightbox_items)) : ?>
    <script>
        window.homeGalleryItems = <?php echo json_encode($lightbox_items, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>;
    </script>
    <?php endif; ?>

    <script>
    (function () {
        var HOME_GAL_LIMIT = <?php echo (int) $gallery_limit; ?>;

        function applyGalleryFilter(filter) {
            var grid  = document.getElementById('homeGalleryGrid');
            if (!grid) return;
            var items = Array.prototype.slice.call(grid.querySelectorAll('.gallery-item'));
            var shown = 0;

            items.forEach(function (item) {
                var cat   = item.getAttribute('data-category') || '';
                var match = (filter === 'all' || cat === filter);
                if (match && shown < HOME_GAL_LIMIT) {
                    item.classList.remove('gal-hidden');
                    shown++;
                } else {
                    item.classList.add('gal-hidden');
                }
            });
        }

        function initHomeGallery() {
            // Apply initial "Semua" limit
            applyGalleryFilter('all');

            var btns = document.querySelectorAll('.gallery-filter .filter-btn');
            btns.forEach(function (btn) {
                btn.addEventListener('click', function () {
                    btns.forEach(function (b) { b.classList.remove('active'); });
                    this.classList.add('active');
                    applyGalleryFilter(this.getAttribute('data-filter'));
                });
            });
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initHomeGallery);
        } else {
            initHomeGallery();
        }
    })();
    </script>

    <!-- ===================== ALUMNI SLIDER SECTION ===================== -->
    <?php
    $uni_logo_w = max(80, min(260, intval(get_option('sman1_uni_logo_width', 140))));
    $uni_logo_h = max(40, min(180, intval(get_option('sman1_uni_logo_height', 80))));

    $universities_query = new WP_Query([
        'post_type'      => 'accepted_university',
        'posts_per_page' => -1,
        'post_status'    => 'publish',
        'orderby'        => [
            'menu_order' => 'ASC',
            'title'      => 'ASC',
        ],
    ]);
    ?>
    <style>
        .alumni-section {
            --uni-logo-w: <?php echo esc_attr($uni_logo_w); ?>px;
            --uni-logo-h: <?php echo esc_attr($uni_logo_h); ?>px;
        }
    </style>
    <section class="alumni-section section" id="alumni">
        <div class="container">
            <div class="alumni-header" data-aos="fade-up">
                <div class="section-subtitle"><i class="fas fa-user-graduate"></i><span>Jejak Alumni</span></div>
                <h2 class="section-title">Diterima di <span class="text-accent">Universitas Terbaik</span></h2>
                <p>Lulusan kami melanjutkan pendidikan di berbagai perguruan tinggi terkemuka di Indonesia dan dunia</p>
            </div>
            <div class="alumni-slider-container">
                <div class="alumni-track">
                    <?php if ($universities_query->have_posts()) : ?>
                        <?php
                        $uni_items = [];
                        while ($universities_query->have_posts()) :
                            $universities_query->the_post();
                            $logo_url   = get_the_post_thumbnail_url(get_the_ID(), 'medium');
                            $name       = get_the_title();
                            $short_name = get_post_meta(get_the_ID(), '_uni_short_name', true) ?: $name;
                            $uni_url    = get_post_meta(get_the_ID(), '_uni_url', true);
                            if (!$logo_url) {
                                continue;
                            }
                            $uni_items[] = [
                                'logo'  => $logo_url,
                                'name'  => $name,
                                'short' => $short_name,
                                'url'   => $uni_url,
                            ];
                        endwhile;
                        wp_reset_postdata();
                        ?>

                        <?php if (!empty($uni_items)) : ?>
                            <?php for ($loop = 0; $loop < 2; $loop++) : ?>
                                <?php foreach ($uni_items as $uni) : ?>
                                    <div class="uni-item">
                                        <?php if (!empty($uni['url'])) : ?>
                                            <a href="<?php echo esc_url($uni['url']); ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php echo esc_attr($uni['name']); ?>">
                                                <img src="<?php echo esc_url($uni['logo']); ?>" alt="<?php echo esc_attr($uni['name']); ?>" class="uni-logo" loading="lazy">
                                            </a>
                                        <?php else : ?>
                                            <img src="<?php echo esc_url($uni['logo']); ?>" alt="<?php echo esc_attr($uni['name']); ?>" class="uni-logo" loading="lazy">
                                        <?php endif; ?>
                                        <span class="uni-name"><?php echo esc_html($uni['short']); ?></span>
                                    </div>
                                <?php endforeach; ?>
                            <?php endfor; ?>
                        <?php else : ?>
                            <div class="uni-item" style="opacity:1;filter:none;cursor:default;">
                                <div class="uni-logo" style="display:flex;align-items:center;justify-content:center;border:1px dashed var(--gray-300);background:#fff;border-radius:var(--radius-md);color:var(--gray-400);">No Logo</div>
                                <span class="uni-name" style="opacity:1;transform:none;">Belum ada logo universitas</span>
                            </div>
                        <?php endif; ?>
                    <?php else : ?>
                        <div class="uni-item" style="opacity:1;filter:none;cursor:default;">
                            <div class="uni-logo" style="display:flex;align-items:center;justify-content:center;border:1px dashed var(--gray-300);background:#fff;border-radius:var(--radius-md);color:var(--gray-400);">No Logo</div>
                            <span class="uni-name" style="opacity:1;transform:none;">Belum ada logo universitas</span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- ===================== SPMB SECTION ===================== -->
    <?php
    // ── Read SPMB options ─────────────────────────────────────────────────
    $spmb_year         = get_option( 'sman1_spmb_year', '2026/2027' );
    $spmb_target       = get_option( 'sman1_spmb_target_date', '' );
    $spmb_reg_start    = get_option( 'sman1_spmb_reg_start', '' );
    $spmb_reg_end      = get_option( 'sman1_spmb_reg_end', '' );
    $spmb_url          = get_option( 'sman1_spmb_url', '#' );
    $spmb_guide_url    = get_option( 'sman1_spmb_guide_url', '#' );
    $spmb_quota        = get_option( 'sman1_spmb_quota', '' );
    $spmb_status_open  = get_option( 'sman1_spmb_status_open', 'Pendaftaran Dibuka' );
    $spmb_status_closed= get_option( 'sman1_spmb_status_closed', 'Pendaftaran Ditutup' );
    $spmb_feats_raw    = get_option( 'sman1_spmb_features',
        "Proses pendaftaran 100% online\nSeleksi transparan dan akuntabel\nBeasiswa dan program unggulan tersedia\nKonfirmasi pendaftaran via WhatsApp"
    );

    // ── Determine status ──────────────────────────────────────────────────
    $today         = current_time( 'Y-m-d' );
    $is_after_end  = $spmb_reg_end   && $today > $spmb_reg_end;
    $is_before_start = $spmb_reg_start && $today < $spmb_reg_start;

    if ( $is_after_end ) {
        $spmb_badge_class = 'spmb-badge-closed';
        $spmb_badge_label = $spmb_status_closed;
        $spmb_badge_icon  = 'fas fa-times-circle';
        $spmb_btn_disabled = true;
    } elseif ( $is_before_start ) {
        $spmb_badge_class = 'spmb-badge-soon';
        $spmb_badge_label = 'Segera Dibuka';
        $spmb_badge_icon  = 'fas fa-clock';
        $spmb_btn_disabled = true;
    } else {
        $spmb_badge_class = 'spmb-badge-open';
        $spmb_badge_label = $spmb_status_open;
        $spmb_badge_icon  = 'fas fa-check-circle';
        $spmb_btn_disabled = false;
    }

    $spmb_features = array_filter( array_map( 'trim', explode( "\n", $spmb_feats_raw ) ) );

    // ── Countdown target timestamp (ms) for JS ────────────────────────────
    $spmb_target_ms = $spmb_target ? strtotime( $spmb_target ) * 1000 : 0;
    ?>

    <section class="spmb-section" id="spmb">

        <!-- Layered background -->
        <div class="spmb-bg" aria-hidden="true"></div>
        <div class="spmb-shapes" aria-hidden="true">
            <span class="spmb-shape spmb-shape-1"></span>
            <span class="spmb-shape spmb-shape-2"></span>
            <span class="spmb-shape spmb-shape-3"></span>
        </div>

        <div class="container spmb-inner">

            <!-- ── LEFT: Text + Features + Buttons ── -->
            <div class="spmb-content" data-aos="fade-right">

                <span class="spmb-badge <?php echo esc_attr( $spmb_badge_class ); ?>">
                    <i class="<?php echo esc_attr( $spmb_badge_icon ); ?>"></i>
                    <?php echo esc_html( $spmb_badge_label ); ?>
                </span>

                <h2 class="spmb-title">
                    Siap Menjadi Bagian dari<br>
                    <span>Keluarga Besar SMANSA?</span>
                </h2>

                <p class="spmb-desc">
                    Sistem Penerimaan Murid Baru (SPMB) Tahun Ajaran
                    <strong><?php echo esc_html( $spmb_year ); ?></strong>
                    segera dibuka. Wujudkan impianmu bersama kami!
                </p>

                <?php if ( ! empty( $spmb_features ) ) : ?>
                <ul class="spmb-features">
                    <?php foreach ( $spmb_features as $feat ) : ?>
                    <li>
                        <span class="spmb-feat-icon" aria-hidden="true"><i class="fas fa-check"></i></span>
                        <?php echo esc_html( $feat ); ?>
                    </li>
                    <?php endforeach; ?>
                </ul>
                <?php endif; ?>

                <div class="spmb-buttons">
                    <a href="<?php echo esc_url( $spmb_url ?: '#' ); ?>"
                       class="btn btn-lg spmb-btn-primary<?php echo $spmb_btn_disabled ? ' spmb-btn-disabled' : ''; ?>"
                       <?php if ( $spmb_btn_disabled ) echo 'aria-disabled="true" tabindex="-1"'; ?>
                       <?php if ( ! $spmb_btn_disabled && $spmb_url && $spmb_url !== '#' ) echo 'target="_blank" rel="noopener"'; ?>>
                        <i class="fas fa-user-plus"></i>
                        Daftar SPMB Online
                    </a>
                    <a href="<?php echo esc_url( $spmb_guide_url ?: '#' ); ?>"
                       class="btn btn-lg spmb-btn-outline"
                       <?php if ( $spmb_guide_url && $spmb_guide_url !== '#' ) echo 'target="_blank" rel="noopener"'; ?>>
                        <i class="fas fa-file-download"></i>
                        Unduh Panduan
                    </a>
                </div>
            </div><!-- / .spmb-content -->

            <!-- ── RIGHT: Countdown Card ── -->
            <div class="spmb-countdown-wrap" data-aos="fade-left" data-aos-delay="150">
                <div class="spmb-countdown-card">

                    <!-- Decorative rings -->
                    <div class="spmb-card-ring spmb-ring-1" aria-hidden="true"></div>
                    <div class="spmb-card-ring spmb-ring-2" aria-hidden="true"></div>

                    <div class="spmb-countdown-header">
                        <i class="fas fa-hourglass-half"></i>
                        <span>Pendaftaran Dibuka Dalam</span>
                    </div>

                    <div class="spmb-timer" id="spmbCountdown">
                        <div class="spmb-timer-item">
                            <span class="spmb-timer-num" id="spmbDays">00</span>
                            <span class="spmb-timer-label">Hari</span>
                        </div>
                        <div class="spmb-timer-sep" aria-hidden="true">:</div>
                        <div class="spmb-timer-item">
                            <span class="spmb-timer-num" id="spmbHours">00</span>
                            <span class="spmb-timer-label">Jam</span>
                        </div>
                        <div class="spmb-timer-sep" aria-hidden="true">:</div>
                        <div class="spmb-timer-item">
                            <span class="spmb-timer-num" id="spmbMinutes">00</span>
                            <span class="spmb-timer-label">Menit</span>
                        </div>
                        <div class="spmb-timer-sep" aria-hidden="true">:</div>
                        <div class="spmb-timer-item">
                            <span class="spmb-timer-num" id="spmbSeconds">00</span>
                            <span class="spmb-timer-label">Detik</span>
                        </div>
                    </div><!-- / .spmb-timer -->

                    <div class="spmb-countdown-footer">
                        <?php if ( $spmb_reg_start ) : ?>
                        <div class="spmb-date-info">
                            <i class="fas fa-calendar-alt" aria-hidden="true"></i>
                            <span>
                                Buka: <?php echo date_i18n( 'd M Y', strtotime( $spmb_reg_start ) ); ?>
                                <?php if ( $spmb_reg_end ) : ?>
                                    &ndash; <?php echo date_i18n( 'd M Y', strtotime( $spmb_reg_end ) ); ?>
                                <?php endif; ?>
                            </span>
                        </div>
                        <?php endif; ?>
                        <?php if ( $spmb_quota ) : ?>
                        <div class="spmb-quota">
                            <i class="fas fa-users" aria-hidden="true"></i>
                            <span>Kuota: <strong><?php echo esc_html( $spmb_quota ); ?></strong> Siswa</span>
                        </div>
                        <?php endif; ?>
                    </div><!-- / .spmb-countdown-footer -->

                </div><!-- / .spmb-countdown-card -->
            </div><!-- / .spmb-countdown-wrap -->

        </div><!-- / .spmb-inner -->
    </section>

    <script>
    window.spmbConfig = {
        targetDate : <?php echo (int) $spmb_target_ms; ?>,
        isClosed   : <?php echo $is_after_end ? 'true' : 'false'; ?>
    };
    </script>

    <!-- ===================== GALLERY LIGHTBOX ===================== -->
    <div class="gallery-lightbox" id="galleryLightbox" role="dialog" aria-modal="true" aria-label="Preview Foto" style="display:none;">
        <div class="lightbox-backdrop" id="lightboxBackdrop"></div>
        <div class="lightbox-container" role="document">
            <!-- Close -->
            <button class="lightbox-close" id="lightboxClose" aria-label="Tutup preview">
                <i class="fas fa-times"></i>
            </button>
            <!-- Prev -->
            <button class="lightbox-nav lightbox-prev" id="lightboxPrev" aria-label="Foto sebelumnya">
                <i class="fas fa-chevron-left"></i>
            </button>
            <!-- Next -->
            <button class="lightbox-nav lightbox-next" id="lightboxNext" aria-label="Foto berikutnya">
                <i class="fas fa-chevron-right"></i>
            </button>
            <!-- Image -->
            <div class="lightbox-image-wrap">
                <div class="lightbox-spinner" id="lightboxSpinner">
                    <i class="fas fa-circle-notch fa-spin"></i>
                </div>
                <img src="" alt="" id="lightboxImg" class="lightbox-img">
            </div>
            <!-- Caption + Counter -->
            <div class="lightbox-footer">
                <span class="lightbox-caption" id="lightboxCaption"></span>
                <span class="lightbox-counter" id="lightboxCounter"></span>
            </div>
            <!-- Thumbnail strip -->
            <div class="lightbox-thumbs" id="lightboxThumbs"></div>
        </div>
    </div>

<?php get_footer(); ?>
