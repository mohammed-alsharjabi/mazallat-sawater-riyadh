import { gsap } from 'gsap';

const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

if (!reducedMotion) {
    gsap.from('[data-hero-copy] > *', { y: 24, opacity: 0, duration: .7, stagger: .08, ease: 'power2.out' });
    gsap.from('[data-hero-visual]', { x: -26, opacity: 0, duration: .9, delay: .12, ease: 'power2.out' });
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
