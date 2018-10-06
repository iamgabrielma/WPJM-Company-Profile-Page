<?php
/**
 * Plugin Name: WPJM Company Profile Page
 * Plugin URI: https://tilcode.blog/wpjm-company-profile-page-add-a-company-profile-page/
 * Description: Adds a company profile page to WP Job Manager. In this page you'll be able to see listed all the jobs by the same company, as well as other data like the company description.
 * Author:      Gabriel Maldonado
 * Author URI:  https://tilcode.blog/
 * Version:     1.0
 * Text Domain: wpjm-company-profile-page
 * Domain Path: /languages
 *
 * License: GPLv2 or later
 */

/**
 * Prevent direct access data leaks
 **/
if (! defined( 'ABSPATH' )) {
	exit;
}

/**
* Check if WP Job Manager is installed and active
*/
if ( !class_exists( 'WP_Job_Manager' ) ) {

	add_action( 'admin_notices', 'gma_wpjmcpp_admin_notice__error' );

} else {

	add_action( 'single_job_listing_meta_end', 'gma_wpjmcpp_display_job_meta_data' );
	add_action( 'init', 'gma_wpjmcpp_job_taxonomy_init');
	add_action( 'template_include', 'gma_wpjmccp_companies_archive_page_template' );
	/*
	* Meta management for the custom "Company" taxonomy
	*/
	add_filter( 'manage_edit-companies_columns', 'gma_wpjmcpp_edit_term_columns' );
	add_action( 'companies_add_form_fields', 'gma_wpjmcpp_add_form_field_term_meta_text' );
	add_action( 'companies_edit_form_fields', 'gma_wpjmcpp_edit_form_field_term_meta_text' );
	add_filter( 'manage_companies_custom_column', 'gma_wpjmcpp_manage_term_custom_column', 10, 3 );
	add_action( 'edit_companies',   'gma_wpjmcpp_save_term_meta_text' );
	add_action( 'create_companies',   'gma_wpjmcpp_save_term_meta_text' );
	/*
	* Scripts and styles
	*/
	add_action( 'wp_enqueue_scripts', 'add_gma_wpjmccp_scripts' );
	add_action( 'admin_enqueue_scripts', 'add_gma_wpjmccp_admin_scripts' );
}

/*
* Front-end styles
*/
function add_gma_wpjmccp_scripts(){
	
	wp_enqueue_style( 'gma_wpjmccp_style', plugins_url() . '/wpjm-company-profile-page/style.css',false,'1.1','all');
}

/*
* Back-end styles
*/
function add_gma_wpjmccp_admin_scripts(){
	
	wp_enqueue_style( 'gma_wpjmccp_admin_style', plugins_url() . '/wpjm-company-profile-page/admin_style.css',false,'1.1','all');
}

/*
* Template loader for company-archive-page-template.php
*/
function gma_wpjmccp_companies_archive_page_template( $template ){

	$plugins_url = plugins_url();
	$plugin_dir_path = plugin_dir_path( __FILE__ );
	$company_template_url = $plugin_dir_path . 'company-archive-page-template.php';
	
	if ( is_tax('companies') ) {

		$template = $company_template_url;
		return $template;
	
	}

	return $template;
	
}

/*
* Creates custom companies/company taxonomy
*/
function gma_wpjmcpp_job_taxonomy_init(){

	register_taxonomy(
		'companies',
		'job_listing',
		array(
			'label' => __( 'Companies' ),
			'description' => 'testestdescription',
			'rewrite' => array( 'slug' => 'company'),
			'public' => true
		)
	);
}

/* 
* Taxonomy metadata : Adds a new column to the companies term page under Job Listings > Companies
*/
function gma_wpjmcpp_edit_term_columns( $columns ) {

    $columns['__term_meta_text'] = __( 'Company website', 'wpjm-company-profile-page' );

    return $columns;
}

/* 
* Taxonomy metadata : Adds new field to the Companies term page under Job Listings > Companies
*/
function gma_wpjmcpp_add_form_field_term_meta_text() { 
   
    ?>
    <!-- HTML Output -->
    <div class="form-field term-meta-text-wrap">
        <label for="term-meta-text">
        	<?php _e( 'Company website', 'wpjm-company-profile-page' ); ?>
		</label>
        <input type="text" name="term_meta_text" id="term-meta-text" value="" class="term-meta-text-field" />
    </div>

	<?php 
}

/* 
* Taxonomy metadata : Adds new field to the Companies edit term page.
*/
function gma_wpjmcpp_edit_form_field_term_meta_text( $term ){

	$value = gma_wpjmcpp_get_term_meta_text( $term->term_id );

	?>
	
	<!-- HTML Output -->
    <tr class="form-field term-meta-text-wrap">   
        <th scope="row">
        	<label for="term-meta-text">
        		<?php _e( 'Company website', 'wpjm-company-profile-page' ); ?>
        	</label>
        </th>
        <td>
            <input type="text" name="term_meta_text" id="term-meta-text" value="<?php echo $value ?>" class="term-meta-text-field"  />
        </td>
    </tr>

	<?php 
}

/*
* Taxonomy metadata: Gets the term metadata
*/
function gma_wpjmcpp_get_term_meta_text( $term_id ) {
  
  $value = get_term_meta( $term_id, '__term_meta_text', true );
  return $value;
}

/* 
* Taxonomy metadata : Save term metadata
*/
function gma_wpjmcpp_save_term_meta_text( $term_id ){

	$old_value  = gma_wpjmcpp_get_term_meta_text( $term_id );

    if (isset( $_POST['term_meta_text'] )) {
    	$new_value = $_POST['term_meta_text'];
    }

	update_term_meta( $term_id, '__term_meta_text', $new_value );

}

/* 
* Taxonomy metadata : Display metadata in Columns
*/
function gma_wpjmcpp_manage_term_custom_column( $out, $column, $term_id ) {

    if ( '__term_meta_text' === $column ) {

        $value  = gma_wpjmcpp_get_term_meta_text( $term_id );

        if ( ! $value )
            $value = '';

        $out = sprintf( '<span class="term-meta-text-block" style="" >%s</div>', esc_attr( $value ) );
    }

    return $out;
}

/* 
* Taxonomy metadata : Display "Company Profile" link in single job listings 
*/
function gma_wpjmcpp_display_job_meta_data() {
  
  global $post;

  $data = get_post_meta( $post->ID, "_company_name", true);
  $the_new_company_taxonomy = wp_get_post_terms($post->ID, 'companies');
  $single_company_slug = $the_new_company_taxonomy[0]->slug;
  $url = site_url() . '/company/' . $single_company_slug;

  if (!empty($data)) {
  	$company_name = "<li><a href='" . $url . "'>" . $data . " profile</a></li>";	
  } else {
  	$company_name = "<li><a href='" . $url . "'>" . $data . "Company profile</a></li>";	
  }

  echo $company_name;

}

/* 
* Admin notice to activate WP Job Manager
*/
function gma_wpjmcpp_admin_notice__error(){

	$class = 'notice notice-error';
	$message = __( 'An error has occurred. WP Job Manager must be installed in order to use WPJM Company Profile Page plugin', 'wpjm-company-profile-page' );
	/* 
	* Debug: error_log( print_r( $message , true ) );
	*/
	printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) ); 
}
