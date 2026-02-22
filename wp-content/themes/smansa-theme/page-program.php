<?php
/**
 * Template Name: Halaman Program Unggulan
 *
 * Dedicated full-page presentation of NAWA BRATA WIDYAKARYA —
 * the 8 flagship programs of SMAN 1 Purwokerto.
 *
 * Assign this template to any WP Page via:
 *   WP Admin → Edit Page → Page Attributes → Template → "Halaman Program Unggulan"
 */

get_header();

// =========================================================
// 1.  Query school_program CPT
// =========================================================
$prog_query = new WP_Query( array(
    'post_type'      => 'school_program',
    'posts_per_page' => 20,
    'orderby'        => 'menu_order',
    'order'          => 'ASC',
    'post_status'    => 'publish',
) );
$programs = $prog_query->posts;
wp_reset_postdata();

// =========================================================
// 2.  Rich fallback data (used when CPT has no entries)
// =========================================================
$prog_fallback = array(
    array(
        'title'       => 'Sekolah Sehat',
        'icon'        => 'fas fa-hospital',
        'color'       => '1e6f3e',
        'desc'        => 'Mewujudkan lingkungan belajar yang bersih, sehat, dan nyaman melalui program UKS aktif, kantin bergizi, dan gerakan hidup sehat seluruh warga sekolah.',
        'desc_long'   => 'Program Sekolah Sehat menjadi landasan terbentuknya generasi yang sehat jasmani dan rohani. SMAN 1 Purwokerto berkomitmen menghadirkan lingkungan belajar yang bebas dari polusi, kebisingan berlebih, dan potensi bahaya fisik lainnya. Melalui Unit Kesehatan Sekolah (UKS) yang aktif, setiap siswa mendapatkan akses layanan kesehatan dasar, pemeriksaan berkala, dan edukasi kesehatan reproduksi serta jiwa. Kantin sekolah hanya menyajikan makanan bergizi yang telah disertifikasi layak konsumsi, menjauhkan siswa dari jajanan tidak sehat.',
        'highlights'  => array(
            'UKS aktif berstandar nasional',
            'Kantin bergizi bersertifikat Dinkes',
            'Pemeriksaan kesehatan berkala',
            'Sanitasi dan air bersih terjamin',
            'Taman hijau & ruang terbuka sehat',
        ),
        'url'         => '#',
        'is_featured' => false,
    ),
    array(
        'title'       => 'Sekolah Ramah Anak',
        'icon'        => 'fas fa-child',
        'color'       => '2563eb',
        'desc'        => 'Menciptakan lingkungan sekolah yang aman, inklusif, dan menyenangkan yang menghargai hak-hak anak serta mendukung tumbuh kembang optimal siswa.',
        'desc_long'   => 'Program Sekolah Ramah Anak (SRA) merupakan perwujudan komitmen sekolah terhadap pemenuhan hak-hak anak sesuai Konvensi PBB tentang Hak Anak. Setiap kebijakan sekolah diformulasikan dengan mengedepankan kepentingan terbaik bagi siswa. Program ini mencakup pelatihan guru dan seluruh tenaga kependidikan dalam pengasuhan positif, pencegahan kekerasan, serta pengelolaan kelas yang menyenangkan. Mekanisme pengaduan yang aman dan mudah diakses tersedia agar siswa dapat melaporkan permasalahan tanpa rasa takut.',
        'highlights'  => array(
            'Zona bebas kekerasan & perundungan',
            'Mekanisme pengaduan yang aman',
            'Pelatihan guru pola asuh positif',
            'Ruang konseling ramah siswa',
            'Partisipasi siswa dalam pengambilan keputusan',
        ),
        'url'         => '#',
        'is_featured' => false,
    ),
    array(
        'title'       => 'Sekolah Berintegritas',
        'icon'        => 'fas fa-shield-halved',
        'color'       => '7c3aed',
        'desc'        => 'Membangun budaya kejujuran, transparansi, dan tanggung jawab dalam seluruh aspek kehidupan warga sekolah sebagai fondasi karakter yang unggul.',
        'desc_long'   => 'Integritas adalah DNA SMAN 1 Purwokerto. Program Sekolah Berintegritas bukan sekadar slogan — melainkan sistem nilai yang tertanam dalam setiap kegiatan belajar mengajar, pengelolaan administrasi, hingga pelaksanaan ujian. Sekolah menerapkan zona integritas dengan CCTV di ruang ujian, pengawas silang, serta sanksi tegas bagi pelanggar. Di sisi lain, kejujuran diberi penghargaan nyata lewat apresiasi siswa berintegritas setiap semester.',
        'highlights'  => array(
            'Zona anti-kekerasan & anti-korupsi',
            'Sistem ujian transparan & anti-curang',
            'Apresiasi siswa berintegritas tiap semester',
            'Pelaporan pelanggaran secara anonim',
            'Kode etik warga sekolah yang tertulis',
        ),
        'url'         => '#',
        'is_featured' => true,
    ),
    array(
        'title'       => 'Sekolah Riset',
        'icon'        => 'fas fa-microscope',
        'color'       => '0891b2',
        'desc'        => 'Membudayakan penelitian ilmiah dan inovasi berbasis masalah nyata, mendorong siswa menjadi ilmuwan muda yang berpikir kritis, analitis, dan kreatif.',
        'desc_long'   => 'Program Sekolah Riset menempatkan siswa bukan sebagai objek pembelajaran, melainkan sebagai peneliti muda yang aktif. Setiap siswa didorong untuk mengidentifikasi permasalahan nyata di sekitar mereka dan mencari solusi melalui metode ilmiah. Laboratorium IPA yang lengkap, perpustakaan digital, dan pembimbing riset berpengalaman menjadi infrastruktur utama. Karya-karya riset terbaik diikutsertakan dalam kompetisi ilmiah KIR, LKTI, dan ajang internasional seperti Intel ISEF.',
        'highlights'  => array(
            'Lab IPA lengkap berstandar nasional',
            'Ekstrakurikuler KIR (Karya Ilmiah Remaja)',
            'Mentor riset dari perguruan tinggi',
            'Jurnal ilmiah siswa dua tahunan',
            'Partisipasi lomba ilmiah nasional & internasional',
        ),
        'url'         => '#',
        'is_featured' => true,
    ),
    array(
        'title'       => 'Sekolah Prestasi',
        'icon'        => 'fas fa-trophy',
        'color'       => 'c2410c',
        'desc'        => 'Membina dan mengembangkan potensi siswa di bidang akademik, seni, dan olahraga untuk meraih prestasi membanggakan di tingkat nasional maupun internasional.',
        'desc_long'   => 'Prestasi adalah bukti nyata kualitas pendidikan SMAN 1 Purwokerto. Program Sekolah Prestasi secara sistematis mengidentifikasi bakat siswa sejak dini melalui asesmen minat dan bakat, kemudian mengarahkan mereka ke pembinaan yang tepat sasaran. Tim olimpiade sains telah melahirkan puluhan medali OSN. Tim seni dan olahraga secara rutin mengharumkan nama sekolah di tingkat provinsi dan nasional. Bimbingan intensif, sarana latihan memadai, dan pelatih berprestasi menjadi kunci keberhasilan program ini.',
        'highlights'  => array(
            'Pembinaan olimpiade sains (OSN) intensif',
            'Tim seni & olahraga berprestasi nasional',
            'Beasiswa prestasi akademik & non-akademik',
            'Database alumni berprestasi sebagai mentor',
            'Sarana latihan berstandar kompetisi',
        ),
        'url'         => '#',
        'is_featured' => false,
    ),
    array(
        'title'       => 'Gerakan Kepeloporan',
        'icon'        => 'fas fa-rocket',
        'color'       => 'd97706',
        'desc'        => 'Menumbuhkan jiwa pemimpin, keberanian berinovasi, dan semangat menjadi pelopor perubahan positif bagi lingkungan, masyarakat, dan bangsa.',
        'desc_long'   => 'Gerakan Kepeloporan lahir dari keyakinan bahwa setiap siswa SMANSA adalah calon pemimpin masa depan. Program ini membekali siswa dengan keterampilan kepemimpinan, komunikasi publik, kewirausahaan sosial, dan kemampuan berpikir out-of-the-box. Melalui Organisasi Siswa Intra Sekolah (OSIS), Pramuka, dan berbagai unit kegiatan, siswa diberi ruang seluas-luasnya untuk berinisiatif dan memimpin proyek nyata. Kolaborasi dengan NGO dan instansi pemerintah membuka wawasan siswa terhadap isu-isu sosial di masyarakat.',
        'highlights'  => array(
            'Program kepemimpinan OSIS & MPK',
            'Social entrepreneurship & proyek nyata',
            'Kolaborasi dengan NGO & komunitas',
            'Pelatihan public speaking & debat',
            'Kunjungan industri & leadership camp',
        ),
        'url'         => '#',
        'is_featured' => false,
    ),
    array(
        'title'       => 'Gerakan Literasi',
        'icon'        => 'fas fa-book-open',
        'color'       => '059669',
        'desc'        => 'Membangun budaya baca-tulis yang kuat melalui membaca harian, pojok buku, dan penulisan kreatif untuk meningkatkan kemampuan literasi siswa.',
        'desc_long'   => 'Di era digital yang penuh distraksi, Gerakan Literasi SMANSA hadir sebagai benteng kokoh budaya baca-tulis. Program ini diwujudkan lewat 15 menit membaca harian sebelum pelajaran dimulai, pojok baca di setiap ruang kelas, dan perpustakaan digital yang bisa diakses dari mana saja. Festival literasi tahunan mengundang penulis dan intelektual untuk menginspirasi siswa. Kompetisi menulis cerita pendek, esai, dan puisi membuka jalur ekspresi kreatif yang tak ternilai bagi perkembangan berpikir siswa.',
        'highlights'  => array(
            '15 menit membaca wajib setiap hari',
            'Perpustakaan digital 24 jam',
            'Pojok baca nyaman di setiap kelas',
            'Festival literasi tahunan',
            'Kompetisi menulis & jurnalistik sekolah',
        ),
        'url'         => '#',
        'is_featured' => false,
    ),
    array(
        'title'       => 'Gerakan Pembiasaan Anak Indonesia Hebat',
        'icon'        => 'fas fa-seedling',
        'color'       => '16a34a',
        'desc'        => 'Menanamkan kebiasaan hidup sehat sejak dini melalui olahraga rutin, pola makan bergizi, dan kesehatan mental sebagai investasi terbaik untuk masa depan.',
        'desc_long'   => 'GPAIS (Gerakan Pembiasaan Anak Indonesia Hebat) merupakan gerakan holistik yang menyentuh aspek fisik, mental, dan sosial kesehatan siswa. Senam pagi bersama setiap hari Jumat, program "Isi Piringku" untuk edukasi gizi, dan konseling kesehatan mental yang mudah diakses menjadi tiga pilar utama. SMANSA juga menjadi sekolah pelopor dalam penerapan kebijakan bebas rokok dan napza secara konsisten, dengan melibatkan orang tua dan masyarakat sekitar dalam pengawasan.',
        'highlights'  => array(
            'Senam pagi & olahraga bersama rutin',
            'Edukasi gizi & program "Isi Piringku"',
            'Layanan konseling kesehatan mental',
            'Kawasan bebas rokok & napza ketat',
            'Pelibatan orang tua dalam program kesehatan',
        ),
        'url'         => '#',
        'is_featured' => false,
    ),
);

$prog_is_fallback = empty( $programs );

// =========================================================
// 3.  Hero Section
// =========================================================
get_template_part( 'template-parts/inner-page-hero', null, array(
    'eyebrow_icon' => 'fas fa-star',
    'eyebrow_text' => 'Program Unggulan Sekolah',
    'title'        => 'NAWA BRATA',
    'title_accent' => 'WIDYAKARYA',
    'description'  => 'Delapan program pilar transformasi pendidikan SMAN 1 Purwokerto — membangun generasi unggul, berkarakter, dan berdaya saing global.',
    'breadcrumb'   => 'Program Unggulan',
) );
?>

<!-- =========================================================
     INTRO: Pillar Pills Overview
     ========================================================= -->
<section class="progpage-intro">
    <div class="container">
        <div class="progpage-intro-inner" data-aos="fade-up">
            <div class="progpage-intro-text">
                <span class="progpage-intro-kicker">
                    <i class="fas fa-quote-left" aria-hidden="true"></i>
                    Filosofi Program
                </span>
                <h2>
                    Delapan Pilar Menuju <span>Sekolah Bermutu Global</span>
                </h2>
                <p>
                    NAWA BRATA WIDYAKARYA adalah ikrar SMAN 1 Purwokerto dalam mewujudkan pendidikan 
                    bermakna yang melahirkan generasi sehat, berkarakter, berprestasi, dan siap menghadapi 
                    tantangan dunia. Setiap program dirancang saling melengkapi, membentuk ekosistem belajar 
                    yang menyeluruh dan berkelanjutan.
                </p>
            </div>
            <div class="progpage-pillars-grid">
                <?php
                $pillar_items = $prog_is_fallback ? $prog_fallback : array();
                if ( ! $prog_is_fallback ) {
                    foreach ( $programs as $prog ) {
                        $pillar_items[] = array(
                            'title' => get_the_title( $prog->ID ),
                            'icon'  => get_post_meta( $prog->ID, 'sp_icon', true ) ?: 'fas fa-star',
                        );
                    }
                }
                foreach ( $pillar_items as $idx => $pillar ) :
                    $n = sprintf( '%02d', $idx + 1 );
                    $icon = $prog_is_fallback ? $pillar['icon'] : $pillar['icon'];
                ?>
                <div class="progpage-pillar-pill" data-aos="zoom-in" data-aos-delay="<?php echo min( ($idx+1)*60, 480 ); ?>">
                    <span class="progpage-pillar-num"><?php echo $n; ?></span>
                    <i class="<?php echo esc_attr( $icon ); ?>" aria-hidden="true"></i>
                    <span class="progpage-pillar-name"><?php echo esc_html( $prog_is_fallback ? $pillar['title'] : $pillar['title'] ); ?></span>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

<!-- =========================================================
     PROGRAM BLOCKS — Alternating Layout
     ========================================================= -->
<section class="progpage-blocks">
    <div class="container">
        <?php
        $block_idx = 0;
        if ( $prog_is_fallback ) :
            foreach ( $prog_fallback as $prog ) :
                $title       = $prog['title'];
                $icon        = $prog['icon'];
                $color       = $prog['color'];
                $desc        = $prog['desc'];
                $desc_long   = $prog['desc_long'];
                $highlights  = $prog['highlights'];
                $url         = $prog['url'];
                $is_featured = $prog['is_featured'];
                $num         = sprintf( '%02d', $block_idx + 1 );
                $is_reversed = ( $block_idx % 2 !== 0 );
                $delay       = min( 100, 100 );
                $block_idx++;
        ?>
        <div class="progpage-block <?php echo $is_reversed ? 'progpage-block--rev' : ''; ?> <?php echo $is_featured ? 'progpage-block--featured' : ''; ?>" data-aos="fade-up">

            <!-- ── Visual Side ── -->
            <div class="progpage-visual" style="--prog-color:#<?php echo esc_attr( $color ); ?>">
                <div class="progpage-visual-inner">
                    <div class="progpage-bg-pattern" aria-hidden="true">
                        <span class="progpage-circle progpage-circle-1"></span>
                        <span class="progpage-circle progpage-circle-2"></span>
                        <span class="progpage-circle progpage-circle-3"></span>
                    </div>
                    <div class="progpage-icon-wrap">
                        <i class="<?php echo esc_attr( $icon ); ?>" aria-hidden="true"></i>
                    </div>
                    <div class="progpage-num-badge"><?php echo $num; ?></div>
                    <?php if ( $is_featured ) : ?>
                    <div class="progpage-featured-badge">
                        <i class="fas fa-star" aria-hidden="true"></i> Program Unggulan
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- ── Text Side ── -->
            <div class="progpage-text">
                <span class="progpage-kicker">
                    <span class="progpage-kicker-num"><?php echo $num; ?></span>
                    Program ke-<?php echo $block_idx; ?> dari delapan
                </span>

                <h2 class="progpage-title"><?php echo esc_html( $title ); ?></h2>

                <p class="progpage-desc-short"><?php echo esc_html( $desc ); ?></p>

                <div class="progpage-divider" aria-hidden="true"></div>

                <div class="progpage-desc-long">
                    <?php echo wpautop( esc_html( $desc_long ) ); ?>
                </div>

                <?php if ( ! empty( $highlights ) ) : ?>
                <ul class="progpage-highlights">
                    <?php foreach ( $highlights as $hl ) : ?>
                    <li>
                        <span class="progpage-hl-icon" aria-hidden="true">
                            <i class="fas fa-check-circle"></i>
                        </span>
                        <?php echo esc_html( $hl ); ?>
                    </li>
                    <?php endforeach; ?>
                </ul>
                <?php endif; ?>

                <?php if ( $url && $url !== '#' ) : ?>
                <a href="<?php echo esc_url( $url ); ?>" class="progpage-cta">
                    Pelajari Program Ini <i class="fas fa-arrow-right" aria-hidden="true"></i>
                </a>
                <?php endif; ?>
            </div>
        </div>
        <?php
            endforeach;
        else :
            foreach ( $programs as $prog ) :
                $title       = get_the_title( $prog->ID );
                $icon        = get_post_meta( $prog->ID, 'sp_icon',       true ) ?: 'fas fa-star';
                $desc        = get_post_meta( $prog->ID, 'sp_desc',       true ) ?: '';
                $desc_long   = get_post_meta( $prog->ID, 'sp_desc_long',  true ) ?: $desc;
                $highlights_raw = get_post_meta( $prog->ID, 'sp_highlights', true );
                $highlights  = array_filter( array_map( 'trim', explode( "\n", $highlights_raw ) ) );
                $url         = get_post_meta( $prog->ID, 'sp_url',        true ) ?: '#';
                $is_featured = (bool) get_post_meta( $prog->ID, 'sp_featured', true );
                $color       = 'var(--primary)';
                $num         = sprintf( '%02d', $block_idx + 1 );
                $is_reversed = ( $block_idx % 2 !== 0 );
                $block_idx++;
        ?>
        <div class="progpage-block <?php echo $is_reversed ? 'progpage-block--rev' : ''; ?> <?php echo $is_featured ? 'progpage-block--featured' : ''; ?>" data-aos="fade-up">

            <div class="progpage-visual" style="--prog-color:var(--primary)">
                <div class="progpage-visual-inner">
                    <div class="progpage-bg-pattern" aria-hidden="true">
                        <span class="progpage-circle progpage-circle-1"></span>
                        <span class="progpage-circle progpage-circle-2"></span>
                        <span class="progpage-circle progpage-circle-3"></span>
                    </div>
                    <div class="progpage-icon-wrap">
                        <i class="<?php echo esc_attr( $icon ); ?>" aria-hidden="true"></i>
                    </div>
                    <div class="progpage-num-badge"><?php echo $num; ?></div>
                    <?php if ( $is_featured ) : ?>
                    <div class="progpage-featured-badge">
                        <i class="fas fa-star" aria-hidden="true"></i> Program Unggulan
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="progpage-text">
                <span class="progpage-kicker">
                    <span class="progpage-kicker-num"><?php echo $num; ?></span>
                    Program ke-<?php echo $block_idx; ?> dari <?php echo count( $programs ); ?>
                </span>

                <h2 class="progpage-title"><?php echo esc_html( $title ); ?></h2>

                <?php if ( $desc ) : ?>
                <p class="progpage-desc-short"><?php echo esc_html( $desc ); ?></p>
                <?php endif; ?>

                <div class="progpage-divider" aria-hidden="true"></div>

                <?php if ( $desc_long ) : ?>
                <div class="progpage-desc-long">
                    <?php echo wpautop( esc_html( $desc_long ) ); ?>
                </div>
                <?php endif; ?>

                <?php if ( ! empty( $highlights ) ) : ?>
                <ul class="progpage-highlights">
                    <?php foreach ( $highlights as $hl ) : ?>
                    <li>
                        <span class="progpage-hl-icon" aria-hidden="true">
                            <i class="fas fa-check-circle"></i>
                        </span>
                        <?php echo esc_html( $hl ); ?>
                    </li>
                    <?php endforeach; ?>
                </ul>
                <?php endif; ?>

                <?php if ( $url && $url !== '#' ) : ?>
                <a href="<?php echo esc_url( $url ); ?>" class="progpage-cta">
                    Pelajari Program Ini <i class="fas fa-arrow-right" aria-hidden="true"></i>
                </a>
                <?php endif; ?>
            </div>
        </div>
        <?php
            endforeach;
        endif;
        ?>
    </div>
</section>

<!-- =========================================================
     CTA STRIP — Bottom Call-to-Action
     ========================================================= -->
<section class="progpage-cta-strip">
    <div class="container">
        <div class="progpage-cta-inner" data-aos="fade-up">
            <div class="progpage-cta-text">
                <h2>Bergabunglah dengan <span>SMAN 1 Purwokerto</span></h2>
                <p>Jadilah bagian dari generasi unggul yang tumbuh bersama delapan program unggulan kami.</p>
            </div>
            <div class="progpage-cta-btns">
                <?php
                $spmb_page = get_page_by_path( 'spmb' );
                $spmb_url  = $spmb_page ? get_permalink( $spmb_page->ID ) : home_url( '/spmb/' );
                $kontak_page = get_page_by_path( 'kontak' );
                $kontak_url  = $kontak_page ? get_permalink( $kontak_page->ID ) : home_url( '/kontak/' );
                ?>
                <a href="<?php echo esc_url( $spmb_url ); ?>" class="progpage-btn-primary">
                    <i class="fas fa-user-plus" aria-hidden="true"></i> Daftar SPMB
                </a>
                <a href="<?php echo esc_url( $kontak_url ); ?>" class="progpage-btn-secondary">
                    <i class="fas fa-phone" aria-hidden="true"></i> Hubungi Kami
                </a>
            </div>
        </div>
    </div>
</section>

<?php get_footer(); ?>
