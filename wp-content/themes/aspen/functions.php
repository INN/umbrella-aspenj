<?php
// This site is an INN Member
if ( ! defined( 'INN_MEMBER' ) ) {
    define( 'INN_MEMBER', true );
}
// This site is hosted by INN
if ( ! defined( 'INN_HOSTED' ) ) {
    define( 'INN_HOSTED', true );
}

function aspen_search_filter($query) {
	if ( ! $query->is_admin && $query->is_search ) {
		$query->set( 'post_type', array( 'post', 'page' ) );
	}
	return $query;
}
add_filter( 'pre_get_posts', 'aspen_search_filter' );

/**
 * Include compiled aspen.min.css
 */
function aspen_stylesheet() {
	wp_dequeue_style( 'largo-child-styles' );

	/* This theme uses Adobe Typekit for a few custom fonts (https://typekit.com).
	 * The ID is unique to this particular site so if you wanted to use this theme for another site
	 * you would need to register with Typekit and get your own ID.
	 *
	 * Included fonts/weights in this bundle:
	 *	- Prenton Condensed Bold
	 *	- FF Meta Serif Web Pro Book and Bold
	 *	- LFT Etica Web Extra Bold
	 */
	wp_register_style(
		'aspen-typekit',
		'https://use.typekit.net/zni4nda.css'
	);

	$suffix = (LARGO_DEBUG)? '' : '.min';
	wp_enqueue_style(
		'aspen',
		get_stylesheet_directory_uri().'/css/child' . $suffix . '.css',
		array( 'aspen-typekit' ),
		filemtime( get_stylesheet_directory().'/css/child' . $suffix . '.css' )
	);
}
add_action( 'wp_enqueue_scripts', 'aspen_stylesheet', 20 );

function aspen_archive_rounduplink_title() {
	$title = __( 'The Bucket: News of Interest to Aspenites', 'aspen' );
	return $title;
}
add_filter( 'largo_archive_rounduplink_title', 'aspen_archive_rounduplink_title' );

/**
 * Display a subscribe button in the navbars
 * 
 * @param str $location The location that this button is placed
 * 
 * @return str The formatted subscribe button
 */
function aspen_subscribe_button( $location = null ) {

    if( 'sticky' === $location ) {

        printf( '<a class="subscribe-link" href="%1$s"><span>%2$s</span></a>',
            esc_url( '\/subscribe\/' ),
            esc_html( 'Subscribe' )
        );

    } else {

        printf( '<div class="subscribe-btn"><a href="%1$s">%2$s</a></div>',
            esc_url( '\/subscribe\/' ),
            esc_html( 'Subscribe' )
        );

    }
}