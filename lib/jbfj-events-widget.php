<?php
add_action( 'widgets_init', function(){
     register_widget( 'jbfj_Events_Widget' );
});

class jbfj_Events_Widget extends WP_Widget {

	/***** Sets up the widgets name etc *****/
	function __construct() {
		$widget_ops = array('classname' => 'widget-jbfj-events', 'description' => __( "Your site&#8217;s upcoming Events.") );
		parent::__construct('jbfj-events', __('jbfj Events Widget'), $widget_ops);
		$this->alt_option_name = 'widget-jbfj-events';

		add_action( 'save_post', array($this, 'flush_widget_cache') );
		add_action( 'deleted_post', array($this, 'flush_widget_cache') );
		add_action( 'switch_theme', array($this, 'flush_widget_cache') );
	}

	/***** Outputs the content of the widget *****/
	function widget( $args, $instance ) {

		$cache = wp_cache_get('widget_jbfj_events', 'widget');

		if ( !is_array($cache) )
			$cache = array();

		if ( ! isset( $args['widget_id'] ) )
			$args['widget_id'] = $this->id;

		if ( isset( $cache[ $args['widget_id'] ] ) ) {
			echo $cache[ $args['widget_id'] ];
			return;
		}

		ob_start();
		extract($args);
		
		// outputs the content of the widget
		$title = ( ! empty( $instance['title'] ) ) ? $instance['title'] : __( 'Upcoming Events' );
		$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );
		$number = ( ! empty( $instance['number'] ) ) ? absint( $instance['number'] ) : 10;
		if ( ! $number )
 			$number = 10;
		$show_date = isset( $instance['show_date'] ) ? $instance['show_date'] : false;
		$show_venue = isset( $instance['show_venue'] ) ? $instance['show_venue'] : false;
		$show_address = isset( $instance['show_address'] ) ? $instance['show_address'] : false;
		$show_link = isset( $instance['show_link'] ) ? $instance['show_link'] : false;
		$link_text = ( ! empty( $instance['link_text'] ) ) ? $instance['link_text'] : __( 'See all upcoming events' );
		$ul_class = ( ! empty( $instance['ul_class'] ) ) ? $instance['ul_class'] : __( '' );
		$li_class = ( ! empty( $instance['li_class'] ) ) ? $instance['li_class'] : __( '' );
		
		// Get Events page ID
		$pID = jbfj_events_page_id();
		
		//Get Current date and event date
		$current = date('Y-m-d g:i A');

		$e = new WP_Query( apply_filters( 
			'widget_events_args', 
			array( 
				'post_type' => 'event',
				'meta_key' => 'date',			
				'orderby' => 'meta_value',
				'order' => 'ASC',
				'posts_per_page' => $number, 
				'no_found_rows' => true, 
				'post_status' => 'publish', 
				'ignore_sticky_posts' => true,
				'meta_query' => array(
					array(
						'key' => 'date',
						'value' => $current,
						'compare' => '>='
					)
				)
			) 
		) );

		
		if ($e->have_posts()  ) :
?>
		<?php echo $before_widget; ?>
		<?php if ( $title ) echo $before_title . $title . $after_title; ?>

		<ul <?php if ( $ul_class ) echo 'class="' . $ul_class . '"'; ?>>
		<?php while ( $e->have_posts() ) : $e->the_post(); ?>
			<li <?php if ( $li_class ) echo 'class="' . $li_class . '"'; ?>>
				<section class="event-title"><?php get_the_title() ? the_title() : the_ID(); ?></section>
				<?php if ( $show_date ) : ?>
					<section class="event-date"><?php 
					 	$date = get_post_meta( get_the_ID(), 'date', true); 
					 	$date = strtotime($date);
					 	echo date('F j, Y', $date); ?>
					 	<span class="event-time"><?php echo get_post_meta(get_the_ID(), 'startT', true); ?></span>
					 </section>
				<?php endif; ?>
				<?php if ( $show_venue ) : ?>
					<section class="event-venue"><?php echo get_post_meta(get_the_ID(), 'venue', true); ?></section>
				<?php endif; ?>
				<?php if ( $show_address ) : ?>
					<section class="event-address"><?php echo get_post_meta(get_the_ID(), 'address', true); ?></section>
				<?php endif; ?>
			</li>
		<?php endwhile; ?>
		</ul>
		<?php if ( $show_link ) : ?>
			<a class="event-page-link" href="<?php echo get_permalink( $pID ); ?>"><?php echo $link_text; ?></a>
		<?php endif; ?>

		<?php echo $after_widget; ?>

		
<?php
		
		// Reset the global $the_post as this query will have stomped on it
		wp_reset_postdata();

		endif;
		
		$cache[$args['widget_id']] = ob_get_flush();
		wp_cache_set('widget_jbfj_events', $cache, 'widget');
	}

	/***** Options form on admin *****/
	function form( $instance ) {
		// outputs the options form on admin		
		$title = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
		$number = isset( $instance['number'] ) ? absint( $instance['number'] ) : 5;
		$show_date = isset( $instance['show_date'] ) ? (bool) $instance['show_date'] : false;
		$show_venue = isset( $instance['show_venue'] ) ? (bool) $instance['show_venue'] : false;
		$show_address = isset( $instance['show_address'] ) ? (bool) $instance['show_address'] : false;
		$show_link = isset( $instance['show_link'] ) ? (bool) $instance['show_link'] : false;
		$link_text = isset( $instance['link_text'] ) ? esc_attr( $instance['link_text'] ) : '';
		$ul_class = isset ( $instance['ul_class'] ) ? esc_attr( $instance['ul_class'] ) : '';
		$li_class = isset ( $instance['li_class'] ) ? esc_attr( $instance['li_class'] ) : '';
?>
		<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" /></p>

		<p><label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e( 'Events to show:' ); ?></label>
		<input id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="text" value="<?php echo $number; ?>" size="3" /></p>

		<p><input class="checkbox" type="checkbox" <?php checked( $show_date ); ?> id="<?php echo $this->get_field_id( 'show_date' ); ?>" name="<?php echo $this->get_field_name( 'show_date' ); ?>" />
		<label for="<?php echo $this->get_field_id( 'show_date' ); ?>"><?php _e( 'Display date?' ); ?></label></p>
		
		<p><input class="checkbox" type="checkbox" <?php checked( $show_venue ); ?> id="<?php echo $this->get_field_id( 'show_venue' ); ?>" name="<?php echo $this->get_field_name( 'show_venue' ); ?>" />
		<label for="<?php echo $this->get_field_id( 'show_venue' ); ?>"><?php _e( 'Display venue?' ); ?></label></p>
		
		<p><input class="checkbox" type="checkbox" <?php checked( $show_address ); ?> id="<?php echo $this->get_field_id( 'show_address' ); ?>" name="<?php echo $this->get_field_name( 'show_address' ); ?>" />
		<label for="<?php echo $this->get_field_id( 'show_address' ); ?>"><?php _e( 'Display address?' ); ?></label></p>
		
		<p><input class="checkbox" type="checkbox" <?php checked( $show_link ); ?> id="<?php echo $this->get_field_id( 'show_link' ); ?>" name="<?php echo $this->get_field_name( 'show_link' ); ?>" />
		<label for="<?php echo $this->get_field_id( 'show_link' ); ?>"><?php _e( 'Show link to Events page?' ); ?></label></p>
		
		<p><label for="<?php echo $this->get_field_id( 'link_text' ); ?>"><?php _e( 'Link text:' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'link_text' ); ?>" name="<?php echo $this->get_field_name( 'link_text' ); ?>" type="text" value="<?php echo $link_text; ?>" /></p>
		
		<p><label for="<?php echo $this->get_field_id( 'ul_class' ); ?>"><?php _e( 'List Class:' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'ul_class' ); ?>" name="<?php echo $this->get_field_name( 'ul_class' ); ?>" type="text" value="<?php echo $ul_class; ?>" /></p>
		
		<p><label for="<?php echo $this->get_field_id( 'li_class' ); ?>"><?php _e( 'List Item Class:' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'li_class' ); ?>" name="<?php echo $this->get_field_name( 'li_class' ); ?>" type="text" value="<?php echo $li_class; ?>" /></p>

<?php
	}

	/***** Processing widget options on save *****/
	function update( $new_instance, $old_instance ) {
		// processes widget options to be saved		
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['number'] = (int) $new_instance['number'];
		$instance['show_date'] = isset( $new_instance['show_date'] ) ? (bool) $new_instance['show_date'] : false;
		$instance['show_venue'] = isset( $new_instance['show_venue'] ) ? (bool) $new_instance['show_venue'] : false;
		$instance['show_address'] = isset( $new_instance['show_address'] ) ? (bool) $new_instance['show_address'] : false;
		$instance['show_link'] = isset( $new_instance['show_link'] ) ? (bool) $new_instance['show_link'] : false;
		$instance['link_text'] = strip_tags($new_instance['link_text']);
		$instance['ul_class'] = strip_tags($new_instance['ul_class']);
		$instance['li_class'] = strip_tags($new_instance['li_class']);
		$this->flush_widget_cache();

		$alloptions = wp_cache_get( 'alloptions', 'options' );
		if ( isset($alloptions['widget_jbfj_events']) )
			delete_option('widget_jbfj_events');

		return $instance;
	}
	
	function flush_widget_cache() {
		wp_cache_delete('widget_jbfj_events', 'widget');
	}
}