// ===== DOM CONTENT LOADED =====
document.addEventListener('DOMContentLoaded', function() {
    // Initialize all functions
    initNavbar();
    initBackToTop();
    initScrollReveal();
    initSmoothScroll();
    initDotNavigation();
    initTestimonialSlider();
    initCounterAnimation();
    initFormHandlers();
    initTemplateCarousel();
});

// ===== NAVBAR SCROLL EFFECT =====
function initNavbar() {
    const navbar = document.querySelector('.navbar');

    window.addEventListener('scroll', function() {
        if (window.scrollY > 50) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
    });

    // Active nav link on scroll - only for anchor links (starting with #)
    const sections = document.querySelectorAll('section[id]');
    const navLinks = document.querySelectorAll('.nav-link');

    window.addEventListener('scroll', function() {
        let current = '';

        sections.forEach(section => {
            const sectionTop = section.offsetTop;
            const sectionHeight = section.clientHeight;

            if (scrollY >= sectionTop - 200) {
                current = section.getAttribute('id');
            }
        });

        navLinks.forEach(link => {
            const href = link.getAttribute('href');
            // Only modify active state for anchor links (starting with #)
            if (href && href.startsWith('#')) {
                link.classList.remove('active');
                if (href === '#' + current) {
                    link.classList.add('active');
                }
            }
            // Don't touch active class for full URL links - they are set by the server
        });
    });
}

// ===== BACK TO TOP BUTTON =====
function initBackToTop() {
    const backToTop = document.getElementById('backToTop');

    window.addEventListener('scroll', function() {
        if (window.scrollY > 300) {
            backToTop.classList.add('show');
        } else {
            backToTop.classList.remove('show');
        }
    });

    backToTop.addEventListener('click', function(e) {
        e.preventDefault();
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
}

// ===== SCROLL REVEAL ANIMATION =====
function initScrollReveal() {
    const revealElements = document.querySelectorAll(
        '.category-card, .pricing-card, .benefit-card, .about-content, .why-content, .about-images, .why-images, .testimonial-card, .accordion-item'
    );

    revealElements.forEach(el => el.classList.add('reveal'));

    function reveal() {
        revealElements.forEach(element => {
            const elementTop = element.getBoundingClientRect().top;
            const elementVisible = 150;

            if (elementTop < window.innerHeight - elementVisible) {
                element.classList.add('active');
            }
        });
    }

    window.addEventListener('scroll', reveal);
    reveal(); // Initial check
}

// ===== SMOOTH SCROLL FOR ANCHOR LINKS =====
function initSmoothScroll() {
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            const href = this.getAttribute('href');

            if (href !== '#') {
                e.preventDefault();

                const target = document.querySelector(href);
                if (target) {
                    const navbarHeight = document.querySelector('.navbar').offsetHeight;
                    const targetPosition = target.offsetTop - navbarHeight;

                    window.scrollTo({
                        top: targetPosition,
                        behavior: 'smooth'
                    });

                    // Close mobile menu if open
                    const navbarCollapse = document.querySelector('.navbar-collapse');
                    if (navbarCollapse.classList.contains('show')) {
                        const bsCollapse = bootstrap.Collapse.getInstance(navbarCollapse);
                        if (bsCollapse) {
                            bsCollapse.hide();
                        }
                    }
                }
            }
        });
    });
}

// ===== DOT NAVIGATION =====
function initDotNavigation() {
    const dots = document.querySelectorAll('.hero-dots .dot, .menu-dots .dot');

    dots.forEach((dot, index) => {
        dot.addEventListener('click', function() {
            const parent = this.parentElement;
            parent.querySelectorAll('.dot').forEach(d => d.classList.remove('active'));
            this.classList.add('active');
        });
    });
}

// ===== TESTIMONIAL SLIDER =====
function initTestimonialSlider() {
    const testimonials = [
        {
            text: "Qrscop transformed how we serve our customers. The QR menu is so easy to use, and we save hundreds on printing costs every month. Our customers love the convenience!",
            name: "Rajesh Kumar",
            role: "Owner, Spice Garden Restaurant",
            image: "https://images.unsplash.com/photo-1560250097-0b93528c311a?w=100"
        },
        {
            text: "Since implementing Qrscop, our table turnover has improved significantly. Customers can browse the menu instantly and our staff can focus on providing better service.",
            name: "Priya Sharma",
            role: "Manager, The Urban Cafe",
            image: "https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=100"
        },
        {
            text: "The analytics dashboard is a game-changer. We now know exactly which dishes are most viewed and can optimize our menu accordingly. Highly recommended!",
            name: "Amit Patel",
            role: "Owner, Flavors of India",
            image: "https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=100"
        },
        {
            text: "We run multiple restaurant locations and Qrscop makes it easy to manage all menus from one dashboard. The multi-language support helps us serve tourists too!",
            name: "Sarah Chen",
            role: "Director, Dragon Palace Chain",
            image: "https://images.unsplash.com/photo-1580489944761-15a19d654956?w=100"
        }
    ];

    const dots = document.querySelectorAll('.testimonial-dots .dot');
    const textEl = document.querySelector('.testimonial-text');
    const nameEl = document.querySelector('.testimonial-author h5');
    const roleEl = document.querySelector('.testimonial-author p');
    const imgEl = document.querySelector('.testimonial-author img');

    if (!textEl || !nameEl || !roleEl || !imgEl) return;

    let currentIndex = 0;

    function updateTestimonial(index) {
        const testimonial = testimonials[index];

        // Fade out
        textEl.style.opacity = '0';
        nameEl.style.opacity = '0';
        roleEl.style.opacity = '0';
        imgEl.style.opacity = '0';

        setTimeout(() => {
            textEl.textContent = testimonial.text;
            nameEl.textContent = testimonial.name;
            roleEl.textContent = testimonial.role;
            imgEl.src = testimonial.image;
            imgEl.alt = testimonial.name;

            // Fade in
            textEl.style.opacity = '1';
            nameEl.style.opacity = '1';
            roleEl.style.opacity = '1';
            imgEl.style.opacity = '1';
        }, 300);

        // Update dots
        dots.forEach((dot, i) => {
            dot.classList.toggle('active', i === index);
        });
    }

    // Click handlers for dots
    dots.forEach((dot, index) => {
        dot.addEventListener('click', () => {
            currentIndex = index;
            updateTestimonial(index);
        });
    });

    // Auto slide
    setInterval(() => {
        currentIndex = (currentIndex + 1) % testimonials.length;
        updateTestimonial(currentIndex);
    }, 5000);

    // Add transition styles
    textEl.style.transition = 'opacity 0.3s ease';
    nameEl.style.transition = 'opacity 0.3s ease';
    roleEl.style.transition = 'opacity 0.3s ease';
    imgEl.style.transition = 'opacity 0.3s ease';
}

// ===== COUNTER ANIMATION =====
function initCounterAnimation() {
    const statItems = document.querySelectorAll('.stat-item h3');

    function animateCounter(element) {
        const text = element.textContent;
        const hasPlus = text.includes('+');
        const hasM = text.includes('M');
        const hasPercent = text.includes('%');

        let target = parseInt(text.replace(/[^0-9]/g, ''));
        let suffix = '';

        if (hasPlus) suffix = '+';
        if (hasM) suffix = 'M+';
        if (hasPercent) suffix = '%';

        let current = 0;
        const increment = target / 50;
        const timer = setInterval(() => {
            current += increment;
            if (current >= target) {
                element.textContent = target + suffix;
                clearInterval(timer);
            } else {
                element.textContent = Math.floor(current) + suffix;
            }
        }, 30);
    }

    // Intersection Observer for counter animation
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                animateCounter(entry.target);
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.5 });

    statItems.forEach(item => observer.observe(item));
}

// ===== FORM HANDLERS =====
function initFormHandlers() {
    // Newsletter/CTA Form
    const newsletterForm = document.querySelector('.newsletter-form');
    if (newsletterForm) {
        newsletterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const email = this.querySelector('input[type="email"]').value;

            if (email) {
                showToast('Thank you! We\'ll be in touch soon.');
                this.querySelector('input[type="email"]').value = '';
            }
        });
    }

    // Pricing button handlers
    const pricingButtons = document.querySelectorAll('.pricing-card .btn');
    pricingButtons.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const plan = this.closest('.pricing-card').querySelector('h4').textContent;
            showToast(`Great choice! Starting ${plan} plan setup...`);
        });
    });
}

// ===== TEMPLATE CAROUSEL =====
function initTemplateCarousel() {
    const track = document.querySelector('.template-track');
    const cards = document.querySelectorAll('.template-card');
    const prevBtn = document.getElementById('templatePrev');
    const nextBtn = document.getElementById('templateNext');
    const dots = document.querySelectorAll('.template-dots .dot');

    if (!track || cards.length === 0) return;

    let currentIndex = 0;
    let cardsPerView = getCardsPerView();
    const totalCards = cards.length;
    const maxIndex = Math.ceil(totalCards / cardsPerView) - 1;

    // Get cards per view based on screen width
    function getCardsPerView() {
        if (window.innerWidth < 768) return 1;
        if (window.innerWidth < 992) return 2;
        if (window.innerWidth < 1200) return 3;
        return 4;
    }

    // Calculate card width including gap
    function getCardWidth() {
        const card = cards[0];
        const style = getComputedStyle(card);
        const width = card.offsetWidth;
        const gap = 20; // gap from CSS
        return width + gap;
    }

    // Update carousel position
    function updateCarousel() {
        const cardWidth = getCardWidth();
        const offset = currentIndex * cardWidth * cardsPerView;
        track.style.transform = `translateX(-${offset}px)`;

        // Update dots
        dots.forEach((dot, index) => {
            dot.classList.toggle('active', index === currentIndex);
        });

        // Update button states
        if (prevBtn) prevBtn.style.opacity = currentIndex === 0 ? '0.5' : '1';
        if (nextBtn) nextBtn.style.opacity = currentIndex >= maxIndex ? '0.5' : '1';
    }

    // Next slide
    function nextSlide() {
        if (currentIndex < maxIndex) {
            currentIndex++;
            updateCarousel();
        } else {
            // Loop back to start
            currentIndex = 0;
            updateCarousel();
        }
    }

    // Previous slide
    function prevSlide() {
        if (currentIndex > 0) {
            currentIndex--;
            updateCarousel();
        } else {
            // Loop to end
            currentIndex = maxIndex;
            updateCarousel();
        }
    }

    // Event listeners
    if (nextBtn) nextBtn.addEventListener('click', nextSlide);
    if (prevBtn) prevBtn.addEventListener('click', prevSlide);

    // Dot navigation
    dots.forEach((dot, index) => {
        dot.addEventListener('click', () => {
            currentIndex = index;
            updateCarousel();
        });
    });

    // Touch/Swipe support for mobile
    let startX = 0;
    let endX = 0;

    track.addEventListener('touchstart', (e) => {
        startX = e.touches[0].clientX;
    }, { passive: true });

    track.addEventListener('touchmove', (e) => {
        endX = e.touches[0].clientX;
    }, { passive: true });

    track.addEventListener('touchend', () => {
        const diff = startX - endX;
        if (Math.abs(diff) > 50) {
            if (diff > 0) {
                nextSlide();
            } else {
                prevSlide();
            }
        }
    });

    // Auto slide
    let autoSlideInterval = setInterval(nextSlide, 4000);

    // Pause on hover
    const carouselWrapper = document.querySelector('.template-carousel-wrapper');
    if (carouselWrapper) {
        carouselWrapper.addEventListener('mouseenter', () => {
            clearInterval(autoSlideInterval);
        });

        carouselWrapper.addEventListener('mouseleave', () => {
            autoSlideInterval = setInterval(nextSlide, 4000);
        });
    }

    // Handle window resize
    window.addEventListener('resize', () => {
        cardsPerView = getCardsPerView();
        currentIndex = 0;
        updateCarousel();
    });

    // Initial update
    updateCarousel();

    // Initialize Template Modal
    initTemplateModal();
}

// ===== TEMPLATE PREVIEW MODAL =====
function initTemplateModal() {
    const modal = document.getElementById('templateModal');
    const modalOverlay = modal.querySelector('.template-modal-overlay');
    const modalClose = document.getElementById('modalClose');
    const modalImage = document.getElementById('modalTemplateImage');
    const modalName = document.getElementById('modalTemplateName');
    const modalPrev = document.getElementById('modalPrev');
    const modalNext = document.getElementById('modalNext');
    const modalUseTemplate = document.getElementById('modalUseTemplate');
    const templateCards = document.querySelectorAll('.template-card');

    if (!modal || templateCards.length === 0) return;

    // Template data
    const templates = [];
    templateCards.forEach(card => {
        const img = card.querySelector('img');
        const name = card.querySelector('.template-name');
        templates.push({
            image: img.src,
            name: name.textContent
        });
    });

    let currentTemplateIndex = 0;

    // Open modal function
    function openModal(index) {
        currentTemplateIndex = index;
        updateModalContent();
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    // Close modal function
    function closeModal() {
        modal.classList.remove('active');
        document.body.style.overflow = '';
    }

    // Update modal content
    function updateModalContent() {
        const template = templates[currentTemplateIndex];
        modalImage.src = template.image;
        modalImage.alt = template.name;
        modalName.textContent = template.name;

        // Update nav button states
        modalPrev.style.opacity = currentTemplateIndex === 0 ? '0.5' : '1';
        modalNext.style.opacity = currentTemplateIndex === templates.length - 1 ? '0.5' : '1';
    }

    // Navigate to previous template
    function prevTemplate() {
        if (currentTemplateIndex > 0) {
            currentTemplateIndex--;
            updateModalContent();
        }
    }

    // Navigate to next template
    function nextTemplate() {
        if (currentTemplateIndex < templates.length - 1) {
            currentTemplateIndex++;
            updateModalContent();
        }
    }

    // Preview button click handlers
    const previewButtons = document.querySelectorAll('.template-overlay .btn');
    previewButtons.forEach((btn, index) => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            openModal(index);
        });
    });

    // Also allow clicking on the template card image
    templateCards.forEach((card, index) => {
        card.addEventListener('click', function(e) {
            if (!e.target.closest('.btn')) {
                openModal(index);
            }
        });
    });

    // Close modal handlers
    modalClose.addEventListener('click', closeModal);
    modalOverlay.addEventListener('click', closeModal);

    // Navigation handlers
    modalPrev.addEventListener('click', prevTemplate);
    modalNext.addEventListener('click', nextTemplate);

    // Use template handler
    modalUseTemplate.addEventListener('click', function() {
        const templateName = templates[currentTemplateIndex].name;
        closeModal();
        showToast(`Great choice! "${templateName}" template selected. Redirecting to setup...`);
    });

    // Keyboard navigation
    document.addEventListener('keydown', function(e) {
        if (!modal.classList.contains('active')) return;

        if (e.key === 'Escape') {
            closeModal();
        } else if (e.key === 'ArrowLeft') {
            prevTemplate();
        } else if (e.key === 'ArrowRight') {
            nextTemplate();
        }
    });

    // Touch swipe for modal
    let modalStartX = 0;
    let modalEndX = 0;

    const modalBody = modal.querySelector('.template-modal-body');
    modalBody.addEventListener('touchstart', (e) => {
        modalStartX = e.touches[0].clientX;
    }, { passive: true });

    modalBody.addEventListener('touchmove', (e) => {
        modalEndX = e.touches[0].clientX;
    }, { passive: true });

    modalBody.addEventListener('touchend', () => {
        const diff = modalStartX - modalEndX;
        if (Math.abs(diff) > 50) {
            if (diff > 0) {
                nextTemplate();
            } else {
                prevTemplate();
            }
        }
    });
}

// ===== TOAST NOTIFICATION =====
function showToast(message, type = 'success') {
    // Create toast element
    const toast = document.createElement('div');
    toast.className = 'custom-toast';

    const icon = type === 'success' ? 'bi-check-circle-fill' : 'bi-info-circle-fill';
    const bgColor = type === 'success' ? '#28a745' : '#f5a623';

    toast.innerHTML = `
        <i class="bi ${icon}"></i>
        <span>${message}</span>
    `;

    // Add styles
    toast.style.cssText = `
        position: fixed;
        bottom: 100px;
        right: 30px;
        background-color: ${bgColor};
        color: white;
        padding: 15px 25px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: 500;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.2);
        z-index: 9999;
        transform: translateX(100%);
        opacity: 0;
        transition: all 0.3s ease;
    `;

    document.body.appendChild(toast);

    // Animate in
    setTimeout(() => {
        toast.style.transform = 'translateX(0)';
        toast.style.opacity = '1';
    }, 10);

    // Animate out and remove
    setTimeout(() => {
        toast.style.transform = 'translateX(100%)';
        toast.style.opacity = '0';
        setTimeout(() => {
            toast.remove();
        }, 300);
    }, 3000);
}

// ===== PARALLAX EFFECT FOR FLOATING ICONS =====
window.addEventListener('scroll', function() {
    const parallaxElements = document.querySelectorAll('.floating-bean');
    const scrolled = window.scrollY;

    parallaxElements.forEach((el, index) => {
        const speed = 0.3 + (index * 0.1);
        el.style.transform = `translateY(${scrolled * speed}px)`;
    });
});

// ===== LAZY LOADING IMAGES =====
document.addEventListener('DOMContentLoaded', function() {
    const images = document.querySelectorAll('img[data-src]');

    const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src;
                img.removeAttribute('data-src');
                observer.unobserve(img);
            }
        });
    });

    images.forEach(img => imageObserver.observe(img));
});

// ===== PRELOADER (Optional) =====
window.addEventListener('load', function() {
    const preloader = document.querySelector('.preloader');
    if (preloader) {
        preloader.style.opacity = '0';
        setTimeout(() => {
            preloader.style.display = 'none';
        }, 500);
    }
});

// ===== MOBILE MENU ANIMATION =====
const navbarToggler = document.querySelector('.navbar-toggler');
if (navbarToggler) {
    navbarToggler.addEventListener('click', function() {
        this.classList.toggle('active');
    });
}

// ===== PREVENT DEFAULT ON EMPTY LINKS =====
document.querySelectorAll('a[href="#"]').forEach(link => {
    link.addEventListener('click', function(e) {
        if (this.getAttribute('href') === '#' && !this.closest('.navbar-nav')) {
            e.preventDefault();
        }
    });
});

// ===== IMAGE ERROR HANDLING =====
document.querySelectorAll('img').forEach(img => {
    img.addEventListener('error', function() {
        // Prevent infinite loop by checking if already tried fallback
        if (this.dataset.fallbackAttempted) return;
        this.dataset.fallbackAttempted = 'true';

        // Use a data URI placeholder (no network required)
        this.src = 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAwIiBoZWlnaHQ9IjMwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSIjZWVlIi8+PHRleHQgeD0iNTAlIiB5PSI1MCUiIGZvbnQtZmFtaWx5PSJBcmlhbCIgZm9udC1zaXplPSIxNiIgZmlsbD0iIzk5OSIgdGV4dC1hbmNob3I9Im1pZGRsZSIgZHk9Ii4zZW0iPkltYWdlIE5vdCBGb3VuZDwvdGV4dD48L3N2Zz4=';
    });
});

// ===== PRICING CARD HOVER EFFECTS =====
const pricingCards = document.querySelectorAll('.pricing-card');
pricingCards.forEach(card => {
    card.addEventListener('mouseenter', function() {
        if (!this.classList.contains('featured')) {
            this.style.borderColor = '#f5a623';
        }
    });

    card.addEventListener('mouseleave', function() {
        if (!this.classList.contains('featured')) {
            this.style.borderColor = 'transparent';
        }
    });
});

// ===== QR CODE DEMO INTERACTION =====
const qrFloatingCard = document.querySelector('.qr-floating-card');
if (qrFloatingCard) {
    qrFloatingCard.addEventListener('click', function() {
        showToast('Scan the QR code with your phone camera!', 'info');
    });
}

// ===== FEATURE CARDS ANIMATION =====
const featureCards = document.querySelectorAll('.category-card');
featureCards.forEach((card, index) => {
    card.style.animationDelay = `${index * 0.1}s`;
});

// ===== ACCORDION CUSTOM BEHAVIOR =====
const accordionButtons = document.querySelectorAll('.accordion-button');
accordionButtons.forEach(button => {
    button.addEventListener('click', function() {
        // Add smooth scroll to the accordion item
        setTimeout(() => {
            if (!this.classList.contains('collapsed')) {
                this.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }, 350);
    });
});

// ===== STATS NUMBER FORMATTING =====
function formatNumber(num) {
    if (num >= 1000000) {
        return (num / 1000000).toFixed(1) + 'M';
    }
    if (num >= 1000) {
        return (num / 1000).toFixed(1) + 'K';
    }
    return num.toString();
}

// ===== COPY TO CLIPBOARD UTILITY =====
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
        showToast('Copied to clipboard!');
    }).catch(err => {
        console.error('Failed to copy:', err);
    });
}

// ===== CONSOLE BRANDING =====
console.log('%c Qrscop - Digital QR Menu Solutions ', 'background: #f5a623; color: #0d0d0d; font-size: 20px; font-weight: bold; padding: 10px;');
console.log('%c Transform your restaurant with QR menus! ', 'color: #888; font-size: 12px;');
