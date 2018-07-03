<?php

/*
Plugin Name:  Neshan-Maps
Plugin URI:   https://static.neshan.org/api/web/v1/examples/maptypes/
Description:  Neshan Map Simple Wordpress Plugin
Version:      1.0.0
Author:       
Author URI:   
License:      GPL2
License URI:  https://www.gnu.org/licenses/gpl-2.0.html
Text Domain:  neshan
Domain Path:  /languages
*/

register_activation_hook(__FILE__, 'neshan_maps_install');
register_uninstall_hook(__FILE__, 'pluginUninstall');

define('NDP', plugin_dir_url(__DIR__).'neshan-maps');
define("ADMINAJAX" , admin_url( "admin-ajax.php" ));
define("ADMINAJAX" , admin_url( "admin-ajax.php" ));

/*  Load plugin textdomain */

add_action( 'plugins_loaded', 'neshan_load_textdomain' );
function neshan_load_textdomain() {
  load_plugin_textdomain( 'neshan', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' ); 
}

include_once 'db/db.php';

/* Incloud Files In Front Site */

add_action('wp_enqueue_scripts', 'load_neshan_users_style');
function load_neshan_users_style() {
    
    wp_register_style( 'static_neshan', 'https://static.neshan.org/api/web/v1/openlayers/v4.6.5.css' );
    wp_enqueue_style( 'static_neshan' );
    
    wp_register_script( 'cdn_polyfill', 'https://cdn.polyfill.io/v2/polyfill.min.js?features=requestAnimationFrame,Element.prototype.classList,URL', false, true );
    wp_enqueue_script('cdn_polyfill');
    
    wp_register_script( 'static_neshan', 'https://static.neshan.org/api/web/v1/openlayers/v4.6.5.js?callback=initMyMap',array(), false, true );
    wp_enqueue_script('static_neshan');
    
}

function add_async_attribute($tag, $handle) {

   $scripts_to_async = array('static_neshan', 'another-handle');
   
   foreach($scripts_to_async as $async_script) {
      if ($async_script === $handle) {
         return str_replace('src', 'defer async src', $tag);
      }
   }
   return $tag;
}
add_filter('script_loader_tag', 'add_async_attribute', 10, 2);

/* Incloud Files In BackEnd Site */
/*if( $_GET[page] === 'create_map_page' || $_GET[page] === 'neshan_page_item' || $_GET[page] === 'neshan_my_maps'):
add_action( 'admin_enqueue_scripts', 'load_neshan_admin_style' );
endif;
function load_neshan_admin_style() {
    wp_register_style( 'openlayers', 'https://static.neshan.org/api/web/v1/openlayers/v4.6.5.css', false, '1.0.0' );
    wp_enqueue_style( 'openlayers' );
    
    wp_register_style( 'bootstrap_rtl', 'https://static.neshan.org/api/web/v1/lib/bootstrap-rtl/v4.1.1/css/bootstrap-rtl.min.css', false, '1.0.0' );
    wp_enqueue_style( 'bootstrap_rtl' );
    
    wp_register_style( 'MapMaker', 'https://static.neshan.org/api/web/v1/tools/assets/css/MapMaker.css', false, '1.0.0' );
    wp_enqueue_style( 'MapMaker' );
    
    wp_register_style( 'styles', 'https://static.neshan.org/api/web/v1/tools/wordpress-map-maker/styles.css', false, '1.0.0' );
    wp_enqueue_style( 'styles' );
    
    
    wp_register_script( 'bootstrap_min', 'https://static.neshan.org/api/web/v1/lib/bootstrap-rtl/v4.1.1/js/bootstrap.min.js', array( 'jquery' ), true );
    wp_enqueue_script('bootstrap_min');
    
    wp_register_script( 'callback', 'https://static.neshan.org/api/web/v1/openlayers/v4.6.5.js?callback=initMyMap', array('scripts', 'wordpress_map_maker'), false, true );
    wp_enqueue_script('callback');
    
    wp_register_script( 'scripts', 'https://static.neshan.org/api/web/v1/tools/assets/js/MapMaker.js',array( 'jquery' ), true );
    wp_enqueue_script('scripts');

    wp_register_script( 'wordpress_map_maker', 'https://static.neshan.org/api/web/v1/tools/wordpress-map-maker/scripts.js',array( 'jquery' ), true );
    wp_enqueue_script('wordpress_map_maker');

}*/

if( $_GET[page] === 'create_map_page' || $_GET[page] === 'neshan_page_item' || $_GET[page] === 'neshan_my_maps'):
add_action( 'admin_head', 'neshan_styles_admin' );
add_action('admin_footer', 'neshan_scripts_admin');

function neshan_styles_admin() { ?>

    <link rel="stylesheet" type="text/css" href="https://static.neshan.org/api/web/v1/openlayers/v4.6.5.css">
    <link rel="stylesheet" type="text/css" href="https://static.neshan.org/api/web/v1/lib/bootstrap-rtl/v4.1.1/css/bootstrap-rtl.min.css">
    <link rel="stylesheet" type="text/css" href="https://static.neshan.org/api/web/v1/fonts/samim/css/style.css">

    <link rel="stylesheet" type="text/css" href="https://static.neshan.org/api/web/v1/tools/assets/css/MapMaker.css">
    <link rel="stylesheet" type="text/css" href="https://static.neshan.org/api/web/v1/tools/wordpress-map-maker/styles.css">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:400,700" media="none" onload="if(media!='all')media='all'"><?php
}

function neshan_scripts_admin() {
	echo '<!-- The line below is only needed for old environments like Internet Explorer and Android 4.x -->
<script src="https://cdn.polyfill.io/v2/polyfill.min.js?features=requestAnimationFrame,Element.prototype.classList,URL"></script>

<script type="text/javascript" src="https://static.neshan.org/api/web/v1/lib/bootstrap-rtl/v4.1.1/js/bootstrap.min.js" async></script>

<script type="text/javascript" src="https://static.neshan.org/api/web/v1/tools/assets/js/MapMaker.js?v='.time().'"></script>
<script type="text/javascript" src="https://static.neshan.org/api/web/v1/tools/wordpress-map-maker/scripts.js?v='.time().'"></script>

<script type="text/javascript" src="https://static.neshan.org/api/web/v1/openlayers/v4.6.5.js?callback=initMyMap&v=2" defer async></script>';
}

endif;
/* Include File Admin Page */
include_once 'inc/admin-page.php';

/* Include File Front Page */
include_once 'inc/front-page.php';

/* Include File Show Item */
include_once 'inc/show-item.php';

?>