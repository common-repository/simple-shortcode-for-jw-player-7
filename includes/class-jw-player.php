<?php

class JW_Player {
	
	protected static $first = true;
	protected static $counter = 0;

	public static function menu() {
		add_menu_page( 'JW Player 7', 'JW Player 7', 'manage_options', 'jw-player-options', array( 'JW_Player', 'options' ) );
	}
	
	public static function options() {
		
		$options = json_decode( get_option( 'jw-player-options', '{"key":"","js":""}' ), true );
		$update = false;

		if( isset( $_POST['key'] ) && ! empty( $_POST['key'] ) ) {
			$options['key'] = $_POST['key'];
			$update = true;
		}

		if( isset( $_POST['js'] ) && ! empty( $_POST['js'] ) ) {
			$options['js'] = $_POST['js'];
			$update = true;
		}

		if( $update ) {
			update_option( 'jw-player-options', json_encode( $options ) );
		}

?>
		<div class="wrap">
		<h1><?php _e( 'JW Player Options', 'jw-player-7' ); ?></h1>
			<form method="POST" action="">
				<table class="form-table">
					<tr>
						<th scope="row"><label for="key"><?php _e( 'Key', 'jw-player-7' ); ?></label></th>
						<td>
							<input id="jw-player-key" type="text" class="regular-text" name="key" value="<?php echo isset( $options['key'] ) ? $options['key'] : '' ; ?>" />
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="js"><?php _e( 'Javascript file location', 'jw-player-7' ); ?></label></th>
						<td>
							<input id="jw-player-js" type="text" class="regular-text" name="js" value="<?php echo isset( $options['js'] ) ? $options['js'] : '' ; ?>" />
						</td>
					</tr>
					
				</table>

				<p class="submit"><input type="submit" value="<?php _e( 'Save changes', 'jw-player-7' ); ?>" class="button button-primary" id="submit" name="submit"></p>
			</form>
		</div>
<?php
	}
	
	
	/* shortcodes */
	
	public static function player_shortcode( $atts ) {
		
		$options = json_decode( get_option( 'jw-player-options' ), true );
		
		if( ! isset( $options['key'] ) || empty( $options['key'] ) ) {
			return __( 'JW Player needs a key.', 'jw-player-7' );
		}

		if( ! isset( $options['js'] ) || empty( $options['js'] ) ) {
			return __( 'JW Player javascript file not found.', 'jw-player-7' );
		}
		
		$atts = shortcode_atts( array(
			'width'			=> '100%',
			'height'		=> '',
			'image'			=> '',
			'file'			=> '',
			'title'			=> '',
			'description'	=> '',
			'ratio'			=> '16:9',
			'mediaid'		=> '',
			'controls'		=> 'true',
			'autostart'		=> 'false',
		), $atts );
		
		$file = $title = $description = $image = '';
		
		if( ! empty( $atts['mediaid'] ) ) {
			
			$post = get_post( $atts['mediaid'] );
			if( empty( $post ) ) {
				return sprintf( __( 'Could not find media with id : %s', 'jw-player-7' ), $atts['mediaid'] );
			} else {
				$file = $post->guid;
				$title = $post->post_title;
				$description = $post->post_excerpt;
				$image = wp_get_attachment_url( get_post_thumbnail_id( $post->ID ) );

			}
			
		} elseif( ! empty( $atts['file'] ) ) {
			$file = $atts['file'];
			$title = $atts['title'];
			$description = $atts['description'];
			$image = $atts['image'];
		}
		
		if( empty( $file ) ) {
			return __( 'Could not find source file.' );
		}
		
		$id = 'jw-player-' . static::$counter++;
		
		$out = '<div id="' . $id . '">' . __( 'Loading video ...', 'jw-player-7' ) . '</div>';

		if( static::$first ) {
			static::$first = false;
			$out .= '<script type="text/javascript" src="' . $options['js'] . '"></script><script>jwplayer.key="' . $options['key'] . '";</script>';
		}

		$out .= "
<script>( function() { 
	var pi = jwplayer('$id');
	pi.setup({
		file: '$file',
		image: '$image',
		width: '$atts[width]',
		height: '$atts[height]',
		title: '$title',
		description: '$description',
		aspectratio: '$atts[ratio]',
		controls: $atts[controls],
		autostart: $atts[autostart],
	});
} )();</script>
";

		return $out;
	}
	
	/* End shortcodes */

	public static function register_tinymce_button( $buttons ) {
		array_push( $buttons, 'separator', 'ssfjwplayer' );
		return $buttons;
	}
	public static function tinymce_button_javascript( $plugin_array ) {
		$plugin_array['ssfjwplayer'] = plugins_url( '../js/tmce-button.js', __FILE__ );
		return $plugin_array;
	}
}



