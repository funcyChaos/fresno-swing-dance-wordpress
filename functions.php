<?php
/**
 * Fresno Swing Dance functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Fresno_Swing_Dance
 */

if ( ! defined( '_S_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( '_S_VERSION', '1.0.1' );
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function fresno_swing_dance_setup() {
	/*
		* Make theme available for translation.
		* Translations can be filed in the /languages/ directory.
		* If you're building a theme based on Fresno Swing Dance, use a find and replace
		* to change 'fresno-swing-dance' to the name of your theme in all the template files.
		*/
	load_theme_textdomain( 'fresno-swing-dance', get_template_directory() . '/languages' );

	/*
		* Let WordPress manage the document title.
		* By adding theme support, we declare that this theme does not use a
		* hard-coded <title> tag in the document head, and expect WordPress to
		* provide it for us.
		*/
	add_theme_support( 'title-tag' );

	/*
		* Enable support for Post Thumbnails on posts and pages.
		*
		* @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		*/
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	// This theme is not using the wp_nav_menu() function, but could/should be added in later
	// register_nav_menus(
	// 	array(
	// 		'menu-1' => esc_html__( 'Primary', 'fresno-swing-dance' ),
	// 	)
	// );

	// Set up the WordPress core custom background feature.
	// This would be cool to add back in, but would require some work I think
	// add_theme_support(
	// 	'custom-background',
	// 	apply_filters(
	// 		'fresno_swing_dance_custom_background_args',
	// 		array(
	// 			'default-color' => 'ffffff',
	// 			'default-image' => '',
	// 		)
	// 	)
	// );

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
}
add_action( 'after_setup_theme', 'fresno_swing_dance_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function fresno_swing_dance_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'fresno_swing_dance_content_width', 640 );
}
add_action( 'after_setup_theme', 'fresno_swing_dance_content_width', 0 );

/**
 * Enqueue scripts and styles.
 */
add_action( 'wp_enqueue_scripts', function(){
	wp_enqueue_style( 'fresno-swing-dance-style', get_stylesheet_uri(), array(), _S_VERSION );
	wp_style_add_data( 'fresno-swing-dance-style', 'rtl', 'replace' );
	if(is_page(6)){
		wp_register_script( 'subscription-script', get_template_directory_uri() . '/js/subscription.js', array(), _S_VERSION, true );
		wp_localize_script('subscription-script', 'wpVars', [
			'homeURL'	=> home_url(),
			'nonce'		=> wp_create_nonce('wp_rest')
		]);
		wp_enqueue_script('subscription-script');
	}else{
		wp_enqueue_script( 'fresno-swing-dance-navigation', get_template_directory_uri() . '/js/navigation.js', array(), _S_VERSION, true );
		wp_enqueue_script( 'font-awesome', 'https://kit.fontawesome.com/aebdbe8212.js', array(), _S_VERSION, true );
		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}
	}
});


/**
* Remove unnecessary menus
*/
add_action('admin_menu', function(){
	remove_menu_page('edit.php');
	remove_menu_page('edit-comments.php');
});

/**
* The subscription API for the subscription page database interactions
*/
require get_template_directory() . '/inc/fsd-subscription-api.php';