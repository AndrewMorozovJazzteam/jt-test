<?php
/**
 * The template for displaying the footer new design.
 **/
function load_footer_widget($name) {
    ob_start();
    if ( is_active_sidebar( $name ) ) {
        dynamic_sidebar( $name );
    }
    $output = ob_get_clean();
	
	$output = preg_replace('/<div\s+class="textwidget">/', '', $output);
    $output = preg_replace('/<\/div>/', '', $output);
	echo $output;
}

?>
    <div id="cookie-agreement-banner" class="privacy-cookies cookie-agreement-banner--hidden">
      <div class="privacy-cookies__container">
        <div class="privacy-cookies__content">
		  <img fetchpriority="high" class="privacy-cookies__cookie-picture" src="<?php echo esc_url( home_url( '/wp-content/themes/astrum-theme2/new_design_assets/img/cookie.svg' ) ); ?>" alt="cookie" height="32" width="32"/>
          <p class="text-second"><span class="privacy-cookies__enhance">We use cookies to enhance your experience on this website.</span> <a class="link link--weight-lite privacy-cookies__learn-more" href="<?php echo esc_url( home_url( '/privacy-policy/' ) ); ?>" target="_blank">Learn more</a></p>
        </div>
        <button id="confirm-cookie-button" type="button" class="privacy-cookies__agree btn btn--orange" aria-label="I agree">
          <span class="headline-four">I agree</span>
        </button>
      </div>
    </div>

    <footer class="footer">
      <?php if ( is_front_page() ) { ?><div class="footer__notification">
        <div class="container">
          <div class="footer__notification-content">
            <p class="text-second text-second--dark">
              <a class="link link--mobile-weight-lite" target="_blank" href="https://www.xml2selenium.com/">XML2Selenium</a> – is an extensible, plug-in based
              platform for creation and control of the automated tests on the basis of Java technology.
            </p>
          </div>
        </div>
      </div><?php } ?>
      <div class="container">
        <div class="footer__navigation">
          <div class="footer__company_info">
            <a class="footer__logo-container" href="<?=esc_url(home_url( '/' ))?>">
              <img loading="lazy" class="footer__logo" src="<?=get_template_directory_uri().'/new_design_assets/img/logo/company-logo.svg' ?>"  alt="JazzTeam"/>
            </a>
            <div class="footer__socials"><?php load_footer_widget('nd-footer-socials'); ?></div>
            <div class="footer__get-in-touch">
              <span class="headline-four">Get in touch</span>
              <div class="footer__contacts"><?php load_footer_widget('nd-footer-0'); ?></div>
            </div>
          </div>
          <nav class="footer__compact-nav">
 			<ul class="compact-nav"><?php load_footer_widget('nd-footer-1'); ?></ul>
            <ul class="compact-nav"><?php load_footer_widget('nd-footer-2'); ?></ul>
			<ul class="compact-nav compact-nav--tablet-row"><?php load_footer_widget('nd-footer-3'); ?></ul>
          </nav>
        </div>
      </div>
      <div class="container">
        <div class="footer__rights">
          <p class="footer__company-rights">
            © 2011-<?=date('Y')?> Agile Java Development Company JazzTeam. All Rights Reserved.
            <a class="link footer__rights-link" href="https://jazzteam.org/privacy-policy/">Privacy Policy</a>
          </p>
        </div>
      </div>
    </footer>

<?php
// 	if ( !str_contains($_SERVER['REQUEST_URI'], '/main-page-new-design') ) 
	wp_footer();
// } ?>
	
<script>
  document.addEventListener(
    'wpcf7mailsent',
    function(event) {
      yaCounter24294061.reachGoal('formsEN');
    },
    false);
</script>
			
<script>!function(){document.jivositeloaded=0;var e=document,t=window;function o(){var t=e.createElement("script");t.type="text/javascript",t.async=!0,t.src="//code.jivosite.com/script/widget/lvL0NRIruq";var o=document.getElementsByTagName("script")[0];o.parentNode.insertBefore(t,o)}function a(){t.detachEvent?(t.detachEvent("onscroll",a),t.detachEvent("onmousemove",a),t.detachEvent("ontouchmove",a),t.detachEvent("onresize",a)):(t.removeEventListener("scroll",a,!1),t.removeEventListener("mousemove",a,!1),t.removeEventListener("touchmove",a,!1),t.removeEventListener("resize",a,!1)),"complete"==e.readyState?o():t.attachEvent?t.attachEvent("onload",o):t.addEventListener("load",o,!1);var n=new Date;n.setTime(n.getTime()+1008e5),e.cookie="JivoSiteLoaded=1;path=/;expires="+n.toGMTString()}0>e.cookie.search("JivoSiteLoaded")?t.attachEvent?(t.attachEvent("onscroll",a),t.attachEvent("onmousemove",a),t.attachEvent("ontouchmove",a),t.attachEvent("onresize",a)):(t.addEventListener("scroll",a,{capture:!1,passive:!0}),t.addEventListener("mousemove",a,{capture:!1,passive:!0}),t.addEventListener("touchmove",a,{capture:!1,passive:!0}),t.addEventListener("resize",a,{capture:!1,passive:!0})):a()}();

function loadYoutubeApi(){let e=document.createElement("script");e.type="text/javascript",e.src="https://www.youtube.com/iframe_api",e.async=!0,window.removeEventListener("scroll",loadYoutubeApi,!1),window.removeEventListener("mousemove",loadYoutubeApi,!1),window.removeEventListener("touchmove",loadYoutubeApi,!1),window.removeEventListener("resize",loadYoutubeApi,!1);let t=document.getElementsByTagName("script")[0];t.parentNode.insertBefore(e,t)}function attachEvents(){document.querySelector("div[data-video]")&&(window.addEventListener("scroll",loadYoutubeApi,{capture:!1,passive:!0}),window.addEventListener("mousemove",loadYoutubeApi,{capture:!1,passive:!0}),window.addEventListener("touchmove",loadYoutubeApi,{capture:!1,passive:!0}),window.addEventListener("resize",loadYoutubeApi,{capture:!1,passive:!0}))}attachEvents();
</script>

</body>
</html>