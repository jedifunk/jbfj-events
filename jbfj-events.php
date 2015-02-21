<?php
/* Plugin Name: JBFJ Events
 * Author: Bryce Flory
 * URI: www.bryceflory.com
 * Version: 1.9.4
 */

// Enqueue necessary scripts
function jbfj_events_scripts() {
	wp_enqueue_script('jquery-ui-datepicker', array('jquery'));
	wp_enqueue_script('events-scripts', plugins_url('jbfjwp-events/lib/js/jbfj-events.js'));
	wp_enqueue_style('datepicker-css', plugins_url('jbfjwp-events/lib/css/jquery-ui-1.10.3.custom.min.css'));
	wp_enqueue_script('timepicker', plugins_url('jbfjwp-events/lib/js/jquery.timepicker.min.js'), array('jquery'));
	wp_enqueue_style('timepicker-css', plugins_url('jbfjwp-events/lib/css/jquery.timepicker.css'));
}
add_action( 'admin_init', 'jbfj_events_scripts' );

function jbfj_frontend_events_scripts() {
	
		wp_enqueue_style('jbfj-events', plugins_url('jbfjwp-events/lib/css/jbfj-simple-events.css'));
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
        'menu_icon' => plugins_url('jbfjwp-events/lib/i/calendar-month.png'),
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
	$eventdate = get_post_meta($post->ID, 'date', true);
	$startT = get_post_meta($post->ID, 'startT', true);
	$endT = get_post_meta($post->ID, 'endT', true);
	$venue = get_post_meta($post->ID, 'venue', true);
	$address = get_post_meta($post->ID, 'address', true);
	
	// Get WP time formats
	$time_format = get_option('time_format');
	
	// If date empty add time 
	if ( $eventdate  == null ) {
		$startT = 0;
	}
	
	// Convert to human readable time
	$startT = date($time_format, $startT);
?>
<table class="form-table">
	<tbody>
		<tr>
			<th scope="row"><label for="eventdate">Date:</label></th>
			<td><input id="eventdate" name="eventdate" class="jDate" type="text" value="<?php echo esc_attr($eventdate); ?>" /></td>
		</tr>
		<tr>
			<th scope="row"><label for="startT">Start Time:</label></th>
			<td><input id="startT" name="startT" class="startT timepicker" type="text" value="<?php echo esc_attr($startT); ?>" /></td>
		</tr>
		<tr>
			<th scope="row"><label for="endT">End Time:</label></th>
			<td><input id="endT" name="endT" class="endT timepicker" type="text" value="<?php echo esc_attr($endT); ?>" /></td>
		</tr>
		<tr>
			<th scope="row"><label for="venue">Venue:</label></th>
			<td><input id="venue" name="venue" class="venue regular-text" type="text" value="<?php echo esc_attr($venue); ?>" /></td>
		</tr>
		<tr>
			<th scope="row"><label for="address">Address:</label></th>
			<td><input id="address" name="address" class="address regular-text" type="text" value="<?php echo esc_attr($address); ?>" /></td>
		</tr>
	</tbody>
</table>
<?php }

// Save to DB
function add_event_detail($post_id) {
	global $post;
	
	// checking for the 'save' status
    $is_autosave = wp_is_post_autosave( $post_id );
    $is_revision = wp_is_post_revision( $post_id );
 
    // exit depending on the save status
    if ( $is_autosave || $is_revision ) {
        return;
    }

		
	if(isset ($_POST['date']) ) {

		update_post_meta($post_id, 'date', strtotime( $_POST['date'] ) );
	}
	if( isset( $_POST['startT'] ) ) {

		update_post_meta( $post_id, 'startT', strtotime( $_POST['startT'] ) );		
	}
	if( isset( $_POST['endT'] ) ) {

		update_post_meta( $post_id, 'endT', strtotime( $_POST['endT'] ) );		
	}
	if( isset( $_POST['venue'] ) ) {

		update_post_meta( $post_id, 'venue', sanitize_text_field( $_POST['venue'] ) );		
	}
	if( isset( $_POST['address'] ) ) {

		update_post_meta( $post_id, 'address', sanitize_text_field( $_POST['address'] ) );		
	}

}

// Add admin menu
require_once('lib/jbfj-events-settings.php');

//Add widget
require_once('lib/jbfj-events-widget.php');

/********************** DUPLICATION ********************/
// Add Duplicate link to Events Listing Screen
function rd_duplicate_post_as_draft(){
	global $wpdb;
	if (! ( isset( $_GET['post']) || isset( $_POST['post'])  || ( isset($_REQUEST['action']) && 'rd_duplicate_post_as_draft' == $_REQUEST['action'] ) ) ) {
		wp_die('No post to duplicate has been supplied!');
	}
 
	/*
	 * get the original post id
	 */
	$post_id = (isset($_GET['post']) ? $_GET['post'] : $_POST['post']);
	/*
	 * and all the original post data then
	 */
	$post = get_post( $post_id );
 
	/*
	 * if you don't want current user to be the new post author,
	 * then change next couple of lines to this: $new_post_author = $post->post_author;
	 */
	$current_user = wp_get_current_user();
	$new_post_author = $current_user->ID;
 
	/*
	 * if post data exists, create the post duplicate
	 */
	if (isset( $post ) && $post != null) {
 
		/*
		 * new post data array
		 */
		$args = array(
			'comment_status' => $post->comment_status,
			'ping_status'    => $post->ping_status,
			'post_author'    => $new_post_author,
			'post_content'   => $post->post_content,
			'post_excerpt'   => $post->post_excerpt,
			'post_name'      => $post->post_name,
			'post_parent'    => $post->post_parent,
			'post_password'  => $post->post_password,
			'post_status'    => 'draft',
			'post_title'     => $post->post_title,
			'post_type'      => $post->post_type,
			'to_ping'        => $post->to_ping,
			'menu_order'     => $post->menu_order
		);
 
		/*
		 * insert the post by wp_insert_post() function
		 */
		$new_post_id = wp_insert_post( $args );
 
		/*
		 * get all current post terms ad set them to the new post draft
		 */
		$taxonomies = get_object_taxonomies($post->post_type); // returns array of taxonomy names for post type, ex array("category", "post_tag");
		foreach ($taxonomies as $taxonomy) {
			$post_terms = wp_get_object_terms($post_id, $taxonomy, array('fields' => 'slugs'));
			wp_set_object_terms($new_post_id, $post_terms, $taxonomy, false);
		}
 
		/*
		 * duplicate all post meta
		 */
		$post_meta_infos = $wpdb->get_results("SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE post_id=$post_id");
		if (count($post_meta_infos)!=0) {
			$sql_query = "INSERT INTO $wpdb->postmeta (post_id, meta_key, meta_value) ";
			foreach ($post_meta_infos as $meta_info) {
				$meta_key = $meta_info->meta_key;
				$meta_value = addslashes($meta_info->meta_value);
				$sql_query_sel[]= "SELECT $new_post_id, '$meta_key', '$meta_value'";
			}
			$sql_query.= implode(" UNION ALL ", $sql_query_sel);
			$wpdb->query($sql_query);
		}
 
 
		/*
		 * finally, redirect to the edit post screen for the new draft
		 */
		wp_redirect( admin_url( 'post.php?action=edit&post=' . $new_post_id ) );
		exit;
	} else {
		wp_die('Post creation failed, could not find original post: ' . $post_id);
	}
}
add_action( 'admin_action_rd_duplicate_post_as_draft', 'rd_duplicate_post_as_draft' );
 
/*
 * Add the duplicate link to action list for post_row_actions
 */
add_filter( 'post_row_actions', 'rd_duplicate_post_link', 10, 2 );

function rd_duplicate_post_link( $actions, $post ) {
	if (current_user_can('edit_posts') && ($post->post_type == 'event')) {
		$actions['duplicate'] = '<a href="admin.php?action=rd_duplicate_post_as_draft&amp;post=' . $post->ID . '" title="Duplicate this item" rel="permalink">Duplicate</a>';
	}
	return $actions;
}


/********************** QUICK EDIT ********************/
// Add Event Date Column to Events Listing
add_filter('manage_posts_columns', 'jbfjevent_add_post_columns', 10, 2);
 
function jbfjevent_add_post_columns($columns, $post_type) {
    switch ($post_type) {
    
    case 'event':
    	
    	$new_columns = array();
    	foreach( $columns as $key => $value ) {
	    	$new_columns[ $key ] = $value;
	    	if ( $key == 'title' )
	    		$new_columns[ 'event_date' ] = 'Event Date';
    	}
    	return $new_columns;
                       
    }
    return $columns;
}

// Post Data to New Column
add_action('manage_event_posts_custom_column', 'jbfjevent_render_post_columns', 10, 2);
 
function jbfjevent_render_post_columns($column_name, $id) {
    
    switch ($column_name) {
    case 'event_date':
        // Show the Event Date
        $eventdate = get_post_meta($id, 'date', true);
        echo $eventdate;               
    }
}

// Add Custom Box to Quick Edit
add_action('quick_edit_custom_box',  'jbfjevent_add_quick_edit', 10, 2);
 
function jbfjevent_add_quick_edit($column_name, $post_type) {
    if ($column_name != 'event_date') return;
    ?>
    <fieldset class="inline-edit-col-left">
        <div class="inline-edit-col column-<?php echo $column_name; ?>">
            <span class="title">Event Date</span>
            <input id="eventdate_noncename" type="hidden" name="eventdate_noncename" value="" />
            <input id="eventdate" name="eventdate" class="jDate" type="text" value="" />
        </div>
    </fieldset>
     <?php
}

// Save quick edit custom box data
add_action('save_post', 'jbfjevent_save_quick_edit_data');   

function jbfjevent_save_quick_edit_data($post_id) {  
  // verify if this is an auto save routine.         
  if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE )          
      return $post_id;         
  // Check permissions     
  if ( 'event' == $_POST['post_type'] ) {         
    if ( !current_user_can( 'edit_page', $post_id ) )             
      return $post_id;     
  } else {         
    if ( !current_user_can( 'edit_post', $post_id ) )         
    return $post_id;     
  }        
  // Authentication passed now we save the data 
  if (isset($_POST['eventdate']) ) {
        $eventdate = esc_attr($_REQUEST['eventdate']);
        if ($eventdate) {
            update_post_meta($post_id, 'date', $eventdate);
        }
        
    }

    return $eventdate;
}

// Use Javascript to update quick edit menu
add_action('admin_footer', 'jbfjevent_quick_edit_javascript');
 
function jbfjevent_quick_edit_javascript() {
    global $current_screen;
    if (($current_screen->post_type != 'event')) return;
 
    ?>
	<script type="text/javascript">
	function set_date_value(fieldValue) {
	        // refresh the quick menu properly
	        inlineEditPost.revert();
	        console.log(fieldValue);
	        jQuery('#eventdate').val(fieldValue);
	}
	</script>
 <?php 
}

// Link Javascript to Quick Edit link to display field data
add_filter('post_row_actions', 'jbfjevent_expand_quick_edit_link', 10, 2);

function jbfjevent_expand_quick_edit_link($actions, $post) {     
    global $current_screen;     
    if (($current_screen->post_type != 'event')) return $actions;
    
    $nonce = wp_create_nonce( 'date_'.$post->ID);
    $currentdate = get_post_meta( $post->ID, 'date', TRUE);
    $actions['inline hide-if-no-js'] = '<a href="#" class="editinline" title="';     
    $actions['inline hide-if-no-js'] .= esc_attr( __( 'Edit this item inline' ) ) . '"';
    $actions['inline hide-if-no-js'] .= " onclick=\"set_date_value('{$currentdate}')\" >";
    $actions['inline hide-if-no-js'] .= __( 'Quick Edit' );
    $actions['inline hide-if-no-js'] .= '</a>';
    return $actions;
}


/********************** TEMPLATE OUTPUT ********************/
// Load default template
add_filter( 'template_include', 'jbfj_event_template', 99 );

function jbfj_event_template( $template ) {	
	
	global $wpdb;
	$p = get_option( 'jbfj_event_page' );
	$p2 = $p["page_to_use"];
	
	if ( is_page( $p2 ) && $p2 != 0 ) {
	
		$t = locate_template( array('jbfj-events.php'), true );
		
		if ( !$t ) {
			
			$t = dirname(__FILE__). "/lib/templates/jbfj-events.php";
		
			return $t;
		}
		
	} else {

		return $template; 
	
	}

}

// Get Event Page ID
function jbfj_events_page_id() {
	global $pageID;
	$eventPage = get_option( 'jbfj_event_page' );
	$pageID = $eventPage["page_to_use"];
	return $pageID;
	echo $pageID;
}

// Optional Function for output in any template
function custom_orderby($orderby) {
	return 'mt1.meta_value, mt2.meta_value ASC';
}
function jbfj_events( $max_posts = -1 ) {
	global $post;

	$cd = current_time('Y-m-d');
	
	$args = array(
		'post_type' => 'event',
		'posts_per_page' => $max_posts,
		'meta_key' => 'date',
		'meta_query' => array(
			'relation' => 'AND',
			array(
				'key' => 'date',
				'value' => $cd,
				'compare' => '>=',
				'type' => 'DATE'
			),
			array(
				'key' => 'startT'
			)
		)
	);
	add_filter( 'posts_orderby', 'custom_orderby' );
	$loop = new WP_Query( apply_filters( 'jbfj_events_query', $args) );
	remove_filter( 'posts_orderby', 'custom_orderby' );

	if ( $loop -> have_posts() ) :
		while( $loop -> have_posts() ) : $loop -> the_post();
			
			jbfj_events_get_template_part( 'content', 'events' );

		endwhile; 
		
	else : ?> 
	
	<article class="no-dates">
		<?php 
			$message = get_option( 'jbfj_event_message' );
			
			if( !empty($message) ) {
				echo $message;
			} else {
				echo '<p>There are currently no upcoming events at this time. <br/>Please check back again soon.</p>';
			} ?>
	</article>
	
	
	<?php endif; wp_reset_postdata();
}

function jbfj_events_get_template_part( $slug, $name = '' ) {
	$template = '';

	// Look in yourtheme/slug-name.php
	if ( $name ) {
		$template = locate_template( array( "{$slug}-{$name}.php" ) );
	}

	// Get default slug-name.php
	if ( ! $template ) {
		$template = dirname(__FILE__). "/lib/templates/content-events.php";
	}

	if ( $template ) {
		load_template( $template, false );
	}
}

// Shortcode for Event output
function jbfj_events_scode( $atts ) {

	// Create Parameters
	extract(shortcode_atts(array(
		'max_posts' => ''
	), $atts) );
	
	ob_start();
	jbfj_events( esc_attr( $max_posts ) );
	return ob_get_clean();
}
add_shortcode("jbfj_events", "jbfj_events_scode");