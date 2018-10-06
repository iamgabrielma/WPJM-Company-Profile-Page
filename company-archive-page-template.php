<?php
/*
Template Name: Company Profile Page
*/
get_header(); ?>

<div id="container">
	<div id="content" role="main">

		<?php the_post(); ?>

		<?php
				
				//$my_individual_companies = get_terms( array( 'taxonomy' => 'companies') );

				//$my_individual_company_slug = $my_individual_companies[0]->slug;

				//var_dump($my_individual_companies[0]->name);
				

				$post_type = wp_get_post_terms($post->ID, 'companies');
				$post_type_slug = $post_type[0]->slug;
				$post_type_name = $post_type[0]->name;
				$post_type_term_taxonomy_id = $post_type[0]->term_taxonomy_id;
				$post_type_description = $post_type[0]->description;
				//$post_type_data = get_post_type_object( $post_type );
				//$post_type_slug = $post_type_data->rewrite['slug'];
				//var_dump($post_type);
				//var_dump($post_type_slug);
				//var_dump($post_type_data);
				//var_dump($post_type_slug);

		?>

		<header class="gma_wpjmccp_single_job_listing_header">
		<h1 class="entry-title">About <strong><?php echo $post_type_name ?></strong></h1>
		
		<p class="gma_wpjmccp_single_job_listing_description"><?php echo $post_type_description; ?></p>
		
		<h1 class="entry-title">Jobs by <strong><?php echo $post_type_name ?></strong> </h1>
		</header>

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
            		//## TODO Rewrite using base URL + $mypost->post_name

            	?>
                <!-- ## FRONT-END DISPLAY ## -->
                
                <div class="gma_wpjmccp_single_job_listing">
                	
                	
                		<span><strong><?php echo  $job_title  ?></strong></span>
					
					<a class="gma_wpjmccp_single_job_listing_ahref" href="<?php echo $job_url ?>">
                		<input class="application_button button" value="Apply for job" type="button" >
                	</a>


				</div>
				<!--// ## FRONT-END DISPLAY ## -->
            	<?php 
            	

            	//$term = get_term_by('slug', 'google', 'companies');
            	//var_dump($term);


            	}
            	echo '</ul>';
			?>

		</ul>

		<!--<h1 class="entry-title">Other Taxonomy Metadata</h1>-->

		<?php
		
			$foometa = get_term_meta( $post_type_term_taxonomy_id ); 
			$echoedfoometa = $foometa["__term_meta_text"][0];
			//echo $echoedfoometa;
        ?>
        <div id="gma_wpjmccp_single_job_listing_website">
		<span><strong>Company Website:</strong> <?php echo  $echoedfoometa  ?></span>
		</div>


	</div><!-- #content -->
</div><!-- #container -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>