/*jQuery User Role Base Menu plugin jQuery functions 
 *Date 2014-02-25
 *@Since 0.0.2
 */
//rj = role base jQuery variable
var $rj = jQuery.noConflict();
(function($rj) {
	$rj('.widget_user_role_base_widget_id ul li').addClass('rj_expanded_parent');
	$rj('.widget_user_role_base_widget_id ul li ul li').addClass('rj_leaf');
	$rj('.widget_user_role_base_widget_id ul li ul li').removeClass('rj_expanded_parent');
	$rj('.rj_expanded_parent' ).prepend( "<img src='wp-content/plugins/user-role-sidebar-menu/img/parent.png' /> " );
	$rj('.rj_leaf').prepend( "<img src='wp-content/plugins/user-role-sidebar-menu/img/leaf.png' /> " );
	$rj('.widget_user_role_base_widget_id ul ul').hide();
	$rj('.widget_user_role_base_widget_id ul li.rj_expanded_parent > a').mouseover(function(event){
		$rj(this).parent().find('ul').slideDown(400);
	});
	$rj('.widget_user_role_base_widget_id ul li.rj_expanded_parent').mouseleave(function(event){
		$rj(this).parent().find('ul').slideUp(450);
	});
})(jQuery);
