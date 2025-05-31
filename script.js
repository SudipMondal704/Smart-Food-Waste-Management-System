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
    });

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
