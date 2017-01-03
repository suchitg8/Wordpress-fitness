// get header height (without border)
var getHeaderHeight = jQuery('.head-sticky').outerHeight();

// init variable for last scroll position
var lastScrollPosition = 0;

jQuery('.head-sticky').css('top', '-' + (getHeaderHeight) + 'px');

jQuery(window).scroll(function() {
  jQuery('.head-sticky').css('top', '-' + (getHeaderHeight) + 'px');
  var currentScrollPosition = jQuery(window).scrollTop();

  if (jQuery(window).scrollTop() > 2 * (getHeaderHeight) ) {

    jQuery('body').addClass('scrollActive');
    jQuery('.head-sticky').css('top', 0);

    lastScrollPosition = currentScrollPosition;

  } else {
    jQuery('.head-sticky').css('top', '-' + (getHeaderHeight) + 'px');
  }
});
