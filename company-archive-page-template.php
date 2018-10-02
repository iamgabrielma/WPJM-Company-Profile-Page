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
				echo '<br>';
				//$post_type = get_post_type();
				$post_type = wp_get_post_terms($post->ID, 'companies');
				$post_type_slug = $post_type[0]->slug;
				//$post_type_data = get_post_type_object( $post_type );
				//$post_type_slug = $post_type_data->rewrite['slug'];
				var_dump($post_type);
				var_dump($post_type_slug);
				//var_dump($post_type_data);
				//var_dump($post_type_slug);

		?>

		<h1 class="entry-title"><?php /*the_title();*/ ?></h1>
		<h1 class="entry-title">Jobs by <strong><?php echo $post_type_slug ?></strong> </h1>
		
		<?php /*get_search_form();*/ ?>
		
		<h2>COMPANY ARCHIVE PAGE TEMPLATE [<strong><?php echo $post_type_slug ?></strong>] </h2>
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
				//print_r( $args );

				// // I need to get all the taxonomies to filter them down later on
				// $my_individual_companies = get_terms( array( 'taxonomy' => 'companies') );
				// print_r($my_individual_companies);

				// // :)
				// echo $my_individual_companies[0]->slug;

				foreach ($posts_array as $job => $data) {

					// URL == taxonomy, display, otherwise dont
					// if ($post_type_slug ) {
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