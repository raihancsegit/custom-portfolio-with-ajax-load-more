<?php
/*
Plugin Name: Book
Plugin URI:
Description: Book Plugin
Version: 1.0
Author: Raihan Islam
Author URI:
License: GPLv2 or later
Text Domain: book
Domain Path: /languages/
*/

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    die( 'No Direct Access' );
}

/*******************************************************************
 * Constants
 *******************************************************************/

/** Book Engine version  */
define( 'BOOK_HELPER_VERSION', '1.0.0' );

/** Book Engine directory path  */
define( 'BOOK_HELPER_DIR', trailingslashit( plugin_dir_path( __FILE__ ) ) );

/** Book Engine includes directory path  */
define( 'BOOK_HELPER_INCLUDES_DIR', trailingslashit( BOOK_HELPER_DIR . 'includes' ) );

class OurBookPlugin {

	public function __construct() {
		register_activation_hook( __FILE__, array($this, 'activate') );
		add_action( 'plugins_loaded', array( $this, 'book_load_textdomain' ) );
		add_action( 'admin_menu', array( $this, 'book_add_metabox' ) );
		add_action( 'save_post', array( $this, 'book_save_image' ) );

		add_action( 'admin_enqueue_scripts', array( $this, 'book_admin_assets' ) );

		$this->load_includes();
	}

	public function activate() {
        // flash rewrite rules because of custom post type
        flush_rewrite_rules();
    }

	public function book_load_textdomain() {
		load_plugin_textdomain( 'book', false, dirname( __FILE__ ) . "/languages" );
	}

	private function load_includes() {

        // custom post type
        require_once  BOOK_HELPER_INCLUDES_DIR . 'register-custom-post.php';


    }

	function book_admin_assets() {
		wp_enqueue_style( 'omb-admin-style', plugin_dir_url( __FILE__ ) . "assets/admin/css/style.css", null, time() );
		wp_enqueue_style( 'jquery-ui-css', '//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css', null, time() );
		wp_enqueue_script( 'omb-admin-js', plugin_dir_url( __FILE__ ) . "assets/admin/js/main.js", array(
			'jquery',
			'jquery-ui-datepicker'
		), time(), true );
	}


	private function is_secured( $nonce_field, $action, $post_id ) {
		$nonce = isset( $_POST[ $nonce_field ] ) ? $_POST[ $nonce_field ] : '';

		if ( $nonce == '' ) {
			return false;
		}
		if ( ! wp_verify_nonce( $nonce, $action ) ) {
			return false;
		}

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return false;
		}

		if ( wp_is_post_autosave( $post_id ) ) {
			return false;
		}

		if ( wp_is_post_revision( $post_id ) ) {
			return false;
		}

		return true;

	}

	function book_save_image($post_id){
		if ( ! $this->is_secured( 'book_image_nonce', 'book_image', $post_id ) ) {
			return $post_id;
		}

		$image_id    = isset( $_POST['book_image_id'] ) ? $_POST['book_image_id'] : '';
		$image_url    = isset( $_POST['book_image_url'] ) ? $_POST['book_image_url'] : '';

		update_post_meta($post_id,'book_image_id',$image_id);
		update_post_meta($post_id,'book_image_url',$image_url);

	}

	function book_add_metabox() {
		add_meta_box(
			'book_image_info',
			__( 'Image Info', 'our-metabox' ),
			array( $this, 'book_image_info' ),
			array( 'post' )
		);

	}

	function book_image_info($post) {
		$image_id = esc_attr(get_post_meta($post->ID,'book_image_id',true));
		$image_url = esc_attr(get_post_meta($post->ID,'book_image_url',true));
		wp_nonce_field( 'book_image', 'book_image_nonce' );

		$button_label = __('Upload Image','our-metabox');
		$metabox_html = <<<EOD
			<div class="fields">
				<div class="field_c">
					<div class="label_c">
						<label>Image</label>
					</div>
					<div class="input_c">
						<button class="button" id="upload_image">{$button_label}</button>
						<input type="hidden" name="book_image_id" id="book_image_id" value="{$image_id}"/>
						<input type="hidden" name="book_image_url" id="book_image_url" value="{$image_url}"/>
						<div style="width:100%;height:auto;" id="image-container"></div>
					</div>
					<div class="float_c"></div>
				</div>
				
			</div>
EOD;

		echo $metabox_html;

	}

	


	
}

new OurBookPlugin();
