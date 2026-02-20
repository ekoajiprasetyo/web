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

    // Contact Info Section (Top Bar)
    $wp_customize->add_section('contact_section', array(
        'title'    => __('Contact Info (Top Bar)', 'sman1'),
        'priority' => 25,
    ));

    $wp_customize->add_setting('contact_phone', array('default' => '(0281) 636293', 'transport' => 'refresh'));
    $wp_customize->add_control('contact_phone', array('label' => __('Phone Number', 'sman1'), 'section' => 'contact_section', 'type' => 'text'));

    $wp_customize->add_setting('contact_email', array('default' => 'smansa_pwt@yahoo.co.id', 'transport' => 'refresh'));
    $wp_customize->add_control('contact_email', array('label' => __('Email Address', 'sman1'), 'section' => 'contact_section', 'type' => 'text'));

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


