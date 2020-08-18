$('.owl-carousel').owlCarousel({
    loop: false,
    margin: 10,
    responsiveClass: true,
    nav: true,
    responsive: {
        0: {
            items: 1,
        },
        767: {
            items: 2,
        },
        1023: {
            items: 3,
        }
    }
});

$(".nano").nanoScroller();
