import Alpine from 'alpinejs';

window.Alpine = Alpine;

const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');

/**
 * Révélation des blocs à l'entrée dans le viewport.
 * Un seul observateur pour toute la page ; chaque élément n'est révélé qu'une fois.
 */
function initReveal() {
    const targets = document.querySelectorAll('[data-reveal]');

    if (! targets.length) {
        return;
    }

    if (prefersReducedMotion.matches || ! ('IntersectionObserver' in window)) {
        targets.forEach((el) => el.classList.add('is-revealed'));

        return;
    }

    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (! entry.isIntersecting) {
                    return;
                }

                entry.target.classList.add('is-revealed');
                observer.unobserve(entry.target);
            });
        },
        { rootMargin: '0px 0px -12% 0px', threshold: 0.12 },
    );

    targets.forEach((el) => observer.observe(el));
}

/**
 * Tracé de la courbe de charge : on mesure la longueur réelle du path pour que
 * la durée du dash soit correcte quelle que soit la largeur de l'écran.
 */
function initCurveDrawing() {
    document.querySelectorAll('[data-draw-path]').forEach((path) => {
        const length = Math.ceil(path.getTotalLength());

        path.style.setProperty('--path-length', length);

        requestAnimationFrame(() => {
            window.setTimeout(() => {
                path.classList.add('is-drawn');
                path.closest('svg')?.querySelectorAll('[data-draw-fill]').forEach((el) => {
                    el.classList.add('is-drawn');
                });
            }, 260);
        });
    });
}

/**
 * Compteurs : les relevés (−32 %, 420 MWh…) montent jusqu'à leur valeur une
 * fois visibles. La valeur finale reste dans le HTML pour les lecteurs d'écran.
 */
function initCounters() {
    const counters = document.querySelectorAll('[data-count-to]');

    if (! counters.length || prefersReducedMotion.matches || ! ('IntersectionObserver' in window)) {
        return;
    }

    const format = (value, decimals) =>
        new Intl.NumberFormat(document.documentElement.lang || 'fr', {
            minimumFractionDigits: decimals,
            maximumFractionDigits: decimals,
        }).format(value);

    const run = (el) => {
        const target = parseFloat(el.dataset.countTo);
        const decimals = parseInt(el.dataset.countDecimals || '0', 10);
        const duration = 1400;
        const start = performance.now();

        const step = (now) => {
            const progress = Math.min((now - start) / duration, 1);
            // easeOutExpo : la valeur ralentit nettement en fin de course
            const eased = progress === 1 ? 1 : 1 - Math.pow(2, -10 * progress);

            el.textContent = format(target * eased, decimals);

            if (progress < 1) {
                requestAnimationFrame(step);
            }
        };

        requestAnimationFrame(step);
    };

    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (! entry.isIntersecting) {
                    return;
                }

                run(entry.target);
                observer.unobserve(entry.target);
            });
        },
        { threshold: 0.6 },
    );

    counters.forEach((el) => observer.observe(el));
}

/**
 * Rail de mesure : la graduation active suit la section visible.
 */
function initSectionRail() {
    const links = Array.from(document.querySelectorAll('[data-rail-link]'));

    if (! links.length || ! ('IntersectionObserver' in window)) {
        return;
    }

    const sections = links
        .map((link) => document.getElementById(link.dataset.railLink))
        .filter(Boolean);

    if (! sections.length) {
        return;
    }

    const setActive = (id) => {
        links.forEach((link) => {
            link.setAttribute('aria-current', link.dataset.railLink === id ? 'true' : 'false');
        });
    };

    const observer = new IntersectionObserver(
        (entries) => {
            const visible = entries
                .filter((entry) => entry.isIntersecting)
                .sort((a, b) => b.intersectionRatio - a.intersectionRatio)[0];

            if (visible) {
                setActive(visible.target.id);
            }
        },
        { rootMargin: '-45% 0px -45% 0px', threshold: 0 },
    );

    sections.forEach((section) => observer.observe(section));
}

/**
 * Parallaxe légère sur les halos décoratifs uniquement (data-parallax).
 * Lecture du scroll dans un rAF pour éviter tout layout thrashing.
 */
function initParallax() {
    const layers = document.querySelectorAll('[data-parallax]');

    if (! layers.length || prefersReducedMotion.matches) {
        return;
    }

    let ticking = false;

    const update = () => {
        const offset = window.scrollY;

        layers.forEach((layer) => {
            const speed = parseFloat(layer.dataset.parallax) || 0.08;

            layer.style.transform = `translate3d(0, ${(offset * speed).toFixed(2)}px, 0)`;
        });

        ticking = false;
    };

    window.addEventListener(
        'scroll',
        () => {
            if (ticking) {
                return;
            }

            ticking = true;
            requestAnimationFrame(update);
        },
        { passive: true },
    );

    update();
}

/**
 * Header : passe en verre opaque dès que la page a défilé.
 */
function initHeaderState() {
    const header = document.querySelector('[data-site-header]');

    if (! header) {
        return;
    }

    const update = () => {
        header.classList.toggle('is-scrolled', window.scrollY > 24);
    };

    window.addEventListener('scroll', update, { passive: true });
    update();
}

document.addEventListener('DOMContentLoaded', () => {
    initReveal();
    initCurveDrawing();
    initCounters();
    initSectionRail();
    initParallax();
    initHeaderState();
});

Alpine.start();
