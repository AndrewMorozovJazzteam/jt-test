<?php
/** A simple text block **/
class PP_Notice_Block extends AQ_Block {

    //set and create block
    function __construct() {
        $block_options = array(
            'name' => 'Notice box',
            'size' => 'span4',
            'last' => ''
            );

        //create the block
        parent::__construct('pp_notice_block', $block_options);
    }

    function form($instance) {

        $defaults = array(
            'title' => '',
            'icon' => '',
            'content' => '',
            'link' => '',
            'wp_autop' => 1
            );
        $instance = wp_parse_args($instance, $defaults);
        extract($instance);

        ?>
        <p class="description">
            <label for="<?php echo $this->get_field_id('title') ?>">
                Headline
                <?php echo aq_field_input('title', $block_id, $title, $size = 'full') ?>
            </label>
        </p>
        <p class="tab-desc description">
            <label for="<?php echo $this->get_field_id('boxes') ?>-icon">
                Box Icon <br/>
                <?php global $fontawesome; ?>
                <?php echo aq_field_select('icon', $block_id, $fontawesome, $icon) ?>

            </label>
        </p>
        <p class="description">
            <label for="<?php echo $this->get_field_id('text') ?>">
                Content
                <?php echo aq_field_textarea('text', $block_id, $text, $size = 'full') ?>
            </label>
            <label for="<?php echo $this->get_field_id('wp_autop') ?>">
                <?php echo aq_field_checkbox('wp_autop', $block_id, $wp_autop) ?>
                Do not create the paragraphs automatically. <code>"wpautop"</code> disable.
            </label>
        </p>
        <p class="description">
            <label for="<?php echo $this->get_field_id('link') ?>">
                URL
                <?php echo aq_field_input('link', $block_id, $link, $size = 'full') ?>
            </label>
        </p>
        <?php
    }

    function block($instance) {
        extract($instance);
        if($link) { echo'<a href="'.$link.'">'; }
        echo '<div class="notice-box"><h3>'.$title.'</h3>';
        if($icon) { echo '<i class="'.$icon.'"></i>';}
        if($wp_autop == 1){
            echo do_shortcode(htmlspecialchars_decode($text));
        }
        else
        {
            echo wpautop(do_shortcode(htmlspecialchars_decode($text)));
        }
        echo '</div>';
        if($link) { echo '</a>'; }


    }

}
