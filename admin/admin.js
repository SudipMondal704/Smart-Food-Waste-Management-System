$(document).ready(function() {
    // Toggle submenu
    $(".menu > ul > li").click(function(e) {
        // Prevent submenu toggle when clicking on logout
        if ($(this).find('a').hasClass('logout')) {
            return;
        }
        
        $(this).siblings().removeClass("active");
        $(this).toggleClass("active");
        $(this).find(".sub-menu").slideToggle();
        $(this).siblings().find(".sub-menu").slideUp();
        $(this).siblings().find(".sub-menu").find("li").removeClass("active");
    });
    
    // Enable clicking on submenu items without toggling parent
    $(".sub-menu li").click(function(e) {
        e.stopPropagation();
    });
    
    // Toggle sidebar
    $(".toggle-menu").click(function() {
        $(".sidebar").toggleClass("hide");
        $(".topbar").toggleClass("expand");
        $(".content").toggleClass("expand");
    });
    
    // Search button functionality
    $(".search-btn").click(function(e) {
        e.preventDefault();
        const searchTerm = $(".search input").val().trim();
        if (searchTerm !== "") {
            // You can implement search functionality here
            alert("Searching for: " + searchTerm);
        }
    });
    
    // Also trigger search on Enter key press
    $(".search input").keypress(function(e) {
        if (e.which === 13) {
            e.preventDefault();
            $(".search-btn").click();
        }
    });
});