/* =====================================================
   SMAN 1 PURWOKERTO - MAIN JAVASCRIPT
   Interactive Features & Animations
   ===================================================== */

document.addEventListener('DOMContentLoaded', function () {

    // ==================== PRELOADER ====================
    const preloader = document.getElementById('preloader');

    window.addEventListener('load', function () {
        setTimeout(() => {
            preloader.classList.add('hidden');
            document.body.style.overflow = ''; // clear inline lock; CSS overflow-x:hidden stays in effect
        }, 2000);
    });

    // ==================== INITIALIZE AOS ====================
    AOS.init({
        duration: 800,
        easing: 'ease-out-cubic',
        once: true,
        offset: 50
    });

    // ==================== HEADER SCROLL EFFECT ====================
    const header = document.getElementById('header');
    let lastScroll = 0;

    window.addEventListener('scroll', function () {
        const currentScroll = window.pageYOffset;

        if (currentScroll > 50) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }

        lastScroll = currentScroll;
    });

    // Initial check in case of reload on scrolled page
    if (window.pageYOffset > 50) {
        header.classList.add('scrolled');
    }

    // ==================== MOBILE MENU ====================
    const hamburger = document.getElementById('hamburger');
    const navMenu = document.getElementById('navMenu');
    const navOverlay = document.getElementById('navOverlay');
    const navCloseBtn = document.getElementById('navCloseBtn');

    function openMobileMenu() {
        if (!hamburger || !navMenu) return;
        // Position menu directly below sticky navbar using getBoundingClientRect (viewport-relative)
        const headerRect = header.getBoundingClientRect();
        const menuTop = headerRect.bottom;
        navMenu.style.top = menuTop + 'px';
        navMenu.style.height = (window.innerHeight - menuTop) + 'px';
        if (navOverlay) navOverlay.style.top = menuTop + 'px';
        hamburger.classList.add('active');
        navMenu.classList.add('active');
        if (navOverlay) navOverlay.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeMobileMenu() {
        if (!hamburger || !navMenu) return;
        hamburger.classList.remove('active');
        navMenu.classList.remove('active');
        if (navOverlay) navOverlay.classList.remove('active');
        document.body.style.overflow = '';
        navMenu.style.top = '';
        navMenu.style.height = '';
        if (navOverlay) navOverlay.style.top = '';
        closeAllDropdowns();
    }

    if (hamburger) {
        hamburger.addEventListener('click', function (e) {
            e.stopPropagation();
            if (navMenu.classList.contains('active')) {
                closeMobileMenu();
            } else {
                openMobileMenu();
            }
        });
    }

    // Close via in-panel close button
    if (navCloseBtn) {
        navCloseBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            closeMobileMenu();
        });
    }

    // Close via overlay click
    if (navOverlay) {
        navOverlay.addEventListener('click', closeMobileMenu);
    }

    // ==================== DROPDOWN TOGGLE (click-based, all screen sizes) ====================
    function initDropdownToggles() {
        const navItems = document.querySelectorAll('.nav-item.has-dropdown, .nav-item.has-mega-menu');

        navItems.forEach(function (item) {
            const link = item.querySelector(':scope > .nav-link');
            if (!link) return;

            // Remove any old listener by cloning
            const newLink = link.cloneNode(true);
            link.parentNode.replaceChild(newLink, link);

            newLink.addEventListener('click', function (e) {
                const dropdown = item.querySelector(':scope > .dropdown-menu, :scope > .mega-menu');
                if (!dropdown) return;

                e.preventDefault();
                e.stopPropagation();

                const isOpen = item.classList.contains('active');

                // Close all siblings first
                const siblings = item.parentElement ? item.parentElement.querySelectorAll(':scope > .nav-item') : [];
                siblings.forEach(function (sib) {
                    if (sib !== item) {
                        sib.classList.remove('active');
                        const sibArrow = sib.querySelector(':scope > .nav-link .dropdown-icon');
                        if (sibArrow) sibArrow.style.transform = '';
                    }
                });

                item.classList.toggle('active', !isOpen);

                // Rotate dropdown arrow
                const arrow = newLink.querySelector('.dropdown-icon');
                if (arrow) {
                    arrow.style.transform = isOpen ? '' : 'rotate(180deg)';
                }
            });
        });
    }

    initDropdownToggles();

    // ---- Shared helper: close every open desktop dropdown ----
    function closeAllDropdowns() {
        document.querySelectorAll('.nav-item.active').forEach(function (item) {
            item.classList.remove('active');
            const arrow = item.querySelector(':scope > .nav-link .dropdown-icon');
            if (arrow) arrow.style.transform = '';
        });
    }

    // Close any open dropdown when clicking outside the navbar
    document.addEventListener('click', function (e) {
        if (!e.target.closest('#navMenu')) {
            closeAllDropdowns();
        }
    });

    // Close all dropdowns on scroll (sticky header keeps panel floating over hero)
    window.addEventListener('scroll', closeAllDropdowns, { passive: true });

    // Close all dropdowns on Escape key
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') closeAllDropdowns();
    });

    // Close mobile menu on nav-link click (non-dropdown items)
    if (navMenu) {
        navMenu.querySelectorAll('.nav-link').forEach(function (link) {
            link.addEventListener('click', function () {
                // Only close whole menu if this link leads to a real page (no sub-menu)
                const parentItem = this.closest('.nav-item');
                const hasDropdown = parentItem && (parentItem.classList.contains('has-dropdown') || parentItem.classList.contains('has-mega-menu'));
                if (!hasDropdown && window.innerWidth <= 992) {
                    closeMobileMenu();
                }
            });
        });
    }

    // ==================== SEARCH OVERLAY ====================
    const searchBtn = document.getElementById('searchBtn');
    const searchOverlay = document.getElementById('searchOverlay');
    const searchClose = document.getElementById('searchClose');

    if (searchBtn && searchOverlay && searchClose) {
        searchBtn.addEventListener('click', function () {
            searchOverlay.classList.add('active');
            document.body.style.overflow = 'hidden';
            searchOverlay.querySelector('input').focus();
        });

        searchClose.addEventListener('click', function () {
            searchOverlay.classList.remove('active');
            document.body.style.overflow = ''; // clear inline lock; CSS overflow-x:hidden stays in effect
        });

        searchOverlay.addEventListener('click', function (e) {
            if (e.target === this) {
                this.classList.remove('active');
                document.body.style.overflow = ''; // clear inline lock; CSS overflow-x:hidden stays in effect
            }
        });

        // Close on Escape key
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && searchOverlay.classList.contains('active')) {
                searchOverlay.classList.remove('active');
                document.body.style.overflow = ''; // clear inline lock; CSS overflow-x:hidden stays in effect
            }
        });
    }

    // ==================== HERO SLIDER ====================
    const heroSlides = document.querySelectorAll('.hero-slide');
    const heroDots = document.querySelectorAll('.hero-dot');
    const heroPrev = document.getElementById('heroPrev');
    const heroNext = document.getElementById('heroNext');
    let currentSlide = 0;
    let slideInterval;

    function showSlide(index) {
        heroSlides.forEach((slide, i) => {
            slide.classList.remove('active');
            slide.setAttribute('aria-hidden', 'true');
            if (heroDots[i]) {
                // Remove and re-add to restart the CSS animation
                heroDots[i].classList.remove('active');
            }
        });

        currentSlide = index;
        if (currentSlide >= heroSlides.length) currentSlide = 0;
        if (currentSlide < 0) currentSlide = heroSlides.length - 1;

        heroSlides[currentSlide].classList.add('active');
        heroSlides[currentSlide].setAttribute('aria-hidden', 'false');
        if (heroDots[currentSlide]) {
            // Force reflow so the ::after animation restarts from zero
            void heroDots[currentSlide].offsetWidth;
            heroDots[currentSlide].classList.add('active');
        }
    }

    function nextSlide() {
        showSlide(currentSlide + 1);
    }

    function prevSlide() {
        showSlide(currentSlide - 1);
    }

    function startSlideshow() {
        if (heroSlides.length <= 1) return;
        slideInterval = setInterval(nextSlide, 10000);
    }

    function stopSlideshow() {
        clearInterval(slideInterval);
    }

    if (heroSlides.length > 0) {
        showSlide(0);

        if (heroNext) {
            heroNext.addEventListener('click', function () {
                stopSlideshow();
                nextSlide();
                startSlideshow();
            });
        }

        if (heroPrev) {
            heroPrev.addEventListener('click', function () {
                stopSlideshow();
                prevSlide();
                startSlideshow();
            });
        }

        heroDots.forEach((dot, index) => {
            dot.addEventListener('click', function () {
                stopSlideshow();
                showSlide(index);
                startSlideshow();
            });
        });

        startSlideshow();
    }

    // ==================== STATS COUNTER ====================
    const statNumbers = document.querySelectorAll('.stat-number');

    function animateCounter(element) {
        const target = parseInt(element.getAttribute('data-count'));
        const duration = 2000;
        const step = target / (duration / 16);
        let current = 0;

        const timer = setInterval(() => {
            current += step;
            if (current >= target) {
                element.textContent = target;
                clearInterval(timer);
            } else {
                element.textContent = Math.floor(current);
            }
        }, 16);
    }

    // Intersection Observer for stats
    const statsObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                animateCounter(entry.target);
                statsObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.5 });

    statNumbers.forEach(stat => statsObserver.observe(stat));

    // ==================== GALLERY FILTER ====================
    // Handled by inline script in front-page.php (homeGalleryFilter)

    function updateGalleryResultCount() {
        const countEl = document.getElementById('galleryResultCount');
        if (!countEl) return;
        const visible = document.querySelectorAll('.gallery-page-item:not(.is-hidden)').length;
        const total   = document.querySelectorAll('.gallery-page-item').length;
        countEl.innerHTML = `Menampilkan <strong>${visible}</strong> dari ${total} foto`;
    }

    // ==================== GALLERY PAGE SEARCH ====================
    // Handled by inline script in page-galeri.php (galleryPageFilter IIFE)

    // ==================== GALLERY LIGHTBOX ====================
    (function initLightbox() {
        const lightbox    = document.getElementById('galleryLightbox');
        if (!lightbox) return;

        const backdrop    = document.getElementById('lightboxBackdrop');
        const closeBtn    = document.getElementById('lightboxClose');
        const prevBtn     = document.getElementById('lightboxPrev');
        const nextBtn     = document.getElementById('lightboxNext');
        const img         = document.getElementById('lightboxImg');
        const spinner     = document.getElementById('lightboxSpinner');
        const captionEl   = document.getElementById('lightboxCaption');
        const counterEl   = document.getElementById('lightboxCounter');
        const thumbsEl    = document.getElementById('lightboxThumbs');

        // Determine data source: page gallery or home gallery
        let items = [];
        if (window.pageGalleryItems && window.pageGalleryItems.length) {
            items = window._galleryPageVisible || window.pageGalleryItems;
        } else if (window.homeGalleryItems && window.homeGalleryItems.length) {
            items = window.homeGalleryItems;
        }

        let currentIndex = 0;
        let isOpen = false;

        // Build thumbnail strip
        function buildThumbs() {
            if (!thumbsEl || items.length <= 1) return;
            thumbsEl.innerHTML = '';
            items.forEach((item, i) => {
                const t = document.createElement('div');
                t.className = 'lightbox-thumb';
                t.setAttribute('aria-label', `Foto ${i + 1}: ${item.caption}`);
                t.setAttribute('tabindex', '0');
                const tImg = document.createElement('img');
                tImg.src = item.src;
                tImg.alt = item.caption;
                tImg.loading = 'lazy';
                t.appendChild(tImg);
                t.addEventListener('click', () => goTo(i));
                t.addEventListener('keydown', e => { if (e.key === 'Enter') goTo(i); });
                thumbsEl.appendChild(t);
            });
        }

        function updateThumbs(index) {
            if (!thumbsEl) return;
            thumbsEl.querySelectorAll('.lightbox-thumb').forEach((t, i) => {
                t.classList.toggle('active', i === index);
            });
            // Scroll active thumb into view
            const active = thumbsEl.children[index];
            if (active) {
                active.scrollIntoView({ inline: 'center', behavior: 'smooth', block: 'nearest' });
            }
        }

        function showItem(index, direction) {
            if (!items.length) return;
            currentIndex = (index + items.length) % items.length;
            const item = items[currentIndex];

            // Show spinner, hide image
            if (spinner) { spinner.classList.add('is-visible'); }
            img.classList.add('is-loading');

            const newImg = new Image();
            newImg.onload = function () {
                img.src = newImg.src;
                img.alt = item.caption;
                img.classList.remove('is-loading');
                if (spinner) spinner.classList.remove('is-visible');
            };
            newImg.onerror = function () {
                img.src = item.src; // fallback direct
                img.classList.remove('is-loading');
                if (spinner) spinner.classList.remove('is-visible');
            };
            newImg.src = item.src;

            if (captionEl) captionEl.textContent = item.caption;
            if (counterEl) counterEl.textContent = `${currentIndex + 1} / ${items.length}`;

            updateThumbs(currentIndex);
        }

        function goTo(index) {
            showItem(index);
        }

        function openLightbox(index) {
            if (!lightbox) return;
            buildThumbs();
            lightbox.style.display = 'flex';
            // Force reflow then animate
            void lightbox.offsetWidth;
            lightbox.classList.add('is-visible');
            isOpen = true;
            document.body.style.overflow = 'hidden';
            showItem(index);
            // Focus close button for accessibility
            setTimeout(() => closeBtn && closeBtn.focus(), 50);
        }

        function closeLightbox(animate) {
            if (!isOpen) return;
            const container = lightbox.querySelector('.lightbox-container');
            if (container && animate !== false) {
                container.style.animation = 'lightboxOut 0.25s ease forwards';
                setTimeout(() => {
                    container.style.animation = '';
                    lightbox.classList.remove('is-visible');
                    lightbox.style.display = 'none';
                    isOpen = false;
                    document.body.style.overflow = '';
                }, 260);
            } else {
                lightbox.classList.remove('is-visible');
                lightbox.style.display = 'none';
                isOpen = false;
                document.body.style.overflow = '';
            }
        }

        // Attach click to all gallery items (home + page)
        function attachGalleryClicks() {
            const allItems = document.querySelectorAll('[data-lightbox-index]');
            allItems.forEach(el => {
                // Re-attach cleanly
                const clone = el.cloneNode(true);
                el.parentNode.replaceChild(clone, el);
                clone.addEventListener('click', function () {
                    const idx = parseInt(this.getAttribute('data-lightbox-index'), 10);
                    openLightbox(isNaN(idx) ? 0 : idx);
                });
                clone.addEventListener('keydown', function (e) {
                    if (e.key === 'Enter' || e.key === ' ') {
                        e.preventDefault();
                        const idx = parseInt(this.getAttribute('data-lightbox-index'), 10);
                        openLightbox(isNaN(idx) ? 0 : idx);
                    }
                });
            });

            // Sync filterBtns to also update visible items in lightbox (page gallery)
            const pageFilerBtns = document.querySelectorAll('.gallery-page-filter .filter-btn');
            if (pageFilerBtns.length) {
                pageFilerBtns.forEach(btn => {
                    btn.addEventListener('click', function () {
                        // Small delay to let inline IIFE apply visibility first
                        setTimeout(() => {
                            items = window._galleryPageVisible || window.pageGalleryItems || [];
                            buildThumbs();
                        }, 50);
                    });
                });
            }
        }

        closeBtn && closeBtn.addEventListener('click', () => closeLightbox(true));
        backdrop && backdrop.addEventListener('click', () => closeLightbox(true));
        prevBtn  && prevBtn.addEventListener('click', () => showItem(currentIndex - 1));
        nextBtn  && nextBtn.addEventListener('click', () => showItem(currentIndex + 1));

        // Keyboard
        document.addEventListener('keydown', function (e) {
            if (!isOpen) return;
            if (e.key === 'Escape')      closeLightbox(true);
            if (e.key === 'ArrowLeft')   showItem(currentIndex - 1);
            if (e.key === 'ArrowRight')  showItem(currentIndex + 1);
        });

        // Touch swipe
        let touchStartX = null;
        lightbox && lightbox.addEventListener('touchstart', e => {
            touchStartX = e.touches[0].clientX;
        }, { passive: true });
        lightbox && lightbox.addEventListener('touchend', e => {
            if (touchStartX === null) return;
            const dx = e.changedTouches[0].clientX - touchStartX;
            if (Math.abs(dx) > 50) {
                dx < 0 ? showItem(currentIndex + 1) : showItem(currentIndex - 1);
            }
            touchStartX = null;
        }, { passive: true });

        // Init
        attachGalleryClicks();

        // Expose for inline page scripts
        window._openGalleryLightbox = openLightbox;
    })();

    // ==================== SPMB COUNTDOWN TIMER ====================
    (function initSpmbCountdown() {
        const timerWrap = document.getElementById('spmbCountdown');
        if (!timerWrap) return;

        const cfg = window.spmbConfig || {};

        // If registration is already closed, show closed message
        if (cfg.isClosed) {
            timerWrap.innerHTML = '<p style="opacity:.7;font-size:.9rem;">Pendaftaran telah ditutup</p>';
            return;
        }

        // Resolve target date: from PHP config or fallback to 30 days from now
        let targetMs = cfg.targetDate && cfg.targetDate > 0
            ? cfg.targetDate
            : Date.now() + 30 * 24 * 60 * 60 * 1000;

        const daysEl    = document.getElementById('spmbDays');
        const hoursEl   = document.getElementById('spmbHours');
        const minutesEl = document.getElementById('spmbMinutes');
        const secondsEl = document.getElementById('spmbSeconds');

        if (!daysEl || !hoursEl || !minutesEl || !secondsEl) return;

        // Flip animation helper
        function flashFlip(el, newVal) {
            if (el.textContent !== newVal) {
                el.classList.add('spmb-flip');
                el.textContent = newVal;
                setTimeout(function () { el.classList.remove('spmb-flip'); }, 180);
            }
        }

        function tick() {
            const distance = targetMs - Date.now();

            if (distance <= 0) {
                clearInterval(countdownInterval);
                timerWrap.innerHTML = '<p style="opacity:.7;font-size:.9rem;color:#4ade80;">&#10003; Pendaftaran Sedang Dibuka!</p>';
                return;
            }

            const d = Math.floor(distance / 86400000);
            const h = Math.floor((distance % 86400000) / 3600000);
            const m = Math.floor((distance % 3600000) / 60000);
            const s = Math.floor((distance % 60000) / 1000);

            flashFlip(daysEl,    d.toString().padStart(2, '0'));
            flashFlip(hoursEl,   h.toString().padStart(2, '0'));
            flashFlip(minutesEl, m.toString().padStart(2, '0'));
            flashFlip(secondsEl, s.toString().padStart(2, '0'));
        }

        tick();
        const countdownInterval = setInterval(tick, 1000);
    })();

    // ==================== BACK TO TOP ====================
    const backToTop = document.getElementById('backToTop');

    if (backToTop) {
        window.addEventListener('scroll', function () {
            if (window.pageYOffset > 500) {
                backToTop.classList.add('visible');
            } else {
                backToTop.classList.remove('visible');
            }
        });

        backToTop.addEventListener('click', function () {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }

    // ==================== SMOOTH SCROLL ====================
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const href = this.getAttribute('href');
            if (href !== '#') {
                e.preventDefault();
                const target = document.querySelector(href);
                if (target) {
                    const headerHeight = header.offsetHeight;
                    const targetPosition = target.getBoundingClientRect().top + window.pageYOffset - headerHeight;

                    window.scrollTo({
                        top: targetPosition,
                        behavior: 'smooth'
                    });
                }
            }
        });
    });

    // ==================== LAZY LOAD IMAGES ====================
    const lazyImages = document.querySelectorAll('img[data-src]');

    const imageObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src;
                img.removeAttribute('data-src');
                imageObserver.unobserve(img);
            }
        });
    });

    lazyImages.forEach(img => imageObserver.observe(img));

    // ==================== PARALLAX EFFECT ====================
    const parallaxElements = document.querySelectorAll('[data-parallax]');

    window.addEventListener('scroll', function () {
        parallaxElements.forEach(element => {
            const speed = element.getAttribute('data-parallax') || 0.5;
            const yPos = -(window.pageYOffset * speed);
            element.style.transform = `translateY(${yPos}px)`;
        });
    });

    // ==================== ACHIEVEMENTS SLIDER ====================
    // Add touch/swipe support for mobile
    const achievementsSlider = document.querySelector('.achievements-slider');

    if (achievementsSlider) {
        let isDown = false;
        let startX;
        let scrollLeft;

        achievementsSlider.addEventListener('mousedown', (e) => {
            isDown = true;
            startX = e.pageX - achievementsSlider.offsetLeft;
            scrollLeft = achievementsSlider.scrollLeft;
        });

        achievementsSlider.addEventListener('mouseleave', () => {
            isDown = false;
        });

        achievementsSlider.addEventListener('mouseup', () => {
            isDown = false;
        });

        achievementsSlider.addEventListener('mousemove', (e) => {
            if (!isDown) return;
            e.preventDefault();
            const x = e.pageX - achievementsSlider.offsetLeft;
            const walk = (x - startX) * 2;
            achievementsSlider.scrollLeft = scrollLeft - walk;
        });
    }

    // ==================== FORM VALIDATION ====================
    const forms = document.querySelectorAll('form');

    forms.forEach(form => {
        form.addEventListener('submit', function (e) {
            const requiredFields = this.querySelectorAll('[required]');
            let isValid = true;

            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.classList.add('error');
                } else {
                    field.classList.remove('error');
                }
            });

            if (!isValid) {
                e.preventDefault();
            }
        });
    });

    // ==================== TYPING EFFECT ====================
    function typeWriter(element, text, speed = 50) {
        let i = 0;
        element.textContent = '';

        function type() {
            if (i < text.length) {
                element.textContent += text.charAt(i);
                i++;
                setTimeout(type, speed);
            }
        }

        type();
    }

    // ==================== UTILITY FUNCTIONS ====================

    // Debounce function
    function debounce(func, wait = 20) {
        let timeout;
        return function (...args) {
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(this, args), wait);
        };
    }

    // Throttle function
    function throttle(func, limit = 100) {
        let inThrottle;
        return function (...args) {
            if (!inThrottle) {
                func.apply(this, args);
                inThrottle = true;
                setTimeout(() => inThrottle = false, limit);
            }
        };
    }

    // ==================== WINDOW RESIZE ====================
    window.addEventListener('resize', debounce(function () {
        // If resizing to desktop, close mobile menu and restore all dropdown states
        if (window.innerWidth > 992) {
            closeMobileMenu();
            document.querySelectorAll('.nav-item.active').forEach(function (item) {
                item.classList.remove('active');
                const arrow = item.querySelector('.dropdown-icon');
                if (arrow) arrow.style.transform = '';
            });
        }
    }));

    // ==================== KEYBOARD NAVIGATION ====================
    document.addEventListener('keydown', function (e) {
        // Tab navigation enhancement
        if (e.key === 'Tab') {
            document.body.classList.add('keyboard-nav');
        }
    });

    document.addEventListener('mousedown', function () {
        document.body.classList.remove('keyboard-nav');
    });

    // ==================== TESTIMONIAL SWIPER ====================
    if (typeof Swiper !== 'undefined' && document.querySelector('.testimonialSwiper')) {
        new Swiper(".testimonialSwiper", {
            slidesPerView: 1,
            spaceBetween: 30,
            centeredSlides: true,
            loop: true,
            speed: 800,
            autoplay: {
                delay: 6000,
                disableOnInteraction: false,
            },
            pagination: {
                el: ".swiper-pagination",
                clickable: true,
            },
            breakpoints: {
                768: {
                    slidesPerView: 2,
                    spaceBetween: 30,
                },
                1024: {
                    slidesPerView: 3,
                    spaceBetween: 40,
                },
            },
        });
    }

    console.log('🎓 SMAN 1 Purwokerto Website Loaded Successfully');
});

// ==================== CSS ANIMATION KEYFRAMES ====================
const style = document.createElement('style');
style.textContent = `
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .keyboard-nav *:focus {
        outline: 2px solid var(--accent) !important;
        outline-offset: 2px;
    }
    
    input.error, textarea.error {
        border-color: var(--error) !important;
        box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
    }
`;
document.head.appendChild(style);

