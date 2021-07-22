<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

function cs_plus_admin_welcome_page(){
    $plugin_name    = 'PLUS Admin';
    $plugin_uri     = 'plus-admin';
	$plugin_version = PLUS_ADMIN_VERSION;


	$welcome_msg 	= esc_attr__("First off all, thanks for considering our work, we love you! \n We made this plugin with all our heart, taking care of every small element, motion, interaction and customizing capabilities, so you can entirely focus on creating great things. We sincerely hope you'll enjoy it the same as we do!",'plus_admin');
	
	$msg1_title 	= esc_attr__('Customize Appearance','plus_admin');
	$msg1_content 	= esc_attr__('We have paid special attention to the possibility of customizing each section in the way you want, so we have put at your disposal a large number of themes and settings, that way our theme will fit your needs.','plus_admin');

	$msg2_title 	= esc_attr__('Need Help?','plus_admin');
	$msg2_content 	= esc_attr__('We are available to help you in what you need so you can fully enjoy our plugin. Just send us an email to support@castorstudio.com and we will answer as quickly as possible!','plus_admin');

	$msg3_title 	= esc_attr__('Get in touch','plus_admin');
	$msg3_content 	= esc_attr__('Do you have questions or suggestions? Feel free to contact us through our official website or via our Envato profile. We are available every day!','plus_admin');

	?>
	<div class="wrap cs-plugin-home">
        <h1><?=$plugin_name?> <?php _e('Welcome Page', 'plus_admin'); ?></h1>
		<div class="cs-header">
            <div class="cs-header__title">
                <h1><?php echo sprintf( esc_attr__( 'Welcome to %s', 'plus_admin' ), '<strong>'.$plugin_name .' '. $plugin_version.'</strong>' ) ?></h1>
            </div>
            <div class="cs-header__content">
                <div class="cs-header__about-text">
                    <?php echo $welcome_msg; ?>
                </div>
            </div>
		</div>

		<div class="cs-features">
            <div class="one-third">
                <h4><i class="cli cli-droplet"></i><?php echo $msg1_title; ?></h4>
                <p><?php echo $msg1_content; ?></p>
            </div>
			<div class="one-third">
				<h4><i class="cli cli-help-circle"></i><?php echo $msg2_title ?></h4>
				<p><?php echo $msg2_content; ?></p>
			</div>
			<div class="one-third">
				<h4><i class="cli cli-life-buoy"></i><?php echo $msg3_title; ?></h4>
				<p><?php echo $msg3_content; ?></p>
			</div>
		</div>
	</div>
	<?php
}