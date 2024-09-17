<?php
/** A simple text block **/
class PP_Image_Block extends AQ_Block {

    //set and create block
    function __construct() {
        $block_options = array(
            'name' => 'Image block',
            'size' => 'span4',
            'last' => ''
            );

        //create the block
        parent::__construct('pp_image_block', $block_options);
    }

    function form($instance) {

        $defaults = array(
            'caption' => '',
            'upload' => '',
            'url' => ''
            );
        $instance = wp_parse_args($instance, $defaults);
        extract($instance);

        ?>
        <div class="description half">
            <label for="<?php echo $this->get_field_id('caption') ?>">
                Caption (optional)<br/>
                <?php echo aq_field_input('caption', $block_id, $caption) ?>
            </label>
        </div>
        <div class="description half last">
            <label for="<?php echo $this->get_field_id('url') ?>">
                URL (optional)<br/>
                <?php echo aq_field_input('url', $block_id, $url) ?>
            </label>
        </div>
        <div class="description half ">
            <label for="<?php echo $this->get_field_id('upload') ?>">
                Upload an image<br/>
                <?php echo aq_field_upload('upload', $block_id, $upload) ?>
            </label>
            <?php if($upload) { ?>
            <div class="screenshot">
                <img src="<?php echo $upload ?>" />
            </div>
            <?php } ?>
        </div>
        <?php
    }

    function block($instance) {
        extract($instance);
        if($caption) { echo'<div class="wp-caption  alignnone">'; }
        if($url) { echo'<a href="'.$url.'">'; }
        if($upload) { echo'<img class="size-full aqua-image-block"  src="'.$upload.'" >'; }
        if($url) { echo'</a>'; }
        if($caption) { echo'<p class="wp-caption-text">'.$caption.'</p></div>'; }
    }

}
