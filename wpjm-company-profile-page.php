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

//include(locate_template('company-archive-page-template.php'));

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
	add_action( 'template_include', 'gma_wpjmccp_companies_archive_page_template' );

	/**/
	// https://developer.wordpress.org/reference/hooks/create_taxonomy/

}

// ## Template loader to edit archives
function gma_wpjmccp_companies_archive_page_template( $template ){

	//locate_template( 'company-archive-page-template.php', true, true );
	//$plugin_directory = dirname(__FILE__);
	$plugins_url = plugins_url();
	$plugin_dir_path = plugin_dir_path( __FILE__ );
	$company_template_url = $plugin_dir_path . 'company-archive-page-template.php';
	


	if ( is_tax('companies') ) {
		//get_template_part($company_template_url);
		// possibly the function exists already within wpj? https://github.com/Automattic/WP-Job-Manager/blob/914f790050a7069a2bf7db9d5b91480d6bd256be/wp-job-manager-template.php


		//$plugin_directory = dirname(__FILE__);
		//$new_template = locate_template( array( 'company-archive-page-template.php' ) );
		//error_log( print_r(dirname(__FILE__) . '/wpjm-company-profile-page/company-archive-page-template.php'));
		//error_log( var_dump($new_template));
		//var_dump($template); // "/Applications/MAMP/htdocs/local/wp-content/themes/storefront/archive.php"
		//var_dump($plugin_directory); // "/Applications/MAMP/htdocs/local/wp-content/plugins/wpjm-company-profile-page"
		//var_dump($plugins_url);
		//var_dump($company_template_url);
		//var_dump($plugin_dir_path);

		/*
		* Changes the default archive.php template for company-archive-page-template.php
		*/
		 // $new_template = '/Applications/MAMP/htdocs/local/wp-content/plugins/wpjm-company-profile-page/company-archive-page-template.php';
		//$template = $company_template_url;
		//$template = '/Applications/MAMP/htdocs/local/wp-content/plugins/wpjm-company-profile-page/company-archive-page-template.php';
		$template = $company_template_url;
		
		return $template;
	
	}

	return $template;
	
}

// ### Creates custom job taxonomy
function gma_wpjmcpp_job_taxonomy_init(){

	register_taxonomy(
		'companies',
		//'page',
		'job_listing',
		array(
			'label' => __( 'Companies' ),
			'rewrite' => array( 'slug' => 'company')
		)
	);
}

/*
* Creating new taxonomy data. Testing from here: https://wordpress.stackexchange.com/questions/211703/need-a-simple-but-complete-example-of-adding-metabox-to-taxonomy
*/
// EDIT COLUMNS
add_filter( 'manage_edit-companies_columns', '___edit_term_columns' );
function ___edit_term_columns( $columns ) {

    $columns['__term_meta_text'] = __( 'TERM META TEXT', 'text_domain' );

    return $columns;
}

// ADD FIELD TO CATEGORY TERM PAGE
add_action( 'companies_add_form_fields', '___add_form_field_term_meta_text' );
function ___add_form_field_term_meta_text() { ?>
    <?php wp_nonce_field( basename( __FILE__ ), 'term_meta_text_nonce' ); ?>
    <div class="form-field term-meta-text-wrap">
        <label for="term-meta-text"><?php _e( 'TERM META TEXT', 'text_domain' ); ?></label>
        <input type="text" name="term_meta_text" id="term-meta-text" value="" class="term-meta-text-field" />
    </div>
<?php }


function gma_wpjmcpp_display_job_meta_data() {
  
  global $post;
  //print_r($post);

  //$data = get_post_meta( $post->ID, "", true);
  $data = get_post_meta( $post->ID, "_company_name", true);

  $the_new_company_taxonomy = wp_get_post_terms($post->ID, 'companies');
  //print_r($the_new_company_taxonomy[0]->slug);
  $single_company_slug = $the_new_company_taxonomy[0]->slug;

  //$url = "https://google.com";
  $url = 'http://localhost:8888/local/company/' . $single_company_slug;
  //##TODO: escape and html secure input for $url and $data
  //##TODO: internationalize profile string
  $company_name = "<li><a href='" . $url . "'>" . $data . " profile</a></li>";
  //var_dump($data);
  echo $company_name;

  // echo ' || ';
  // var_dump(get_post_meta( $post->ID ));
  // echo ' || ';
  // $the_company_taxonomy = get_the_terms($post->ID, 'companies', true);
  // echo ' || ';
  // var_dump($the_company_taxonomy[0]->slug);
  // echo ' || ';
  //$the_new_company_taxonomy = wp_get_post_terms($post->ID, 'companies');
  //print_r($the_new_company_taxonomy[0]->slug);
  //$the_company_slug = $the_company_taxonomy['slug'];
  //echo $the_company_slug;

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



