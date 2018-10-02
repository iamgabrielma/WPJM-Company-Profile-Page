<?php
/**
 * Plugin Name: WPJM Company Profile Page
 * Plugin URI:  
 * Description: Adds a company profile page to WP Job Manager companies
 * Author:      Gabriel Maldonado
 * Author URI:  
 * Version:     0.1
 * Text Domain: wpjm-company-profile-page
 */


//### Prevent direct access data leaks: If this file is accessed directly in the URL, do not load the rest of the file's content in the browser. If file is accessed within the WordPress Environment, continue to load the file's content
if (! defined( 'ABSPATH' )) {
	exit;
}

// ### Check if WP Job Manager is installed and active, prompt error message otherwise
if ( !class_exists( 'WP_Job_Manager' ) ) {

	add_action( 'admin_notices', 'gma_wpjmcpp_admin_notice__error' );

} else {
	add_action( 'single_job_listing_meta_end', 'gma_wpjmcpp_display_job_meta_data' );
	add_action( 'init', 'gma_wpjmcpp_job_taxonomy_init');
}

// ### Creates custom job taxonomy
function gma_wpjmcpp_job_taxonomy_init(){

	register_taxonomy(
		'companies',
		//'page',
		'job_listing',
		array(
			'label' => __( 'Companies' ),
			'rewrite' => array( 'slug' => 'company'),
			'description' => "Hello description",
		)
	);
}


function gma_wpjmcpp_display_job_meta_data() {
  
  global $post;
  //print_r($post);

  //$data = get_post_meta( $post->ID, "", true);
  $data = get_post_meta( $post->ID, "_company_name", true);
  $url = "https://google.com";

  $company_name = "<a href='" . $url . "'>" . $data . " profile</a>";
  //var_dump($data);
  echo $company_name;

}

// ### Add company column to wp-admin > All Jobs


// ### Create a category for companies in the URL permalink.


// ### Create an empty page for each company via maybe wp_insert_post, post_type = page and post_title = company name , post_status = publish, content = company info.

// ### Admin notice + debug log error if core plugin is not active

function gma_wpjmcpp_admin_notice__error(){

	$class = 'notice notice-error';
	$message = __( 'An error has occurred. WP Job Manager must be installed in order to use WPJM Company Profile Page plugin', 'wpjm-company-profile-page' );

	error_log( print_r( $message , true ) );
	printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) ); 
}



