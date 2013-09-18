<?php
if( !function_exists('cptr_checkbox_roles') ):
function cptr_checkbox_roles( $name = 'cptr_allowed_roles[]', $selected = array() ) {
	$str = '';

	$editable_roles = get_editable_roles();

	foreach ( $editable_roles as $role => $details ) {
		$name = translate_user_role($details['name'] );
		
		if ( in_array($role, $selected) ) // preselect specified roles
			$str .= '\n\t<label><input type="checkbox" name="' . esc_attr($name) . '" checked="checked" value="' . esc_attr($role) . '">' . $name . '</label>';
		else
			$str .= '\n\t<label><input type="checkbox" name="' . esc_attr($name) . '" value="' . esc_attr($role) . '">' . $name . '</label>';
	}
	echo $str;
}

?>