$oldPattern = 'hamburger.addEventListener\(''click', function \(\) \{[\s\S]*?\}\);[\s\S]*?\}\);'
$newCode = @'
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
                const toggleBtn = document.createElement('span');
                toggleBtn.className = 'dropdown-toggle';
                toggleBtn.innerHTML = '<i class="fas fa-chevron-down"></i>';
                toggleBtn.setAttribute('aria-label', 'Toggle dropdown');
                toggleBtn.setAttribute('tabindex', '0');
                
                if (link) {
                    link.parentNode.insertBefore(toggleBtn, link.nextSibling);
                }

                toggleBtn.addEventListener('click', function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    navItems.forEach(otherItem => {
                        if (otherItem !== item) {
                            otherItem.classList.remove('active');
                            const otherToggle = otherItem.querySelector('.dropdown-toggle');
                            if (otherToggle) otherToggle.classList.remove('active');
                        }
                    });
                    
                    item.classList.toggle('active');
                    toggleBtn.classList.toggle('active');
                });

                toggleBtn.addEventListener('keydown', function (e) {
                    if (e.key === 'Enter' || e.key === ' ') {
                        e.preventDefault();
                        toggleBtn.click();
                    }
                });
            }
        });

        navMenu.querySelectorAll('.nav-link').forEach(link => {
            link.addEventListener('click', function (e) {
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

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && navMenu.classList.contains('active')) {
                hamburger.classList.remove('active');
                navMenu.classList.remove('active');
                mobileOverlay.classList.remove('active');
                document.body.style.overflow = 'auto';
            }
        });
'@

$content = Get-Content 'D:\xampp\htdocs\web\wp-content\themes\smansa-theme\js\main.js' -Raw

# Add mobileOverlay creation after navMenu
$content = $content -replace '(const navMenu = document\.getElementById\(''''navMenu''''\);)', "`$1`n`n    // Create mobile overlay`n    const mobileOverlay = document.createElement('div');`n    mobileOverlay.className = 'mobile-overlay';`n    document.body.appendChild(mobileOverlay);"

$content | Set-Content 'D:\xampp\htdocs\web\wp-content\themes\smansa-theme\js\main.js'
Write-Host "Phase 1 done - added mobile overlay"