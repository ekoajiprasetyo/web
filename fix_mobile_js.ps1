$content = Get-Content 'D:\xampp\htdocs\web\wp-content\themes\smansa-theme\js\main.js' -Raw

# Replace the mobile menu section with improved version
$oldMobileJS = @'
    // ==================== MOBILE MENU ====================
    const hamburger = document.getElementById('hamburger');
    const navMenu = document.getElementById('navMenu');

    if (hamburger && navMenu) {
        hamburger.addEventListener('click', function () {
            this.classList.toggle('active');
            navMenu.classList.toggle('active');
            document.body.style.overflow = navMenu.classList.contains('active') ? 'hidden' : 'auto';
        });

        // Close menu when clicking outside
        document.addEventListener('click', function (e) {
            if (!hamburger.contains(e.target) && !navMenu.contains(e.target)) {
                hamburger.classList.remove('active');
                navMenu.classList.remove('active');
                document.body.style.overflow = 'auto';
            }
        });

        // Close menu on link click
        navMenu.querySelectorAll('.nav-link').forEach(link => {
            link.addEventListener('click', function (e) {
                if (!this.nextElementSibling) {
                    hamburger.classList.remove('active');
                    navMenu.classList.remove('active');
                    document.body.style.overflow = 'auto';
                }
            });
        });
    }
'@

$newMobileJS = @'
    // ==================== MOBILE MENU ====================
    const hamburger = document.getElementById('hamburger');
    const navMenu = document.getElementById('navMenu');

    // Create mobile overlay
    const mobileOverlay = document.createElement('div');
    mobileOverlay.className = 'mobile-overlay';
    document.body.appendChild(mobileOverlay);

    if (hamburger && navMenu) {
        hamburger.addEventListener('click', function () {
            this.classList.toggle('active');
            navMenu.classList.toggle('active');
            mobileOverlay.classList.toggle('active');
            document.body.style.overflow = navMenu.classList.contains('active') ? 'hidden' : 'auto';
        });

        // Close menu when clicking overlay
        mobileOverlay.addEventListener('click', function () {
            hamburger.classList.remove('active');
            navMenu.classList.remove('active');
            mobileOverlay.classList.remove('active');
            document.body.style.overflow = 'auto';
        });

        // Close menu when clicking outside
        document.addEventListener('click', function (e) {
            if (!hamburger.contains(e.target) && !navMenu.contains(e.target) && !mobileOverlay.contains(e.target)) {
                hamburger.classList.remove('active');
                navMenu.classList.remove('active');
                mobileOverlay.classList.remove('active');
                document.body.style.overflow = 'auto';
            }
        });

        // Add dropdown toggle buttons for mobile
        const navItems = navMenu.querySelectorAll('.nav-item');
        navItems.forEach(item => {
            const link = item.querySelector('.nav-link');
            const dropdown = item.querySelector('.dropdown-menu, .mega-menu');
            
            if (dropdown) {
                // Create toggle button
                const toggleBtn = document.createElement('span');
                toggleBtn.className = 'dropdown-toggle';
                toggleBtn.innerHTML = '<i class="fas fa-chevron-down"></i>';
                toggleBtn.setAttribute('aria-label', 'Toggle dropdown');
                toggleBtn.setAttribute('tabindex', '0');
                
// Insert after the link
                if (link) {
                    link.parentNode.insertBefore(toggleBtn, link.nextSibling);
                }

                // Toggle dropdown on click
                toggleBtn.addEventListener('click', function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    // Close other dropdowns
                    navItems.forEach(otherItem => {
                        if (otherItem !== item) {
                            otherItem.classList.remove('active');
                            const otherToggle = otherItem.querySelector('.dropdown-toggle');
                            if (otherToggle) otherToggle.classList.remove('active');
                        }
                    });
                    
                    // Toggle current dropdown
                    item.classList.toggle('active');
                    toggleBtn.classList.toggle('active');
                });

                // Handle keyboard navigation
                toggleBtn.addEventListener('keydown', function (e) {
                    if (e.key === 'Enter' || e.key === ' ') {
                        e.preventDefault();
                        toggleBtn.click();
                    }
                });
            }
        });

        // Close menu on link click (for non-dropdown links)
        navMenu.querySelectorAll('.nav-link').forEach(link => {
            link.addEventListener('click', function (e) {
                // Only close if it is not a parent of dropdown
                if (!this.nextElementSibling || !this.nextElementSibling.classList.contains('dropdown-toggle')) {
                    if (window.innerWidth <= 992) {
                        hamburger.classList.remove('active');
                        navMenu.classList.remove('active');
                        mobileOverlay.classList.remove('active');
                        document.body.style.overflow = 'auto';
                    }
                }
            });
        });

        // Close on Escape key
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && navMenu.classList.contains('active')) {
                hamburger.classList.remove('active');
                navMenu.classList.remove('active');
                mobileOverlay.classList.remove('active');
                document.body.style.overflow = 'auto';
            }
        });
    }
'@

$content = $content -replace [regex]::Escape($oldMobileJS), $newMobileJS

$content | Set-Content 'D:\xampp\htdocs\web\wp-content\themes\smansa-theme\js\main.js'
Write-Host "Mobile Menu JavaScript Fixed"