import { gsap } from 'gsap';

const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

const heroSlider = document.querySelector('[data-hero-slider]');
if (heroSlider) {
    const slides = [...heroSlider.querySelectorAll('[data-hero-slide]')];
    const dots = [...heroSlider.querySelectorAll('[data-hero-dot]')];
    const currentLabel = heroSlider.querySelector('[data-hero-current]');
    const serviceName = heroSlider.querySelector('[data-hero-service-name]');
    const serviceLink = heroSlider.querySelector('[data-hero-service-link]');
    let current = Math.max(0, slides.findIndex((slide) => slide.classList.contains('active')));
    let timer;
    let animating = false;

    const setSlide = (nextIndex, userInitiated = false) => {
        if (slides.length < 2 || animating) return;
        const normalized = (nextIndex + slides.length) % slides.length;
        if (normalized === current) return;

        const outgoing = slides[current];
        const incoming = slides[normalized];
        const finish = () => {
            outgoing.classList.remove('active');
            outgoing.setAttribute('aria-hidden', 'true');
            incoming.classList.add('active');
            incoming.setAttribute('aria-hidden', 'false');
            slides.forEach((slide, index) => {
                if (index !== normalized) slide.classList.remove('active');
            });
            gsap.set([outgoing, incoming], { clearProps: 'opacity,visibility,transform,zIndex' });
            animating = false;
        };

        dots.forEach((dot, index) => {
            const active = index === normalized;
            dot.classList.toggle('active', active);
            dot.setAttribute('aria-selected', active ? 'true' : 'false');
        });
        if (currentLabel) currentLabel.textContent = String(normalized + 1).padStart(2, '0');
        if (serviceName) serviceName.textContent = incoming.dataset.serviceName || 'الخدمة';
        if (serviceLink && incoming.dataset.serviceUrl) serviceLink.href = incoming.dataset.serviceUrl;

        if (reducedMotion) {
            finish();
        } else {
            animating = true;
            incoming.classList.add('active');
            incoming.setAttribute('aria-hidden', 'false');
            gsap.set(incoming, { opacity: 0, visibility: 'visible', scale: 1.08, zIndex: 2 });
            gsap.timeline({ onComplete: finish })
                .to(outgoing, { opacity: 0, scale: 1.025, duration: .72, ease: 'power2.inOut' }, 0)
                .to(incoming, { opacity: 1, scale: 1, duration: .95, ease: 'power3.out' }, .12);
        }
        current = normalized;
        if (userInitiated) restartAutoPlay();
    };

    const stopAutoPlay = () => window.clearInterval(timer);
    const startAutoPlay = () => {
        stopAutoPlay();
        if (!reducedMotion && slides.length > 1 && !document.hidden) {
            timer = window.setInterval(() => setSlide(current + 1), 6200);
        }
    };
    const restartAutoPlay = () => startAutoPlay();

    heroSlider.querySelector('[data-hero-prev]')?.addEventListener('click', () => setSlide(current - 1, true));
    heroSlider.querySelector('[data-hero-next]')?.addEventListener('click', () => setSlide(current + 1, true));
    dots.forEach((dot, index) => dot.addEventListener('click', () => setSlide(index, true)));
    heroSlider.addEventListener('pointerenter', stopAutoPlay);
    heroSlider.addEventListener('pointerleave', startAutoPlay);
    heroSlider.addEventListener('focusin', stopAutoPlay);
    heroSlider.addEventListener('focusout', (event) => {
        if (!heroSlider.contains(event.relatedTarget)) startAutoPlay();
    });
    document.addEventListener('visibilitychange', () => document.hidden ? stopAutoPlay() : startAutoPlay());
    startAutoPlay();
}

document.querySelectorAll('[data-service-showcase]').forEach((showcase) => {
    const tabs = [...showcase.querySelectorAll('[data-service-tab]')];
    const panels = [...showcase.querySelectorAll('[data-service-panel]')];

    const activate = (index) => {
        const selectedPanel = panels[index];
        if (!selectedPanel || tabs[index]?.classList.contains('active')) return;

        tabs.forEach((tab, tabIndex) => {
            const active = tabIndex === index;
            tab.classList.toggle('active', active);
            tab.setAttribute('aria-selected', active ? 'true' : 'false');
            tab.setAttribute('tabindex', active ? '0' : '-1');
        });
        panels.forEach((panel, panelIndex) => {
            const active = panelIndex === index;
            panel.hidden = !active;
            panel.classList.toggle('active', active);
        });

        if (!reducedMotion) {
            const intro = selectedPanel.querySelector('.service-showcase-intro');
            const galleryItems = selectedPanel.querySelectorAll('.service-showcase-gallery figure');
            if (intro) gsap.fromTo(intro, { opacity: 0, y: 18 }, { opacity: 1, y: 0, duration: .45, ease: 'power2.out' });
            if (galleryItems.length) gsap.fromTo(galleryItems, { opacity: 0, y: 22, scale: .985 }, { opacity: 1, y: 0, scale: 1, duration: .55, stagger: .07, ease: 'power2.out' });
        }
    };

    tabs.forEach((tab, index) => {
        if (index !== 0) tab.setAttribute('tabindex', '-1');
        tab.addEventListener('click', () => activate(index));
        tab.addEventListener('keydown', (event) => {
            if (!['ArrowLeft', 'ArrowRight'].includes(event.key)) return;
            event.preventDefault();
            const direction = event.key === 'ArrowLeft' ? 1 : -1;
            const next = (index + direction + tabs.length) % tabs.length;
            activate(next);
            tabs[next]?.focus();
        });
    });
});

if (!reducedMotion) {
    const heroCopyElements = document.querySelectorAll('[data-hero-copy] > *');
    const heroVisual = document.querySelector('[data-hero-visual]');
    if (heroCopyElements.length) gsap.from(heroCopyElements, { y: 24, opacity: 0, duration: .7, stagger: .08, ease: 'power2.out' });
    if (heroVisual) gsap.from(heroVisual, { x: -26, opacity: 0, duration: .9, delay: .12, ease: 'power2.out' });
}

const filterButtons = document.querySelectorAll('[data-filter]');
const projectCards = [...document.querySelectorAll('[data-project-card]')];
const filterEmpty = document.querySelector('[data-filter-empty]');

filterButtons.forEach((button) => {
    button.addEventListener('click', () => {
        filterButtons.forEach((item) => item.classList.remove('active'));
        button.classList.add('active');
        const value = button.dataset.filter;
        const type = button.dataset.filterType;
        let visible = 0;
        projectCards.forEach((card) => {
            const matches = value === 'all' || card.dataset[type] === value;
            card.hidden = !matches;
            if (matches) {
                visible += 1;
                if (!reducedMotion) gsap.fromTo(card, { opacity: 0, y: 12 }, { opacity: 1, y: 0, duration: .3, ease: 'power1.out' });
            }
        });
        if (filterEmpty) filterEmpty.hidden = visible !== 0;
    });
});

const beforeAfter = document.querySelector('[data-before-after]');
const beforeAfterRange = document.querySelector('[data-before-after-range]');
beforeAfterRange?.addEventListener('input', () => beforeAfter?.style.setProperty('--position', `${beforeAfterRange.value}%`));

const calculator = document.querySelector('[data-area-calculator]');
if (calculator) {
    const width = calculator.querySelector('[data-calc-width]');
    const length = calculator.querySelector('[data-calc-length]');
    const output = calculator.querySelector('[data-calc-output]');
    const submit = calculator.querySelector('[data-calc-submit]');
    const update = () => {
        const area = Number(width.value) * Number(length.value);
        const valid = Number.isFinite(area) && area > 0 && area <= 10000;
        output.textContent = valid ? `${area.toLocaleString('ar-SA', { maximumFractionDigits: 1 })} م²` : '— م²';
        submit.classList.toggle('is-disabled', !valid);
        submit.setAttribute('aria-disabled', valid ? 'false' : 'true');
        submit.href = valid ? `${submit.href.split('?')[0]}?area_size=${Math.ceil(area)}` : submit.href.split('?')[0];
    };
    width.addEventListener('input', update);
    length.addEventListener('input', update);
    submit.addEventListener('click', (event) => { if (submit.getAttribute('aria-disabled') === 'true') event.preventDefault(); });
}

if (!reducedMotion && 'IntersectionObserver' in window) {
    const counterObserver = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (!entry.isIntersecting) return;
            entry.target.querySelectorAll('[data-counter]').forEach((counter) => {
                const target = Number(counter.dataset.counter);
                if (!Number.isFinite(target)) return;
                const state = { value: 0 };
                gsap.to(state, { value: target, duration: 1.2, ease: 'power2.out', onUpdate: () => { counter.textContent = Math.round(state.value).toLocaleString('ar-SA'); } });
            });
            counterObserver.unobserve(entry.target);
        });
    }, { threshold: .4 });
    document.querySelectorAll('[data-counter-item]').forEach((item) => counterObserver.observe(item));

    const parallax = document.querySelector('[data-parallax] picture');
    if (parallax) {
        let ticking = false;
        window.addEventListener('scroll', () => {
            if (ticking) return;
            ticking = true;
            requestAnimationFrame(() => {
                const rect = parallax.getBoundingClientRect();
                const offset = Math.max(-10, Math.min(10, (window.innerHeight / 2 - rect.top) * .018));
                parallax.style.transform = `translate3d(0, ${offset}px, 0)`;
                ticking = false;
            });
        }, { passive: true });
    }
}
