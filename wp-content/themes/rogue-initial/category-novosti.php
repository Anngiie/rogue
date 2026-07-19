<?php
/**
 * Category: Novosti
 *
 * @package oceanwp-child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$term = get_queried_object();

set_query_var( 'term', $term );
set_query_var( 'eyebrow', __( 'Najnovije', 'oceanwp-child' ) );
set_query_var( 'intro', __( 'Brze i pouzdane informacije iz zemlje i svijeta. Budite u toku sa najvažnijim događajima dana.', 'oceanwp-child' ) );

get_template_part( 'partials/category', 'loop' );

get_footer();
