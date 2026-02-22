<?php

if (! function_exists('sman1_setup')):
    function sman1_setup() {
        // Add default posts and comments RSS feed links to head.
        add_theme_support('automatic-feed-links');

        // Let WordPress manage the document title.
        add_theme_support('title-tag');

        // Enable support for Post Thumbnails on posts and pages.
        add_theme_support('post-thumbnails');

        // Enable support for Custom Logo.
        add_theme_support('custom-logo', array(
            'height'      => 80,
            'width'       => 80,
            'flex-height' => true,
            'flex-width'  => true,
        ));

        // This theme uses wp_nav_menu() in one location.
        register_nav_menus(array(
            'primary_menu' => esc_html__('Primary Menu', 'sman1'),
            'footer_menu'  => esc_html__('Footer Menu', 'sman1'),
            'social_menu'  => esc_html__('Social Media Menu (Top Bar)', 'sman1'),
        ));

        // Switch default core markup for search form, comment form, and comments to output valid HTML5.
        add_theme_support('html5', array(
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
        ));
    }
endif;
add_action('after_setup_theme', 'sman1_setup');

/**
 * Enqueue scripts and styles.
 */
function sman1_scripts() {
    // Fonts
    wp_enqueue_style('sman1-fonts', 'https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Montserrat:wght@400;500;600;700;800&display=swap', array(), null);
    wp_enqueue_style('sman1-fontawesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css', array(), '6.4.0');

    // Libraries
    wp_enqueue_style('sman1-aos-css', 'https://unpkg.com/aos@2.3.1/dist/aos.css', array(), '2.3.1');
    wp_enqueue_style('sman1-swiper-css', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css', array(), '11.0.0');

    // Main Stylesheet
    wp_enqueue_style('sman1-style', get_template_directory_uri() . '/css/style.css', array(), '1.0.0');
    // WordPress Main Style (Standard)
    wp_enqueue_style('sman1-wp-style', get_stylesheet_uri());

    // Scripts
    wp_enqueue_script('sman1-aos-js', 'https://unpkg.com/aos@2.3.1/dist/aos.js', array(), '2.3.1', true);
    wp_enqueue_script('sman1-swiper-js', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js', array(), '11.0.0', true);
    
    // Main JS (depends on AOS and Swiper)
    wp_enqueue_script('sman1-main', get_template_directory_uri() . '/js/main.js', array('sman1-aos-js', 'sman1-swiper-js'), '1.0.0', true);

    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}
add_action('wp_enqueue_scripts', 'sman1_scripts');

/**
 * Browser tab title — format: "Situs Resmi SMAN 1 Purwokerto" on front page,
 * "{Judul Halaman} – SMAN 1 Purwokerto" on other pages.
 */
add_filter('document_title_parts', function ($title) {
    if (is_front_page()) {
        return array('title' => 'Situs Resmi SMAN 1 Purwokerto');
    }
    $title['site'] = 'SMAN 1 Purwokerto';
    unset($title['tagline']);
    return $title;
}, 20);

add_filter('document_title_separator', function () {
    return '–';
}, 20);

/**
 * Favicon — gunakan site icon dari Customizer jika ada,
 * fallback ke custom logo, fallback ke images/logo.png.
 */
add_action('wp_head', function () {
    // Jika site icon sudah diset di WP Customizer → WordPress otomatis output-nya
    if (function_exists('get_site_icon_url') && get_site_icon_url(32)) {
        return;
    }

    $favicon_url = '';

    // Coba pakai custom logo sebagai favicon
    $custom_logo_id = get_theme_mod('custom_logo');
    if ($custom_logo_id) {
        $logo_src = wp_get_attachment_image_src($custom_logo_id, 'thumbnail');
        if ($logo_src) {
            $favicon_url = $logo_src[0];
        }
    }

    // Final fallback: images/logo.png di dalam tema
    if (! $favicon_url) {
        $favicon_url = get_template_directory_uri() . '/images/logo.png';
    }

    echo '<link rel="icon" type="image/png" href="' . esc_url($favicon_url) . '">' . "\n";
    echo '<link rel="shortcut icon" href="' . esc_url($favicon_url) . '">' . "\n";
    echo '<link rel="apple-touch-icon" href="' . esc_url($favicon_url) . '">' . "\n";
}, 2); // priority 2 agar muncul sebelum tag lain dari wp_head

/**
 * Judul tab berjalan (marquee) jika teks > 30 karakter.
 * Teks bergeser dari kanan ke kiri di tab browser.
 */
add_action('wp_footer', function () {
    ?>
    <script>
    (function () {
        var raw   = document.title;
        var limit = 30;
        if (raw.length <= limit) return;           // cukup pendek, tidak perlu scroll

        var padded = raw + '\u00a0\u00a0\u00a0\u2022\u00a0\u00a0\u00a0'; // spasi + bullet
        var len    = padded.length;
        var pos    = 0;

        setInterval(function () {
            document.title = padded.slice(pos) + padded.slice(0, pos);
            pos = (pos + 1) % len;
        }, 200);
    })();
    </script>
    <?php
}, 99);

/* ======================================================
 * CUSTOM LOGIN PAGE — SMAN 1 Purwokerto
 * Modern redesign of wp-login.php
 * ====================================================== */

/**
 * 1. Enqueue custom login stylesheet + Font Awesome.
 */
add_action('login_enqueue_scripts', function () {
    wp_enqueue_style(
        'sman1-login',
        get_template_directory_uri() . '/css/login.css',
        array('login'), // Make sure it loads AFTER default login CSS
        filemtime(get_template_directory() . '/css/login.css')
    );
    // Font Awesome for icons
    wp_enqueue_style(
        'sman1-login-fa',
        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css',
        array(),
        '6.4.0'
    );
});

/**
 * 2. Logo link → homepage (instead of wordpress.org).
 */
add_filter('login_headerurl', function () {
    return home_url('/');
});

/**
 * 3. Logo title → site name.
 */
add_filter('login_headertext', function () {
    return get_bloginfo('name');
});

/**
 * 4. Inject school name badge + form header + footer copyright
 *    directly into the login page via login_message / login_footer actions.
 */
add_filter('login_message', function ($message) {
    $custom = '<span class="lg-school-name">SMAN 1 Purwokerto</span><span class="lg-school-sub">Portal Administrator Sekolah</span>';
    return $custom . $message;
});

add_action('login_footer', function () {
    ?>
    <p class="login-footer-copy">
        &copy; <?php echo date('Y'); ?> SMA Negeri 1 Purwokerto &mdash; Hak cipta dilindungi undang-undang.
    </p>
    <script>
    (function() {
        // Inject real <span> icons into the input fields so browser autofill
        // cannot override them (background-image on input gets wiped by autofill).
        function injectIcon(inputId, svgHtml) {
            var input = document.getElementById(inputId);
            if (!input) return;

            // For password, .wp-pwd wraps only the input — works perfectly.
            // For username, the parent <p> also contains the label, so top:50%
            // would land at the label. We wrap just the input in a relative div.
            var wpPwd = input.closest('.wp-pwd');
            var wrapper;
            if (wpPwd) {
                wrapper = wpPwd;
            } else {
                // Avoid double-wrapping
                if (input.parentNode.classList.contains('lg-input-wrap')) {
                    wrapper = input.parentNode;
                } else {
                    var div = document.createElement('div');
                    div.className = 'lg-input-wrap';
                    input.parentNode.insertBefore(div, input);
                    div.appendChild(input);
                    wrapper = div;
                }
            }

            // Avoid double icon injection
            if (wrapper.querySelector('.lg-field-icon')) return;

            var span = document.createElement('span');
            span.className = 'lg-field-icon';
            span.setAttribute('aria-hidden', 'true');
            span.innerHTML = svgHtml;
            wrapper.insertBefore(span, input);
        }

        var svgUser = '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="8" r="4" stroke="currentColor" stroke-width="1.7"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/></svg>';
        var svgLock = '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"><rect x="5" y="11" width="14" height="10" rx="2.5" stroke="currentColor" stroke-width="1.7"/><path d="M8 11V8a4 4 0 0 1 8 0v3" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/><circle cx="12" cy="16" r="1.5" fill="currentColor"/></svg>';

        document.addEventListener('DOMContentLoaded', function() {
            injectIcon('user_login', svgUser);
            injectIcon('user_pass', svgLock);
        });
    })();
    </script>
    <?php
});

/**
 * 5. Add 'sman1-login' class to body for specificity.
 */
add_filter('login_body_class', function ($classes) {
    $classes[] = 'sman1-login';
    return $classes;
});

/**
 * 6. Override login page title in <title> tag.
 */
add_filter('login_title', function ($login_title) {
    return 'Login Administrator &mdash; SMAN 1 Purwokerto';
});

/**
 * Customizer Settings
 * Allows user to edit Hero and Footer content.
 */
function sman1_customize_register($wp_customize) {
    // Hero Section Panel
    $wp_customize->add_section('hero_section', array(
        'title'    => __('Hero Section', 'sman1'),
        'priority' => 30,
    ));

    // Hero Title
    $wp_customize->add_setting('hero_title', array('default' => 'Mewujudkan Generasi Emas Berkarakter Pancasila', 'transport' => 'refresh'));
    $wp_customize->add_control('hero_title', array('label' => __('Hero Title', 'sman1'), 'section' => 'hero_section', 'type' => 'text'));

    // Hero Subtitle
    $wp_customize->add_setting('hero_subtitle', array('default' => 'SMAN 1 Purwokerto berkomitmen mencetak lulusan unggul dalam prestasi, luhur dalam budi pekerti, dan siap bersaing di era global.', 'transport' => 'refresh'));
    $wp_customize->add_control('hero_subtitle', array('label' => __('Hero Subtitle', 'sman1'), 'section' => 'hero_section', 'type' => 'textarea'));

    // Hero Stats
    $wp_customize->add_setting('stats_siswa', array('default' => '1200+', 'transport' => 'postMessage'));
    $wp_customize->add_control('stats_siswa', array('label' => __('Jumlah Siswa', 'sman1'), 'section' => 'hero_section', 'type' => 'text'));
    
    $wp_customize->add_setting('stats_guru', array('default' => '85+', 'transport' => 'postMessage'));
    $wp_customize->add_control('stats_guru', array('label' => __('Jumlah Guru', 'sman1'), 'section' => 'hero_section', 'type' => 'text'));
    
    $wp_customize->add_setting('stats_prestasi', array('default' => '150+', 'transport' => 'postMessage'));
    $wp_customize->add_control('stats_prestasi', array('label' => __('Jumlah Prestasi', 'sman1'), 'section' => 'hero_section', 'type' => 'text'));

    // Footer Section
    $wp_customize->add_section('footer_section', array(
        'title'    => __('Footer Settings', 'sman1'),
        'priority' => 40,
    ));

    $wp_customize->add_setting('footer_about_text', array('default' => 'Mencetak generasi takwa, unggul, berbudaya, sehat dan berwawasan lingkungan.', 'transport' => 'refresh'));
    $wp_customize->add_control('footer_about_text', array('label' => __('Footer About Text', 'sman1'), 'section' => 'footer_section', 'type' => 'textarea'));

    // Contact Info Section (Top Bar + Halaman Kontak)
    $wp_customize->add_section('contact_section', array(
        'title'    => __('Contact Info', 'sman1'),
        'priority' => 25,
    ));

    $wp_customize->add_setting('contact_phone', array('default' => '(0281) 636293', 'transport' => 'refresh'));
    $wp_customize->add_control('contact_phone', array('label' => __('Nomor Telepon', 'sman1'), 'section' => 'contact_section', 'type' => 'text'));

    $wp_customize->add_setting('contact_email', array('default' => 'smansa_pwt@yahoo.co.id', 'transport' => 'refresh'));
    $wp_customize->add_control('contact_email', array('label' => __('Alamat Email', 'sman1'), 'section' => 'contact_section', 'type' => 'text'));

    $wp_customize->add_setting('contact_address', array('default' => 'Jl. Jend. Gatot Subroto No.73, Kranji, Purwokerto Timur, Banyumas 53116', 'transport' => 'refresh'));
    $wp_customize->add_control('contact_address', array('label' => __('Alamat Lengkap', 'sman1'), 'section' => 'contact_section', 'type' => 'textarea'));

    $wp_customize->add_setting('contact_hours', array('default' => 'Senin – Jumat: 07.00 – 15.00', 'transport' => 'refresh'));
    $wp_customize->add_control('contact_hours', array('label' => __('Jam Operasional', 'sman1'), 'section' => 'contact_section', 'type' => 'text'));

    $wp_customize->add_setting('contact_maps_embed', array('default' => '', 'transport' => 'refresh'));
    $wp_customize->add_control('contact_maps_embed', array(
        'label'       => __('Google Maps Embed', 'sman1'),
        'description' => __('Boleh paste URL saja (dari src="...") ATAU tempel seluruh kode <iframe ...> dari Google Maps — keduanya otomatis dikenali.', 'sman1'),
        'section'     => 'contact_section',
        'type'        => 'textarea',
    ));

    $wp_customize->add_setting('contact_whatsapp', array('default' => '62281636293', 'transport' => 'refresh'));
    $wp_customize->add_control('contact_whatsapp', array(
        'label'       => __('Nomor WhatsApp', 'sman1'),
        'description' => __('Angka saja, tanpa + atau spasi. Contoh: 6281234567890 (kode negara + nomor).', 'sman1'),
        'section'     => 'contact_section',
        'type'        => 'text',
    ));

    // ── Portal Button Section ─────────────────────────────────────
    $wp_customize->add_section('portal_section', array(
        'title'       => __('🔐 Tombol Portal (Navbar)', 'sman1'),
        'description' => __('Atur teks dan URL tombol "Portal" di pojok kanan navbar.', 'sman1'),
        'priority'    => 26,
    ));

    $wp_customize->add_setting('portal_btn_label', array('default' => 'Portal', 'transport' => 'refresh'));
    $wp_customize->add_control('portal_btn_label', array(
        'label'       => __('Teks Tombol', 'sman1'),
        'description' => __('Contoh: Portal, Login, E-Learning', 'sman1'),
        'section'     => 'portal_section',
        'type'        => 'text',
    ));

    $wp_customize->add_setting('portal_btn_url', array('default' => '', 'transport' => 'refresh'));
    $wp_customize->add_control('portal_btn_url', array(
        'label'       => __('URL Tujuan', 'sman1'),
        'description' => __('Tempel URL lengkap (https://...) atau pilih halaman. Kosongkan untuk menonaktifkan.', 'sman1'),
        'section'     => 'portal_section',
        'type'        => 'url',
    ));

    $wp_customize->add_setting('portal_btn_new_tab', array('default' => '1', 'transport' => 'refresh'));
    $wp_customize->add_control('portal_btn_new_tab', array(
        'label'   => __('Buka di tab baru', 'sman1'),
        'section' => 'portal_section',
        'type'    => 'checkbox',
    ));

    $wp_customize->add_setting('portal_btn_icon', array('default' => 'fas fa-sign-in-alt', 'transport' => 'refresh'));
    $wp_customize->add_control('portal_btn_icon', array(
        'label'       => __('Icon (Font Awesome class)', 'sman1'),
        'description' => __('Contoh: fas fa-sign-in-alt &nbsp;|&nbsp; fas fa-user-circle &nbsp;|&nbsp; fas fa-graduation-cap', 'sman1'),
        'section'     => 'portal_section',
        'type'        => 'text',
    ));

    // Page picker: list all published pages as dropdown
    $portal_page_choices = array('' => '— Pilih Halaman (opsional) —');
    $all_pages = get_pages(array('post_status' => 'publish', 'sort_column' => 'post_title'));
    foreach ($all_pages as $pg) {
        $portal_page_choices[$pg->ID] = $pg->post_title;
    }
    $wp_customize->add_setting('portal_btn_page_id', array('default' => '', 'transport' => 'refresh'));
    $wp_customize->add_control('portal_btn_page_id', array(
        'label'       => __('Atau Pilih Halaman WordPress', 'sman1'),
        'description' => __('Jika diisi, URL di atas akan diabaikan dan tombol mengarah ke halaman ini.', 'sman1'),
        'section'     => 'portal_section',
        'type'        => 'select',
        'choices'     => $portal_page_choices,
    ));


    // Preloader Section
    $wp_customize->add_section('preloader_section', array(
        'title'    => __('Preloader Settings', 'sman1'),
        'priority' => 20,
    ));

    $wp_customize->add_setting('preloader_tagline', array('default' => get_bloginfo('description'), 'transport' => 'refresh'));
    $wp_customize->add_control('preloader_tagline', array('label' => __('Preloader Tagline', 'sman1'), 'section' => 'preloader_section', 'type' => 'textarea'));
}
add_action('customize_register', 'sman1_customize_register');

/**
 * Helper: Get FontAwesome Icon from URL
 */
function sman1_get_social_icon($url) {
    if (strpos($url, 'facebook') !== false) return 'fab fa-facebook-f';
    if (strpos($url, 'instagram') !== false) return 'fab fa-instagram';
    if (strpos($url, 'twitter') !== false || strpos($url, 'x.com') !== false) return 'fab fa-twitter';
    if (strpos($url, 'youtube') !== false) return 'fab fa-youtube';
    if (strpos($url, 'linkedin') !== false) return 'fab fa-linkedin-in';
    if (strpos($url, 'tiktok') !== false) return 'fab fa-tiktok';
    if (strpos($url, 'whatsapp') !== false || strpos($url, 'wa.me') !== false) return 'fab fa-whatsapp';
    if (strpos($url, 'telegram') !== false || strpos($url, 't.me') !== false) return 'fab fa-telegram';
    return 'fas fa-link'; // Default
}

/**
 * Helper: Derive platform display label from URL
 * Used as fallback when menu item title is blank or generic.
 */
function sman1_get_social_label( $url ) {
    if ( strpos($url, 'facebook')  !== false ) return 'Facebook';
    if ( strpos($url, 'instagram') !== false ) return 'Instagram';
    if ( strpos($url, 'twitter')   !== false ) return 'Twitter';
    if ( strpos($url, 'x.com')     !== false ) return 'X (Twitter)';
    if ( strpos($url, 'youtube')   !== false ) return 'YouTube';
    if ( strpos($url, 'linkedin')  !== false ) return 'LinkedIn';
    if ( strpos($url, 'tiktok')    !== false ) return 'TikTok';
    if ( strpos($url, 'wa.me')     !== false ||
         strpos($url, 'whatsapp')  !== false ) return 'WhatsApp';
    if ( strpos($url, 'telegram')  !== false ||
         strpos($url, 't.me')      !== false ) return 'Telegram';
    if ( strpos($url, 'spotify')   !== false ) return 'Spotify';
    if ( strpos($url, 'pinterest') !== false ) return 'Pinterest';
    return 'Website';
}

/**
 * ─────────────────────────────────────────────────────────
 * POST VIEW COUNTER
 * Tracks non-admin page views via post meta '_sman1_post_views'.
 * ─────────────────────────────────────────────────────────
 */
function sman1_track_post_views( $post_id ) {
    if ( ! $post_id || is_feed() || is_trackback() ) return;
    if ( current_user_can( 'manage_options' ) ) return; // skip admin views
    $count = (int) get_post_meta( $post_id, '_sman1_post_views', true );
    update_post_meta( $post_id, '_sman1_post_views', $count + 1 );
}

/**
 * Get post views formatted (e.g. 1.2rb, 3.5jt)
 */
function sman1_get_post_views( $post_id ) {
    $count = (int) get_post_meta( $post_id, '_sman1_post_views', true );
    if ( $count >= 1000000 ) return number_format( $count / 1000000, 1 ) . 'jt';
    if ( $count >= 1000    ) return number_format( $count / 1000   , 1 ) . 'rb';
    return number_format( $count );
}

/**
 * ─────────────────────────────────────────────────────────
 * AUTO TABLE OF CONTENTS GENERATOR
 * Parses all <h2> and <h3> tags from post content, injects
 * unique IDs into the content, and returns structured data:
 *   ['toc_html' => string, 'modified_content' => string, 'items' => array]
 * If fewer than 2 headings are found, 'items' is empty (no TOC rendered).
 * ─────────────────────────────────────────────────────────
 */
function sman1_generate_toc( $content ) {
    $empty = array( 'toc_html' => '', 'modified_content' => $content, 'items' => array() );
    if ( empty( $content ) ) return $empty;

    // Match every <h2> or <h3> (single line & case-insensitive)
    preg_match_all( '/<(h[23])([^>]*)>(.*?)<\/h[23]>/is', $content, $raw_matches, PREG_SET_ORDER );

    if ( count( $raw_matches ) < 2 ) return $empty; // need at least 2 headings

    $items      = array();
    $slug_seen  = array();
    $modified   = $content;
    $offset_adj = 0; // cumulative byte offset shift from replacements

    foreach ( $raw_matches as $m ) {
        $full   = $m[0];
        $tag    = strtolower( $m[1] );
        $attrs  = $m[2];
        $inner  = $m[3]; // may contain sub-tags like <strong>

        $plain  = wp_strip_all_tags( $inner );
        $base   = sanitize_title( $plain ) ?: ( $tag . '-heading' );

        // Deduplicate slug
        $slug = $base;
        if ( isset( $slug_seen[ $base ] ) ) {
            $slug_seen[ $base ]++;
            $slug = $base . '-' . $slug_seen[ $base ];
        } else {
            $slug_seen[ $base ] = 0;
        }

        $items[] = array( 'tag' => $tag, 'id' => $slug, 'text' => $plain );

        // Strip any existing id="" and inject the new one
        $clean_attrs = preg_replace( '/\s*id=["\'][^"\']*["\']/', '', $attrs );
        $replacement = '<' . $tag . ' id="' . esc_attr( $slug ) . '"' . $clean_attrs . '>' . $inner . '</' . $tag . '>';

        $pos      = strpos( $modified, $full );
        if ( $pos !== false ) {
            $modified = substr_replace( $modified, $replacement, $pos, strlen( $full ) );
        }
    }

    // Build TOC HTML
    $toc = '<ol class="spost-toc-list">';
    foreach ( $items as $item ) {
        $cls  = ( $item['tag'] === 'h3' ) ? ' class="spost-toc-sub"' : '';
        $toc .= '<li' . $cls . '>'
              . '<a href="#' . esc_attr( $item['id'] ) . '">'
              . esc_html( $item['text'] )
              . '</a></li>';
    }
    $toc .= '</ol>';

    return array(
        'toc_html'         => $toc,
        'modified_content' => $modified,
        'items'            => $items,
    );
}

/**
 * Format date in Bahasa Indonesia
 * Returns e.g. "20 Februari 2026"
 */
function sman1_indo_date( $timestamp = null ) {
    static $months = array(
        1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',
        7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember',
    );
    if ( ! $timestamp ) $timestamp = time();
    return (int) date( 'j', $timestamp ) . ' ' . $months[ (int) date( 'n', $timestamp ) ] . ' ' . date( 'Y', $timestamp );
}

/**
 * Convert a YouTube or Vimeo watch/share URL to its embed URL.
 * Supports:
 *   https://www.youtube.com/watch?v=ID
 *   https://youtu.be/ID
 *   https://vimeo.com/ID  or  https://vimeo.com/video/ID
 * Returns the URL unchanged for any other value.
 */
function sman1_video_embed_url( $url ) {
    if ( ! $url ) return '';
    // Already an embed URL — return as-is
    if ( strpos( $url, 'youtube.com/embed/' ) !== false || strpos( $url, 'player.vimeo.com' ) !== false ) {
        return $url;
    }
    // YouTube watch?v= or youtu.be/
    if ( preg_match( '/(?:youtube\.com\/watch\?(?:.*&)?v=|youtu\.be\/)([a-zA-Z0-9_\-]{11})/', $url, $m ) ) {
        return 'https://www.youtube.com/embed/' . $m[1];
    }
    // Vimeo
    if ( preg_match( '/vimeo\.com\/(?:video\/)?(\d+)/', $url, $m ) ) {
        return 'https://player.vimeo.com/video/' . $m[1];
    }
    return $url;
}

/**
 * Register Widget Area (Footer)
 */
function sman1_widgets_init() {
    register_sidebar(array(
        'name'          => esc_html__('Footer Area', 'sman1'),
        'id'            => 'footer-1',
        'description'   => esc_html__('Add widgets here. (Layout: Digital Services)', 'sman1'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ));
}
add_action('widgets_init', 'sman1_widgets_init');

// Include Navigation Walker
require_once get_template_directory() . '/class-sman1-nav-walker.php';

// Include Hero Slide setup (CPT + ACF)
require_once get_template_directory() . '/hero-slide-setup.php';
require_once get_template_directory() . '/quick-access-setup.php';
require_once get_template_directory() . '/program-setup.php';
require_once get_template_directory() . '/achievement-setup.php';
require_once get_template_directory() . '/testimonial-setup.php';

// Include Tentang Kami section ACF fields
require_once get_template_directory() . '/about-section-setup.php';

// Include Gallery CPT, Taxonomy, Meta Boxes & Admin UI
require_once get_template_directory() . '/gallery-setup.php';

// Include University logos setup (CPT + admin settings)
require_once get_template_directory() . '/university-setup.php';

// Include SPMB (Sistem Penerimaan Murid Baru) admin settings & countdown
require_once get_template_directory() . '/spmb-setup.php';

// Include Collaboration/Partner links CPT
require_once get_template_directory() . '/collaboration-setup.php';

// Include Guru & Staf CPT (school_staff) setup
require_once get_template_directory() . '/staff-setup.php';


/* ==========================================================================
   SECURITY HARDENING
   ========================================================================== */

// 1. Remove WordPress version from <head>, RSS, and generator meta
remove_action('wp_head', 'wp_generator');
add_filter('the_generator', '__return_empty_string');

// 2. Remove version strings from enqueued CSS & JS URLs (fingerprinting prevention)
add_filter('style_loader_src', 'sman1_remove_ver_css_js', 9999);
add_filter('script_loader_src', 'sman1_remove_ver_css_js', 9999);
function sman1_remove_ver_css_js($src) {
    if (strpos($src, '?ver=') !== false) {
        $src = remove_query_arg('ver', $src);
    }
    return $src;
}

// 3. Disable XML-RPC completely (prevents brute-force & DDoS via pingbacks)
add_filter('xmlrpc_enabled', '__return_false');
add_filter('pings_open', '__return_false', 9999);
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wlwmanifest_link');

// 4. Disable pingback from xmlrpc
add_filter('xmlrpc_methods', function($methods) {
    unset($methods['pingback.ping']);
    unset($methods['pingback.extensions.getPingbacks']);
    return $methods;
});

// 5. Remove unnecessary <link> tags from <head>
remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);
remove_action('wp_head', 'feed_links_extra', 3);
remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);

// 6. Prevent user enumeration via REST API /wp-json/wp/v2/users
add_filter('rest_endpoints', function($endpoints) {
    if (!current_user_can('administrator')) {
        if (isset($endpoints['/wp/v2/users'])) {
            unset($endpoints['/wp/v2/users']);
        }
        if (isset($endpoints['/wp/v2/users/(?P<id>[\d]+)'])) {
            unset($endpoints['/wp/v2/users/(?P<id>[\d]+)']);
        }
    }
    return $endpoints;
});

// 7. Prevent user enumeration via ?author= redirect (backup to .htaccess rule)
add_action('template_redirect', function() {
    if (!is_admin() && isset($_GET['author']) && !current_user_can('administrator')) {
        wp_redirect(home_url('/'), 301);
        exit;
    }
});

// 8. Change default login error messages (don't reveal if username exists)
add_filter('login_errors', function() {
    return 'Username atau password yang Anda masukkan salah. Silakan coba lagi.';
});

// 9. Prevent direct file/plugin editing from dashboard (belt-and-suspenders with wp-config constant)
add_filter('user_has_cap', function($allcaps) {
    if (!defined('DISALLOW_FILE_EDIT') || !DISALLOW_FILE_EDIT) {
        unset($allcaps['edit_themes'], $allcaps['edit_plugins']);
    }
    return $allcaps;
});

// 10. Basic login brute-force protection: track failed attempts in transients
add_action('wp_login_failed', function($username) {
    $ip  = sman1_get_client_ip();
    $key = 'sman1_login_fail_' . md5($ip);
    $attempts = (int) get_transient($key);
    set_transient($key, $attempts + 1, 15 * MINUTE_IN_SECONDS); // window: 15 min
});

add_filter('authenticate', function($user, $username, $password) {
    if (empty($username) && empty($password)) return $user;
    $ip  = sman1_get_client_ip();
    $key = 'sman1_login_fail_' . md5($ip);
    $attempts = (int) get_transient($key);
    if ($attempts >= 5) {
        return new WP_Error('too_many_retries',
            'Terlalu banyak percobaan login. Silakan coba lagi dalam 15 menit.'
        );
    }
    return $user;
}, 30, 3);

add_action('wp_login', function() {
    $ip  = sman1_get_client_ip();
    $key = 'sman1_login_fail_' . md5($ip);
    delete_transient($key); // reset counter on successful login
});

function sman1_get_client_ip() {
    foreach (['HTTP_CF_CONNECTING_IP','HTTP_X_FORWARDED_FOR','HTTP_CLIENT_IP','REMOTE_ADDR'] as $key) {
        if (!empty($_SERVER[$key])) {
            return sanitize_text_field(explode(',', $_SERVER[$key])[0]);
        }
    }
    return '0.0.0.0';
}


/* ==========================================================================
   MAINTENANCE MODE
   ========================================================================== */

// Register Customizer section & setting
add_action('customize_register', function($wp_customize) {

    $wp_customize->add_section('sman1_maintenance', array(
        'title'    => '🔧 Mode Maintenance',
        'priority' => 5, // top of customizer list
    ));

    // Toggle ON/OFF
    $wp_customize->add_setting('maintenance_mode', array(
        'default'           => '0',
        'transport'         => 'refresh',
        'sanitize_callback' => 'absint',
    ));
    $wp_customize->add_control('maintenance_mode', array(
        'label'   => 'Aktifkan Mode Maintenance',
        'section' => 'sman1_maintenance',
        'type'    => 'checkbox',
    ));

    // Optional: custom message
    $wp_customize->add_setting('maintenance_message', array(
        'default'           => 'Kami sedang melakukan pembaruan. Mohon kunjungi kembali dalam beberapa saat.',
        'transport'         => 'refresh',
        'sanitize_callback' => 'sanitize_textarea_field',
    ));
    $wp_customize->add_control('maintenance_message', array(
        'label'       => 'Pesan Maintenance (opsional)',
        'section'     => 'sman1_maintenance',
        'type'        => 'textarea',
    ));
});

// Intercept all front-end requests and serve maintenance page to non-admins
add_action('template_redirect', 'sman1_maintenance_redirect', 1);
function sman1_maintenance_redirect() {
    // Skip if maintenance mode is off
    if (!get_theme_mod('maintenance_mode', '0')) return;

    // Allow logged-in administrators through
    if (current_user_can('manage_options')) return;

    // Allow WP-cron, REST, and login page through
    if (defined('DOING_CRON') && DOING_CRON) return;
    if (defined('REST_REQUEST') && REST_REQUEST) return;
    if ($GLOBALS['pagenow'] === 'wp-login.php') return;

    // Send HTTP 503 (Service Temporarily Unavailable) — good for SEO
    http_response_code(503);
    header('Retry-After: 3600');
    header('Content-Type: text/html; charset=UTF-8');

    $maintenance_file = get_template_directory() . '/maintenance.php';
    if (file_exists($maintenance_file)) {
        include $maintenance_file;
    } else {
        echo '<h1>Website sedang maintenance. Mohon kembali nanti.</h1>';
    }
    exit;
}



