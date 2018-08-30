<?php
/**
 * Walled garden page shell
 *
 * Used for the walled garden index page
 */
$head = $vars['head'];
$body = $vars['body'];


$is_sticky_register = elgg_is_sticky_form('register');
$wg_body_class = 'elgg-body-walledgarden';
if ($is_sticky_register) {
    $wg_body_class .= ' hidden';
}

$wettoolkit_url = elgg_get_site_url()."mod/wettoolkit";

header("Content-type: text/html; charset=UTF-8");
?>
<!DOCTYPE html>
<html lang="en" class="no-js">
	<?php
if(isset($_SESSION['lang'])) {
?>
<head>
<meta charset="utf-8" />
<meta http-equiv="Content-type" content="text/html; charset=UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<link rel="shortcut icon" href="<?php echo $wettoolkit_url.'/dist/theme-gcwu-fegc/images/favicon.ico';?>" />
<meta name="description" content="English description / Description en anglais" />
<meta name="dcterms.creator" content="English name of the content author / Nom en anglais de l'auteur du contenu" />
<meta name="dcterms.title" content="English title / Titre en anglais" />
<meta name="dcterms.issued" title="W3CDTF" content="Date published (YYYY-MM-DD) / Date de publication (AAAA-MM-JJ)" />
<meta name="dcterms.modified" title="W3CDTF" content="Date modified (YYYY-MM-DD) / Date de modification (AAAA-MM-JJ)" />
<meta name="dcterms.subject" title="scheme" content="English subject terms / Termes de sujet en anglais" />
<meta name="dcterms.language" title="ISO639-2" content="eng" />
<meta name="viewport" content="width=device-width, initial-scale=1" />


<!-- Google Font -->
<!--<link href='http://fonts.googleapis.com/css?family=Average+Sans' rel='stylesheet' type='text/css'>-->

<!--[if lte IE 8]>
<script src="<?php echo $wettoolkit_url.'/dist/js/jquery-ie.min.js';?>"></script>
<script src="<?php echo $wettoolkit_url.'/dist/js/polyfills/html5shiv-min.js';?>"></script>
<link rel="stylesheet" href="<?php echo $wettoolkit_url.'/dist/grids/css/util-ie-min.css';?>"/>
<link rel="stylesheet" href="<?php echo $wettoolkit_url.'/dist/js/css/pe-ap-ie-min.css';?>"/>
<link rel="stylesheet" href="<?php echo $wettoolkit_url.'/dist/theme-gcwu-fegc/css/theme-ie-min.css';?>"/>
<noscript><link rel="stylesheet" href="<?php echo $wettoolkit_url.'/dist/theme-gcwu-fegc/css/theme-ns-ie-min.css';?>" /></noscript>
<![endif]-->
<!--[if gt IE 8]><!-->

<!--<script src="<?php echo $wettoolkit_url.'/dist/js/jquery.min.js';?>"></script>-->
<link rel="stylesheet" href="<?php echo $wettoolkit_url.'/css/base.min.css';?>"/>
<link rel="stylesheet" href="<?php echo $wettoolkit_url.'/css/bootstrap.css';?>"/>
<link rel="stylesheet" href="<?php echo $wettoolkit_url.'/dest/css/styles.min.css';?>"/>
<link rel="stylesheet" href="<?php echo $wettoolkit_url.'/dist/grids/css/util-min.css';?>"/>
<link rel="stylesheet" href="<?php echo $wettoolkit_url.'/dist/js/css/pe-ap-min.css';?>" />
<link rel="stylesheet" href="<?php echo $wettoolkit_url.'/dist/theme-gcwu-fegc/css/theme-min.css';?>" />
<noscript><link rel="stylesheet" href="<?php echo $wettoolkit_url.'/dist/theme-gcwu-fegc/css/theme-ns-min.css';?>"/></noscript>
<!--<![endif]-->

<?php echo elgg_view('page/elements/head', $vars); ?>
</head>
<body>
<div id="wb-body">
    <div id="wb-head"><div id="wb-head-in"><header>
<!-- HeaderStart -->
<nav role="navigation"><div id="gcwu-gcnb"><h2><?=elgg_echo('login:govNavBar')?></h2><div id="gcwu-gcnb-in"><div id="gcwu-gcnb-fip">
<div id="gcwu-sig"><div id="gcwu-sig-in"><object data="<?php echo $wettoolkit_url.'/dist/theme-gcwu-fegc/images/sig-eng.svg';?>" role="img" tabindex="-1" aria-label="Government of Canada" type="image/svg+xml"><img src="<?php echo $wettoolkit_url.'/dist/theme-gcwu-fegc/images/sig-eng.png';?>" alt="Government of Canada" /></object></div></div>
<ul>
<li id="gcwu-gcnb1"><a rel="external" href="http://www.canada.gc.ca/menu-eng.html">Canada.gc.ca</a></li>
<li id="gcwu-gcnb2"><a rel="external" href="http://www.servicecanada.gc.ca/eng/home.shtml"><?=elgg_echo('login:services')?></a></li>
<li id="gcwu-gcnb3"><a rel="external" href="http://www.canada.gc.ca/aboutgov-ausujetgouv/depts/menu-eng.html"><?=elgg_echo('login:departments')?></a></li>

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
    <a href="<?=$url?>"><?=$nextLang?></a>
    <?php //BOOM
} else {
    ?>
    <a href="./?lang=<?=$nextLangID?>" lang="<?=$nextLangID?>"><?=$nextLang?></a>
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
<div id="gcwu-title"><p id="gcwu-title-in"><a href="<?php echo elgg_get_site_url();?>"/><?=elgg_echo('login:siteTitle')?></a></p>
<p id="language"><?php if(elgg_is_logged_in() && 1==5){
    ?>
    <a href="<?=$url?>"><?=$nextLang?></a>
    <?php //BOOM
} else {
    ?>
    <a href="./?lang=<?=$nextLangID?>" lang="<?=$nextLangID?>"><?=$nextLang?></a>
    <?php //BOOM 2
}
?></p></div>
</div></div>


<nav role="navigation">
<div id="gcwu-psnb"><h2><?=elgg_echo('login:siteMenu')?></h2><div id="gcwu-psnb-in"><div class="wet-boew-menubar mb-mega"><div>
<ul class="mb-menu">

</ul>
</div></div></div></div>

<!--
<div id="gcwu-bc"><h2><?=elgg_echo('breadcrumbs')?></h2><div id="gcwu-bc-in">
<!--
<ol>
<li><a href="#"><?=elgg_echo('ongarde:home')?></a></li>
</ol>
-->
<!--
</div></div>
-->

</nav>
</header></div></div>
<div id="wb-core"><div id="wb-core-in" class="equalize">
<div id="wb-main" role="main"><div id="wb-main-in">
<!-- MainContentStart -->
<?php echo $header;?> 
<div class="elgg-page elgg-page-default">
    <div class="elgg-page-messages">
        <?php echo elgg_view('page/elements/messages', array('object' => $vars['sysmessages'])); ?>
    </div>
    <div class="elgg-page-body">
            <?php echo $body; ?>
    </div>
</div>

<?php if ($is_sticky_register): ?>
<script type="text/javascript">
elgg.register_hook_handler('init', 'system', function() {
    $('.registration_link').trigger('click');
});
</script>
<?php endif; ?>
<?php echo elgg_view('page/elements/foot'); ?>
<!-- MainContentEnd -->
</div></div>
</div></div>
<div id="wb-foot"><div id="wb-foot-in"><footer>
<div class="banner"><img src="<?php echo $wettoolkit_url.'/dist/theme-gcwu-fegc/images/banner.png' ?>" style="width:auto;display:block;margin: 0 auto"/></div>
<h2 id="wb-nav"><?=elgg_echo('login:footer')?></h2>
<!-- FooterStart -->
<nav role="navigation"><div id="gcwu-sft"><h3><?=elgg_echo('login:siteFooter')?></h3><div id="gcwu-sft-in">
<div id="gcwu-tctr">
<ul>
<li class="gcwu-tc"><a href="http://s3.ongarde.net/portal/pages/view/2909/guidelines-for-the-use-of-the-learning-portal-lignes-directrices-pour-lutilisation-du-portail-dapprentissage-des-fac" rel="license"><?=elgg_echo('login:termsConditions')?></a></li>
<li class="gcwu-tr"><a href="http://www.tbs-sct.gc.ca/pd-dp/index-eng.asp"><?=elgg_echo('login:transparency')?></a></li>
</ul>
</div>
<div class="clear"></div>
<!--<section><div class="span-2"><h4 class="gcwu-col-head"><?=elgg_echo('login:about')?></h4>
<ul>
<li><a href="#"><?=elgg_echo('login:aboutONGARDE')?></a></li>
</ul>
</div></section>-->
<section><div class="span-2"><h4 class="gcwu-col-head"><?=elgg_echo('login:contactUs')?></h4>
<ul>
<li><a href="mailto:cda-adllab@forces.gc.ca"><?=elgg_echo('login:questionsComments')?></a></li>
</ul>
</div></section>

</div></div></nav>

<nav role="navigation"><div id="gcwu-gcft"><h3><?=elgg_echo('login:govFooter')?></h3><div id="gcwu-gcft-in"><div id="gcwu-gcft-fip">
<ul>
<li><a rel="external" href="http://healthycanadians.gc.ca/index-eng.php"><span><?=elgg_echo('login:health')?></span><br />healthycanadians.gc.ca</a></li>
<li><a rel="external" href="http://www.voyage.gc.ca/index-eng.asp"><span><?=elgg_echo('login:travel')?></span><br />travel.gc.ca</a></li>
<li><a rel="external" href="http://www.servicecanada.gc.ca/eng/home.shtml"><span><?=elgg_echo('login:serviceCanada')?></span><br />servicecanada.gc.ca</a></li>
<li><a rel="external" href="http://www.jobbank.gc.ca/intro-eng.aspx"><span><?=elgg_echo('login:jobs')?></span><br />jobbank.gc.ca</a></li>
<li><a rel="external" href="http://actionplan.gc.ca/en"><span><?=elgg_echo('login:economy')?></span><br />actionplan.gc.ca</a></li>
<li id="gcwu-gcft-ca"><div><a rel="external" href="http://www.canada.gc.ca/menu-eng.html">Canada.gc.ca</a></div></li>
</ul>
</div></div></div></nav>
<!-- FooterEnd -->
</footer>
</div></div></div>

<!-- ScriptsStart -->
<script src="<?php echo $wettoolkit_url.'/dist/js/settings.js';?>"></script>
<!--[if lte IE 8]>
<!--<script src="<?php echo $wettoolkit_url.'/dist/js/jquerymobile/jquery.mobile-ie.min.js';?>"></script>-->
<![endif]-->
<!--[if gt IE 8]><!-->
<script src="<?php echo $wettoolkit_url.'/dist/theme-gcwu-fegc/js/theme-min.js';?>"></script>
<script src="<?php echo $wettoolkit_url.'/dist/js/jquery.validate.min.js';?>"></script>
<script src="<?php echo $wettoolkit_url.'/dist/js/wetMessages.min.js';?>"></script>
<script src="<?php echo $wettoolkit_url.'/js/stopSubmit.js';?>"></script>
<script src="<?php echo $wettoolkit_url.'/dist/js/wg-script.min.js';?>"></script>
<script src="<?php echo $wettoolkit_url.'/dist/js/validation-script.js';?>"></script>
<!--<script src="<?php echo $wettoolkit_url.'/dist/js/jquerymobile/jquery.mobile.min.js';?>"></script>-->
<!--<![endif]-->
<!-- ScriptsEnd -->

<!-- CustomScriptsStart -->
<!-- CustomScriptsEnd -->

</body>
<?php 
}else{
?>
<head>
<meta charset="utf-8" />
<meta http-equiv="Content-type" content="text/html; charset=UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<link rel="shortcut icon" href="<?php echo $wettoolkit_url.'/dist/theme-gcwu-fegc/images/favicon.ico';?>" />

<link rel="stylesheet" href="<?php echo $wettoolkit_url.'/css/theme.min.css';?>"/>

</head>
	<header role="banner">
	<div id="wb-bnr" class="container">
	<object id="gcwu-sig" type="image/svg+xml" tabindex="-1" role="img" data="<?php echo $wettoolkit_url.'/dist/theme-gcwu-fegc/images/sig-alt-en.svg'?>" aria-label="Government of Canada"></object>
	</div>
	</header>
	<main role="main" property="mainContentOfPage" class="container">
	<div class="col-md-12">
	<h1 class="wb-inv">Language selection - Web Experience Toolkit / <span lang="fr">Sélection de la langue - Boîte à outils de l’expérience Web</span></h1>

	<section class="col-md-6" style="box-sizing: border-box;">
			<h2 class="h3 text-center">DLN Learning Portal</h2>
			<ul class="list-unstyled">
					<li><a class="btn btn-lg btn-primary btn-block" href=".?lang=en" style="width:auto;color:#fff!important;">English</a></li>
					<li><a class="btn btn-lg btn-default btn-block mrgn-tp-sm" href="http://www.forces.gc.ca/en/terms-conditions.page" rel="license" style="width:auto;">Terms and conditions of use</a></li>
			</ul>
	</section>

	<section class="col-md-6" style="box-sizing: border-box;" lang="fr">
			<h2 class="h3 text-center">Le Portail d'apprentissage RAD</h2>
			<ul class="list-unstyled">
					<li><a class="btn btn-lg btn-primary btn-block" href=".?lang=fr" style="width:auto;color:#fff!important;">Français</a></li>
					<li><a class="btn btn-lg btn-default btn-block mrgn-tp-sm" href="http://www.forces.gc.ca/fr/termes-conditions.page" style="width:auto;">Conditions régissant l'utilisation</a></li>
			</ul>
	</section>
	</div>
	</main>
	<footer role="contentinfo" class="container">
		<object id="wmms" type="image/svg+xml" tabindex="-1" role="img" data="<?php echo $wettoolkit_url.'/dist/theme-gcwu-fegc/images/wmms-alt.svg'?>" aria-label="Symbol of the Government of Canada"></object>
	</footer>
<?php
}
?>
</html>