<?php
/**
 * The main template file.
 * @package WordPress
 */
get_header(); ?>


<!-- 960 Container -->
<div class="container">

	<div class="sixteen columns">
	
		<!-- Page Title -->
		<div id="page-title">
			<h1> <?php
                    if (is_day()) :
                        printf(__('Daily Archives:<span> %s </span>', 'purepress'), get_the_date());
                    elseif (is_month()) :
                        printf(__('Monthly Archives:<span> %s</span>', 'purepress'), get_the_date('F Y'));
                    elseif (is_year()) :
                        printf(__('Yearly Archives:<span> %s</span>', 'purepress'), get_the_date('Y'));
                    else :
                        _e('Blog Archives', 'purepress');
                    endif;
                    ?></h1>
			<div id="bolded-line"></div>
		</div>
		<!-- Page Title / End -->

	</div>
</div>
<!-- 960 Container / End -->

<!-- 960 Container -->
<div class="container">
<?php 
if(ot_get_option('blog_layout') == 'left-sidebar'){
	get_sidebar();
}
?>
<!-- Blog Posts
	================================================== -->
	<div class="twelve columns">
	
		<!-- Post -->

		<?php while (have_posts()) : the_post(); ?>

		<div <?php post_class('loop'); ?> id="post-<?php the_ID(); ?>" >

			<?php
			// Check what to display above post title (image,vide, slideshow)
			global $shortname;
			$feat_type = get_post_meta($post->ID, 'incr_feattype', true);

			if(function_exists( 'get_post_format' ) && get_post_format( $post->ID ) != 'gallery' && get_post_format( $post->ID ) != 'video' && has_post_thumbnail()) { 
				$thumbbig = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'full' );
				$alt_text = get_post_meta($post->ID, '_wp_attachment_image_alt', true);
				?>
				<div class="post-img picture">
					<a href="<?php echo $thumbbig[0];?>"  rel="image-gallery" title="<?php the_title(); ?>">
						<?php the_post_thumbnail(); ?>
						<div class="image-overlay-link"></div>
					</a>
				</div>
				<?php } ?>

				<?php
					if (( function_exists( 'get_post_format' ) && 'video' == get_post_format( $post->ID ) )  ) {
					global $wp_embed;
					$videolink = get_post_meta($post->ID, 'incr_video_link', true);
					$post_embed = $wp_embed->run_shortcode('[embed  width="600" height="360"]'.$videolink.'[/embed]') ;
					echo '<div class="embed">'.$post_embed.'</div>';
				}


				if (( function_exists( 'get_post_format' ) && 'gallery' == get_post_format( $post->ID ) )  ) {
					$args = array(
                            'numberposts' => -1, // Using -1 loads all posts
                            'orderby' => 'menu_order', // This ensures images are in the order set in the page media manager
                            'order'=> 'ASC',
                            'post_mime_type' => 'image', // Make sure it doesn't pull other resources, like videos
                            'post_parent' => $post->ID, // Important part - ensures the associated images are loaded
                            'post_status' => null,
                            'post_type' => 'attachment'
                            );

					$images = get_children( $args );
					
                    	// continued from above ...
					if($images){ ?>
					<div class="flexslider">
						<ul class="slides"> 
							<?php foreach($images as $image){ 
								$attachment = wp_get_attachment_image_src($image->ID, 'large');
								$thumb = wp_get_attachment_image_src($image->ID, 'post-thumbnail'); 
								?>
								<li>
									<div class="post-img">
										<a href="<?php echo $attachment[0] ?>"  rel="image-gallery" title="<?php echo $image->post_title; ?>" >
											<img src="<?php echo $thumb[0] ?>" alt="<?php echo $image->post_title; ?>" />
											<div class="overlay zoom"></div>
										</a>
									</div>
								</li>
							</li>
							<?php	} ?>
						</ul>  
					</div>
					<?php } 
				}
				?>
				<?php if ( function_exists( 'get_post_format' )) {  ?>
				<a href="#" class="post-icon <?php echo get_post_format( $post->ID ); ?>"></a>
				<?php } else {  ?>
				<a href="#" class="post-icon standard"></a>
				<?php } ?>
				<div class="post-content">
					<div class="post-title">
							<h2>
								<a href="<?php the_permalink(); ?>" title="<?php printf(esc_attr__('Permalink to %s', 'purepress'), the_title_attribute('echo=0')); ?>" rel="bookmark">
									<?php the_title(); ?>
								</a>
							</h2>
					</div>

					<div class="post-meta">
						<span><i class="mini-ico-calendar"></i><?php printf('<a href="%1$s" class="published-time" title="%2$s" rel="bookmark">%3$s</a>', get_permalink(), esc_attr(get_the_time()), get_the_date()); ?></span> 
						<span><i class="mini-ico-user"></i><?php  _e('By', 'purepress'); ?>  <a class="author-link" href="<?php echo get_author_posts_url(get_the_author_meta('ID' )); ?>"><?php the_author_meta('display_name'); ?></a></span>
						<span><i class="mini-ico-comment"></i><?php  _e('With', 'purepress'); ?> <?php  comments_popup_link( __('no comments yet','purepress'), __('1 comment','purepress'), __('% comments','purepress'), 'comments-link', __('Comments are off for this post','purepress'));
?></span> </div>
						<div class="post-description">
							<?php the_excerpt() ?>
						</div>
						<a class="post-entry" href="<?php the_permalink(); ?>"><?php  _e('Continue Reading', 'purepress'); ?> &rarr;</a>
					</div>
			</div>
			<!-- Post -->
		<?php endwhile; // End the loop. Whew.  ?>



		<div class="pagination">
<?php if(function_exists('wp_pagenavi')) : 
         wp_pagenavi(); 
    else:
         if ($wp_query->max_num_pages > 1) : ?>
            <nav id="nav-below" class="navigation">
                <div class="nav-previous"><?php next_posts_link(__('&larr; Older posts', 'purepress')); ?></div>
                <div class="nav-next"><?php previous_posts_link(__('Newer posts &rarr;', 'purepress')); ?></div>
            </nav><!-- #nav-below -->
         <?php endif;
    endif; ?>
			
</div>

</div> <!-- eof eleven column -->


<?php 
if($sidebar_side != "left-sidebar") {
	get_sidebar();
}

get_footer(); 

?>