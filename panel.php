<?php
// Build the admin panel

if ( is_admin() ){
	add_action('admin_menu', 'ci_cptr_menu');
	add_action('admin_init', 'register_ci_cptr_settings' );
	register_activation_hook( __FILE__, 'ci_cptr_activate' );
	register_deactivation_hook( __FILE__, 'ci_cptr_deactivate' );
}

function register_ci_cptr_settings() { // whitelist options
  register_setting( 'ci_cptr_plugin_settings', CI_CPTR_PLUGIN_OPTIONS, 'ci_cptr_plugin_settings_validate');
}

function ci_cptr_activate()
{
	$options = get_option(CI_CPTR_PLUGIN_OPTIONS);
	if ( !isset($options['limit']) ) $options['limit'] = CPTR_DEFAULT_LIMIT;
	if ( !isset($options['excerpt']) ) $options['excerpt'] = CPTR_DEFAULT_EXCERPT;
	if ( !isset($options['words']) ) $options['words'] = CPTR_DEFAULT_EXCERPT_LENGTH;
	if ( !isset($options['thumb']) ) $options['thumb'] = CPTR_DEFAULT_THUMB;
	if ( !isset($options['width']) ) $options['width'] = CPTR_DEFAULT_THUMB_WIDTH;
	if ( !isset($options['height']) ) $options['height'] = CPTR_DEFAULT_THUMB_HEIGHT;
	
	update_option( CI_CPTR_PLUGIN_OPTIONS, $options );
}
function ci_cptr_deactivate()
{
  unregister_setting( 'ci_cptr_plugin_settings', CI_CPTR_PLUGIN_OPTIONS);
}



function ci_cptr_menu() {
  add_options_page('CSSIgniter Custom Post Types Relationships Options', 'Custom Post Types Relationships', 'manage_options', CI_CPTR_PLUGIN_OPTIONS, 'ci_cptr_plugin_options');
}

function ci_cptr_plugin_settings_validate($settings)
{
	$settings['limit'] = intval($settings['limit']) > 0 ? intval($settings['limit']) : CPTR_DEFAULT_LIMIT;
	$settings['excerpt'] = (isset($settings['excerpt']) and ($settings['excerpt'] == 1) ) ? 1 : 0;
	$settings['words'] = intval($settings['words']) > 0 ? intval($settings['words']) : CPTR_DEFAULT_EXCERPT_LENGTH;
	$settings['thumb'] = (isset($settings['thumb']) and ($settings['thumb'] == 1) ) ? 1 : 0;
	$settings['width'] = intval($settings['width']) > 0 ? intval($settings['width']) : CPTR_DEFAULT_THUMB_WIDTH;
	$settings['height'] = intval($settings['height']) > 0 ? intval($settings['height']) : CPTR_DEFAULT_THUMB_HEIGHT;
	return $settings;
}

function ci_cptr_plugin_options() {
	
	if (!current_user_can('manage_options'))  {
		wp_die( __('You do not have sufficient permissions to access this page.') );
	}
	?>
	<div class="wrap">
		<h2>CSSIgniter Custom Post Types Relationships v<?php echo CPTR_VERSION; ?> - Settings</h2>
		<p>In this page you can define general options for the Custom Post Types Relationships plugin. All options here can be overridden 
		manually by passing the appropriate parameters to the shortcode or the theme function. If you find yourself making changes here 
		that don't have any effect, it's because your WordPress Theme has hardcoded options for you, so check with the theme's developer.</p>
		<p>For complete usage instructions, plase visit the <a href="http://www.cssigniter.com/ignite/custom-post-types-relationships/">plugin's homepage</a>.</p>
		<form method="post" action="options.php">
			<?php settings_fields('ci_cptr_plugin_settings'); ?>
	
			<?php 
				$options = get_option(CI_CPTR_PLUGIN_OPTIONS); 
				
			?>

			<table class="form-table">
				<tr valign="top">
					<th scope="row">Max number of displayed related posts:</th>
					<td>
						<input name="<?php echo CI_CPTR_PLUGIN_OPTIONS; ?>[limit]" type="text" value="<?php echo $options['limit']; ?>" />
						<p>Set to 0 for no limit.</p>
					</td>
				</tr>

				<tr valign="top">
					<th scope="row">Show the excerpt for each post?</th>
					<td>
						<input name="<?php echo CI_CPTR_PLUGIN_OPTIONS; ?>[excerpt]" type="checkbox" value="1" <?php checked($options['excerpt'], 1); ?> />
					</td>
				</tr>

				<tr valign="top">
					<th scope="row">How many words the excerpt should be?</th>
					<td>
						<input name="<?php echo CI_CPTR_PLUGIN_OPTIONS; ?>[words]" type="text" value="<?php echo $options['words']; ?>" />
					</td>
				</tr>

				<tr valign="top">
					<th scope="row">Display the thumbnail?</th>
					<td>
						<input name="<?php echo CI_CPTR_PLUGIN_OPTIONS; ?>[thumb]" type="checkbox" value="1" <?php checked($options['thumb'], 1); ?> />
						<p>The thumbnail will be displayed after the title and before the excerpt.</p>
					</td>
				</tr>

				<tr valign="top">
					<th scope="row">Thumbnail size</th>
					<td>
						<label>Width</label>
						<input name="<?php echo CI_CPTR_PLUGIN_OPTIONS; ?>[width]" type="text" value="<?php echo $options['width']; ?>" />
						<br /><label>Height</label>
						<input name="<?php echo CI_CPTR_PLUGIN_OPTIONS; ?>[height]" type="text" value="<?php echo $options['height']; ?>" />
					</td>
				</tr>


			</table>
	
			<p class="submit">
				<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
			</p>
		</form>
	</div>
	
	<?php
}
?>
