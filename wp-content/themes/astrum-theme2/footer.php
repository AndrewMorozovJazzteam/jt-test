<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the id=main div and all content after
 *
 * @package Nevia
 * @since Nevia 1.0
 */
?>
</div>
<!-- Content Wrapper / End -->
<?php $style = get_theme_mod( 'astrum_footer_style', 'light' ); ?>
<!-- Footer
================================================== -->
<div id="footer" class="<?php echo $style; ?>">
    <!-- 960 Container -->
    <div class="container">

        <div class="four columns">
             <?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('Footer 1st Column')) : endif; ?>
        </div>

        <div class="four columns">
            <?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('Footer 2nd Column')) : endif; ?>
        </div>


        <div class="four columns">
            <?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('Footer 3rd Column')) : endif; ?>
        </div>

        <div class="four columns">
            <?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('Footer 4th Column')) : endif; ?>
        </div>
    </div>
    <!-- Container / End -->

</div>
<!-- Footer / End -->

<!-- Footer Bottom / Start -->
<div id="footer-bottom" class="<?php echo $style; ?>">

    <!-- Container -->
    <div class="container">
        <div class="twelve columns">
            <?php $copyrights = ot_get_option('pp_copyrights' );
                if (function_exists('icl_register_string')) {
                    icl_register_string('Copyrights in footer','copyfooter', $copyrights);
                    echo icl_t('Copyrights in footer','copyfooter', $copyrights);
                } else {
                  echo $copyrights;
                } ?>
        </div>
        <div class="eight columns">
                <?php /* get the slider array */
                $footericons = ot_get_option( 'pp_headericons', array() );
                if ( !empty( $footericons ) ) {
                    echo '<ul class="social-icons-footer">';
                    foreach( $footericons as $icon ) {
                        echo '<li><a class="tooltip top" title="' . $icon['title'] . '" href="' . $icon['icons_url'] . '"><i class="icon-' . $icon['icons_service'] . '"></i></a></li>';
                    }
                    echo '</ul>';
                }
            ?>
        </div>
    </div>
    <!-- Container / End -->
</div>
<!-- Footer Bottom / Start -->


<div class="jt_icons_messangers_kit jt_icons_messangers_kit_size_32 jt_icons_messangers_floating_style jt_icons_messangers_vertical_style" style="right: 0px; top: 300px; background-color: transparent; line-height: 32px;">
	<a class="jt_icons_messangers_button_telegram" href="https://t.me/JazzTeam_org" title="Telegram" rel="nofollow noopener" target="_blank" data-wpel-link="external">
		<span class="jt_icons_messangers_svg jt_icons_messangers_s__default jt_icons_messangers_s_telegram" style="background-color: rgb(44, 165, 224);">
			<svg focusable="false" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32">
				<path fill="#FFF" d="M25.515 6.896 6.027 14.41c-1.33.534-1.322 1.276-.243 1.606l5 1.56 1.72 5.66c.226.625.115.873.77.873.506 0 .73-.235 1.012-.51l2.43-2.363 5.056 3.734c.93.514 1.602.25 1.834-.863l3.32-15.638c.338-1.363-.52-1.98-1.41-1.577z"></path>
			</svg>
		</span>
		<span class="jt_icons_messangers_label">Telegram</span>
	</a>
	<a class="jt_icons_messangers_button_whatsapp" href="https://wa.me/375333225187" title="WhatsApp" rel="nofollow noopener" target="_blank" data-wpel-link="external" style="display:none;">
		<span class="jt_icons_messangers_svg jt_icons_messangers_s__default jt_icons_messangers_s_whatsapp" style="background-color: rgb(18, 175, 10);">
			<svg focusable="false" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32">
				<path fill-rule="evenodd" clip-rule="evenodd" fill="#FFF" d="M16.21 4.41C9.973 4.41 4.917 9.465 4.917 15.7c0 2.134.592 4.13 1.62 5.832L4.5 27.59l6.25-2.002a11.241 11.241 0 0 0 5.46 1.404c6.234 0 11.29-5.055 11.29-11.29 0-6.237-5.056-11.292-11.29-11.292zm0 20.69c-1.91 0-3.69-.57-5.173-1.553l-3.61 1.156 1.173-3.49a9.345 9.345 0 0 1-1.79-5.512c0-5.18 4.217-9.4 9.4-9.4 5.183 0 9.397 4.22 9.397 9.4 0 5.188-4.214 9.4-9.398 9.4zm5.293-6.832c-.284-.155-1.673-.906-1.934-1.012-.265-.106-.455-.16-.658.12s-.78.91-.954 1.096c-.176.186-.345.203-.628.048-.282-.154-1.2-.494-2.264-1.517-.83-.795-1.373-1.76-1.53-2.055-.158-.295 0-.445.15-.584.134-.124.3-.326.45-.488.15-.163.203-.28.306-.47.104-.19.06-.36-.005-.506-.066-.147-.59-1.587-.81-2.173-.218-.586-.46-.498-.63-.505-.168-.007-.358-.038-.55-.045-.19-.007-.51.054-.78.332-.277.274-1.05.943-1.1 2.362-.055 1.418.926 2.826 1.064 3.023.137.2 1.874 3.272 4.76 4.537 2.888 1.264 2.9.878 3.43.85.53-.027 1.734-.633 2-1.297.266-.664.287-1.24.22-1.363-.07-.123-.26-.203-.54-.357z"></path>
			</svg>
		</span>
		<span class="jt_icons_messangers_label">WhatsApp</span>
	</a>
	<a class="jt_icons_messangers_button_facebook" href="https://www.facebook.com/jazzteam.org" title="Facebook" rel="nofollow noopener" target="_blank" data-wpel-link="external">
		<span class="jt_icons_messangers_svg jt_icons_messangers_s__default jt_icons_messangers_s_facebook" style="background-color: rgb(24, 119, 242);">
			<svg focusable="false" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32">
				<path fill="#FFF" d="M17.78 27.5V17.008h3.522l.527-4.09h-4.05v-2.61c0-1.182.33-1.99 2.023-1.99h2.166V4.66c-.375-.05-1.66-.16-3.155-.16-3.123 0-5.26 1.905-5.26 5.405v3.016h-3.53v4.09h3.53V27.5h4.223z"></path>
			</svg>
		</span>
		<span class="jt_icons_messangers_label">Facebook</span>
	</a>
	<a class="jt_icons_messangers_button_linkedin" href="http://www.linkedin.com/company/jazzteam" title="LinkedIn" rel="nofollow noopener" target="_blank" data-wpel-link="external">
		<span class="jt_icons_messangers_svg jt_icons_messangers_s__default jt_icons_messangers_s_linkedin" style="background-color: rgb(0, 123, 181);">
			<svg focusable="false" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32">
				<path d="M6.227 12.61h4.19v13.48h-4.19V12.61zm2.095-6.7a2.43 2.43 0 0 1 0 4.86c-1.344 0-2.428-1.09-2.428-2.43s1.084-2.43 2.428-2.43m4.72 6.7h4.02v1.84h.058c.56-1.058 1.927-2.176 3.965-2.176 4.238 0 5.02 2.792 5.02 6.42v7.395h-4.183v-6.56c0-1.564-.03-3.574-2.178-3.574-2.18 0-2.514 1.7-2.514 3.46v6.668h-4.187V12.61z" fill="#FFF"></path>
			</svg>
		</span>
		<span class="jt_icons_messangers_label">LinkedIn</span>
	</a>
</div>
<div class="jt-menu-background-overlay"></div>
<?php wp_footer(); ?>
	
<script type="text/javascript">
  document.addEventListener(
    'wpcf7mailsent',
    function(event) {
      yaCounter24294061.reachGoal('formsEN');
    },
    false);
</script>
			

</body>
</html>