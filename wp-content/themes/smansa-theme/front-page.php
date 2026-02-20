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
            'badge_icon' => 'fas fa-school',
            'badge_text' => 'Website Resmi SMAN 1 Purwokerto',
            'title'      => get_theme_mod( 'hero_title', 'Mewujudkan Generasi Emas Berkarakter Pancasila' ),
            'subtitle'   => get_theme_mod( 'hero_subtitle', 'SMAN 1 Purwokerto berkomitmen mencetak lulusan unggul dalam prestasi, luhur dalam budi pekerti, dan siap bersaing di era global.' ),
            'bg_url'     => get_template_directory_uri() . '/images/hero-slide-1.svg',
            'btn1_label' => 'Jelajahi Profil',
            'btn1_url'   => '#profil',
            'btn1_icon'  => 'fas fa-arrow-right',
            'btn2_label' => 'Hubungi Kami',
            'btn2_url'   => '#kontak',
            'btn2_icon'  => 'fas fa-envelope',
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
            $slides[] = array(
                'badge_icon' => get_field( 'slide_badge_icon' ) ?: 'fas fa-school',
                'badge_text' => get_field( 'slide_badge_text' ) ?: 'Website Resmi SMAN 1 Purwokerto',
                'title'      => get_field( 'slide_title' )      ?: get_the_title(),
                'subtitle'   => get_field( 'slide_subtitle' )   ?: '',
                'bg_url'     => $bg_url,
                'btn1_label' => get_field( 'slide_btn1_label' ) ?: 'Jelajahi Profil',
                'btn1_url'   => get_field( 'slide_btn1_url' )   ?: '#profil',
                'btn1_icon'  => get_field( 'slide_btn1_icon' )  ?: 'fas fa-arrow-right',
                'btn2_label' => get_field( 'slide_btn2_label' ) ?: '',
                'btn2_url'   => get_field( 'slide_btn2_url' )   ?: '',
                'btn2_icon'  => get_field( 'slide_btn2_icon' )  ?: 'fas fa-envelope',
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
                        <h1><?php echo esc_html( $slide['title'] ); ?></h1>
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
    $about_badge_number   = get_field('about_badge_number')   ?: 'A';
    $about_badge_text     = get_field('about_badge_text')     ?: 'Akreditasi';
    $about_btn1_label     = get_field('about_btn1_label')     ?: 'Selengkapnya';
    $about_btn1_url       = get_field('about_btn1_url')       ?: '#profil';
    $about_btn1_icon      = get_field('about_btn1_icon')      ?: 'fas fa-arrow-right';
    $about_btn2_label     = get_field('about_btn2_label')     ?: 'Visi & Misi';
    $about_btn2_url       = get_field('about_btn2_url')       ?: '#visi-misi';
    $about_btn2_icon      = get_field('about_btn2_icon')      ?: '';

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
                        <?php if ( $about_video_url ) : ?>
                        <a class="about-image-overlay" href="<?php echo esc_url( $about_video_url ); ?>" target="_blank" rel="noopener">
                            <div class="play-btn"><i class="fas fa-play"></i></div>
                            <span>Video Profil</span>
                        </a>
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
            array( 'icon' => 'fas fa-user-plus',    'title' => 'PPDB Online',  'desc' => 'Pendaftaran siswa baru',       'url' => '#', 'highlight' => true  ),
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
            (object)array('ID'=>0,'post_title'=>'Gerakan Pembiasaan Anak Indonesia Sehat',   'sp_icon'=>'fas fa-seedling',       'sp_desc'=>'Menanamkan kebiasaan hidup sehat sejak dini melalui olahraga rutin, pola makan bergizi, dan kesehatan mental sebagai investasi terbaik untuk masa depan.','sp_url'=>'#','sp_featured'=>false),
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
    <section class="gallery-section section" id="gallery">
        <div class="container">
            <div class="section-header text-center" data-aos="fade-up">
                <div class="section-subtitle"><i class="fas fa-images"></i><span>Galeri</span></div>
                <h2 class="section-title">Momen <span class="text-accent">Berkesan</span></h2>
                <p>Dokumentasi kegiatan dan prestasi SMAN 1 Purwokerto</p>
            </div>
            <div class="gallery-filter" data-aos="fade-up">
                <button class="filter-btn active" data-filter="all">Semua</button>
                <button class="filter-btn" data-filter="academic">Akademik</button>
                <button class="filter-btn" data-filter="sports">Olahraga</button>
                <button class="filter-btn" data-filter="arts">Seni</button>
                <button class="filter-btn" data-filter="events">Kegiatan</button>
            </div>
            <div class="gallery-grid" data-aos="fade-up">
                <div class="gallery-item" data-category="academic">
                    <img src="https://images.unsplash.com/photo-1523580494863-6f3031224c94?w=400&h=300&fit=crop" alt="Gallery">
                    <div class="gallery-overlay"><i class="fas fa-search-plus"></i><span>Olimpiade Sains</span></div>
                </div>
                <div class="gallery-item large" data-category="events">
                    <img src="https://images.unsplash.com/photo-1541339907198-e08756dedf3f?w=600&h=400&fit=crop" alt="Gallery">
                    <div class="gallery-overlay"><i class="fas fa-search-plus"></i><span>Wisuda Angkatan 2025</span></div>
                </div>
                <div class="gallery-item" data-category="sports">
                    <img src="https://images.unsplash.com/photo-1574629810360-7efbbe195018?w=400&h=300&fit=crop" alt="Gallery">
                    <div class="gallery-overlay"><i class="fas fa-search-plus"></i><span>Turnamen Basket</span></div>
                </div>
                <div class="gallery-item" data-category="arts">
                    <img src="https://images.unsplash.com/photo-1514320291840-2e0a9bf2a9ae?w=400&h=300&fit=crop" alt="Gallery">
                    <div class="gallery-overlay"><i class="fas fa-search-plus"></i><span>Pentas Seni</span></div>
                </div>
                <div class="gallery-item" data-category="events">
                    <img src="https://images.unsplash.com/photo-1509062522246-3755977927d7?w=400&h=300&fit=crop" alt="Gallery">
                    <div class="gallery-overlay"><i class="fas fa-search-plus"></i><span>MPLS 2025</span></div>
                </div>
                <div class="gallery-item" data-category="academic">
                    <img src="https://images.unsplash.com/photo-1427504494785-3a9ca7044f45?w=400&h=300&fit=crop" alt="Gallery">
                    <div class="gallery-overlay"><i class="fas fa-search-plus"></i><span>Laboratorium</span></div>
                </div>
            </div>
            <div class="text-center" style="margin-top: 3rem;">
                <a href="#" class="btn btn-primary"><i class="fas fa-images"></i> Lihat Semua Galeri</a>
            </div>
        </div>
    </section>

    <!-- ===================== ALUMNI SLIDER SECTION ===================== -->
    <section class="alumni-section section" id="alumni">
        <div class="container">
            <div class="alumni-header" data-aos="fade-up">
                <div class="section-subtitle"><i class="fas fa-user-graduate"></i><span>Jejak Alumni</span></div>
                <h2 class="section-title">Diterima di <span class="text-accent">Universitas Terbaik</span></h2>
                <p>Lulusan kami melanjutkan pendidikan di berbagai perguruan tinggi terkemuka di Indonesia.</p>
            </div>
            <div class="alumni-slider-container">
                <div class="alumni-track">
                    <div class="uni-item"><img src="https://placehold.co/150x150/FFD700/000000?text=UI" alt="UI" class="uni-logo"><span class="uni-name">Univ. Indonesia</span></div>
                    <div class="uni-item"><img src="https://placehold.co/150x150/f3f4f6/1f2937?text=UGM" alt="UGM" class="uni-logo"><span class="uni-name">UGM</span></div>
                    <div class="uni-item"><img src="https://placehold.co/150x150/2563eb/ffffff?text=ITB" alt="ITB" class="uni-logo"><span class="uni-name">ITB</span></div>
                    <div class="uni-item"><img src="https://placehold.co/150x150/1e3a8a/ffffff?text=UNDIP" alt="UNDIP" class="uni-logo"><span class="uni-name">UNDIP</span></div>
                    <div class="uni-item"><img src="https://placehold.co/150x150/0ea5e9/ffffff?text=ITS" alt="ITS" class="uni-logo"><span class="uni-name">ITS</span></div>
                    <div class="uni-item"><img src="https://placehold.co/150x150/f59e0b/ffffff?text=UNAIR" alt="UNAIR" class="uni-logo"><span class="uni-name">UNAIR</span></div>
                    <div class="uni-item"><img src="https://placehold.co/150x150/3b82f6/ffffff?text=UNS" alt="UNS" class="uni-logo"><span class="uni-name">UNS</span></div>
                    <div class="uni-item"><img src="https://placehold.co/150x150/1d4ed8/ffffff?text=UB" alt="UB" class="uni-logo"><span class="uni-name">Brawijaya</span></div>
                    <div class="uni-item"><img src="https://placehold.co/150x150/eab308/000000?text=UNSOED" alt="UNSOED" class="uni-logo"><span class="uni-name">UNSOED</span></div>
                    <div class="uni-item"><img src="https://placehold.co/150x150/4338ca/ffffff?text=UNPAD" alt="UNPAD" class="uni-logo"><span class="uni-name">UNPAD</span></div>
                    <div class="uni-item"><img src="https://placehold.co/150x150/FFD700/000000?text=UI" alt="UI" class="uni-logo"><span class="uni-name">Univ. Indonesia</span></div>
                    <div class="uni-item"><img src="https://placehold.co/150x150/f3f4f6/1f2937?text=UGM" alt="UGM" class="uni-logo"><span class="uni-name">UGM</span></div>
                    <div class="uni-item"><img src="https://placehold.co/150x150/2563eb/ffffff?text=ITB" alt="ITB" class="uni-logo"><span class="uni-name">ITB</span></div>
                    <div class="uni-item"><img src="https://placehold.co/150x150/1e3a8a/ffffff?text=UNDIP" alt="UNDIP" class="uni-logo"><span class="uni-name">UNDIP</span></div>
                    <div class="uni-item"><img src="https://placehold.co/150x150/0ea5e9/ffffff?text=ITS" alt="ITS" class="uni-logo"><span class="uni-name">ITS</span></div>
                    <div class="uni-item"><img src="https://placehold.co/150x150/f59e0b/ffffff?text=UNAIR" alt="UNAIR" class="uni-logo"><span class="uni-name">UNAIR</span></div>
                    <div class="uni-item"><img src="https://placehold.co/150x150/3b82f6/ffffff?text=UNS" alt="UNS" class="uni-logo"><span class="uni-name">UNS</span></div>
                    <div class="uni-item"><img src="https://placehold.co/150x150/1d4ed8/ffffff?text=UB" alt="UB" class="uni-logo"><span class="uni-name">Brawijaya</span></div>
                    <div class="uni-item"><img src="https://placehold.co/150x150/eab308/000000?text=UNSOED" alt="UNSOED" class="uni-logo"><span class="uni-name">UNSOED</span></div>
                    <div class="uni-item"><img src="https://placehold.co/150x150/4338ca/ffffff?text=UNPAD" alt="UNPAD" class="uni-logo"><span class="uni-name">UNPAD</span></div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="cta-bg"></div>
        <div class="container cta-content">
            <h2 data-aos="fade-up">Siap Menjadi Bagian dari Keluarga Besar SMANSA?</h2>
            <p data-aos="fade-up" data-aos-delay="100">Penerimaan Peserta Didik Baru (PPDB) Tahun Ajaran 2026/2027 segera dibuka. Persiapkan dirimu sekarang!</p>
            
            <div class="cta-countdown" data-aos="fade-up" data-aos-delay="200">
                <span>Pendaftaran Dibuka Dalam:</span>
                <div class="countdown-timer" id="countdown">
                    <!-- JS Countdown -->
                </div>
            </div>
            
            <div class="cta-buttons" data-aos="fade-up" data-aos-delay="300">
                <a href="ppdb.html" class="btn btn-lg btn-accent">
                    <i class="fas fa-user-plus"></i> Daftar PPDB Online
                </a>
                <a href="panduan.html" class="btn btn-lg btn-outline-white">
                    <i class="fas fa-book"></i> Unduh Panduan
                </a>
            </div>
        </div>
    </section>

<?php get_footer(); ?>
