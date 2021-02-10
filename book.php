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
		add_shortcode( 'books', array( $this,'book_shortcode_add') );
		add_action( 'admin_enqueue_scripts', array( $this, 'book_admin_assets' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'book_fontend_assets' ) );

        // Action hook

        add_action( 'wp_ajax_loadmore', array( $this,'book_load_ajax') );
        add_action( 'wp_ajax_nopriv_loadmore', array( $this,'book_load_ajax' ) );

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
		wp_enqueue_style( 'book-porfolio-style', plugin_dir_url( __FILE__ ) . "assets/css/portfolio.css", null, BOOK_HELPER_VERSION );

		wp_enqueue_script( 'isotope-js', plugin_dir_url( __FILE__ ) . "assets/js/isotope.pkgd.min.js", array(
			'jquery',
		), BOOK_HELPER_VERSION, true );

		wp_enqueue_script( 'portfolio-js', plugin_dir_url( __FILE__ ) . "assets/js/portfolio.js", array(
			'jquery',
		     'isotope-js'
		), BOOK_HELPER_VERSION, true );


	}

	public function book_admin_assets() {
		wp_enqueue_style( 'book-admin-style', plugin_dir_url( __FILE__ ) . "assets/admin/css/style.css", null, time() );
		wp_enqueue_style( 'jquery-ui-css', '//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css', null, time() );
		wp_enqueue_script( 'book-admin-js', plugin_dir_url( __FILE__ ) . "assets/admin/js/main.js", array(
			'jquery',
			'jquery-ui-datepicker'
		), time(), true );
	}

    public function book_load_ajax(){

        $args = array(
            'post_type' => 'book',
            'posts_per_page' => $_POST['postNumber'],
            'paged' => $_POST['page'] + 1
        );
        $query = new WP_Query( $args );

        // Book Loop
        if( $query->have_posts() ):
            while( $query->have_posts() ): $query->the_post();
            $image_url = esc_attr(get_post_meta(get_the_ID(),'book_image_url',true));
            $terms = get_the_terms( get_the_ID(), 'bookcategory' );
            $cat = array();
            $id = '';
            if( $terms ){
                foreach( $terms as $term ){
                    $cat[] = $term->name.' ';
                    $slug = $term->slug;
                    $id  .= ' '.$term->slug.'-'.$term->term_id;
                }
            }

?>
        <div class="portfolio--item portfolio-item <?php echo esc_attr($id);?>">
            <a href="<?php echo esc_url($image_url);?>" class="portfolio-image popup-gallery" title="Bread">
                <img src="<?php echo esc_url($image_url);?>" alt=""/>
                <div class="portfolio-hover-title">
                    <div class="portfolio-content">
                        <h4><?php the_title();?></h4>
                        <div class="portfolio-category">
                            <span><?php echo esc_html($slug);?></span>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <?php
        endwhile;
        wp_reset_postdata();
        endif;
        die();
        ?>

    </div>
    <?php
    }

	public function book_shortcode_add($atts, $content = null){
        $a = shortcode_atts( array(
            'per_page' => 3,
        ), $atts );
		ob_start();
		?>
        <div class="section" data-aos="fade">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="text-center">
                            <ul class="portfolio-filter text-center">
                                <li class="active"><a href="#" data-filter="*"> All</a></li>
                                <?php
                                    $category = get_terms( 'bookcategory', array( 'hide_empty' => true ));
                                    foreach ( $category as $w_cat ) :
                                        echo '<li><a href="#" data-filter=".'.$w_cat->slug.'-'.$w_cat->term_id.'">'.$w_cat->name.'</a></li>';
                                    endforeach;
                                ?>
                            </ul>
                        </div>

                        <div class="portfolio--items portfolio-grid portfolio-gallery grid-4 gutter">
                        <?php

                            $args = array(
                                'post_type' => 'book',
                                'posts_per_page' => $a['per_page'],
                            );
                            $query = new WP_Query( $args );

                            // Localize
                            wp_localize_script(
                                'portfolio-js',
                                'galleryloadajax',
                                array(
                                    'action_url' => admin_url( 'admin-ajax.php' ),
                                    'current_page' => ( get_query_var('paged') ) ? get_query_var('paged') : 1,
                                    'posts' => json_encode( $query->query_vars ),
                                    'max_pages' => $query->max_num_pages,
                                    'postNumber' => $a['per_page'],
                                    'col' => 3,
                                    'btnLabel' => esc_html__( 'Load More', 'book' ),
                                    'btnLodingLabel' => esc_html__( 'Loading....', 'book' ),
                                )
                            );

                            // Book Loop
                            if( $query->have_posts() ):
                                while( $query->have_posts() ): $query->the_post();
                                $image_url = esc_attr(get_post_meta(get_the_ID(),'book_image_url',true));
                                $terms = get_the_terms( get_the_ID(), 'bookcategory' );
                                $cat = array();
                                $id = '';
                                if( $terms ){
                                    foreach( $terms as $term ){
                                        $cat[] = $term->name.' ';
                                        $slug = $term->slug;
                                        $id  .= ' '.$term->slug.'-'.$term->term_id;
                                    }
                                }

                          ?>
                            <div class="portfolio--item portfolio-item <?php echo esc_attr($id);?>">
                                <a href="<?php echo esc_url($image_url);?>" class="portfolio-image popup-gallery">
                                    <img src="<?php echo esc_url($image_url);?>" alt=""/>
                                    <div class="portfolio-hover-title">
                                        <div class="portfolio-content">
                                            <h4><?php the_title();?></h4>
                                            <div class="desc">
                                                <span><?php the_content();?></span>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <?php
                            endwhile;
                            wp_reset_postdata();
                            echo '<span class="dataload"></span>';
                            endif;
                            ?>

                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- .section -->

        <?php
                                // Portfolio Footer Start
             if( $query->max_num_pages > 0 ):
         ?>
                <div class="portfolio--footer">
                    <div class="load-more-btn">
                         <a class="btn loadAjax btn-default"><?php esc_html_e( 'Load More', 'book' ); ?></a>
                    </div>
                </div>
        <?php
           endif;
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
