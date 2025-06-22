
const Utils = {
    debounce: function(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    },
    safeElementOperation: function(selector, callback) {
        const element = document.querySelector(selector);
        if (element && callback) {
            callback(element);
        }
        return element;
    },
    fadeIn: function(element, duration = 300) {
        element.style.opacity = '0';
        element.style.display = 'block';
        
        let start = performance.now();
        
        function animate(currentTime) {
            let elapsed = currentTime - start;
            let progress = elapsed / duration;
            
            if (progress < 1) {
                element.style.opacity = progress;
                requestAnimationFrame(animate);
            } else {
                element.style.opacity = '1';
            }
        }
        
        requestAnimationFrame(animate);
    },

    fadeOut: function(element, duration = 300) {
        let start = performance.now();
        let startOpacity = parseFloat(element.style.opacity) || 1;
        
        function animate(currentTime) {
            let elapsed = currentTime - start;
            let progress = elapsed / duration;
            
            if (progress < 1) {
                element.style.opacity = startOpacity * (1 - progress);
                requestAnimationFrame(animate);
            } else {
                element.style.opacity = '0';
                element.style.display = 'none';
            }
        }
        
        requestAnimationFrame(animate);
    }
};
class LoginModal {
    constructor() {
        this.modal = document.getElementById('loginModal');
        this.container = document.getElementById('login-container');
        this.isActive = false;
        
        if (this.modal) {
            this.init();
        }
    }
    init() {
        this.bindEvents();
        this.initFormValidation();
    }
    bindEvents() {
        const loginNavBtn = document.getElementById('loginNavBtn');
        const donateNowBtn = document.getElementById('donateNowBtn');
        const closeModal = document.getElementById('closeModal');
        const registerBtn = document.getElementById('register');
        const loginBackBtn = document.getElementById('login');
        if (loginNavBtn) {
            loginNavBtn.addEventListener('click', (e) => this.openModal(e));
        }
        
        if (donateNowBtn) {
            donateNowBtn.addEventListener('click', (e) => this.openModal(e));
        }
        
        if (closeModal) {
            closeModal.addEventListener('click', () => this.closeModal());
        }
        
        if (registerBtn) {
            registerBtn.addEventListener('click', () => this.toggleForm('register'));
        }
        
        if (loginBackBtn) {
            loginBackBtn.addEventListener('click', () => this.toggleForm('login'));
        }
        this.modal.addEventListener('click', (e) => {
            if (e.target === this.modal) {
                this.closeModal();
            }
        });
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && this.isActive) {
                this.closeModal();
            }
        });
    }
    openModal(e) {
        e.preventDefault();
        this.modal.classList.add('active');
        this.isActive = true;
        document.body.style.overflow = 'hidden';
        const firstInput = this.modal.querySelector('input[type="email"], input[type="text"]');
        if (firstInput) {
            setTimeout(() => firstInput.focus(), 100);
        }
    }
    
    closeModal() {
        this.modal.classList.remove('active');
        if (this.container) {
            this.container.classList.remove('active');
        }
        this.isActive = false;
        document.body.style.overflow = 'auto';
    }
    
    toggleForm(type) {
        if (this.container) {
            if (type === 'register') {
                this.container.classList.add('active');
            } else {
                this.container.classList.remove('active');
            }
        }
    }
    
    initFormValidation() {
        const forms = this.modal.querySelectorAll('form');
        forms.forEach(form => {
            form.addEventListener('submit', (e) => this.handleFormSubmit(e));
        });
    }
    
    handleFormSubmit(e) {
        e.preventDefault();
        const form = e.target;
        const formData = new FormData(form);
        let isValid = true;
        const requiredFields = form.querySelectorAll('[required]');
        
        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                field.classList.add('error');
                isValid = false;
            } else {
                field.classList.remove('error');
            }
        });
        
        if (isValid) {
            this.showMessage('Form submitted successfully! Welcome to our platform.', 'success');
            setTimeout(() => this.closeModal(), 2000);
        } else {
            this.showMessage('Please fill in all required fields.', 'error');
        }
    }
    showMessage(message, type = 'info') {
        const messageDiv = document.createElement('div');
        messageDiv.className = `modal-message ${type}`;
        messageDiv.textContent = message;
        
        const existingMessage = this.modal.querySelector('.modal-message');
        if (existingMessage) {
            existingMessage.remove();
        }
        
        this.modal.querySelector('.login-container, .modal-content').appendChild(messageDiv);
        
        setTimeout(() => {
            if (messageDiv.parentNode) {
                messageDiv.remove();
            }
        }, 3000);
    }
}
class AccordionManager {
    constructor() {
        this.accordions = document.querySelectorAll('.accordion');
        this.init();
    }
    
    init() {
        this.accordions.forEach((accordion, index) => {
            accordion.addEventListener('click', () => this.toggleAccordion(accordion, index));
            accordion.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    this.toggleAccordion(accordion, index);
                }
            });
            accordion.setAttribute('tabindex', '0');
            accordion.setAttribute('role', 'button');
            accordion.setAttribute('aria-expanded', 'false');
        });
    }
    
    toggleAccordion(accordion, index) {
        const panel = accordion.nextElementSibling;
        if (!panel) return;
        const isActive = accordion.classList.contains('active');
        if (isActive) {
            accordion.classList.remove('active');
            accordion.setAttribute('aria-expanded', 'false');
            panel.classList.remove('active');
        } else {
            accordion.classList.add('active');
            accordion.setAttribute('aria-expanded', 'true');
            panel.classList.add('active');
        }
    }
}
class ScrollManager {
    constructor() {
        this.scrollToTopBtn = document.getElementById('scrollToTop');
        this.navbar = document.querySelector('.navbar');
        this.init();
    }
    
    init() {
        if (this.scrollToTopBtn) {
            this.initScrollToTop();
        }
        
        if (this.navbar) {
            this.initStickyNavbar();
        }
        
        this.initScrollAnimations();
    }
    
    initScrollToTop() {
        const debouncedScroll = Utils.debounce(() => {
            if (window.pageYOffset > 300) {
                this.scrollToTopBtn.classList.add('show');
            } else {
                this.scrollToTopBtn.classList.remove('show');
            }
        }, 10);
        
        window.addEventListener('scroll', debouncedScroll);
        
        this.scrollToTopBtn.addEventListener('click', () => {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }
    
    initStickyNavbar() {
        const debouncedNavbar = Utils.debounce(() => {
            if (window.pageYOffset > 250) {
                this.navbar.classList.add('fixed-top');
            } else {
                this.navbar.classList.remove('fixed-top');
            }
        }, 10);
        
        window.addEventListener('scroll', debouncedNavbar);
    }
    
    initScrollAnimations() {
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-in');
                }
            });
        }, observerOptions);
        const animatedElements = document.querySelectorAll('.fade-in, .slide-in, .scale-in');
        animatedElements.forEach(el => observer.observe(el));
    }
}
class Chatbot {
    constructor() {
        this.messagesContainer = document.getElementById('messages');
        this.inputField = document.getElementById('input');
        this.isTyping = false;
        
        this.responses = {
            greetings: ['hello', 'hi', 'hey', 'good morning', 'good afternoon', 'good evening'],
            donate: ['donate', 'donation', 'give', 'contribute'],
            food: ['food', 'meal', 'items', 'what can i donate'],
            help: ['help', 'assist', 'support', 'guidance'],
            thanks: ['thank', 'thanks', 'appreciate'],
            contact: ['contact', 'reach', 'phone', 'email', 'address'],
            time: ['time', 'hours', 'when', 'schedule'],
            location: ['location', 'where', 'address', 'place'],
            process: ['process', 'how', 'procedure', 'steps']
        };
        
        this.botReplies = {
            greetings: [
                'Hello! Welcome to our food donation platform. How can I help you today?',
                'Hi there! I\'m here to help you with food donations. What would you like to know?',
                'Good day! Thanks for visiting our food donation site. How can I assist you?'
            ],
            donate: [
                'To donate food, click "Donate Now" on our homepage and fill out the form. We\'ll contact you within 24 hours!',
                'Food donation is easy! Just register through our form, and we\'ll guide you through the process.',
                'Thank you for wanting to donate! Use our donation form to get started immediately.'
            ],
            food: [
                'We accept non-perishable items, fresh produce, and cooked meals from restaurants. All food must be safe for consumption.',
                'You can donate canned goods, fresh fruits/vegetables, packaged foods, and prepared meals from certified kitchens.',
                'We welcome all safe, consumable food items - from pantry staples to fresh produce!'
            ],
            help: [
                'I can help you with donation processes, food requirements, contact information, and more. What specific information do you need?',
                'I\'m here to assist! Ask me about our donation process, accepted foods, or how to get involved.',
                'Need guidance? I can help with donations, volunteering, or general questions about our mission.'
            ],
            thanks: [
                'You\'re very welcome! Thank you for your interest in fighting food waste.',
                'My pleasure! Together we can make a real difference in reducing food waste.',
                'Happy to help! Your involvement means everything to our cause.'
            ],
            contact: [
                'Reach us at fooddonate@gmail.com or call (+91) 0000 000 000. We\'re available 24/7!',
                'Contact us: Email fooddonate@gmail.com | Phone (+91) 0000 000 000 | Available round the clock!',
                'Get in touch: fooddonate@gmail.com or (+91) 0000 000 000. We respond quickly!'
            ],
            time: [
                'We operate 24/7 to ensure no food goes to waste. Contact us anytime!',
                'Our services are available around the clock - because hunger doesn\'t wait!',
                '24/7 availability ensures we can help whenever you need to donate or receive food.'
            ],
            location: [
                'We\'re located at Dewandighi, Katwa Road, Purba Bardhaman, 713102.',
                'Find us at: Dewandighi, Katwa Road, Purba Bardhaman, 713102. We serve the entire region!',
                'Our address: Dewandighi, Katwa Road, Purba Bardhaman, 713102. Come visit us!'
            ],
            process: [
                'Our process is simple: 1) Fill out donation form 2) We contact you 3) Schedule pickup 4) Food reaches those in need!',
                'Easy steps: Submit form → We call you → Arrange collection → Food distributed to those who need it most.',
                '4 simple steps: Register → Contact → Collect → Distribute. We handle everything professionally!'
            ],
            default: [
                'That\'s a great question! Check our FAQ section or contact us directly for detailed information.',
                'I understand your interest in food donation. Please explore our website or reach out for specific guidance.',
                'For detailed information about that topic, I recommend checking our resources or contacting our team directly.'
            ]
        };
        
        if (this.messagesContainer && this.inputField) {
            this.init();
        }
    }
    
    init() {
        this.inputField.addEventListener('keypress', (e) => {
            if (e.key === 'Enter' && !this.isTyping) {
                this.handleUserMessage();
            }
        });
        const sendBtn = document.getElementById('sendBtn');
        if (sendBtn) {
            sendBtn.addEventListener('click', () => {
                if (!this.isTyping) {
                    this.handleUserMessage();
                }
            });
        }
        setTimeout(() => {
            this.addMessage(this.getRandomReply('greetings'), false);
        }, 1000);
    }
    
    handleUserMessage() {
        const userMessage = this.inputField.value.trim();
        if (!userMessage) return;
        
        this.addMessage(userMessage, true);
        this.inputField.value = '';
        this.showTypingIndicator();
        
        setTimeout(() => {
            this.hideTypingIndicator();
            const botResponse = this.getBotResponse(userMessage);
            this.addMessage(botResponse, false);
        }, 1500);
    }
    
    addMessage(message, isUser = false) {
        const messageDiv = document.createElement('div');
        messageDiv.className = `response ${isUser ? 'user' : 'bot'}`;
        
        const avatar = document.createElement('div');
        avatar.className = 'avatar';
        avatar.textContent = isUser ? '👤' : '🤖';
        
        const messageContent = document.createElement('div');
        messageContent.className = 'message';
        messageContent.textContent = message;
        
        const timestamp = document.createElement('div');
        timestamp.className = 'timestamp';
        timestamp.textContent = new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
        
        messageDiv.appendChild(avatar);
        messageDiv.appendChild(messageContent);
        messageDiv.appendChild(timestamp);
        
        this.messagesContainer.appendChild(messageDiv);
        this.scrollToBottom();
        requestAnimationFrame(() => {
            messageDiv.classList.add('message-enter');
        });
    }
    
    showTypingIndicator() {
        this.isTyping = true;
        const typingDiv = document.createElement('div');
        typingDiv.className = 'response bot typing-indicator';
        typingDiv.innerHTML = `
            <div class="avatar">🤖</div>
            <div class="message">
                <div class="typing-dots">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </div>
        `;
        
        this.messagesContainer.appendChild(typingDiv);
        this.scrollToBottom();
    }
    
    hideTypingIndicator() {
        this.isTyping = false;
        const typingIndicator = this.messagesContainer.querySelector('.typing-indicator');
        if (typingIndicator) {
            typingIndicator.remove();
        }
    }
    
    getBotResponse(userMessage) {
        const lowerMessage = userMessage.toLowerCase();
        
        for (const [category, keywords] of Object.entries(this.responses)) {
            if (keywords.some(keyword => lowerMessage.includes(keyword))) {
                return this.getRandomReply(category);
            }
        }
        
        return this.getRandomReply('default');
    }
    
    getRandomReply(category) {
        const replies = this.botReplies[category] || this.botReplies.default;
        return replies[Math.floor(Math.random() * replies.length)];
    }
    
    scrollToBottom() {
        this.messagesContainer.scrollTop = this.messagesContainer.scrollHeight;
    }
}
class FormHandler {
    constructor() {
        this.init();
    }
    
    init() {
        const contactForm = document.querySelector('.contact-form');
        if (contactForm) {
            contactForm.addEventListener('submit', (e) => this.handleContactForm(e));
        }
        const donationForm = document.querySelector('.donation-form');
        if (donationForm) {
            donationForm.addEventListener('submit', (e) => this.handleDonationForm(e));
        }
        
        const newsletterForm = document.querySelector('.newsletter-form');
        if (newsletterForm) {
            newsletterForm.addEventListener('submit', (e) => this.handleNewsletterForm(e));
        }
    }
    
    handleContactForm(e) {
        e.preventDefault();
        const form = e.target;
        
        if (this.validateForm(form)) {
            this.showFormSuccess('Thank you for your message! We\'ll get back to you within 24 hours.');
            form.reset();
        }
    }
    
    handleDonationForm(e) {
        e.preventDefault();
        const form = e.target;
        
        if (this.validateForm(form)) {
            this.showFormSuccess('Donation registered successfully! We\'ll contact you soon to arrange pickup.');
            form.reset();
        }
    }
    
    handleNewsletterForm(e) {
        e.preventDefault();
        const form = e.target;
        
        if (this.validateForm(form)) {
            this.showFormSuccess('Successfully subscribed to our newsletter!');
            form.reset();
        }
    }
    
    validateForm(form) {
        let isValid = true;
        const requiredFields = form.querySelectorAll('[required]');
        
        requiredFields.forEach(field => {
            const value = field.value.trim();
            
            if (!value) {
                this.showFieldError(field, 'This field is required');
                isValid = false;
            } else if (field.type === 'email' && !this.isValidEmail(value)) {
                this.showFieldError(field, 'Please enter a valid email address');
                isValid = false;
            } else if (field.type === 'tel' && !this.isValidPhone(value)) {
                this.showFieldError(field, 'Please enter a valid phone number');
                isValid = false;
            } else {
                this.clearFieldError(field);
            }
        });
        
        return isValid;
    }
    
    isValidEmail(email) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    }
    
    isValidPhone(phone) {
        return /^[\+]?[1-9][\d]{0,15}$/.test(phone.replace(/[\s\-\(\)]/g, ''));
    }
    
    showFieldError(field, message) {
        field.classList.add('error');
        
        let errorDiv = field.parentNode.querySelector('.field-error');
        if (!errorDiv) {
            errorDiv = document.createElement('div');
            errorDiv.className = 'field-error';
            field.parentNode.appendChild(errorDiv);
        }
        errorDiv.textContent = message;
    }
    
    clearFieldError(field) {
        field.classList.remove('error');
        const errorDiv = field.parentNode.querySelector('.field-error');
        if (errorDiv) {
            errorDiv.remove();
        }
    }
    
    showFormSuccess(message) {
        const notification = document.createElement('div');
        notification.className = 'form-success-notification';
        notification.innerHTML = `
            <div class="notification-content">
                <span class="success-icon">✓</span>
                <span class="success-message">${message}</span>
            </div>
        `;
         document.body.appendChild(notification);
        requestAnimationFrame(() => {
            notification.classList.add('show');
        });
        setTimeout(() => {
            notification.classList.remove('show');
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.remove();
                }
            }, 300);
        }, 4000);
    }
}
class PerformanceMonitor {
    constructor() {
        this.metrics = {};
        this.init();
    }
    init() {
        window.addEventListener('load', () => {
            this.measurePerformance();
        });
        let scrollStartTime;
        window.addEventListener('scroll', () => {
            if (!scrollStartTime) {
                scrollStartTime = performance.now();
            }
        }, { passive: true });
        
        window.addEventListener('scrollend', () => {
            if (scrollStartTime) {
                const scrollDuration = performance.now() - scrollStartTime;
                this.metrics.scrollPerformance = scrollDuration;
                scrollStartTime = null;
            }
        });
    }
     measurePerformance() {
        if ('performance' in window) {
            const perfData = performance.getEntriesByType('navigation')[0];
            
            this.metrics = {
                pageLoadTime: perfData.loadEventEnd - perfData.loadEventStart,
                domContentLoaded: perfData.domContentLoadedEventEnd - perfData.domContentLoadedEventStart,
                totalPageLoadTime: perfData.loadEventEnd - perfData.fetchStart
            };
            console.log('Performance Metrics:', this.metrics);
        }
    }
}
document.addEventListener('DOMContentLoaded', function() {
    try {
        new LoginModal();
        new AccordionManager();
        new ScrollManager();
        new Chatbot();
        new FormHandler();
        new PerformanceMonitor();
        
        console.log('Food Donation Website: All components initialized successfully');
    } catch (error) {
        console.error('Error initializing components:', error);
    }
});
if (typeof module !== 'undefined' && module.exports) {
    module.exports = {
        LoginModal,
        AccordionManager,
        ScrollManager,
        Chatbot,
        FormHandler,
        Utils
    };
}1
        document.addEventListener('DOMContentLoaded', function() {
            const track = document.getElementById('reviewTestimonialsTrack');
            const prevBtn = document.getElementById('reviewPrevBtn');
            const nextBtn = document.getElementById('reviewNextBtn');
            const dots = document.querySelectorAll('.review-dot');
            let currentSlide = 0;
            const totalSlides = 2;
            function updateSlide() {
                const translateX = -currentSlide * 50;
                track.style.transform = `translateX(${translateX}%)`;
                dots.forEach((dot, index) => {
                    dot.classList.toggle('active', index === currentSlide);
                });
                prevBtn.disabled = currentSlide === 0;
                nextBtn.disabled = currentSlide === totalSlides - 1;
            }
            function nextSlide() {
                if (currentSlide < totalSlides - 1) {
                    currentSlide++;
                    updateSlide();
                }
            }
            function prevSlide() {
                if (currentSlide > 0) {
                    currentSlide--;
                    updateSlide();
                }
            }
            function goToSlide(slideIndex) {
                currentSlide = slideIndex;
                updateSlide();
            }
            nextBtn.addEventListener('click', nextSlide);
            prevBtn.addEventListener('click', prevSlide);
            dots.forEach((dot, index) => {
                dot.addEventListener('click', () => goToSlide(index));
            });
            function startAutoPlay() {
                return setInterval(() => {
                    if (currentSlide < totalSlides - 1) {
                        nextSlide();
                    } else {
                        currentSlide = 0;
                        updateSlide();
                    }
                }, 5000);
            }
            let autoPlayInterval = startAutoPlay();
            const testimonialContainer = document.querySelector('.review-testimonial-container');
            testimonialContainer.addEventListener('mouseenter', () => {
                clearInterval(autoPlayInterval);
            });
            testimonialContainer.addEventListener('mouseleave', () => {
                autoPlayInterval = startAutoPlay();
            });
            updateSlide();
            const avatarImages = document.querySelectorAll('.review-customer-avatar img');
            avatarImages.forEach(img => {
                img.addEventListener('error', function() {
                    this.style.display = 'none';
                    this.nextElementSibling.style.display = 'flex';
                });
            });
            let startX = 0;
            let currentX = 0;
            let isDragging = false;
            
            testimonialContainer.addEventListener('touchstart', (e) => {
                startX = e.touches[0].clientX;
                isDragging = true;
                clearInterval(autoPlayInterval);
            });
            
            testimonialContainer.addEventListener('touchmove', (e) => {
                if (!isDragging) return;
                currentX = e.touches[0].clientX;
            });
            
            testimonialContainer.addEventListener('touchend', () => {
                if (!isDragging) return;
                
                const diffX = startX - currentX;
                const threshold = 50;
                
                if (Math.abs(diffX) > threshold) {
                    if (diffX > 0) {
                        nextSlide();
                    } else {
                        prevSlide();
                    }
                }
                
                isDragging = false;
                autoPlayInterval = startAutoPlay();
            });
            document.addEventListener('keydown', (e) => {
                if (e.key === 'ArrowLeft') {
                    prevSlide();
                } else if (e.key === 'ArrowRight') {
                    nextSlide();
                }
            });
        });