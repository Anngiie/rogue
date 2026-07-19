<?php
/**
 * Shared category archive loop: featured post + regular grid.
 *
 * Expected variables:
 *   $term       WP_Term object for the current category.
 *   $eyebrow    (optional) string to override default eyebrow.
 *   $intro      (optional) string to override default intro.
 *
 * @package oceanwp-child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$eyebrow = isset( $eyebrow ) ? $eyebrow : __( 'Rubrika', 'oceanwp-child' );
$intro   = isset( $intro ) ? $intro : sprintf( __( 'Najnovije vesti, analize i priče iz rubrike %s.', 'oceanwp-child' ), esc_html( $term->name ) );

// Fetch featured post: sticky in this category, otherwise latest.
$sticky = get_option( 'sticky_posts' );
$featured_query = new WP_Query( array(
	'cat'            => $term->term_id,
	'posts_per_page' => 1,
	'post__in'       => ! empty( $sticky ) ? $sticky : array( 0 ),
	'orderby'        => 'date',
	'order'          => 'DESC',
) );

if ( ! $featured_query->have_posts() ) {
	$featured_query = new WP_Query( array(
		'cat'            => $term->term_id,
		'posts_per_page' => 1,
		'orderby'        => 'date',
		'order'          => 'DESC',
	) );
}

$exclude_ids = $featured_query->have_posts() ? array( $featured_query->posts[0]->ID ) : array();

$posts_query = new WP_Query( array(
	'cat'              => $term->term_id,
	'posts_per_page'   => 9,
	'post__not_in'     => $exclude_ids,
	'orderby'          => 'date',
	'order'            => 'DESC',
) );
?>

<!-- Archive hero -->
<section class="vk-archive-hero" data-category="<?php echo esc_attr( $term->name ); ?>">
	<div class="vk-archive-hero__inner">
		<span class="vk-eyebrow"><?php echo esc_html( $eyebrow ); ?></span>
		<h1><?php echo esc_html( $term->name ); ?></h1>
		<p><?php echo esc_html( $intro ); ?></p>
	</div>
</section>

<?php if ( $featured_query->have_posts() ) : ?>
<section class="vk-archive-featured">
	<div class="vk-container">
		<div class="vk-section-head vk-section-head--left">
			<span class="vk-eyebrow"><?php esc_html_e( 'Istaknuto', 'oceanwp-child' ); ?></span>
			<h2><?php esc_html_e( 'Najvažnije danas', 'oceanwp-child' ); ?></h2>
		</div>

		<?php while ( $featured_query->have_posts() ) : $featured_query->the_post(); ?>
			<article class="vk-card vk-card--featured">
				<a href="<?php the_permalink(); ?>" class="vk-card__thumb" aria-hidden="true">
					<?php if ( has_post_thumbnail() ) : ?>
						<?php the_post_thumbnail( 'large' ); ?>
					<?php else : ?>
						<img class="vk-thumb-fallback vk-thumb-fallback--featured" src="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/img/fallback.jpg' ); ?>" alt="" loading="lazy" />
					<?php endif; ?>
				</a>
				<div class="vk-card__body">
					<a class="vk-card__cat" href="<?php echo esc_url( get_category_link( $term->term_id ) ); ?>"><?php echo esc_html( $term->name ); ?></a>
					<h3 class="vk-card__title">
						<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
					</h3>
					<p class="vk-card__excerpt"><?php echo esc_html( vk_trim_excerpt( 35 ) ); ?></p>
					<div class="vk-card__meta">
						<span><?php echo get_the_date(); ?></span>
						<span><?php echo esc_html( get_the_author() ); ?></span>
					</div>
				</div>
			</article>
		<?php endwhile; ?>
	</div>
</section>
<?php endif; ?>

<section class="vk-section">
	<div class="vk-container">
		<div class="vk-section-head vk-section-head--left">
			<span class="vk-eyebrow"><?php esc_html_e( 'Najnovije', 'oceanwp-child' ); ?></span>
			<h2><?php esc_html_e( 'Još vesti', 'oceanwp-child' ); ?></h2>
		</div>

		<?php if ( $posts_query->have_posts() ) : ?>
			<div class="vk-posts-grid vk-posts-grid--3">
				<?php while ( $posts_query->have_posts() ) : $posts_query->the_post(); ?>
					<?php get_template_part( 'partials/card', 'article' ); ?>
				<?php endwhile; ?>
			</div>
		<?php else : ?>
			<p style="text-align:center; color: var(--vk-muted);">
				<?php esc_html_e( 'Trenutno nema dodatnih vesti u ovoj rubrici.', 'oceanwp-child' ); ?>
			</p>
		<?php endif; ?>
	</div>
</section>

<?php
wp_reset_postdata();
