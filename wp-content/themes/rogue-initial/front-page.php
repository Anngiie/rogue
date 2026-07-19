<?php
/**
 * Custom news portal homepage.
 *
 * @package oceanwp-child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

// Latest featured post.
$featured_query = new WP_Query( array(
	'posts_per_page' => 1,
	'orderby'        => 'date',
	'order'          => 'DESC',
) );

$exclude_ids = $featured_query->have_posts() ? array( $featured_query->posts[0]->ID ) : array();

// Latest news excluding featured.
$latest_query = new WP_Query( array(
	'posts_per_page' => 6,
	'post__not_in'   => $exclude_ids,
	'orderby'        => 'date',
	'order'          => 'DESC',
) );

// Editor's pick: first sticky post, or second latest.
$sticky = get_option( 'sticky_posts' );
$editors_query = new WP_Query( array(
	'posts_per_page' => 3,
	'post__in'       => ! empty( $sticky ) ? $sticky : array( 0 ),
	'orderby'        => 'date',
	'order'          => 'DESC',
) );

if ( ! $editors_query->have_posts() ) {
	$editors_query = new WP_Query( array(
		'posts_per_page' => 3,
		'post__not_in'   => $exclude_ids,
		'offset'         => 6,
		'orderby'        => 'date',
		'order'          => 'DESC',
	) );
}

$news_categories = array(
	'novosti'   => __( 'Novosti', 'oceanwp-child' ),
	'misljenja' => __( 'Mišljenja', 'oceanwp-child' ),
	'sport'     => __( 'Sport', 'oceanwp-child' ),
	'kultura'   => __( 'Kultura', 'oceanwp-child' ),
	'zabava'    => __( 'Zabava', 'oceanwp-child' ),
);
?>

<!-- ============== HERO ============== -->
<section class="vk-hero">
	<div class="vk-hero__inner">
		<?php if ( $featured_query->have_posts() ) : ?>
			<?php while ( $featured_query->have_posts() ) : $featured_query->the_post(); ?>
				<?php
				$categories = get_the_category();
				$first_cat  = ! empty( $categories ) && ! is_wp_error( $categories ) ? $categories[0] : null;
				?>
				<div class="vk-hero__featured">
					<div class="vk-hero__content">
						<span class="vk-hero__eyebrow">⚡ <?php esc_html_e( 'Najvažnija vest', 'oceanwp-child' ); ?></span>
						<h1 class="vk-hero__title">
							<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
						</h1>
						<div class="vk-hero__meta">
							<?php if ( $first_cat ) : ?>
								<a href="<?php echo esc_url( get_category_link( $first_cat->term_id ) ); ?>"><?php echo esc_html( $first_cat->name ); ?></a>
							<?php endif; ?>
							<span><?php echo get_the_date(); ?></span>
							<span><?php echo esc_html( get_the_author() ); ?></span>
						</div>
						<p class="vk-hero__text"><?php echo esc_html( vk_trim_excerpt( 40 ) ); ?></p>
						<div class="vk-hero__actions">
							<a href="<?php the_permalink(); ?>" class="vk-btn">
								<?php esc_html_e( 'Pročitaj više', 'oceanwp-child' ); ?>
								<span aria-hidden="true">→</span>
							</a>
							<a href="#vk-latest" class="vk-btn vk-btn--ghost"><?php esc_html_e( 'Pregledaj vesti', 'oceanwp-child' ); ?></a>
						</div>
					</div>

					<a href="<?php the_permalink(); ?>" class="vk-hero__thumb" aria-hidden="true">
						<?php if ( has_post_thumbnail() ) : ?>
							<?php the_post_thumbnail( 'large' ); ?>
						<?php else : ?>
							<img class="vk-thumb-fallback vk-thumb-fallback--hero" src="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/img/fallback.jpg' ); ?>" alt="" loading="lazy" />
						<?php endif; ?>
						<?php if ( $first_cat ) : ?>
							<span class="vk-cat-badge"><?php echo esc_html( $first_cat->name ); ?></span>
						<?php endif; ?>
					</a>
				</div>
			<?php endwhile; ?>
		<?php else : ?>
			<div class="vk-hero__content">
				<span class="vk-hero__eyebrow">⚡ <?php esc_html_e( 'Dobrodošli', 'oceanwp-child' ); ?></span>
				<h1 class="vk-hero__title"><?php esc_html_e( 'Vaš izvor svježih vijesti.', 'oceanwp-child' ); ?></h1>
				<p class="vk-hero__text"><?php esc_html_e( 'Portal još nema objavljenih vesti. Posetite ?seed_demo=1 da učitate demo sadržaj.', 'oceanwp-child' ); ?></p>
			</div>
		<?php endif; ?>
	</div>
</section>

<!-- ============== CATEGORIES ============== -->
<section class="vk-section vk-section--mist">
	<div class="vk-container">
		<div class="vk-section-head">
			<span class="vk-eyebrow"><?php esc_html_e( 'Istraži', 'oceanwp-child' ); ?></span>
			<h2><?php esc_html_e( 'Rubrike', 'oceanwp-child' ); ?></h2>
			<p><?php esc_html_e( 'Izaberite temu koja vas zanima i pronađite najnovije tekstove.', 'oceanwp-child' ); ?></p>
		</div>

		<div class="vk-categories__grid">
			<?php foreach ( $news_categories as $slug => $label ) :
				$term = get_category_by_slug( $slug );
				if ( ! $term || is_wp_error( $term ) ) {
					continue;
				}
				?>
				<a class="vk-cat" href="<?php echo esc_url( get_category_link( $term->term_id ) ); ?>">
					<span class="vk-cat__bg"></span>
					<span class="vk-cat__overlay"></span>
					<span class="vk-cat__arrow">→</span>
					<span class="vk-cat__body">
						<span class="vk-cat__name"><?php echo esc_html( $term->name ); ?></span>
						<span class="vk-cat__count"><?php echo esc_html( $term->count ); ?> <?php esc_html_e( 'članaka', 'oceanwp-child' ); ?></span>
					</span>
				</a>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<!-- ============== LATEST NEWS ============== -->
<section id="vk-latest" class="vk-section">
	<div class="vk-container">
		<div class="vk-section-head">
			<span class="vk-eyebrow"><?php esc_html_e( 'Sveže', 'oceanwp-child' ); ?></span>
			<h2><?php esc_html_e( 'Najnovije vesti', 'oceanwp-child' ); ?></h2>
			<p><?php esc_html_e( 'Pregled najsvežijih objava iz svih rubrika.', 'oceanwp-child' ); ?></p>
		</div>

		<?php if ( $latest_query->have_posts() ) : ?>
			<div class="vk-posts-grid">
				<?php while ( $latest_query->have_posts() ) : $latest_query->the_post(); ?>
					<?php get_template_part( 'partials/card', 'article' ); ?>
				<?php endwhile; ?>
			</div>
		<?php else : ?>
			<p style="text-align:center; color: var(--vk-muted);">
				<?php esc_html_e( 'Trenutno nema dodatnih vesti.', 'oceanwp-child' ); ?>
			</p>
		<?php endif; ?>
	</div>
</section>

<!-- ============== EDITOR'S PICK ============== -->
<?php if ( $editors_query->have_posts() ) : ?>
<section class="vk-section vk-section--ink">
	<div class="vk-container">
		<div class="vk-section-head">
			<span class="vk-eyebrow"><?php esc_html_e( 'Izbor urednika', 'oceanwp-child' ); ?></span>
			<h2><?php esc_html_e( 'Ne propustite', 'oceanwp-child' ); ?></h2>
			<p><?php esc_html_e( 'Tekstovi koje naš tim posebno ističe.', 'oceanwp-child' ); ?></p>
		</div>

		<div class="vk-posts-grid vk-posts-grid--3">
			<?php while ( $editors_query->have_posts() ) : $editors_query->the_post(); ?>
				<?php get_template_part( 'partials/card', 'article' ); ?>
			<?php endwhile; ?>
		</div>
	</div>
</section>
<?php endif; ?>

<?php
wp_reset_postdata();
get_footer();
