<?php
/**
 * The header.
 *
 * This is the template that displays all of the <head> section and everything up until main.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package WordPress
 * @subpackage Twenty_Twenty_One
 * @since Twenty Twenty-One 1.0
 */

?>
<!doctype html>
<html <?php language_attributes(); ?> <?php twentytwentyone_the_html_classes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<!-- Google Tag Manager -->
	<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
	new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
	j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
	'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
	})(window,document,'script','dataLayer','GTM-NCM435S');</script>
	<!-- End Google Tag Manager -->
	<?php wp_head(); ?>
	<!-- Global site tag (gtag.js) - Google Analytics -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=G-HVXCXLTZK1"></script>
	<script>
	window.dataLayer = window.dataLayer || [];
	function gtag(){dataLayer.push(arguments);}
	gtag('js', new Date());

	gtag('config', 'G-HVXCXLTZK1');
	</script>
	<script>(function(i,s,o,g,r,a,m){i["GoogleAnalyticsObject"]=r;i[r]=i[r]||function(){(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)})(window,document,"script","https://www.google-analytics.com/analytics.js","ga");ga("create", "UA-12112020-1", {"cookieDomain":"auto"});ga("set", "anonymizeIp", true);ga("send", "pageview");</script>
	<?php if (!is_page('city-data-iframe')): ?>
	<script src="https://ads.cordlessmedia.com/ad-wrapper/25419/cm.min.js"></script>
	<?php endif; ?>
	<?php if (!is_single() ||  !is_page('city-data-iframe') ): ?>
	<script>
	var cmWrapper = cmWrapper || {};
	cmWrapper.que = cmWrapper.que || [];
	cmWrapper.que.push(function () {
	// Define all needed units
	
	cmWrapper.ads.defineUnit("div-gpt-ad-1568929479747-0");
	cmWrapper.ads.defineUnit("div-gpt-ad-1568929535248-0");
	cmWrapper.ads.defineUnit("div-gpt-ad-1568929556599-0");
	cmWrapper.ads.define("CordlessMedia_Livability_ROS_OutOfPage_0");

	cmWrapper.ads.requestUnits();
	});
	</script>
	<?php endif; ?>
	<meta name="p:domain_verify" content="2957482f3b41217c5c23ec119b32a1d5"/>
</head>

<body <?php body_class(); ?>>
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-NCM435S"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
<!-- Facebook Pixel Code -->
<script>
!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
document,'script','https://connect.facebook.net/en_US/fbevents.js');
fbq('init', '1106745702700189'); // Insert your pixel ID here.
fbq('track', 'PageView');
</script>
<noscript>
<img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id=935734086562381&ev=PageView&noscript=1" />
</noscript>
<!-- End Facebook Pixel Code -->
<link rel="apple-touch-icon" sizes="57x57" href="<?php echo get_template_directory(  ); ?>/assets/images/apple-touch-icon-57x57.png">
<link rel="apple-touch-icon" sizes="180x180" href="<?php echo get_template_directory(  ); ?>/assets/images/apple-touch-icon-180x180.png">
<?php wp_body_open(); ?>

<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'livablity' ); ?></a>

	<?php get_template_part( 'template-parts/header/site-header' ); ?>

	<div id="content" class="site-content">
		<div id="primary" class="content-area">
			<main id="main" class="site-main" role="main">
