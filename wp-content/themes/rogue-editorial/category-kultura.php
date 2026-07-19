<?php
/**
 * Category: Kultura
 *
 * @package oceanwp-child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$term = get_queried_object();

set_query_var( 'term', $term );
set_query_var( 'eyebrow', __( 'Scena', 'oceanwp-child' ) );
set_query_var( 'intro', __( 'Film, muzika, književnost, pozorište i likovna umjetnost na jednom mjestu.', 'oceanwp-child' ) );

get_template_part( 'partials/category', 'loop' );

get_footer();
