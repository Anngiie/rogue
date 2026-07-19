<?php
/**
 * Search result page entry thumbnail with placeholder.
 * Overrides OceanWP parent partial.
 *
 * @package oceanwp-child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$img_args = array(
	'alt' => get_the_title(),
);
if ( function_exists( 'oceanwp_get_schema_markup' ) && oceanwp_get_schema_markup( 'image' ) ) {
	$img_args['itemprop'] = 'image';
}
?>

<div class="thumbnail">
	<a href="<?php the_permalink(); ?>" class="thumbnail-link">
		<?php if ( has_post_thumbnail() ) : ?>
			<?php the_post_thumbnail( 'thumbnail', $img_args ); ?>
		<?php else : ?>
			<img class="vk-thumb-fallback vk-thumb-fallback--thumb" src="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/img/fallback.jpg' ); ?>" alt="" loading="lazy" />
		<?php endif; ?>
	</a>
</div><!-- .thumbnail -->
