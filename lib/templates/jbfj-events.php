<?php
/**
 * Template: JBFJ Simple Events
 * This template is deprecated, as it uses 960grid not bootstrap
 */

get_header(); ?>

	<div id="primary" class="grid_16 content-area">
		<div id="content" class="site-content" role="main">

			<?php if ( has_post_thumbnail() ) : the_post_thumbnail(); else : ?>
				<h1 class="entry-title"><?php the_title(); ?></h1>
			<?php endif; ?>
			
			<?php
			$current = date('Y-m-d, g:i A');
			
			$args = array(
				'post_type' => 'event',
				'posts_per_page' => -1,
				'meta_key' => 'date',
				'orderby' => 'meta_value',
				'order' => 'ASC',
				'meta_query' => array(
					array(
						'key' => 'date',
						'value' => $current,
						'compare' => '>'
					)
				)
			);
			
			$loop = new WP_Query($args);

			if ( $loop -> have_posts() ) :
				while( $loop -> have_posts() ) : $loop -> the_post();
			?>
				<article <?php post_class('jbfj-events'); ?>>
					<header class="event-header">
						<h2 class="event-title"><?php the_title(); ?></h2>
						<div class="event-details">
							<aside class="event-date">
							<?php 
							 	$date = get_post_meta($post->ID, 'date', true); 
							 	$date = strtotime($date);
							 	$time = get_post_meta($post->ID, 'startT', true);
							 	
							 	if ( is_numeric( $time ) ) :
							 	
							 		echo date('F j, Y', $date); ?> <span class="event-time"><?php echo date('g:i A', $time); ?></span></aside>
							 	<?php else :
							 		
							 		echo date('F j, Y', $date); ?> <span class="event-time"><?php echo $time; ?></span></aside>
							 	
							 	<?php endif; ?>
							<aside class="event-venue"><?php echo get_post_meta($post->ID, 'venue', true); ?></aside>
							<aside class="event-address"><?php echo get_post_meta($post->ID, 'address', true); ?></aside>
						</div>
					</header>
					
					<section class="event-description clearfix">
						<?php the_content(); ?>
					</section>
				</article>
				
				<?php endwhile; ?>
				
			<?php else : ?> 
			
			<article class="no-dates">
				<?php 
					$message = get_option( 'jbfj_event_message' );
					
					if( !empty($message) ) {
						echo $message;
					} else {
						echo '<p>There are currently no upcoming events at this time. <br/>Please check back again soon.</p>';
					} ?>
			</article>
					
			<?php endif; wp_reset_postdata(); ?>

		</div><!-- #content -->
	</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>