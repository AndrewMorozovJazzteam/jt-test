<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package Nevia
 * @since Nevia 1.0
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<!-- Google Tag Manager & Yandex.Metrika counter -->
<script defer>
    var GTMMD2VBGDLoad=!1;function loadGTM(){var e,t,a,n;!1===GTMMD2VBGDLoad&&(GTMMD2VBGDLoad=!0,e=window,t=document,a="script",e[n="dataLayer"]=e[n]||[],e[n].push({"gtm.start":(new Date).getTime(),event:"gtm.js"}),n=t.getElementsByTagName(a)[0],(a=t.createElement(a)).async=!0,a.src="https://www.googletagmanager.com/gtm.js?id=GTM-W8WSBXT",n.parentNode.insertBefore(a,n))}window.addEventListener("scroll",function(){!1===GTMMD2VBGDLoad&&(GTMMD2VBGDLoad=!0,setTimeout(function(){var e,t,a,n;e=window,t=document,a="script",e[n="dataLayer"]=e[n]||[],e[n].push({"gtm.start":(new Date).getTime(),event:"gtm.js"}),n=t.getElementsByTagName(a)[0],(a=t.createElement(a)).async=!0,a.src="https://www.googletagmanager.com/gtm.js?id=GTM-W8WSBXT",n.parentNode.insertBefore(a,n)},1e3))},{passive:true});
		var YANDEXLoad=!1;function loadMetrika(){!1===YANDEXLoad&&(YANDEXLoad=!0,setTimeout(function(){var e,a,r,t,o="script";e=window,a=document,e.ym=e.ym||function(){(e.ym.a=e.ym.a||[]).push(arguments)},e.ym.l=+new Date,r=a.createElement(o),t=a.getElementsByTagName(o)[0],r.async=1,r.src="https://mc.yandex.ru/metrika/tag.js",t.parentNode.insertBefore(r,t),ym(24294061,"init",{clickmap:!0,trackLinks:!0,accurateTrackBounce:!0,webvisor:!0})},0))}document.referrer&&/^https?:\/\/([^\/]+\.)?(webvisor\.com|metri[ck]a\.yandex\.(com|ru|by|com\.tr))\//.test(document.referrer)&&loadMetrika(),window.addEventListener("scroll",loadMetrika,{passive:!0}),window.addEventListener("mousemove",loadMetrika);
</script>
<noscript><div><img src="https://mc.yandex.ru/watch/24294061" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter & End Google Tag Manager -->
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<meta property="og:title" content="<?php echo get_the_title();?>" />
	<meta property="og:type" content="article" /> 
	<?php if (  has_post_thumbnail() ) { ?>
		 <meta property="og:image" content="<?php echo the_post_thumbnail_url(); ?>" />
	<?php } else { ?>
		<meta property="og:image" content="<?php echo get_template_directory_uri().'/images/no-preview.jpg'; ?>" />
	<?php } ?>
	
	<meta property="og:url" content="<?php echo get_permalink();?>" />

	<?php 
	
	if (strpos($_SERVER['REQUEST_URI'], '/tag/') == true) {
	//remove_action('wp_head', '_wp_render_title_tag',1);
	$tags_str = single_term_title('', 0);
	
	?>
	
	<!-- Tag -->
	<title>Materials related to <?php echo $tags_str;?> | JazzTeam Software Development Company</title>
	<meta name="description" content="Materials related to <?php echo $tags_str; ?> from the JazzTeam Software Development team. Useful articles, examples of implemented projects on the <?php echo $tags_str; ?> topic.">
	<meta name="keywords" content="<?php echo $tags_str; ?>, <?php echo $tags_str; ?> and JazzTeam">
	<!-- End Tag -->

	<?php } elseif ( strpos($_SERVER['REQUEST_URI'], '/portfolio') == true &&  $_SERVER['REQUEST_URI'] != '/en/portfolio/'){
	//remove_action('wp_head', '_wp_render_title_tag',1);
		
		echo get_post_meta($post->ID, _aioseop_description, true);
	?>
	<?php } elseif ( strpos($_SERVER['REQUEST_URI'], '/category/company-news') == true   ) { ?>
		<meta name="description" content="Stay in the loop with updates on the Jazzteam's company life, industry recognitions, and the latest software development projects!">
	<?php } elseif ( strpos($_SERVER['REQUEST_URI'], '/category/technical-articles') == true   ) { ?>
		<meta name="description" content="Gain valuable insights on software development, IT solutions, and the latest industry trends from Jazzteam's seasoned experts. Our practical tips in digital engineering can help you stay ahead in the fast-paced world of innovative technologies!">
	<?php } elseif ( strpos($_SERVER['REQUEST_URI'], '/business-articles') == true   ) { ?>
		<meta name="description" content="Explore strategic insights and expert advice driving your company's innovation and helping navigate technological and business challenges.">
	<?php }
		
		
	elseif ( $_SERVER['REQUEST_URI'] =='/en/portfolio/') { ?>
		<title>JazzTeam Software Development portfolio | IT solutions and company products</title>
		<meta name="description" content="Explore JazzTeam case studies and IT solutions in the software development and IT consulting portfolio. We invite you to familiarize yourself with the portfolio - solutions that have helped our clients develop their business and increase income.">
	<?php 
		remove_action('wp_head', '_wp_render_title_tag',1);
	}
		
	else {		
		//add_action('wp_head', '_wp_render_title_tag',1);
	}

	if (  str_contains($_SERVER['REQUEST_URI'], '/portfolio') && ($_GET['id'] || $_GET['desc'])) { ?>
		<title><?php echo get_the_title(); ?> | IT solutions and company products</title>
	<?php } ?>

	
	
	 <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;0,700;0,900;1,800&family=Open+Sans:wght@300;400;500;600;700;800&display=swap"
      rel="stylesheet"
    />
	<link rel="profile" href="http://gmpg.org/xfn/11" />
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
	<!-- <link rel="shortcut icon" type="image/x-icon" href="<?php //echo ot_get_option('pp_favicon_upload', get_template_directory_uri().'/images/favicon.ico')?>" /> -->
	<!--[if lt IE 9]>
	<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js" type="text/javascript"></script>
	<![endif]-->

<!-- Fonts
	================================================== -->
	<?php
 	    /*if(ot_get_option('pp_logofonts_on') =="yes") {
	   	 	$logofont = ot_get_option('pp_logo_typo',array());
	   			if(ot_get_option('pp_fonts_on') == 'yes') { $fontl = '|'.$logofont['font-family']; } else { $fontl = $logofont['font-family']; }
	    } else { $fontl = ""; }
	    if(ot_get_option('pp_fonts_on') == 'yes')  {
	    	$fonts =  ot_get_option('pp_body_font').'|'.ot_get_option('pp_h_font').'';
	    } else { $fonts = ''; }

	if(ot_get_option('pp_fonts_on') == 'yes' || ot_get_option('pp_logofonts_on') =="yes" )  { ?>
		<link href='//fonts.googleapis.com/css?family=<?php echo $fonts.$fontl;?>' rel='stylesheet' type='text/css'>
	<?php }*/
	?>
<?php wp_head(); 

$title = get_the_title();
$image = get_the_post_thumbnail_url();
?>
<script type="application/ld+json">
{
	"@context": "https://schema.org",
	"@type": "JazzTeam",
	"url": "https://jazzteam.org/en/",
	"logo": "https://jazzteam.org/en/images/logo.png"
	<?php if( is_single() ){
		echo ',"image":["'.$image.'"],';
		echo '"headline": "'.$title.'",';
		echo 
		'"author":[ 
			{
			"@type":"Organization",
			"name": "JazzTeam",
			"url": "https://jazzteam.org/"
			}
		]';
	}?>}
	
</script>

<style id="ac">body { display: none; }</style>
<script type="text/javascript"> if (self === top || /^https?:\/\/([^\/]+\.)?(jazzteam\.org|webvisor\.com|metri[ck]a\.yandex\.(com|ru|by|com\.tr))\//.test(document.referrer)) { var ac = document.getElementById("ac"); ac.parentNode.removeChild(ac);}</script>

<!--  <Slick slider  -->
<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
<script type="text/javascript" src="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
<!--  Slick slider>	 -->

</head>


<body data-is-front="<?php echo is_front_page() == 1 ? 'true' : 'false'; ?>" <?php $style = get_theme_mod( 'astrum_layout_style', 'boxed' ); body_class($style); ?>>
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-W8WSBXT"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
<?php do_action( 'before' ); ?>
<!-- Header
================================================== -->
<header id="header">

<!-- Container -->
<div class="container">
	<?php
		$logo_area_width = ot_get_option('pp_logo_area_width',3);
		$menu_area_width = 16 - $logo_area_width;
	?>
	<!-- Logo / Mobile Menu -->
	<div class="<?php echo incr_number_to_width($logo_area_width); ?> columns nav-menu-container">
		<div id="mobile-navigation">
			<form method="get" id="menu-search" action="<?php echo esc_url( home_url( '/' ) ); ?>">
				<input type="text" class="mobile-nav__inp" name="s" id="s" placeholder="<?php _e('Start Typing...','purepress'); ?>" />
			</form>
			<a href="#menu" class="menu-trigger" data-open-menu-mobile="false"><i class="icon-reorder jt-icon-visible"></i><i class="menu-icon-remove"></i></a>
			<span class="search-trigger"><i class="icon-search"></i></span>
		</div>

		<div id="logo">
			<?php
			$logo = ot_get_option( 'pp_logo_upload' );
			if($logo) { ?>
				<?php if(is_front_page()){ ?>
					<a href="<?php echo home_url('/'); ?>" title="<?php echo esc_attr(get_bloginfo('name', 'display')); ?>" rel="home"><img src="<?php echo $logo; ?>" alt="<?php bloginfo('name'); ?>"/></a>
				<?php } else { ?>
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><img src="<?php echo $logo; ?>" alt="<?php bloginfo('name'); ?>"/></a>
				<?php }
			} else { ?>
				<?php if(is_front_page()){ ?>
					<h1><a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
				<?php } else { ?>
					<h2><a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h2>
				<?php } ?>
			<?php } ?>
			<?php if(get_theme_mod('astrum_tagline_switch','show') == 'show') { ?><div id="blogdesc"><?php bloginfo( 'description' ); ?></div><?php } ?>
		</div>
	</div>

	<!-- Navigation
	================================================== -->
	<div class="<?php echo incr_number_to_width($menu_area_width); ?> columns jt-mobile-menu-columns">
		<?php $minicart = ot_get_option( 'pp_woo_header_cart' ); if($minicart== 'yes') { get_template_part( 'inc/mini_cart'); }?>
		<nav id="navigation" class="menu">
		<?php /*wp_nav_menu( array(
		'theme_location' => 'primary',
		'container' => false,
		'menu_id' => 'responsive',
		'walker' => new New_Astrum_Custom_Menu_Walker()
		));*/
			if ( wp_is_mobile() ) {
				

				 wp_nav_menu( array( 
					'theme_location' => 'mobile',
					'container' => false,
					'menu_id' => 'responsive',
					'walker' => new New_Astrum_Custom_Menu_Walker(),
				 ) );
				//echo '1';
			} else {
				
				 wp_nav_menu( array(
					'theme_location' => 'primary',
					'container' => false,
					'menu_id' => 'responsive',
					'walker' => new New_Astrum_Custom_Menu_Walker(),
				 ) );
				//echo '2';

			}	?>
		</nav>
	</div>

</div>
<!-- Container / End -->
</header>
<!-- Header / End -->
<!-- Content Wrapper / Start -->
<div id="content-wrapper">
