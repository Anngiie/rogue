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
			<div class="vk-search-thumb-placeholder">
				<span><?php echo esc_html( mb_substr( get_the_title(), 0, 1 ) ); ?></span>
			</div>
		<?php endif; ?>
	</a>
</div><!-- .thumbnail -->
