<?php
/**
 * Footer layout override for Viberi news portal.
 * Newsletter sits vertically in the links row.
 * The site name appears as a large background watermark.
 *
 * @package oceanwp-child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$news_categories = array(
	'novosti'   => __( 'Novosti', 'oceanwp-child' ),
	'misljenja' => __( 'Mišljenja', 'oceanwp-child' ),
	'sport'     => __( 'Sport', 'oceanwp-child' ),
	'kultura'   => __( 'Kultura', 'oceanwp-child' ),
	'zabava'    => __( 'Zabava', 'oceanwp-child' ),
);

$company_links = array(
	array( __( 'O nama', 'oceanwp-child' ), '#' ),
	array( __( 'Kontakt', 'oceanwp-child' ), '#' ),
	array( __( 'Impressum', 'oceanwp-child' ), '#' ),
	array( __( 'Politika privatnosti', 'oceanwp-child' ), '#' ),
);

$social_links = array(
	array( 'Instagram', '#' ),
	array( 'Twitter / X', '#' ),
	array( 'Facebook', '#' ),
	array( 'LinkedIn', '#' ),
);

$copy = get_theme_mod( 'ocean_footer_copyright_text', __( 'Copyright [oceanwp_date] WordPress Theme by OceanWP', 'oceanwp-child' ) );
$copy = oceanwp_tm_translation( 'ocean_footer_copyright_text', $copy );
?>

<footer id="footer" class="<?php echo esc_attr( oceanwp_footer_classes() ); ?>"<?php oceanwp_schema_markup( 'footer' ); ?> role="contentinfo">

	<?php do_action( 'ocean_before_footer_inner' ); ?>

	<div id="footer-inner" class="clr">

		<?php do_action( 'ocean_before_footer_widgets' ); ?>

		<div id="footer-widgets" class="vk-footer-main">
			<div class="vk-container">
				<div class="vk-footer-main__grid">

					<!-- Links row: newsletter + categories + company + social -->
					<div class="vk-footer__bottom">

						<!-- Newsletter -->
						<div class="vk-footer__col vk-footer__col--newsletter">
							<div class="vk-newsletter vk-newsletter--vertical">
								<h3><?php esc_html_e( 'Prijavite se na newsletter', 'oceanwp-child' ); ?></h3>
								<p><?php esc_html_e( 'Budite prvi koji saznaju najvažnije vesti — direktno u vašem inboxu.', 'oceanwp-child' ); ?></p>
								<form class="vk-newsletter__form" action="#" method="post" onsubmit="return false;">
									<input type="email" placeholder="vas@email.com" aria-label="<?php esc_attr_e( 'Email adresa', 'oceanwp-child' ); ?>" required>
									<button type="submit" class="vk-btn"><?php esc_html_e( 'Prijavi me', 'oceanwp-child' ); ?></button>
								</form>
							</div>
						</div>

						<!-- Categories -->
						<div class="vk-footer__col">
							<h4 class="vk-footer__title"><?php esc_html_e( 'Rubrike', 'oceanwp-child' ); ?></h4>
							<ul class="vk-footer__links">
								<?php foreach ( $news_categories as $slug => $label ) :
									$term = get_term_by( 'slug', $slug, 'category' );
									if ( $term && ! is_wp_error( $term ) ) {
										$cat_link = get_term_link( $term );
										$cat_name = $term->name;
									} else {
										// Fallback: build category URL manually.
										$cat_link = home_url( '/category/' . $slug . '/' );
										$cat_name = $label;
									}
									?>
									<li>
										<a href="<?php echo esc_url( $cat_link ); ?>">
											<?php echo esc_html( $cat_name ); ?>
										</a>
									</li>
								<?php endforeach; ?>
							</ul>
						</div>

						<!-- Company -->
						<div class="vk-footer__col">
							<h4 class="vk-footer__title"><?php esc_html_e( 'Važni linkovi', 'oceanwp-child' ); ?></h4>
							<ul class="vk-footer__links">
								<?php foreach ( $company_links as $link ) : ?>
									<li>
										<a href="<?php echo esc_url( $link[1] ); ?>"><?php echo esc_html( $link[0] ); ?></a>
									</li>
								<?php endforeach; ?>
							</ul>
						</div>

						<!-- Social -->
						<div class="vk-footer__col">
							<h4 class="vk-footer__title"><?php esc_html_e( 'Pratite nas', 'oceanwp-child' ); ?></h4>
							<ul class="vk-footer__links">
								<?php foreach ( $social_links as $link ) : ?>
									<li>
										<a href="<?php echo esc_url( $link[1] ); ?>" target="_blank" rel="noopener">
											<?php echo esc_html( $link[0] ); ?>
										</a>
									</li>
								<?php endforeach; ?>
							</ul>
						</div>

					</div>

					</div>

					<?php if ( ! empty( $copy ) ) : ?>
						<div class="vk-footer__copyright">
							<?php echo wp_kses_post( do_shortcode( $copy ) ); ?>
						</div>
					<?php endif; ?>

				</div>
			</div>
		</div>

		<?php do_action( 'ocean_after_footer_widgets' ); ?>

	</div><!-- #footer-inner -->

	<?php do_action( 'ocean_after_footer_inner' ); ?>

</footer><!-- #footer -->
