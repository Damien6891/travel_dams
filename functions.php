<?php

/**
 * travel_dams functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package travel_dams
 */

if (! defined('_S_VERSION')) {
	// Replace the version number of the theme on each release.
	define('_S_VERSION', '1.0.0');
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function travel_dams_setup()
{
	/*
		* Make theme available for translation.
		* Translations can be filed in the /languages/ directory.
		* If you're building a theme based on travel_dams, use a find and replace
		* to change 'travel_dams' to the name of your theme in all the template files.
		*/
	load_theme_textdomain('travel_dams', get_template_directory() . '/languages');

	// Add default posts and comments RSS feed links to head.
	add_theme_support('automatic-feed-links');

	add_theme_support('align-wide');

	/*
		* Let WordPress manage the document title.
		* By adding theme support, we declare that this theme does not use a
		* hard-coded <title> tag in the document head, and expect WordPress to
		* provide it for us.
		*/
	add_theme_support('title-tag');

	/*
		* Enable support for Post Thumbnails on posts and pages.
		*
		* @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		*/
	add_theme_support('post-thumbnails');

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus(
		array(
			'menu-1' => esc_html__('Primary', 'travel-dams'),
		)
	);

	/*
		* Switch default core markup for search form, comment form, and comments
		* to output valid HTML5.
		*/
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);

	// Set up the WordPress core custom background feature.
	// add_theme_support(
	// 	'custom-background',
	// 	apply_filters(
	// 		'travel_dams_custom_background_args',
	// 		array(
	// 			'default-color' => 'ffffff',
	// 			'default-image' => '',
	// 		)
	// 	)
	// );

	// Add theme support for selective refresh for widgets.
	add_theme_support('customize-selective-refresh-widgets');

	/**
	 * Add support for core custom logo.
	 *
	 * @link https://codex.wordpress.org/Theme_Logo
	 */
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		)
	);

	add_image_size('travel-dams-card', 400, 300, true); // true = recadrage forcé

}
add_action('after_setup_theme', 'travel_dams_setup');

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function travel_dams_content_width()
{
	$GLOBALS['content_width'] = apply_filters('travel_dams_content_width', 640);
}
add_action('after_setup_theme', 'travel_dams_content_width', 0);

add_filter('body_class', function ($classes) {
	if (travel_dams_has_hero()) {
		$classes[] = 'has-hero';
	}
	return $classes;
});

function travel_dams_has_hero()
{
	if (is_front_page()) {
		return true;
	}

	if (is_singular('post') && has_post_thumbnail()) {
		return true;
	}

	if (is_tax('destination')) {
		return true;
	}

	return false;
}

/**
 * Version d'un asset basée sur sa date de modification.
 * clearstatcache() évite les faux négatifs dus au cache de stat de PHP
 * (realpath_cache_ttl), notamment sous Apache/mod_php où les workers
 * sont réutilisés entre requêtes.
 */
function travel_dams_asset_version($relative_path)
{
	$path = get_template_directory() . $relative_path;

	clearstatcache(true, $path);

	return file_exists($path) ? filemtime($path) : false;
}

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function travel_dams_widgets_init()
{
	register_sidebar(
		array(
			'name'          => esc_html__('Sidebar', 'travel_dams'),
			'id'            => 'sidebar-1',
			'description'   => esc_html__('Add widgets here.', 'travel_dams'),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action('widgets_init', 'travel_dams_widgets_init');

// function td_remove_global_styles()
// {
// 	wp_dequeue_style('global-styles');
// 	wp_deregister_style('global-styles');
// }
// add_action('wp_enqueue_scripts', 'td_remove_global_styles', 100);
// add_action('wp_footer', 'td_remove_global_styles', 20);


// Empêche l'enregistrement initial de la feuille globale
// remove_action('wp_enqueue_scripts', 'wp_enqueue_global_styles');
// remove_action('wp_footer', 'wp_enqueue_global_styles', 1);

// Empêche la réinjection par bloc pendant le rendu (le vrai coupable)
// add_filter('wp_theme_json_get_style_nodes', '__return_empty_array');
// add_filter('should_load_separate_core_block_assets', '__return_false');


/**
 * Enqueue scripts and styles.
 */
function travel_dams_scripts()
{

	// Remove all WordPress block styles
	// wp_dequeue_style('wp-block-library');
	// wp_dequeue_style('wp-block-library-theme');
	// wp_dequeue_style('wc-blocks-style'); // WooCommerce blocks
	// wp_dequeue_style('classic-theme-styles');
	// wp_dequeue_style('global-styles');

	wp_enqueue_style(
		'travel-dams-style',
		get_template_directory_uri() . '/assets/css/style.css',
		array(),
		// filemtime(get_template_directory() . '/assets/css/style.css')
		travel_dams_asset_version('/assets/css/style.css')
	);
	wp_style_add_data('travel_dams-style', 'rtl', 'replace');

	wp_enqueue_script(
		'travel-dams-navigation',
		get_template_directory_uri() . '/assets/js/navigation.js',
		array(),
		// filemtime(get_template_directory() . '/assets/js/navigation.js'),
		travel_dams_asset_version('/assets/js/navigation.js'),
		true
	);

	wp_enqueue_script(
		'travel-dams-header',
		get_template_directory_uri() . '/assets/js/header.js',
		array(),
		// filemtime(get_template_directory() . '/assets/js/header.js'),
		travel_dams_asset_version('/assets/js/header.js'),
		true
	);

	if (is_singular() && comments_open() && get_option('thread_comments')) {
		wp_enqueue_script('comment-reply');
	}
}
add_action('wp_enqueue_scripts', 'travel_dams_scripts');

// Taille dédiée au hero — crop dur pour un ratio cohérent quelle que soit l'image source
add_image_size('hero', 1600, 900, true);

function travel_dams_get_hero_image_id()
{
	$image_id = 0;

	if (is_singular() && has_post_thumbnail()) {
		$image_id = get_post_thumbnail_id();
	} elseif (is_front_page() && 'page' === get_option('show_on_front')) {
		$front_page_id = (int) get_option('page_on_front');
		if ($front_page_id && has_post_thumbnail($front_page_id)) {
			$image_id = get_post_thumbnail_id($front_page_id);
		}
	}

	// Taxonomie "destination" : pas d'image native pour l'instant (cf. limitation).
	// À implémenter plus tard : term meta + uploader média sur l'écran d'édition du terme.

	return apply_filters('travel_dams_hero_image_id', $image_id);
}

function travel_dams_get_hero_title()
{
	if (is_front_page()) {
		return get_the_title();
		// return get_bloginfo('description');
	}

	if (is_singular()) {
		return get_the_title();
	}

	if (is_tax() || is_category() || is_tag()) {
		return single_term_title('', false);
	}

	return '';
}

// h1 partout SAUF sur la home, où le h1 est déjà pris par le nom du site dans le header
function travel_dams_get_hero_title_tag()
{
	return is_front_page() ? 'h2' : 'h1';
}

function enqueue_dashicons_front()
{
	wp_enqueue_style('dashicons');
}
add_action('wp_enqueue_scripts', 'enqueue_dashicons_front');

require get_template_directory() . '/inc/taxonomies.php';

/**
 *  NAVIGATION 
 */
require get_template_directory() . '/inc/navigation.php';
require get_template_directory() . '/inc/class-travel-dams-nav-walker.php';
/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

require get_template_directory() . '/inc/helpers.php';
require get_template_directory() . '/inc/related-content.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/** CARBON FIELDS */
require get_template_directory() . '/inc/carbon-fields.php';

// Contourne un changement de comportement de Gutenberg (~2026) qui verrouille
// automatiquement les patterns non synchronisés en mode "contenu seul" à l'insertion,
// empêchant l'ajout de nouveaux blocs (ex: images) dans le pattern "Jour de carnet".
// Réf: https://github.com/WordPress/gutenberg/pull/75457
// Si ce filtre cesse de fonctionner après une mise à jour WordPress/Gutenberg,
// vérifier le nom de l'option dans ce ticket (le nom était encore en discussion
// au moment de l'écriture) et l'ajuster ici en conséquence.
add_filter('block_editor_settings_all', function ($settings) {
	$settings['disableContentOnlyForUnsyncedPatterns'] = true;
	return $settings;
});




// add_action('admin_notices', function () {
// 	if (! current_user_can('manage_options')) return;

// 	global $wp_version;
// 	$theme = wp_get_theme();
// 	$dirpath = get_stylesheet_directory() . '/patterns/';

// 	echo '<div class="notice notice-info"><pre>';
// 	echo 'WP version : ' . esc_html($wp_version) . "\n";
// 	echo 'Thème actif (stylesheet) : ' . esc_html(get_stylesheet()) . "\n";
// 	echo 'Thème actif (template) : ' . esc_html(get_template()) . "\n";
// 	echo 'Dossier stylesheet : ' . esc_html(get_stylesheet_directory()) . "\n";
// 	echo 'Chemin patterns attendu : ' . esc_html($dirpath) . "\n";
// 	echo 'Dossier existe : ' . (is_dir($dirpath) ? 'oui' : 'NON') . "\n";
// 	echo 'Dossier lisible : ' . (is_readable($dirpath) ? 'oui' : 'NON') . "\n";
// 	echo 'Fichiers trouvés (glob) : ' . print_r(glob($dirpath . '*.php'), true) . "\n";

// 	$patterns = WP_Block_Patterns_Registry::get_instance()->get_all_registered();
// 	echo "Patterns enregistrés :\n" . print_r(wp_list_pluck($patterns, 'title', 'name'), true) . "\n";

// 	$cats = WP_Block_Pattern_Categories_Registry::get_instance()->get_all_registered();
// 	echo "Catégories enregistrées :\n" . print_r(wp_list_pluck($cats, 'label', 'name'), true);
// 	echo '</pre></div>';
// });

add_action('init', function () {
	register_block_pattern_category(
		'travel_dams',
		array('label' => __('Carnet de voyage', 'travel-dams'))
	);
});


// function travel_dams_flush_rewrites()
// {
// 	travel_dams_register_taxonomies();
// 	flush_rewrite_rules();
// }
// add_action('after_switch_theme', 'travel_dams_flush_rewrites');

/**
 * Load Jetpack compatibility file.
 */
if (defined('JETPACK__VERSION')) {
	require get_template_directory() . '/inc/jetpack.php';
}
