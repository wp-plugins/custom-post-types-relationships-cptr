<?php

if (!defined('CPR_VERSION'))
	define('CPR_VERSION', CPTR_VERSION);

if (!defined('CI_CPR_PLUGIN_OPTIONS'))
	define('CI_CPR_PLUGIN_OPTIONS', 'ci-cpr-plugin');
	
if (!defined('CI_CPR_POST_RELATED'))
	define('CI_CPR_POST_RELATED', 'cpr_related');
	
// Deprecated Constants. Use the ones with CPTR prefix.
define('CPR_DEFAULT_LIMIT', CPTR_DEFAULT_LIMIT);
define('CPR_DEFAULT_EXCERPT', CPTR_DEFAULT_EXCERPT);
define('CPR_DEFAULT_EXCERPT_LENGTH', CPTR_DEFAULT_EXCERPT_LENGTH); 
define('CPR_DEFAULT_THUMB', CPTR_DEFAULT_THUMB); 
define('CPR_DEFAULT_THUMB_WIDTH', CPTR_DEFAULT_THUMB_WIDTH); 
define('CPR_DEFAULT_THUMB_HEIGHT', CPTR_DEFAULT_THUMB_HEIGHT); 


function cpr_scripts_admin_styles() {
	_cptr_deprecated_function(__FUNCTION__, '2.2', 'cptr_scripts_admin_styles');
	cptr_scripts_admin_styles();
}

function cpr_scripts_styles() {
	_cptr_deprecated_function(__FUNCTION__, '2.2', 'cptr_scripts_styles');
	cptr_scripts_styles();
}

function cpr_box() {
	_cptr_deprecated_function(__FUNCTION__, '2.2', 'cptr_box');
	cptr_box();
}

function cpr_category_selector() {
	_cptr_deprecated_function(__FUNCTION__, '2.2', 'cptr_category_selector');
	cptr_category_selector();
} 

function cpr_cats() {
	_cptr_deprecated_function(__FUNCTION__, '2.2', 'cptr_cats');
	cptr_cats();
}

function cpr_save() {	
	_cptr_deprecated_function(__FUNCTION__, '2.2', 'cptr_save');
	cptr_save();
}

function cpr_populate($id) {
	_cptr_deprecated_function(__FUNCTION__, '2.2', 'cptr_populate');
	return cptr_populate($id);
}

function cpr_show(	$echo=true, 
					$limit=CPR_DEFAULT_LIMIT, 
					$excerpt=CPR_DEFAULT_EXCERPT, 
					$words=CPR_DEFAULT_EXCERPT_LENGTH, 
					$thumb=CPR_DEFAULT_THUMB, 
					$width=CPR_DEFAULT_THUMB_WIDTH,
					$height=CPR_DEFAULT_THUMB_HEIGHT) {

	_cptr_deprecated_function(__FUNCTION__, '2.2', 'cptr_show');
	return cptr_show($echo, $limit, $excerpt, $words, $thumb, $width, $height);
}

function ci_cpr_short($atts) {
	_cptr_deprecated_function(__FUNCTION__, '2.2', 'ci_cptr_short');
	return ci_cptr_short($atts);
}

function cpr_uninstall()
{
	_cptr_deprecated_function(__FUNCTION__, '2.2', 'cptr_uninstall');
	cptr_uninstall();
}

function _cpr_get_post_types()
{
	_cptr_deprecated_function(__FUNCTION__, '2.2', '_cptr_get_post_types');
	return _cptr_get_post_types();
}


?>