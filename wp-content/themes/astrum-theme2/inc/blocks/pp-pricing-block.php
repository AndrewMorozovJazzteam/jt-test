<?php
/** A simple text block **/
class PP_Pricing_Block extends AQ_Block {

    //set and create block
    function __construct() {
        $block_options = array(
            'name' => 'Pricing table',
            'size' => 'span4',
            'last' => ''
            );

        //create the block
        parent::__construct('pp_pricing_block', $block_options);
    }

    function form($instance) {

        $defaults = array(
            "title" => '',
            "type" => '',
            "currency" => '$',
            "price" => '',
            "per" => '',
            "buttonstyle" => '',
            "buttonlink" => '',
            "buttontext" => 'Sign Up',
            "text" => '',
            'wp_autop' => 0
            );
        $instance = wp_parse_args($instance, $defaults);
        extract($instance);

        ?>
        <p class="description half">
            <label for="<?php echo $this->get_field_id('title') ?>">
                Title
                <?php echo aq_field_input('title', $block_id, $title, $size = 'full') ?>
            </label>
        </p>
        <p class="description half last">
            <label for="<?php echo $this->get_field_id('boxes') ?>-type">
                Type <br/>
                <?php
                $type_options = array(
                'featured' => 'Featured' ,
                'premium' => 'Premium' ,
                'standard' => 'Standard'
                );
            echo aq_field_select('type', $block_id, $type_options, $type) ?>
            </label>
        </p>
        <p class="description half">
            <label for="<?php echo $this->get_field_id('currency') ?>">
                Currency
                <?php echo aq_field_input('currency', $block_id, $currency, $size = 'full') ?>
            </label>
        </p>
        <p class="description half last">
            <label for="<?php echo $this->get_field_id('price') ?>">
                Price
                <?php echo aq_field_input('price', $block_id, $price, $size = 'full') ?>
            </label>
        </p>
        <p class="description half">
            <label for="<?php echo $this->get_field_id('per') ?>">
                "Per"
                <?php echo aq_field_input('per', $block_id, $per, $size = 'full') ?>
            </label>
        </p>

        <p class="description half last ">
            <label for="<?php echo $this->get_field_id('buttonstyle') ?>">
                "Sing up" style
               <?php
                $style_options = array(
                'light' => 'Light' ,
                'color' => 'Color'
                );
            echo aq_field_select('buttonstyle', $block_id, $style_options, $buttonstyle); ?>
            </label>
        </p>
        <p class="description half ">
            <label for="<?php echo $this->get_field_id('buttonlink') ?>">
                "Sing up" button URL
                <?php echo aq_field_input('buttonlink', $block_id, $buttonlink, $size = 'full'); ?>
            </label>
        </p>
        <p class="description half last">
            <label for="<?php echo $this->get_field_id('buttontext') ?>">
                "Sing up" text
                <?php echo aq_field_input('buttontext', $block_id, $buttontext, $size = 'full') ?>
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

            <?php
        }

        function block($instance) {
            extract($instance);

            echo '<div class="'.$type.' plan ">
            <h3>'.$title.'</h3>
            <div class="plan-price">
            <span class="plan-currency">'.$currency.'</span>
            <span class="value">'.$price.'</span>
            <span class="period">'.$per.'</span>
            </div>
            <div class="plan-features">';
            if($wp_autop == 1){
                echo do_shortcode(htmlspecialchars_decode($text));
            }
            else
            {
                echo wpautop(do_shortcode(htmlspecialchars_decode($text)));
            }
            if($buttonlink) { echo ' <a class="button '.$buttonstyle.'" href="'.$buttonlink.'">'.$buttontext.'</a>'; }
            echo ' </div>
            </div>';





        }

    }
