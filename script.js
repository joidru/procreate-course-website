// Smooth navigation scroll
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});

// Navbar background on scroll
const navbar = document.querySelector('nav');
window.addEventListener('scroll', function() {
    if (window.scrollY > 50) {
        navbar.style.boxShadow = '0 2px 8px rgba(0,0,0,0.08)';
    } else {
        navbar.style.boxShadow = 'none';
    }
});

// Button interactions
document.querySelectorAll('button').forEach(button => {
    button.addEventListener('click', function(e) {
        // Add ripple effect
        const rect = this.getBoundingClientRect();
        const x = e.clientX - rect.left;
        const y = e.clientY - rect.top;
        
        const ripple = document.createElement('span');
        ripple.style.left = x + 'px';
        ripple.style.top = y + 'px';
        ripple.style.position = 'absolute';
        ripple.style.width = '20px';
        ripple.style.height = '20px';
        ripple.style.background = 'rgba(255,255,255,0.5)';
        ripple.style.borderRadius = '50%';
        ripple.style.pointerEvents = 'none';
        ripple.style.animation = 'ripple 0.6s ease-out';
        
        // Log button click
        console.log('Button clicked:', this.textContent);
    });
});

// Intersection Observer for fade-in animations
const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
};

const observer = new IntersectionObserver(function(entries) {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.style.opacity = '1';
            entry.target.style.transform = 'translateY(0)';
        }
    });
}, observerOptions);

// Apply animation to cards
document.querySelectorAll('[class*="grid"] > div').forEach(card => {
    card.style.opacity = '0';
    card.style.transform = 'translateY(20px)';
    card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
    observer.observe(card);
});

// Track page interactions (for analytics)
function trackEvent(eventName, eventData) {
    console.log('Event tracked:', eventName, eventData);
    // Add your analytics service here (Google Analytics, Mixpanel, etc.)
}

// Track page view
trackEvent('page_view', {
    page: document.title,
    url: window.location.href
});

// Handle pricing button
const pricingButtons = document.querySelectorAll('button');
pricingButtons.forEach(btn => {
    if (btn.textContent.includes('Получить курс') || btn.textContent.includes('Начать обучение')) {
        btn.addEventListener('click', function() {
            trackEvent('cta_click', {
                action: this.textContent,
                url: window.location.href
            });
        });
    }
});