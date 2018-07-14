<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://platform.neshan.org
 * @since      1.0.0
 *
 * @package    Neshan_Maps
 * @subpackage Neshan_Maps/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Neshan_Maps
 * @subpackage Neshan_Maps/public
 * @author     Neshan Platform <platform@neshan.org>
 */
class Neshan_Maps_Simple_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 *
	 * @param      string $plugin_name The name of the plugin.
	 * @param      string $version     The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version     = $version;
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style( 'neshan-web-sdk', 'https://static.neshan.org/api/web/v1/openlayers/v4.6.5.css', array(),
			$this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( 'neshan-polyfill-check',
			'https://cdn.polyfill.io/v2/polyfill.min.js?features=requestAnimationFrame,Element.prototype.classList,URL',
			array(), null, true );

		wp_enqueue_script( 'neshan-web-sdk', 'https://static.neshan.org/api/web/v1/openlayers/v4.6.5.js',
			array( 'neshan-polyfill-check' ), $this->version, true );
	}

	public function render($id, $data, $content) {
		$width = strpos( $data->width, '%' ) !== false ? $data->width : $data->width . 'px';
		$height = strpos( $data->height, '%' ) !== false ? $data->height : $data->height . 'px';

		$output = '';
		$output .= "<div id='neshan_map_{$id}' style='width: {$width}; height: {$height};'>";

		if ( ! is_null( $content ) ) {
			$output .= apply_filters( 'the_content', $content );
			$output .= do_shortcode( $content );
		}

		$output .= '</div>';

		wp_add_inline_script( 'neshan-web-sdk', "initNeshanMap{$id}();", 'after' );

		include_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/simple-map.php';
	}

}
