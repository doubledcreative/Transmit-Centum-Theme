<?php
/**
 * Template Name: Home Page 2014
 *
 * A custom page template without sidebar.
 *
 * The "Template Name:" bit above allows this to be selectable
 * from a dropdown menu on the edit page screen.
 *
 * @package WordPress
 * @subpackage purepress
 * @since purepress 1.0
 */

get_header();
	
	
 while (have_posts()) : the_post(); ?>
	
		<div class="container">
			
			<div class="homeimg slider1">
				<a href="//transmitstartups.co.uk/news/2000-entrepreneurs-open-business-thanks-transmit-start-ups/"><img src="//transmitstartups.co.uk/wp-content/uploads/dukes-pub.jpg" alt="#2000startuploans" width="auto" height="auto" /></a>
			</div>
			
			<!--<div class="homeimg slider2">
				<a href="//transmitstartups.co.uk/apply-now/"><img src="//transmitstartups.co.uk/wp-content/uploads/dukes-pub.jpg" alt="slider" width="auto" height="auto" /></a>
			</div>-->
		
			<div <?php post_class('post home clearfix'); ?> id="post-<?php the_ID(); ?>" >
				<?php the_content() ?>
			</div>
		
		</div><!-- end container -->
	
	<?php endwhile; // End the loop. Whew.  ?>

<?php get_footer(); ?>