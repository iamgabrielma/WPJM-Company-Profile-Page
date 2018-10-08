<?php
/*
Template Name: Company Profile Page
*/
get_header(); ?>

<div id="container">
	<div id="content" role="main">

		<?php the_post(); ?>

		<?php
			
			/*
			* Get data
			*/
			$post_type = wp_get_post_terms($post->ID, 'companies');
			$post_type_slug = $post_type[0]->slug;
			$post_type_name = $post_type[0]->name;
			$post_type_term_taxonomy_id = $post_type[0]->term_taxonomy_id;
			$post_type_description = $post_type[0]->description;

		?>

		<header class="gma_wpjmccp_single_job_listing_header">
		
			<h1 class="entry-title">About 
				<strong>
					<?php echo esc_textarea($post_type_name); ?>
				</strong>
			</h1>
			<p class="gma_wpjmccp_single_job_listing_description">
				<?php echo esc_textarea( $post_type_description); ?>
			</p>
			<h1 class="entry-title">Jobs by 
				<strong>
					<?php echo esc_textarea($post_type_name); ?>
				</strong> 
			</h1>
		
		</header>

		<!-- Start: List of unique jobs by specific company -->
		<ul>
		
		<?php

			$myposts = get_posts(
				array(
		            'showposts' => -1,
		            'post_type' => 'job_listing',
		            'tax_query' => array(
		                array(
		                'taxonomy' => 'companies',
		                'field' => 'slug',
		                'terms' => $post_type_slug
	               		)
               		)		
		        )
            );
	
			echo '<ul>';

			// Start: foreach
            foreach ($myposts as $mypost) { 

            	$job_title = $mypost->post_title;
            	$job_url = $mypost->guid;

            ?>
                
            <!-- ## FRONT-END DISPLAY ## -->
            <div class="gma_wpjmccp_single_job_listing">
				<span>
					<strong>
						<?php echo esc_textarea($job_title); ?>
					</strong>
				</span>
				<a class="gma_wpjmccp_single_job_listing_ahref" href="
					<?php echo esc_html($job_url); ?>
				">
					<input class="application_button button" value="Apply for job" type="button" >
				</a>
			</div>
			<!--// ## FRONT-END DISPLAY ## -->

            <?php 
 
            }
            	echo '</ul>';
			?>

		<!-- End: List of unique jobs by specific company -->
		</ul>
		
		<?php
		
			$website_meta = get_term_meta( $post_type_term_taxonomy_id );

			if (!empty($website_meta["__term_meta_text"][0])) {

				$echoed_website_meta = "<strong>Company Website: </strong>" . 
				"<a href=" . esc_url($website_meta["__term_meta_text"][0]) . ">" . $post_type_name . "</a>";

			} else {
				
				$echoed_website_meta = "";

			}
		
        ?>

        <div id="gma_wpjmccp_single_job_listing_website">
			<span>
					<?php echo $echoed_website_meta; ?>
			</span>
		</div>


	</div><!-- #content -->
</div><!-- #container -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>