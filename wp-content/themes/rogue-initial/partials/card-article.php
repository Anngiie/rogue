<?php
/**
 * Article card partial.
 *
 * Must be used inside The Loop.
 *
 * @package oceanwp-child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$categories = get_the_category();
$first_cat  = ! empty( $categories ) && ! is_wp_error( $categories ) ? $categories[0] : null;
?>

<article class="vk-card">
	<a href="<?php the_permalink(); ?>" class="vk-card__thumb" aria-hidden="true">
		<?php if ( has_post_thumbnail() ) : ?>
			<?php the_post_thumbnail( 'medium_large' ); ?>
		<?php else : ?>
			<div style="width:100%;aspect-ratio:16/10;background:var(--vk-grad);display:grid;place-items:center;color:#fff;font-family:Poppins,sans-serif;font-weight:800;">
				<?php echo esc_html( get_bloginfo( 'name' ) ); ?>
			</div>
		<?php endif; ?>
	</a>
	<div class="vk-card__body">
		<?php if ( $first_cat ) : ?>
			<a class="vk-card__cat" href="<?php echo esc_url( get_category_link( $first_cat->term_id ) ); ?>">
				<?php echo esc_html( $first_cat->name ); ?>
			</a>
		<?php endif; ?>
		<h3 class="vk-card__title">
			<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
		</h3>
		<p class="vk-card__excerpt"><?php echo esc_html( vk_trim_excerpt( 18 ) ); ?></p>
		<div class="vk-card__meta">
			<span><?php echo get_the_date(); ?></span>
			<span><?php echo esc_html( get_the_author() ); ?></span>
		</div>
	</div>
</article>
