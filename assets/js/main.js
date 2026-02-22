/**
 * Akrasia – Frontend JavaScript
 * Mobile nav, dropdown menu, sticky header, scroll animations
 */

document.addEventListener('DOMContentLoaded', () => {

    // ── Hamburger / mobile nav ────────────────────────────────────
    const toggle = document.querySelector('.nav-toggle');
    const nav    = document.querySelector('.site-nav');

    if (toggle && nav) {
        toggle.addEventListener('click', () => {
            const isOpen = toggle.getAttribute('aria-expanded') === 'true';
            toggle.setAttribute('aria-expanded', String(!isOpen));
            toggle.classList.toggle('active', !isOpen);
            nav.classList.toggle('active', !isOpen);
            document.body.classList.toggle('nav-open', !isOpen);
        });

        // Zavřít menu kliknutím na přímý nav-link (ne dropdown toggle)
        nav.querySelectorAll('a.nav-link:not([aria-haspopup])').forEach(link => {
            link.addEventListener('click', closeMobileNav);
        });

        // Zavřít menu kliknutím mimo header a mimo toggle (toggle je teď mimo header)
        document.addEventListener('click', e => {
            if (nav.classList.contains('active')
                && !e.target.closest('.site-header')
                && !e.target.closest('.nav-toggle')) {
                closeMobileNav();
            }
        });

        // Zavřít menu klávesou Escape
        document.addEventListener('keydown', e => {
            if (e.key === 'Escape' && nav.classList.contains('active')) {
                closeMobileNav();
                toggle.focus();
            }
        });
    }

    function closeMobileNav() {
        if (!toggle || !nav) return;
        toggle.setAttribute('aria-expanded', 'false');
        toggle.classList.remove('active');
        nav.classList.remove('active');
        document.body.classList.remove('nav-open');
        // Zavřít všechna otevřená podmenu
        document.querySelectorAll('.nav-item.open').forEach(item => item.classList.remove('open'));
    }

    // ── Dropdown menu (klávesnice + focus) ───────────────────────
    document.querySelectorAll('.nav-item').forEach(item => {
        const trigger  = item.querySelector('.nav-link[aria-haspopup]');
        const dropdown = item.querySelector('.dropdown');
        if (!trigger || !dropdown) return;

        // Mobil: tap otevírá podmenu (preventDefault, toggle .open)
        trigger.addEventListener('click', e => {
            if (window.innerWidth > 768) return;
            e.preventDefault();
            document.querySelectorAll('.nav-item.open').forEach(other => {
                if (other !== item) other.classList.remove('open');
            });
            const isOpen = item.classList.toggle('open');
            trigger.setAttribute('aria-expanded', String(isOpen));
        });

        // Desktop + klávesnice: Enter/Space otevírá podmenu
        trigger.addEventListener('keydown', e => {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                const isOpen = item.classList.toggle('open');
                trigger.setAttribute('aria-expanded', String(isOpen));
                if (isOpen) {
                    const first = dropdown.querySelector('a[role="menuitem"]');
                    if (first) first.focus();
                }
            }
        });

        // Zavřít dropdown při odchodu fokusem
        item.addEventListener('focusout', e => {
            if (!item.contains(e.relatedTarget)) {
                item.classList.remove('dropdown-open');
                trigger.setAttribute('aria-expanded', 'false');
            }
        });

        // Klávesová navigace šipkami uvnitř dropdownu
        dropdown.addEventListener('keydown', e => {
            const items = [...dropdown.querySelectorAll('a[role="menuitem"]')];
            const idx   = items.indexOf(document.activeElement);
            if (e.key === 'ArrowDown') {
                e.preventDefault();
                items[Math.min(idx + 1, items.length - 1)]?.focus();
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                if (idx === 0) { trigger.focus(); item.classList.remove('dropdown-open'); }
                else items[Math.max(idx - 1, 0)]?.focus();
            } else if (e.key === 'Escape') {
                item.classList.remove('dropdown-open');
                trigger.setAttribute('aria-expanded', 'false');
                trigger.focus();
            }
        });
    });

    // ── Sticky header shadow on scroll ───────────────────────────
    const header = document.querySelector('.site-header');
    if (header) {
        const onScroll = () => header.classList.toggle('scrolled', window.scrollY > 60);
        window.addEventListener('scroll', onScroll, { passive: true });
        onScroll();
    }

    // ── Scroll-reveal (fade-up) ───────────────────────────────────
    const fadeEls = document.querySelectorAll('.fade-up');
    if (fadeEls.length > 0) {
        if ('IntersectionObserver' in window) {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('visible');
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.07, rootMargin: '0px 0px -40px 0px' });

            fadeEls.forEach(el => observer.observe(el));
        } else {
            // Fallback pro starší prohlížeče
            fadeEls.forEach(el => el.classList.add('visible'));
        }
    }

    // ── Smooth scroll pro anchor linky ───────────────────────────
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                e.preventDefault();
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                target.focus({ preventScroll: true });
            }
        });
    });

});
