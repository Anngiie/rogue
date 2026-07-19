<?php
/**
 * Default category archive template.
 *
 * @package oceanwp-child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$term = get_queried_object();

set_query_var( 'term', $term );
set_query_var( 'eyebrow', __( 'Rubrika', 'oceanwp-child' ) );
set_query_var( 'intro', sprintf( __( 'Najnovije vesti, analize i priče iz rubrike %s.', 'oceanwp-child' ), esc_html( $term->name ) ) );

get_template_part( 'partials/category', 'loop' );

get_footer();
