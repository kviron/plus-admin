<header class="cssf-header <?php echo $sticky_header; ?>">
	<?php if ( ! empty( $title ) ) : ?>
		<h1><?php echo $title; ?><small><?php echo $subtitle; ?></small></h1>
	<?php endif; ?>

	<div class="cssf-header-buttonbar">
		<?php
		if ( 'yes' === $ajax ) {
			echo '<span id="cssf-save-ajax">' . esc_attr__( 'Settings Saved', 'cssf-framework' ) . '</span>';
		}
		echo $class->get_settings_buttons();
		?>
	</div>
	<?php
	if ( true === $class->is( 'has_nav' ) ) {
		if ($class->_option( 'show_all_options_link' )){
			echo '<a href="#" class="cssf-expand-all"><i class="fa fa-eye-slash"></i> ' . esc_attr__( 'Show All Options','cssf-framework' ) . '</a>';
		}
	}
	echo '<div class="clear"></div>';
	?>

</header>

<div class="cssf-body <?php echo $has_nav; ?>">
	<div class="cssf-nav">
		<div class="cssf-nav-buttons"><div class="cssf-nav-button cssf-nav-prev" data-type="prev"></div><div class="cssf-nav-button cssf-nav-next" data-type="next"></div></div>
		<div class="cssf-nav-inner-wrapper">
			<div class="cssf-nav-wrapper">
				<ul> <?php cssf_modern_navs( $class->navs(), $class ); ?> </ul>
			</div>
		</div>
	</div>


	<div class="cssf-content">
		<div class="cssf-sections">
			<?php
			foreach ( $class->options as $option ) {
				if ( 'no' === $single_page && $option['name'] !== $class->active() ) {
					continue;
				}

				$pg_active = ( $option['name'] === $class->active() ) ? true : false;

				if ( isset( $option['sections'] ) ) {
					foreach ( $option['sections'] as $section ) {
						if ( 'no' === $single_page && $section['name'] !== $class->active( false ) ) {
							continue;
						}

						$sc_active = ( true === $pg_active && $section['name'] === $class->active( false ) ) ? true : false;
						$fields    = $class->render_fields( $section );

						echo '<div ' . $class->is( 'page_active', $sc_active ) . ' 
                        id="cssf-tab-' . $option['name'] . '-' . $section['name'] . '" 
                        class="cssf-section">' . $class->get_title( $section ) . $fields . '</div>';
					}
				} elseif ( isset( $option['fields'] ) || isset( $option['callback_hook'] ) ) {
					$fields = $class->render_fields( $option );
					echo '<div ' . $class->is( 'page_active', $pg_active ) . ' 
                        id="cssf-tab-' . $option['name'] . '" 
                        class="cssf-section">' . $class->get_title( $option ) . $fields . '</div>';
				}
			}
			?>
		</div>
		<div class="clear"></div>
	</div>
	<div class="cssf-nav-background"></div>
</div>

<footer class="cssf-footer">
	<div class="cssf-block-left"><?php _e('Powered by CastorStudio Settings Framework','cssf-framework'); ?></div>
	<div class="cssf-block-right">
		<?php
			echo esc_attr__('Version','cssf-framework') . ' ' . CSSF_VERSION;
		?>
	</div>
	<div class="clear"></div>
</footer>