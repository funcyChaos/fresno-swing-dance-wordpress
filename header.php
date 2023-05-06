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
						src="./img/fsd-logo.png"
						alt="fsd-logo"
					/>
				</div>
			</a>

			<div class="banner">
				<h1>Fresno Swing Dance</h1>
				<div class="nav-links">
					<ul>
						<li>
							<a class="current-page" href="index.html">Home</a>
						</li>
						<li>
							<a href="about-us.html">About Us</a>
						</li>
						<li>
							<a href="lessons-events.html">Lessons & Events</a>
						</li>
						<li>
							<a href="contact-us.html">Contact Us</a>
						</li>
						<li>
							<a href="code-of-conduct.html">Code of Conduct</a>
						</li>
					</ul>
				</div>
				<i class="fas fa-bars fa-lg" id="menu_button"></i>
				<div class="nav-dropdown" id="nav_dropdown">
					<ul>
						<li>
							<a class="current-page" href="index.html">Home</a>
						</li>
						<li>
							<a href="about-us.html">About Us</a>
						</li>
						<li>
							<a href="lessons-events.html">Lessons & Events</a>
						</li>
						<li>
							<a href="contact-us.html">Contact Us</a>
						</li>
						<li>
							<a href="code-of-conduct.html">Code of Conduct</a>
						</li>
					</ul>
				</div>
			</div>
		</nav>
