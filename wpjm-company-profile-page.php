<?php
/**
 * Plugin Name: WPJM Company Profile Page
 * Plugin URI: https://tilcode.blog/wpjm-company-profile-page-add-a-company-profile-page/
 * Description: Adds a company profile page to WP Job Manager. In this page you'll be able to see listed all the jobs by the same company, as well as other data like the company description.
 * Author:      Gabriel Maldonado
 * Author URI:  https://tilcode.blog/
 * Version:     1.2.1
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


add_action( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'gma_wpjmcpp_add_support_link_to_plugin_page' );

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


/*
* Front-end styles
*/
function add_gma_wpjmccp_scripts(){
	
	wp_enqueue_style( 'gma_wpjmccp_style', plugin_dir_url(__FILE__) . 'style.css',false,'1.1','all');
}

/*
* Back-end styles
*/
function add_gma_wpjmccp_admin_scripts(){
	
	wp_enqueue_style( 'gma_wpjmccp_admin_style', plugin_dir_url(__FILE__) . 'admin_style.css',false,'1.1','all');
}

/**
* Adds a direct support link under the Plugins Page once the plugin is activated
**/
function gma_wpjmcpp_add_support_link_to_plugin_page( $links ){

    $links = array_merge( array(
        '<a href="https://wordpress.org/support/plugin/wpjm-company-profile-page" target="_blank">' . __( 'Support', 'wpjm-company-profile-page' ) . '</a>'
    ), $links );
    return $links;
}

/*
* Template loader for company-archive-page-template.php
*/
function gma_wpjmccp_companies_archive_page_template( $template ){

	$plugin_dir_path = plugin_dir_path( __FILE__ );
	$company_template_url = $plugin_dir_path . 'company-archive-page-template.php';

	if ( is_tax($taxonomy="companies") ) { 

    // TODO: Sometimes this fails as is_tax() returns NULL. This can be fixed flushing the cache via $wp_rewrite->flush_rules( true ); but is an expensive operation. Would be a good idea to integrate this somehow if the page returns a 404 for example.
		$template = $company_template_url;
		return $template;
	
	}

	return $template;
	
}

/*
* Creates custom companies/company taxonomy. This will show under Job Listings > Companies as well as within the Editor metabox
*/
function gma_wpjmcpp_job_taxonomy_init(){

	register_taxonomy(
		'companies',
		'job_listing',
		array(
			'label' => __( 'Companies', 'wpjm-company-profile-page' ),
			'rewrite' => array( 'slug' => 'company'),
			'public' => true,
      /*
      Necessary after WP 5.0+ in order to show the metabox within the editor. Since the editor operates using the REST API, taxonomies and post types must be whitelisted to be accessible within the editor.
      https://developer.wordpress.org/reference/functions/register_taxonomy/
      */
      'show_in_rest' => true,
		)
	);
}

/* 
* Taxonomy metadata : Adds a new column to the companies term page under Job Listings > Companies
*/
function gma_wpjmcpp_edit_term_columns( $columns ) {

    $columns['__term_meta_text'] = _e( 'Company website', 'wpjm-company-profile-page' );

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
    	// sanitize_url() deprecated in favor of: esc_url() is intended for output, while esc_url_raw() is intended for database storage
    	$new_value = esc_url_raw($_POST['term_meta_text']);
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
  // ##
  //var_dump($data); // string(0) in http://localhost:8888/local/job/new-job-2/
  //wp_die();
  // ##
  $the_new_company_taxonomy = wp_get_post_terms($post->ID, 'companies');
  // ##
  //var_dump($the_new_company_taxonomy); // OK, data added in wp-admin is here.
  //wp_die();
  // ##

  
      $single_company_slug = $the_new_company_taxonomy[0]->slug;
      $url = home_url() . '/company/' . $single_company_slug; // OK, page is created.



  // Checks if the company name has been added as a tag to the individual job listing
  if (!empty($data)) {
  	$company_name = "<li><a href='" . esc_url( $url ) . "'>" . esc_html( $data ) . " profile</a></li>";	
  } else {
  	$company_name = "<li><a href='" . esc_url( $url ) . "'>Company profile</a></li>";	
  }

  echo $company_name;

}

/* 
* Admin notice to activate WP Job Manager
*/
function gma_wpjmcpp_admin_notice__error(){

	$class = 'notice notice-error';
	$message = _e( 'An error has occurred. WP Job Manager must be installed in order to use WPJM Company Profile Page plugin', 'wpjm-company-profile-page' );
	/* 
	* Debug: error_log( print_r( $message , true ) );
	*/
	printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) ); 
}
