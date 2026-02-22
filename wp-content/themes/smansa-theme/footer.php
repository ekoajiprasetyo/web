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

                    <!-- Footer Collaboration Links (CPT: collaboration_link) -->
                    <div class="footer-col">
                        <h4>Kolaborasi &amp; Mitra</h4>
                        <?php
                        $collab_query = new WP_Query( array(
                            'post_type'      => 'collaboration_link',
                            'posts_per_page' => 10,
                            'orderby'        => 'menu_order',
                            'order'          => 'ASC',
                            'post_status'    => 'publish',
                        ) );
                        if ( $collab_query->have_posts() ) :
                        ?>
                        <ul class="footer-links">
                            <?php while ( $collab_query->have_posts() ) : $collab_query->the_post();
                                $c_url  = get_post_meta( get_the_ID(), '_collab_url',  true );
                                $c_desc = get_post_meta( get_the_ID(), '_collab_desc', true );
                                $c_icon = get_post_meta( get_the_ID(), '_collab_icon', true ) ?: 'fas fa-link';
                            ?>
                            <li>
                                <a href="<?php echo esc_url( $c_url ?: '#' ); ?>"
                                   target="_blank"
                                   rel="noopener noreferrer"
                                   <?php if ( $c_desc ) echo 'title="' . esc_attr( $c_desc ) . '"'; ?>>
                                    <i class="<?php echo esc_attr( $c_icon ); ?> footer-collab-icon" aria-hidden="true"></i>
                                    <?php the_title(); ?>
                                </a>
                            </li>
                            <?php endwhile; wp_reset_postdata(); ?>
                        </ul>
                        <?php else : ?>
                        <p class="footer-empty-note">Belum ada mitra. Tambahkan lewat <a href="<?php echo admin_url('edit.php?post_type=collaboration_link'); ?>">WP Admin &rarr; Kolaborasi</a>.</p>
                        <?php endif; ?>
                    </div>

                    <!-- Footer Digital Services (synced with CPT: quick_access_card) -->
                    <div class="footer-col">
                        <h4>Layanan Digital</h4>
                        <?php
                        $qa_footer_query = new WP_Query( array(
                            'post_type'      => 'quick_access_card',
                            'posts_per_page' => 8,
                            'orderby'        => 'menu_order',
                            'order'          => 'ASC',
                            'post_status'    => 'publish',
                        ) );
                        if ( $qa_footer_query->have_posts() ) :
                        ?>
                        <ul class="footer-links">
                            <?php while ( $qa_footer_query->have_posts() ) : $qa_footer_query->the_post();
                                $qa_id    = get_the_ID();
                                $qa_url   = get_post_meta( $qa_id, 'qa_url', true )   ?: '#';
                                $qa_icon  = get_post_meta( $qa_id, 'qa_icon', true )  ?: 'fas fa-link';
                                $qa_title = get_post_meta( $qa_id, 'qa_title', true ) ?: get_the_title();
                            ?>
                            <li>
                                <a href="<?php echo esc_url( $qa_url ); ?>"
                                   <?php if ( $qa_url !== '#' ) echo 'target="_blank" rel="noopener"'; ?>>
                                    <i class="<?php echo esc_attr( $qa_icon ); ?> footer-service-icon" aria-hidden="true"></i>
                                    <?php echo esc_html( $qa_title ); ?>
                                </a>
                            </li>
                            <?php endwhile; wp_reset_postdata(); ?>
                        </ul>
                        <?php else : ?>
                        <ul class="footer-links">
                            <li><a href="#"><i class="fas fa-laptop-code footer-service-icon"></i> E-Learning</a></li>
                            <li><a href="#"><i class="fas fa-edit footer-service-icon"></i> Ujian Online</a></li>
                            <li><a href="#"><i class="fas fa-user-circle footer-service-icon"></i> Portal Siswa</a></li>
                            <li><a href="#"><i class="fas fa-book-reader footer-service-icon"></i> Perpustakaan</a></li>
                            <li><a href="#"><i class="fas fa-user-plus footer-service-icon"></i> SPMB Online</a></li>
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
                        <?php
                        $fp_kp  = get_pages( array( 'meta_key' => '_wp_page_template', 'meta_value' => 'page-kebijakan-privasi.php', 'number' => 1 ) );
                        $fp_sk  = get_pages( array( 'meta_key' => '_wp_page_template', 'meta_value' => 'page-syarat-ketentuan.php',  'number' => 1 ) );
                        $fp_sm  = get_pages( array( 'meta_key' => '_wp_page_template', 'meta_value' => 'page-sitemap.php',            'number' => 1 ) );
                        $url_kp = $fp_kp ? get_permalink( $fp_kp[0]->ID ) : home_url( '/kebijakan-privasi/' );
                        $url_sk = $fp_sk ? get_permalink( $fp_sk[0]->ID ) : home_url( '/syarat-ketentuan/' );
                        $url_sm = $fp_sm ? get_permalink( $fp_sm[0]->ID ) : home_url( '/sitemap/' );
                        ?>
                        <a href="<?php echo esc_url( $url_kp ); ?>">Kebijakan Privasi</a>
                        <a href="<?php echo esc_url( $url_sk ); ?>">Syarat &amp; Ketentuan</a>
                        <a href="<?php echo esc_url( $url_sm ); ?>">Sitemap</a>
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
    <?php $foot_wa = preg_replace( '/[^0-9]/', '', get_theme_mod( 'contact_whatsapp', '62281636293' ) ); ?>
    <a href="https://wa.me/<?php echo esc_attr( $foot_wa ); ?>" class="whatsapp-float" target="_blank" aria-label="WhatsApp">
        <i class="fab fa-whatsapp"></i>
        <span class="tooltip">Hubungi via WhatsApp</span>
    </a>

    <?php wp_footer(); ?>
</body>
</html>
