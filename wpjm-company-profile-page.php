<?php
/**
 * Plugin Name: WPJM Company Profile Page
 * Plugin URI:  
 * Description: Adds company profile page to WP Job Manager
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
}

// ### Make the company class clickable
// https://github.com/Automattic/WP-Job-Manager/blob/914f790050a7069a2bf7db9d5b91480d6bd256be/wp-job-manager-template.php#L1014

//add_action( 'the_company_name', 'linkify_me' );
//add_action('the_job_permalink', 'linkify_me'); //this changes the job URL permalink
//function linkify_me($str){
	
	//return "hello";
	//$company_logo = the_company_logo();
	//print_r($company_logo);
	//return $company_logo;
	//$company_name = '<a href="https://google.com/" class="company_url" target="_blank">' . $company_name . '</a>';
	//print_r($company_name);
	//return $company_name;
	// Cannot go this way because uses wp_strip_all_tags which removes the <a> and there's no apply filters to use there. Via company tag line we cannot either because strip tags $company_tagline = esc_attr( wp_strip_all_tags( $company_tagline ) ); OPEN EH?
//}


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

// ### Create an empty page for each company via maybe wp_insert_post, post_type = page and post_title = company name , post_status = publish, content = company info.

// ### Admin notice + debug log error if core plugin is not active

function gma_wpjmcpp_admin_notice__error(){

	$class = 'notice notice-error';
	$message = __( 'An error has occurred. WP Job Manager must be installed in order to use WPJM Company Profile Page plugin', 'wpjm-company-profile-page' );

	error_log( print_r( $message , true ) );
	printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) ); 
}



