<?php
add_action('admin_menu', 'peg_admin_add_page');
function peg_admin_add_page() {	
	add_options_page('Paginated Effects Page ', 'Paginated Effects', 'manage_options', 'peg', 'peg_options_page');	
}

add_action( 'admin_init', 'peg_admin_init' );
function peg_admin_init() {	
	register_setting( 'peg_options', 'thumbnails_per_page' );	
	register_setting( 'peg_options', 'paginated_links' );
	register_setting( 'peg_options', 'hide_effect' );
	register_setting( 'peg_options', 'show_effect' );
	register_setting( 'peg_options', 'effect_speed' );	
}

function peg_options_page() {
	if (!current_user_can('manage_options'))  {
		wp_die( __('You do not have sufficient permissions to access this page.') );
	} ?>
	<div class="wrap">
		<?php screen_icon(); ?>
		<h2>Paginated Effects Gallery</h2>
		<form method="post" action="options.php">			
		  <?php settings_fields( 'peg_options' ); ?>			
		    <table class="form-table">
		        <tr valign="top">
			        <th scope="row">Thumbnails Per Page</th>
			        <td><input type="text" name="thumbnails_per_page" value="<?php echo get_option('thumbnails_per_page'); ?>" /></td>
		        </tr>		  
						<tr valign="top">
							<th scope="row">Max paginated links</th>
							<td><input type="text" name="paginated_links" value="<?php echo get_option('paginated_links'); ?>" /></td>
						</tr>
						<tr valign="top">
			        <th scope="row">Select jQuery Hide Effect</th>
			        <td>
								<?php $hideOption = get_option( "hide_effect" ); ?>
								<select name='hide_effect'>
									<option value='none' <?php if( $hideOption == "none") echo "selected";?>>None</option>
									<option value='blind' <?php if( $hideOption == "blind") echo "selected";?>>Blind</option>
									<option value='bounce' <?php if( $hideOption == "bounce") echo "selected";?>>Bounce</option>
									<option value='clip' <?php if( $hideOption == "clip") echo "selected";?>>Clip</option>
									<option value='drop' <?php if( $hideOption == "drop") echo "selected";?>>Drop</option>
									<option value='explode' <?php if( $hideOption == "explode") echo "selected";?>>Explode</option>		
									<option value='fade' <?php if( $hideOption == "fade") echo "selected";?>>Fade</option>
									<option value='fold' <?php if( $hideOption == "fold") echo "selected";?>>Fold</option>		
									<option value='highlight' <?php if( $hideOption == "highlight") echo "selected";?>>Highlight</option>	
									<option value='puff' <?php if( $hideOption == "puff") echo "selected";?>>Puff</option>
									<option value='pulsate' <?php if( $hideOption == "pulsate") echo "selected";?>>Pulsate</option>
									<option value='scale' <?php if( $hideOption == "scale") echo "selected";?>>Scale</option>
									<option value='shake' <?php if( $hideOption == "shake") echo "selected";?>>Shake</option>
									<option value='size' <?php if( $hideOption == "size") echo "selected";?>>Size</option>
									<option value='slide' <?php if( $hideOption == "slide") echo "selected";?>>Slide</option>									
								</select>
							</td>
		        </tr>
						<tr valign="top">
			        <th scope="row">Select jQuery Show Effect</th>
			        <td>
								<?php $showOption = get_option( "show_effect" ); ?>
								<select name='show_effect'>
									<option value='none' <?php if( $showOption == "none" ) echo "selected";?>>None</option>
									<option value='blind' <?php if( $showOption == "blind" ) echo "selected";?>>Blind</option>
									<option value='bounce' <?php if( $showOption == "bounce" ) echo "selected";?>>Bounce</option>
									<option value='clip' <?php if( $showOption == "clip" ) echo "selected";?>>Clip</option>
									<option value='drop' <?php if( $showOption == "drop" ) echo "selected";?>>Drop</option>
									<option value='explode' <?php if( $showOption == "explode" ) echo "selected";?>>Explode</option>		
									<option value='fade' <?php if( $showOption == "fade" ) echo "selected";?>>Fade</option>
									<option value='fold' <?php if( $showOption == "fold" ) echo "selected";?>>Fold</option>		
									<option value='highlight' <?php if( $showOption == "highlight" ) echo "selected";?>>Highlight</option>	
									<option value='puff' <?php if( $showOption == "puff" ) echo "selected";?>>Puff</option>
									<option value='pulsate' <?php if( $showOption == "pulsate" ) echo "selected";?>>Pulsate</option>
									<option value='scale' <?php if( $showOption == "scale" ) echo "selected";?>>Scale</option>
									<option value='shake' <?php if( $showOption == "shake" ) echo "selected";?>>Shake</option>
									<option value='size' <?php if( $showOption == "size" ) echo "selected";?>>Size</option>
									<option value='slide' <?php if( $showOption == "slide" ) echo "selected";?>>Slide</option>									
								</select>
							</td>
		        </tr>
						<tr valign="top">
			        <th scope="row">Select Animation Speed</th>
			        <td>
								<?php $effectSpeed = get_option( "effect_speed" ); ?>
								<select name='effect_speed'>
									<option value='slow' <?php if( $effectSpeed == "slow") echo "selected";?>>Slow</option>
									<option value='normal' <?php if( $effectSpeed == "normal") echo "selected";?>>Normal</option>																
									<option value='fast' <?php if( $effectSpeed == "fast") echo "selected";?>>Fast</option>																
								</select>
							</td>
		        </tr>
		    </table>	    
				<?php submit_button(); ?>
		</form>
	</div>
	<?php 
}
?>