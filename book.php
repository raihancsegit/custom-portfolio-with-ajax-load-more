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
		add_shortcode( 'books', array( $this,'Bood_Shortcode_Add') );
		add_action( 'admin_enqueue_scripts', array( $this, 'book_admin_assets' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'book_fontend_assets' ) );

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

	function book_fontend_assets(){
		wp_enqueue_style( 'book-bootstrap', plugin_dir_url( __FILE__ ) . "assets/css/bootstrap.css", null, BOOK_HELPER_VERSION );
		wp_enqueue_style( 'book-porfolio-style', plugin_dir_url( __FILE__ ) . "assets/css/portfolio.css", null, BOOK_HELPER_VERSION );

		wp_enqueue_script( 'book-bootstrap-js', plugin_dir_url( __FILE__ ) . "assets/js/bootstrap.min.js", array(
			'jquery',
		), BOOK_HELPER_VERSION, true );
		
		wp_enqueue_script( 'magnific-popup-options-js', plugin_dir_url( __FILE__ ) . "assets/js/magnific-popup-options.js", array(
			'jquery',
		), BOOK_HELPER_VERSION, true );

		wp_enqueue_script( 'imagesloaded-js', plugin_dir_url( __FILE__ ) . "assets/js/imagesloaded.js", array(
			'jquery',
		), BOOK_HELPER_VERSION, true );

		wp_enqueue_script( 'isotope-js', plugin_dir_url( __FILE__ ) . "assets/js/isotope.pkgd.min.js", array(
			'jquery',
		), BOOK_HELPER_VERSION, true );

		wp_enqueue_script( 'book-isotope-js', plugin_dir_url( __FILE__ ) . "assets/js/jquery.isotope.js", array(
			'jquery',
		), BOOK_HELPER_VERSION, true );

		wp_enqueue_script( 'portfolio-js', plugin_dir_url( __FILE__ ) . "assets/js/portfolio.js", array(
			'jquery',
			'magnific-popup-options-js',
		     'imagesloaded-js',
		     'isotope-js'
		), BOOK_HELPER_VERSION, true );
	}

	function book_admin_assets() {
		wp_enqueue_style( 'book-admin-style', plugin_dir_url( __FILE__ ) . "assets/admin/css/style.css", null, time() );
		wp_enqueue_style( 'jquery-ui-css', '//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css', null, time() );
		wp_enqueue_script( 'book-admin-js', plugin_dir_url( __FILE__ ) . "assets/admin/js/main.js", array(
			'jquery',
			'jquery-ui-datepicker'
		), time(), true );
	}


	function Bood_Shortcode_Add(){
		ob_start();
		?>

<div class="section bg-white pt-2 pb-2 text-center" data-aos="fade">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="text-center">
                            <ul class="portfolio-filter text-center">
                                <li class="active"><a href="#" data-filter="*"> All</a></li>
                                <li><a href="#" data-filter=".cat1">Salad</a></li>
                                <li><a href="#" data-filter=".cat2">Bread</a></li>
                                <li><a href="#" data-filter=".cat3">Fish</a></li>
                                <li><a href="#" data-filter=".cat4">Meat</a></li>
                                <li><a href="#" data-filter=".cat5">Fruits</a></li>
                            </ul>
                        </div>

                        <div class="portfolio-grid portfolio-gallery grid-4 gutter">
                            
                            <div class="portfolio-item cat2 cat3 cat4">
                                <a href="imgs/img1.jpg" class="portfolio-image popup-gallery" title="Bread">
                                    <img src="imgs/img1.jpg" alt=""/>
                                    <div class="portfolio-hover-title">
                                        <div class="portfolio-content">
                                            <h4>Branding</h4>
                                            <div class="portfolio-category">
                                                <span>Cat 1</span>
                                                <span>Cat 2</span>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="portfolio-item cat2 cat3 cat4">
                                <a href="imgs/img9.jpg" class="portfolio-image popup-gallery" title="Bread">
                                    <img src="imgs/img9.jpg" alt=""/>
                                    <div class="portfolio-hover-title">
                                        <div class="portfolio-content">
                                            <h4>Branding</h4>
                                            <div class="portfolio-category">
                                                <span>Cat 1</span>
                                                <span>Cat 2</span>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="portfolio-item cat1 cat2 cat3">
                                <a href="imgs/img2.jpg" class="portfolio-image popup-gallery" title="Design">
                                    <img src="imgs/img2.jpg" alt=""/>
                                    <div class="portfolio-hover-title">
                                        <div class="portfolio-content">
                                            <h4>Design</h4>
                                            <div class="portfolio-category">
                                                <span>Cat 1</span>
                                                <span>Cat 2</span>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="portfolio-item cat1 cat4">
                                <a href="imgs/img10.jpg" class="portfolio-image popup-gallery" title="Photography">
                                    <img src="imgs/img10.jpg" alt=""/>
                                    <div class="portfolio-hover-title">
                                        <div class="portfolio-content">
                                            <h4>Photography</h4>
                                            <div class="portfolio-category">
                                                <span>Cat 1</span>
                                                <span>Cat 2</span>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="portfolio-item cat3 cat5">
                                <a href="imgs/img4.jpg" class="portfolio-image popup-gallery" title="Marketing">
                                    <img src="imgs/img4.jpg" alt=""/>
                                    <div class="portfolio-hover-title">
                                        <div class="portfolio-content">
                                            <h4>Marketing</h4>
                                            <div class="portfolio-category">
                                                <span>Cat 1</span>
                                                <span>Cat 2</span>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="portfolio-item cat4 cat5">
                                <a href="imgs/img5.jpg" class="portfolio-image popup-gallery" title="Web Desgin">
                                    <img src="imgs/img5.jpg" alt=""/>
                                    <div class="portfolio-hover-title">
                                        <div class="portfolio-content">
                                            <h4>Web Design</h4>
                                            <div class="portfolio-category">
                                                <span>Cat 1</span>
                                                <span>Cat 2</span>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="portfolio-item cat2 cat3">
                                <a href="imgs/img7.jpg" class="portfolio-image popup-gallery" title="Media">
                                    <img src="imgs/img7.jpg" alt=""/>
                                    <div class="portfolio-hover-title">
                                        <div class="portfolio-content">
                                            <h4>Media</h4>
                                            <div class="portfolio-category">
                                                <span>Cat 1</span>
                                                <span>Cat 2</span>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="portfolio-item cat3 cat4 cat5">
                                <a href="imgs/img6.jpg" class="portfolio-image popup-gallery" title="Portfolio">
                                    <img src="imgs/img6.jpg" alt=""/>
                                    <div class="portfolio-hover-title">
                                        <div class="portfolio-content">
                                            <h4>Portfolio</h4>
                                            <div class="portfolio-category">
                                                <span>Cat 1</span>
                                                <span>Cat 2</span>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- .section -->

		<?php 
		return ob_get_clean();
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
			__( 'Book Gallery', 'our-metabox' ),
			array( $this, 'book_image_info' ),
			array( 'book' )
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
