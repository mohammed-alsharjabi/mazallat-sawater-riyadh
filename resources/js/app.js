document.documentElement.classList.add('reveal-enabled');
const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
const navToggle = document.querySelector('[data-nav-toggle]');
const nav = document.querySelector('[data-nav]');

const closeNavigation = () => {
    nav?.classList.remove('open');
    navToggle?.setAttribute('aria-expanded', 'false');
    document.querySelectorAll('[data-mega]').forEach((item) => item.classList.remove('open'));
    document.querySelectorAll('[data-mega-toggle]').forEach((button) => button.setAttribute('aria-expanded', 'false'));
};

navToggle?.addEventListener('click', () => {
    const open = nav?.classList.toggle('open');
    navToggle.setAttribute('aria-expanded', open ? 'true' : 'false');
});

document.querySelectorAll('[data-mega-toggle]').forEach((button) => {
    button.addEventListener('click', () => {
        const item = button.closest('[data-mega]');
        const open = item?.classList.toggle('open');
        button.setAttribute('aria-expanded', open ? 'true' : 'false');
    });
});

document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape') {
        closeNavigation();
        navToggle?.focus();
    }
});

document.addEventListener('click', (event) => {
    if (window.innerWidth < 1025 && nav?.classList.contains('open') && !event.target.closest('[data-nav]') && !event.target.closest('[data-nav-toggle]')) {
        closeNavigation();
    }
});

const adminMenu = document.querySelector('[data-admin-menu]');
const adminSidebar = document.querySelector('.admin-sidebar');
adminMenu?.addEventListener('click', () => adminSidebar?.classList.toggle('open'));

document.querySelectorAll('[data-image-shell]').forEach((shell) => {
    const image = shell.querySelector('img');
    if (!image) return;
    const complete = () => shell.classList.remove('is-loading');
    if (image.complete && image.naturalWidth) complete();
    image.addEventListener('load', complete, { once: true });
    image.addEventListener('error', () => {
        shell.classList.remove('is-loading');
        shell.classList.add('has-error');
    }, { once: true });
});

if (!reducedMotion && 'IntersectionObserver' in window) {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-revealed');
                observer.unobserve(entry.target);
            }
        });
    }, { rootMargin: '0px 0px -8% 0px', threshold: 0.08 });
    document.querySelectorAll('[data-reveal]').forEach((element) => observer.observe(element));
} else {
    document.querySelectorAll('[data-reveal]').forEach((element) => element.classList.add('is-revealed'));
}

document.querySelectorAll('.lead-form').forEach((form) => {
    form.addEventListener('submit', () => {
        const button = form.querySelector('button[type="submit"]');
        if (!button || button.disabled) return;
        button.disabled = true;
        button.dataset.originalLabel = button.textContent;
        button.textContent = 'جارٍ إرسال الطلب…';
        form.classList.add('is-submitting');
    });
});

document.querySelectorAll('[data-service-accordion]').forEach((accordion) => {
    accordion.querySelectorAll('[data-accordion-trigger]').forEach((trigger) => {
        trigger.addEventListener('click', () => {
            const selected = trigger.closest('[data-accordion-item]');
            accordion.querySelectorAll('[data-accordion-item]').forEach((item) => {
                const isSelected = item === selected;
                item.classList.toggle('active', isSelected);
                item.querySelector('[data-accordion-trigger]')?.setAttribute('aria-expanded', isSelected ? 'true' : 'false');
                const panel = item.querySelector('.service-accordion-panel');
                if (panel) panel.hidden = !isSelected;
            });
        });
    });
});

document.querySelectorAll('[data-before-after]').forEach((comparison) => {
    const range = comparison.querySelector('[data-before-after-range]');
    range?.addEventListener('input', () => comparison.style.setProperty('--position', `${range.value}%`));
});
