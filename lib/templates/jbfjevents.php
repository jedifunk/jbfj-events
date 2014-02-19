<?php
/**
 * Template: JBFJ Simple Events
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<div id="content" class="site-content" role="main">

			<?php if ( has_post_thumbnail() ) : the_post_thumbnail(); else : ?>
				<h1 class="entry-title"><?php the_title(); ?></h1>
			<?php endif; ?>
			
			<?php
			$current = date('Y-m-d g:i A');
			
			$args = array(
				'post_type' => 'event',
				'posts_per_page' => -1,
				'meta_key' => 'date',
				'orderby' => 'meta_value',
				'order' => 'ASC'
			);
			
			$loop = new WP_Query($args);

			if ( $loop -> have_posts() ) :
				while( $loop -> have_posts() ) : $loop -> the_post();
				
				$post_date = get_post_meta($post->ID, 'date', true);
				$post_startT = get_post_meta($post->ID, 'startT', true);
				$post_all = $post_date. ' '.$post_startT;
				
				if ( $post_all >= $current ) :
			?>
				<article <?php post_class('jbfj-events'); ?>>
					<header class="event-header">
						<h2 class="event-title"><?php the_title(); ?></h2>
						<div class="event-details">
							<aside class="event-date">
							<?php 
							 	$date = get_post_meta($post->ID, 'date', true); 
							 	$date = strtotime($date);
							 	echo date('F j, Y', $date); ?> <span class="event-time"><?php echo get_post_meta($post->ID, 'startT', true); ?></span></aside>
							<aside class="event-venue"><?php echo get_post_meta($post->ID, 'venue', true); ?></aside>
						</div>
					</header>
					
					<section class="event-description">
						<?php the_content(); ?>
					</section>
				</article>
				
				<?php endif; endwhile; ?>
				
			<?php else : ?> 
			
			<article class="no-dates">
				<h3>There are currently no upcoming events at this time. <br/>Please check back again soon.</h3>
			</article>
			
			
			<?php endif; wp_reset_postdata(); ?>

		</div><!-- #content -->
	</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>