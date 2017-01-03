<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/// color defaults are loaded in _include/sm-settings.php
$sm_btns_dir=plugin_dir_url(dirname( __FILE__) )."/_btns/";
$round=" -moz-border-radius:7px; -webkit-border-radius:7px; border-radius:7px; overflow:hidden;";
$css_fade=" -webkit-transition: background 0.2s linear;
        -moz-transition: background 0.2s linear;
        -ms-transition: background 0.2s linear;
        -o-transition: background 0.2s linear;
        transition: background 0.2s linear;";
?>
<style type="text/css">
/* ================================================= START SKEDMAKER STYLES =================================================*/
/*=================================================
======= BTN IMAGES =======
=================================================*/
img.btn{
	border:none;
	margin-right:7px;
	vertical-align:middle;
}

.SM-anchor{
   position:relative;
   top:-150px;
   visibility:hidden;
}

.option_notes{font-size:16px; font-weight:bold;}

/*=================================================
======= FORMS=======
=================================================*/
.form_textfield{
	border:1px solid #999 !important;
	color:#333333;
	font-family:Arial, Helvetica, sans-serif;
	font-size:14px;
	height:35px;
	padding:7px;
	<?php echo $round;?>
	<?php if(!wp_is_mobile()){ ?>width:450px; <?php }else{ ?>width:100%; <?php } ?>
	vertical-align:middle;
}

/*specific for the textfield in the text-check capture */
.form_textfield_cap{
	border:1px solid #999;
	color:#333333;
	font-family:Arial, Helvetica, sans-serif;
	font-size:14px;
	height:35px;
	padding:7px;
	vertical-align:middle;
	<?php if(!wp_is_mobile()){ ?>width:100px; <?php }else{ ?>width:100%; <?php } ?>
	<?php echo $round;?>
}

.form_area{
	border:1px solid #999999;
	color:#333333;
	font-family:Arial, Helvetica, sans-serif;
	font-size:14px;
	padding:7px;
	<?php if(!wp_is_mobile()){ ?>width:450px; <?php }else{ ?>width:100%; <?php } ?>
	<?php echo $round;?>
}

.form_select{
	border:1px solid #999999;
	color:#333333;
	font-family:Arial, Helvetica, sans-serif;
	font-size:12px;
	height:35px;
	padding:7px;
    border-radius:7px 0px 0px 7px;
	-moz-border-radius:7px 0px 0px 7px;
    -webkit-border-radius:7px 0px 0px 7px;
}

/*=================================================
======= LINKS =======
=================================================*/
a.fbtc-menu:link{
	color:#fff;
	font-size:12px;
	font-weight:normal;
	text-decoration:none;
}
a.fbtc-menu:visited{
	color:#fff;
	font-size:12px;
	margin:0px;
	text-decoration:none;
}
a.fbtc-menu:hover{
	color:#<?php echo $b1_text_highlight;?>;
	font-size:12px;
	margin:0px;
	text-decoration:none;
}
a.fbtc-menu:active{
	color:#<?php echo $b1_text_highlight;?>;
	font-size:12px;
	margin:0px;
	text-decoration:none;
}


a.fbtc-menu-home:link{
	color:#000;
	font-size:21px;
	font-weight:normal;
	text-decoration:none;
}
a.fbtc-menu-home:visited{
	color:#000;
	font-size:21px;
	margin:0px;
	text-decoration:none;
}
a.fbtc-menu-home:hover{
	color:#<?php echo $b1_text_highlight;?>;
	font-size:21px;
	margin:0px;
	text-decoration:none;
}
a.fbtc-menu-home:active{
	color:#<?php echo $b1_text_highlight;?>;
	font-size:21px;
	margin:0px;
	text-decoration:none;
}

/* ======= used only on the blueBox for links*/
a.skedblue:link{
	color:#06F;
	font-weight:bold;
	text-decoration:none;
}
a.skedblue:visited{
	color:#06F;
	font-weight:bold;
	text-decoration:none;
}
a.skedblue:hover{
	color:#06F;
	font-weight:bold;
	text-decoration:none;
}
a.skedblue:active{
	color:#06F;
	font-weight:bold;
	text-decoration:none;
}

a.header:link{
/*	color:#000; */
	font-size:28px;
	font-weight:normal;
	text-decoration:none;
}
a.header:visited{
	color:#000;
	font-size:28px;
	font-weight:normal;
	text-decoration:none;
}
a.header:hover{
	color:#000;
	font-size:28px;
	font-weight:normal;
	text-decoration:none;
}
a.header:active{
	color:#000;
	font-size:28px;
	font-weight:normal;
	text-decoration:none;
}

input[type=submit]{padding:7px;}

/*=================================================
======= HR =======
=================================================*/
hr {background-color:#366; border:0px; color:#366; height:2px;}

/*=================================================
======= TABLES =======
=================================================*/
<?php 
if ( wp_is_mobile() ){ ?>
table.cc100{
	background-color:transparent;
	border:none;
	*border-collapse:expression('separate', cellSpacing = '0px');
	border-spacing:0px;
	box-shadow:none;
	margin:0px;
	padding:0px;
	width:100%;
}

table.cc800{
	background-color:transparent;
	border:none; 
	*border-collapse:expression('separate', cellSpacing = '0px');
	border-spacing:0px;
	box-shadow:none;
	margin:0px;
	padding:0px;
	width:100%;
}
<?php }else{ ?>
table.cc100{
	background-color:transparent;
	border:none;
	*border-collapse:expression('separate', cellSpacing = '0px');
	border-spacing:0px;
	box-shadow:none;
	margin:0px;
	padding:0px;
	width:100%;
}

table.cc800{
	background-color:transparent;
	border:none;
	border-spacing:0px;
	*border-collapse:expression('separate', cellSpacing = '0px');margin:0px;
	box-shadow:none;
	padding:0px;
	width:800px;
}
<?php } ?>

.gEmpty{background-color:#e9e9e9; padding:7px; color:#ccc; text-align:center;<?php echo $round;?>;}
.read-page-selected{background-color:#C2DAE2; padding:7px; color:#666; text-align:center;<?php echo $round;?>;}


/*=================================================
======= TR =======
=================================================*/
tr.g666{
	background-color:#<?php echo $b1_color;?>;
	box-shadow:none;
	<?php echo $round;?>
}

/*
tr.menubox{
	background-color:#ccc;
	border-bottom:1px dotted #666;
	box-shadow:none;
}
*/

tr.stagger{
	background-color:#e9e9e9;
	box-shadow:none;
}

/*=================================================
======= TD =======
=================================================*/

td.menu{
	border-bottom:1px dotted #666;
	box-shadow:none;
	padding:0px;
	text-align:center;
}

.gBox{
	background-color:#E6E6E6;
	box-shadow:none;
	padding:5px;
	text-align:left;
}

td.redBox{
	border-collapse:separate;
	background-color:#FCC;
	border:3px solid #F00;
	box-shadow:none;
	overflow:hidden;
	padding:14px;
	<?php echo $round;?>
}

td.greenBox{
	border-collapse:separate;
	background-color:#CFC;
	border:3px solid #093;
	box-shadow:none;
	padding:14px;
	text-align:center;
	<?php echo $round;?>
}

td.blueBox{
	background-color:#E2F8FE;
	border:3px solid #06F;
	box-shadow:none;
	padding:14px;
	text-align:center;
	<?php echo $round;?>
}

td.grayBox{
	background-color:#e9e9e9;
	border:3px solid #666;
	box-shadow:none;
	color:#666;
	padding:14px;
	text-align:center;
	<?php echo $round;?>
}

td.orangeBox{
	background-color:#FFC;
	border:2px solid #F63;
	box-shadow:none;
	color:#F63;
	font-weight:bold;
	padding:14px;
	<?php echo $round;?>
}

td.btn{
	background-color:#88B3CA;
	border:1px solid #233C49;
	box-shadow:none;
	padding:0px;
	vertical-align:middle;
}

td.b1{
	background-color:#<?php echo $b1_color;?>;
	border:none;
	box-shadow:none;
	color:#fff;
	font-weight:bold;	
	<?php if(wp_is_mobile()){?>font-size:16px; <?php }else{?> font-size:18px; <?php } ?>
	overflow:hidden;
	padding:7px 7px 7px 7px;
    border-top-left-radius:7px;
    -moz-border-radius-topleft:7px;
    -webkit-border-top-left-radius:7px;
    border-top-right-radius:7px;
    -moz-border-radius-topright:7px;
    -webkit-border-top-right-radius:7px;
}

td.b2{
	background-color:#<?php echo $b2_color;?>;
	border:none;
	box-shadow:none;
	overflow:hidden;
	padding:10px; border:1px solid #<?php echo $b1_color; ?>;
	text-align:left;
	border-bottom-left-radius:7px;
	-moz-border-radius-bottomleft:7px;
	-webkit-border-bottom-left-radius:7px;
	border-bottom-right-radius:7px;;
	-moz-border-radius-bottomright:7px;
	-webkit-border-bottom-right-radius:7px;
}

td.b2-only{
	background-color:#<?php echo $b2_color;?>;
	border:1px solid #<?php echo $b1_color; ?>;
	padding:14px;
	text-align:left;<?php echo $round; ?>
}

/*=================================================
======= PADDING TD
=================================================*/
td.nopad{
	background-color:transparent;
	border:none;
	box-shadow:none;
	color:#000; 
	font-size:14px;
	padding:0px;
}

td.pad7{
	background-color:transparent;
	border:none;
	box-shadow:none;
	color:#000;
	font-size:14px;
	padding:7px;
	text-align:left;
}

td.pad7center{
	background-color:transparent;
	border:none;
	box-shadow:none;
	color:#000;
	font-size:14px;
	padding:7px;
	text-align:center;
}

td.pad14{
	background-color:transparent;
	border:none;
	box-shadow:none;
	color:#000; 
	font-size:14px;
	padding:14px;
	text-align:left;
}

td.pad21{
	background-color:transparent;
	border:none;
	box-shadow:none;
	color:#000;
	font-size:14px;
	padding:21px;
	text-align:left;
}

/*=================================================
======= FORM LABEL TDs
=================================================*/
<?php if(wp_is_mobile()){$label150="15%";}else{$label150="150px";}?>

td.label50{
	background-color:#<?php echo $b2_color;?>;
	border:none;
	box-shadow:none;
	padding:7px;
	text-align:right;
	font-weight:bold;
	vertical-align:middle;
	width:50px;
	font-size:16px;
	color:#000;
}

td.label100{
	background-color:#<?php echo $b2_color;?>;
	border:none;
	box-shadow:none;
	color:#000;
	font-size:14px;
	font-weight:bold;
	padding:7px;
	text-align:right;
	vertical-align:middle;
	width:100px;
}

td.label100top{
	background-color:#<?php echo $b2_color;?>;
	border:none;
	box-shadow:none;
	color:#000;
	font-weight:bold;
	padding:7px;
	text-align:right;
	vertical-align:top;
	width:100px;
}

td.label150{
	background-color:#<?php echo $b2_color;?>;
	border:none;
	box-shadow:none;
	color:#000;
	font-weight:bold;
	padding:7px;
	text-align:right;
	vertical-align:middle;
	width:15%;
}

td.label150top{
	background-color:#<?php echo $b2_color;?>;
	border:none;
	box-shadow:none;
	color:#000;
	font-weight:bold;
	padding:7px;
	text-align:right;
	vertical-align:top;
	width:150px;
}

td.label200{
	background-color:#<?php echo $b2_color;?>;
	border:none;
	box-shadow:none;
	color:#000;
	font-size:14px;
	font-weight:bold;
	padding:7px;
	text-align:right;
	vertical-align:middle;
	width:200px;
}

/*=================================================
======= COLUMN HEADERS ON LISTS
=================================================*/
.tab{
	background-color:#<?php echo $b1_color;?>;
/*	color:#fff; */
	font-size:14px;
	font-weight:bold;
	padding:0px;
	text-align:left;
}

.tab-left{
	background-color:#<?php echo $b1_color;?>;
	border-left:1px solid #<?php echo $b1_color;?>;
/*	color:#fff; */
	font-size:14px;
	font-weight:bold;
	padding:0px; 
	text-align:left;
	border-top-left-radius:7px;
	-moz-border-radius-topleft:7px;
	-webkit-border-top-left-radius:7px;
	overflow:hidden;
}

.tab-right{
	background-color:#<?php echo $b1_color;?>;
	color:#fff;
	font-size:14px;
	font-weight:bold;
	padding:0px; 
	text-align:left;
    border-top-right-radius:7px;
    -moz-border-top-right-radius:7px;
    -webkit-border-top-right-radius:7px;
	overflow:hidden;
}

.tab-bottom-right{
	background-color:#<?php echo $b1_color;?>;
	color:#fff;
	font-size:14px;
	font-weight:bold;
	padding:0px;
	text-align:left;
	border-bottom-right-radius:7px;
	-moz-border-bottom-right-radius:7px;
	-webkit-border-bottom-right-radius:7px;
	overflow:hidden;
}

.tab-bottom-left{
	background-color:#<?php echo $b1_color;?>;
	color:#fff;
	font-weight:bold;
	font-size:14px;
	padding:0px; 
	text-align:left;
    border-bottom-left-radius:7px;
    -moz-border-bottom-left-radius:7px;
    -webkit-border-bottom-left-radius:7px;
	overflow:hidden;	
}

/*=================================================
======= LIST ITEM TDs 
=================================================*/
td.list_left{
	border-left:1px solid #<?php echo $b1_color;?>;
	border-right:1px dotted #666;
	padding:0px;
	text-align:left;
}

td.list_center{
	border-right:1px dotted #666;
	text-align:left;
	padding-left:14px;
}

td.list_right{
	border-right:1px solid #<?php echo $b1_color;?>;
}

td.list_bottom{
	border-top:1px solid #<?php echo $b1_color;?>;
	padding:0px;
}

/*=================================================
======= SMALL MENU BUTTONS
=================================================*/
td.g666{
	background-color:#<?php echo $b1_color;?>;
	border:1px solid #<?php echo $b1_color;?>;
	margin:0px;
	padding:0px;
	text-align:center;
	vertical-align:middle;
}

/*=================================================
======= TEXT =======
=================================================*/
.header {
	background-color:none;
	box-shadow:none;
	color:#000;
	font-size:28px;
	font-weight:normal;
}

.redText{
	color:#F00;
/*	font-size:14px; */
	font-weight:bold;
}

.greenText{
	color:#009900;
	font-size:14px;
	font-weight:bold;
}

.blueText{
	color:#06F;
	font-size:14px;
	font-weight:bold;
}

.smallRed{
	color:#F00;
	font-size:10px;
	font-weight:bold;
}

.smallText{
	color:#000;
	font-size:10px;
}

.smallG{
	color:#666;
	font-size:10px;
}

.whiteText{
	color:#FFF;
	font-size:14px;
	font-weight:bold;
}

.whiteBold{
	color:#FFF;
	font-size:14px;
	font-weight:bold;
}

/*=================================================
======= NAVS =======
=================================================*/
.navb1 a:link{
	background-color:#<?php echo $b1_color;?>;
	color:#fff;
	display:block;
	padding:7px;
	text-decoration:none;
/*	border:1px solid #<?php echo $b1_color; ?>; */
	<?php echo $round; echo $css_fade;?>
}
.navb1 a:visited{
	background-color:#<?php echo $b1_color;?>;
	color:#fff;
	display:block;
	padding:7px;
	text-decoration:none;
/*	border:1px solid #<?php echo $b1_color; ?>; */
	<?php echo $round; ?>
}
.navb1 a:hover{
	background-color:#<?php echo $b1_highlight;?>;
	color:#<?php echo $b1_text_highlight;?>;
	display:block;
	padding:7px;
	text-decoration:none;
/*	border:1px solid #<?php echo $b1_color; ?>; */
	<?php echo $round; ?>
}
.navb1 a:active{
	background-color:#<?php echo $b1_highlight;?>;
	color:#<?php echo $b1_text_highlight;?>;
	display:block;
	padding:7px;
	text-decoration:none;
/*	border:1px solid #<?php echo $b1_color; ?>; */
	<?php echo $round; ?>
}

.navMenuHome a:link{
	background-color:none;
	color:#000;
	display:block;
	padding:21px;
	text-decoration:none;
	<?php echo $round; echo $css_fade;?>
}
.navMenuHome a:visited{
	background-color:none;
	color:#000;
	display:block;
	padding:21px;
	text-decoration:none;
	<?php echo $round; ?>
}
.navMenuHome a:hover{
	background-color:#<?php echo $b1_highlight;?>;
	color:#<?php echo $b1_text_highlight;?>;
	display:block;
	padding:21px;
	text-decoration:none;
	<?php echo $round; ?>
}
.navMenuHome a:active{
	background-color:#<?php echo $b1_highlight;?>;
	color:#<?php echo $b1_text_highlight;?>;
	display:block;
	padding:21px;
	text-decoration:none;
	<?php echo $round; ?>
}

.navMenu a:link{
	<?php echo $round; ?>
	background-color:none;
	color:#000;
	display:block;
	margin:0px;
	padding:7px;
	text-decoration:none;
}

.navMenu a:visited{
	<?php echo $round; ?>
	background-color:none;
	color:#000;
	display:block;
	padding:7px;
	text-decoration:none;
}

.navMenu a:hover{
	<?php echo $round; ?>
	background-color:#<?php echo $b1_highlight;?>;
	color:#fff;
	display:block;
	padding:7px;
	text-decoration:none;
}

.navMenu a:active{
	<?php echo $round; ?>
	background-color:#<?php echo $b1_highlight;?>;
	color:#fff;
	display:block;
	padding:7px;
	text-decoration:none;
}

.navColumnTop a:link {display: block; background-color: #<?php echo $b1_color;?>; text-decoration: none; padding:7px; color:#fff; font-weight:bold; overflow:hidden;<?php echo  $css_fade;?>}
.navColumnTop a:visited	{display: block;	background-color: #<?php echo $b1_color;?>; 	text-decoration: none; padding:7px; color:#fff; font-weight:bold; overflow:hidden;}
.navColumnTop  a:hover		{display: block;	background-color: #<?php echo $b1_highlight;?>;	text-decoration: none; padding:7px; color:#<?php echo $b1_text_highlight;?>; font-weight:bold; overflow:hidden;}
.navColumnTop a:active	{display: block;	background-color: #<?php echo $b1_highlight;?>;	text-decoration: none; padding:7px; color:#<?php echo $b1_text_highlight;?>; font-weight:bold; overflow:hidden;}

.navListItem a:link {display: block; background-color:none; text-decoration: none; padding:7px; padding-left:14px; color:#<?php echo $b1_color;?>; <?php echo  $css_fade;?>}
.navListItem a:visited {display: block; background-color:none; text-decoration:none; padding:7px; padding-left:14px; color:#<?php echo $b1_color;?>;}
.navListItem a:hover {display: block; background-color:#<?php echo $b1_highlight;?>; text-decoration: none; padding:7px; padding-left:14px; color:#<?php echo $b1_text_highlight;?>;}
.navListItem a:active {display: block; background-color:#<?php echo $b1_highlight;?>; text-decoration: none; padding:7px; padding-left:14px; color:#<?php echo $b1_text_highlight;?>;}

.navListAction a:link 		{display: block; background-color:none; text-decoration: none; padding:7px; color:#<?php echo $b1_color;?>; <?php echo  $css_fade;?>}
.navListAction a:visited	{display: block; background-color:none; text-decoration:none; padding:7px; color:#<?php echo $b1_color;?>;}
.navListAction a:hover 		{display: block; background-color:#<?php echo $b1_highlight;?>; text-decoration:none; padding:7px; color:#<?php echo $b1_text_highlight;?>;}
.navListAction a:active 	{display: block; background-color:#<?php echo $b1_highlight;?>; text-decoration:none; padding:7px; color:#<?php echo $b1_text_highlight;?>;}

.navOps a:link		{display: block;	background-color: #666;		text-decoration: none; padding:7px; color:#fff; font-weight:bold; overflow:hidden;<?php echo  $css_fade; echo $round;?>}
.navOps a:visited	{display: block;	background-color: #666; 	text-decoration: none; padding:7px; color:#fff; font-weight:bold; overflow:hidden; <?php echo $round;?>}
.navOps a:hover		{display: block;	background-color: #<?php echo $b1_highlight;?>;	text-decoration: none; padding:7px; color:#<?php echo $highlight_text;?>; font-weight:bold; overflow:hidden;<?php echo $round;?>}
.navOps a:active	{display: block;	background-color: #<?php echo $b1_highlight;?>;	text-decoration: none; padding:7px; color:#<?php echo $highlight_text;?>; font-weight:bold; overflow:hidden;<?php echo $round;?>}

.navGreen a:link{
	background-color:#090;
	color:#fff;
	display:block;
	padding:7px;
	text-decoration:none;
}
.navGreen a:visited{
	background-color:#090;
	color:#fff;
	display:block;
	padding:7px;
	text-decoration:none;
}
.navGreen a:hover{
	background-color:#0C0;
	color:#fff;
	display:block;
	padding:7px;
	text-decoration:none;
}
.navGreen a:active{
	background-color:#0C0;
	color:#fff;
	display:block;
	padding:7px;
	text-decoration:none;
}

.navRed a:link{
	background-color:#F33;
	color:#fff;
	display:block;
	height:100%;
	padding:7px;
	text-decoration:none;
	<?php echo $round; echo $css_fade;?>
}
.navRed a:visited{
	background-color:#F33;
	color:#fff;
	display:block;
	height:100%;
	padding:7px;
	text-decoration:none;
	<?php echo $round;?>
}
.navRed a:hover{
	background-color:#C00;
	color:#fff;
	display:block;
	height:100%;
	padding:7px;
	text-decoration:none;
	<?php echo $round;?>
}
.navRed a:active{
	background-color:#C00;
	color:#fff;
	display:block;
	height:100%;
	padding:7px;
	text-decoration:none;
	<?php echo $round;?>
}

.navCancel a:link{
	background-color:transparent;
	color:#000;
	display:block;
	height:100%;
	padding:7px;
	text-decoration:none;
	<?php echo $round;?>
}
.navCancel a:visited{
	height:100%;
	display:block;
	background-color:transparent;
	text-decoration:none;
	padding:7px;
	color:#000;
	<?php echo $round;?>
}
.navCancel a:hover{
	background-color:#F03;
	color:#fff;
	display:block;
	height:100%;
	padding:7px;
	text-decoration:none;
	<?php echo $round;?>
}
.navCancel a:active{
	background-color:#F03;
	color:#fff;
	display:block;
	height:100%;
	padding:7px;
	text-decoration:none;
	<?php echo $round;?>
}

.navPurge a:link{
	background-color:#none;
	color:#000;
	display:block;
	height:100%;
	padding:7px;
	text-decoration:none;
	<?php echo $round; echo $css_fade?>
}
 
.navPurge a:visited{
	background-color:#none;
	color:#000;
	display:block;
	height:100%;
	padding:7px;
	text-decoration:none;
	<?php echo $round;?>
}
.navPurge a:hover{
	background-color:#F00;
	color:#fff;
	display:block;
	height:100%;
	padding:7px;
	text-decoration:none;
	<?php echo $round;?>
}
.navPurge a:active{
	background-color:#F00;
	color:#FFF;
	display:block;
	height:100%;
	padding:7px;
	text-decoration:none;
	<?php echo $round;?>
}

.navBlue a:link{
	background-color:#09F;
	<?php echo $round; echo $css_fade; ?>
	color:#fff;
	display:block;
	height:100%;
	padding:7px;
	text-decoration:none;
}
.navBlue a:visited{
	<?php echo $round;?>
	background-color:#09F;
	color:#fff;
	display:block;
	height:100%;
	padding:7px;
	text-decoration:none;
}
.navBlue a:hover{
	<?php echo $round;?>
	background-color:#0CF;
	color:#fff;
	display:block;
	height:100%;
	padding:7px;
	text-decoration:none;
}
.navBlue a:active{
	<?php echo $round;?>
	background-color:#0CF;
	color:#fff;
	display:block;
	height:100%;
	padding:7px;
	text-decoration:none;
}

#button{
    <?php echo $css_fade; ?>
    background-color:#<?php echo $b1_color;?>;
    -moz-border-radius:5px;
    -webkit-border-radius:5px;
    border-radius:6px;
    color:#fff;
    font-size:14px;
    text-decoration:none;
    cursor:pointer;
    border:none;
	padding:7px;
	padding-left:28px;
	padding-right:14px;
	background:#<?php echo $b1_color;?> url("<?php echo $sm_btns_dir;?>btn_save16_g.png") no-repeat scroll 5px center;
}
#button:hover {
	border:none;
	color:#<?php echo $b1_text_highlight;?>;
	background:#<?php echo $b1_highlight;?> url("<?php echo $sm_btns_dir;?>btn_save16_reg.png") no-repeat scroll 5px center;
    box-shadow:0px 0px 1px #777;
}

#purge {
    <?php echo $css_fade; ?>
	border:2px solid #F00;
	padding:7px;
	color:#fff;
	font-weight:bold; cursor:pointer;
    -moz-border-radius:5px;
    -webkit-border-radius:5px;
    border-radius:6px;
    color:#fff;
    font-size:14px;
    text-decoration:none;
    cursor:pointer;
    border:none;
	padding:7px;
	padding-left:28px;
	padding-right:14px;
	background:#C30 url("<?php echo $sm_btns_dir;?>btn_purge16_g.png") no-repeat scroll 5px center;
}
#purge:hover {
    border:none;
    box-shadow:0px 0px 1px #777;
	background:#F60 url("<?php echo $sm_btns_dir;?>btn_purge16_reg.png") no-repeat scroll 5px center;
}

#contact {
    background-color:#<?php echo $b1_color;?>;
    -moz-border-radius:5px;
    -webkit-border-radius:5px;
    border-radius:6px;
    color:#fff;
    font-size:14px;
    text-decoration:none;
    cursor:pointer;
    border:none;
	padding-left:28px;
	padding-right:14px;
	background:#<?php echo $b1_color;?> url("<?php echo $sm_btns_dir;?>btn_sendmail16_reg.png") no-repeat scroll 5px center;
}
#contact:hover {
    border:none;
	color:#<?php echo $b1_text_highlight;?>;
	background-color:#<?php echo $b1_highlight;?>;
    box-shadow:0px 0px 1px #777;
}

#trash {
    background-color:#<?php echo $b1_color;?>;
    -moz-border-radius:5px;
    -webkit-border-radius:5px;
    border-radius:6px;
    color:#fff;
    font-size:14px;
    text-decoration:none;
    cursor:pointer;
    border:none;
	padding-left:28px;
	padding-right:14px;
	background:#900 url("<?php echo $sm_btns_dir;?>btn_delete16_reg.png") no-repeat scroll 5px center;
}

#trash:hover {
    border:none;
    box-shadow:0px 0px 1px #777;
	background:#F00 url("<?php echo $sm_btns_dir;?>btn_delete16_reg.png") no-repeat scroll 5px center;
}
</style>