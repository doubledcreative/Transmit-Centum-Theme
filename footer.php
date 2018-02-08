<?php 
                         /* <!-- Begin WordPress Cache (DO NOT MODIFY) --> *//* <!-- End WordPress Cache --> */ ?></div>
</div>
<!-- Wrapper / End -->


<!-- Footer Start -->

	<div class="greenfoot">
	
		<div class="container">
				
			<div class="greenspan">
				
				<i class="fa fa-fw fa-gbp"></i> Money Lent: <b>£<?php
				$home_page_post_id = 5832;
				$home_page_post = get_post( $home_page_post_id, ARRAY_A );
				$content_home = $home_page_post['post_content'];
				echo $content_home;
				?></b>
				
			</div> 
			
			<div class="greenspan">
				
				<i style="margin-left:20px;" class="fa fa-fw fa-user"></i> Entrepreneurs Backed: <b><?php
				$home_page_post_id = 5834;
			  	$home_page_post = get_post( $home_page_post_id, ARRAY_A );
			  	$content_home = $home_page_post['post_content'];
			  	echo $content_home;
				?></b>
			
			</div> 
			
			<div class="greenspan">
				
				<i style="margin-left:20px;" class="fa fa-fw fa-pencil"></i> <b><a href="//transmitstartups.co.uk/apply-now/">Apply Now!</a></b>
				
			</div>
			
			<div id="scroll-top-top"><a href="#"></a></div>
		
		</div>
	
	</div>

<div id="footer">

	<!-- 960 Container -->
	<div class="container">

		<div class="four columns col1">
		<?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('Footer 1st Column')) : endif; ?>
		</div>

		<div class="four columns col2">
		<?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('Footer 2nd Column')) : endif; ?>
		</div>
		
		<div class="four columns col3">
		<?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('Footer 3rd Column')) : endif; ?>
		</div>

		<div class="four columns col4">
		<?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('Footer 4th Column')) : endif; ?>
		</div>

		<div class="sixteen columns">
			<div id="footer-bottom">
				<?php $copyrights = ot_get_option('copyrights' );  echo $copyrights?>
			</div>
		</div>

	</div>
	<!-- 960 Container End -->

</div>
<!-- Footer End -->
<?php wp_footer(); ?>

<script type="text/javascript" src="//cdn-static.formisimo.com/tracking/js/tracking.js"></script>
<script type="text/javascript" src="//cdn-static.formisimo.com/tracking/js/conversion.js"></script> 

</body>
</html>