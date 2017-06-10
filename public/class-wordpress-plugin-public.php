<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://github.com/danielswallace
 * @since      1.0.0
 *
 * @package    Wordpress_Plugin
 * @subpackage Wordpress_Plugin/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wordpress_Plugin
 * @subpackage Wordpress_Plugin/public
 * @author     Daniel Wallace <daniel.shawn.wallace@gmail.com>
 */
class Wordpress_Plugin_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		global $wpdb;

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->table_name = $wpdb->prefix . 'wordpress_plugin';

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wordpress_Plugin_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wordpress_Plugin_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wordpress-plugin-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wordpress_Plugin_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wordpress_Plugin_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wordpress-plugin-public.js', array( 'jquery' ), $this->version, false );

	}

	public function shortcode( $atts, $content ) {

		$id = uniqid();

		return '
		<div id="status-'.$id.'"></div>
		<form action="/wp-json/wordpress-plugin/v1/form" method="POST" id="wordpress-form-'.$id.'">
			<p>
				<label for="wpp-name">Name</label>
				<input type="text" name="name" class="wpp-input-name" id="name">
			</p>
			<p>
				<label for="wpp-email">Email</label>
				<input type="email" name="email" class="wpp-input-email" id="email">
			</p>
			<button type="submit" class="wpp-button-submit">Submit</button>
		</form>
		
		<script type="text/javascript">
			jQuery("#wordpress-form-'.$id.'").submit(function(e){
        e.preventDefault();

				var formData = jQuery( this ).serializeArray();

				jQuery.ajax({
          type: "POST",
          url: "/wp-json/wordpress-plugin/v1/form",
          data: formData,
          success: function( data ) {
						jQuery("#status-'.$id.'").html("<p>Your form has been successfully submitted</p>");
					},
					error: function( data ) {
						jQuery("#status-'.$id.'").html("<p>There has been an error with your form</p>");
				 	}
         });
    	});
		</script>';
	}

	function register_shortcode() {
		add_shortcode( 'wpp-form', array( $this, 'shortcode' ) );
	}

	public function api_endpoint_for_form( $request ) {
		global $wpdb;
		$information = $request->get_params();

		if ( $information ) {

			$wpdb->insert(
				$this->table_name,
				$information
			);

			return new WP_REST_Response( $information, 200 );
		}

		return new WP_Error( 'Something went wrong in your request.', __( 'message', 'text-domain' ), array(
			'status' => 500,
		) );
	}

	public function register_endpoint_for_form() {
		register_rest_route( 'wordpress-plugin/v1', '/form', array(
			'methods' => 'POST',
			'callback' => array( $this, 'api_endpoint_for_form' ),
			'args' => array(
				'name' => array(
					'required' => true,
					'validate_callback' => function($param, $request, $key) {
						return !empty( $param );
					},
					'sanitize_callback' => function( $param, $request, $key ) {
						return sanitize_text_field( $param );
					}
				),
				'email' => array(
					'required' => true,
					'validate_callback' => function($param, $request, $key) {
						return !empty( $param );
					},
					'sanitize_callback' => function( $param, $request, $key ) {
						return sanitize_text_field( $param );
					}
				)
			)
		) );
	}

}
