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
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

	<title><?php wp_title( '|', true, 'right' ); ?></title>
	<meta name="description" content="<?php bloginfo('description'); ?>" />

	<link rel="profile" href="http://gmpg.org/xfn/11" />
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
	<link rel="shortcut icon" type="image/x-icon" href="<?php echo ot_get_option('pp_favicon_upload', get_template_directory_uri().'/images/favicon.ico')?>" />
	<!--[if lt IE 9]>
	<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js" type="text/javascript"></script>
	<![endif]-->

<!-- Fonts
	================================================== -->
	<?php
 	    if(ot_get_option('pp_logofonts_on') =="yes") {
	   	 	$logofont = ot_get_option('pp_logo_typo',array());
	   			if(ot_get_option('pp_fonts_on') == 'yes') { $fontl = '|'.$logofont['font-family']; } else { $fontl = $logofont['font-family']; }
	    } else { $fontl = ""; }
	    if(ot_get_option('pp_fonts_on') == 'yes')  {
	    	$fonts =  ot_get_option('pp_body_font').'|'.ot_get_option('pp_h_font').'';
	    } else { $fonts = ''; }

	if(ot_get_option('pp_fonts_on') == 'yes' || ot_get_option('pp_logofonts_on') =="yes" )  { ?>
		<link href='http://fonts.googleapis.com/css?family=<?php echo $fonts.$fontl;?>' rel='stylesheet' type='text/css'>
	<?php }
	?>
<?php wp_head(); ?>
</head>


<body <?php $style = get_theme_mod( 'astrum_layout_style', 'boxed' ); body_class($style); ?>>
<?php do_action( 'before' ); ?>
<!-- Header
================================================== -->
<header id="header-full">

<!-- Container -->
<div class="container">
	<?php
		$logo_area_width = ot_get_option('pp_logo_area_width',3);
		$menu_area_width = 16 - $logo_area_width;
	?>
	<!-- Logo / Mobile Menu -->
	<div class="sixteen columns">
		<div id="mobile-navigation">
			<form method="get" id="menu-search" action="<?php echo esc_url( home_url( '/' ) ); ?>">
				<input type="text" name="s" id="s" placeholder="<?php _e('Start Typing...','purepress'); ?>" />
			</form>
			<a href="#menu" class="menu-trigger"><i class="icon-reorder"></i></a>
			<span class="search-trigger"><i class="icon-search"></i></span>
		</div>

		<div id="logo">
			<?php
			$logo = ot_get_option( 'pp_logo_upload' );
			if($logo) { ?>
				<?php if(is_front_page()){ ?>
					<h1><a href="<?php echo home_url('/'); ?>" title="<?php echo esc_attr(get_bloginfo('name', 'display')); ?>" rel="home"><img src="<?php echo $logo; ?>" alt="<?php bloginfo('name'); ?>"/></a></h1>
				<?php } else { ?>
					<h2><a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><img src="<?php echo $logo; ?>" alt="<?php bloginfo('name'); ?>"/></a></h2>
				<?php }
			} else { ?>
				<?php if(is_front_page()){ ?>
					<h1><a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
				<?php } else { ?>
					<h2><a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h2>
				<?php } ?>
			<?php }
			if(get_theme_mod('astrum_tagline_switch','show') == 'show') { ?><div id="blogdesc"><?php bloginfo( 'description' ); ?></div><?php } ?>
		</div>
		<?php
			if(ot_get_option( 'pp_contact_details') == 'yes') {
				$email = ot_get_option( 'pp_cdetails_email');
				$phone = ot_get_option( 'pp_cdetails_phone');
			?>
			<!-- Contact Details -->
			<div id="contact-details">
				<ul>
					<?php if($email) { ?><li><i class="icon-envelope"></i><a href="mailto:<?php echo $email ;?>"><?php echo $email ;?></a></li><?php } ?>
					<?php if($phone) { ?><li><i class="icon-user"></i><?php echo $phone ;?></li><?php } ?>
				</ul>
			</div>
		<?php } ?>
	</div>
</div>
<div class="menu-container">
	<div class="container">
		<!-- Navigation
		================================================== -->
		<div class="sixteen columns">
			<?php $minicart = ot_get_option( 'pp_woo_header_cart' );
					if($minicart== 'yes') { get_template_part( 'inc/mini_cart'); }
			?>
			<nav id="navigation" class="menu clearfix">
			<?php wp_nav_menu( array(
			'theme_location' => 'primary',
			'container' => false,
			'menu_id' => 'responsive'
			)); ?>
			</nav>
		</div>
	</div>
	<!-- Container / End -->
</div>
</header>
<!-- Header / End -->

<!-- Content Wrapper / Start -->
<div id="content-wrapper">