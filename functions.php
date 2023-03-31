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
	define( '_S_VERSION', '1.0.0' );
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

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

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
	register_nav_menus(
		array(
			'menu-1' => esc_html__( 'Primary', 'fresno-swing-dance' ),
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
	add_theme_support(
		'custom-background',
		apply_filters(
			'fresno_swing_dance_custom_background_args',
			array(
				'default-color' => 'ffffff',
				'default-image' => '',
			)
		)
	);

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

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
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function fresno_swing_dance_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'fresno-swing-dance' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'fresno-swing-dance' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'fresno_swing_dance_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function fresno_swing_dance_scripts() {
	if(is_page(6)){
		wp_enqueue_style( 'subscription-styles', get_template_directory_uri().'/sass/style.css', array(), _S_VERSION );
		wp_register_script( 'subscription-script', get_template_directory_uri() . '/js/subscription.js', array(), _S_VERSION, true );
		wp_localize_script('subscription-script', 'wpVars', [
			'homeURL'	=> home_url(),
			'nonce'		=> wp_create_nonce('wp_rest')
		]);
		wp_enqueue_script('subscription-script');
	}else{
		wp_enqueue_style( 'fresno-swing-dance-style', get_stylesheet_uri(), array(), _S_VERSION );
	}
	wp_style_add_data( 'fresno-swing-dance-style', 'rtl', 'replace' );

	wp_enqueue_script( 'fresno-swing-dance-navigation', get_template_directory_uri() . '/js/navigation.js', array(), _S_VERSION, true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'fresno_swing_dance_scripts' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

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

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}

add_action("rest_api_init", function(){
	register_rest_route('subscription/v1', '/by-number/(?P<phone>\d+)', [
		[
			"methods"	=> "POST",
			"callback"	=> function(WP_REST_Request $req){
				global $wpdb;
				$current = $wpdb->get_results("SELECT vouchers FROM `{$wpdb->base_prefix}subscription_members` where phone = {$req['phone']}", ARRAY_N);
				// return !!$current;
				if(!$current) 						return ['error' => 'Subscriber does not exist'];
				if($current[0][0] == 0) 	return ['error' => 'Subscriber is out of vouchers'];

				$wpdb->query("BEGIN TRAN");
				$query = $wpdb->prepare(
					"UPDATE `{$wpdb->base_prefix}subscription_members`
					SET vouchers = vouchers - 1
					WHERE phone = {$req['phone']}
				");
				$wpdb->query($query);
				$wpdb->query("COMMIT");
				$res = $wpdb->get_results("SELECT first_name, vouchers FROM `{$wpdb->base_prefix}subscription_members` where phone = {$req['phone']}", ARRAY_N);
				return [
					'first_name'	=> $res[0][0],
					'vouchers' 		=> $res[0][1],
				];
			},
			'permission_callback' => function(){
				return current_user_can('edit_others_posts');
			}
		],
	]);

	register_rest_route('subscription/v1', '/new-user', [
		[
			"methods"	=> "POST",
			"callback"	=> function(WP_REST_Request $req){
				global $wpdb;
				$current = $wpdb->get_results("SELECT * FROM `{$wpdb->base_prefix}subscription_members` where phone = {$req->get_param('phone')} or (first_name = '{$req->get_param('first_name')}' and last_name = '{$req->get_param('last_name')}')", ARRAY_N);
				if($current) return [
					'error' 			=> 'Subscriber already exists',
					'subscriber'	=> $current,
				];
				$wpdb->query("BEGIN TRAN");
				$query = $wpdb->prepare(
					"INSERT INTO `{$wpdb->base_prefix}subscription_members`
					VALUES ('{$req->get_param('first_name')}', '{$req->get_param('last_name')}', '{$req->get_param('phone')}', 4)
				");
				$res = $wpdb->query($query);
				$wpdb->query("COMMIT");
				return ['success' => $res == true ? true : false];
			},
			'permission_callback' => function(){
				return current_user_can('edit_others_posts');
			}
		]
	]);
});

/* add new tab called "mytab" */

add_filter('um_account_page_default_tabs_hook', function($tabs){
	$tabs[100]['profile']['icon'] = 'um-faicon-users';
	$tabs[100]['profile']['title'] = 'Profile';
	$tabs[100]['profile']['custom'] = true;
	$tabs[100]['profile']['show_button'] = false;
	return $tabs;
}, 100 );
	
/* make our new tab hookable */

add_action('um_account_tab__profile', function($info){
	global $ultimatemember;
	extract( $info );
	$output = $ultimatemember->account->get_tab_output('profile');
	if ( $output ) { echo $output; }
});

/* Finally we add some content in the tab */

add_filter('um_account_content_hook_profile', function($output){
	ob_start();
	?>
	<div class="um-field">
		<?=do_shortcode('[ultimatemember form_id="11"]')?>
	</div>
	<?php
	$output .= ob_get_contents();
	ob_end_clean();
	return $output;
});