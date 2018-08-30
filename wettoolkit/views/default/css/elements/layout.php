<?php
/**
 * Page Layout
 ************Changed for EasyTheme (line 25)****************
 * Contains CSS for the page shell and page layout
 *
 * Default layout: 990px wide, centered. Used in default page shell
 *
 */

?>headingdfdf

/* ***************************************
	PAGE LAYOUT
*************************************** */
/***** DEFAULT LAYOUT ******/
<?php // the width is on the page rather than topbar to handle small viewports ?>
/*.elgg-page-default {
	-moz-box-shadow: 0px 0px 10px 4px white !important;
	-webkit-box-shadow: 0px 0px 10px 4px white !important;
	box-shadow: 0px 0px 10px 4px white !important;
	width: 100%;

}*/



/*-----------Page Width ------------*/

.elgg-page-default .elgg-page-footer > .elgg-inner {
	width: 990px;
	margin: 0 auto;
	padding: 5px 0;
	border-top: 0px solid #DEDEDE;
	
}

/*-- Stretch menu bar 100% of body and align on page --*/
ul.elgg-menu.elgg-menu-site.elgg-menu-site-default.clearfix {
	width: 100%;
	position: relative;
	top: 0px;
}

/*---------- Hides default Elgg text in h1 --------- */
h3 {
	width: 100% !important;
}



.elgg-module-livesearch {
width: 300px !important;

}
.elgg-module-livesearch > .elgg-head h3 {
background-color: black;
}
.elgg-module-livesearch > .elgg-head {
background: black;
}

/*------------Elgg Login Button Dropdown--------------*/
.elgg-button-dropdown {
padding: 3px 6px;
text-decoration: none;
display: block;
font-weight: bold;
position: relative;
margin-left: 0;
color: white;
border: 1px solid #71B9F7;
-webkit-border-radius: 4px;
-moz-border-radius: 4px;
border-radius: 4px;
-webkit-box-shadow: 0 0 0;
-moz-box-shadow: 0 0 0;
box-shadow: 0 0 0;
top: 25px;
right: 20px;
}


/*-------- Removes 1px of white bg not required --------*/
.elgg-page-body {
	background: none !important;
	max-width:1200px;
}

/*------ Index Widgets ----------*/

body {
	background-color: transparent !important;
}


.elgg-widget-content {
    -moz-box-shadow:    3px 3px 2px 6px white;
  -webkit-box-shadow: 3px 3px 2px 6px white;
  box-shadow:         3px 3px 2px 6px white;

}
/***** TOPBAR ******/
.elgg-page-topbar {
	background: #000099;
	border-bottom: 1px solid #000000;
	position: relative;
	height: 24px;
}
.elgg-page-topbar > .elgg-inner {
	padding: 0 10px;
}

/***** PAGE MESSAGES ******/
.elgg-system-messages li {
	margin-top: 10px;
}
.elgg-system-messages li p {
	margin: 0;
}


/***** PAGE BODY LAYOUT ******/
.elgg-layout {
	min-height: 360px;
}
.elgg-layout-one-sidebar {
	background: transparent url(<?php echo elgg_get_site_url(); ?>mod/easytheme/graphics/sidebar_background.gif) repeat-y right top;
}
.elgg-layout-two-sidebar {
	background: transparent url(<?php echo elgg_get_site_url(); ?>_graphics/two_sidebar_background.gif) repeat-y right top;
}
.elgg-sidebar {
	position: relative;
	padding: 20px 10px;
	float: right;
	width: 210px;
	margin: 0 0 0 10px;
	
}
.elgg-sidebar-alt {
	position: relative;
	padding: 20px 10px;
	float: left;
	width: 160px;
	margin: 0 10px 0 0;
	
}
.elgg-main {
	position: relative;
	min-height: 360px;
	padding: 10px;
}
.elgg-page-body {
	background: #FFF;
}
.elgg-layout-one-sidebar {
	background: #EEECE9;



}
/*------------------------------CHANGE CSS IN THIS FILE ONLY-----------------------------------------------------*/
/*------- Change the color of all anchors in the body ------*/
.elgg-body a {
	color: #1D63A3;
}
ul.elgg-list.elgg-list-entity > li:hover {
	background: white;
}
ul.elgg-list.elgg-list-river.elgg-river > li:hover {
	background: white;
}
ul.elgg-gallery.tidypics-gallery > li:hover {
	background: white;
}

/*----------------------------------------DROPDOWN STYLES-----------------------------*/
ul.elgg-menu.elgg-child-menu {
	background: #EEECE9;
	
}
ul.elgg-menu.elgg-child-menu > li a {
	color: #555555;
}
a.elgg-menu-closed.elgg-menu-parent {
	padding-right: 20px;
	background: url(<?php echo elgg_get_site_url(); ?>mod/wettoolkit/graphics/elgg_sprites.png) no-repeat scroll right center transparent;
	background-position-y: 3px;


}
.elgg-menu-site-default > .elgg-state-selected > a.elgg-menu-closed.elgg-menu-parent, .elgg-menu-site-default > li:hover > a.elgg-menu-closed.elgg-menu-parent {
	background:url(<?php echo elgg_get_site_url(); ?>mod/wettoolkit/graphics/elgg_sprites.png) no-repeat scroll right center transparent;
	background: #EEECE9;
}


.elgg-page-topbar {
	background-image:url(<?php echo elgg_get_site_url(); ?>mod/wettoolkit/graphics/gradient.png);
	z-index: 1;
}

.elgg-main > .elgg-head {
	padding-bottom: 3px;
	border-bottom: 1px solid #CCCCCC;
	margin-bottom: 10px;
}

.elgg-menu-site-default > .elgg-state-selected > a, .elgg-menu-site-default > li:hover > a {
	background: #EEECE9;
	
	border-bottom: 4px solid #EEECE9;
	
}
.elgg-menu-site-default > .elgg-state-selected, .elgg-menu-site-default > li {
		top: -4px;
		padding-bottom: 3px
}

.elgg-module-widget > .elgg-head {
	background-color: #000099;
}

.elgg-page-default .elgg-state-header . elgg-inner {
	width: 100%;
	height: 120px;
}
.elgg-module-widget a.widget-manager-widget-title-link {
	color: white;
}
.elgg-page-header {
	position: relative;
}






/*----------*/

#wb-main-in a:visited, #wb-main-in a.ui-link:visited {
color: black;
}

.elgg-menu-site-default > li > a {
color: white !important;
padding-top: 10px !important;

}



.elgg-menu-site-default > .elgg-state-selected > a, .elgg-menu-site-default > li:hover > a {
background: #145788 !important;
border-bottom: 4px solid #000000 !important;
padding-top: 10px !important;
}



ul.elgg-menu.elgg-menu-entity.elgg-menu-hz.elgg-menu-entity-default a {
	color: #1D63A3;

}
span.elgg-icon.elgg-icon-delete {
	background-position: 0px -252px !important;
}
a.elgg-button.elgg-button-submit {
	color: white;

}
a.elgg-button.elgg-button-delete {
	color: white;
}


/*******************------------ Bookmark Icon - Topbar ---------------**********/
.elgg-menu-item-bmark a {
	width: 16px;
	height: 24px;
	background: url(<?php echo elgg_get_site_url(); ?>/mod/wettoolkit/graphics/bmark.gif) no-repeat;
	background-position: 0px 5.5px;

}

.elgg-menu-item-bmark a:hover {
	width: 16px;
	height: 24px;
	background: url(<?php echo elgg_get_site_url(); ?>mod/wettoolkit/graphics/bmark_hover1.png) no-repeat;
	background-position: 0px 5.5px;

}
/*--------------------------Groups UI-------------------------*/

/***** PAGE FOOTER ******/
.elgg-page-footer {
	position: relative;
}
.elgg-page-footer {
	color: #999;
}
.elgg-page-footer a:hover {
	color: #666;
}



/* these are for narrowing the WET body content to match elgg  -------------------------------------- */

/* ---- these are unused (old attempt that didnt pan out with ie7)
#wb-body #wb-main{
	width:990px !important;
}
#gcwu-psnb .mb-menu, #wb-core-in, #gcwu-gcnb-in, #gcwu-bnr-in, #gcwu-psnb-in, #gcwu-bc-in, #gcwu-gcft-in, #gcwu-sft-in{
	width:990px !important;
}
#wb-main-in{
	padding-bottom:10px !important;
}
*/

.elgg-page-body{
	padding:10px;
}

.elgg-page-default .elgg-page-body > .elgg-inner, .elgg-page-default {
	width: 100% !important;
	*width: 99% !important;
}
.elgg-page-default{
	-webkit-box-shadow: none !important;
	-box-shadow: none !important;
	min-width:0 !important;
	display:table !important;
}
/*
.elgg-body{
	width:990px !important;
}
*/

/* this is for the left profile nav on the profile page --------------------------------------------- */

.elgg-menu-owner-block li a:hover{
	color: #FFF !important;
}

/* this is for a similar navigation on the webinar page --------------------------------------------- */

.elgg-menu-page li a:hover, .elgg-menu-page li.elgg-state-selected a{
	color: #FFF !important;
}


/* this sets widget titles to white (WET styles were overridin) */

.elgg-module-widget a.widget-manager-widget-title-link{
	color:#FFF !important;
}

/******** CDA LT CUSTOM STYLES *******/
#wb-core-in{
	padding:0;
}

#wb-main-in a:visited, #wb-main-in a.ui-link:visited{
	color:#1D63A3;
}

	/******** Forum Plugin ********/
	.hj-framework-list-wrapper{
		position:default !important;
	}
	.hj-forum-post-body{
		width:78%;
		float:left;
	}

	.hj-forum-post-footer{
		width:20%;
		float:left;
	}

	article.attachments{
		clear:both;
	}

#gcwu-psnb .mb-menu, #gcwu-psnb .mb-menu li{
	/*background:#101010;*/
	background: linear-gradient(#606060,#333);
	background: -ms-linear-gradient(#606060,#333);
	background: -moz-linear-gradient(#606060,#333);
	background: -webkit-linear-gradient(#606060,#333);
	filter: progid:DXImageTransform.Microsoft.gradient(GradientType=0,startColorstr='#606060', endColorstr='#333');/*For IE7-8-9*/ 
	border:none;
}

ul.elgg-menu-site{
	/* background:#335075 !important;*/
	background-color: #146094 !important;
	background: linear-gradient(#146094,#23447e) !important;
	background: -ms-linear-gradient(#146094,#23447e) !important;
	background: -moz-linear-gradient(#146094,#23447e) !important;
	background: -webkit-linear-gradient(#146094,#23447e) !important;
	height:38px !important;
	border-bottom: 4px solid #ccc !important;
	border-top:4px solid #87aec9 !important;
}

#wb-main-in ul.elgg-menu-site > li{
	top:0;
	padding-bottom:0;
	max-height: 38px;
	border-right:1px solid #CCC !important;
}

#wb-main-in ul.elgg-menu-site > li.elgg-state-selected{
	border:none !important;
}

#wb-main-in > ul.elgg-menu-site li > a{
	font-size:16px;
	/*letter-spacing:.01em;*/
	font-weight:700;
	padding:10px 20px !important;
	height:18px;
}

#wb-main-in ul.elgg-menu-site > li.elgg-state-selected > a, #wb-main-in ul.elgg-menu-site > li a:hover{
	border:none !important;
	border-radius:0;
	background:#0f315b !important;
	color:#FFF !important;
	box-shadow:none !important;
}

#wb-main-in ul.elgg-menu-site > li:hover > a{
	border:none !important;
	background:#CCC !important;
	color:#000 !important;
}

#wb-main-in ul.elgg-menu-site > li > ul{
	border:none;
	box-shadow:none;
	border-radius:0;
}

#wb-main-in ul.elgg-menu-site > li ul li a{
	padding:15px 15px 13.5px !important;
}

	#wb-main .elgg-menu-page li.elgg-state-selected a, #wb-main .elgg-menu-page li a:hover, #wb-main .elgg-menu-owner-block li a:hover{
		background:#335075;
	}

	/******** STYLES LOGIN DROPDOWN ********/

	.elgg-button-dropdown{
		top:5px;
		font-size:1.333em;
		letter-spacing:.01em;
		font-weight:600;
		color:#fff !important;
		border:none;
	}

	.elgg-button-dropdown:hover{
		background:#CCC !important;
		color:#000 !important;
	}

	/******** TYPOGRAPHY ********/
	#wb-main-in h1, #wb-main-in h2, #wb-main-in h3, #wb-main-in h4, #wb-main-in h5, #wb-main-in h6{
		/*font-family: Verdana, Geneva, sans-serif;*/

		font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;
		font-weight:700;
		font-size: 16px;
		-webkit-font-smoothing: antialiased;
	}

	#wb-main-in h2.elgg-heading-main{

		font-family: "Helvetica Neue", Helvetica, Arial, sans-serif !important;
		font-size: 18px !important; 
		color: #333 !important;
		font-weight: 600 !important;

		/*letter-spacing:.25px;*/
		line-height:150%;
		margin-bottom:24px;
		color:black;
	}

	#wb-main-in .elgg-breadcrumbs li a{
		font-weight:500;
		font-size:1.5em;
	}

	/******** STYLE FORM LABELS ********/
	#wb-main-in .elgg-form-login label{
		font-size:1.33em;
		margin-bottom:5px;
	}

	/******** STYLING FORM INPUTS ********/


	#wb-main-in .elgg-form-login input{
		padding: 15px;
		margin: 0 0 10px 0;
	}
	
	elgg-form-login input{
		padding: 10px;
		margin: 0 0 6px 0;
	}

	#wb-main-in form input:-webkit-autofill{
		background-color: rgba(255,255,255) !important;
	}

	#wb-main-in form.elgg-search-header{
		top:-68px;
	}

	#wb-main-in form.elgg-search-header input.search-input{
		background: white !important;
		color:#000;
		font-size:1em;
		font-weight:600;
		/*letter-spacing:1px;*/
		border:2px solid #e3e3e3 !important;
		border-radius:0;
		margin:0;
		padding:5px 8px;
	}

	/******** STYLE BUTTONS ********/


	#wb-main-in .elgg-menu-title li{
		padding:0 24px 24px 0;
	}

	#wb-main-in .elgg-menu-title li:last-child{
		padding:0 0 24px 0;
	}

	#wb-main-in .elgg-button-submit:hover, .elgg-button-submit:hover, #wb-main-in .elgg-button-action:hover{
		background:#335075;
	}

	#wb-main-in .elgg-button-submit, .elgg-button-submit, .elgg-button-delete{
		background:#335075;
		text-shadow:none;
		border:none;
	}

	

	#wb-main-in #profile-owner-block a.elgg-button-action{
		margin-bottom:10px;
	}

		/******** RATING BUTTON NEED NOT BE DISPLAYED ********/
		#wb-main-in .elgg-form-stars-rate input[type='submit']{
			display:none !important;
		}

	/******** STYLES FOR FILTER MENUS/TABS ********/
	ul.elgg-tabs li, ul.elgg-menu-filter li{
		background:#fff;
		padding:4px;
		border-radius:2px;
		border:2px solid #999;
		border-bottom:none;
		color:#111 !important;
	}

	ul.elgg-htabs li:hover, ul.elgg-menu-filter li:hover{
		background:#335075;
		color:#fff !important;
	}

	ul.elgg-htabs li a:hover, ul.elgg-menu-filter li a:hover{
		background:#335075;
	}

	ul.elgg-tabs li a, ul.elgg-menu-filter li a{
		color:inherit !important;
	}

	ul.elgg-tabs li.elgg-state-selected, ul.elgg-menu-filter li.elgg-state-selected{
		background:#335075;
	}

	ul.elgg-tabs li.elgg-state-selected > a, ul.elgg-menu-filter li.elgg-state-selected > a{
		background:#335075;
		color:#fff !important;
	}

	/******** STYLING WIDGET HEADERS ********/
	#wb-main-in .elgg-module-widget > .elgg-head{
		background:#335075;
	}

	#wb-main-in .toolbox h3{
		background: #335075;
	}

/******** MOBILE STYLES ********/
div#menu-container{
	font-size:32px;
	position:relative;
	height:32px;
	display:none;
}

p#language{
	display:none;
}

@media screen and (max-width: 960px){
	
	#wb-main, #wb-core-in, #gcwu-bnr-in{
		width:100% !important;
	}

	#wb-main-in{
		padding:0;
	}

	#wb-main-in h1{
		display:none;
	}

	div#wb-main-in #login-dropdown{
		display:none;
	}

	#wb-core-in{
		position:static;
	}

	#gcwu-gcnb{
		display:none;
	}

	#gcwu-bnr #gcwu-wmms{
		display:none;
	}

	#gcwu-bnr-in{
		min-height:0;
		background:#111 !important;
	}

	#gcwu-title{
		background:#111 !important;
	}
	
	#gcwu-psnb{
		display:none;
	}

	#gcwu-title p{
		display:inline-block;
		text-transform:uppercase;
	}

	#gcwu-title{
		position:relative;
		height:auto;
	}

	div#gcwu-title #gcwu-title-in{
		margin-left:15px;
	}

	#gcwu-title p#language{
		position:absolute;
		right:0;
		top:0;
		padding:7px 5px;
		margin-right:20px;

	}

	#gcwu-title p#language a{
		font-size:1em;
	}

	ul.elgg-menu.elgg-menu-site.elgg-menu-site-default.clearfix{
		display:none;
		position:relative;
		background:none !important;
		height: auto !important;
		width:auto;
		border-bottom: 1px solid black;
	}

	ul.elgg-menu.elgg-menu-site.elgg-menu-site-default.clearfix > li{
		width:100%;
		margin:0;
		border-bottom:1px solid #fff !important;
	}

	ul.elgg-menu li{
		clear:both;
	}

	div#wb-main-in ul.elgg-menu-site-default > li > a{
		box-sizing:border-box;
		background:#335075 !important;
		color:#fff !important;
		padding-left:0 !important;
		display:block;
		width:100%;
		padding:18px 25px !important;
		height:auto !important;
	}

	div#wb-main-in ul.elgg-menu-site-default > li > a:hover, div#wb-main-in ul.elgg-menu-site-default > li.elgg-state-selected > a{
		background:#CCC !important;
		color:#000 !important;
		box-shadow:none;
	}
	
	div#menu-container{
		display:block;
		height:48px;
		background:#335075;
	}

	a.menu{
		display:block;
		margin-left:25px;
	}

	.menu {
	 	position: relative;
		padding-left: 1.25em;
	}

	.menu:before {
	  	content: "";
	  	position: absolute;
	  	top: 14px;
	  	left: 0;
	  	width: 1em;
	  	height: 0.125em;
	  	border-top: 0.375em double #fff;
	  	border-bottom: 0.125em solid #fff;
	}

	#wb-main-in .elgg-search-header{
		position:absolute;
		right:25px;
		top:10px !important;
	}

} 

@media screen and (max-width: 480px){
	#wb-main-in .elgg-search input[type=text]{
		width:200px;
	}

	.groups-profile .elgg-image{
		float:none;
	}

	.elgg-image-block .elgg-body{
		display:table;
	}

	.elgg-image-block .elgg-body h3{
		display: table-header-group;
	}

	.elgg-image-block .elgg-body .elgg-subtext{
		display:table-row-group;
		float:none;
	}

	.elgg-image-block .elgg-body > ul{
		float:none;
		display:table-row-group;
	}

	#gcwu-title p#language a{
		font-size:.667em;
	}
}

@media screen and (max-width: 768px){
	.elgg-layout-one-sidebar{
		display:table;
	}

	.elgg-layout-one-sidebar .elgg-sidebar{
		display:table-footer-group;
		float:none;
	}

	.hj-forum-post-body{
		width:90%;
		float:none;
	}

	.hj-forum-post-footer{
		width:90%;
		float:none;
	}
}

/* Styling for WET Buttons */
#wb-main-in .elgg-button-action, #wb-main-in .elgg-button-submit, input[type=submit], input[type=reset]{
	background:#2572b4 !important;
	border-color:#143c5f !important;
	color: #fff !important;
	display: inline-block !important;
	font-weight:500 !important;
	touch-action: manipulation;
	cursor: pointer;
	border-radius: 4px !important;
	font-size: 16px !important;
	box-shadow:none !important;
}

#wb-main-in .elgg-button-action:hover, #wb-main-in .elgg-button-submit:hover, input[type=submit]:hover, input[type=reset]:hover{
	color: #ffffff !important;
	background-color:#0f315b !important;

}

#wb-main-in a.elgg-button-delete {
	box-shadow: none !important;
	display: inline-block !important;
	margin-bottom: 0 !important;
	font-weight:500 !important;
	touch-action: manipulation;
	cursor: pointer;
	border-radius: 4px !important;
	font-size: 16px !important;

}


/* Styling of Page Body */
#wb-main-in .elgg-page-body {
	background-color: #FFF !important;
}

#wb-main-in {
    border-left: 1px solid #d4d4d4;
    border-right: 1px solid #d4d4d4;
     border-bottom: 1px solid #d4d4d4;
     padding-bottom:0px;

}

.elgg-module-widget > .elgg-body {
	/*background-color: #f9f9f9 !important; */
	background-color: #f5f5f5 !important;
	border: 1px solid #e3e3e3 !important; 
	border-radius: 4px !important;
	box-shadow: inset 0 1px 1px rgba(0,0,0,.05) !important;
	
}

#widget-welcome > h2 {
	font-family: "Helvetica Neue", Helvetica, Arial, sans-serif !important;
	font-size: 18px !important; 
	color: #333 !important;
	font-weight: 600 !important;
	padding-top: 10px;
}

#gcwu-sft-in {
	background-position: center top !important;
}

.elgg-input-text {
	background-color:white !important;
}

h3.widget-header {
	font-family: "Helvetica Neue", Helvetica, Arial, sans-serif !important;
	font-size: 18px !important; 
	color: #333 !important;
	font-weight: 600 !important;
}

#widget-welcome > ul > li {
		color:#000 !important;
	background-color:#d4d6da !important;
	border-color:#bbbfc5 !important;

	display: inline-block !important;
    font-weight: 400 !important;
    text-align: center !important;
    vertical-align: middle !important;
    cursor: pointer !important;
    background-image: none !important;
    border: 1px solid #bbbfc5 !important;
}


#widget-welcome > ul > li.selected {
	color: #fff !important;
    background-color: #2572b4 !important;
    border-color: #2572b4 !important;
}

#widget-welcome > ul > li:hover {
color: #fff !important;
    background-color: #2572b4 !important;
    border-color: #2572b4 !important;
}

#activity-filter > li {
	color:#000 !important;
	background-color:#d4d6da !important;
	border-color:#bbbfc5 !important;

	display: inline-block !important;
    font-weight: 400 !important;
    text-align: center !important;
    vertical-align: middle !important;
    cursor: pointer !important;
    background-image: none !important;
    border: 1px solid #bbbfc5 !important;
}

#activity-filter > li.active {
	color: #fff !important;
    background-color: #2572b4 !important;
    border-color: #2572b4 !important;
}

#activity-filter > li:hover {
	color: #fff !important;
    background-color: #2572b4 !important;
    border-color: #2572b4 !important;
}

.profile.elgg-col-3of3 > .elgg-inner.clearfix {
	background-color: #eeeeee !important;
}

.widget-button {
	color:#000 !important;
	background-color:#d4d6da !important;
	border-color:#bbbfc5 !important;
	font-family: "Helvetica Neue", Helvetica, Arial, sans-serif !important;
	display: inline-block !important;
    font-weight: 500 !important;
    text-align: center !important;
    vertical-align: middle !important;
    cursor: pointer !important;
    background-image: none !important;
    border: 1px solid #bbbfc5 !important;
    letter-spacing: -1px !important;
 }

 .widget-button:hover {
	color: #fff !important;
    background-color: #2572b4 !important;
    border-color: #2572b4 !important;
 }

 .elgg-module-livesearch > .elgg-head h3 {
  background-image: linear-gradient(#606060,#333);
  background-image: -ms-linear-gradient(#606060,#333);
  background-image: -webkit-linear-gradient(#606060,#333);
  background-image: -moz-linear-gradient(#606060,#333);
  filter: progid:DXImageTransform.Microsoft.gradient(GradientType=0,startColorstr='#606060', endColorstr='#333');/*For IE7-8-9*/ 
  border: #333;
}

#ajax-form > h2 {
	font-family: "Helvetica Neue", Helvetica, Arial, sans-serif !important;
	font-size: 18px !important; 
	color: #333 !important;
	font-weight: 600 !important;
}

#activity-feed > ul > li:hover {
	background: none !important;	
}

.elgg-item:hover {
	background: none !important;
}

ul.elgg-menu-filter li.elgg-state-selected, ul.elgg-menu-filter li.elgg-state-selected a {
	border-color: #143c5f;
    border-style: solid;
    background-color: #2572b4;
    border-bottom:none;
    font-family: "Helvetica Neue", Helvetica, Arial, sans-serif !important;
     font-weight: 600 !important;
     font-size: 14px !important; 
}

ul.elgg-menu-filter li:hover {
	border-color: #143c5f;
    border-style: solid;
    background-color: #2572b4;
       border-bottom:none;
       font-family: "Helvetica Neue", Helvetica, Arial, sans-serif !important;
        font-weight: 600 !important;
        font-size: 14px !important; 
}

ul.elgg-menu-filter li {
	border-color: #DBDBDB;
    border-style: solid;
    background-color: #FFF;
       border-bottom:none;
       font-family: "Helvetica Neue", Helvetica, Arial, sans-serif !important;
        font-weight: 600 !important;
        font-size: 14px !important; 
}

ul.elgg-menu-filter li:hover, ul.elgg-menu-filter li a:hover {
	border-color: #143c5f;
    border-style: solid;
    border-bottom:none;
    background-color: #2572b4;
    font-family: "Helvetica Neue", Helvetica, Arial, sans-serif !important;
     font-weight: 600 !important;
     font-size: 14px !important; 
}

ul.elgg-tabs li.elgg-state-selected, ul.elgg-menu-filter li.elgg-state-selected{
	border-color: #143c5f;
    border-style: solid;
    border-bottom:none;
    background-color: #2572b4;
    font-family: "Helvetica Neue", Helvetica, Arial, sans-serif !important;
     font-weight: 600 !important;
     font-size: 14px !important; 
}

ul.elgg-tabs li.elgg-state-selected a, ul.elgg-menu-filter li.elgg-state-selected a{
	border-color: #143c5f;
    border-style: solid;
    border-bottom:none;
    background-color: #2572b4 !important;
    font-family: "Helvetica Neue", Helvetica, Arial, sans-serif !important;
     font-weight: 600 !important;
     font-size: 14px !important; 
}

.elgg-tabs a {
	font-family: "Helvetica Neue", Helvetica, Arial, sans-serif !important;
     font-weight: 600 !important;
     font-size: 14px !important; 
}

.elgg-tabs a:hover{
	border-color: #143c5f;
    border-style: solid;
    border-bottom:none;
    background-color: #2572b4 !important;
    font-family: "Helvetica Neue", Helvetica, Arial, sans-serif !important;
    font-weight: 600 !important;
    font-size: 14px !important; 
}

.elgg-tabs li:hover{
	border-color: #143c5f;
    border-style: solid;
    border-bottom:none;
    background-color: #2572b4 !important;
    font-family: "Helvetica Neue", Helvetica, Arial, sans-serif !important;
    font-weight: 600 !important;
    font-size: 14px !important; 
}

ul.elgg-tabs li, ul.elgg-menu-filter li {
		border-color: #DBDBDB;
    border-style: solid;
    border-bottom:none;
    background-color: #FFF;
    font-family: "Helvetica Neue", Helvetica, Arial, sans-serif !important;
    font-weight: 600 !important;
    font-size: 14px !important; 
}

ul.elgg-menu.elgg-menu-page.elgg-menu-page-default {
	font-family: "Helvetica Neue", Helvetica, Arial, sans-serif !important;
    font-weight: 600 !important;
    font-size: 14px !important;
}

ul.elgg-menu.elgg-menu-owner-block.elgg-menu-owner-block-default {
	font-family: "Helvetica Neue", Helvetica, Arial, sans-serif !important;
    font-weight: 600 !important;
    font-size: 14px !important;
}

.elgg-river-summary {
	font-family: "Helvetica Neue", Helvetica, Arial, sans-serif !important;
    font-size: 16px !important;
}

.elgg-river-message {
	font-family: "Helvetica Neue", Helvetica, Arial, sans-serif !important;
    font-size: 14px !important;
}