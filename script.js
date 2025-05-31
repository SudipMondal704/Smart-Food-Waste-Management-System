// Improved Accordion functionality
const accordions = document.querySelectorAll('.accordion');

accordions.forEach(accordion => {
    accordion.addEventListener('click', function() {
        // Toggle active class on clicked accordion
        this.classList.toggle('active');
        
        // Get the panel associated with this accordion
        const panel = this.nextElementSibling;
        
        // Toggle the panel visibility
        if (panel.classList.contains('active')) {
            panel.classList.remove('active');
        } else {
            // Optional: Close all other panels (uncomment if you want only one panel open at a time)
            /*
            accordions.forEach(otherAccordion => {
                if (otherAccordion !== this) {
                    otherAccordion.classList.remove('active');
                    otherAccordion.nextElementSibling.classList.remove('active');
                }
            });
            */
            
            panel.classList.add('active');
        }
    });
});

// Rest of your existing JavaScript code...
// Get the scroll to top button
const scrollToTopBtn = document.getElementById('scrollToTop');

// Show/hide button based on scroll position
window.addEventListener('scroll', function() {
    if (window.pageYOffset > 300) {
        scrollToTopBtn.classList.add('show');
    } else {
        scrollToTopBtn.classList.remove('show');
    }
});

// Smooth scroll to top when button is clicked
scrollToTopBtn.addEventListener('click', function() {
    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });
<<<<<<< HEAD
});

// Chatbot functionality
const messagesContainer = document.getElementById('messages');
const inputField = document.getElementById('input');

// Bot responses
const botResponses = {
    'hello': 'Hello! How can I help you with food donation today?',
    'hi': 'Hi there! I\'m here to assist you with any food donation questions.',
    'donate': 'To donate food, please click on "Donate Now" on our homepage and fill out the form. We\'ll contact you within 24 hours!',
    'food': 'We accept non-perishable items, fresh produce, and cooked meals from restaurants. All food must be safe for consumption.',
    'help': 'I can help you with information about food donation, our process, accepted food types, and more. What would you like to know?',
    'thank': 'You\'re welcome! Thank you for your interest in helping reduce food waste.',
    'thanks': 'You\'re welcome! Thank you for your interest in helping reduce food waste.',
    'contact': 'You can reach us at fooddonate@gmail.com or call (+91) 0000 000 000. We\'re available 24/7!',
    'time': 'We operate 24/7 to ensure no food goes to waste. You can contact us anytime!',
    'location': 'We\'re located at Dewandighi, Katwa Road, Purba Bardhaman, 713102.',
    'default': 'I understand you\'re asking about food donation. Please check our FAQ section below or contact us directly for more specific information!'
};

function addMessage(message, isUser = false) {
    const messageDiv = document.createElement('div');
    messageDiv.className = `response ${isUser ? 'user' : 'bot'}`;
    
    const avatar = document.createElement('div');
    avatar.className = 'avatar';
    avatar.textContent = isUser ? '👤' : '🤖';
    
    const messageContent = document.createElement('div');
    messageContent.className = 'message';
    messageContent.textContent = message;
    
    messageDiv.appendChild(avatar);
    messageDiv.appendChild(messageContent);
    messagesContainer.appendChild(messageDiv);
    
    // Scroll to bottom
    messagesContainer.scrollTop = messagesContainer.scrollHeight;
}

function getBotResponse(userMessage) {
    const lowerMessage = userMessage.toLowerCase();
    
    for (const [key, response] of Object.entries(botResponses)) {
        if (lowerMessage.includes(key)) {
            return response;
        }
    }
    
    return botResponses.default;
}

inputField.addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        const userMessage = inputField.value.trim();
        if (userMessage) {
            addMessage(userMessage, true);
            inputField.value = '';
            
            // Simulate bot typing delay
            setTimeout(() => {
                const botResponse = getBotResponse(userMessage);
                addMessage(botResponse, false);
            }, 1000);
        }
    }
});

// Form submission
document.querySelector('.contact-form').addEventListener('submit', function(e) {
    e.preventDefault();
    alert('Thank you for your message! We will get back to you soon.');
    this.reset();
});
=======

    // Login Modal Functionality
        const loginModal = document.getElementById('loginModal');
        const loginNavBtn = document.getElementById('loginNavBtn');
        const donateNowBtn = document.getElementById('donateNowBtn');
        const closeModal = document.getElementById('closeModal');
        const registerBtn = document.getElementById('register');
        const loginBackBtn = document.getElementById('login');
        const container = document.getElementById('login-container');

        // Show modal when Login nav button is clicked
        loginNavBtn.addEventListener('click', function(e) {
            e.preventDefault();
            loginModal.classList.add('active');
            document.body.style.overflow = 'hidden'; // Prevent background scrolling
        });

        // Show modal when Donate Now button is clicked
        donateNowBtn.addEventListener('click', function(e) {
            e.preventDefault();
            loginModal.classList.add('active');
            document.body.style.overflow = 'hidden'; // Prevent background scrolling
        });

        // Close modal when X button is clicked
        closeModal.addEventListener('click', function() {
            loginModal.classList.remove('active');
            container.classList.remove('active');
            document.body.style.overflow = 'auto'; // Restore scrolling
        });

        // Close modal when clicking outside the login container
        loginModal.addEventListener('click', function(e) {
            if (e.target === loginModal) {
                loginModal.classList.remove('active');
                container.classList.remove('active');
                document.body.style.overflow = 'auto'; // Restore scrolling
            }
        });

        // Close modal when pressing Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && loginModal.classList.contains('active')) {
                loginModal.classList.remove('active');
                container.classList.remove('active');
                document.body.style.overflow = 'auto'; // Restore scrolling
            }
        });

        // Toggle between sign-in and sign-up within the modal
        registerBtn.addEventListener('click', function() {
            container.classList.add("active");
        });

        loginBackBtn.addEventListener('click', function() {
            container.classList.remove("active");
        });
>>>>>>> db9e35e976de88c6a2ef8a813bd9ae9409092913
