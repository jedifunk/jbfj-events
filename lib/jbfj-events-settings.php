<?php 
// Create Admin Menu
if (is_admin() ){
	add_action('admin_menu', 'jbfj_events_create_menu' );
	add_action( 'admin_init', 'jbfj_events_register_settings' );
}

// Create Events Settings Page
function jbfj_events_create_menu() {
    add_submenu_page('edit.php?post_type=event', 'Events Settings', 'Events Settings', 'administrator', 'events-settings.php', 'jbfj_events_settings_page');
}

// Create the Events Settings page
function jbfj_events_settings_page() {
?>
<div class="wrap">
	<h2>jbfj Events Page Settings</h2>
	<?php settings_errors(); ?>	
	<form method="post" action="options.php">
		<?php
			do_settings_sections('events-settings.php');
			settings_fields('jbfj_event_options'); 		
			submit_button(); 
		?>
	</form>
</div>
<?php }

// Create Settings Section and Settings
function jbfj_events_register_settings() {
	
	// Create Section
	add_settings_section('jbfj_event_main', 'Events Page Settings', false, 'events-settings.php');
	
	// Add Settings
	add_settings_field(
		'jbfj_events_page',
		'Page:',
		'page_callback',
		'events-settings.php',
		'jbfj_event_main'
	);	
	add_settings_field(
		'jbfj_event_message',
		'Message: ',
		'message_callback',
		'events-settings.php',
		'jbfj_event_main'
	);
	
	register_setting( 'jbfj_event_options', 'jbfj_event_page', 'jbfj_events_validate' );
	register_setting( 'jbfj_event_options', 'jbfj_event_message' );
}

function page_callback() {
	global $wpdb;
	$eventPage = get_option( 'jbfj_event_page' );
	$pageID = $eventPage["page_to_use"];
	$page = get_post($pageID);

	if ($pageID == 0) {
		echo '<p>What Page should use the Events template?</p>';
	} else {
		echo '<p>The events are set to display on the <strong>' . $page->post_title .'</strong> page currently.</p>';	
	}
	
	$dd_options = array(
		'name' => 'jbfj_event_page[page_to_use]',
		'show_option_none' => __( 'Choose Page' ),
		'option_none_value' => '0',
		'selected' => $page_to_use
	);
	wp_dropdown_pages($dd_options);
}

function message_callback(){
	$message = esc_attr( get_option('jbfj_event_message') );
	echo '<textarea rows="5" cols="50" name="jbfj_event_message" id="jbfj_event_message">'. $message .'</textarea>';
}

function jbfj_events_validate( $input ) {
	$input['page_to_use'] = (int) $input['page_to_use'];
	return $input;
}