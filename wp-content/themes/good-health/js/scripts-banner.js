// get header height (without border)
var getHeaderHeight = jQuery('.head-sticky').outerHeight();
var banner = jQuery('#banner').outerHeight();

var extra = 100;

// init variable for last scroll position
var lastScrollPosition = 0;


jQuery(window).scroll(function() {

  var currentScrollPosition = jQuery(window).scrollTop();

  if (jQuery(window).scrollTop() > banner +  getHeaderHeight + extra) {

    jQuery('body').addClass('scrollActive');
    jQuery('.head-sticky').css('top', 0);

    lastScrollPosition = currentScrollPosition;

  } else {
    jQuery('.head-sticky').css('top', '-' + (getHeaderHeight) + 'px');
  }
});



jQuery(document).ready(function(){
jQuery('a.arrow').on('click',function (e) {
    e.preventDefault();

    var target = this.hash,
    jQuerytarget = jQuery(target);

    jQuery('html, body').stop().animate({
        'scrollTop': jQuerytarget.offset().top
    }, 900, 'swing', function () {
       window.location.href.split('#')[0] = target;
    });
});
});
