<?php

/* Create Menu Admin */

add_action('admin_menu', 'create_neshan_menu');
function create_neshan_menu() {
    add_menu_page( __('Neshan Map', 'neshan'), __('Neshan Map', 'neshan'), 'add_users', 'neshan_my_maps', 'neshan_content_page_item', 'dashicons-location-alt', null);
    
    add_submenu_page( 'neshan_my_maps', __('Show Map Item', 'neshan'), __('My Maps', 'neshan'), 'edit_posts', 'neshan_page_item', 'neshan_content_page_item' );
    add_submenu_page( 'neshan_my_maps', __('Create New Map', 'neshan'), __('Create New Map', 'neshan'), 'edit_posts', 'create_map_page', 'neshan_content_page' );
    remove_submenu_page('neshan_my_maps', 'neshan_my_maps');
}

/* Display Form Create Map Neshan In Dashboard Site */

function neshan_content_page(){
    echo '<div class="wrap">';
    
if( $_GET['page'] === 'create_map_page' && $_GET['edit_item'] === 'update'){
    include_once 'edit-map.php';
}elseif( $_GET['page'] === 'create_map_page' ){
    include_once 'create-map.php';
}else{
    include_once 'show-item.php';
}
    
    echo '</div>';
}

?>