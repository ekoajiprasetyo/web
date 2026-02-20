    <!-- ===================== FOOTER ===================== -->
    <footer class="footer">
        <div class="footer-top">
            <div class="container">
                <div class="footer-grid">
                    <!-- Footer About -->
                    <div class="footer-col footer-about">
                        <div class="footer-logo">
                           <?php if (has_custom_logo()): ?>
                                <?php the_custom_logo(); ?>
                            <?php else: ?>
                                <img src="<?php echo get_template_directory_uri(); ?>/images/logo.png" alt="Logo SMAN 1 Purwokerto" style="width: 50px; height: auto; margin-right: 10px;">
                            <?php endif; ?>
                            <div class="logo-text">
                                <span class="logo-primary" style="color: var(--primary) !important; font-weight: 800; font-size: 1.5rem;">SMAN 1</span>
                                <span class="logo-secondary" style="color: var(--white) !important; opacity: 1; font-size: 1rem; font-weight: 600;">Purwokerto</span>
                            </div>
                        </div>
                        <p style="opacity: 0.8; font-size: 0.95rem;"><?php echo get_theme_mod('footer_about_text', 'Mencetak generasi takwa, unggul, berbudaya, sehat dan berwawasan lingkungan.'); ?></p>
                        <div class="footer-social">
                            <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                            <a href="#" aria-label="YouTube"><i class="fab fa-youtube"></i></a>
                            <a href="#" aria-label="TikTok"><i class="fab fa-tiktok"></i></a>
                        </div>
                    </div>

                    <!-- Footer Links (Menu) -->
                    <div class="footer-col">
                        <h4>Tautan Cepat</h4>
                        <?php
                        wp_nav_menu(array(
                            'theme_location' => 'footer_menu',
                            'container'      => false,
                            'menu_class'     => 'footer-links',
                            'fallback_cb'    => false,
                        ));
                        ?>
                    </div>

                    <!-- Footer Services (Widget Area) -->
                    <div class="footer-col">
                        <?php if (is_active_sidebar('footer-1')) : ?>
                            <?php dynamic_sidebar('footer-1'); ?>
                        <?php else : ?>
                            <h4>Layanan Digital</h4>
                            <ul class="footer-links">
                                <li><a href="#"><i class="fas fa-chevron-right"></i> E-Learning</a></li>
                                <li><a href="#"><i class="fas fa-chevron-right"></i> Ujian Online</a></li>
                                <li><a href="#"><i class="fas fa-chevron-right"></i> Portal Siswa</a></li>
                                <li><a href="#"><i class="fas fa-chevron-right"></i> Perpustakaan</a></li>
                                <li><a href="#"><i class="fas fa-chevron-right"></i> Alumni Portal</a></li>
                            </ul>
                        <?php endif; ?>
                    </div>

                    <!-- Footer Contact -->
                    <div class="footer-col footer-contact">
                        <h4>Kontak Kami</h4>
                        <ul class="contact-list">
                            <li>
                                <i class="fas fa-map-marker-alt"></i>
                                <span>Jl. Jend. Gatot Subroto No.73, Kranji,<br>Purwokerto Timur, Banyumas 53116</span>
                            </li>
                            <li>
                                <i class="fas fa-phone"></i>
                                <a href="tel:+62281636293">(0281) 636293</a>
                            </li>
                            <li>
                                <i class="fas fa-envelope"></i>
                                <a href="mailto:smansa_pwt@yahoo.co.id">smansa_pwt@yahoo.co.id</a>
                            </li>
                            <li>
                                <i class="fas fa-clock"></i>
                                <span>Senin - Jumat: 07:00 - 15:00</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer Bottom -->
        <div class="footer-bottom">
            <div class="container">
                <div class="footer-bottom-content">
                    <p>&copy; <?php echo date('Y'); ?> SMA Negeri 1 Purwokerto. All rights reserved.</p>
                    <div class="footer-bottom-links">
                        <a href="#">Kebijakan Privasi</a>
                        <a href="#">Syarat & Ketentuan</a>
                        <a href="#">Sitemap</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- ===================== BACK TO TOP ===================== -->
    <button class="back-to-top" id="backToTop">
        <i class="fas fa-chevron-up"></i>
    </button>

    <!-- ===================== WHATSAPP FLOAT ===================== -->
    <a href="https://wa.me/62281636293" class="whatsapp-float" target="_blank" aria-label="WhatsApp">
        <i class="fab fa-whatsapp"></i>
        <span class="tooltip">Hubungi via WhatsApp</span>
    </a>

    <?php wp_footer(); ?>
</body>
</html>
