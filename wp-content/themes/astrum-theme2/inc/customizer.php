<?php
/**
 * astrum Theme Customizer
 *
 * @package astrum
 * @since astrum 1.2
 */


/*add_action ('admin_menu', 'astrum_customize_admin');
function astrum_customize_admin() {
    add_theme_page( 'Customize', 'Customize', 'edit_theme_options', 'customize.php' );
}
*/

/**
 * Convert a hexa decimal color code to its RGB equivalent
 *
 * @param string $hexStr (hexadecimal color value)
 * @param boolean $returnAsString (if set true, returns the value separated by the separator character. Otherwise returns associative array)
 * @param string $seperator (to separate RGB values. Applicable only if second parameter is true.)
 * @return array or string (depending on second parameter. Returns False if invalid hex color value)
 */
function astrumhex2rgb($hexStr, $returnAsString = false, $seperator = ',') {
    $hexStr = preg_replace("/[^0-9A-Fa-f]/", '', $hexStr); // Gets a proper hex string
    $rgbArray = array();
    if (strlen($hexStr) == 6) { //If a proper hex code, convert using bitwise operation. No overhead... faster
        $colorVal = hexdec($hexStr);
        $rgbArray['red'] = 0xFF & ($colorVal >> 0x10);
        $rgbArray['green'] = 0xFF & ($colorVal >> 0x8);
        $rgbArray['blue'] = 0xFF & $colorVal;
    } elseif (strlen($hexStr) == 3) { //if shorthand notation, need some string manipulations
        $rgbArray['red'] = hexdec(str_repeat(substr($hexStr, 0, 1), 2));
        $rgbArray['green'] = hexdec(str_repeat(substr($hexStr, 1, 1), 2));
        $rgbArray['blue'] = hexdec(str_repeat(substr($hexStr, 2, 1), 2));
    } else {
        return false; //Invalid hex color code
    }
    return $returnAsString ? implode($seperator, $rgbArray) : $rgbArray; // returns the rgb string or the associative array
}


add_action( 'customize_register', 'astrum_customize_register' );


function astrum_customize_register($wp_customize) {
  // color section
  $wp_customize->add_section( 'astrum_color_settings', array(
    'title'          => __('Main color','purethemes'),
    'priority'       => 35,
    ) );

  $wp_customize->add_setting( 'astrum_main_color', array(
    'default'        => '#73b819',
    'transport' =>'postMessage'
    ) );

  $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'astrum_main_color', array(
    'label'   => __('Color Setting','purethemes'),
    'section' => 'colors',
    'settings'   => 'astrum_main_color',
    )));
    // eof color section

    // bof layout section
  $wp_customize->add_section( 'astrum_layout_settings', array(
      'title'          => __('Layout','purethemes'),
      'priority'       => 36,
    ));

  $wp_customize->add_setting( 'astrum_layout_style', array(
    'default'  => 'wide',
    'transport' => 'postMessage'
    ));
  $wp_customize->add_control( 'astrum_layout_choose', array(
    'label'    => __('Select layout type','purethemes'),
    'section'  => 'astrum_layout_settings',
    'settings' => 'astrum_layout_style',
    'type'     => 'select',
    'choices'    => array(
        'wide' => 'Wide',
        'boxed' => 'Boxed',
        )
    ));
  $wp_customize->add_setting( 'astrum_footer_style', array(
    'default'  => 'light',
    'transport' => 'postMessage'
    ));
  $wp_customize->add_control( 'astrum_footer_choose', array(
    'label'    => __('Select footer style','purethemes'),
    'section'  => 'astrum_layout_settings',
    'settings' => 'astrum_footer_style',
    'type'     => 'select',
    'choices'    => array(
        'light' => 'Light',
        'dark' => 'Dark',
        )
    ));


   // eof layout section

  $wp_customize->add_setting( 'astrum_tagline_switch', array(
    'default'  => 'show',
    'transport' => 'refresh'
    ));
  $wp_customize->add_control( 'astrum_tagline_switcher', array(
   'settings' => 'astrum_tagline_switch',
   'label'    => __( 'Display Tagline','purepress' ),
   'section'  => 'title_tagline',
   'type'     => 'select',
   'choices'    => array(
    'show' => 'Show',
    'hide' => 'Hide',
    )
   ));


  if ( $wp_customize->is_preview() && !is_admin() ) {
    add_action( 'wp_footer', 'astrum_customize_preview', 21);
  }
}


function astrum_customize_preview() { ?>
    <script type="text/javascript">
    ( function( $ ){
        function astrumhex2rgb(hex) {
            if (hex[0]=="#") hex=hex.substr(1);
            if (hex.length==3) {
                var temp=hex; hex='';
                temp = /^([a-f0-9])([a-f0-9])([a-f0-9])$/i.exec(temp).slice(1);
                for (var i=0;i<3;i++) hex+=temp[i]+temp[i];
            }
        var triplets = /^([a-f0-9]{2})([a-f0-9]{2})([a-f0-9]{2})$/i.exec(hex).slice(1);
        return {
            red: parseInt(triplets[0],16),
            green: parseInt(triplets[1],16),
            blue: parseInt(triplets[2],16)
        }
    }


    wp.customize('astrum_main_color',function( value ) {
        value.bind(function(to) {

        $('a:visited,#not-found i,.testimonials-author,.happy-clients-author,.dropcap,.list-1 li:before,.list-2 li:before,.list-3 li:before,.list-4 li:before').css('color',to);
        $('.menu ul li.current-menu-ancestor > a,:hover,.menu ul > li:hover > a,.flickr-widget-blog a:hover,#footer .flickr-widget-blog a:hover').css('border-color',to);
        $('.menu ul ul').css('border-top-color',to);
        $('#filters a:hover,.selected').css('background-color',to);
        $('.premium .plan-price,.premium .plan-features a.button:hover').css('background-color',to);
        $('.current-menu-ancestor > a:after,.pagination .current,.pagination ul li a:hover,.tagcloud a:hover,.button.gray:hover,.button.light:hover, .product-add-to-cart .add_to_cart.button, .button.color,input[type=button],input[type=submit],input[type=button]:focus,input[type=submit]:focus, #footer .tabs-nav li.active a, .tabs-nav li.active a,.ui-accordion .ui-accordion-header-active:hover,.ui-accordion .ui-accordion-header-active,.trigger.active a,.trigger.active a:hover,.skill-bar-value,.highlight.color,.notice-box:hover').css('background',to);
        $('.featured-box:hover > .circle,.featured-box:hover > .circle span,.featured-box:hover > .circle-2,.featured-box:hover > .circle-3,.portfolio-item:hover > figure > a .item-description,.widget_ns_mailchimp input.button, .newsletter-btn,.search-btn,.premium.plan h3,.premium .plan-features a.button').css('background-color',to);


         $('.comment-by span.reply a,.comment-by span.reply a,.categories a,.meta ul li a').hover(
          function(){
            var attr = $(this).attr('orgcolor');
            if (typeof attr == 'undefined' || attr == false) {
              var orgbg = $(this).css('color');
            }
            $(this).attr('orgcolor', orgbg).css('color', to);
          }, function(){
            var bg = $(this).attr('orgcolor');
            $(this).css('color', bg);
          } );

           $('#current,.menu ul li a').hover(
          function(){
            var attr = $(this).attr('orgbordercolor');
            if (typeof attr == 'undefined' || attr == false) {
              var orgbg = $(this).css('border-color');
            }
            $(this).attr('orgbordercolor', orgbg).css('border-color', to);
          }, function(){
            var bg = $(this).attr('orgbordercolor');
            $(this).css('border-color', bg);
          } );

         $('.tp-leftarrow,.tp-rightarrow,.flexslider .flex-next,.flexslider .flex-prev,.sb-navigation-left,.sb-navigation-right').hover(
          function(){
            var attr = $(this).attr('orgbackground');
            if (typeof attr == 'undefined' || attr == false) {
              var orgbg = $(this).css('background-color');
            }
            $(this).attr('orgbackground', orgbg).css('background-color', to);
          }, function(){
            var bg = $(this).attr('orgbackground');
            $(this).css('background-color', bg);
          } );

          $('.portfolio-item').hover(
          function(){
            var attr = $(this).find('figure a .item-description').attr('orgbordertopcolor');
            if (typeof attr == 'undefined' || attr == false) {
              var orgbg = $(this).find('figure .item-description').css('borderTopColor');
            }
            $(this).find('figure a .item-description').attr('orgbordertopcolor', orgbg).css('borderTopColor', to);
          }, function(){
            var bg = $(this).find('figure a .item-description').attr('orgbordertopcolor');
            $(this).find('figure a .item-description').css('borderTopColor', bg);
          } );

           });
      });

      wp.customize('astrum_tagline_switch',function( value ) {
          value.bind(function(to) {
              if(to === 'hide') { $('#blogdesc').hide(); } else { $('#blogdesc').show(); }
          });
      });
      wp.customize('astrum_layout_style',function( value ) {
          value.bind(function(to) {
            $("body").removeClass("boxed").removeClass("wide").addClass(to);
          });
      });

      wp.customize('astrum_footer_style',function( value ) {
          value.bind(function(to) {
           $("#footer, #footer-bottom").removeClass("dark").removeClass("light").addClass(to);
          });
      });


//.image-overlay-link, .image-overlay-zoom
} )( jQuery )
</script>
<?php
}

//add_action('wp_head', 'custom_stylesheet_content');

function custom_stylesheet_content() {
 $ltopmar = ot_get_option( 'pp_logo_top_margin' );
 $ltopmarsticky = ot_get_option( 'pp_logo_top_margin_sticky' );
 $lbotmar = ot_get_option( 'pp_logo_bottom_margin' );
 $taglinemar = ot_get_option( 'pp_tagline_margin' );
 $logofont = ot_get_option('pp_logo_typo',array());
 $bodytypo = ot_get_option( 'astrum_body_font');
 $menutypo = ot_get_option( 'astrum_menu_font');
 $logotypo = ot_get_option( 'astrum_logo_font');
 $headerstypo = ot_get_option( 'astrum_headers_font');
 global $post;
 global $google_array;

 ?>
 <style type="text/css">
 #header { height: <?php echo ot_get_option('pp_header_height',86);?>px; }

  body { <?php phantom_generate_typo_css($bodytypo); ?> }
  #content h1, h2, h3, h4, h5, h6  { <?php phantom_generate_typo_css($headerstypo); ?> }
  #logo h2 a, #logo h1 a { <?php phantom_generate_typo_css($logotypo); ?> }
  .menu ul li a {  <?php phantom_generate_typo_css($menutypo); ?>  }

  #logo {
    <?php if ( isset( $ltopmar[0] ) && $ltopmar[1] ) { echo 'margin-top:'.$ltopmar[0].$ltopmar[1].';'; } ?>
    <?php if ( isset( $lbotmar[0] ) && $lbotmar[1] ) { echo 'margin-bottom:'.$lbotmar[0].$lbotmar[1].';'; } ?>
  }
  .compact #logo {
    <?php if ( isset( $ltopmar[0] ) && $ltopmar[1] ) { echo 'margin-top:'.$ltopmarsticky[0].$ltopmarsticky[1].';'; } ?>
  }
  #tagline { <?php if ( isset( $taglinemar[0] ) && $taglinemar[1] ) { echo 'margin-top:'.$taglinemar[0].$taglinemar[1].';'; } ?> }
  <?php
$custom_main_color = get_theme_mod('astrum_main_color','#73b819');
$custom_rgb = astrumhex2rgb($custom_main_color);
if($custom_rgb) {
  $red = $custom_rgb['red'];
  $green = $custom_rgb['green'];
  $blue = $custom_rgb['blue'];
}
 ?>

a,a:visited,#not-found i,.comment-by span.reply a:hover,.comment-by span.reply a:hover i,.categories a:hover,.testimonials-author,.happy-clients-author,.dropcap,.meta ul li a:hover,.list-1 li:before,.list-2 li:before,.list-3 li:before,.list-4 li:before, .widget li.current_page_item a, a.twitter-link, .widget li.twitter-item a
{color:<?php echo $custom_main_color; ?>}
#astrum_header_cart ul li img:hover, ul.product_list_widget li img:hover,  .menu > ul > li.current-menu-item > a, .menu ul li.current-menu-ancestor > a,#current,.menu ul li a:hover,.menu ul > li:hover > a,.flickr-widget-blog a:hover,#footer .flickr-widget-blog a:hover, .widget .flickr-widget-blog li a:hover
{border-color:<?php echo $custom_main_color; ?>}
.menu ul ul
{border-top-color:<?php echo $custom_main_color; ?>}
#filters a:hover,.selected:not(.stars), ul.product_list_widget li img:hover, #astrum_header_cart ul li img:hover, .quantity.buttons_added .plus:hover, .quantity.buttons_added .minus:hover
{background-color:<?php echo $custom_main_color; ?>!important}
.wc-proceed-to-checkout .button,.actions .wc-forward,.premium .plan-price,.premium .plan-features a.button:hover, .cart_contents, .price_slider_wrapper .button:hover
{background-color:<?php echo $custom_main_color; ?>}
.featured-box:hover > .circle-2,.featured-box:hover > .circle-3 {box-shadow:0 0 0 8px rgba(<?php echo $red; ?>,<?php echo $green; ?>,<?php echo $blue; ?>,0.3)}
.variations_button .button, /*.menu > ul > li.current-menu-item > a:after*/, .current-menu-ancestor > a:after,.wp-pagenavi .current, .pagination .current,.pagination a:hover, .wp-pagenavi a:hover, .tagcloud a:hover,.button.gray:hover,.button.light:hover,.button.color,input[type=button],input[type=submit],input[type=button]:focus,input[type=submit]:focus, #footer .tabs-nav li.active a, .tabs-nav li.active a,.ui-accordion .ui-accordion-header-active:hover,.ui-accordion .ui-accordion-header-active,.trigger.active a,.woocommerce-MyAccount-navigation li.is-active a,.trigger.active a:hover,.skill-bar-value,.highlight.color,.notice-box:hover
{background:<?php echo $custom_main_color; ?>}
.price_slider_wrapper .ui-widget-header,span.onsale, .tp-leftarrow:hover,.tp-rightarrow:hover,.flexslider .flex-next:hover,.flexslider .flex-prev:hover,.featured-box:hover > .circle,.featured-box:hover > .circle span,.featured-box:hover > .circle-2,.featured-box:hover > .circle-3,.portfolio-item:hover > figure > a .item-description,.sb-navigation-left:hover,.sb-navigation-right:hover,.widget_ns_mailchimp input.button, .newsletter-btn,.search-btn,.premium.plan h3,.premium .plan-features a.button,.pagination ul li a:hover
{background-color:<?php echo $custom_main_color; ?>}

<?php echo ot_get_option( 'pp_custom_css' );  ?>
</style>
<?php
// .bypostauthor > .comment > .comment-des {
//  border-bottom: 1px solid <?php echo $custom_main_color;
// }
}   //eof custom_stylesheet_content


function mobile_menu_css(){
  $breakpoint = ot_get_option('pp_menu_breakpoint','767');
  ?>
  <style type="text/css">
/* =================================================================== */
/* Mobile Navigation
====================================================================== */
#mobile-navigation { display: none; }

@media only screen and (max-width: <?php echo $breakpoint; ?>px) {

  #mobile-navigation { display: block; }
  #astrum_header_cart,
  #responsive,
  .search-container { display: none; }

  .container .columns.nav-menu-container {
    width: 98%
  }

  #header {
    z-index: 999;
    background-color: #fff;
    height: 79px;
    margin-top: 0;
    position: relative;
    width: 100%;
  }

  body { padding-top: 0; }
  #header-full #logo,
  #header #logo {
    width: 124px;
/*  left: 0;
    right: 0;
    position: absolute; */
    margin: 0 auto;
    text-align: center;
    margin-top: 21px;
    z-index: 99;
    float: none;

  }
  #blogdesc {
    display: none;
  }
  #logo img {
    max-height: 51px;
    width: auto;
  }

  #header-full #contact-details {
    float: left;
    margin-top: 0px;
  }
  #header-full #contact-details ul {
    margin-left: 0px
  }

  a.menu-trigger {
    color: #a0a0a0;
    display: block;
    font-size: 28px;
    float: left;
    background: #fff;
    z-index: 100;
    position: relative;
  }

  .search-trigger {
    color: #a0a0a0;
    display: block;
    font-size: 28px;
    float: right;
    cursor: pointer;
    background: #fff;
    z-index: 100;
    position: relative;
  }

  #menu-search {
    display: none;
  }

  #menu-search input {
    float: left;
    box-shadow: none;
    border: 0;
    font-size: 16px;
    color: #aaa;
    width: 50%;
    padding: 27px 0 23px 0;
  }

  a.menu-trigger,
  .search-trigger { padding: 25px 30px; }

  a.menu-trigger { padding-left:0; }
  .search-trigger { padding-right:0; }

  #menu-search input:focus { color: #888; }

  #menu-search input::-webkit-input-placeholder { color: #a0a0a0; opacity: 1;}
  #menu-search input::-moz-placeholder { color: #a0a0a0; opacity: 1; }
  #menu-search input:-ms-input-placeholder { color: #a0a0a0; opacity: 1; }
  #menu-search input:focus::-webkit-input-placeholder { color: #888; opacity: 1; }
  #menu-search input:focus::-moz-placeholder { color: #888; opacity: 1; }
  #menu-search input:focus:-ms-input-placeholder { color: #888; opacity: 1; }

}
</style>
  <?php
}
//add_action('wp_footer', 'mobile_menu_css');