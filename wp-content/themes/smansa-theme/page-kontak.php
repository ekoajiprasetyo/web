<?php
/**
 * Template Name: Halaman Kontak
 *
 * Halaman kontak SMAN 1 Purwokerto — formulir pesan, info kontak, dan peta.
 *
 * Cara pakai:
 *   1. WP Admin → Halaman → Tambah Baru
 *   2. Judul: "Kontak" (atau terserah)
 *   3. Pilih Template: "Halaman Kontak"
 *   4. Publish
 *   5. WP Admin → Appearance → Menus → tambahkan halaman ini ke Primary Menu
 */

// ── Form Handler ──────────────────────────────────────────────────────────────
if ( isset( $_POST['sman1_contact_submit'] ) ) {
    // Verify nonce
    if (
        ! isset( $_POST['sman1_contact_nonce'] ) ||
        ! wp_verify_nonce( $_POST['sman1_contact_nonce'], 'sman1_contact_form' )
    ) {
        wp_die( 'Permintaan tidak valid.', 'Error', array( 'response' => 403 ) );
    }

    $name    = sanitize_text_field( wp_unslash( $_POST['contact_name']    ?? '' ) );
    $email   = sanitize_email( wp_unslash( $_POST['contact_email']          ?? '' ) );
    $phone   = sanitize_text_field( wp_unslash( $_POST['contact_phone']   ?? '' ) );
    $subject = sanitize_text_field( wp_unslash( $_POST['contact_subject'] ?? '' ) );
    $message = sanitize_textarea_field( wp_unslash( $_POST['contact_message'] ?? '' ) );

    $errors = array();
    if ( empty( $name ) )       $errors[] = 'name';
    if ( ! is_email( $email ) ) $errors[] = 'email';
    if ( empty( $subject ) )    $errors[] = 'subject';
    if ( empty( $message ) )    $errors[] = 'message';

    if ( empty( $errors ) ) {
        $admin_email = get_option( 'admin_email' );
        $mail_subject = '[Pesan Web] ' . wp_strip_all_tags( $subject );
        $mail_body  = "Pesan baru dari formulir kontak SMAN 1 Purwokerto.\n\n";
        $mail_body .= "Nama    : {$name}\n";
        $mail_body .= "Email   : {$email}\n";
        $mail_body .= "Telepon : " . ( $phone ?: '—' ) . "\n";
        $mail_body .= "Perihal : {$subject}\n\n";
        $mail_body .= "Pesan:\n{$message}\n";

        $headers = array(
            'Content-Type: text/plain; charset=UTF-8',
            "Reply-To: {$name} <{$email}>",
        );

        $sent = wp_mail( $admin_email, $mail_subject, $mail_body, $headers );

        wp_safe_redirect( add_query_arg( 'pesan', $sent ? 'terkirim' : 'gagal', get_permalink() ) );
        exit;
    } else {
        // Pass errors back via query string (values only; no sensitive data)
        wp_safe_redirect( add_query_arg( 'pesan', 'error', get_permalink() ) );
        exit;
    }
}

// ── Status from redirect ──────────────────────────────────────────────────────
$form_status = sanitize_key( $_GET['pesan'] ?? '' ); // 'terkirim' | 'gagal' | 'error'

// ── Contact details (sync with Customizer + footer) ──────────────────────────
$contact_phone     = get_theme_mod( 'contact_phone', '(0281) 636293' );
$contact_email     = get_theme_mod( 'contact_email', 'smansa_pwt@yahoo.co.id' );
$contact_address   = get_theme_mod( 'contact_address', 'Jl. Jend. Gatot Subroto No.73, Kranji, Purwokerto Timur, Banyumas 53116' );
$contact_hours     = get_theme_mod( 'contact_hours', 'Senin – Jumat: 07.00 – 15.00' );
$contact_whatsapp  = preg_replace( '/[^0-9]/', '', get_theme_mod( 'contact_whatsapp', '62281636293' ) );

// Support both full <iframe src="..."> tag AND bare URL pasted in Customizer
$raw_maps = get_theme_mod(
    'contact_maps_embed',
    'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3956.395657103313!2d109.23433807533208!3d-7.421389692589107!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e655e8a3f5cb477%3A0x1edb7d0197aa5ce9!2sSenior%20High%20School%201%20Purwokerto!5e0!3m2!1sen!2sid!4v1771596825658!5m2!1sen!2sid'
);
if ( preg_match( '/\bsrc=["\']([^"\']+)["\']/', $raw_maps, $_map_match ) ) {
    $maps_embed_url = $_map_match[1];  // extracted src URL from <iframe> tag
} else {
    $maps_embed_url = $raw_maps;       // already a plain URL
}

get_header();
?>

<main id="kontak-page">

    <!-- ═══════════════════════════════════════════════════
         HERO
    ═══════════════════════════════════════════════════ -->
    <section class="kontak-hero">
        <div class="kontak-hero-bg" aria-hidden="true"></div>
        <div class="kontak-hero-shapes" aria-hidden="true">
            <span class="kh-shape kh-shape-1"></span>
            <span class="kh-shape kh-shape-2"></span>
        </div>
        <div class="container kontak-hero-content">
            <!-- Breadcrumb -->
            <nav class="kontak-breadcrumb" aria-label="Breadcrumb">
                <a href="<?php echo esc_url( home_url('/') ); ?>">
                    <i class="fas fa-home" aria-hidden="true"></i> Beranda
                </a>
                <i class="fas fa-chevron-right" aria-hidden="true"></i>
                <span>Kontak</span>
            </nav>

            <div class="kontak-hero-body" data-aos="fade-up">
                <span class="kontak-hero-eyebrow">
                    <i class="fas fa-headset" aria-hidden="true"></i>
                    Hubungi Kami
                </span>
                <h1>Kami Siap <span>Mendengarkan</span> Anda</h1>
                <p>Punya pertanyaan, saran, atau ingin berkolaborasi? Tim kami selalu siap membantu Anda dengan cepat dan ramah.</p>
            </div>
        </div>
    </section>

    <!-- ═══════════════════════════════════════════════════
         INFO CARDS
    ═══════════════════════════════════════════════════ -->
    <section class="kontak-cards-wrap">
        <div class="container">
            <div class="kontak-cards">

                <div class="kontak-card" data-aos="fade-up" data-aos-delay="0">
                    <div class="kontak-card-icon">
                        <i class="fas fa-map-marker-alt" aria-hidden="true"></i>
                    </div>
                    <div class="kontak-card-body">
                        <h4>Alamat</h4>
                        <p><?php echo nl2br( esc_html( $contact_address ) ); ?></p>
                    </div>
                </div>

                <div class="kontak-card" data-aos="fade-up" data-aos-delay="100">
                    <div class="kontak-card-icon">
                        <i class="fas fa-phone-alt" aria-hidden="true"></i>
                    </div>
                    <div class="kontak-card-body">
                        <h4>Telepon</h4>
                        <p>
                            <a href="tel:<?php echo esc_attr( preg_replace('/[^0-9+]/', '', $contact_phone) ); ?>">
                                <?php echo esc_html( $contact_phone ); ?>
                            </a>
                        </p>
                        <p style="font-size:0.82rem;opacity:0.7;">Tersedia selama jam kerja</p>
                    </div>
                </div>

                <div class="kontak-card" data-aos="fade-up" data-aos-delay="200">
                    <div class="kontak-card-icon">
                        <i class="fas fa-envelope" aria-hidden="true"></i>
                    </div>
                    <div class="kontak-card-body">
                        <h4>Email</h4>
                        <p>
                            <a href="mailto:<?php echo esc_attr( $contact_email ); ?>">
                                <?php echo esc_html( $contact_email ); ?>
                            </a>
                        </p>
                        <p style="font-size:0.82rem;opacity:0.7;">Dibalas dalam 1×24 jam</p>
                    </div>
                </div>

                <div class="kontak-card" data-aos="fade-up" data-aos-delay="300">
                    <div class="kontak-card-icon">
                        <i class="fas fa-clock" aria-hidden="true"></i>
                    </div>
                    <div class="kontak-card-body">
                        <h4>Jam Operasional</h4>
                        <p><?php echo esc_html( $contact_hours ); ?></p>
                        <p style="font-size:0.82rem;opacity:0.7;">Hari kerja aktif</p>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ═══════════════════════════════════════════════════
         FORM  +  MAP
    ═══════════════════════════════════════════════════ -->
    <section class="kontak-main section">
        <div class="container">
            <div class="kontak-grid">

                <!-- ── FORM ── -->
                <div class="kontak-form-wrap" data-aos="fade-right">
                    <div class="kontak-section-label">
                        <i class="fas fa-paper-plane" aria-hidden="true"></i>
                        Kirim Pesan
                    </div>
                    <h2 class="kontak-form-title">Formulir <span>Pesan</span></h2>
                    <p class="kontak-form-desc">Isi formulir di bawah ini dan kami akan membalas secepatnya.</p>

                    <?php if ( $form_status === 'terkirim' ) : ?>
                    <div class="kontak-alert kontak-alert-success" role="alert">
                        <i class="fas fa-check-circle" aria-hidden="true"></i>
                        <div>
                            <strong>Pesan berhasil terkirim!</strong>
                            <p>Terima kasih telah menghubungi kami. Tim kami akan segera merespons pesan Anda.</p>
                        </div>
                    </div>
                    <?php elseif ( $form_status === 'gagal' ) : ?>
                    <div class="kontak-alert kontak-alert-error" role="alert">
                        <i class="fas fa-exclamation-circle" aria-hidden="true"></i>
                        <div>
                            <strong>Pengiriman gagal.</strong>
                            <p>Terjadi kesalahan teknis. Silakan hubungi kami langsung via telepon atau email.</p>
                        </div>
                    </div>
                    <?php elseif ( $form_status === 'error' ) : ?>
                    <div class="kontak-alert kontak-alert-error" role="alert">
                        <i class="fas fa-exclamation-circle" aria-hidden="true"></i>
                        <div>
                            <strong>Data tidak lengkap.</strong>
                            <p>Mohon lengkapi semua field yang wajib diisi lalu kirim ulang.</p>
                        </div>
                    </div>
                    <?php endif; ?>

                    <form class="kontak-form"
                          method="post"
                          action="<?php echo esc_url( get_permalink() ); ?>"
                          novalidate>
                        <?php wp_nonce_field( 'sman1_contact_form', 'sman1_contact_nonce' ); ?>
                        <input type="hidden" name="sman1_contact_submit" value="1">

                        <div class="kontak-form-row">
                            <div class="kontak-field">
                                <label for="contact_name">
                                    Nama Lengkap <span class="required" aria-hidden="true">*</span>
                                </label>
                                <div class="kontak-input-wrap">
                                    <i class="fas fa-user" aria-hidden="true"></i>
                                    <input type="text"
                                           id="contact_name"
                                           name="contact_name"
                                           placeholder="Masukkan nama Anda"
                                           required
                                           autocomplete="name">
                                </div>
                            </div>
                            <div class="kontak-field">
                                <label for="contact_email">
                                    Alamat Email <span class="required" aria-hidden="true">*</span>
                                </label>
                                <div class="kontak-input-wrap">
                                    <i class="fas fa-envelope" aria-hidden="true"></i>
                                    <input type="email"
                                           id="contact_email"
                                           name="contact_email"
                                           placeholder="email@contoh.com"
                                           required
                                           autocomplete="email">
                                </div>
                            </div>
                        </div>

                        <div class="kontak-form-row">
                            <div class="kontak-field">
                                <label for="contact_phone">Nomor Telepon <span class="kontak-optional">(opsional)</span></label>
                                <div class="kontak-input-wrap">
                                    <i class="fas fa-phone" aria-hidden="true"></i>
                                    <input type="tel"
                                           id="contact_phone"
                                           name="contact_phone"
                                           placeholder="08xx-xxxx-xxxx"
                                           autocomplete="tel">
                                </div>
                            </div>
                            <div class="kontak-field">
                                <label for="contact_subject">
                                    Perihal <span class="required" aria-hidden="true">*</span>
                                </label>
                                <div class="kontak-input-wrap kontak-select-wrap">
                                    <i class="fas fa-tag" aria-hidden="true"></i>
                                    <select id="contact_subject" name="contact_subject" required>
                                        <option value="">— Pilih perihal —</option>
                                        <option value="Informasi Umuml">Informasi Umum</option>
                                        <option value="SPMB / Penerimaan Siswa">SPMB / Penerimaan Siswa</option>
                                        <option value="Akademik & Kurikulum">Akademik &amp; Kurikulum</option>
                                        <option value="Ekstrakurikuler">Ekstrakurikuler</option>
                                        <option value="Kerjasama & Kolaborasi">Kerjasama &amp; Kolaborasi</option>
                                        <option value="Alumni">Alumni</option>
                                        <option value="Teknis Website">Teknis Website</option>
                                        <option value="Lainnya">Lainnya</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="kontak-field kontak-field-full">
                            <label for="contact_message">
                                Pesan <span class="required" aria-hidden="true">*</span>
                            </label>
                            <div class="kontak-input-wrap kontak-textarea-wrap">
                                <i class="fas fa-comment-alt" aria-hidden="true"></i>
                                <textarea id="contact_message"
                                          name="contact_message"
                                          rows="5"
                                          placeholder="Tuliskan pesan Anda di sini..."
                                          required></textarea>
                            </div>
                        </div>

                        <div class="kontak-form-footer">
                            <p class="kontak-required-note">
                                <span class="required" aria-hidden="true">*</span> Wajib diisi
                            </p>
                            <button type="submit" class="btn btn-lg kontak-submit-btn">
                                <i class="fas fa-paper-plane" aria-hidden="true"></i>
                                Kirim Pesan
                            </button>
                        </div>
                    </form>
                </div><!-- / .kontak-form-wrap -->

                <!-- ── MAP ── -->
                <div class="kontak-map-wrap" data-aos="fade-left" data-aos-delay="100">
                    <div class="kontak-section-label">
                        <i class="fas fa-map-marked-alt" aria-hidden="true"></i>
                        Lokasi Kami
                    </div>
                    <h2 class="kontak-form-title">Temukan <span>Kami</span></h2>
                    <p class="kontak-form-desc">Kunjungi kami langsung di kampus SMAN 1 Purwokerto.</p>

                    <div class="kontak-map-frame">
                        <iframe
                            src="<?php echo esc_url( $maps_embed_url ); ?>"
                            width="100%"
                            height="100%"
                            style="border:0;"
                            allowfullscreen=""
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"
                            title="Lokasi SMAN 1 Purwokerto">
                        </iframe>
                    </div>

                    <div class="kontak-map-actions">
                        <a href="https://maps.google.com/?q=SMAN+1+Purwokerto"
                           target="_blank"
                           rel="noopener noreferrer"
                           class="btn btn-sm kontak-maps-btn">
                            <i class="fas fa-directions" aria-hidden="true"></i>
                            Dapatkan Petunjuk Arah
                        </a>
                        <a href="https://wa.me/<?php echo esc_attr( $contact_whatsapp ); ?>"
                           target="_blank"
                           rel="noopener noreferrer"
                           class="btn btn-sm kontak-wa-btn">
                            <i class="fab fa-whatsapp" aria-hidden="true"></i>
                            Chat WhatsApp
                        </a>
                    </div>

                    <!-- Quick info strip -->
                    <div class="kontak-quick-info">
                        <div class="kontak-qi-item">
                            <i class="fas fa-map-pin" aria-hidden="true"></i>
                            <span><?php echo esc_html( $contact_address ); ?></span>
                        </div>
                        <div class="kontak-qi-item">
                            <i class="fas fa-phone-alt" aria-hidden="true"></i>
                            <a href="tel:<?php echo esc_attr( preg_replace('/[^0-9+]/', '', $contact_phone) ); ?>">
                                <?php echo esc_html( $contact_phone ); ?>
                            </a>
                        </div>
                    </div>
                </div><!-- / .kontak-map-wrap -->

            </div><!-- / .kontak-grid -->
        </div>
    </section>

    <!-- ═══════════════════════════════════════════════════
         SOCIAL STRIP
    ═══════════════════════════════════════════════════ -->
    <section class="kontak-social-section" data-aos="fade-up">
        <div class="container">
            <div class="kontak-social-inner">
                <div class="kontak-social-text">
                    <h3>Ikuti Kami di Media Sosial</h3>
                    <p>Dapatkan informasi terbaru seputar kegiatan, prestasi, dan pengumuman sekolah.</p>
                </div>
                <div class="kontak-social-links">
                    <?php
                    if ( has_nav_menu('social_menu') ) {
                        $locations = get_nav_menu_locations();
                        $menu = wp_get_nav_menu_object( $locations['social_menu'] );
                        if ( $menu ) {
                            $items = wp_get_nav_menu_items( $menu->term_id );
                            foreach ( $items as $item ) {
                                $icon  = sman1_get_social_icon( $item->url );
                                // Use platform name derived from URL when title is blank or generic
                                $title = trim( $item->title );
                                $title_lc = strtolower( $title );
                                if ( empty($title)
                                    || $title_lc === 'menu item'
                                    || $title_lc === 'main menu'
                                    || $title_lc === strtolower( $menu->name ) ) {
                                    $title = sman1_get_social_label( $item->url );
                                }
                                echo '<a href="' . esc_url($item->url) . '" target="_blank" rel="noopener" aria-label="' . esc_attr($title) . '" class="kontak-social-btn">';
                                echo '<i class="' . esc_attr($icon) . '" aria-hidden="true"></i>';
                                echo '<span>' . esc_html($title) . '</span>';
                                echo '</a>';
                            }
                        }
                    } else {
                        // Fallback icons
                        $socials = array(
                            array('fab fa-instagram', 'Instagram', '#'),
                            array('fab fa-youtube',   'YouTube',   '#'),
                            array('fab fa-tiktok',    'TikTok',    '#'),
                        );
                        foreach ( $socials as $s ) {
                            echo '<a href="' . esc_url($s[2]) . '" class="kontak-social-btn" aria-label="' . esc_attr($s[1]) . '">';
                            echo '<i class="' . esc_attr($s[0]) . '" aria-hidden="true"></i>';
                            echo '<span>' . esc_html($s[1]) . '</span>';
                            echo '</a>';
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    </section>

</main>

<?php get_footer(); ?>
