
var container = document.querySelector('.container');
var masonry;
imagesLoaded( container, function() {
masonry = new Masonry( container, {
// options
itemSelector: '.item',
gutter: ".gutter-sizer",
stamp: ".widget-area-wrap"
});
});

jQuery('.carousel-area-wrap').slick({
    infinite: true,
    speed: 400,
    slidesToScroll: 1,
    autoplay: true,
    autoplaySpeed: 5000,
    slidesToShow: 4,
    arrows: false,
    responsive: [{
            breakpoint: 1039,
            settings: {
                slidesToShow: 3,
                centerMode: true,
            }

        }, {
            breakpoint: 839,
            settings: {
                slidesToShow: 2,
                slidesToScroll: 1
            }
        },
        {
            breakpoint: 499,
            settings: {
                slidesToShow: 1,
                slidesToScroll: 1
            }
        },

    ]

});