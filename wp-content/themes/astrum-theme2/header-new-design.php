<?php
/**
 * The Header new design for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package Nevia
 * @since Nevia 1.0
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo( 'charset' ); ?>" >
  <?php if( !is_singular( 'test-pages' ) ){ ?>
  <script>
    var GTMMD2VBGDLoad=!1;function loadGTM(){var e,t,a,n;!1===GTMMD2VBGDLoad&&(GTMMD2VBGDLoad=!0,e=window,t=document,a="script",e[n="dataLayer"]=e[n]||[],e[n].push({"gtm.start":(new Date).getTime(),event:"gtm.js"}),n=t.getElementsByTagName(a)[0],(a=t.createElement(a)).async=!0,a.src="https://www.googletagmanager.com/gtm.js?id=GTM-W8WSBXT",n.parentNode.insertBefore(a,n))}window.addEventListener("scroll",function(){!1===GTMMD2VBGDLoad&&(GTMMD2VBGDLoad=!0,setTimeout(function(){var e,t,a,n;e=window,t=document,a="script",e[n="dataLayer"]=e[n]||[],e[n].push({"gtm.start":(new Date).getTime(),event:"gtm.js"}),n=t.getElementsByTagName(a)[0],(a=t.createElement(a)).async=!0,a.src="https://www.googletagmanager.com/gtm.js?id=GTM-W8WSBXT",n.parentNode.insertBefore(a,n)},1e3))},{passive:true});
    var YANDEXLoad=!1;function loadMetrika(){!1===YANDEXLoad&&(YANDEXLoad=!0,setTimeout(function(){var e,a,r,t,o="script";e=window,a=document,e.ym=e.ym||function(){(e.ym.a=e.ym.a||[]).push(arguments)},e.ym.l=+new Date,r=a.createElement(o),t=a.getElementsByTagName(o)[0],r.async=1,r.src="https://mc.yandex.ru/metrika/tag.js",t.parentNode.insertBefore(r,t),ym(24294061,"init",{clickmap:!0,trackLinks:!0,accurateTrackBounce:!0,webvisor:!0})},0))}document.referrer&&/^https?:\/\/([^\/]+\.)?(webvisor\.com|metri[ck]a\.yandex\.(com|ru|by|com\.tr))\//.test(document.referrer)&&loadMetrika(),window.addEventListener("scroll",loadMetrika,{passive:!0}),window.addEventListener("mousemove",loadMetrika);
  </script>
<?php } else { ?>
<meta name="robots" content="noindex, nofollow"/>
<?php } ?>
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <meta property="og:title" content="<?php echo get_the_title();?>">
  <meta property="og:type" content="article">
  <meta name="twitter:card" content="summary_large_image" /> 
  <?php if (  has_post_thumbnail() ) { ?>
  <meta property="og:image" content="<?php echo the_post_thumbnail_url(); ?>">
  <?php } else { ?>
  <meta property="og:image" content="<?php echo get_template_directory_uri().'/images/jazzteampreview.webp'; ?>">
  <?php }
  $canonical_url = get_canonical_url();
  if($canonical_url) {?>
  <meta property="og:url" content="<?php echo $canonical_url; ?>"><?php
  } 
  if ( str_contains($_SERVER['REQUEST_URI'], '/tag/') ) {
    $tags_str = single_term_title('', 0);
  ?>	
  <title>Materials related to <?php echo $tags_str;?> | JazzTeam Software Development Company</title>
  <meta name="description" content="Materials related to <?php echo $tags_str; ?> from the JazzTeam Software Development team. Useful articles, examples of implemented projects on the <?php echo $tags_str; ?> topic.">
  <meta name="keywords" content="<?php echo $tags_str; ?>, <?php echo $tags_str; ?> and JazzTeam">
  <?php } elseif ( str_contains($_SERVER['REQUEST_URI'], 'portfolio') &&  $_SERVER['REQUEST_URI'] != 'portfolio/' ){	
    echo get_post_meta($post->ID, _aioseop_description, true);
  ?>
  <?php } elseif ( $_SERVER['REQUEST_URI'] == 'portfolio/' ) { ?>
    <title>JazzTeam Software Development portfolio | IT solutions and company products</title>
    <meta name="description" content="Explore JazzTeam case studies and IT solutions in the software development and IT consulting portfolio. We invite you to familiarize yourself with the portfolio - solutions that have helped our clients develop their business and increase income.">
  <?php 
    remove_action('wp_head', '_wp_render_title_tag',1);
  }  

  if ( str_contains($_SERVER['REQUEST_URI'], 'portfolio') && ($_GET['id'] || $_GET['desc']) ) { ?>
    <title><?php echo get_the_title(); ?> | IT solutions and company products</title>
  <?php } ?>

  <link rel="profile" href="http://gmpg.org/xfn/11">
  <link rel="icon" href="https://jazzteam.org/wp-content/uploads/2023/07/cropped-cropped-2020-01-15_125510-32x32.webp" sizes="32x32">	
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link
    href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;0,700;0,900;1,800&family=Open+Sans:wght@300;400;500;600;700;800;900&display=swap"
    rel="stylesheet"
  />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" media="all" />
  <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
  <script src="<?php echo get_template_directory_uri().'/new_design_assets/js/script.js'; ?>" defer></script>
<?php 

wp_head();

  $title = get_the_title();
  $image = get_the_post_thumbnail_url();
  $uniq_id_for_style_and_script = uniqid();
?>
  <link rel="stylesheet" href="<?php echo get_template_directory_uri().'/new_design_assets/css/styles.css?ver='.$uniq_id_for_style_and_script; ?>" />
  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@type": "JazzTeam",
    "url": "https://jazzteam.org",
    "logo": "https://jazzteam.org/images/logo.png"
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
  <script> if (self === top || /^https?:\/\/([^\/]+\.)?(jazzteam\.org|webvisor\.com|metri[ck]a\.yandex\.(com|ru|by|com\.tr))\//.test(document.referrer)) { var ac = document.getElementById("ac"); ac.parentNode.removeChild(ac);}</script>
</head>

<body <?php $style = get_theme_mod( 'astrum_layout_style', 'boxed' ); body_class($style); ?>>
<noscript><img src="https://mc.yandex.ru/watch/24294061" style="position:absolute; left:-9999px;" alt="" /><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-W8WSBXT" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<?php do_action( 'before' ); ?>
    <header class="header" data-test>
	  <div class="container header__container">
		<a class="header__company-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>"><img class="header_company-picture-raw" src="<?php echo get_template_directory_uri().'/new_design_assets/img/logo/company-logo.svg'; ?>" fetchpriority="high" alt="Jazzteam" /></a>
		<div class="header__core">

			<nav class="header__nav">
				<?php
					if ( wp_is_mobile() ) {
						 wp_nav_menu( array( 
							'theme_location' => 'mobile',
							'container' => false,
							'walker' => new New_Design_Menu_Walker(),
						 ) );
					} else {
						 wp_nav_menu( array(
							'theme_location' => 'primary',
							'container' => false,
							'walker' => new New_Design_Menu_Walker(),
						 ) );
				}
				?>
				<a href="https://jazzteam.org/contacts/" class="btn btn--orange header__contact-us-mobile" data-wpel-link="internal"><span class="headline-four">Contact us</span></a>
			</nav>
			
			<div class="header__controls">
				<a href="https://jazzteam.org/contacts/" class="btn btn--orange header__contact-us"><span class="headline-four">Contact us</span></a>
				<form method="get" class="header__search" action="<?php echo esc_url( home_url( '/' ) ); ?>" role="search" data-form-processed="true">
 					<input type="text" name="s" id="s" class="header__search-field">
 				</form>
				<button class="header__toggle-mobile-menu" aria-label="Toggle mobile menu"><span class="header__toggle-mobile-menu-icon"></span></button>
			</div>
		</div>
	  </div>
		<div class="header__backdrop-shadow"></div>
	</header>