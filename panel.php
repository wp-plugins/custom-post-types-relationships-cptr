<?php
// Build the admin panel

function ci_cptr_plugin_options() {
	
	if ( !current_user_can('manage_options') )
	{
		wp_die( __('You do not have sufficient permissions to access this page.') );
	}
	?>
	<div class="wrap" id="cptr-options">
		<!--<h2><?php echo sprintf(__('CSSIgniter Custom Post Types Relationships v%s - Settings', 'cptr'), CPTR_VERSION); ?></h2>-->
		<h2><?php echo sprintf(__('CSSIgniter Custom Post Types Relationships', 'cptr'), CPTR_VERSION); ?></h2>
		<p><?php _e("In this page you can define general options for the Custom Post Types Relationships plugin. All options here can be overridden manually by passing the appropriate parameters to the shortcode or the theme function. If you find yourself making changes here that don't have any effect, it's because your WordPress theme has hardcoded options for you, so check with the theme's developer.", 'cptr'); ?></p>
		<p><?php echo sprintf(__('For complete usage instructions, please visit the <a href="%s">plugin\'s homepage</a>.', 'cptr'), 'http://www.cssigniter.com/ignite/custom-post-types-relationships/'); ?></p>
		<form method="post" action="options.php">
			<?php settings_fields('ci_cptr_plugin_settings'); ?>
	
			<?php
				$options = get_option(CI_CPTR_PLUGIN_OPTIONS);
				$options = ci_cptr_plugin_settings_validate($options);
			?>

			<table class="form-table">
				<tr valign="top">
					<th scope="row"><?php _e('Max number of displayed related posts:', 'cptr'); ?></th>
					<td>
						<input name="<?php echo CI_CPTR_PLUGIN_OPTIONS; ?>[limit]" type="text" value="<?php echo $options['limit']; ?>" class="small-text" />
						<p><?php _e('Set to 0 for no limit.', 'cptr'); ?></p>
					</td>
				</tr>

				<tr valign="top">
					<th scope="row"><?php _e('Show the excerpt for each post?', 'cptr'); ?></th>
					<td>
						<input name="<?php echo CI_CPTR_PLUGIN_OPTIONS; ?>[excerpt]" type="checkbox" value="1" <?php checked($options['excerpt'], 1); ?> />
					</td>
				</tr>

				<tr valign="top">
					<th scope="row"><?php _e('How many words the excerpt should be?', 'cptr'); ?></th>
					<td>
						<input name="<?php echo CI_CPTR_PLUGIN_OPTIONS; ?>[words]" type="text" value="<?php echo $options['words']; ?>" class="small-text" />
					</td>
				</tr>

				<tr valign="top">
					<th scope="row"><?php _e('Display the thumbnail?', 'cptr'); ?></th>
					<td>
						<input name="<?php echo CI_CPTR_PLUGIN_OPTIONS; ?>[thumb]" type="checkbox" value="1" <?php checked($options['thumb'], 1); ?> />
						<p><?php _e('The thumbnail will be displayed after the title and before the excerpt.', 'cptr'); ?></p>
					</td>
				</tr>

				<tr valign="top">
					<th scope="row"><?php _e('Thumbnail size', 'cptr'); ?></th>
					<td>
						<label><?php _e('Width', 'cptr'); ?></label>
						<input name="<?php echo CI_CPTR_PLUGIN_OPTIONS; ?>[width]" type="text" value="<?php echo $options['width']; ?>" class="small-text" />
						<label><?php _e('Height', 'cptr'); ?></label>
						<input name="<?php echo CI_CPTR_PLUGIN_OPTIONS; ?>[height]" type="text" value="<?php echo $options['height']; ?>" class="small-text" />
					</td>
				</tr>

				<tr valign="top">
					<th scope="row"><h3><?php _e('Display Options', 'cptr'); ?></h3></th>
					<td>
						&nbsp;
					</td>
				</tr>

				<tr valign="top">
					<th scope="row"><?php _e('Metabox title', 'cptr'); ?></th>
					<td>
						<input name="<?php echo CI_CPTR_PLUGIN_OPTIONS; ?>[metabox_name]" type="text" value="<?php echo $options['metabox_name']; ?>" class="regular-text" />
						<p><?php _e('This is the title of the metabox that the users will see while in the post edit screens.', 'cptr'); ?></p>
					</td>
				</tr>

				<tr valign="top">
					<th scope="row"><?php _e('Allowed roles', 'cptr'); ?></th>
					<td>
						<p><?php _e('Select the roles that will have access to the plugin (i.e. can create/delete relationships).', 'cptr'); ?></p>
						<fieldset class="allowed-roles">
							<?php cptr_checkbox_roles(CI_CPTR_PLUGIN_OPTIONS.'[allowed_roles][]', $options['allowed_roles']); ?>
						</fieldset>
					</td>
				</tr>

				<tr valign="top">
					<th scope="row"><?php _e('Allowed post types', 'cptr'); ?></th>
					<td>
						<p><?php _e('Select the post types that the plugin will be available to.', 'cptr'); ?></p>
						<fieldset class="allowed-post-types">
							<?php cptr_checkbox_post_types(CI_CPTR_PLUGIN_OPTIONS.'[allowed_post_types][]', $options['allowed_post_types']); ?>
						</fieldset>
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