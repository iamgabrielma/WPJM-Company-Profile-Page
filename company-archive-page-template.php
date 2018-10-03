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
				//$post_type_data = get_post_type_object( $post_type );
				//$post_type_slug = $post_type_data->rewrite['slug'];
				//var_dump($post_type);
				//var_dump($post_type_slug);
				//var_dump($post_type_data);
				//var_dump($post_type_slug);

		?>

		<h1 class="entry-title"><?php /*the_title();*/ ?></h1>
		<h1 class="entry-title">Jobs by <strong><?php echo $post_type_slug ?></strong> </h1>
		
		<?php /*get_search_form();*/ ?>
		

		<ul>
			<?php

				// $args = array(
				// 	'post_type' 	=> 'job_listing',
				// 	'taxonomy' 		=> 'companies'
				// 	// 'tax_query' => array(
				// 	// 	array(
				// 	// 		'taxonomy' => 'companies'
				// 	// 	)
				// 	// )
				// );

				// $posts_array = get_posts( $args );
				//var_dump($posts_array);
				//print_r( $args );

				// // I need to get all the taxonomies to filter them down later on
				// $my_individual_companies = get_terms( array( 'taxonomy' => 'companies') );
				// print_r($my_individual_companies);

				// // :)
				// echo $my_individual_companies[0]->slug;
				//var_dump($posts_array);

				// foreach ($posts_array as $job => $data) {

				// 	// URL == taxonomy, display, otherwise dont
				// 	// if ($post_type_slug ) {
				// 	// 	# code...
				// 	// }
					 
				// 	 echo '<br>';
				// 	 echo '----------------';
				// 	 //echo $job['post_title'];
				// 	 $job_title = $data->post_title;
				// 	 $job_url = $data->guid;
				// 	 echo '<br>';
				// 	 print_r($job_title);

				// 	 echo "<a href='" . $job_url . "'> | Apply </a>";
				// }

				// ## WP Query + taxonomies:
				
				
				// $args=array(
				// 	'post_type' => 'job_listing',
				//     'posts_per_page' => -1, 
				//     'tag' => $post_type_slug
				// );

				// $the_query = new WP_Query( $args );

				// //var_dump($the_query);
				
				// echo '<br>';
				// echo '----------------';

				// :)
				//var_dump($the_query->query['tag']);

				// if ($the_query->query['tag'] == $post_type_slug) {
				// 	echo 'yes! Only ' . $post_type_slug . " jobs" ;


				// 	$args = array(
				// 		'post_type' 	=> 'job_listing',
				// 		//'taxonomy' 		=> 'companies'
				// 		//'tag'			=> $post_type_slug
				// 		'tax_query'		=> array(
				// 								'taxonomy' => 'companies',
				// 								'field' => 'slug',
				// 								'terms' => $post_type_slug
				// 							)
				// 	);

				// 	$posts_array = get_posts( $args );
					
				// 	foreach ($posts_array as $job => $data) {
 
				// 		 echo '<br>';
				// 		 echo '----------------';
				// 		 $job_title = $data->post_title;
				// 		 $job_url = $data->guid;
				// 		 echo '<br>';
				// 		 print_r($job_title);
				// 		 echo "<a href='" . $job_url . "'> | Apply </a>";
				// 	}


				// } else {
				// 	echo "no";
				// }

				// echo '<br>';
				// echo 'new_query';
				// echo '----------------';

				// $args=array(
				// 	'companies' => $post_type_slug
				// );

				// $new_query = new WP_Query( $args );
				// var_dump($new_query);
				
				// echo '<br>';
				// echo 'get_terms';
				// echo '----------------';
				// echo '<br>';

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
                
                <li>
                	
                	<p><?php echo  $job_title . "| <a href='" . $job_url . "'> Apply </a>"; ?></p>
            	<?php 
            	}
            	echo '</ul>';
				
				//$my_individual_companies = get_terms( array( 'taxonomy' => 'companies') );
				//var_dump($my_individual_companies);

				//!! Infintite loop
				// while ( $new_query->have_posts() ) :

    //             echo '<p>'. the_title() .'</p>';

    //             endwhile;

				// foreach ($the_query as $key => $value) {
					
					
				// }

				//var_dump($the_query);

				//$new_posts_array = get_posts( $args );
				//var_dump($new_posts_array);

				// while ( $the_query->have_posts() ) : $the_query->the_post();
    // 				the_content(__('Continue Reading'));             
				// endwhile;


				// tag_slug__in
				// tag

				// if ( $the_query->have_posts() ) {
				//     echo '<ul>';
				//     while ( $the_query->have_posts() ) {
				//         $the_query->the_post();
				//         echo '<li>' . get_the_title() . '</li>';
				//     }
				//     echo '</ul>';
				// } else {
				//     echo 'No jobs found';
				// }

				// wp_reset_postdata();

				// $post_tag = get_the_tags ( $post->ID );
				// var_dump($post_tag);

				// if ( $post_tag ) {
				// 	foreach ($post_tag as $tag) {
				// 		$ids[] = $tag->term_id;
				// 		echo '0';
				// 	}
				// }

				// $args = array(
				//     'post_type' => 'job_listing',
				//     'tag__in'   => $ids,
				// );

				// $related_posts = new WP_Query( $args );
				// var_dump($related_posts);

			?>

		</ul>

	</div><!-- #content -->
</div><!-- #container -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>