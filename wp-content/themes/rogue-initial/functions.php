<?php
/**
 * OceanWP Child — Viberi (News Portal)
 * Functions, asset loading and news-site helpers.
 *
 * @package oceanwp-child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'VK_CHILD_VERSION', '2.1.5' );
define( 'VK_CHILD_DIR', get_stylesheet_directory() );
define( 'VK_CHILD_URI', get_stylesheet_directory_uri() );

/**
 * 1) Load parent + child styles, Google Fonts and the news CSS.
 */
add_action( 'wp_enqueue_scripts', 'vk_child_enqueue_styles', 20 );

function vk_child_enqueue_styles() {
	wp_enqueue_style(
		'oceanwp-style',
		get_template_directory_uri() . '/style.css',
		array(),
		OCEANWP_THEME_VERSION
	);

	wp_enqueue_style(
		'oceanwp-child-style',
		get_stylesheet_uri(),
		array( 'oceanwp-style' ),
		VK_CHILD_VERSION
	);

	wp_enqueue_style(
		'vk-news',
		VK_CHILD_URI . '/assets/css/news.css',
		array( 'oceanwp-child-style' ),
		VK_CHILD_VERSION
	);

	wp_enqueue_style(
		'vk-google-fonts',
		'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@600;700;800&display=swap',
		array(),
		null
	);

	wp_enqueue_script(
		'vk-news',
		VK_CHILD_URI . '/assets/js/news.js',
		array(),
		VK_CHILD_VERSION,
		true
	);
}

/**
 * 2) System font override — make Poppins/Inter the default.
 */
add_filter( 'ocean_default_font_family', 'vk_default_font_family' );
add_filter( 'ocean_default_headings_font_family', 'vk_headings_font_family' );

function vk_default_font_family() {
	return "Inter, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif";
}
function vk_headings_font_family() {
	return "'Poppins', Inter, -apple-system, BlinkMacSystemFont, sans-serif";
}

/**
 * 3) Register a primary menu for the news categories.
 */
add_action( 'after_setup_theme', 'vk_child_setup' );

function vk_child_setup() {
	register_nav_menus(
		array(
			'vk_primary_menu' => __( 'Primary News Menu', 'oceanwp-child' ),
			'vk_footer_menu'  => __( 'Footer Links Menu', 'oceanwp-child' ),
		)
	);
}

/**
 * 4) Auto-create the news categories and primary menu on the first admin request.
 */
add_action( 'admin_init', 'vk_ensure_news_categories' );

function vk_ensure_news_categories() {
	$categories = array(
		'novosti'   => 'Novosti',
		'misljenja' => 'Mišljenja',
		'sport'     => 'Sport',
		'kultura'   => 'Kultura',
		'zabava'    => 'Zabava',
	);

	$cat_ids = array();
	foreach ( $categories as $slug => $name ) {
		$existing = term_exists( $slug, 'category' );
		if ( ! $existing ) {
			$res = wp_insert_term(
				$name,
				'category',
				array(
					'slug'        => $slug,
					'description' => sprintf( __( 'Najnovije vesti iz rubrike %s.', 'oceanwp-child' ), $name ),
				)
			);
			if ( ! is_wp_error( $res ) ) {
				$cat_ids[ $slug ] = (int) $res['term_id'];
			}
		} else {
			$cat_ids[ $slug ] = is_array( $existing ) ? (int) $existing['term_id'] : (int) $existing;
		}
	}

	// Auto-create a primary menu with Home + category links and assign it to the main menu location.
	$menu_location = 'main_menu';
	$menu_name     = __( 'Primary News Menu', 'oceanwp-child' );
	$menu_flag     = 'vk_news_menu_created';

	if ( ! has_nav_menu( $menu_location ) && ! get_option( $menu_flag ) ) {
		$menu_id = wp_create_nav_menu( $menu_name );

		if ( $menu_id && ! is_wp_error( $menu_id ) ) {
			$locations = get_theme_mod( 'nav_menu_locations' );
			if ( ! is_array( $locations ) ) {
				$locations = array();
			}
			$locations[ $menu_location ] = $menu_id;
			set_theme_mod( 'nav_menu_locations', $locations );

			wp_update_nav_menu_item( $menu_id, 0, array(
				'menu-item-title'  => __( 'Početna', 'oceanwp-child' ),
				'menu-item-url'    => home_url( '/' ),
				'menu-item-status' => 'publish',
				'menu-item-type'   => 'custom',
			) );

			foreach ( $cat_ids as $slug => $cat_id ) {
				wp_update_nav_menu_item( $menu_id, 0, array(
					'menu-item-title'     => $categories[ $slug ],
					'menu-item-object-id' => $cat_id,
					'menu-item-object'    => 'category',
					'menu-item-type'      => 'taxonomy',
					'menu-item-status'    => 'publish',
				) );
			}

			update_option( $menu_flag, true );
		}
	}
}

/**
 * 5) Inject custom cursor markup on every page.
 */
add_action( 'wp_footer', 'vk_custom_cursor_markup', 1 );

function vk_custom_cursor_markup() {
	echo '<div class="vk-cursor" aria-hidden="true"></div>';
	echo '<div class="vk-cursor-follower" aria-hidden="true"></div>';
}

/**
 * 6) Update default footer copyright text for the news portal.
 */
add_filter( 'theme_mod_ocean_footer_copyright_text', function ( $value ) {
	if ( empty( $value ) || false !== strpos( $value, 'WordPress Theme by OceanWP' ) ) {
		return sprintf( __( 'Copyright %s Anđela Ćasić. Sva prava zadržana.', 'oceanwp-child' ), date_i18n( 'Y' ) );
	}
	return $value;
} );

/**
 * 7) Force full-width layout on the homepage, archives and single posts.
 */
add_filter( 'ocean_post_layout_class', function ( $layout ) {
	return ( is_front_page() || is_category() || is_home() || is_archive() || is_singular( 'post' ) ) ? 'full-width' : $layout;
}, 20 );

/**
 * 8) Hide the default OceanWP page header on homepage, archives and single posts,
 *    because we provide our own hero / archive hero / post hero.
 */
add_filter( 'ocean_display_page_header', function ( $return ) {
	if ( is_front_page() || is_category() || is_home() || is_archive() || is_singular( 'post' ) ) {
		return false;
	}
	return $return;
} );

/**
 * 8b) Disable the top bar completely.
 */
add_filter( 'ocean_display_top_bar', '__return_false' );

/**
 * 9) Translate common OceanWP / WordPress frontend strings to Serbian.
 */
add_filter( 'theme_mod_ocean_custom_header_search_form_label', function () {
	return __( 'Pretraži ovaj sajt', 'oceanwp-child' );
} );

add_filter( 'oceanwp_theme_strings', 'vk_serbian_theme_strings', 20 );

function vk_serbian_theme_strings( $strings ) {
	$serbian = array(
		'owp-string-single-related-posts'        => __( 'Možda će vas zanimati', 'oceanwp-child' ),
		'owp-string-comment-post-button'         => __( 'Pošalji komentar', 'oceanwp-child' ),
		'owp-string-comment-placeholder'         => __( 'Vaš komentar ovde...', 'oceanwp-child' ),
		'owp-string-comment-name'                => __( 'Ime', 'oceanwp-child' ),
		'owp-string-comment-email'               => __( 'Email', 'oceanwp-child' ),
		'owp-string-comment-website'             => __( 'Web sajt', 'oceanwp-child' ),
		'owp-string-comment-name-req'            => __( 'Ime (obavezno)', 'oceanwp-child' ),
		'owp-string-comment-email-req'           => __( 'Email (obavezno)', 'oceanwp-child' ),
		'owp-string-comment-logout-text'         => __( 'Odjavi se sa ovog naloga', 'oceanwp-child' ),
		'owp-string-search-form-label'           => __( 'Pretraži ovaj sajt', 'oceanwp-child' ),
		'owp-string-search-text'                 => __( 'Pretraži', 'oceanwp-child' ),
		'owp-string-search-field'                => __( 'Unesite upit za pretragu', 'oceanwp-child' ),
		'owp-string-website-search-icon'         => __( 'Uključi/isključi pretragu sajta', 'oceanwp-child' ),
		'owp-string-mobile-submit-search'        => __( 'Pretraži', 'oceanwp-child' ),
		'owp-string-wai-comments'                => __( 'Komentari', 'oceanwp-child' ),
		'owp-string-esc-close-notice'            => __( 'Pritisnite Escape da zatvorite panel.', 'oceanwp-child' ),
		'owp-string-single-next-post'            => __( 'Sledeća vest', 'oceanwp-child' ),
		'owp-string-single-prev-post'            => __( 'Prethodna vest', 'oceanwp-child' ),
		'owp-string-author-page'                 => __( 'Poseti stranicu autora', 'oceanwp-child' ),
		'owp-string-read-more-article'           => __( 'Pročitaj više o članku', 'oceanwp-child' ),
		'owp-string-post-continue-reading'       => __( 'Nastavi čitanje', 'oceanwp-child' ),
		'owp-string-search-continue-reading'     => __( 'Nastavi čitanje', 'oceanwp-child' ),
	);

	return array_merge( $strings, $serbian );
}

/**
 * 9b) Serbian labels for the default WordPress / OceanWP comment form.
 */
add_filter( 'comment_form_defaults', 'vk_serbian_comment_form_defaults', 99 );

function vk_serbian_comment_form_defaults( $defaults ) {
	$defaults['title_reply']          = __( 'Ostavi komentar', 'oceanwp-child' );
	$defaults['title_reply_to']       = __( 'Odgovori %s', 'oceanwp-child' );
	$defaults['cancel_reply_link']    = __( 'Otkaži odgovor', 'oceanwp-child' );
	$defaults['label_submit']         = __( 'Pošalji komentar', 'oceanwp-child' );
	$defaults['comment_notes_before'] = '<p class="comment-notes">' . __( 'Vaša email adresa neće biti objavljena.', 'oceanwp-child' ) . '</p>';

	if ( isset( $defaults['comment_field'] ) ) {
		$defaults['comment_field'] = str_replace(
			array( '>Comment <', '>Comment<', 'Comment ', 'Comment' ),
			array( '>Komentar <', '>Komentar<', 'Komentar ', 'Komentar' ),
			$defaults['comment_field']
		);
	}

	return $defaults;
}

add_filter( 'comment_form_default_fields', 'vk_serbian_comment_form_fields', 99 );

function vk_serbian_comment_form_fields( $fields ) {
	if ( isset( $fields['author'] ) ) {
		$fields['author'] = str_replace( '>Name', '>Ime', $fields['author'] );
	}
	if ( isset( $fields['email'] ) ) {
		$fields['email'] = str_replace( '>Email', '>Email', $fields['email'] );
	}
	// Remove the optional Website field from the public comment form.
	unset( $fields['url'] );
	return $fields;
}

add_filter( 'gettext', 'vk_translate_to_serbian', 20, 3 );
add_filter( 'gettext_with_context', 'vk_translate_to_serbian_with_context', 20, 4 );
add_filter( 'ngettext', 'vk_translate_to_serbian_n', 20, 5 );

function vk_translate_to_serbian_map() {
	return array(
		'Your email address will not be published.'           => 'Vaša email adresa neće biti objavljena.',
		'Save my name, email, and website in this browser for the next time I comment.'
		                                                      => 'Sačuvaj moje ime, email i web sajt u ovom pregledaču za sledeći put.',
		'Loading...'                                          => 'Učitavanje...',
		'Log in'                                              => 'Prijavi se',
		'Read More'                                           => 'Pročitaj više',
		'Recent Posts'                                        => 'Najnovije vesti',
		'Blog'                                                => 'Blog',
		'Home'                                                => 'Početna',
		'Comments are closed.'                                => 'Komentari su zatvoreni.',
		'Leave a Comment'                                     => 'Ostavi komentar',
		'Comment'                                             => 'Komentar',
		'Comment *'                                           => 'Komentar *',
		'Comments'                                            => 'Komentari',
		'0 Comments'                                          => '0 komentara',
		'1 Comment'                                           => '1 komentar',
		'% Comments'                                          => '% komentara',
		'Search'                                              => 'Pretraži',
		'Mobile Menu'                                         => 'Mobilni meni',
		'Menu'                                                => 'Meni',
		'Close'                                               => 'Zatvori',
		'Main website navigation'                             => 'Glavna navigacija sajta',
		'You must be %slogged in%s to post a comment.'        => 'Morate biti %sprijavljeni%s da biste poslali komentar.',
		'Logged in as %2$s. %1$sLog out &raquo;%2$s.'        => 'Prijavljeni ste kao %2$s. %1$sOdjavi se &raquo;%2$s.',
	);
}

function vk_translate_to_serbian( $translation, $text, $domain ) {
	if ( 'oceanwp' !== $domain && 'default' !== $domain ) {
		return $translation;
	}

	$strings = vk_translate_to_serbian_map();

	if ( isset( $strings[ $text ] ) ) {
		return $strings[ $text ];
	}

	return $translation;
}

function vk_translate_to_serbian_with_context( $translation, $text, $context, $domain ) {
	return vk_translate_to_serbian( $translation, $text, $domain );
}

function vk_translate_to_serbian_n( $translation, $single, $plural, $number, $domain ) {
	if ( 'oceanwp' !== $domain && 'default' !== $domain ) {
		return $translation;
	}

	$strings = vk_translate_to_serbian_map();
	$text    = ( 1 === $number ) ? $single : $plural;

	if ( isset( $strings[ $text ] ) ) {
		return $strings[ $text ];
	}

	return $translation;
}

/**
 * 10) Helper: output the news category list as a menu fallback.
 *
 * @return array
 */
function vk_news_category_links() {
	$slugs = array( 'novosti', 'misljenja', 'sport', 'kultura', 'zabava' );
	$links = array();

	foreach ( $slugs as $slug ) {
		$term = get_category_by_slug( $slug );
		if ( $term && ! is_wp_error( $term ) ) {
			$links[] = array(
				'url'   => get_category_link( $term->term_id ),
				'label' => $term->name,
			);
		}
	}

	return $links;
}

/**
 * 11) Helper: excerpt with custom length and ellipsis.
 *
 * @param int    $limit  Word count.
 * @param string $more   Ellipsis string.
 * @return string
 */
function vk_trim_excerpt( $limit = 20, $more = '…' ) {
	$excerpt = get_the_excerpt();
	$words   = wp_trim_words( $excerpt, $limit, $more );
	return $words;
}

/**
 * 12) Optional demo data seeder — visit ?seed_demo=1 to load sample posts.
 *    Visit ?seed_demo=clear to remove them.
 */
require_once VK_CHILD_DIR . '/inc/demo-data.php';
