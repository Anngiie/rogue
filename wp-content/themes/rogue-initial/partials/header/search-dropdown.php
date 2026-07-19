<?php
/**
 * Custom navbar search dropdown for Viberi.
 * Replaces the default OceanWP dropdown with a full-width clean bar.
 *
 * @package oceanwp-child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$item_search_attrs = apply_filters( 'oceanwp_attrs_search_bar', '' );
?>

<div id="searchform-dropdown" class="header-searchform-wrap clr vk-search-dropdown" <?php echo $item_search_attrs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<div class="vk-container vk-search-dropdown__inner">
		<form role="search" method="get" class="vk-search-dropdown__form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
			<label for="vk-search-dropdown-field" class="screen-reader-text"><?php esc_html_e( 'Pretraži ovaj sajt', 'oceanwp-child' ); ?></label>
			<input id="vk-search-dropdown-field" type="search" class="field vk-search-dropdown__field" autocomplete="off" placeholder="<?php esc_attr_e( 'Pretraži vesti…', 'oceanwp-child' ); ?>" name="s" />
			<button type="submit" class="vk-search-dropdown__submit">
				<span class="vk-search-dropdown__submit-text"><?php esc_html_e( 'Pretraži', 'oceanwp-child' ); ?></span>
				<i class="icon-magnifier" aria-hidden="true"></i>
			</button>
		</form>
		<button type="button" class="vk-search-dropdown__close" aria-label="<?php esc_attr_e( 'Zatvori pretragu', 'oceanwp-child' ); ?>">
			<i class="icon-close" aria-hidden="true"></i>
		</button>
	</div>
</div><!-- #searchform-dropdown -->
