<?php
/*
	settings tab on settings page
*/

/**
 * Declare the Namespace.
 */
namespace azurecurve\ConditionalLinks;

/**
 * Prevent direct access.
 */
if ( ! defined( 'ABSPATH' ) ) {
	die();
}

/**
 * Settings tab.
 */

$tab_settings_label = PLUGIN_NAME . ' ' . esc_html__( 'Settings', 'azrcrv-cl' );
$tab_settings       = '
<table class="form-table azrcrv-settings">

	<tr>

		<th scope="row" colspan="2">

			<label for="explanation">
				' . esc_html__( 'Conditional Links generates anchor tags when the target post or page exists; otherwise outputs plain text. Edit and Add links for admins can be toggled per shortcode group below.', 'azrcrv-cl' ) . '
			</label>

		</th>

	</tr>

	<tr>

		<th scope="row" colspan=2 class="azrcrv-settings-section-heading">

			<h2 class="azrcrv-settings-section-heading">' . esc_html__( 'Conditional Links (cond-link)', 'azrcrv-cl' ) . '</h2>

		</th>

	</tr>

	<tr>

		<th scope="row">
			<label for="cond_link_display_edit_link">
				' . esc_html__( 'Display edit link', 'azrcrv-cl' ) . '
			</label>
		</th>

		<td>
			<input name="cond_link_display_edit_link" type="checkbox" id="cond_link_display_edit_link" value="1" ' . checked( '1', $options['cond_link_display_edit_link'], false ) . ' />
			<label for="cond_link_display_edit_link">
				<span class="description">' . esc_html__( 'Display an Edit link after the link for users with edit_posts capability (applies to all post types).', 'azrcrv-cl' ) . '</span>
			</label>
		</td>

	</tr>

	<tr>

		<th scope="row">
			<label for="cond_link_display_add_link">
				' . esc_html__( 'Display add link', 'azrcrv-cl' ) . '
			</label>
		</th>

		<td>
			<input name="cond_link_display_add_link" type="checkbox" id="cond_link_display_add_link" value="1" ' . checked( '1', $options['cond_link_display_add_link'], false ) . ' />
			<label for="cond_link_display_add_link">
				<span class="description">' . esc_html__( 'Display an Add link when the target does not exist, for users with edit_posts capability (applies to all post types).', 'azrcrv-cl' ) . '</span>
			</label>
		</td>

	</tr>

	<tr>

		<th scope="row" colspan=2 class="azrcrv-settings-section-heading">

			<h2 class="azrcrv-settings-section-heading">' . esc_html__( 'Conditional Page Links (cpl)', 'azrcrv-cl' ) . '</h2>

		</th>

	</tr>

	<tr>

		<th scope="row">
			<label for="display_edit_link">
				' . esc_html__( 'Display edit link', 'azrcrv-cl' ) . '
			</label>
		</th>

		<td>
			<input name="display_edit_link" type="checkbox" id="display_edit_link" value="1" ' . checked( '1', $options['display_edit_link'], false ) . ' />
			<label for="display_edit_link">
				<span class="description">' . esc_html__( 'Display an Edit link after the page link for users with edit_posts capability.', 'azrcrv-cl' ) . '</span>
			</label>
		</td>

	</tr>

	<tr>

		<th scope="row">
			<label for="display_add_link">
				' . esc_html__( 'Display add link', 'azrcrv-cl' ) . '
			</label>
		</th>

		<td>
			<input name="display_add_link" type="checkbox" id="display_add_link" value="1" ' . checked( '1', $options['display_add_link'], false ) . ' />
			<label for="display_add_link">
				<span class="description">' . esc_html__( 'Display an Add link when the target page does not exist, for users with edit_posts capability.', 'azrcrv-cl' ) . '</span>
			</label>
		</td>

	</tr>

	<tr>

		<th scope="row" colspan=2 class="azrcrv-settings-section-heading">

			<h2 class="azrcrv-settings-section-heading">' . esc_html__( 'Conditional Blog Links (cbl)', 'azrcrv-cl' ) . '</h2>

		</th>

	</tr>

	<tr>

		<th scope="row">
			<label for="blog_display_edit_link">
				' . esc_html__( 'Display edit link', 'azrcrv-cl' ) . '
			</label>
		</th>

		<td>
			<input name="blog_display_edit_link" type="checkbox" id="blog_display_edit_link" value="1" ' . checked( '1', $options['blog_display_edit_link'], false ) . ' />
			<label for="blog_display_edit_link">
				<span class="description">' . esc_html__( 'Display an Edit link after the post link for users with edit_posts capability.', 'azrcrv-cl' ) . '</span>
			</label>
		</td>

	</tr>

	<tr>

		<th scope="row">
			<label for="blog_display_add_link">
				' . esc_html__( 'Display add link', 'azrcrv-cl' ) . '
			</label>
		</th>

		<td>
			<input name="blog_display_add_link" type="checkbox" id="blog_display_add_link" value="1" ' . checked( '1', $options['blog_display_add_link'], false ) . ' />
			<label for="blog_display_add_link">
				<span class="description">' . esc_html__( 'Display an Add link when the target post does not exist, for users with edit_posts capability.', 'azrcrv-cl' ) . '</span>
			</label>
		</td>

	</tr>

</table>';
