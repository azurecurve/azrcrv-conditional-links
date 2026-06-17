<?php
/*
	plugin functionality
*/

/**
 * Declare the Namespace.
 */
namespace azurecurve\ConditionalLinks;

/**
 * Check whether shortcode is present and if so enqueue styles.
 */
function check_for_shortcode( $posts, $query ) {
	if ( empty( $posts ) ) {
		return $posts;
	}

	$shortcodes = array(
		'cpl',       'Cpl',       'CPL',
		'cbl',       'Cbl',       'CBL',
		'cond-link', 'Cond-link', 'Cond-Link',, 'COND-LINK',
	);

	$found = false;
	foreach ( $posts as $post ) {
		foreach ( $shortcodes as $shortcode ) {
			if ( has_shortcode( $post->post_content, $shortcode ) ) {
				$found = true;
				break 2;
			}
		}
	}

	if ( $found ) {
		add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\\enqueue_frontend_styles' );
	}

	return $posts;
}

/**
 * Shared function used by cpl, cbl, and cond-link shortcodes.
 *
 * @param string $slug            Post slug to look up.
 * @param string $title           Display text.
 * @param string $post_type       Post type to query.
 * @param string $edit_option_key Options key controlling edit link display.
 * @param string $add_option_key  Options key controlling add link display.
 *
 * @return string HTML output.
 */
function resolve_conditional_link( $slug, $title, $post_type, $edit_option_key, $add_option_key ) {

	$options = get_option_with_defaults( PLUGIN_HYPHEN );

	$output = '';

	$args = array(
		'name'        => $slug,
		'post_type'   => $post_type,
		'post_status' => 'publish',
		'numberposts' => 1,
	);

	$posts = get_posts( $args );

	if ( $posts ) {

		$post = $posts[0];

		$display_title = ( strlen( $title ) > 0 ) ? $title : get_the_title( $post->ID );

		$output = '<a href="' . esc_url( get_the_permalink( $post->ID ) ) . '">' . esc_html( $display_title ) . '</a>';

		if ( current_user_can( 'edit_posts' ) && isset( $options[ $edit_option_key ] ) && $options[ $edit_option_key ] == 1 ) {
			$output .= '&nbsp;<a href="' . esc_url( admin_url( 'post.php?post=' . $post->ID . '&action=edit' ) ) . '">[' . esc_html__( 'Edit', 'azrcrv-cl' ) . ']</a>';
		}
	} else {

		$display_title = ( strlen( $title ) > 0 ) ? $title : $slug;

		$output = esc_html( $display_title );

		if ( current_user_can( 'edit_posts' ) && isset( $options[ $add_option_key ] ) && $options[ $add_option_key ] == 1 ) {
			$output .= '&nbsp;<a href="' . esc_url( admin_url( 'post-new.php?post_type=' . $post_type ) ) . '">[' . esc_html__( 'Add', 'azrcrv-cl' ) . ']</a>';
		}
	}

	return $output;
}

/**
 * CPL shortcode — conditional page link.
 *
 * @param array  $atts    Shortcode attributes.
 * @param string $content Shortcode content.
 *
 * @return string HTML output.
 */
function cpl_shortcode( $atts, $content = null ) {

	$atts = shortcode_atts(
		array(
			'slug'  => '',
			'title' => '',
		),
		$atts,
		'cpl'
	);

	$slug  = sanitize_title( $atts['slug'] );
	$title = sanitize_text_field( $atts['title'] );

	if ( strlen( $slug ) == 0 && ! is_null( $content ) ) {
		$slug = sanitize_title( $content );
	}

	if ( strlen( $title ) == 0 && ! is_null( $content ) ) {
		$title = sanitize_text_field( $content );
	}

	return resolve_conditional_link( $slug, $title, 'page', 'display_edit_link', 'display_add_link' );
}

/**
 * CBL shortcode — conditional blog (post) link.
 *
 * @param array  $atts    Shortcode attributes.
 * @param string $content Shortcode content.
 *
 * @return string HTML output.
 */
function cbl_shortcode( $atts, $content = null ) {

	$atts = shortcode_atts(
		array(
			'slug'  => '',
			'title' => '',
		),
		$atts,
		'cbl'
	);

	$slug  = sanitize_title( $atts['slug'] );
	$title = sanitize_text_field( $atts['title'] );

	if ( strlen( $slug ) == 0 && ! is_null( $content ) ) {
		$slug = sanitize_title( $content );
	}

	if ( strlen( $title ) == 0 && ! is_null( $content ) ) {
		$title = sanitize_text_field( $content );
	}

	return resolve_conditional_link( $slug, $title, 'post', 'blog_display_edit_link', 'blog_display_add_link' );
}

/**
 * Cond-link shortcode — conditional link for any post type.
 *
 * @param array  $atts    Shortcode attributes.
 * @param string $content Shortcode content.
 *
 * @return string HTML output.
 */
function cond_link_shortcode( $atts, $content = null ) {

	$atts = shortcode_atts(
		array(
			'slug'      => '',
			'title'     => '',
			'post_type' => 'post',
		),
		$atts,
		'cond-link'
	);

	$slug      = sanitize_title( $atts['slug'] );
	$title     = sanitize_text_field( $atts['title'] );
	$post_type = sanitize_key( $atts['post_type'] );

	if ( strlen( $slug ) == 0 && ! is_null( $content ) ) {
		$slug = sanitize_title( $content );
	}

	if ( strlen( $title ) == 0 && ! is_null( $content ) ) {
		$title = sanitize_text_field( $content );
	}

	return resolve_conditional_link( $slug, $title, $post_type, 'cond_link_display_edit_link', 'cond_link_display_add_link' );
}
