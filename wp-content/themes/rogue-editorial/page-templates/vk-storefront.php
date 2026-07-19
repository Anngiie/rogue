<?php
/**
 * Template Name: Vibrant Storefront
 * Description:  Bold & vibrant e-commerce homepage with hero, categories, featured products, promo banner, and newsletter.
 *
 * @package oceanwp-child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<!-- ============== HERO ============== -->
<section class="vk-hero">
	<div class="vk-hero__inner">
		<div class="vk-hero__content">
			<span class="vk-hero__eyebrow"><?php esc_html_e( 'New season drop', 'oceanwp-child' ); ?></span>
			<h1 class="vk-hero__title">
				Gear that hits <span class="grad">different.</span>
			</h1>
			<p class="vk-hero__text">
				Bold pieces for bold people. Shop our hand-picked collection of apparel,
				accessories and gadgets — built to stand out, priced to keep you coming back.
			</p>
			<div class="vk-hero__actions">
				<a href="<?php echo esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ); ?>" class="vk-btn">
					Shop the collection
					<span aria-hidden="true">→</span>
				</a>
				<a href="#vk-categories" class="vk-btn vk-btn--ghost">Browse categories</a>
			</div>

			<div class="vk-hero__stats">
				<div class="vk-hero__stat">
					<strong>5k+</strong>
					<span>Happy customers</span>
				</div>
				<div class="vk-hero__stat">
					<strong>200+</strong>
					<span>Products in stock</span>
				</div>
				<div class="vk-hero__stat">
					<strong>4.9★</strong>
					<span>Average rating</span>
				</div>
			</div>
		</div>

		<div class="vk-hero__visual" aria-hidden="true">
			<div class="vk-hero__card vk-hero__card--1">
				<div class="vk-hero__card-img"></div>
				<div class="vk-hero__card-body">
					<h4>Neon Hoodie</h4>
					<div class="price">$59.00</div>
				</div>
			</div>
			<div class="vk-hero__card vk-hero__card--2">
				<div class="vk-hero__card-img"></div>
				<div class="vk-hero__card-body">
					<h4>Aurora Sneakers</h4>
					<div class="price">$89.00</div>
				</div>
			</div>
			<div class="vk-hero__card vk-hero__card--3">
				<div class="vk-hero__card-img"></div>
				<div class="vk-hero__card-body">
					<h4>Sun Cap</h4>
					<div class="price">$24.00</div>
				</div>
			</div>
		</div>
	</div>
</section>

<!-- ============== FEATURE STRIP ============== -->
<section class="vk-features">
	<div class="vk-container">
		<div class="vk-features__grid">
			<div class="vk-feature">
				<div class="vk-feature__icon vk-feature__icon--p">🚚</div>
				<div>
					<h4 class="vk-feature__title">Free shipping</h4>
					<p class="vk-feature__text">On all orders over $50</p>
				</div>
			</div>
			<div class="vk-feature">
				<div class="vk-feature__icon vk-feature__icon--h">↩</div>
				<div>
					<h4 class="vk-feature__title">30-day returns</h4>
					<p class="vk-feature__text">No questions asked</p>
				</div>
			</div>
			<div class="vk-feature">
				<div class="vk-feature__icon vk-feature__icon--a">🔒</div>
				<div>
					<h4 class="vk-feature__title">Secure checkout</h4>
					<p class="vk-feature__text">Encrypted payments</p>
				</div>
			</div>
			<div class="vk-feature">
				<div class="vk-feature__icon vk-feature__icon--p">💬</div>
				<div>
					<h4 class="vk-feature__title">24/7 support</h4>
					<p class="vk-feature__text">We're always here</p>
				</div>
			</div>
		</div>
	</div>
</section>

<!-- ============== SHOP BY CATEGORY ============== -->
<section id="vk-categories" class="vk-section">
	<div class="vk-container">
		<div class="vk-section-head">
			<span class="vk-eyebrow">Find your vibe</span>
			<h2>Shop by category</h2>
			<p>Pick a lane and start exploring — fresh drops added every week.</p>
		</div>

		<?php
		$cats = get_terms( array(
			'taxonomy'   => 'product_cat',
			'hide_empty' => false,
			'number'     => 4,
		) );

		if ( ! empty( $cats ) && ! is_wp_error( $cats ) ) : ?>
			<div class="vk-categories__grid">
				<?php foreach ( $cats as $cat ) :
					$term_link = get_term_link( $cat ); ?>
					<a class="vk-cat" href="<?php echo esc_url( $term_link ); ?>">
						<span class="vk-cat__bg"></span>
						<span class="vk-cat__overlay"></span>
						<span class="vk-cat__arrow">→</span>
						<span class="vk-cat__body">
							<span class="vk-cat__name"><?php echo esc_html( $cat->name ); ?></span>
							<span class="vk-cat__count"><?php echo esc_html( $cat->count ); ?> products</span>
						</span>
					</a>
				<?php endforeach; ?>
			</div>
		<?php else : ?>
			<p style="text-align:center; color: var(--vk-muted);">
				No categories yet. Visit <code>?seed_demo=1</code> to load sample data,
				or create categories in <strong>Products → Categories</strong>.
			</p>
		<?php endif; ?>
	</div>
</section>

<!-- ============== FEATURED PRODUCTS ============== -->
<section class="vk-section vk-section--mist">
	<div class="vk-container">
		<div class="vk-section-head">
			<span class="vk-eyebrow">Hand-picked</span>
			<h2>Featured products</h2>
			<p>The pieces everyone's talking about right now.</p>
		</div>

		<?php
		echo do_shortcode( '[products visibility="featured" limit="8" columns="4"]' );
		?>
	</div>
</section>

<!-- ============== PROMO BANNER ============== -->
<section class="vk-section vk-section--tight">
	<div class="vk-container">
		<div class="vk-promo">
			<div>
				<div class="vk-promo__eyebrow">Limited time</div>
				<h2 class="vk-promo__title">Take 20% off your first order</h2>
				<p class="vk-promo__text">
					New here? Welcome aboard. Use the code below at checkout and treat yourself
					to something bold — on us.
				</p>
				<div class="vk-promo__actions">
					<a href="<?php echo esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ); ?>" class="vk-btn vk-btn--ink">
						Start shopping
					</a>
				</div>
			</div>
			<div class="vk-promo__code">
				<span>Your code</span>
				<strong>BOLD20</strong>
			</div>
		</div>
	</div>
</section>

<!-- ============== NEW ARRIVALS ============== -->
<section class="vk-section">
	<div class="vk-container">
		<div class="vk-section-head">
			<span class="vk-eyebrow">Just landed</span>
			<h2>New arrivals</h2>
			<p>Fresh out the box and ready to ship.</p>
		</div>

		<?php
		echo do_shortcode( '[recent_products limit="4" columns="4"]' );
		?>
	</div>
</section>

<!-- ============== NEWSLETTER ============== -->
<section class="vk-section vk-section--ink">
	<div class="vk-container">
		<div class="vk-newsletter">
			<span class="vk-eyebrow">Stay in the loop</span>
			<h2>Get 10% off your next drop</h2>
			<p>Join the list for early access to new arrivals, members-only deals and the occasional good meme.</p>
			<form class="vk-newsletter__form" onsubmit="return false;">
				<input type="email" placeholder="you@example.com" aria-label="Email address" required>
				<button type="submit" class="vk-btn">Subscribe</button>
			</form>
			<p class="vk-newsletter__note">No spam, unsubscribe anytime.</p>
		</div>
	</div>
</section>

<?php
get_footer();
