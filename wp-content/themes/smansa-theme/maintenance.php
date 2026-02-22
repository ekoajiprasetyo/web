<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sedang Maintenance – SMAN 1 Purwokerto</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;800&family=Inter:wght@300;400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
  :root {
    --primary:      #9E1B1E;
    --primary-dark: #7a1417;
    --accent:       #D4AF37;
    --accent-light: #F4CF5D;
    --white:        #ffffff;
  }
  * { margin: 0; padding: 0; box-sizing: border-box; }

  body {
    font-family: 'Inter', sans-serif;
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #0d0d0d 0%, #1a0508 45%, #0d0508 100%);
    color: var(--white);
    overflow: hidden;
    padding: 2rem 1.5rem;
    text-align: center;
  }

  /* ── Decorative circles ── */
  .bg-circle {
    position: fixed;
    border-radius: 50%;
    pointer-events: none;
    opacity: .06;
  }
  .bg-circle-1 { width: 700px; height: 700px; background: radial-gradient(circle, var(--primary), transparent 70%); top: -250px; left: -200px; }
  .bg-circle-2 { width: 500px; height: 500px; background: radial-gradient(circle, var(--accent),  transparent 70%); bottom: -150px; right: -150px; opacity: .04; }

  /* ── Logo ── */
  .maint-logo {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 3rem;
    text-decoration: none;
  }
  .maint-logo img { width: 56px; height: 56px; object-fit: contain; filter: drop-shadow(0 0 12px rgba(212,175,55,.4)); }
  .maint-logo-text { text-align: left; }
  .maint-logo-primary   { font-family: 'Montserrat', sans-serif; font-weight: 800; font-size: 1.1rem; color: var(--white); }
  .maint-logo-secondary { font-size: .85rem; color: rgba(255,255,255,.6); font-weight: 500; }

  /* ── Icon gears ── */
  .maint-icon-wrap {
    position: relative;
    width: 110px;
    height: 110px;
    margin: 0 auto 2.5rem;
  }
  .maint-icon-wrap .fa-gear {
    position: absolute;
    color: var(--accent);
    animation: spin 8s linear infinite;
  }
  .maint-icon-wrap .fa-gear:first-child { font-size: 5rem; top: 0; left: 0; }
  .maint-icon-wrap .fa-gear:last-child  { font-size: 2.5rem; bottom: 0; right: 0; animation-direction: reverse; animation-duration: 4s; color: rgba(212,175,55,.5); }
  @keyframes spin { to { transform: rotate(360deg); } }

  /* ── Heading & text ── */
  h1 {
    font-family: 'Montserrat', sans-serif;
    font-size: clamp(1.8rem, 5vw, 2.8rem);
    font-weight: 800;
    margin-bottom: .75rem;
    letter-spacing: -.5px;
  }
  h1 span { color: var(--accent); }
  .maint-desc {
    font-size: 1.05rem;
    color: rgba(255,255,255,.7);
    line-height: 1.8;
    max-width: 520px;
    margin: 0 auto 2.5rem;
  }

  /* ── Info pills ── */
  .maint-pills {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: .75rem;
    margin-bottom: 3rem;
    max-width: 480px;
    margin-left: auto;
    margin-right: auto;
  }
  .maint-pill {
    display: inline-flex;
    align-items: center;
    gap: .5rem;
    background: rgba(255,255,255,.06);
    border: 1px solid rgba(255,255,255,.1);
    border-radius: 999px;
    padding: .5rem 1.2rem;
    font-size: .85rem;
    backdrop-filter: blur(8px);
    white-space: nowrap;
  }
  .maint-pill i { color: var(--accent); font-size: .8rem; }

  /* ── Admin link — removed for security ── */

  /* ── Footer ── */
  .maint-footer {
    position: fixed;
    bottom: 1.5rem;
    left: 0; right: 0;
    text-align: center;
    font-size: .8rem;
    color: rgba(255,255,255,.35);
  }
  .maint-footer a { color: var(--accent); text-decoration: none; }
  .maint-footer a:hover { text-decoration: underline; }

  @media (max-width: 480px) {
    .maint-logo { flex-direction: column; text-align: center; }
    .maint-logo-text { text-align: center; }
    /* Force 2 pills on first row, 1 centered on second row */
    .maint-pills {
      display: grid;
      grid-template-columns: 1fr 1fr;
      max-width: 340px;
    }
    .maint-pill:last-child {
      grid-column: 1 / -1;
      justify-self: center;
    }
  }
</style>
</head>
<body>

<div class="bg-circle bg-circle-1"></div>
<div class="bg-circle bg-circle-2"></div>

<?php
// Attempt to load WordPress functions for logo/site name
$logo_url = '';
$site_name = 'SMAN 1 Purwokerto';
if (function_exists('get_custom_logo') && has_custom_logo()) {
    $logo_id  = get_theme_mod('custom_logo');
    $logo_src = wp_get_attachment_image_src($logo_id, 'full');
    if ($logo_src) $logo_url = $logo_src[0];
}
if (!$logo_url) {
    $logo_url = get_template_directory_uri() . '/images/logo.png';
}
?>

<a href="<?php echo esc_url(home_url('/')); ?>" class="maint-logo">
  <img src="<?php echo esc_url($logo_url); ?>" alt="Logo SMAN 1 Purwokerto">
  <div class="maint-logo-text">
    <div class="maint-logo-primary">SMAN 1</div>
    <div class="maint-logo-secondary">Purwokerto</div>
  </div>
</a>

<div class="maint-icon-wrap">
  <i class="fas fa-gear"></i>
  <i class="fas fa-gear"></i>
</div>

<h1>Website Sedang <span>Maintenance</span></h1>
<p class="maint-desc">
  Kami sedang melakukan pembaruan dan peningkatan website untuk memberikan
  pengalaman yang lebih baik bagi Anda. Mohon kunjungi kembali dalam beberapa saat.
</p>

<div class="maint-pills">
  <span class="maint-pill"><i class="fas fa-shield-halved"></i> Pembaruan Keamanan</span>
  <span class="maint-pill"><i class="fas fa-rocket"></i> Peningkatan Performa</span>
  <span class="maint-pill"><i class="fas fa-wrench"></i> Perbaikan Sistem</span>
</div>

<div class="maint-footer">
  &copy; <?php echo date('Y'); ?> 
  <a href="<?php echo esc_url(home_url('/')); ?>"><?php echo esc_html($site_name); ?></a>
  — All rights reserved.
</div>

</body>
</html>
