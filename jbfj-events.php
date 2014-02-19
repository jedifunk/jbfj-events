<?php
/* Plugin Name: JBFJ Events
 * Author: Bryce Flory
 * URI: bryceflory.com
 * Version: 1.4
 */

// Enqueue necessary scripts
function jbfj_events_scripts() {
	wp_enqueue_script('jquery-ui-datepicker', array('jquery'));
	wp_enqueue_script('events-scripts', plugins_url('jbfj-events/lib/js/jbfj-events.js'));
	wp_enqueue_style('datepicker-css', plugins_url('jbfj-events/lib/css/jquery-ui-1.10.3.custom.min.css'));
	wp_enqueue_script('timepicker', plugins_url('jbfj-events/lib/js/jquery.timepicker.min.js'), array('jquery'));
	wp_enqueue_style('timepicker-css', plugins_url('jbfj-events/lib/css/jquery.timepicker.css'));
}
add_action( 'admin_init', 'jbfj_events_scripts' );

function jbfj_frontend_events_scripts() {
	
		wp_enqueue_style('jbfj-events', plugins_url('jbfj-events/lib/css/jbfj-simple-events.css'));
}
add_action('wp_enqueue_scripts', 'jbfj_frontend_events_scripts');
 
// CPT for Events
add_action( 'init', 'register_cpt_event' );

function register_cpt_event() {

    $labels = array( 
        'name' => _x( 'Events', 'event' ),
        'singular_name' => _x( 'Event', 'event' ),
        'add_new' => _x( 'Add New', 'event' ),
        'add_new_item' => _x( 'Add New Event', 'event' ),
        'edit_item' => _x( 'Edit Event', 'event' ),
        'new_item' => _x( 'New Event', 'event' ),
        'view_item' => _x( 'View Event', 'event' ),
        'search_items' => _x( 'Search Events', 'event' ),
        'not_found' => _x( 'No events found', 'event' ),
        'not_found_in_trash' => _x( 'No events found in Trash', 'event' ),
        'parent_item_colon' => _x( 'Parent Event:', 'event' ),
        'menu_name' => _x( 'Events', 'event' ),
    );

    $args = array( 
        'labels' => $labels,
        'hierarchical' => false,      
        'supports' => array( 'title', 'editor', 'thumbnail' ),
        'taxonomies' => array( 'category' ),
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'menu_position' => 20,
        'menu_icon' => plugins_url('jbfj-events/lib/i/calendar-month.png'),
        'show_in_nav_menus' => false,
        'publicly_queryable' => true,
        'exclude_from_search' => false,
        'has_archive' => true,
        'query_var' => true,
        'can_export' => true,
        'rewrite' => true,
        'capability_type' => 'post'
    );

    register_post_type( 'event', $args );
}

// Meta Box for Event Details
add_action('add_meta_boxes', 'jbfj_event_detail_meta_box');
add_action('save_post', 'add_event_detail');

function jbfj_event_detail_meta_box() {
	add_meta_box('event_detail_meta_box', 'Event Details', 'event_detail_callback', 'event', 'normal');
}
function event_detail_callback() {
	global $post;
	$date = get_post_meta($post->ID, 'date', true);
	$startT = get_post_meta($post->ID, 'startT', true);
	$startN = get_post_meta($post->ID, 'startN', true);
	$endT = get_post_meta($post->ID, 'endT', true);
	$endN = get_post_meta($post->ID, 'endN', true);
	$Noptions = array('AM or PM', 'AM', 'PM');
	$venue = get_post_meta($post->ID, 'venue', true);
	
	echo '<label for="date_id">Date:</label><input id="date_id" name="date_id" class="jDate" type="text" value="'.esc_attr($date).'" /><br/>';
	echo '<label for="startT_id">Start Time:</label><input id="startT_id" name="startT_id" class="startT timepicker" type="text" value="'.esc_attr($startT).'" /><br/>';			
	echo '<label for="endT_id">End Time:</label><input id="endT_id" name="endT_id" class="endT timepicker" type="text" value="'.esc_attr($endT).'" /><br/>';
	echo '<label for="venue_id">Venue:</label><input id="venue_id" name="venue_id" class="venue" type="text" value="'.esc_attr($venue).'" />';

}

// Save to DB
function add_event_detail() {
	global $post;
	if(isset ($_POST['date_id']) ) {
		update_post_meta($post->ID, 'date', $_POST['date_id']);
	}
	if ( $_POST['date_id'] ='') {
		update_post_meta($post->ID, 'date', NULL);
	}
	if(isset ($_POST['startT_id']) ) {
		update_post_meta($post->ID, 'startT', $_POST['startT_id']);
	}
	if ( $_POST['startT_id'] ='') {
		update_post_meta($post->ID, 'startT', NULL);
	}
	if(isset ($_POST['startN_id']) ) {
		update_post_meta($post->ID, 'startN', $_POST['startN_id']);
	}
	if ( $_POST['startN_id'] ='') {
		update_post_meta($post->ID, 'startN', NULL);
	}
	if(isset ($_POST['endT_id']) ) {
		update_post_meta($post->ID, 'endT', $_POST['endT_id']);
	}
	if ( $_POST['endT_id'] ='') {
		update_post_meta($post->ID, 'endT', NULL);
	}
	if(isset ($_POST['endN_id']) ) {
		update_post_meta($post->ID, 'endN', $_POST['endN_id']);
	}
	if ( $_POST['endN_id'] ='') {
		update_post_meta($post->ID, 'endN', NULL);
	}
	if(isset ($_POST['venue_id']) ) {
		update_post_meta($post->ID, 'venue', $_POST['venue_id']);
	}
	if ( $_POST['venueN_id'] ='') {
		update_post_meta($post->ID, 'venue', NULL);
	}
}

// Add admin menu
function jbfj_events_settings_menu() {
	require plugin_dir_path(__FILE__) . 'lib/jbfj-events-settings.php';
	//add submenu
	add_submenu_page('edit.php?post_type=event', 'Events Page Settings', 'Events Page Settings', 'manage_options', 'event-settings', 'jbfj_events_settings_page');
}
add_action('admin_menu', 'jbfj_events_settings_menu');
	
// Load default template
add_filter( 'template_include', 'jbfj_event_template', 99 );

function jbfj_event_template( $template ) {	
	
	global $wpdb;
	$p = get_option( 'jbfj_event_page' );
	$p2 = $p["page_to_use"];
	
	if ( is_page( $p2 ) && $p2 != 0 ) {
	
		$t = locate_template( array('jbfjevents.php'), true );
		
		if ( !$t ) {
			
			$t = dirname(__FILE__). "/lib/templates/jbfjevents.php";
		
			return $t;
		}
		
	} else {

		return $template; 
	
	}

}