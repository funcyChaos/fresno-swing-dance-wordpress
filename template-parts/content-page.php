<?php
/**
 * Template part for displaying page content in page.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Fresno_Swing_Dance
 */

fresno_swing_dance_post_thumbnail();
the_content();

wp_link_pages(
	array(
		'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'fresno-swing-dance' ),
		'after'  => '</div>',
	)
);