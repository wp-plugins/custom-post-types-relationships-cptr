<?php
if( !function_exists('cptr_checkbox_roles') ):
function cptr_checkbox_roles( $fieldname = 'allowed_roles[]', $selected = array() ) {
	$str = '';

	// Make sure we have an array to check against, even if empty.
	if(empty($selected))
		$selected = array();

	$editable_roles = get_editable_roles();

	foreach ( $editable_roles as $role => $details ) {
		$name = translate_user_role($details['name'] );
		
		if ( in_array($role, $selected) ) // preselect specified roles
			$str .= "\n\t".'<label><input type="checkbox" name="' . esc_attr($fieldname) . '" checked="checked" value="' . esc_attr($role) . '">' . $name . '</label>';
		else
			$str .= "\n\t".'<label><input type="checkbox" name="' . esc_attr($fieldname) . '" value="' . esc_attr($role) . '">' . $name . '</label>';
	}
	echo $str;
}
endif;

if( !function_exists('cptr_checkbox_post_types') ):
function cptr_checkbox_post_types( $fieldname = 'allowed_post_types[]', $selected = array() ) {
	$str = '';

	// Make sure we have an array to check against, even if empty.
	if(empty($selected))
		$selected = array();

	$post_types = _cptr_get_post_types();

	foreach ( $post_types as $cpt_name => $cpt_details ) {
		$name = $cpt_details->labels->name;
		
		if ( in_array($cpt_name, $selected) ) // preselect specified roles
			$str .= "\n\t".'<label><input type="checkbox" name="' . esc_attr($fieldname) . '" checked="checked" value="' . esc_attr($cpt_name) . '">' . $name . '</label>';
		else
			$str .= "\n\t".'<label><input type="checkbox" name="' . esc_attr($fieldname) . '" value="' . esc_attr($cpt_name) . '">' . $name . '</label>';
	}
	echo $str;
}
endif;

function cptr_user_should_see_metabox($options = false)
{
	if( !is_array($options) )
	{
		$options = get_option( CI_CPTR_PLUGIN_OPTIONS );
	}

	$cur_user = wp_get_current_user();

	// Check that at least one of the allowed roles, is also assigned to the user.
	$has_allowed_role = false;
	foreach($options['allowed_roles'] as $role)
	{
		if(in_array($role, $cur_user->roles))
			$has_allowed_role = true;
	}

	if( ! $has_allowed_role )
	{
		return false;
	}
	
	return true;

}

function _cptr_get_post_types()
{
	// Get the post types available
	$types = array();
	$types = get_post_types($args = array(
		'public'   => true
	), 'objects');

	unset($types['attachment']);
	return $types;
}

function _cptr_get_selected_post_types($options = false)
{
	if( !is_array($options) )
	{
		$options = get_option( CI_CPTR_PLUGIN_OPTIONS );
	}

	$types = _cptr_get_post_types();

	foreach($types as $key => $value)
	{
		if( !in_array($key, $options['allowed_post_types']) )
			unset( $types[$key] );
	}

	return $types;
}

function _cptr_get_default_post_types()
{
	$arr = array();
	$types = _cptr_get_post_types();

	foreach($types as $key => $value)
	{
		$arr[] = $key;
	}

	return $arr;
}

function _create_excerpt($text, $length=55)
{
	$the_excerpt = $text;
	$the_excerpt = strip_shortcodes($the_excerpt);
	$the_excerpt = str_replace(']]>', ']]&gt;', $the_excerpt);
	$the_excerpt = strip_tags($the_excerpt);
	$words_arr = preg_split("/[\n\r\t ]+/", $the_excerpt, $length+1, PREG_SPLIT_NO_EMPTY);
	if ( count($words_arr) > $length ) {
		array_pop($words_arr);
	}
	$the_excerpt = implode(' ', $words_arr);
	return $the_excerpt . '...';
}


/**
 * Marks a function as deprecated and informs when it has been used.
 *
 * The current behavior is to trigger a user error if WP_DEBUG is true.
 *
 * This function is to be used in every function that is deprecated.
 *
 * @param string $function The function that was called
 * @param string $version The version of the plugin that deprecated the function
 * @param string $replacement Optional. The function that should have been called. Empty means there is no replacement available.
 */
function _cptr_deprecated_function( $function, $version, $replacement=null ) {
	if ( WP_DEBUG ) {
		if ( ! is_null($replacement) )
			trigger_error( sprintf( __('Function %1$s is <strong>deprecated</strong> since CPTR version %2$s! Use %3$s instead.'), $function, $version, $replacement ) );
		else
			trigger_error( sprintf( __('Function %1$s is <strong>deprecated</strong> since CPTR version %2$s with no alternative available.'), $function, $version ) );
	}
}

function _cptr_deprecated_parameters( $function, $version ) {
	if ( WP_DEBUG ) {
		trigger_error( sprintf( __('The signature of the function %1$s has changed and the current usage has been <strong>deprecated</strong> since CPTR version %2$s. Please consult the documentation for up to date usage instructions.'), $function, $version ) );
	}
}


?>