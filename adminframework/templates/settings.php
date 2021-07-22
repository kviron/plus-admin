<?php
	/**
	 * @uses CSSFramework_Settings @class
	 */
	$single_page   	= ( $class->is( 'single_page' ) === true ) ? 'yes' : 'no';
	$sticky_header	= ( $class->is( 'sticky_header' ) === true ) ? 'cssf-sticky-header' : false;
	$ajax          	= ( $class->is( 'ajax_save' ) === true ) ? 'yes' : 'no';
	$title         	= $class->_option( 'framework_title' );
	$subtitle 		= $class->_option('framework_subtitle');
	$has_nav       	= ( $class->is( 'has_nav' ) === false ) ? 'cssf-show-all' : '';
	$unique_id 		= $class->get_unique();
?>
<div class="wrap"><h1 class="wp-heading-inline"><?php echo $title; ?></h1>
	<div class="cssf-framework cssf-option-framework cssf-theme-<?php echo $class->theme(); ?> cssf-framework--<?php echo $unique_id; ?>"
		data-theme="<?php echo $class->theme(); ?>"
		data-single-page="<?php echo $single_page; ?>"
		data-stickyheader="<?php echo $sticky_header; ?>"
		data-cssf-id="<?php echo $unique_id; ?>"
		>

		<form method="post" action="options.php" enctype="multipart/form-data" class="cssf-form" id="cssframework_form">
			<?php settings_fields( $unique_id ); ?>
			<input type="hidden" class="cssf-reset" name="cssf-section-id" value="<?php echo $class->active( false ); ?>"/>
			<!--<input type="hidden" class="cssf_parent_section_id" name="cssf-parent-id" value="<?php echo $class->active(); ?>"/>-->
			<input type='hidden' class='cssf-i18n-ajax-save-saving' value='<?php _e('Saving your settings','cssf-framework'); ?>'/>
			<input type='hidden' class='cssf-i18n-ajax-save-success' value='<?php _e('Settings saved successfully','cssf-framework'); ?>'/>
			<input type='hidden' class='cssf-i18n-ajax-save-error' value='<?php _e('Error saving your settings. Please try again.','cssf-framework'); ?>'/>

			<?php

			cssf_template( $class->override_location(), $class->theme() . '.php', array(
				'class'         => $class,
				'single_page'   => $single_page,
				'sticky_header' => $sticky_header,
				'ajax'          => $ajax,
				'title'         => $title,
				'subtitle'		=> $subtitle,
				'has_nav'       => $has_nav,
			) );
			?>

		</form>
		<div class="clear"></div>
	</div>
</div>
