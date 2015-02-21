<?php
/* Template for event content output 
 */
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