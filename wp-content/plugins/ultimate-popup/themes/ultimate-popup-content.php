<?php


    $global_popup_id = ultimate_popup_get_option( 'global_popup_id' );
    
    $popup_posts = query_posts('p='.$global_popup_id.'&post_type=ultimate-popup&posts_per_page=1');
    global $post;
    
?> 

<?php foreach($popup_posts as $post) : ?>
<?php setup_postdata($post); ?>

<?php 
    $prefix = '_ultimate_popup_';
    
    $ppm_popup_title= get_post_meta($post->ID, $prefix . 'title', true); 
    $ppm_popup_desc= get_post_meta($post->ID, $prefix . 'description', true); 
    $ppm_form_code= get_post_meta($post->ID, $prefix . 'code', true); 
    $ppm_popup_width= get_post_meta($post->ID, $prefix . 'width', true); 
    $ppm_popup_border_width= get_post_meta($post->ID, $prefix . 'border_width', true); 
    $ppm_popup_theme= get_post_meta($post->ID, $prefix . 'theme', true); 
    $ppm_popup_left_content= get_post_meta($post->ID, $prefix . 'left_content', true); 
    $ppm_popup_theme_color= get_post_meta($post->ID, $prefix . 'theme_color', true); 
    $enable_cross_button= get_post_meta($post->ID, $prefix . 'cross_btn', true); 
    $popup_show_on= get_post_meta($post->ID, $prefix . 'show_on', true); 
    $when_popup_load= get_post_meta($post->ID, $prefix . 'when', true); 
    $popup_enable_cookie= get_post_meta($post->ID, $prefix . 'cookie', true); 
    $ppm_popup_timeunit= get_post_meta($post->ID, $prefix . 'time_unit', true); 
    $ppm_popup_timevalue= get_post_meta($post->ID, $prefix . 'time_value', true); 
    $ppm_popup_position= get_post_meta($post->ID, $prefix . 'position', true); 
?>



<div id="ultimate-popup-<?php the_ID(); ?>" class="ultimate-popup-wrapper ultimate-popup-theme-id-<?php echo $ppm_popup_theme; ?> <?php if($popup_show_on == '1') : ?>ultimate-popup-halfway-scroll-activate<?php elseif($popup_show_on == '2') : ?>ultimate-popup-automatic-activate<?php endif; ?>">
   
    <?php if($enable_cross_button == '1') : ?>
        <span class="cross-btn-ppm"><?php if($ppm_popup_theme == '6') : ?>close<?php else : ?>x<?php endif; ?></span>
    <?php endif; ?>    
       
    <?php if($ppm_popup_theme == '3' OR $ppm_popup_theme == '4') : ?>
        <div class="ultimate-popup-inner">
            <div class="pop-inner-column">
              
                <div class="popup-inner-col-thumb">
                <?php if( has_post_thumbnail() ) : ?>
                    <?php the_post_thumbnail('full'); ?>
                <?php else : ?>
                    <img src="<?php echo plugin_dir_url( __FILE__ ); ?>img/message_icon_<?php if($ppm_popup_theme == '4') : ?>1<?php else : ?>2<?php endif; ?>.png" alt="">
                <?php endif; ?>
                </div>
                
                <div class="popup-inner-col-cont">
                    <?php if($ppm_popup_title) : ?>
                    <h2 class="ultimate-popup-inner-title"><?php echo $ppm_popup_title; ?></h2>
                    <?php endif; ?>

                    <?php if($ppm_popup_desc) : ?>
                    <p class="ultimate-popup-inner-description"><?php echo $ppm_popup_desc; ?></p>
                    <?php endif; ?>                    
                    <div class="ultimate-popup-shortcodes">
                        <?php echo do_shortcode( $ppm_form_code ); ?>
                    </div>                    
                </div>
            </div>
        </div> 
    <?php elseif($ppm_popup_theme == '5') : ?>   
        <div class="ultimate-popup-inner">
            <div class="pop-inner-column <?php if( ! has_post_thumbnail() ) : ?>no-thumb-ppm-attached<?php endif; ?>">
              
               
                <div class="popup-inner-col-thumb">
                    <?php the_post_thumbnail('full'); ?>
                    
                    <?php if($ppm_popup_title) : ?>
                    <h2 class="ultimate-popup-inner-title"><?php echo $ppm_popup_title; ?></h2>
                    <?php endif; ?>

                    <?php if($ppm_popup_desc) : ?>
                    <p class="ultimate-popup-inner-description"><?php echo $ppm_popup_desc; ?></p>
                    <?php endif; ?>                      
                </div>
                
                <div class="popup-inner-col-cont">
                    <div class="ultimate-popup-shortcodes">
                        <?php echo do_shortcode( $ppm_form_code ); ?>
                    </div>                    
                </div>
            </div>
        </div>  
                     
    <?php elseif($ppm_popup_theme == '9') : ?>   
               
        <div class="ultimate-popup-inner">
            <div class="ultimate-popup-theme-8-column">
                <div class="ultimate-popup-theme-8-left">
                    <?php echo $ppm_popup_left_content; ?>
                </div>
                
                <div class="ultimate-popup-theme-8-right">
                    <?php if($ppm_popup_title) : ?>
                    <h2 class="ultimate-popup-inner-title"><?php echo $ppm_popup_title; ?></h2>
                    <?php endif; ?>

                    <?php if($ppm_popup_desc) : ?>
                    <p class="ultimate-popup-inner-description"><?php echo $ppm_popup_desc; ?></p>
                    <?php endif; ?>

                    <div class="ultimate-popup-shortcodes">
                        <?php echo do_shortcode( $ppm_form_code ); ?>
                    </div>                    
                </div>
            </div>

        </div>    
    <?php else : ?>
       
        <?php if( $ppm_popup_theme == '6' && has_post_thumbnail() ) : ?>
            <div style="background-image:url(<?php $popup_inner_bg = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' ); echo $popup_inner_bg[0]; ?>)" class="ultimate-popup-inner-bg"></div>
        <?php endif; ?>
        
        <div class="ultimate-popup-inner">
            <?php if($ppm_popup_title) : ?>
            <h2 class="ultimate-popup-inner-title"><?php echo $ppm_popup_title; ?></h2>
            <?php endif; ?>

            <?php if($ppm_popup_desc) : ?>
            <p class="ultimate-popup-inner-description"><?php echo $ppm_popup_desc; ?></p>
            <?php endif; ?>

            <div class="ultimate-popup-shortcodes">
                <?php echo do_shortcode( $ppm_form_code ); ?>
            </div>
        </div>    
    <?php endif; ?>
</div>


<style>
    
    #ultimate-popup-<?php the_ID(); ?> {width: <?php echo $ppm_popup_width; ?>; border-width:<?php echo $ppm_popup_border_width; ?>;border-color:<?php echo $ppm_popup_theme_color; ?>}
    <?php if($ppm_popup_theme == '9' OR $ppm_popup_theme == '8') : ?>
    
    <?php else : ?>
    #ultimate-popup-<?php the_ID(); ?> .ultimate-popup-shortcodes input[type="submit"] {background:<?php echo $ppm_popup_theme_color; ?>}
    <?php endif; ?>
    <?php if($ppm_popup_theme == '2') : ?>
    #ultimate-popup-<?php the_ID(); ?> .cross-btn-ppm {background-color:<?php echo $ppm_popup_theme_color; ?>;color:#fff}
    <?php elseif($ppm_popup_theme == '3') : ?>
    #ultimate-popup-<?php the_ID(); ?> .cross-btn-ppm:hover {background-color:<?php echo $ppm_popup_theme_color; ?>;color:#fff}
    #ultimate-popup-<?php the_ID(); ?> .ultimate-popup-inner {border-color:<?php echo $ppm_popup_theme_color; ?>}
    <?php elseif($ppm_popup_theme == '4') : ?>
    #ultimate-popup-<?php the_ID(); ?> .cross-btn-ppm:hover, #ultimate-popup-<?php the_ID(); ?> .popup-inner-col-thumb {background-color:<?php echo $ppm_popup_theme_color; ?>;color:#fff}
    #ultimate-popup-<?php the_ID(); ?> .ultimate-popup-inner {border:none}
    <?php elseif($ppm_popup_theme == '5') : ?>
    #ultimate-popup-<?php the_ID(); ?> .cross-btn-ppm {color:#fff}
    #ultimate-popup-<?php the_ID(); ?> .popup-inner-col-thumb:after {background-color:<?php echo $ppm_popup_theme_color; ?>}
    <?php elseif($ppm_popup_theme == '7') : ?>
    #ultimate-popup-<?php the_ID(); ?> .cross-btn-ppm {background-color:<?php echo $ppm_popup_theme_color; ?>;color:#fff}
    #ultimate-popup-<?php the_ID(); ?>:before, #ultimate-popup-<?php the_ID(); ?>:after {background-color:<?php echo $ppm_popup_theme_color; ?>}
    #ultimate-popup-<?php the_ID(); ?> .cross-btn-ppm:hover {background-color: #666;color: #fff}
    <?php elseif($ppm_popup_theme == '8') : ?>
    #ultimate-popup-<?php the_ID(); ?> {background-color:<?php echo $ppm_popup_theme_color; ?>}
    #ultimate-popup-<?php the_ID(); ?> .cross-btn-ppm {color:#fff}
    #ultimate-popup-<?php the_ID(); ?> .cross-btn-ppm:hover {background-color:<?php echo $ppm_popup_theme_color; ?>}
    <?php elseif($ppm_popup_theme == '9') : ?>
    #ultimate-popup-<?php the_ID(); ?> .ultimate-popup-theme-8-right {background-color:<?php echo $ppm_popup_theme_color; ?>}
    #ultimate-popup-<?php the_ID(); ?> .ultimate-popup-theme-8-left h3 {color:<?php echo $ppm_popup_theme_color; ?>}
    #ultimate-popup-<?php the_ID(); ?> .cross-btn-ppm {color:#fff}
    <?php else : ?>
    #ultimate-popup-<?php the_ID(); ?> .cross-btn-ppm {color:<?php echo $ppm_popup_theme_color; ?>}
    <?php endif; ?>
    <?php if($popup_show_on == '1') : ?>
        <?php if($ppm_popup_position == '2') : ?>
            #ultimate-popup-<?php the_ID(); ?> {left: 0;bottom: -100%;}
            #ultimate-popup-<?php the_ID(); ?>.ultimate-popup-wrapper-activate {bottom: 0;} 
        <?php else : ?>    
            #ultimate-popup-<?php the_ID(); ?> {right: 0;bottom: -100%;}
            #ultimate-popup-<?php the_ID(); ?>.ultimate-popup-wrapper-activate {bottom: 0;}
        <?php endif; ?>   
    
    <?php endif; ?>
</style>


<script type="text/javascript">
//<![CDATA[  
    
    
    
<?php if($when_popup_load == '2') : ?>
    jQuery(document).ready(function(){    
<?php else : ?>
    jQuery(window).load(function(){   
<?php endif; ?>
        
      

    <?php if($popup_enable_cookie == '2') : ?>
    
        <?php if($popup_show_on == '2') : ?>
            jQuery("#ultimate-popup-<?php the_ID(); ?>").easyModal({
                autoOpen: true,
                closeButtonClass: '.cross-btn-ppm'
            });

            jQuery('#ultimate-popup-<?php the_ID(); ?>').addClass('ultimate-popup-wrapper-activate');

        <?php else : ?>

            // Popup is showing first time!
            jQuery(window).scroll(function () { 
              if (jQuery(window).scrollTop() > jQuery('body').height() / 2) {
                jQuery('#ultimate-popup-<?php the_ID(); ?>').addClass('ultimate-popup-wrapper-activate');
              } 
            });  

        <?php endif; ?>      
    
    <?php else : ?>
    
    <?php if($popup_show_on == '4') : ?>
    jQuery('body').mouseleave(function() {    
    <?php endif; ?>

    if (jQuery.cookie('popupTemporaryCookie<?php the_ID(); ?>')) {

        // Popup is hiding after showing first time!
        jQuery("#ultimate-popup-<?php the_ID(); ?>").hide();

    } else if (jQuery.cookie('popupLongerCookie<?php the_id(); ?>')) {
        
    <?php if($popup_show_on == '2') : ?>
        jQuery("#ultimate-popup-<?php the_ID(); ?>").easyModal({
            autoOpen: true,
            closeButtonClass: '.cross-btn-ppm'
        });
    
        jQuery('#ultimate-popup-<?php the_ID(); ?>').addClass('ultimate-popup-wrapper-activate');
        
    <?php elseif($popup_show_on == '3') : ?>
        jQuery('body').mouseleave(function() { 
        
            jQuery("#ultimate-popup-<?php the_ID(); ?>").easyModal({
                autoOpen: true,
                closeButtonClass: '.cross-btn-ppm',
                overlayOpacity:0
            });
            
            jQuery(".cross-btn-ppm, .lean-overlay").click(function(){
                jQuery('body').addClass('ultimate-popup-wrapper-hide');
            });

            jQuery('#ultimate-popup-<?php the_ID(); ?>').addClass('ultimate-popup-wrapper-activate');
        });        
        
    <?php else : ?>
        
        // Popup is showing again!
        jQuery(window).scroll(function () { 
          if (jQuery(window).scrollTop() > jQuery('body').height() / 2) {
            jQuery('#ultimate-popup-<?php the_ID(); ?>').addClass('ultimate-popup-wrapper-activate');
          } 
        });
        
    <?php endif; ?>        



    } else {
        
    <?php if($popup_show_on == '2') : ?>
        jQuery("#ultimate-popup-<?php the_ID(); ?>").easyModal({
            autoOpen: true,
            closeButtonClass: '.cross-btn-ppm'
        });
    
        jQuery('#ultimate-popup-<?php the_ID(); ?>').addClass('ultimate-popup-wrapper-activate');
        
    <?php elseif($popup_show_on == '3') : ?>
        
        jQuery('body').mouseleave(function() { 
            jQuery("#ultimate-popup-<?php the_ID(); ?>").easyModal({
                autoOpen: true,
                closeButtonClass: '.cross-btn-ppm',
                overlayOpacity:0
            });
            
            jQuery(".cross-btn-ppm, .lean-overlay").click(function(){
                jQuery('body').addClass('ultimate-popup-wrapper-hide');
            });            

            jQuery('#ultimate-popup-<?php the_ID(); ?>').addClass('ultimate-popup-wrapper-activate');
       });    
        
    <?php else : ?>
        
        // Popup is showing first time!
        jQuery(window).scroll(function () { 
          if (jQuery(window).scrollTop() > jQuery('body').height() / 2) {
            jQuery('#ultimate-popup-<?php the_ID(); ?>').addClass('ultimate-popup-wrapper-activate');
          } 
        });  
        
    <?php endif; ?>       

    }
        
      

    var expiresAt = new Date();
    
    <?php if($ppm_popup_timeunit == '1') : ?>
    expiresAt.setTime(expiresAt.getTime() + <?php echo $ppm_popup_timevalue; ?>*24*60*60*1000); // Days               
    <?php elseif($ppm_popup_timeunit == '2') : ?>
    expiresAt.setTime(expiresAt.getTime() + <?php echo $ppm_popup_timevalue; ?>*60*60*1000); // Hours  
    <?php else : ?>
    expiresAt.setTime(expiresAt.getTime() + <?php echo $ppm_popup_timevalue; ?>*60*1000); // Minutes  
    <?php endif; ?>


    jQuery.cookie('popupLongerCookie<?php the_id(); ?>', new Date());
    jQuery.cookie('popupTemporaryCookie<?php the_ID(); ?>', true, { expires: expiresAt });  
    
    <?php endif; ?>
        
    <?php if($popup_show_on == '4') : ?>
    });
    <?php endif; ?>          

});

//]]>
</script>

<?php endforeach; ?>  