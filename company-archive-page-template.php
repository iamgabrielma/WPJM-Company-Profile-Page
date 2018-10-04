<?php
/*
Template Name: Archives
*/
get_header(); ?>

<div id="container">
	<div id="content" role="main">

		<?php the_post(); ?>

		<?php
				// I need to get all the taxonomies to filter them down later on
				$my_individual_companies = get_terms( array( 'taxonomy' => 'companies') );
				//print_r($my_individual_companies);

				// :)
				$my_individual_company_slug = $my_individual_companies[0]->slug;
				//echo $my_individual_companies[0]->slug;

				// ### Get the taxonomy rewrite slug instead to compare and echo output
				//echo '<br>';
				//$post_type = get_post_type();
				$post_type = wp_get_post_terms($post->ID, 'companies');
				$post_type_slug = $post_type[0]->slug;
				$post_type_description = $post_type[0]->description;
				//$post_type_data = get_post_type_object( $post_type );
				//$post_type_slug = $post_type_data->rewrite['slug'];
				//var_dump($post_type);
				//var_dump($post_type_slug);
				//var_dump($post_type_data);
				//var_dump($post_type_slug);

		?>
		<?php 
		// http://localhost:8888/local/wp-content/plugins/wp-job-manager/assets/images/company.png
		//the_company_logo(); 
		//the_company_tagline();
		?>
		<h1 class="entry-title">About <strong><?php echo $post_type_slug ?></strong></h1>
		<?php echo $post_type_description; ?>
		<h1 class="entry-title">Jobs by <strong><?php echo $post_type_slug ?></strong> </h1>
		

		<ul>
			<?php

				/*
				* Working output.
				*/
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

				//print_r($myposts);
            	foreach ($myposts as $mypost) { 

            		$job_title = $mypost->post_title;
            		$job_url = $mypost->guid;

            	?>
                <!-- ## FRONT-END DISPLAY ## -->
                
                <div class="gma_wpjmccp_single_job_listing">
                	
                	
                		<?php echo  $job_title  ?>
					
					<a href="<?php echo $job_url ?>">
                			<input class="application_button button" value="Apply for job" type="button">
                		</a>
				</div>
				<!--// ## FRONT-END DISPLAY ## -->
            	<?php 
            	}
            	echo '</ul>';

			?>

		</ul>

		<h1 class="entry-title">Contact info </h1>

		<?php
		$terms = get_terms( array(
		    'taxonomy' => 'companies',
		    'terms' => $post_type_slug,
		) );
		$new_terms = wp_get_object_terms( $post_type_slug, "");

		//$new_new_terms = get_metadata( $post_type_slug, "__term_meta_text" );

		print_r($terms);
		print_r($new_terms);
		//print_r($new_new_terms);
		
		?>

		<p>Website: </p>
		<p>Twitter: </p>

	</div><!-- #content -->
</div><!-- #container -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>