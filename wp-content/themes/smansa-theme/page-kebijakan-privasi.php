<?php
/**
 * Template Name: Kebijakan Privasi
 *
 * Halaman Kebijakan Privasi SMAN 1 Purwokerto
 */

if ( ! defined( 'ABSPATH' ) ) exit;

get_header();

get_template_part( 'template-parts/inner-page-hero', null, array(
    'eyebrow_icon' => 'fas fa-shield-alt',
    'eyebrow_text' => '',
    'title'        => 'Kebijakan Privasi',
    'title_accent' => '',
    'description'  => '',
    'breadcrumb'   => 'Kebijakan Privasi',
    'extra_html'   => '',
) );
?>

<div class="ppage-wrap">
<div class="container">

    <article class="ppage-card" data-aos="fade-up">
        <div class="ppage-card-stripe" aria-hidden="true"></div>
        <div class="ppage-body">

            <div class="legal-last-updated">
                <i class="fas fa-calendar-check" aria-hidden="true"></i>
                Terakhir diperbarui: <strong>Februari 2026</strong>
            </div>

            <p>SMA Negeri 1 Purwokerto ("<strong>Sekolah</strong>", "<strong>kami</strong>") berkomitmen untuk melindungi privasi setiap pengguna (<strong>"Anda"</strong>) yang mengakses situs web resmi kami di <strong>https://sman1purwokerto.sch.id</strong>. Kebijakan Privasi ini menjelaskan jenis informasi yang kami kumpulkan, bagaimana kami menggunakannya, dan hak-hak Anda atas informasi tersebut.</p>

            <h2><i class="fas fa-info-circle" aria-hidden="true"></i> 1. Informasi yang Kami Kumpulkan</h2>
            <p>Kami dapat mengumpulkan informasi dalam beberapa kategori berikut:</p>
            <ul>
                <li><strong>Informasi yang Anda berikan secara sukarela</strong> — Formulir kontak, pendaftaran acara, atau layanan informasi lainnya yang memerlukan nama, alamat email, dan nomor telepon.</li>
                <li><strong>Data teknis otomatis</strong> — Alamat IP, jenis browser, sistem operasi, halaman yang dikunjungi, dan waktu kunjungan dikumpulkan secara otomatis melalui server log dan layanan analitik seperti Google Analytics.</li>
                <li><strong>Cookie dan teknologi pelacakan</strong> — Kami menggunakan cookie sesi dan cookie preferensi untuk meningkatkan pengalaman pengguna. Cookie tidak menyimpan informasi pribadi yang sensitif.</li>
            </ul>

            <h2><i class="fas fa-tasks" aria-hidden="true"></i> 2. Penggunaan Informasi</h2>
            <p>Informasi yang kami kumpulkan digunakan untuk tujuan berikut:</p>
            <ul>
                <li>Mengoperasikan dan meningkatkan situs web sekolah.</li>
                <li>Merespons pertanyaan, permintaan, atau laporan yang masuk melalui formulir kontak.</li>
                <li>Mengirim informasi terkait kegiatan, pengumuman, dan layanan akademik sekolah (jika Anda memberikan izin).</li>
                <li>Memantau keamanan dan mencegah penyalahgunaan sistem.</li>
                <li>Memenuhi kewajiban hukum yang berlaku di Indonesia.</li>
            </ul>

            <h2><i class="fas fa-share-alt" aria-hidden="true"></i> 3. Berbagi Informasi dengan Pihak Ketiga</h2>
            <p>Kami <strong>tidak menjual, menyewakan, atau memperdagangkan</strong> data pribadi Anda kepada pihak ketiga. Informasi dapat dibagikan hanya dalam kondisi berikut:</p>
            <ul>
                <li><strong>Penyedia layanan</strong> — Pihak ketiga yang membantu kami mengoperasikan situs (misalnya layanan hosting, analitik) dan terikat oleh perjanjian kerahasiaan.</li>
                <li><strong>Kewajiban hukum</strong> — Ketika diwajibkan oleh hukum, peraturan, atau perintah pengadilan yang berlaku di wilayah Republik Indonesia.</li>
                <li><strong>Perlindungan hak</strong> — Untuk melindungi hak, properti, atau keselamatan sekolah, siswa, staf, maupun publik.</li>
            </ul>

            <h2><i class="fas fa-cookie-bite" aria-hidden="true"></i> 4. Cookie</h2>
            <p>Situs kami menggunakan cookie untuk menyimpan preferensi pengguna dan data sesi. Anda dapat mengonfigurasi browser Anda untuk menolak semua cookie atau untuk memberikan notifikasi saat cookie dikirimkan. Namun, perlu diperhatikan bahwa beberapa fitur situs mungkin tidak berfungsi optimal tanpa cookie.</p>

            <h2><i class="fas fa-lock" aria-hidden="true"></i> 5. Keamanan Data</h2>
            <p>Kami menggunakan langkah-langkah teknis dan organisasi yang wajar untuk melindungi informasi Anda dari akses tidak sah, pengungkapan, perubahan, atau penghancuran. Meskipun demikian, tidak ada metode transmisi melalui Internet atau metode penyimpanan elektronik yang 100% aman. Kami berupaya semaksimal mungkin untuk melindungi data Anda, namun tidak dapat menjamin keamanan absolutnya.</p>

            <h2><i class="fas fa-child" aria-hidden="true"></i> 6. Perlindungan Data Siswa (Anak di Bawah Umur)</h2>
            <p>Situs web kami dapat memuat informasi, foto, dan video yang melibatkan siswa sebagai bagian dari kegiatan resmi sekolah. Setiap penayangan konten yang berkaitan dengan siswa dilakukan atas dasar persetujuan sesuai ketentuan yang berlaku. Orang tua atau wali yang memiliki keberatan terkait konten yang melibatkan siswa dapat menghubungi kami melalui halaman <a href="<?php echo esc_url( home_url( '/kontak/' ) ); ?>">Kontak</a>.</p>

            <h2><i class="fas fa-external-link-alt" aria-hidden="true"></i> 7. Tautan ke Situs Eksternal</h2>
            <p>Situs kami dapat mengandung tautan ke situs web eksternal yang tidak dioperasikan oleh kami. Kami tidak bertanggung jawab atas kebijakan privasi atau praktik situs-situs tersebut. Kami mendorong Anda untuk membaca kebijakan privasi dari setiap situs yang Anda kunjungi.</p>

            <h2><i class="fas fa-user-shield" aria-hidden="true"></i> 8. Hak-Hak Anda</h2>
            <p>Sesuai dengan peraturan perundang-undangan yang berlaku, Anda memiliki hak untuk:</p>
            <ul>
                <li>Meminta akses ke data pribadi yang kami simpan tentang Anda.</li>
                <li>Meminta koreksi atas data yang tidak akurat atau tidak lengkap.</li>
                <li>Meminta penghapusan data pribadi Anda dalam kondisi tertentu.</li>
                <li>Mengajukan keberatan atas pemrosesan data pribadi Anda.</li>
            </ul>
            <p>Untuk menggunakan hak-hak tersebut, silakan hubungi kami melalui informasi kontak di bawah ini.</p>

            <h2><i class="fas fa-sync-alt" aria-hidden="true"></i> 9. Perubahan Kebijakan Privasi</h2>
            <p>Kami berhak memperbarui Kebijakan Privasi ini sewaktu-waktu. Perubahan akan diumumkan di halaman ini dengan memperbarui tanggal "Terakhir diperbarui". Kami menyarankan Anda untuk secara berkala meninjau halaman ini guna memastikan Anda mengetahui perubahan terbaru.</p>

            <h2><i class="fas fa-envelope" aria-hidden="true"></i> 10. Hubungi Kami</h2>
            <p>Jika Anda memiliki pertanyaan atau kekhawatiran mengenai Kebijakan Privasi ini, silakan hubungi kami:</p>
            <div class="legal-contact-box">
                <p><i class="fas fa-school" aria-hidden="true"></i> <strong>SMA Negeri 1 Purwokerto</strong></p>
                <p><i class="fas fa-map-marker-alt" aria-hidden="true"></i> Jl. Jend. Gatot Subroto No.73, Kranji, Purwokerto Timur, Banyumas 53116</p>
                <p><i class="fas fa-phone" aria-hidden="true"></i> (0281) 636293</p>
                <p><i class="fas fa-envelope" aria-hidden="true"></i> <a href="mailto:smansa_pwt@yahoo.co.id">smansa_pwt@yahoo.co.id</a></p>
                <p><i class="fas fa-globe" aria-hidden="true"></i> <a href="<?php echo esc_url( home_url( '/kontak/' ) ); ?>">Halaman Kontak</a></p>
            </div>

        </div><!-- .ppage-body -->
    </article><!-- .ppage-card -->

</div><!-- .container -->
</div><!-- .ppage-wrap -->

<?php get_footer(); ?>
