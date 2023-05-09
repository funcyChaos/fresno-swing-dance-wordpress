<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Fresno_Swing_Dance
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<style>
		@font-face {
			font-family: "betty_noirregular";
			src: url("<?=get_template_directory_uri()?>/sass/bettynoir/bettynoir-webfont.woff2") format("woff2"),
				url("./bettynoir/bettynoir-webfont.woff") format("woff");
			font-weight: normal;
			font-style: normal;
		}

		.grid{
			background-image: url("<?=get_template_directory_uri()?>/img/BackgroundNEW.jpg");
		}
	</style>

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
	<div class="grid">
		<nav class="nav-grid">
			<a href="index.html" class="logo">
				<div class="logo-wrapper">
					<img
						id="site_logo"
						src="<?=get_template_directory_uri()?>/img/fsd-logo.png"
						alt="fsd-logo"
					/>
				</div>
			</a>

			<div class="banner">
				<h1>Fresno Swing Dance</h1>
				<div class="nav-links">
					<ul>
						<li>
							<a class="current-page" href="<?=get_home_url()?>">Home</a>
						</li>
						<li>
							<a href="<?=get_home_url()?>/about-us">About Us</a>
						</li>
						<li>
							<a href="<?=get_home_url()?>/lessons-events">Lessons & Events</a>
						</li>
						<li>
							<a href="<?=get_home_url()?>/contact-us">Contact Us</a>
						</li>
						<li>
							<a href="<?=get_home_url()?>/code-of-conduct">Code of Conduct</a>
						</li>
					</ul>
				</div>
				<i class="fas fa-bars fa-lg" id="menu_button"></i>
				<div class="nav-dropdown" id="nav_dropdown">
					<ul>
						<li>
							<a class="current-page" href="<?=get_home_url()?>">Home</a>
						</li>
						<li>
							<a href="<?=get_home_url()?>/about-us">About Us</a>
						</li>
						<li>
							<a href="<?=get_home_url()?>/lessons-events">Lessons & Events</a>
						</li>
						<li>
							<a href="<?=get_home_url()?>/contact-us">Contact Us</a>
						</li>
						<li>
							<a href="<?=get_home_url()?>/code-of-conduct">Code of Conduct</a>
						</li>
					</ul>
				</div>
			</div>
		</nav>
