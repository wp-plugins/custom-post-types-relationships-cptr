<?php
/*
Plugin Name: Custom Post Types Relationships (CPTR)
Plugin URI: http://www.cssigniter.com/ignite/custom-post-types-relationships/
Description: An easy way to create relationships between posts, pages, and custom post types in Wordpress
Version: 2.0
Author: The CSSigniter Team
Author URI: http://www.cssigniter.com/


Copyright 2010  The CSSigniter Team (email : info@cssigniter.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/




if (!defined('CPR_VERSION'))
	define('CPR_VERSION', '1.01');

if (!defined('CI_CPR_PLUGIN_OPTIONS'))
	define('CI_CPR_PLUGIN_OPTIONS', 'ci-cpr-plugin');
	
if (!defined('CI_CPR_POST_RELATED'))
	define('CI_CPR_POST_RELATED', 'cpr_related');
	
// Set defaults
define('CPR_DEFAULT_LIMIT', 0);
define('CPR_DEFAULT_EXCERPT', 0); // 0 is false, 1 is true
define('CPR_DEFAULT_EXCERPT_LENGTH', 55); 
define('CPR_DEFAULT_THUMB', 0); // 0 is false, 1 is true
define('CPR_DEFAULT_THUMB_WIDTH', 100); 
define('CPR_DEFAULT_THUMB_HEIGHT', 100); 


require_once('panel.php');

add_action('admin_menu', 'cpr_scripts_admin_styles');
function cpr_scripts_admin_styles() {
	wp_enqueue_script('jquery-ui-core');
	wp_enqueue_script('jquery-ui-sortable');		
	wp_enqueue_style('cpr-admin-css', plugin_dir_url( __FILE__ ) . 'cpr-admin.css', true, CPR_VERSION , 'all' );
	wp_enqueue_script('category-ajax-request', plugin_dir_url( __FILE__ ) . 'cpr.js', array( 'jquery' ) );
	wp_localize_script('category-ajax-request', 'AjaxHandler', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
}

add_action('init', 'cpr_scripts_styles');
function cpr_scripts_styles() {
	wp_enqueue_style('cpr-css', plugin_dir_url( __FILE__ ) . 'cpr.css', true, CPR_VERSION , 'all' );
}

function cpr_box() {

	$cpr_post_types = _cpr_get_post_types();
	
	foreach ($cpr_post_types as $key=>$value)
	{
		add_meta_box( 'post-meta-boxes', 'Custom Post Types Relationships (CPTR)', 'cpr_category_selector', $key, 'normal','default' );
	}
}

function cpr_category_selector() {
	
	global $post_ID, $post;

	$cpr_post_types = _cpr_get_post_types();
	
	?>
	
	<div id='cat-selector'>
		<select id='howmany' name='howmany'>
			<option value='10'>10</option>
			<option value='50'>50</option>
			<option value='100'>100</option>
			<option value='-1'>All</option>
		</select> posts from 

		<select id='posttype' name='cpr_post_type'>
	 	<?php foreach($cpr_post_types as $key=>$type): ?>
			<option value="<?php echo $key; ?>">
				<?php echo $type->labels->name; ?>
			</option>
		<?php endforeach; ?>
		</select>
		
		ordered by 
		<select id='orderby' name='orderby'>
			<option value='title'>Title</option>
			<option value='date'>Date</option>
		</select>
		 in 
		<select id='orderin' name='orderin'>
			<option value='ASC'>Ascending</option>
			<option value='DESC'>Descending</option>
		</select> order &nbsp;
		&nbsp; Filter: <input type='text' id='filtered' name='filtered' />
		<input type='hidden' id='h_pid' name='h_pid' value='<?php echo $post_ID; ?>'/>
		<input type='button' class='cpr_button button' value='Search' />
	</div>
	
	<div class="postbox">
		<h3>Available Posts</h3>
		<div id="available-posts">Please select a category</div>
		<h3>Related Posts (Drag to reorder)</h3>
		<div id="related-posts">
			<?php
			$relations = get_post_meta($post_ID, CI_CPR_POST_RELATED, true);
	
			if (!empty($relations)) :
				foreach($relations as $relation) :
					$post = get_post($relation);
					echo "<div title='" . $post->post_title . "' class='thepost' id='post-".$post->ID ."'>
							<a href='#' class='removeme'>Remove</a>
							<p><strong>" . $post->post_title . "</strong></p>
							<input type='hidden' name='reladded[]' value='" . $post->ID . "' />
							</div>";
				endforeach;	
			endif;
			?>
			
			<input type="hidden" name="myplugin_noncename" id="myplugin_noncename" value="<?php echo wp_create_nonce( plugin_basename(__FILE__) ); ?>" />
		</div>
	</div>

	<?php
} 

// Where's Dukey? Wa zaaaaaaaaaaaa (the call)
function cpr_cats() {
	$post_type   = $_POST['cpr_post_type'];
	$postID  = $_POST['postID'];
	$howMany = $_POST['howMany'];
	$orderBy = $_POST['orderBy'];
	$orderIn = $_POST['orderIn'];

 		$args = array(
 			'post_type' => $post_type,
 			'numberposts' => $howMany,
 			'post_status' => 'publish',
 			'orderby' => $orderBy,
 			'order' => $orderIn,
 			'post__not_in' => array($postID)
 		);

 		$posts = get_posts($args);
			
		if (!empty($posts)) {
			foreach ( $posts as $post ) {
				setup_postdata($post);
				echo "<div title='" . $post->post_title . "' class='thepost' id='post-".$post->ID ."'>
					<a href='#' class='addme'>Add</a>
					<p><strong>" . $post->post_title . "</strong></p>
					<input type='hidden' name='related[]' value='" . $post->ID . "' />
					</div>";
			}
		}
		else 
		{
			echo '<div class="thepost">This category is empty</div>'; 
		}
	
	exit;
}// function cpr_cats

function cpr_save() {	
	global $post_ID;

	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) return;
	if (!isset($_POST['myplugin_noncename'])) return;
	if (!wp_verify_nonce( $_POST['myplugin_noncename'], plugin_basename(__FILE__))) return;
	if (!current_user_can( 'edit_post', $post_ID ) ) return;

	$id = $_POST['post_ID'];
	$related = isset($_POST['reladded']) ? $_POST['reladded'] : array();
	update_post_meta($id, CI_CPR_POST_RELATED, $related);
}

function cpr_populate($id) {
	global $wpdb;
	$related_meta = get_post_meta($id, CI_CPR_POST_RELATED, true);
	$related_posts = array();
	if (!empty($related_meta)) {
		foreach ($related_meta as $related) {
			$post = get_post($related);
			$related_posts[] = $post;
		}
		return $related_posts; 
	}
}

function cptr($echo=null, $limit=null, $excerpt=null, $words=null, $thumb=null, $width=null, $height=null)
{
	$params = array();
	if ($echo!==null and is_bool($echo)) $params['echo']=$echo; else $params['echo']=true;
	if ($limit!==null) 		$params['limit']=$limit;
	if ($excerpt!==null) 	$params['excerpt']=$excerpt;
	if ($words!==null) 		$params['words']=$words;
	if ($thumb!==null) 		$params['thumb']=$thumb;
	if ($width!==null) 		$params['width']=$width;
	if ($height!==null) 	$params['height']=$height;
	return ci_cpr_short($params);
}

function cpr_show(	$echo=true, 
					$limit=CPR_DEFAULT_LIMIT, 
					$excerpt=CPR_DEFAULT_EXCERPT, 
					$words=CPR_DEFAULT_EXCERPT_LENGTH, 
					$thumb=CPR_DEFAULT_THUMB, 
					$width=CPR_DEFAULT_THUMB_WIDTH,
					$height=CPR_DEFAULT_THUMB_HEIGHT) {
	global $post;
	global $wpdb;

	$text = "";

	$related_meta = get_post_meta($post->ID, CI_CPR_POST_RELATED, true);
	$related_posts = array();
	if (!empty($related_meta)) {
		//Get a list of post IDs
		foreach ($related_meta as $related) {
			$post = get_post($related);
			$related_posts[] = $post;
		}
		
		if(count($related_posts)>0)
		{
			$text .= '<ul id="cptr_related_posts">';
			
			$count=0;
			foreach ($related_posts as $post)
			{
				setup_postdata($post);
				if ($limit!=CPR_DEFAULT_LIMIT and $count>=$limit)
					break;
				$text .= '<li class="'.($count%2==0 ? 'odd' : 'even').'">';
				$text .= '<h4><a href="'.get_permalink($post->ID).'">'.get_the_title().'</a></h4>';
				if (current_theme_supports('post-thumbnails') and $thumb==true and has_post_thumbnail($post->ID))
				{
					$thumbnail = get_the_post_thumbnail($post->ID, array($width, $height));
					$text .= $thumbnail;
				}
				
				if ($excerpt==true)
				{
					$the_excerpt = _create_excerpt($post->post_content, $words);
					$text .= '<p>' . $the_excerpt . '</p>';
				}
				$text .= '</li>';
				$count++;
			}
			$text .= '</ul>';
		}
		
	}
	
	if ($echo)
		echo $text;
	else
		return $text;
}

// [cptr limit=0 excerpt=0 etc... ]
add_shortcode('cptr', 'ci_cpr_short');
function ci_cpr_short($atts) {

	if (isset($atts['echo']))
	{
		$echo = $atts['echo'];
	}
	else
	{
		$cho = true;
	}
	
	$options = get_option(CI_CPR_PLUGIN_OPTIONS);

	// $params will hold the default values ($options) overwritten by the values passed ($atts)
	$params = wp_parse_args($atts, $options);

	// Now check the whole thing against the defaults and remove undefined attributes.
	$p = shortcode_atts(array(
		'limit' => CPR_DEFAULT_LIMIT,
		'excerpt' => CPR_DEFAULT_EXCERPT,
		'words' => CPR_DEFAULT_EXCERPT_LENGTH,
		'thumb' => CPR_DEFAULT_THUMB,
		'width' => CPR_DEFAULT_THUMB_WIDTH,
		'height' => CPR_DEFAULT_THUMB_HEIGHT
	), $params);

	return cpr_show($echo, $p['limit'], $p['excerpt'], $p['words'], $p['thumb'], $p['width'], $p['height']);
}

// oi! wait! where are you going? are you sure? 100%? a second thought? come on let's talk about it. oh well.
function cpr_uninstall()
{
	global $wpdb;	
	$wpdb->query($wpdb->prepare("DELETE FROM $wpdb->postmeta WHERE meta_key = '".CI_CPR_POST_RELATED."'"));
}

function _cpr_get_post_types()
{
	// Get the post types available
	$types = array();
	$types = get_post_types($args = array(
		'public'   => true
	), 'objects');

	unset($types['attachment']);
	return $types;
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



add_action('admin_menu', 'cpr_box');
add_action('wp_ajax_cpr-cats', 'cpr_cats');
add_action('save_post', 'cpr_save');
register_uninstall_hook(__FILE__, 'cpr_uninstall');
?>