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

// ### Check if WP Job Manager is installed and active, prompt error message otherwise

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

// ### Create an empty page for each company via maybe wp_insert_post, post_type = page and post_title = company name , post_status = publish, content = company info.
add_action( 'single_job_listing_meta_end', 'gma_wpjmcpp_display_job_meta_data' );
function gma_wpjmcpp_display_job_meta_data() {
  
  global $post;
  //print_r($post);

  //$data = get_post_meta( $post->ID, "", true);
  $data = get_post_meta( $post->ID, "_company_name", true);
  $url = "https://google.com";

  $company_name = "<a href='" . $url . "'>" . $data . "</a>";
  //var_dump($data);
  echo $company_name;

  
 
  // $salary = get_post_meta( $post->ID, '_job_salary', true );
  // $important_info = get_post_meta( $post->ID, '_job_important_info', true );

  // if ( $salary ) {
  //   echo '<li>' . __( 'Salary: ' ) . esc_html( $salary ) . '</li>';
  // }

}