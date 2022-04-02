<?php
namespace WPFunnels\Widgets\Gutenberg;

use WPFunnels\Wpfnl;
use WPFunnels\Wpfnl_functions;

/**
 * BlockTypesController class.
 *
 * @since 5.0.0
 * @internal
 */
final class Manager {

    /**
     * Instance
     *
     * @since 1.0.0
     *
     * @access private
     * @static
     *
     * @var Gutenberg_Block The single instance of the class.
     */
    private static $_instance = null;


    /**
     * Instance
     *
     * Ensures only one instance of the class is loaded or can be loaded.
     *
     * @return Gutenberg_Block An instance of the class.
     * @since 1.0.0
     *
     * @access public
     * @static
     *
     */
    public static function instance()
    {

        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;

    }

    public function __construct(  ) {
        $this->init();
    }

    /**
     * Initialize class features.
     */
    protected function init() {
        add_action( 'init', array( $this, 'register_assets' ) );
        add_action( 'init', array( $this, 'register_blocks' ) );
		add_action( 'wp_footer', array( $this, 'wpfnl_inline_footer_scripts' ) );
		add_action( 'wp_head', array( $this, 'add_block_inline_css' ), 100 );
		new Wpfnl_Gutenberg_Editor();
	}

    /**
     * Register blocks, hooking up assets and render functions as needed.
     */
    public function register_blocks() {
        $block_types = $this->get_block_types();

        foreach ( $block_types as $block_type ) {
            $block_type_class    = __NAMESPACE__ . '\\BlockTypes\\' . $block_type;
            $block_type_instance = new $block_type_class();
        }
    }


    /**
     * Get list of block types.
     *
     * @return array
     */
    protected function get_block_types() {
        return apply_filters('wpfunnels/gutenberg_block_types', array(
            'NextStepButton',
            'CheckoutForm',
            'OrderDetails',
            'OptinForm',
        ));
    }


	/**
	 * register assets for gutenberg
	 *
	 * @since 2.0.3
	 */
    public function register_assets() {
        wp_register_style( 'wpfnl-blocks-editor-style', plugins_url( 'block/assets/dist/wpfnl-blocks-editor-style.css', __DIR__ ), [], '2.0.3', 'all' );
        wp_register_style( 'wpfnl-blocks-style',plugins_url( 'block/assets/dist/wpfnl-blocks-style.css', __DIR__ ), [], '2.0.3', 'all' );
    }


	/**
	 * Load Inline Footer Script
	 *
	 * @since 1.3.0
	 */
	public function wpfnl_inline_footer_scripts() {
		global $wp_query;
		$is_previewing= $wp_query->is_preview();
		$can_edit= current_user_can( 'edit_posts' );
		if($is_previewing || $can_edit){
			?>
			<script>
				// Set Preview CSS
				document.addEventListener("DOMContentLoaded", function() {
					const cussrent_url = window.location.href;
					let cssInline = document.createElement('style');
					cssInline.type = 'text/css';
					cssInline.id = 'wpfnl-block-js-preview';
					cssInline.innerHTML =JSON.parse( localStorage.getItem('qubelyCSS'));
					window.document.getElementsByTagName("head")[0].appendChild(cssInline);
				})
			</script>
			<?php
		}
	}


	/**
	 * Check current post page open and css path exists
	 * Then read the css file content from css path
	 * Then add inline css to the header
	 */
	public function add_block_inline_css() {
		$upload_dir     = wp_get_upload_dir();
		$upload_css_dir = trailingslashit( $upload_dir['basedir'] );
		$post_id = get_the_ID();
		if ( $post_id ) {
			$css_path       = $upload_css_dir . "wpfunnels/css/wpfnl-css-{$post_id}.css";
			$json_path      = $upload_css_dir . "wpfunnels/css/wpfnl-json-{$post_id}.json";

			if ( file_exists( $css_path ) ) {
				$blockCss = file_get_contents( $css_path );
				echo '<style type="text/css">' . $blockCss . '</style>';
			} else {
				echo '<style type="text/css">' . get_post_meta( get_the_ID(), '_wpfunnels_gb_css', true ) . '</style>';
			}
		}
	}
}
