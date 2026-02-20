$content = Get-Content 'D:\xampp\htdocs\web\wp-content\themes\smansa-theme\css\style.css' -Raw

# Fix mobile menu - Add proper mobile menu styles
$oldMobileMenu = @'
@media (max-width: 992px) {
  .nav-menu {
    position: fixed;
    top: 0;
    right: -100%;
    width: 300px;
    height: 100vh;
    background: var(--white);
    flex-direction: column;
    align-items: flex-start;
    padding: 6rem 2rem 2rem;
    box-shadow: -5px 0 30px rgba(0, 0, 0, 0.1);
    transition: var(--transition);
    overflow-y: auto;
    z-index: 999;
  }

  .nav-menu.active {
    right: 0;
  }

  .nav-link {
    width: 100%;
    padding: 1rem 0;
    font-size: 1.1rem;
    border-bottom: 1px solid var(--gray-100);
    justify-content: space-between;
  }

  .nav-link:last-child {
    border-bottom: none;
  }

  .hamburger {
    display: flex;
    z-index: 1000;
  }

  /* Mega Menu Mobile Logic */
  .mega-menu,
  .dropdown-menu {
    position: static;
    transform: none;
    width: 100%;
    opacity: 1;
    visibility: visible;
    box-shadow: none;
    display: none;
    padding: 0;
    border: none;
  }

  .nav-item:hover .mega-menu,
  .nav-item:hover .dropdown-menu {
    display: none;
  }

  /* Toggle via JS (add .active class to .nav-item) */
  .nav-item.active .mega-menu,
  .nav-item.active .dropdown-menu {
    display: block;
    animation: fadeIn 0.3s ease;
  }
'@

$newMobileMenu = @'
@media (max-width: 992px) {
  .nav-menu {
    position: fixed;
    top: 0;
    right: -100%;
    width: 320px;
    max-width: 85vw;
    height: 100vh;
    background: var(--white);
    flex-direction: column;
    align-items: flex-start;
    padding: 5rem 1.5rem 2rem;
    box-shadow: -5px 0 30px rgba(0, 0, 0, 0.15);
    transition: right 0.3s ease;
    overflow-y: auto;
    z-index: 9998;
  }

  .nav-menu.active {
    right: 0;
  }

  /* Mobile menu overlay */
  .mobile-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.5);
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s ease;
    z-index: 9997;
  }

  .mobile-overlay.active {
    opacity: 1;
    visibility: visible;
  }

  .nav-item {
    width: 100%;
    border-bottom: 1px solid var(--gray-100);
  }

  .nav-link {
    width: 100%;
    padding: 0.875rem 0;
    font-size: 1rem;
    border-bottom: none;
    justify-content: space-between;
    border-radius: 0;
  }

  .nav-link:last-child {
    border-bottom: none;
  }

  .hamburger {
    display: flex;
    z-index: 10000;
    cursor: pointer;
  }

  /* Mega Menu Mobile Logic */
  .mega-menu,
  .dropdown-menu {
    position: static;
    transform: none;
    width: 100%;
    opacity: 1;
    visibility: visible;
    box-shadow: none;
    display: none;
    padding: 0 0 0 1rem;
    border: none;
    background: var(--gray-50);
    border-radius: 0;
  }

  .nav-item:hover .mega-menu,
  .nav-item:hover .dropdown-menu {
    display: none;
  }

  /* Toggle via JS (add .active class to .nav-item) */
  .nav-item.active > .mega-menu,
  .nav-item.active > .dropdown-menu {
    display: block;
    animation: fadeIn 0.3s ease;
  }

  /* Mobile dropdown toggle arrow */
  .nav-item .dropdown-toggle {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    transition: all 0.3s ease;
    cursor: pointer;
    background: rgba(158, 27, 30, 0.05);
  }

  .nav-item .dropdown-toggle.active {
    transform: rotate(180deg);
    background: var(--primary);
    color: white;
  }

  .nav-item .dropdown-toggle i {
    font-size: 0.75rem;
    color: var(--gray-600);
  }

  .nav-item.active > .dropdown-toggle i {
    color: white;
  }
'@

$content = $content -replace [regex]::Escape($oldMobileMenu), $newMobileMenu

$content | Set-Content 'D:\xampp\htdocs\web\wp-content\themes\smansa-theme\css\style.css'
Write-Host "Mobile Menu CSS Fixed"