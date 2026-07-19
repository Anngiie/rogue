<?php
/**
 * Category: Mišljenja
 *
 * @package oceanwp-child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$term = get_queried_object();

set_query_var( 'term', $term );
set_query_var( 'eyebrow', __( 'Pogledi', 'oceanwp-child' ) );
set_query_var( 'intro', __( 'Kolumne, analize i autorski tekstovi koji nude drugačiji ugao gledanja na aktuelne teme.', 'oceanwp-child' ) );

get_template_part( 'partials/category', 'loop' );

get_footer();
