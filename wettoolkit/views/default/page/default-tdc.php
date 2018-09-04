<?php
/**
 * Elgg pageshell
 * The standard HTML page shell that everything else fits into
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses $vars['title']       The page title
 * @uses $vars['body']        The main content of the page
 * @uses $vars['sysmessages'] A 2d array of various message registers, passed from system_messages()
 */

// backward compatability support for plugins that are not using the new approach
// of routing through admin. See reportedcontent plugin for a simple example.
if (elgg_get_context() == 'admin') {
	elgg_deprecated_notice("admin plugins should route through 'admin'.", 1.8);
	elgg_admin_add_plugin_settings_menu();
	elgg_unregister_css('elgg');
	echo elgg_view('page/admin', $vars);
	return true;
}

// render content before head so that JavaScript and CSS can be loaded. See #4032
$topbar = elgg_view('page/elements/topbar', $vars);
$messages = elgg_view('page/elements/messages', array('object' => $vars['sysmessages']));
$header = elgg_view('page/elements/header', $vars);
$body = elgg_view('page/elements/body', $vars);
$footer = elgg_view('page/elements/footer', $vars);

// Set the content type
header("Content-type: text/html; charset=UTF-8");

$wettoolkit_url = elgg_get_site_url()."mod/wettoolkit";


?>
<!DOCTYPE html>
<html lang="en" class="no-js">
<head>
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta charset="utf-8" />
<link rel="shortcut icon" href="<?php echo $wettoolkit_url.'/dist/theme-gcwu-fegc/images/favicon.ico';?>" />
<meta name="description" content="CDA Learning Portal" />
<meta name="author" content="Canadian Defence Academy" />
<meta name="viewport" content="width=device-width, initial-scale=1" />

<link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300' rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="<?php echo $wettoolkit_url.'/dist/grids/css/util-min.css';?>"/>
<link rel="stylesheet" href="<?php echo $wettoolkit_url.'/dist/js/css/joyride-2.1.min.css';?>" />
<link rel="stylesheet" href="<?php echo $wettoolkit_url.'/dist/theme-gcwu-fegc/css/theme-min.css';?>" />
<link rel="stylesheet" href="<?php echo $wettoolkit_url.'/css/base.min.css';?>"/>
<link rel="stylesheet" href="<?php echo $wettoolkit_url.'/css/bootstrap.css';?>"/>
<link rel="stylesheet" href="<?php echo $wettoolkit_url.'/dest/css/styles.min.css';?>"/>
<link rel="stylesheet" href="<?php echo $wettoolkit_url.'/css/custom-gofo.css'?>"/>
<?php 
echo elgg_view('page/elements/head', $vars); 
?>

</head>
<body><div id="wb-body">
<div id="wb-skip">
<ul id="wb-tphp">
<!--
<li id="wb-skip1"><a href="#wb-cont"><?php echo elgg_echo('login:skipMain')?></a></li>
<li id="wb-skip2"><a href="#wb-nav"><?php echo elgg_echo('login:skipFooter')?></a></li>
-->
</ul>
</div>

<div id="wb-head"><div id="wb-head-in"><header>
<!-- HeaderStart -->
<nav role="navigation"><div id="gcwu-gcnb"><h2><?php echo elgg_echo('login:govNavBar')?></h2><div id="gcwu-gcnb-in"><div id="gcwu-gcnb-fip">
<div id="gcwu-sig"><div id="gcwu-sig-in"><object data="<?php echo $wettoolkit_url.'/dist/theme-gcwu-fegc/images/sig-eng.svg';?>" role="img" tabindex="-1" aria-label="Government of Canada" type="image/svg+xml"><img src="<?php echo $wettoolkit_url.'/dist/theme-gcwu-fegc/images/sig-eng.png';?>" alt="Government of Canada" /></object></div></div>
<ul>
<li id="gcwu-gcnb1"><a rel="external" href="http://www.canada.gc.ca/menu-eng.html">Canada.gc.ca</a></li>
<li id="gcwu-gcnb2"><a rel="external" href="http://www.servicecanada.gc.ca/eng/home.shtml"><?php echo elgg_echo('login:services')?></a></li>
<li id="gcwu-gcnb3"><a rel="external" href="http://www.canada.gc.ca/aboutgov-ausujetgouv/depts/menu-eng.html"><?php echo elgg_echo('login:departments')?></a></li>

<li id="gcwu-gcnb-lang">

<?php
if(strtolower(get_current_language())=="en"){
	$nextLangID="fr";
	$nextLang = "Français";
} else {
	$nextLangID="en";
	$nextLang = "English";
}


$ts = time();
$token = generate_action_token($ts);
$url = "action/adl_lang_switcher/switch?__elgg_ts=$ts&&__elgg_token=$token";

if(elgg_is_logged_in() && 1==5){
	?>
	<a href="<?php echo $url?>"><?php echo $nextLang?></a>
	<?php //BOOM
} else {
	?>
	<a href="./?lang=<?php echo $nextLangID?>" lang="<?php echo $nextLangID?>"><?php echo $nextLang?></a>
	<?php //BOOM 2
}
?>

</li>

</ul>
</div></div></div></nav>

<div id="menu-container">
	<a class="menu" href="#">
	</a>
</div>
<div id="gcwu-bnr" role="banner"><div id="gcwu-bnr-in">
<div id="gcwu-wmms"><div id="gcwu-wmms-in"><object data="<?php echo $wettoolkit_url.'/dist/theme-gcwu-fegc/images/wmms.svg';?>" role="img" tabindex="-1" aria-label="Symbol of the Government of Canada" type="image/svg+xml"><img src="<?php echo $wettoolkit_url.'/dist/theme-gcwu-fegc/images/wmms.png';?>" alt="Symbol of the Government of Canada" /></object></div></div>
<div id="gcwu-title"><p id="gcwu-title-in"><a href="<?php echo elgg_get_site_url();?>"/><?php echo elgg_echo('login:siteTitle')?></a></p>
<p id="language"><?php if(elgg_is_logged_in() && 1==5){
	?>
	<a href="<?php echo $url?>"><?php echo $nextLang?></a>
	<?php //BOOM
} else {
	?>
	<a href="./?lang=<?php echo $nextLangID?>" lang="<?php echo $nextLangID?>"><?php echo $nextLang?></a>
	<?php //BOOM 2
}
?></p></div>
</div></div>


<nav role="navigation">
<div id="gcwu-psnb"><h2><?php echo elgg_echo('login:siteMenu')?></h2><div id="gcwu-psnb-in"><div class="wet-boew-menubar mb-mega"><div>
<ul class="mb-menu">

<?php if (elgg_is_logged_in()): ?>
<?php echo $topbar; ?>
<?php endif; ?>
</ul>
</div></div></div></div>

<!--
<div id="gcwu-bc"><h2><?php echo elgg_echo('breadcrumbs')?></h2><div id="gcwu-bc-in">
<!--
<ol>
<li><a href="#"><?php echo elgg_echo('ongarde:home')?></a></li>
</ol>
-->
<!--
</div></div>
-->

</nav>
<!-- HeaderEnd -->
</header></div></div>

<div id="wb-core"><div id="wb-core-in" class="equalize">
<div id="wb-main" role="main"><div id="wb-main-in">
<!-- MainContentStart -->

<?php echo $header;?> 

<a href="" id="start-tour" title="Click for on page help"></a>

<div class="elgg-page elgg-page-default">
	<div class="elgg-page-messages">
		<?php echo $messages; ?>
        <?php
        //Displays alert to user only when they are at /portal/ (Home tab)
        if($_SERVER['REQUEST_URI'] == "/portal/") {
            $alert_list = elgg_get_entities(array(
                'type' => 'object',
                'subtype' => 'global_alert',
                'limit' => 25
            ));

            //Check for global alert - if any exist add to page
            if($alert_list){
                for ($i = 0; $i < count($alert_list); $i++){
                    ?>
                    <section class="alert alert-<?php echo elgg_strtolower($alert_list[$i]->alert_type)?>">
                        <h2><?php echo $alert_list[$i]->title?></h2>
                        <p><?php echo $alert_list[$i]->description?></p>
                    </section>
                    <?php
                }
            }
        }?>
	</div>
	
	<!--
	<?php if (elgg_is_logged_in()): ?>
	<div class="elgg-page-topbar">
		<div class="elgg-inner">
			
		</div>
	</div>
	<?php endif; ?>
	
	
	<div class="elgg-page-header">
		<div class="elgg-inner">
			<?php 
			
			?>
		</div>
	</div>
	-->
	
	<div class="elgg-page-body">
		<div class="elgg-inner">
			<?php echo $body; ?>
		</div>
	</div>
	
</div>

<?php echo elgg_view('page/elements/foot'); ?>


<!-- MainContentEnd -->
</div></div>
</div></div>

<div id="wb-foot"><div id="wb-foot-in"><footer><h2 id="wb-nav"><?php echo elgg_echo('login:footer')?></h2>
<!-- FooterStart -->
<div class="banner"><img src="<?php echo $wettoolkit_url.'/dist/theme-gcwu-fegc/images/banner.png' ?>" style="width:auto;display:block;margin: 0 auto"/></div>
<nav role="navigation"><div id="gcwu-sft"><h3><?php echo elgg_echo('login:siteFooter')?></h3><div id="gcwu-sft-in">
<div id="gcwu-tctr">
<ul>
<li class="gcwu-tc"><a href="https://lp-pa.forces.gc.ca/portal/pages/view/2909/guidelines-for-the-use-of-the-learning-portal-lignes-directrices-pour-lutilisation-du-portail-dapprentissage-des-fac" rel="license"><?php echo elgg_echo('login:termsConditions')?></a></li>
<li class="gcwu-tr"><a href="http://www.tbs-sct.gc.ca/pd-dp/index-eng.asp"><?php echo elgg_echo('login:transparency')?></a></li>
</ul>
</div>
<div class="clear"></div>

<section><div class="span-2"><h4 class="gcwu-col-head"><?php echo elgg_echo('login:contactUs')?></h4>
<ul>
<li><a href="mailto:cda-adllab@forces.gc.ca"><?php echo elgg_echo('login:questionsComments')?></a></li>
</ul>
</div></section>

</div></div></nav>

<nav role="navigation"><div id="gcwu-gcft"><h3><?php echo elgg_echo('login:govFooter')?></h3><div id="gcwu-gcft-in"><div id="gcwu-gcft-fip">
<ul>
<li><a rel="external" href="http://healthycanadians.gc.ca/index-eng.php"><span><?php echo elgg_echo('login:health')?></span><br />healthycanadians.gc.ca</a></li>
<li><a rel="external" href="http://www.voyage.gc.ca/index-eng.asp"><span><?php echo elgg_echo('login:travel')?></span><br />travel.gc.ca</a></li>
<li><a rel="external" href="http://www.servicecanada.gc.ca/eng/home.shtml"><span><?php echo elgg_echo('login:serviceCanada')?></span><br />servicecanada.gc.ca</a></li>
<li><a rel="external" href="http://www.jobbank.gc.ca/intro-eng.aspx"><span><?php echo elgg_echo('login:jobs')?></span><br />jobbank.gc.ca</a></li>
<li><a rel="external" href="http://actionplan.gc.ca/en"><span><?php echo elgg_echo('login:economy')?></span><br />actionplan.gc.ca</a></li>
<li id="gcwu-gcft-ca"><div><a rel="external" href="http://www.canada.gc.ca/menu-eng.html">Canada.gc.ca</a></div></li>
</ul>
</div></div></div></nav>
<!-- FooterEnd -->
</footer>
</div></div></div>

<!-- ScriptsStart -->
<script src="<?php echo $wettoolkit_url.'/dist/js/settings.min.js';?>"></script>
<script src="<?php echo $wettoolkit_url.'/dist/js/jquery.joyride-2.1.min.js';?>"></script>
<script src="<?php echo $wettoolkit_url.'/dist/js/responsiveslides.min.js';?>"></script>
<script src="<?php echo $wettoolkit_url.'/dist/js/wetMessages.min.js';?>"></script>
<script src="<?php echo $wettoolkit_url.'/dist/js/classes/Overlay.js';?>"></script>
<script src="<?php echo $wettoolkit_url.'/js/stopSubmit.js';?>"></script>
<script src="<?php echo $wettoolkit_url.'/dist/js/script.min.js';?>"></script>

<?php
if($_SESSION['badges_check']){
	unset($_SESSION['badges_check']);
	unset($_SESSION['badges']);
}
if ($_SESSION['badges']){ ?>
<script>
$(function(){
	var sliderLink = elgg.get_site_url() + "points/award";
	$.fancybox({
			href: sliderLink,
			titleShow: false,
			'width':'40%',
			'height':'auto',
			"autoDimensions" : false,
			scrolling : 'no',
			onComplete: function(){
				$(".rslides").responsiveSlides({
				 	auto:false,
				 	nav:true,
				 	namespace: "centered-btns",
				 	prevText: "<",
				 	nextText: ">",
				 });
				$.ajax({
					url:elgg.get_site_url() + "points/clearSesh",
					success: function(resultText){

					}
				});
			}
	});
});
</script>
<?php 
}
?>
<!-- ScriptsEnd -->

</body>
</html>
