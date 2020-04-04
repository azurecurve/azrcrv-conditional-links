<?php
/**
 * ------------------------------------------------------------------------------
 * Plugin Name: Conditional Links
 * Description: The cpl shortcode allows links to be generated when the page exists; allows index or other pages to be built before child or other linked pages. Adds anchor tags to valid links otherwise outputs plain text.
 * Version: 1.1.4
 * Author: azurecurve
 * Author URI: https://development.azurecurve.co.uk/classicpress-plugins/
 * Plugin URI: https://development.azurecurve.co.uk/classicpress-plugins/conditional-links/
 * Text Domain: conditional-links
 * Domain Path: /languages
 * ------------------------------------------------------------------------------
 * This is free software released under the terms of the General Public License,
 * version 2, or later. It is distributed WITHOUT ANY WARRANTY; without even the
 * implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. Full
 * text of the license is available at https://www.gnu.org/licenses/gpl-2.0.html.
 * ------------------------------------------------------------------------------
 */

// Prevent direct access.
if (!defined('ABSPATH')){
	die();
}

// include plugin menu
require_once(dirname( __FILE__).'/pluginmenu/menu.php');
register_activation_hook(__FILE__, 'azrcrv_create_plugin_menu_cl');

// include update client
require_once(dirname(__FILE__).'/libraries/updateclient/UpdateClient.class.php');

/**
 * Setup registration activation hook, actions, filters and shortcodes.
 *
 * @since 1.0.0
 *
 */
// add actions
register_activation_hook(__FILE__, 'azrcrv_cl_set_default_options');

// add actions
add_action('admin_menu', 'azrcrv_cl_create_admin_menu');
add_action('admin_post_azrcrv_cl_save_options', 'azrcrv_cl_save_options');
add_action('network_admin_menu', 'azrcrv_cl_create_network_admin_menu');
add_action('network_admin_edit_azrcrv_cl_save_network_options', 'azrcrv_cl_save_network_options');
add_action('wp_enqueue_scripts', 'azrcrv_cl_load_css');
//add_action('the_posts', 'azrcrv_cl_check_for_shortcode');
add_action('plugins_loaded', 'azrcrv_cl_load_languages');

// add filters
add_filter('plugin_action_links', 'azrcrv_cl_add_plugin_action_link', 10, 2);

// add shortcodes
add_shortcode('cpl', 'azc_cpl_shortcode');
add_shortcode('Cpl', 'azc_cpl_shortcode');
add_shortcode('CPL', 'azc_cpl_shortcode');
add_shortcode('cbl', 'azc_cbl_shortcode');
add_shortcode('Cbl', 'azc_cbl_shortcode');
add_shortcode('CBL', 'azc_cbl_shortcode');

/**
 * Load language files.
 *
 * @since 1.0.0
 *
 */
function azrcrv_cl_load_languages() {
    $plugin_rel_path = basename(dirname(__FILE__)).'/languages';
    load_plugin_textdomain('conditional-links', false, $plugin_rel_path);
}

/**
 * Check if shortcode on current page and then load css and jqeury.
 *
 * @since 1.0.0
 *
 */
function azrcrv_cl_check_for_shortcode($posts){
    if (empty($posts)){
        return $posts;
	}
	
	
	// array of shortcodes to search for
	$shortcodes = array(
						'cpl','Cpl','CPL','cbl','Cbl','CBL'
						);
	
    // loop through posts
    $found = false;
    foreach ($posts as $post){
		// loop through shortcodes
		foreach ($shortcodes as $shortcode){
			// check the post content for the shortcode
			if (has_shortcode($post->post_content, $shortcode)){
				$found = true;
				// break loop as shortcode found in page content
				break 2;
			}
		}
	}
 
    if ($found){
		// as shortcode found call functions to load css and jquery
        azrcrv_cl_load_css();
    }
    return $posts;
}

/**
 * Load CSS.
 *
 * @since 1.0.0
 *
 */
function azrcrv_cl_load_css(){
	wp_enqueue_style('azrcrv-cl', plugins_url('assets/css/style.css', __FILE__), '', '1.0.0');
}

/**
 * Set default options for plugin.
 *
 * @since 1.0.0
 *
 */
function azrcrv_cl_set_default_options($networkwide){
	
	$option_name = 'azrcrv-cl';
	$old_option_name = 'azc_cl';
	
	$new_options = array(
						'display_edit_link' => 1,
						'display_add_link' => 1,
						'blog_display_edit_link' => 1,
						'blog_display_add_link' => 1,
			);
	
	// set defaults for multi-site
	if (function_exists('is_multisite') && is_multisite()){
		// check if it is a network activation - if so, run the activation function for each blog id
		if ($networkwide){
			global $wpdb;

			$blog_ids = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");
			$original_blog_id = get_current_blog_id();

			foreach ($blog_ids as $blog_id){
				switch_to_blog($blog_id);
				
				azrcrv_cl_update_options($option_name, $new_options, false, $old_option_name);
			}

			switch_to_blog($original_blog_id);
		}else{
			azrcrv_cl_update_options( $option_name, $new_options, false, $old_option_name);
		}
		if (get_site_option($option_name) === false){
			azrcrv_cl_update_options($option_name, $new_options, true, $old_option_name);
		}
	}
	//set defaults for single site
	else{
		azrcrv_cl_update_options($option_name, $new_options, false, $old_option_name);
	}
}

/**
 * Update options.
 *
 * @since 1.1.4
 *
 */
function azrcrv_cl_update_options($option_name, $new_options, $is_network_site, $old_option_name){
	if ($is_network_site == true){
		if (get_site_option($option_name) === false){
			if (get_site_option($old_option_name) === false){
				add_site_option($option_name, $new_options);
			}else{
				add_site_option($option_name, azrcrv_cl_update_default_options($new_options, get_site_option($old_option_name)));
			}
		}else{
			update_site_option($option_name, azrcrv_cl_update_default_options($new_options, get_site_option($option_name)));
		}
	}else{
		if (get_option($option_name) === false){
			if (get_option($old_option_name) === false){
				add_option($option_name, $new_options);
			}else{
				add_option($option_name, azrcrv_cl_update_default_options($new_options, get_option($old_option_name)));
			}
		}else{
			update_option($option_name, azrcrv_cl_update_default_options($new_options, get_option($option_name)));
		}
	}
}


/**
 * Add default options to existing options.
 *
 * @since 1.1.4
 *
 */
function azrcrv_cl_update_default_options( &$default_options, $current_options ) {
    $default_options = (array) $default_options;
    $current_options = (array) $current_options;
    $updated_options = $current_options;
    foreach ($default_options as $key => &$value) {
        if (is_array( $value) && isset( $updated_options[$key ])){
            $updated_options[$key] = azrcrv_cl_update_default_options($value, $updated_options[$key]);
        } else {
            $updated_options[$key] = $value;
        }
    }
    return $updated_options;
}

/**
 * Add Conditional Links action link on plugins page.
 *
 * @since 1.0.0
 *
 */
function azrcrv_cl_add_plugin_action_link($links, $file){
	static $this_plugin;

	if (!$this_plugin){
		$this_plugin = plugin_basename(__FILE__);
	}

	if ($file == $this_plugin){
		$settings_link = '<a href="'.get_bloginfo('wpurl').'/wp-admin/admin.php?page=azrcrv-cl"><img src="'.plugins_url('/pluginmenu/images/Favicon-16x16.png', __FILE__).'" style="padding-top: 2px; margin-right: -5px; height: 16px; width: 16px;" alt="azurecurve" />'.esc_html__('Settings' ,'conditional-links').'</a>';
		array_unshift($links, $settings_link);
	}

	return $links;
}

/**
 * Add to menu.
 *
 * @since 1.0.0
 *
 */
function azrcrv_cl_create_admin_menu(){
	//global $admin_page_hooks;
	
	add_submenu_page("azrcrv-plugin-menu"
						,esc_html__("Conditional Links Settings", "conditional-links")
						,esc_html__("Conditional Links", "conditional-links")
						,'manage_options'
						,'azrcrv-cl'
						,'azrcrv_cl_display_options');
}

/**
 * Display Settings page.
 *
 * @since 1.0.0
 *
 */
function azrcrv_cl_display_options(){
	if (!current_user_can('manage_options')){
        wp_die(esc_html__('You do not have sufficient permissions to access this page.', 'conditional-links'));
    }
	
	// Retrieve plugin configuration options from database
	$options = get_option('azrcrv-cl');
	?>
	<div id="azrcrv-cl-general" class="wrap">
		<fieldset>
			<h1><?php echo esc_html(get_admin_page_title()); ?></h1>
			<?php if(isset($_GET['options-updated'])){ ?>
				<div class="notice notice-success is-dismissible">
					<p><strong><?php esc_html_e('Site settings have been saved.', 'conditional-links') ?></strong></p>
				</div>
			<?php } ?>
			
			<form method="post" action="admin-post.php">
				<input type="hidden" name="action" value="azrcrv_cl_save_options" />
				<input name="page_options" type="hidden" value="display_add_link,display_edit_link" />
				
				<!-- Adding security through hidden referrer field -->
				<?php wp_nonce_field('azrcrv-cl-nonce', 'azrcrv-cl-nonce'); ?>
				
					<table class="form-table">
					
					<tr>
						<th scope="row" colspan="2">
							<label for="explanation">
								<?php esc_html_e('Conditional links to pages are added using the <strong>cpl</strong> shortcode and conditional links to posts are added using the <strong>cbl</strong> shortcodes. Valid formats for both cpl and cbl shortcodes are:', 'conditional-links'); ?>
								<ul><li>[cpl slug="page slug"]</li>
								<li>[cpl slug="page slug"]text to display[/cpl]</li>
								<li>[cpl title="page title"]text to display[/cpl]</li></ul>
							</label>
						</th>
					</tr>
					
					<tr><th scope="row"><h2><?php esc_html_e("Page Links", "conditional-links"); ?></h2></th><td> </td></tr>
					
					<tr><th scope="row"><?php esc_html_e('Display Add Link?', 'conditional-links'); ?></th><td>
					
						<fieldset><legend class="screen-reader-text"><span><?php esc_html_e('Display Add/Edit Link?', 'conditional-links'); ?></span></legend>
						<label for="display_add_link"><input name="display_add_link" type="checkbox" id="display_add_link" value="1" <?php checked('1', $options['display_add_link']); ?> /></label>
						</fieldset>
					</td></tr>
				
					<tr><th scope="row"><?php esc_html_e('Display Edit Link?', 'conditional-links'); ?></th><td>
						<fieldset><legend class="screen-reader-text"><span><?php esc_html_e('Display Add/Edit Link?', 'conditional-links'); ?></span></legend>
						<label for="display_edit_link"><input name="display_edit_link" type="checkbox" id="display_edit_link" value="1" <?php checked('1', $options['display_edit_link']); ?> /></label>
						</fieldset>
					</td></tr>
					
					<tr><th scope="row"><h2><?php esc_html_e("Blog Links", "conditional-links"); ?></h2></th><td> </td></tr>
					
					<tr><th scope="row"><?php esc_html_e('Display Add Link?', 'conditional-links'); ?></th><td>
						<fieldset><legend class="screen-reader-text"><span><?php esc_html_e('Display Add/Edit Link?', 'conditional-links'); ?></span></legend>
						<label for="blog_display_add_link"><input name="blog_display_add_link" type="checkbox" id="blog_display_add_link" value="1" <?php checked('1', $options['blog_display_add_link']); ?> /></label>
						</fieldset>
					</td></tr>
				
					<tr><th scope="row"><?php esc_html_e('Display Edit Link?', 'conditional-links'); ?></th><td>
						<fieldset><legend class="screen-reader-text"><span><?php esc_html_e('Display Add/Edit Link?', 'conditional-links'); ?></span></legend>
						<label for="blog_display_edit_link"><input name="blog_display_edit_link" type="checkbox" id="blog_display_edit_link" value="1" <?php checked('1', $options['blog_display_edit_link']); ?> /></label>
						</fieldset>
					</td></tr>
				</table>
				<input type="submit" value="<?php esc_html_e('Submit', 'conditional-links'); ?>" class="button-primary"/>
			</form>
		</fieldset>
	</div>
	<?php
}

/**
 * Save settings.
 *
 * @since 1.0.0
 *
 */
function azrcrv_cl_save_options(){
	// Check that user has proper security level
	if (!current_user_can('manage_options')){
		$error = new WP_Error('not_found', esc_html__('You do not have sufficient permissions to perform this action.' , 'comment-validator'), array('response' => '200'));
		if(is_wp_error($error)){
			wp_die($error, '', $error->get_error_data());
		}
    }
	
	// Check that nonce field created in configuration form is present
	if (! empty($_POST) && check_admin_referer('azrcrv-cl-nonce', 'azrcrv-cl-nonce')){
		// Retrieve original plugin options array
		$options = get_option('azrcrv-cpl');
		
		$option_name = 'display_add_link';
		if (isset($_POST[$option_name])){
			$options[$option_name] = 1;
		}else{
			$options[$option_name] = 0;
		}
		
		$option_name = 'display_edit_link';
		if (isset($_POST[$option_name])){
			$options[$option_name] = 1;
		}else{
			$options[$option_name] = 0;
		}
		
		$option_name = 'blog_display_add_link';
		if (isset($_POST[$option_name])){
			$options[$option_name] = 1;
		}else{
			$options[$option_name] = 0;
		}
		
		$option_name = 'blog_display_edit_link';
		if (isset($_POST[$option_name])){
			$options[$option_name] = 1;
		}else{
			$options[$option_name] = 0;
		}
		
		// Store updated options array to database
		update_option('azc_cpl', $options);
		
		// Redirect the page to the configuration form that was processed
		wp_redirect(add_query_arg('page', 'azc-cl&options-updated', admin_url('admin.php')));
		exit;
	}
}

/**
 * Add to Network menu.
 *
 * @since 1.0.0
 *
 */
function azrcrv_cl_create_network_admin_menu(){
	if (function_exists('is_multisite') && is_multisite()){
		add_submenu_page(
						'settings.php'
						,esc_html__("Conditional Links Settings", "conditional-links")
						,esc_html__("Conditional Links", "conditional-links")
						,'manage_network_options'
						,'azrcrv-cl'
						,'azrcrv_cl_network_settings'
						);
	}
}

/**
 * Display network settings.
 *
 * @since 1.0.0
 *
 */
function azrcrv_cl_network_settings(){
	if(!current_user_can('manage_network_options')) wp_die(esc_html__('You do not have permissions to perform this action', 'conditional-links'));
	$options = get_site_option('azrcrv-cl');

	?>
	<div id="azrcrv-cl-general" class="wrap">
		<fieldset>
			<h1><?php echo esc_html(get_admin_page_title()); ?></h1>
			<form method="post" action="admin-post.php">
				<input type="hidden" name="action" value="azrcrv_cl_save_network_options" />
				<input name="page_options" type="hidden" value="smallest, largest, number" />
				
				<!-- Adding security through hidden referrer field -->
				<?php wp_nonce_field('azrcrv-cl-nonce', 'azrcrv-cl-nonce'); ?>
				<table class="form-table">
				
				<tr><th scope="row"><label for="min_length"><?php esc_html_e('Minimum Length', 'conditional-links'); ?></label></th><td>
					<input type="text" name="min_length" value="<?php echo esc_html(stripslashes($options['min_length'])); ?>" class="small-text" />
					<p class="description"><?php esc_html_e('Minimum comment length; set to 0 for no minimum', 'conditional-links'); ?></p>
				</td></tr>
				
				<tr><th scope="row"><label for="max_length"><?php esc_html_e('Maximum Length', 'conditional-links'); ?></label></th><td>
					<input type="text" name="max_length" value="<?php echo esc_html(stripslashes($options['max_length'])); ?>" class="small-text" />
					<p class="description"><?php esc_html_e('Maximum comment length; set to 0 for no maximum', 'conditional-links'); ?></p>
				</td></tr>
				
				<tr><th scope="row"><label for="mod_length"><?php esc_html_e('Moderation Length', 'conditional-links'); ?></label></th><td>
					<input type="text" name="mod_length" value="<?php echo esc_html(stripslashes($options['mod_length'])); ?>" class="small-text" />
					<p class="description"><?php esc_html_e('Moderation comment length; set to 0 for no moderation', 'conditional-links'); ?></p>
				</td></tr>
				
				</table>
				<input type="submit" value="Submit" class="button-primary"/>
			</form>
		</fieldset>
	</div>
	<?php
}

/**
 * Save network settings.
 *
 * @since 1.0.0
 *
 */
function azrcrv_cl_save_network_options(){     
	if(!current_user_can('manage_network_options')){
		wp_die(esc_html__('You do not have permissions to perform this action', 'conditional-links'));
	}
	
	if (! empty($_POST) && check_admin_referer('azrcrv-cl-nonce', 'azrcrv-cl-nonce')){
		// Retrieve original plugin options array
		$options = get_site_option('azrcrv-cl');
		$option_name = 'min_length';
		if (isset($_POST[$option_name])){
			$options[$option_name] = sanitize_text_field(intval($_POST[$option_name]));
		}
		
		$option_name = 'max_length';
		if (isset($_POST[$option_name])){
			$options[$option_name] = sanitize_text_field(intval($_POST[$option_name]));
		}
		
		$option_name = 'mod_length';
		if (isset($_POST[$option_name])){
			$options[$option_name] = sanitize_text_field(intval($_POST[$option_name]));
		}
		
		update_site_option('azrcrv-cl', $options);

		wp_redirect(network_admin_url('settings.php?page=azrcrv-cl&settings-updated'));
		exit;  
	}
}

/**
 * Display Conditional Page Link in shortcode.
 *
 * @since 1.0.0
 *
 */
function azc_cpl_shortcode($atts, $content = null){
	$args = shortcode_atts(array(
		'slug' => '',
	), $atts);
	$slug = $args['slug'];
	
	$slug = sanitize_text_field($slug);
	if (strlen($slug)==0){
		$slug=sanitize_text_field($content);
	}
	
	global $wpdb;
	
	$options = get_option('azc_cpl');
	
	$page_url = trailingslashit(get_bloginfo('url'));

	$sql = $wpdb->prepare("SELECT ID,post_title, post_name, post_status FROM ".$wpdb->prefix."posts WHERE post_status in ('publish') AND post_type = 'page' AND post_name='%s' limit 0,1", sanitize_title($slug));
	
	$return = '';
	$the_page = $wpdb->get_row($sql);
	if ($the_page){
		$return .= "<a href='".get_the_permalink($the_page->ID)."' class='azrcrv-cl-'>".$the_page->post_title."</a>";
		if (current_user_can('edit_posts') and $options['display_edit_link'] == 1){
			if ($the_page->post_status == 'publish'){
				$return .= '&nbsp;<a href="'.$page_url.'wp-admin/post.php?post='.$the_page->ID.'&action=edit" class="azrcrv-cl-_admin">['.esc_html__('Edit','azc_cl').']</a>';
			}
		}
	}else{
		$return .= $slug."</a>";
		if (current_user_can('edit_posts') and $options['display_add_link'] == 1){
			$return .= '&nbsp;<a href="'.$page_url.'wp-admin/post-new.php?post_type=page" class="azrcrv-cl-_admin">['.esc_html__('Add','azc_cl').']</a>';
		}
	}
	return $return;
}

/**
 * Display Conditional Post Link in shortcode.
 *
 * @since 1.0.0
 *
 */

function azc_cbl_shortcode($atts, $content = null){
	$args = shortcode_atts(array(
		'slug' => '',
		'title' => '',
	), $atts);
	$slug = $args['slug'];
	$title = $args['title'];
	
	$slug = sanitize_text_field($slug);
	if (strlen($slug)==0){
		$slug=sanitize_text_field($content);
	}
	
	$title = sanitize_text_field($title);
	
	global $wpdb;
	
	$options = get_option('azc_cpl');
	
	$page_url = trailingslashit(get_bloginfo('url'));

	$sql = $wpdb->prepare("SELECT ID,post_title, post_name, post_status FROM ".$wpdb->prefix."posts WHERE post_status in ('publish') AND post_type = 'post' AND post_name='%s' limit 0,1", sanitize_title($slug));
	
	$return = '';
	$the_page = $wpdb->get_row($sql);
	if ($the_page){
		if (strlen($title) == 0){
			$title = $the_page->post_title;
		}
		$return .= "<a href='".get_the_permalink($the_page->ID)."' class='azrcrv-cl-'>".$title."</a>";
		if (current_user_can('edit_posts') and $options['display_edit_link'] == 1){
			if ($the_page->post_status == 'publish'){
				$return .= '&nbsp;<a href="'.$page_url.'wp-admin/post.php?post='.$the_page->ID.'&action=edit" class="azrcrv-cl-_admin">['.esc_html__('Edit','azc_cl').']</a>';
			}
		}
	}else{
		$return .= $slug."</a>";
		if (current_user_can('edit_posts') and $options['display_add_link'] == 1){
			$return .= '&nbsp;<a href="'.$page_url.'wp-admin/post-new.php?post_type=page" class="azrcrv-cl-_admin">['.esc_html__('Add','azc_cl').']</a>';
		}
	}
	return $return;
}

?>