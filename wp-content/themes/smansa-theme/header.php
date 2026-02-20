<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    
    <!-- Title is handled by WordPress -->
    
    <!-- Meta Tags -->
    <meta name="description" content="<?php bloginfo('description'); ?>">
    
    <!-- Favicon is handled by WordPress Customizer (Site Identity) -->

    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>


    <!-- ===================== PRELOADER ===================== -->
    <div id="preloader">
        <div class="loader-content">
            <div class="loader-logo-box">
                <?php 
                $custom_logo_id = get_theme_mod('custom_logo');
                $logo = wp_get_attachment_image_src($custom_logo_id, 'full');
                if (has_custom_logo()) {
                    echo '<img src="' . esc_url($logo[0]) . '" alt="' . get_bloginfo('name') . '" class="loader-img">';
                } else {
                    echo '<img src="' . get_template_directory_uri() . '/images/logo.png" alt="SMAN 1 Purwokerto" class="loader-img">';
                }
                ?>
                <div class="ripple-effect"></div>
            </div>
            <h1 class="loader-title"><?php bloginfo('name'); ?></h1>
            <p class="loader-slogan">"<?php echo get_theme_mod('preloader_tagline', get_bloginfo('description')); ?>"</p>
            <div class="loader-progress-container">
                <div class="loader-progress-bar"></div>
            </div>
        </div>
    </div>

    <script>
        // Check if navigation is internal and not a reload
        (function() {
            try {
                const perfEntry = performance.getEntriesByType("navigation")[0];
                const isReload = (perfEntry && perfEntry.type === 'reload') || window.performance.navigation.type === 1;
                const isInternal = document.referrer && document.referrer.indexOf(window.location.hostname) > -1;
                
                if (isInternal && !isReload) {
                    var preloader = document.getElementById('preloader');
                    if (preloader) {
                        preloader.style.display = 'none'; // Hide immediately
                    }
                    document.body.style.overflow = 'auto'; // Enable scroll
                }
            } catch(e) { console.error('Preloader logic error', e); }
        })();
    </script>

    <!-- ===================== TOP BAR ===================== -->
    <div class="top-bar">
        <div class="container top-bar-content">
            <div class="top-contacts">
                <?php
                $phone = get_theme_mod('contact_phone', '(0281) 636293');
                $email = get_theme_mod('contact_email', 'smansa_pwt@yahoo.co.id');
                ?>
                <a href="tel:<?php echo esc_attr($phone); ?>"><i class="fas fa-phone-alt"></i> <?php echo esc_html($phone); ?></a>
                <a href="mailto:<?php echo esc_attr($email); ?>"><i class="fas fa-envelope"></i> <?php echo esc_html($email); ?></a>
            </div>
            <div class="top-socials">
                <?php
                if (has_nav_menu('social_menu')) {
                    $locations = get_nav_menu_locations();
                    $menu = wp_get_nav_menu_object($locations['social_menu']);
                    if ($menu) {
                        $menu_items = wp_get_nav_menu_items($menu->term_id);
                        foreach ($menu_items as $item) {
                            $icon_class = sman1_get_social_icon($item->url);
                            echo '<a href="' . esc_url($item->url) . '" target="_blank" aria-label="' . esc_attr($item->title) . '"><i class="' . esc_attr($icon_class) . '"></i></a>';
                        }
                    }
                } else {
                    // Fallback if no menu assigned
                    echo '<span style="opacity:0.6; font-size:0.8em;">(Atur Menu "Social Media" di Admin)</span>';
                }
                ?>
                <div class="lang-switch">
                    <i class="fas fa-globe"></i> ID
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Nav Overlay -->
    <div class="nav-overlay" id="navOverlay"></div>

    <!-- ===================== HEADER / NAVIGATION ===================== -->
    <header class="header" id="header">
        <div class="container">
            <nav class="navbar">
                <!-- Logo -->
                <a href="<?php echo home_url('/'); ?>" class="logo">
                     <?php if (has_custom_logo()): ?>
                        <?php the_custom_logo(); ?>
                    <?php else: ?>
                        <div class="logo-icon">
                            <img src="<?php echo get_template_directory_uri(); ?>/images/logo.png" alt="Logo" style="width: 45px; height: auto;">
                        </div>
                    <?php endif; ?>
                    
                    <div class="logo-text">
                        <span class="logo-primary">SMAN 1</span>
                        <span class="logo-secondary">Purwokerto</span>
                    </div>
                </a>

                <!-- Main Menu -->
                <?php
                if (has_nav_menu('primary_menu')) {
                    wp_nav_menu(array(
                        'theme_location' => 'primary_menu',
                        'container'      => false,
                        'menu_class'     => 'nav-menu',
                        'menu_id'        => 'navMenu',
                        'depth'          => 0,
                        'walker'         => new SMAN1_Nav_Walker(),
                        'items_wrap'     => '<ul id="%1$s" class="%2$s"><button class="nav-close-btn" id="navCloseBtn" aria-label="Tutup Menu"><i class="fas fa-times"></i></button>%3$s</ul>',
                    )); 
                } else {
                    echo '<ul class="nav-menu" id="navMenu"><button class="nav-close-btn" id="navCloseBtn" aria-label="Tutup Menu"><i class="fas fa-times"></i></button><li><a href="#" class="nav-link">Setup Menu di Admin</a></li></ul>';
                }
                ?>

                <!-- Nav Actions -->
                <div class="nav-actions">
                    <button class="search-btn" id="searchBtn">
                        <i class="fas fa-search"></i>
                    </button>
                    <a href="portal.html" class="btn btn-sm btn-accent btn-portal">
                        <i class="fas fa-sign-in-alt"></i> <span>Portal</span>
                    </a>
                    
                    <!-- Hamburger -->
                    <div class="hamburger" id="hamburger">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                </div>
            </nav>
        </div>
    </header>

    <!-- ===================== SEARCH OVERLAY ===================== -->
    <div class="search-overlay" id="searchOverlay">
        <div class="search-container">
            <button class="close-search search-close" id="searchClose"><i class="fas fa-times"></i></button>
            <form class="search-form" action="<?php echo home_url('/'); ?>" method="get">
                <input type="text" name="s" placeholder="Cari informasi, berita, atau dokumen..." autofocus>
                <button type="submit"><i class="fas fa-search"></i></button>
            </form>
            <div class="search-suggestions">
                <span>Pencarian Populer:</span>
                <a href="#">PPDB 2026</a>
                <a href="#">Prestasi</a>
                <a href="#">Ekstrakurikuler</a>
                <a href="#">Agenda</a>
            </div>
        </div>
    </div>
