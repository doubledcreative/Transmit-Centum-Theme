<!-- 960 Container -->
<div class="container">

	<!-- Flexslider -->
	<div class="sixteen columns">
	
		<h1 class="homeh1">Finance, Support &amp; Mentoring for UK Start-up Businesses</h1>
	
		<section class="slider">
			<div class="flexslider home">
				<ul class="slides">
					<?php
					if ( function_exists( 'ot_get_option' ) ) {
						$slides = ot_get_option( 'mainslider', array() );
						if (!empty( $slides ) ) {
							foreach( $slides as $slide ) {
									echo '<li><img src="' . $slide['slider_image_upload'] . '" alt="' . $slide['title'] . '" />';
										if( $slide['slider_empty'] != "yes" ) { 
											echo '<div class="slide-caption">';
											if(!empty($slide['title'])) echo '<h3>' . $slide['title'] . '</h3>';
											if(!empty($slide['slider_description'])) echo '<p>' . do_shortcode($slide['slider_description']) . '</p>';
											echo '</div>'; 
										}
									echo '</li>';
							}
						}
					}
					?>
					<!-- Slide -->
				</ul>
			</div>
		</section>
	</div>
	<!-- Flexslider / End -->
	
</div>
<!-- 960 Container / End -->
