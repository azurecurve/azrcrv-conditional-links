<?php
/*
	instructions tab on settings page
*/

/**
 * Declare the Namespace.
 */
namespace azurecurve\ConditionalLinks;

/**
 * Instructions tab.
 */
$tab_instructions_label = esc_html__( 'Instructions', 'azrcrv-cl' );
$tab_instructions       = '
<table class="form-table azrcrv-settings">

	<tr>

		<th scope="row" colspan=2 class="azrcrv-settings-section-heading">

			<h2 class="azrcrv-settings-section-heading">' . esc_html__( 'Conditional Links (any post type)', 'azrcrv-cl' ) . '</h2>

		</th>

	</tr>

	<tr>

		<td scope="row" colspan=2>

			<p>' . esc_html__( 'Use the [cond-link] shortcode to create a conditional link to any post type. The post_type parameter defaults to post if not supplied.', 'azrcrv-cl' ) . '</p>
			<p><code>[cond-link slug="my-post-slug" /]</code></p>
			<p><code>[cond-link slug="my-page-slug" post_type="page" /]</code></p>
			<p><code>[cond-link slug="my-product-slug" post_type="product" title="Buy Now"]</code></p>
			<p><code>[cond-link slug="my-post-slug"]My Post Title[/cond-link]</code></p>

		</td>

	</tr>

	<tr>

		<th scope="row" colspan=2 class="azrcrv-settings-section-heading">

			<h2 class="azrcrv-settings-section-heading">' . esc_html__( 'Page Links', 'azrcrv-cl' ) . '</h2>

		</th>

	</tr>

	<tr>

		<td scope="row" colspan=2>

			<p>' . esc_html__( 'Use the [cpl] shortcode to create a conditional link to a page. If the page exists a link is created; otherwise the title is output as plain text.', 'azrcrv-cl' ) . '</p>
			<p><code>[cpl slug="my-page-slug" /]</code></p>
			<p><code>[cpl slug="my-page-slug" title="My Page Title" /]</code></p>
			<p><code>[cpl slug="my-page-slug"]My Page Title[/cpl]</code></p>

		</td>

	</tr>

	<tr>

		<th scope="row" colspan=2 class="azrcrv-settings-section-heading">

			<h2 class="azrcrv-settings-section-heading">' . esc_html__( 'Blog Links', 'azrcrv-cl' ) . '</h2>

		</th>

	</tr>

	<tr>

		<td scope="row" colspan=2>

			<p>' . esc_html__( 'Use the [cbl] shortcode to create a conditional link to a post. If the post exists a link is created; otherwise the title is output as plain text.', 'azrcrv-cl' ) . '</p>
			<p><code>[cbl slug="my-post-slug" /]</code></p>
			<p><code>[cbl slug="my-post-slug" title="My Post Title" /]</code></p>
			<p><code>[cbl slug="my-post-slug"]My Post Title[/cbl]</code></p>

		</td>

	</tr>

</table>';
