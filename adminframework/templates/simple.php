<?php
global $cssf_submenus;
$cssf_submenus = array();
if ( ! empty( $title ) ) {
	?>
	<h2><?php echo $title; ?> </h2>
	<?php
}
?>

<h2 class="nav-tab-wrapper cssf-main-nav">
	<?php
	foreach ( $class->navs() as $nav ) {
		$title     = ( isset( $nav['title'] ) ) ? $nav['title'] : '';
		$is_active = ( isset( $nav['is_active'] ) && true === $nav['is_active'] ) ? ' nav-tab-active ' : '';
		$href      = '#';

		if ( isset( $nav['href'] ) && ( false !== $nav['href'] && '#' !== $nav['href'] ) ) {
			$href      = $nav['href'];
			$is_active .= ' has-link ';
		}

		if ( isset( $nav['is_separator'] ) && true === $nav['is_separator'] ) {
			continue;
		}
		echo '<a href="' . $href . '" class="nav-tab ' . $is_active . '" data-section="' . $nav['name'] . '">' . $class->icon( $nav ) . ' ' . $title . '</a>';
		if ( isset( $nav['submenus'] ) ) {
			cssf_simple_render_submenus( $nav['submenus'], $nav['name'], $class );
		}
	}
	?>
</h2>


<div id="poststuff">
	<div class="metabox-holder" id="post-body">
		<div id="post-body-content">
			<div class="cssf-content">
				<div class="cssf-sections">
					<?php
					foreach ( $class->options as $option ) {
						if ( 'no' === $single_page && $option['name'] !== $class->active() ) {
							continue;
						}
						if ( ! isset( $option['fields'] ) && ! isset( $option['callback_hook'] ) && ! isset( $option['sections'] ) ) {
							continue;
						}

						$is_active_page = ( $class->active() === $option['name'] ) ? '' : 'style="display:none;"';

						$pg_active = ( $option['name'] === $class->active() ) ? true : false;
						echo '<div id="cssf-tab-' . $option['name'] . '" ' . $is_active_page . '>';
						echo '<div class="postbox">';
						if ( isset( $cssf_submenus[ $option['name'] ] ) && ! empty( $cssf_submenus[ $option['name'] ] ) ) {
							echo '<h2 class="cssf-subnav-container hndle">';
							echo '<ul class="cssf-submenus subsubsub"  id="cssf-tab-' . $option['name'] . '" >' . $cssf_submenus[ $option['name'] ] . '</ul>';
							echo '</h2>';
						}

						if ( isset( $option['sections'] ) ) {
							echo '<div class="inside">';
							$first_sec = current( $option['sections'] );
							$first_sec = ( is_array( $first_sec ) && isset( $first_sec['name'] ) ) ? $first_sec['name'] : false;
							foreach ( $option['sections'] as $section ) {
								$sc_active = ( true === $pg_active && $section['name'] === $class->active( false ) ) ? true : false;
								$fields    = $class->render_fields( $section );

								if ( false === $sc_active && $first_sec === $section['name'] ) {
									$is_sc_active = 'style="display:block"';
								} else {
									$is_sc_active = empty( $class->is( 'page_active', $sc_active ) ) ? 'style="display:none"' : $class->is( 'page_active', $sc_active );
								}

								echo '<div ' . $is_sc_active . ' id="cssf-tab-' . $option['name'] . '-' . $section['name'] . '" >';
								echo $class->get_title( $section ) . $fields . '</div>';
							}
							echo '</div>';
						} elseif ( isset( $option['fields'] ) ) {
							$fields = $class->render_fields( $option );
							echo '<div class="inside">' . $class->get_title( $option ) . $fields . '</div>';
						} elseif ( isset( $option['callback_hook'] ) ) {
							$fields  = $class->render_fields( $option );
							$is_wrap = ( isset( $option['with_wrapper'] ) && true === $option['with_wrapper'] ) ? true : false;

							if ( $is_wrap ) {
								echo '<div class="inside">' . $class->get_title( $option ) . $fields . '</div>';
							} else {
								echo $fields;
							}
						}
						echo '</div>';
						echo '</div>';
					}
					?>
				</div>
				<div class="cssf-sections">
					<div class="cssf-simple-footer">
						<?php
						if ( 'yes' === $ajax ) {
							echo '<span id="cssf-save-ajax">' . esc_attr__( 'Settings Saved', 'cssf-framework' ) . '</span>';
						}
						echo $class->get_settings_buttons(); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
