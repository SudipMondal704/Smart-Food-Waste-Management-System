$(document).ready(function() {
    $(".menu > ul > li").click(function(e) {
        if ($(this).find('a').hasClass('logout')) {
            return;
        }
         $(this).siblings().removeClass("active");
        $(this).toggleClass("active");
        $(this).find(".sub-menu").slideToggle();
        $(this).siblings().find(".sub-menu").slideUp();
        $(this).siblings().find(".sub-menu").find("li").removeClass("active");
    });
    $(".sub-menu li").click(function(e) {
        e.stopPropagation();
    });
    $(".toggle-menu").click(function() {
        $(".sidebar").toggleClass("hide");
        $(".topbar").toggleClass("expand");
        $(".content").toggleClass("expand");
    });
    $(".search-btn").click(function(e) {
        e.preventDefault();
        const searchTerm = $(".search input").val().trim();
        if (searchTerm !== "") {
            alert("Searching for: " + searchTerm);
        }
    });
    $(".search input").keypress(function(e) {
        if (e.which === 13) {
            e.preventDefault();
            $(".search-btn").click();
        }
    });
});