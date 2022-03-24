<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package crowd
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function crowd_body_classes( $classes ) {
	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	// Adds a class of no-sidebar when there is no sidebar present.
	if ( ! is_active_sidebar( 'sidebar-1' ) ) {
		$classes[] = 'no-sidebar';
	}

	return $classes;
}
add_filter( 'body_class', 'crowd_body_classes' );

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function crowd_pingback_header() {
	if ( is_singular() && pings_open() ) {
		printf( '<link rel="pingback" href="%s">', esc_url( get_bloginfo( 'pingback_url' ) ) );
	}
}
add_action( 'wp_head', 'crowd_pingback_header' );

add_action('admin_menu', 'add_crowd_submission_page');
function add_crowd_submission_page() { 
	add_menu_page( 
		'Crowd Submissions', 
		'Crowd Submissions', 
		'edit_posts', 
		'crowd_submissions', 
		'crowd_submissions_cbk', 
		'dashicons-media-spreadsheet',
		6
	   );
}

function crowd_submissions_cbk()
{
	include  get_template_directory(). '/template-parts/admin/crowd-submissions.php';
}

function scratchcode_create_submissions_table() {
 
    global $wpdb;
 
    $table_name = $wpdb->prefix . "submissions";
 
    $charset_collate = $wpdb->get_charset_collate();
 
    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
      id bigint(20) NOT NULL AUTO_INCREMENT,
      pname varchar(255) NOT NULL,
	  pemail varchar(255) NOT NULL UNIQUE,
      PRIMARY KEY id (id)
    ) $charset_collate;";
 
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}    
 
add_action('init', 'scratchcode_create_submissions_table');

function getCrowdSubmissions()
{
	global $wpdb;
	$submissionsTable = $wpdb->prefix.'submissions';
	$submissions = $wpdb->get_results( "SELECT * FROM $submissionsTable");

	return $submissions;
}

function getCrowdSubmissionsRemainingCount()
{
	$submissionsLimit = get_option( 'crowd_submissions_limit', false);
	$submissionsCount = getCrowdSubmissions();

	return $submissionsLimit - count($submissionsCount);
}