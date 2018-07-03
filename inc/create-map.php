<div id="neshan_header" class="rtl">
    <div class="container">
        <a id="neshan_header_logo" href="https://developers.neshan.org" target="_blank">&nbsp;</a>
        <h1>
            <?php _e('Badge mapping tool', 'neshan'); ?>
        </h1>
    </div>
</div>
<div id="neshan_form_holder" class="container rtl">
   <br>
    <form id="neshan_form" method="post">
      <div class="neshan_form_box neshan_form_button">
            <div class="neshan_form_title"><?php _e('Create Map Setting', 'neshan'); ?></div>
            <div class="form-row">
                <div class="col-sm-9 col-9">
                <label for="map-name"><?php _e('Map Name:', 'neshan'); ?></label>
                    <input type="text" class="form-control rtl neshan_dynamic_changer" id="map-name" name="map-name" aria-describedby="map-name_help">
                    <small id="map-name_help" class="form-text text-muted"><?php _e('Set In Name For Map', 'neshan');?></small>
                    
                </div>
                <div class="col-sm-3 col-3">
                   <label for="button_shortcode">ساختن نقشه:</label>
                    <button id="button_shortcode" class="button button-primary button-large btn-block" name="submit" type="submit"  aria-describedby="button_shortcode_help"><?php _e('Create Map', 'neshan'); ?></button>
                    <small id="button_shortcode_help" class="form-text text-muted"><?php _e('Create a map by clicking on the map.', 'neshan'); ?></small>
                </div>
            </div>
        </div>   
<?php if( isset($_POST["submit"]) ){ ?>
        <div class="neshan_form_box neshan_form_show_shortcode">
        <div class="neshan_form_title"><?php _e('Map Created', 'neshan'); ?></div>
        <div class="form-row">
                <div class="col-sm-12 col-12">
                <br>
<?php
insert_data_in_dbneshan( $_POST['map-name'], $_POST['key'], $_POST['lat'], $_POST['lng'], $_POST['width'], $_POST['height'], $_POST['maptype'] );
?>
                </div>
            </div>
        </div>
        <?php } ?>
       <div id="neshan_map_holder" class="container">
            <div id="neshan_map"></div>
        </div>
        <div id="neshan_form_locker"></div>
        <div class="neshan_form_box">
            <div class="form-group">
                <label for="key" class="neshan_form_title ltr"><?php _e('Api Key', 'neshan'); ?></label>
                <input type="text" class="form-control ltr neshan_dynamic_changer" id="key" name="key" aria-describedby="api_key_help" placeholder="<?php _e('YOUR_API_KEY', 'neshan'); ?>">
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
                    <input type="text" class="form-control ltr neshan_dynamic_changer" id="lat" name="lat" placeholder="Latitude">
                </div>
                <div class="form-group col-md-6">
                    <label for="lng"><?php _e('Longitude:', 'neshan'); ?></label>
                    <input type="text" class="form-control ltr neshan_dynamic_changer" id="lng" name="lng" placeholder="Longitude">
                </div>
            </div>

            <div id="lat_lng_error" class="alert alert-danger hidden" role="alert" style="margin-bottom: 0;">
                <?php _e('Latitude and Longitude values are not entered correctly!', 'neshan'); ?>
            </div>

            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="width"><?php _e('Image Width:', 'neshan');?></label>
                    <input type="text" class="form-control ltr neshan_dynamic_changer" id="width" name="width" placeholder="For example 500" aria-describedby="width_help">
                    <small id="width_help" class="form-text text-muted"><?php _e('Maximum permissible value is 1200 px / 100%.', 'neshan'); ?></small>
                </div>
                <div class="form-group col-md-4">
                    <label for="height"><?php _e('Image height:', 'neshan');?></label>
                    <input type="text" class="form-control ltr neshan_dynamic_changer" id="height" name="height" placeholder="For example 400" aria-describedby="height_help">
                    <small id="height_help" class="form-text text-muted"><?php _e('Maximum permissible value is 1200 px.', 'neshan'); ?></small>
                </div>
                <div class="form-group col-md-4">
                    <label for="zoom"><?php _e('Zoom in:', 'neshan');?></label>
                    <input type="text" class="form-control ltr neshan_dynamic_changer" id="zoom" name="zoom" placeholder="For example 12" aria-describedby="zoom_help">
                    <small id="zoom_help" class="form-text text-muted"><?php _e('Maximum permissible value is 19.', 'neshan'); ?></small>
                </div>
            </div>
        </div>
        <div class="neshan_form_box">
            <div class="neshan_form_title"><?php _e('Map Style', 'neshan');?></div>

            <div class="row" id="neshan_maptype_switcher_wrapper">
                <div class="col-sm-3 col-6 mb-3">
                    <label rel="standard-day" for="maptype-standard-day"></label>
                    <input type="radio" name="maptype" id="maptype-standard-day" value="standard-day">
                </div>

                <div class="col-sm-3 col-6 mb-3">
                    <label rel="standard-night" for="maptype-standard-night"></label>
                    <input type="radio" name="maptype" id="maptype-standard-night" value="standard-night">
                </div>

                <div class="col-sm-3 col-6">
                    <label rel="neshan" for="maptype-neshan" class="active"></label>
                    <input type="radio" name="maptype" id="maptype-neshan" checked value="neshan">
                </div>

                <div class="col-sm-3 col-6">
                    <label rel="osm-bright" for="maptype-osm-bright"></label>
                    <input type="radio" name="maptype" id="maptype-osm-bright" value="osm-bright">
                </div>
            </div>

        </div>
        <div class="neshan_form_box neshan_form_button">
            <div class="neshan_form_title"><?php _e('Create Map Setting', 'neshan'); ?></div>
            <div class="form-row">
                <div class="col-sm-12 col-12">
                   <label for="button_shortcode"><?php _e('Map Create:', 'neshan');?></label>
                    <button id="button_shortcode" class="button button-primary button-large btn-block" name="submit" type="submit"  aria-describedby="button_shortcode_help"><?php _e('Create Map', 'neshan'); ?></button>
                    <small id="button_shortcode_help" class="form-text text-muted"><?php _e('Create a map by clicking on the map.', 'neshan'); ?></small>
                </div>
            </div>
        </div> 
    </form>
</div>