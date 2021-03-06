<?php
/**
 * Template Name: Calculator
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
 
 <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
 <script src="//code.jquery.com/jquery-1.10.2.js"></script>
 <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
 <script src="http://www.transmitstartups.co.uk/wp-content/themes/Centum/myscript.js" type="text/javascript" ></script>
	
	<div class="container">
		
			<div <?php post_class('post home clearfix'); ?> id="post-<?php the_ID(); ?>">
			
						<div id="page-title">
							<h1><?php the_title(); ?></h1>
							<div id="bolded-line"></div>
						</div>
			
				<?php the_content() ?>
				
				<div class="calc">
					<div class="calc-holder">
					
					<div class="row">
					<h2>I want to borrow:</h2>
					<div id="slider1"></div>
					</div>
					
					<div class="row">
					<p>For a duration of:</p>
					<div id="slider2"></div>
					</div>
					
					</div>
				
				<div class="row-bottom">
				
					<p>Monthly Payment <span id="x"></span><span id="emi">£88.64</span></p>
					<p>Total Repayment <span id="total-amount">£2,127.39</span></p>
				
				</div>
				
			</div>
			
			<a class="button medium orange" href="/apply-now/">Register for a Start Up Loan</a>
			
			<div class="divider"></div>
			
			<p>*The calculator is for illustrative purposes only. Start Up Loans are personal loans for business use only and subject to status. The monthly repayment amounts quoted are estimates which may increase or decrease slightly depending on the number of days between approval and when the loan is advanced.</p>
			
			</div>
		
		</div><!-- end container -->
	
	<?php endwhile; // End the loop. Whew.  ?>

<?php get_footer(); ?>