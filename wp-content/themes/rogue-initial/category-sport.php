<?php
/**
 * Category: Sport
 *
 * @package oceanwp-child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$term = get_queried_object();

set_query_var( 'term', $term );
set_query_var( 'eyebrow', __( 'Teren', 'oceanwp-child' ) );
set_query_var( 'intro', __( 'Rezultati, izveštaji, intervjui i komentari iz svijeta sporta.', 'oceanwp-child' ) );

get_template_part( 'partials/category', 'loop' );

get_footer();
