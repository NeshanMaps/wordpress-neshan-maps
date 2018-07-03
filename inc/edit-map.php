<?php
if( isset($_POST["submit"]) ){ 
update_map_items($_POST['map_id'], $_POST['map-name'], $_POST['key'], $_POST['lat'], $_POST['lng'], $_POST['width'], $_POST['height'], $_POST['maptype'] ); 
} 
?>
<div id="neshan_header" class="rtl">
    <div class="container">
        <a id="neshan_header_logo" href="https://developers.neshan.org" target="_blank">&nbsp;</a>
        <h1>
            <?php _e('Badge mapping tool', 'neshan'); ?>
        </h1>
    </div>
</div>
<br>
<?php
    global $wpdb;
    $table_name = $wpdb->prefix . "neshan_maps";
    $id = $_GET['id_item'];
    $show_maps = $wpdb->get_results(
    "SELECT * FROM $table_name WHERE id=$id"
);

foreach ( $show_maps as $show_map )
{
    $encode = $show_map->map_data;
        $items_array = json_decode( $encode, true );
        foreach ($items_array as $key => $value) {?>
    <form id="neshan_form" method="post">
        <div class="neshan_form_box neshan_form_button">
            <div class="neshan_form_title"><?php _e('Update Map Setting', 'neshan'); ?></div>
            <div class="form-row">
                <div class="col-sm-9 col-9">
                <label for="map-name"><?php _e('Map Name:', 'neshan'); ?></label>
                    <input type="text" class="form-control rtl neshan_dynamic_changer" id="map-name" name="map-name" value="<?php echo $show_map->map_name;?>" aria-describedby="map-name_help">
                    <small id="map-name_help" class="form-text text-muted"><?php _e('If you want to enter a new name.', 'neshan'); ?></small>
                    <input type="hidden" name="map_id" value="<?php echo $id ?>">
                </div>
                <div class="col-sm-3 col-3">
                   <label for="button_shortcode"><?php _e('Map Update', 'neshan');?></label>
                    <button id="button_shortcode" class="button button-primary button-large btn-block" name="submit" type="submit"  aria-describedby="button_shortcode_help"><?php _e('Save Map', 'neshan'); ?></button>
                    <small id="button_shortcode_help" class="form-text text-muted"><?php _e('Click on Update Map to edit your map.', 'neshan');?></small>
                </div><br>
                <div class="col-sm-12 col-12"  style="margin-top: 20px;">
                    <p class="bg-success" style="color:#f5f5f5;padding: 10px 5px;"><?php echo '<span style="display: inline-block;">'; _e('Map shortcode Use In Pages.', 'neshan'); echo '</span>'; $shortcode_id_map = '[neshan_map id="'.$id.'"]'; 
   echo '<span style="display: inline-block;margin-right: 35%;direction: ltr;">'.$shortcode_id_map.'</span>';?></p>
                </div>
            </div>
        </div>
<div id="neshan_map_holder" class="container">
    <div id="neshan_map"></div>
</div>
    <div id="neshan_form_holder" class="container rtl">

        <div id="neshan_form_locker"></div>
        <div class="neshan_form_box">
            <div class="form-group">
                <label for="api_key" class="neshan_form_title ltr">Api Key</label>
                <input type="text" class="form-control ltr neshan_dynamic_changer" id="api_key" name="key" aria-describedby="api_key_help" value="<?php echo $value["api_key"]; ?>" >
                <div id="api_key_help" class="form-text text-muted">
                   <?php _e('
                    If still <span class="ltr">Api Key</span> You can not get it by visiting
                    <a href="https://developer.neshan.org" target="_blank">Developer badge</a>
                    Sign up for free and get it.', 'neshan'); ?>
                </div>
            </div>
        </div>

        <div class="neshan_form_box">
            <div class="neshan_form_title" style="margin-bottom: 15px;"><?php _e('Map settings', 'neshan'); ?></div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="lat"><?php _e('Latitude:', 'neshan'); ?></label>
                    <input type="text" class="form-control ltr neshan_dynamic_changer" id="lat" name="lat" value="<?php echo $value["lat"]; ?>">
                </div>
                <div class="form-group col-md-6">
                    <label for="lng"><?php _e('Longitude:', 'neshan'); ?></label>
                    <input type="text" class="form-control ltr neshan_dynamic_changer" id="lng" name="lng" value="<?php echo $value["lng"]; ?>">
                </div>
            </div>

            <div id="lat_lng_error" class="alert alert-danger hidden" role="alert" style="margin-bottom: 0;">
                <?php _e('Latitude and Longitude values are not entered correctly!', 'neshan'); ?>
            </div>

            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="width"><?php _e('Image Width:', 'neshan');?></label>
                    <input type="text" class="form-control ltr neshan_dynamic_changer" id="width" name="width" value="<?php echo $value["width"]; ?>" aria-describedby="width_help">
                    <small id="width_help" class="form-text text-muted"><?php _e('Maximum permissible value is 1200 px / 100%.', 'neshan'); ?></small>
                </div>
                <div class="form-group col-md-4">
                    <label for="height"><?php _e('Image height:', 'neshan');?></label>
                    <input type="text" class="form-control ltr neshan_dynamic_changer" id="height" name="height" value="<?php echo $value["height"]; ?>" aria-describedby="height_help">
                    <small id="height_help" class="form-text text-muted"><?php _e('Maximum permissible value is 1200 px.', 'neshan'); ?></small>
                </div>
                <div class="form-group col-md-4">
                    <label for="zoom"><?php _e('Zoom in:', 'neshan');?></label>
                    <input type="text" class="form-control ltr neshan_dynamic_changer" id="zoom" name="zoom" value="<?php echo $value["zoomlevel"]; ?>" aria-describedby="zoom_help">
                    <small id="zoom_help" class="form-text text-muted"><?php _e('Maximum permissible value is 19.', 'neshan'); ?></small>
                </div>
            </div>
        </div>

        <div class="neshan_form_box">
            <div class="neshan_form_title"><?php _e('Map Style', 'neshan');?></div>
            
            <div class="row" id="neshan_maptype_switcher_wrapper">
<?php for($i=0;$i<=3;$i++){
            
   if($i == '0'){
       $map_type = 'standard-day';
   }elseif($i == '1'){
       $map_type = 'standard-night';
   }elseif($i == '2'){
       $map_type = 'neshan';
   }elseif($i == '3'){
       $map_type = 'osm-bright';
   }
            
   if( $value["maptype"] === $map_type ){
           $active = 'class="active"';
           $checked = 'checked="checked"';
       }else{
           $active = '';
           $checked = '';
       }
        
           echo '<div class="col-sm-3 col-6 mb-3">
                    <label rel="'.$map_type.'" for="maptype-'.$map_type.'" '.$active.'></label>
                    <input type="radio" name="maptype" '.$checked.' id="maptype-'.$map_type.'" value="'.$map_type.'">
                </div>';   
}
?>               
            </div>
        </div>
        <div class="neshan_form_box neshan_form_button">
            <div class="neshan_form_title"><?php _e('Update Map Setting', 'neshan'); ?></div>
            <div class="form-row">
                <div class="col-sm-12 col-12">
                   <label for="button_shortcode"><?php _e('Map Update', 'neshan');?></label>
                    <button id="button_shortcode" class="button button-primary button-large btn-block" name="submit" type="submit"  aria-describedby="button_shortcode_help"><?php _e('Save Map', 'neshan'); ?></button>
                    <small id="button_shortcode_help" class="form-text text-muted"><?php _e('Update a map by clicking on the map.', 'neshan'); ?></small>
                </div><br>
                <div class="col-sm-12 col-12"  style="margin-top: 20px;">
                    <p class="bg-success" style="color:#f5f5f5;padding: 10px 5px;"><?php echo '<span style="display: inline-block;">'; _e('Map shortcode Use In Pages.', 'neshan'); echo '</span>'; $shortcode_id_map = '[neshan_map id="'.$id.'"]'; 
   echo '<span style="display: inline-block;margin-right: 35%;direction: ltr;">'.$shortcode_id_map.'</span>';?></p>
                </div>
            </div>
        </div>
       </div>
    </form>

<?php } } ?>
