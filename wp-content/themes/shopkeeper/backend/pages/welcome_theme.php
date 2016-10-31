<div class="wrap about-wrap getbowtied-about-wrap getbowtied-welcome-wrap">
	
    <?php require_once('global/pages-header.php'); ?>

    <br /><br />

    <h2 class="welcome_title">Congratulations! <br />You've got excellent taste in WordPress themes!</h2>

    <p class="welcome_info">
        Thank you for choosing <strong><?php echo getbowtied_theme_name(); ?></strong>! Whether you're a business owner or a developer building sites for your clients, we're hoping this theme will help you reach your goals. Start with the (1) <strong><a href="<?php echo admin_url( 'admin.php?page=' . $registration_page ); ?>">Product Activation</a></strong> to enable automatic updates and support, (2) <strong><a href="<?php echo admin_url( 'admin.php?page=' . $plugins_page ); ?>">install the plugins</a></strong> coming with the theme then (3) <strong><a href="<?php echo admin_url( 'admin.php?page=' . $demos_page ); ?>">import the demo content</a></strong> (*optional).
    </p>

	<div class="getbowtied-welcome">
        <div class="theme-browser rendered">
            
            <div class="theme">                

                <h3 class="welcome-item-title"><a href="<?php echo $getbowtied_settings['theme_docs'] ?>" target="_blank"><span class="dashicons dashicons-book-alt"></span><br />Theme's Documentation</a></h3>

                <div class="welcome-item-content">

                    <p>Everything you need to know about the theme, from installation and setup to customization.</p>

                    <a href="<?php echo $getbowtied_settings['theme_docs']; ?>" target="_blank" class="button button-primary">Theme's Documentation</a>

                </div>

            </div>

            <div class="theme">

                <h3 class="welcome-item-title"><a href="<?php echo $woocommerce_docs; ?>" target="_blank"><span class="dashicons dashicons-store"></span><br />WooCommerce for Beginners</a></h3>

                <div class="welcome-item-content">

                    <p>A step-by-step video guide on how to set up your first online store. From adding products to collecting payments.</p>

                    <a href="<?php echo $getbowtied_settings['woocommerce_docs']; ?>" target="_blank" class="button button-primary">WooCommerce for Beginners</a>

                </div>

            </div>

            <div class="theme">

                <h3 class="welcome-item-title"><a href="<?php echo $getbowtied_settings['wordpress_docs']; ?>" target="_blank"><span class="dashicons dashicons-wordpress-alt"></span><br />WordPress for absolute Beginners</a></h3>

                <div class="welcome-item-content">

                    <p>New to WordPress, no worries! There's a step-by-step video guide to help absolute beginners get started.</p>

                    <a href="<?php echo $getbowtied_settings['wordpress_docs'];; ?>" target="_blank" class="button button-primary">WordPress for Beginners</a>

                </div>

            </div>

            <div style="clear:both"></div>

        </div>
    </div>

    <br /><br /><br />

    <hr />

    <br />

    <div class="getbowtied_footer">Thank you for choosing <strong><?php echo getbowtied_theme_name(); ?></strong>!</div>

    <br /><br /><br /><br /><br /><br />

</div>