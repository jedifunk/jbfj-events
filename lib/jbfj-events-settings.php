<?php 

add_action('admin_init', 'jbfj_events_admin_init');

function jbfj_events_admin_init() {

	if( false == get_option( 'jbfj_event_options' ) ) {	
		add_option( 'jbfj_event_options' );
	}
	// Main Event settings
	add_settings_section('jbfj_event_main', 'Events Page Settings', 'events_page_section_callback', 'jbfj_events_admin');
	add_settings_field(
		'events_page',
		'Page:',
		'page_callback',
		'jbfj_events_admin',
		'jbfj_event_main'
	);
	
	register_setting('jbfj_event_options', 'jbfj_event_page', 'jbfj_events_validate');
}

function events_page_section_callback() {
	global $wpdb;
	$eventPage = get_option( 'jbfj_event_page' );
	$pageID = $eventPage["page_to_use"];
	$page = get_post($pageID);

	if ($pageID == 0) {
		echo '<p>What Page should use the Events template?</p>';
	} else {
		echo '<p>The events are set to display on the <strong>' . $page->post_title .'</strong> page currently.</p>';	
	}
}

function page_callback() {
	$options = get_option('jbfj_event_options');
	$dd_options = array(
		'name' => 'jbfj_event_page[page_to_use]',
		'show_option_none' => __( 'Choose Page' ),
		'option_none_value' => '0',
		'selected' => $page_to_use
	);
	wp_dropdown_pages($dd_options);
}
// Create the Events Settings page
function jbfj_events_settings_page() {
	
	// check for sufficient admin permissions
	if ( !current_user_can('manage_options') ) {
		wp_die(__('You do not have sufficient permissions to access this page') );
	}
	
	//variables for the fields & options
	
?>

<div class="wrap">
	<?php screen_icon('options-general'); ?><h2>jbfj Events Page Settings</h2>
	<?php settings_errors(); ?>
	
	<form method="post" action="options.php">
		<?php 
			settings_fields('jbfj_event_options');
			do_settings_sections('jbfj_events_admin'); 
		
			submit_button(); 
		?>
	</form>
</div>

<?php }

function jbfj_events_validate( $input ) {
	$input['page_to_use'] = (int) $input['page_to_use'];
	return $input;
}