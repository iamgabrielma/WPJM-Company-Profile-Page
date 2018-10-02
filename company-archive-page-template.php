<?php
/*
Template Name: Archives
*/
get_header(); ?>

<div id="container">
	<div id="content" role="main">

		<?php the_post(); ?>
		<h1 class="entry-title"><?php /*the_title();*/ ?></h1>
		<h1 class="entry-title">Jobs by <strong>test-company</strong> </h1>
		
		<?php /*get_search_form();*/ ?>
		
		<h2>COMPANY ARCHIVE PAGE TEMPLATE [<strong>test-company</strong>] </h2>
		<ul>
			<?php

				$args = array(
					'post_type' 	=> 'job_listing',
					'taxonomy' 		=> 'companies'
					// 'tax_query' => array(
					// 	array(
					// 		'taxonomy' => 'companies'
					// 	)
					// )
				);

				$posts_array = get_posts( $args );
				//var_dump($posts_array);
				print_r( $args );

				// I need to get all the taxonomies to filter them down later on
				$my_individual_companies = get_terms( array( 'taxonomy' => 'companies') );
				print_r($my_individual_companies);

				foreach ($posts_array as $job => $data) {

					// URL == taxonomy, display, otherwise dont
					// if (condition) {
					// 	# code...
					// }
					 
					 echo '<br>';
					 echo '----------------';
					 //echo $job['post_title'];
					 $job_title = $data->post_title;
					 $job_url = $data->guid;
					 echo '<br>';
					 print_r($job_title);

					 echo "<a href='" . $job_url . "'> | Apply </a>";
				}

			?>

		</ul>

	</div><!-- #content -->
</div><!-- #container -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>