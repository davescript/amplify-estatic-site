document.addEventListener('DOMContentLoaded', () => {
    const header = document.querySelector('.site-header');
    const navToggle = document.querySelector('.nav-toggle');
    const mobileNav = document.querySelector('.mobile-nav');
    const body = document.body;

    const setHeaderState = () => {
        const isScrolled = window.scrollY > 40;
        if (header) {
            header.dataset.scrolled = isScrolled ? 'true' : 'false';
        }
    };

    const closeMobileNav = () => {
        if (!mobileNav) return;
        mobileNav.setAttribute('aria-hidden', 'true');
        navToggle?.setAttribute('aria-expanded', 'false');
        body.classList.remove('nav-open');
    };

    const toggleMobileNav = () => {
        if (!mobileNav) return;
        const expanded = navToggle?.getAttribute('aria-expanded') === 'true';
        const newState = !expanded;
        navToggle?.setAttribute('aria-expanded', String(newState));
        mobileNav.setAttribute('aria-hidden', String(!newState));
        body.classList.toggle('nav-open', newState);
    };

    window.addEventListener('scroll', setHeaderState, { passive: true });
    setHeaderState();

    navToggle?.addEventListener('click', toggleMobileNav);
    mobileNav?.addEventListener('click', (event) => {
        if (event.target === mobileNav) {
            closeMobileNav();
        }
    });

    mobileNav?.querySelectorAll('a').forEach((link) => {
        link.addEventListener('click', () => {
            closeMobileNav();
        });
    });

    document.querySelectorAll('a[href^="#"]').forEach((link) => {
        const targetId = link.getAttribute('href');
        if (!targetId || targetId.length <= 1) {
            return;
        }

        link.addEventListener('click', (event) => {
            const targetElement = document.querySelector(targetId);
            if (!targetElement) {
                return;
            }

            event.preventDefault();
            const headerOffset = header ? header.offsetHeight - 10 : 0;
            const elementPosition = targetElement.getBoundingClientRect().top + window.pageYOffset;
            window.scrollTo({
                top: elementPosition - headerOffset,
                behavior: 'smooth',
            });
        });
    });

    const hero = document.querySelector('.hero');
    if (hero?.dataset.slides) {
        try {
            const slides = JSON.parse(hero.dataset.slides);
            let current = 0;
            if (Array.isArray(slides) && slides.length > 1) {
                const updateBackground = () => {
                    hero.style.setProperty('--hero-image', `url('${slides[current]}')`);
                    hero.style.backgroundImage = `radial-gradient(circle at 20% 20%, rgba(200, 155, 60, 0.15), transparent 60%), url('${slides[current]}')`;
                    current = (current + 1) % slides.length;
                };
                updateBackground();
                setInterval(updateBackground, 7000);
            }
        } catch (error) {
            console.error('Não foi possível carregar o slider de destaque.', error);
        }
    }

    const revealTargets = document.querySelectorAll('.reveal');
    if (revealTargets.length > 0) {
        const observer = new IntersectionObserver(
            (entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('is-visible');
                        observer.unobserve(entry.target);
                    }
                });
            },
            {
                rootMargin: '0px 0px -10% 0px',
                threshold: 0.25,
            }
        );

        revealTargets.forEach((target) => observer.observe(target));
    }

    const instagramCarousel = document.querySelector('.js-instagram-carousel');
    if (instagramCarousel && typeof Swiper !== 'undefined') {
        // eslint-disable-next-line no-new
        new Swiper(instagramCarousel, {
            slidesPerView: 1,
            spaceBetween: 20,
            loop: true,
            centeredSlides: true,
            autoplay: {
                delay: 4000,
                disableOnInteraction: false,
            },
            breakpoints: {
                640: { slidesPerView: 2 },
                1024: { slidesPerView: 3 },
            },
            pagination: {
                el: '.js-instagram-pagination',
                clickable: true,
            },
            navigation: {
                nextEl: '.js-instagram-next',
                prevEl: '.js-instagram-prev',
            },
        });
    }

    const areaCards = document.querySelectorAll('.area-card');
    if (areaCards.length > 0 && window.matchMedia('(pointer: fine)').matches) {
        areaCards.forEach((card) => {
            let rotateX = 0;
            let rotateY = 0;
            let requestId = null;

            const render = () => {
                card.style.transform = `rotateX(${rotateX}deg) rotateY(${rotateY}deg)`;
                requestId = null;
            };

            card.addEventListener('mousemove', (event) => {
                const bounds = card.getBoundingClientRect();
                const relativeX = (event.clientX - bounds.left) / bounds.width - 0.5;
                const relativeY = (event.clientY - bounds.top) / bounds.height - 0.5;
                rotateY = relativeX * 12;
                rotateX = relativeY * -12;
                card.classList.add('is-tilt');

                if (!requestId) {
                    requestId = requestAnimationFrame(render);
                }
            });

            card.addEventListener('mouseleave', () => {
                rotateX = 0;
                rotateY = 0;
                card.classList.remove('is-tilt');
                if (!requestId) {
                    requestId = requestAnimationFrame(render);
                }
            });
        });
    }
});
