<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://platform.neshan.org
 * @since      1.0.0
 *
 * @package    Neshan_Maps
 * @subpackage Neshan_Maps/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Neshan_Maps
 * @subpackage Neshan_Maps/admin
 * @author     Neshan Platform <platform@neshan.org>
 */
class Neshan_Maps_Admin {

	private $create_page_hook;
	private $list_page_hook;

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
	 * Is wordpress loaded in a RTL language mode.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $is_rtl Is current Wordpress language RTL?
	 */
	private $is_rtl;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 *
	 * @param      string $plugin_name The name of this plugin.
	 * @param      string $version     The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

		$lang = get_locale();

		if ( $lang === 'fa_IR' ) {
			$this->is_rtl = true;
		}
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @param $hook
	 *
	 * @since    1.0.0
	 *
	 */
	public function enqueue_styles( $hook ) {
		if ( $hook === $this->create_page_hook ) {
			$dir = $this->is_rtl ? '-rtl' : '';

			wp_enqueue_style( 'neshan-web-sdk', 'https://static.neshan.org/api/web/v1/openlayers/v4.6.5.css', array(),
				$this->version, 'all' );
			wp_enqueue_style( $this->plugin_name . '_bootstrap',
				plugin_dir_url( __FILE__ ) . "css/bootstrap{$dir}.min.css", array(), $this->version, 'all' );
			wp_enqueue_style( $this->plugin_name . '_maker', plugin_dir_url( __FILE__ ) . 'css/MapMaker.css', array(),
				$this->version, 'all' );
			wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/neshan-maps-admin.css', array(),
				$this->version, 'all' );
		} elseif ( $hook === $this->list_page_hook ) {
			wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/my-maps.css', array(),
				$this->version, 'all' );
		}
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @param $hook
	 *
	 * @since    1.0.0
	 *
	 */
	public function enqueue_scripts( $hook ) {
		if ( $hook === $this->create_page_hook ) {
			wp_enqueue_script( 'neshan-polyfill-check',
				'https://cdn.polyfill.io/v2/polyfill.min.js?features=requestAnimationFrame,Element.prototype.classList,URL',
				array(), null, true );

			wp_enqueue_script( 'neshan-web-sdk', 'https://static.neshan.org/api/web/v1/openlayers/v4.6.5.js',
				array( 'neshan-polyfill-check' ), $this->version . '111', true );

			wp_enqueue_script( $this->plugin_name . '_bootstrap', plugin_dir_url( __FILE__ ) . 'js/bootstrap.min.js',
				array( 'jquery' ), $this->version, true );

			wp_enqueue_script( $this->plugin_name . '_maker', plugin_dir_url( __FILE__ ) . 'js/MapMaker.js',
				array( 'jquery', $this->plugin_name . '_bootstrap' ), $this->version, true );

			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/neshan-maps-admin.js',
				array( $this->plugin_name . '_maker' ), $this->version, true );

			$scripts = json_encode( array(
				'translate' => array(
					'api_key_help' => __( 'Please fill Api Key field by the appropriate key (WebSite) you received from Neshan Developers Panel',
						'neshan-maps' ),
				)
			) );

			wp_add_inline_script( $this->plugin_name, "var neshan_options = {$scripts};", 'before' );
		} elseif ( $hook === $this->list_page_hook ) {
			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/my-maps.js', array( 'jquery' ),
				$this->version, true );

			$scripts = json_encode( array(
				'translate' => array(
					'delete_confirm_message' => __( 'Are you sure about deleting this map?', 'neshan-maps' )
				)
			) );

			wp_add_inline_script( $this->plugin_name, "var neshan_options = {$scripts};", 'before' );
		}
	}

	public function menu() {
		add_menu_page( __( 'Neshan Maps', 'neshan-maps' ), __( 'Neshan Map', 'neshan-maps' ), 'manage_options',
			'neshan_maps', array( $this, 'page_my_maps' ), 'dashicons-location-alt', null );

		$this->list_page_hook = add_submenu_page( 'neshan_maps', __( 'My Maps', 'neshan-maps' ),
			__( 'My Maps', 'neshan-maps' ), 'manage_options', 'neshan_maps', array( $this, 'page_my_maps' ) );

		$this->create_page_hook = add_submenu_page( 'neshan_maps', __( 'Create New Map', 'neshan-maps' ),
			__( 'Create New Map', 'neshan-maps' ), 'manage_options', 'neshan_maps_create',
			array( $this, 'page_create' ) );
	}

	public function save() {
		global $wpdb;

		$id = null;

		if ( isset( $_POST['id'] ) ) {
			$id = sanitize_text_field( $_POST["id"] );
			check_ajax_referer( 'update_neshan_map_' . $id, 'token' );
		} else {
			check_ajax_referer( 'create_neshan_map', 'token' );
		}

		$date = current_time( 'mysql', false );

		$title   = sanitize_text_field( $_POST["title"] );
		$api_key = sanitize_text_field( $_POST["key"] );
		$lat     = sanitize_text_field( $_POST["lat"] );
		$lng     = sanitize_text_field( $_POST["lng"] );
		$width   = sanitize_text_field( $_POST["width"] );
		$height  = sanitize_text_field( $_POST["height"] );
		$zoom    = sanitize_text_field( $_POST["zoom"] );
		$maptype = sanitize_text_field( $_POST["maptype"] );

		$data = array(
			"api_key" => "{$api_key}",
			"lat"     => "{$lat}",
			"lng"     => "{$lng}",
			"width"   => "{$width}",
			"height"  => "{$height}",
			"zoom"    => "{$zoom}",
			"maptype" => "{$maptype}",
		);

		if ( $id ) {
			$result = $wpdb->update( 'neshan_maps', array(
				'title'      => $title,
				'updated_at' => $date,
				'data'       => json_encode( $data )
			), array( 'id' => $id ), array( '%s', '%s', '%s' ), array( '%d' ) );
		} else {
			$result = $wpdb->insert( 'neshan_maps', array(
				'title'      => $title,
				'created_at' => $date,
				'updated_at' => $date,
				'data'       => json_encode( $data )
			), array( '%s', '%s', '%s' ) );
		}

		wp_send_json( array(
			'id' => $result !== false ? ( $id ? $id : $wpdb->insert_id ) : 0
		) );
	}

	public function delete() {
		global $wpdb;

		$id = sanitize_text_field( $_POST["id"] );

		check_ajax_referer( 'delete_neshan_map_' . $id, 'token' );

		$result = $wpdb->delete( 'neshan_maps', array( 'id' => $id ), array( '%d' ) );

		wp_send_json( array(
			'status' => $result ? 'OK' : 'ERROR',
		) );
	}

	public function page_my_maps() {
		global $wpdb;

		$maps = $wpdb->get_results( "SELECT * FROM neshan_maps ORDER BY updated_at DESC" );

		include_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/my-maps.php';
	}

	public function page_create() {
		$current_map = null;

		if ( isset( $_GET["id"] ) && isset( $_GET["action"] ) && $_GET["action"] === 'edit' ) {
			global $wpdb;

			$id = sanitize_text_field( $_GET["id"] );

			$maps = $wpdb->get_results( "SELECT * FROM neshan_maps WHERE id = {$id}" );

			if ( count( $maps ) === 1 ) {
				$current_map       = $maps[0];
				$current_map->data = json_decode( $current_map->data );

				$current_map->style = '';

				if ( strpos( $current_map->data->width, '%' ) !== false ) {
					$current_map->style = 'width: ' . $current_map->data->width . ';';
				}
				if ( strpos( $current_map->data->height, '%' ) !== false ) {
					$current_map->style = 'height: ' . $current_map->data->height . ';';
				}
			}
		}

		include_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/create-simple-map.php';
	}

}
