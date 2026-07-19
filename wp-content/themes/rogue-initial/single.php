<?php
/**
 * Custom single post template for Viberi news portal.
 *
 * @package oceanwp-child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$categories = get_the_category();
$first_cat  = ! empty( $categories ) && ! is_wp_error( $categories ) ? $categories[0] : null;
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'vk-single-post' ); ?>>

	<!-- Post hero -->
	<header class="vk-single-hero">
		<div class="vk-single-hero__bg" <?php if ( has_post_thumbnail() ) : ?>style="background-image: url('<?php echo esc_url( get_the_post_thumbnail_url( get_the_ID(), 'large' ) ); ?>');"<?php endif; ?>></div>
		<div class="vk-single-hero__overlay"></div>
		<div class="vk-single-hero__inner">
			<div class="vk-container">
				<?php if ( $first_cat ) : ?>
					<a class="vk-cat-badge" href="<?php echo esc_url( get_category_link( $first_cat->term_id ) ); ?>">
						<?php echo esc_html( $first_cat->name ); ?>
					</a>
				<?php endif; ?>
				<h1 class="vk-single-hero__title" itemprop="headline"><?php the_title(); ?></h1>
				<div class="vk-single-hero__meta">
					<span><?php echo get_the_date(); ?></span>
					<span><?php echo esc_html( get_the_author() ); ?></span>
					<span><?php comments_number( __( 'Nema komentara', 'oceanwp-child' ), __( 'Jedan komentar', 'oceanwp-child' ), __( '% komentara', 'oceanwp-child' ) ); ?></span>
				</div>
			</div>
		</div>
	</header>

	<!-- Post content -->
	<div class="vk-single-content">
		<div class="vk-container vk-single-content__inner">
			<div class="vk-single-body">
				<div class="vk-single-entry" itemprop="text">
					<?php
					while ( have_posts() ) :
						the_post();
						the_content();

						wp_link_pages(
							array(
								'before' => '<div class="page-links">' . __( 'Stranice:', 'oceanwp-child' ),
								'after'  => '</div>',
							)
						);
					endwhile;
					?>
				</div>

				<?php the_tags( '<div class="vk-single-tags"><span>' . __( 'Tagovi:', 'oceanwp-child' ) . '</span>', '', '</div>' ); ?>

				<?php get_template_part( 'partials/single/author-bio' ); ?>

				<?php get_template_part( 'partials/single/next-prev' ); ?>

				<?php get_template_part( 'partials/single/related-posts' ); ?>

				<?php comments_template(); ?>
			</div>

			<!-- Sidebar for single post -->
			<aside class="vk-single-sidebar">
				<div class="vk-single-widget">
					<h4 class="vk-single-widget__title"><?php esc_html_e( 'Pretraži', 'oceanwp-child' ); ?></h4>
					<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
						<input type="search" class="search-field" placeholder="<?php esc_attr_e( 'Pretraži vesti…', 'oceanwp-child' ); ?>" value="<?php echo get_search_query(); ?>" name="s" />
						<button type="submit" class="search-submit"><?php esc_html_e( 'Traži', 'oceanwp-child' ); ?></button>
					</form>
				</div>

				<div class="vk-single-widget">
					<h4 class="vk-single-widget__title"><?php esc_html_e( 'Rubrike', 'oceanwp-child' ); ?></h4>
					<ul class="vk-single-widget__list">
						<?php
						wp_list_categories(
							array(
								'title_li'   => '',
								'show_count' => true,
							)
						);
						?>
					</ul>
				</div>

				<div class="vk-single-widget">
					<h4 class="vk-single-widget__title"><?php esc_html_e( 'Najnovije', 'oceanwp-child' ); ?></h4>
					<ul class="vk-single-widget__list vk-single-widget__list--posts">
						<?php
						$recent = new WP_Query(
							array(
								'posts_per_page' => 5,
								'post__not_in'   => array( get_the_ID() ),
							)
						);
						while ( $recent->have_posts() ) :
							$recent->the_post();
							?>
							<li>
								<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
								<span><?php echo get_the_date(); ?></span>
							</li>
							<?php
						endwhile;
						wp_reset_postdata();
						?>
					</ul>
				</div>
			</aside>
		</div>
	</div>

</article>

<?php
get_footer();
