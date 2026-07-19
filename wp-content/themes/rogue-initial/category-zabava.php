<?php
/**
 * Category: Zabava
 *
 * @package oceanwp-child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$term = get_queried_object();

set_query_var( 'term', $term );
set_query_var( 'eyebrow', __( 'Slobodno vreme', 'oceanwp-child' ) );
set_query_var( 'intro', __( 'Zanimljivosti, lifestyle, događaji i sve što čini dan malo veselijim.', 'oceanwp-child' ) );

get_template_part( 'partials/category', 'loop' );

get_footer();
