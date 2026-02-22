<?php
/**
 * SPMB (Sistem Penerimaan Murid Baru) — Admin Setup
 *
 * Registers WP-Admin menu with settings for:
 *   - Countdown target date
 *   - Registration open / close dates
 *   - Registration URL & guide URL
 *   - Academic year, quota, status labels
 *   - Feature bullet points
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// ─── Admin Menu ────────────────────────────────────────────────────────────
add_action( 'admin_menu', 'sman1_spmb_admin_menu' );

function sman1_spmb_admin_menu() {
    add_menu_page(
        /* page_title */ 'Pengaturan SPMB',
        /* menu_title */ 'SPMB',
        /* capability */ 'manage_options',
        /* menu_slug  */ 'sman1-spmb',
        /* callback   */ 'sman1_spmb_render_page',
        /* icon       */ 'dashicons-welcome-write-blog',
        /* position   */ 27
    );
}

// ─── Register Settings ────────────────────────────────────────────────────
add_action( 'admin_init', 'sman1_spmb_register_settings' );

function sman1_spmb_register_settings() {
    $text_fields = array(
        'sman1_spmb_target_date',   // datetime-local value e.g. "2026-06-01T08:00"
        'sman1_spmb_reg_start',     // date e.g. "2026-06-01"
        'sman1_spmb_reg_end',       // date e.g. "2026-06-30"
        'sman1_spmb_year',          // academic year string e.g. "2026/2027"
        'sman1_spmb_quota',         // total quota e.g. "360"
        'sman1_spmb_status_open',   // visible label when open
        'sman1_spmb_status_closed', // visible label when closed
    );
    foreach ( $text_fields as $field ) {
        register_setting(
            'sman1_spmb_group',
            $field,
            array( 'sanitize_callback' => 'sanitize_text_field' )
        );
    }

    register_setting(
        'sman1_spmb_group',
        'sman1_spmb_url',
        array( 'sanitize_callback' => 'esc_url_raw' )
    );

    register_setting(
        'sman1_spmb_group',
        'sman1_spmb_guide_url',
        array( 'sanitize_callback' => 'esc_url_raw' )
    );

    // Textarea — one feature per line
    register_setting(
        'sman1_spmb_group',
        'sman1_spmb_features',
        array( 'sanitize_callback' => 'sanitize_textarea_field' )
    );
}

// ─── Admin Page Render ────────────────────────────────────────────────────
function sman1_spmb_render_page() {
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }

    // Read options (with sensible defaults)
    $target_date    = get_option( 'sman1_spmb_target_date', '' );
    $reg_start      = get_option( 'sman1_spmb_reg_start', '' );
    $reg_end        = get_option( 'sman1_spmb_reg_end', '' );
    $year           = get_option( 'sman1_spmb_year', '2026/2027' );
    $quota          = get_option( 'sman1_spmb_quota', '' );
    $spmb_url       = get_option( 'sman1_spmb_url', '' );
    $guide_url      = get_option( 'sman1_spmb_guide_url', '' );
    $status_open    = get_option( 'sman1_spmb_status_open', 'Pendaftaran Dibuka' );
    $status_closed  = get_option( 'sman1_spmb_status_closed', 'Pendaftaran Ditutup' );
    $features_raw   = get_option( 'sman1_spmb_features', "Proses pendaftaran 100% online\nSeleksi transparan dan akuntabel\nBeasiswa dan program unggulan tersedia\nKonfirmasi pendaftaran via WhatsApp" );

    // Determine current status for preview
    $today = current_time( 'Y-m-d' );
    if ( $reg_end && $today > $reg_end ) {
        $preview_status = '<span style="color:#d63638;">● ' . esc_html( $status_closed ) . '</span>';
    } elseif ( $reg_start && $today < $reg_start ) {
        $preview_status = '<span style="color:#dba617;">● Segera Dibuka</span>';
    } else {
        $preview_status = '<span style="color:#00a32a;">● ' . esc_html( $status_open ) . '</span>';
    }
    ?>
    <div class="wrap" id="spmb-admin-wrap">
        <h1 style="display:flex;align-items:center;gap:.5rem;">
            <span class="dashicons dashicons-welcome-write-blog" style="font-size:1.6rem;margin-top:2px;color:#9E1B1E;"></span>
            Pengaturan SPMB
            <small style="font-size:.65em;font-weight:400;color:#666;">(Sistem Penerimaan Murid Baru)</small>
        </h1>

        <p style="color:#666;margin:-.25rem 0 1.5rem;">
            Kelola semua pengaturan terkait penerimaan murid baru yang tampil di halaman beranda.
        </p>

        <?php settings_errors( 'sman1_spmb_group' ); ?>

        <form method="post" action="options.php">
            <?php settings_fields( 'sman1_spmb_group' ); ?>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;max-width:1060px;">

                <!-- ── LEFT: Tanggal & Waktu ── -->
                <div style="background:#fff;border:1px solid #c3c4c7;border-radius:8px;padding:20px 24px;">
                    <h3 style="margin:0 0 16px;padding-bottom:10px;border-bottom:1px solid #eee;font-size:13px;text-transform:uppercase;letter-spacing:.04em;color:#1d2327;">
                        📅 Tanggal &amp; Waktu
                    </h3>
                    <table class="form-table" role="presentation" style="margin:0;">
                        <tr>
                            <th scope="row" style="width:40%;"><label for="sman1_spmb_year">Tahun Ajaran</label></th>
                            <td>
                                <input type="text"
                                    id="sman1_spmb_year"
                                    name="sman1_spmb_year"
                                    value="<?php echo esc_attr( $year ); ?>"
                                    class="regular-text"
                                    placeholder="2026/2027">
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="sman1_spmb_target_date">Target Countdown</label></th>
                            <td>
                                <input type="datetime-local"
                                    id="sman1_spmb_target_date"
                                    name="sman1_spmb_target_date"
                                    value="<?php echo esc_attr( $target_date ); ?>"
                                    class="regular-text">
                                <p class="description">Tanggal &amp; jam mulai pendaftaran — countdown menghitung mundur ke sini.</p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="sman1_spmb_reg_start">Tanggal Buka</label></th>
                            <td>
                                <input type="date"
                                    id="sman1_spmb_reg_start"
                                    name="sman1_spmb_reg_start"
                                    value="<?php echo esc_attr( $reg_start ); ?>">
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="sman1_spmb_reg_end">Tanggal Tutup</label></th>
                            <td>
                                <input type="date"
                                    id="sman1_spmb_reg_end"
                                    name="sman1_spmb_reg_end"
                                    value="<?php echo esc_attr( $reg_end ); ?>">
                                <p class="description">Setelah tanggal ini tombol daftar akan dinonaktifkan otomatis.</p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="sman1_spmb_quota">Kuota Siswa</label></th>
                            <td>
                                <input type="number"
                                    id="sman1_spmb_quota"
                                    name="sman1_spmb_quota"
                                    value="<?php echo esc_attr( $quota ); ?>"
                                    class="small-text"
                                    min="1"
                                    placeholder="360">
                                <span class="description"> siswa</span>
                            </td>
                        </tr>
                    </table>
                </div>

                <!-- ── RIGHT: Tautan & Status ── -->
                <div style="background:#fff;border:1px solid #c3c4c7;border-radius:8px;padding:20px 24px;">
                    <h3 style="margin:0 0 16px;padding-bottom:10px;border-bottom:1px solid #eee;font-size:13px;text-transform:uppercase;letter-spacing:.04em;color:#1d2327;">
                        🔗 Tautan &amp; Status
                    </h3>
                    <table class="form-table" role="presentation" style="margin:0;">
                        <tr>
                            <th scope="row" style="width:40%;"><label for="sman1_spmb_url">Link Pendaftaran</label></th>
                            <td>
                                <input type="url"
                                    id="sman1_spmb_url"
                                    name="sman1_spmb_url"
                                    value="<?php echo esc_attr( $spmb_url ); ?>"
                                    class="large-text"
                                    placeholder="https://spmb.example.com/">
                                <p class="description">URL sistem pendaftaran SPMB online (tombol <em>Daftar SPMB Online</em>).</p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="sman1_spmb_guide_url">Link Panduan</label></th>
                            <td>
                                <input type="url"
                                    id="sman1_spmb_guide_url"
                                    name="sman1_spmb_guide_url"
                                    value="<?php echo esc_attr( $guide_url ); ?>"
                                    class="large-text"
                                    placeholder="https://...panduan.pdf">
                                <p class="description">URL PDF atau halaman panduan pendaftaran (tombol <em>Unduh Panduan</em>).</p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="sman1_spmb_status_open">Label Saat Buka</label></th>
                            <td>
                                <input type="text"
                                    id="sman1_spmb_status_open"
                                    name="sman1_spmb_status_open"
                                    value="<?php echo esc_attr( $status_open ); ?>"
                                    class="regular-text">
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="sman1_spmb_status_closed">Label Saat Tutup</label></th>
                            <td>
                                <input type="text"
                                    id="sman1_spmb_status_closed"
                                    name="sman1_spmb_status_closed"
                                    value="<?php echo esc_attr( $status_closed ); ?>"
                                    class="regular-text">
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">Status Sekarang</th>
                            <td>
                                <strong><?php echo $preview_status; ?></strong>
                                <p class="description">Dihitung berdasarkan Tanggal Buka/Tutup di atas.</p>
                            </td>
                        </tr>
                    </table>
                </div>
            </div><!-- /grid -->

            <!-- ── Full-width: Feature Bullets ── -->
            <div style="background:#fff;border:1px solid #c3c4c7;border-radius:8px;padding:20px 24px;margin-top:20px;max-width:1060px;">
                <h3 style="margin:0 0 16px;padding-bottom:10px;border-bottom:1px solid #eee;font-size:13px;text-transform:uppercase;letter-spacing:.04em;color:#1d2327;">
                    ✅ Poin Keunggulan (muncul sebagai checklist di beranda)
                </h3>
                <table class="form-table" role="presentation" style="margin:0;">
                    <tr>
                        <th scope="row" style="width:20%;"><label for="sman1_spmb_features">Keunggulan</label></th>
                        <td>
                            <textarea
                                id="sman1_spmb_features"
                                name="sman1_spmb_features"
                                rows="6"
                                class="large-text"
                                placeholder="Proses pendaftaran 100% online&#10;Seleksi transparan dan akuntabel&#10;Beasiswa dan program unggulan tersedia&#10;Konfirmasi pendaftaran via WhatsApp"><?php echo esc_textarea( $features_raw ); ?></textarea>
                            <p class="description">Tulis <strong>satu poin per baris</strong>. Maksimal 5 baris untuk tampilan terbaik.</p>
                        </td>
                    </tr>
                </table>
            </div>

            <?php submit_button( 'Simpan Pengaturan SPMB', 'primary large', 'submit', true, array( 'style' => 'margin-top:16px;' ) ); ?>
        </form>

        <!-- Info box -->
        <div style="max-width:1060px;background:#f0f6fc;border-left:4px solid #2271b1;padding:16px 20px;border-radius:0 8px 8px 0;margin-top:8px;">
            <strong>ℹ️ Cara kerja status otomatis:</strong>
            <ul style="margin:8px 0 0 20px;line-height:1.8;">
                <li>Hari ini <strong>sebelum</strong> Tanggal Buka → badge <em>Segera Dibuka</em> (kuning) + countdown aktif</li>
                <li>Hari ini <strong>antara</strong> Tanggal Buka–Tutup → badge <em><?php echo esc_html( $status_open ); ?></em> (hijau) + tombol aktif</li>
                <li>Hari ini <strong>setelah</strong> Tanggal Tutup → badge <em><?php echo esc_html( $status_closed ); ?></em> (merah) + tombol nonaktif</li>
                <li>Jika Tanggal Buka/Tutup <em>dikosongkan</em> → countdown &amp; tombol selalu aktif</li>
            </ul>
        </div>
    </div>
    <?php
}
