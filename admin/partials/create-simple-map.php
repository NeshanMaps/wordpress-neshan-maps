<?php

/**
 * Provide admin create new map view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://platform.neshan.org
 * @since      1.0.0
 *
 * @package    Neshan_Maps
 * @subpackage Neshan_Maps/admin/partials
 */

/** @var stdClass $current_map */
$maptype = $current_map ? $current_map->data->maptype : 'neshan';
?>

<div class="wrap" id="neshan_wrapper">
    <div id="neshan_header">
        <div class="container">
            <a id="neshan_header_logo" href="https://developers.neshan.org" target="_blank">&nbsp;</a>
            <h1>
				<?php _e( 'Neshan Map Maker', 'neshan-maps' ); ?>
                <small>v<?php echo $this->version; ?></small>
            </h1>
        </div>
    </div>

    <div id="neshan_form_holder" class="container">
		<?php if ( ! $current_map ): ?>
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
				<?php printf( __( 'You need an API key before you start creating a map. You can get your API key from %s Neshan Developers Panel %s for free.',
					'neshan-maps' ), '<a href="https://developer.neshan.org" target="_blank">', '</a>' ); ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
		<?php endif; ?>

        <div id="neshan_form_alert_fill_all_fields" class="alert alert-info  alert-dismissible fade show" role="alert">
			<?php _e( 'Please fill all fields correctly before saving the form. Then you can use the generated shortcode in any page you want.',
				'neshan-maps' ) ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>

        <div id="neshan_form_alert_error" class="alert alert-danger fade show hidden" role="alert">
			<?php _e( 'An error occurred while saving map data. Please check all fields and try again.',
				'neshan-maps' ) ?>
        </div>

        <div id="neshan_form_alert_success" class="alert alert-success fade show hidden" role="alert">
			<?php _e( 'Congratulation! Your map is ready. You can use below shortcode in any page you want:',
				'neshan-maps' ) ?>
            <pre></pre>

            <a href="<?php echo admin_url( 'admin.php?page=neshan_maps' ); ?>">
				<?php _e( 'Back to My Maps page', 'neshan-maps' ); ?>
            </a>
            &nbsp;|&nbsp;
            <a href="<?php echo admin_url( 'admin.php?page=neshan_maps_create' ); ?>">
				<?php _e( 'Create new map', 'neshan-maps' ); ?>
            </a>
        </div>

        <form id="neshan_form">
			<?php if ( $current_map ): ?>
				<?php wp_nonce_field( 'update_neshan_map_' . $current_map->id ); ?>
                <input type="hidden" name="id" value="<?php echo $current_map->id; ?>">
			<?php else: ?>
				<?php wp_nonce_field( 'create_neshan_map' ); ?>
			<?php endif; ?>
            <div class="neshan_form_box neshan_form_button">
                <div class="form-row">
                    <div class="col-sm-9 col-12">
                        <label for="title"><?php _e( 'Title' ); ?></label>
                        <input type="text" class="form-control rtl" id="title" name="title"
                               value="<?php echo $current_map ? $current_map->title : ''; ?>"
                               aria-describedby="title_help">
                        <small id="title_help" class="form-text text-muted"><?php _e( 'Choose a title for this map.',
								'neshan-maps' ); ?></small>
                    </div>
                    <div class="col-sm-3 col-12">
                        <button id="neshan_form_box_save"
                                class="button button-primary button-large btn-block mt-3 mt-sm-4" name="submit"
                                type="submit"><?php _e( $current_map ? 'Update Map' : 'Create Map',
								'neshan-maps' ); ?></button>
                    </div>
                </div>
            </div>
            <div id="neshan_map_holder" class="container">
                <div id="neshan_map"
                     style="<?php echo $current_map ? $current_map->style : ''; ?>"></div>
            </div>
            <div class="neshan_form_box">
                <div class="form-group">
                    <label for="key" class="neshan_form_title ltr"><?php _e( 'Api Key', 'neshan-maps' ); ?></label>
                    <input type="text" class="form-control ltr neshan_dynamic_changer" id="key" name="key"
                           value="<?php echo $current_map ? $current_map->data->api_key : ''; ?>"
                           aria-describedby="api_key_help" placeholder="YOUR_API_KEY">
                    <div id="api_key_help" class="form-text text-muted">
						<?php printf( __( 'You need an API key before you start creating a map. You can get your API key from %s Neshan Developers Panel %s for free.',
							'neshan-maps' ), '<a href="https://developer.neshan.org" target="_blank">', '</a>' ); ?>
                    </div>
                </div>
            </div>
            <div class="neshan_form_box">
                <div class="neshan_form_title" style="margin-bottom: 15px;"><?php _e( 'Map settings',
						'neshan-maps' ); ?></div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="lat"><?php _e( 'Latitude', 'neshan-maps' ); ?>:</label>
                        <input type="text" class="form-control ltr neshan_dynamic_changer" id="lat" name="lat"
                               value="<?php echo $current_map ? $current_map->data->lat : ''; ?>"
                               placeholder="Latitude">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="lng"><?php _e( 'Longitude', 'neshan-maps' ); ?>:</label>
                        <input type="text" class="form-control ltr neshan_dynamic_changer" id="lng" name="lng"
                               value="<?php echo $current_map ? $current_map->data->lng : ''; ?>"
                               placeholder="Longitude">
                    </div>
                </div>

                <div id="lat_lng_error" class="alert alert-danger hidden" role="alert" style="margin-bottom: 0;">
					<?php _e( 'Latitude or Longitude values did not fill correctly!', 'neshan-maps' ); ?>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="width"><?php _e( 'Width', 'neshan-maps' ); ?>:</label>
                        <input type="text" class="form-control ltr neshan_dynamic_changer" id="width" name="width"
                               value="<?php echo $current_map ? $current_map->data->width : ''; ?>"
                               placeholder="For example 500" aria-describedby="width_help">
                        <small id="width_help"
                               class="form-text text-muted">
							<?php _e( 'Set width in pixel for example 1000 or in percent', 'neshan-maps' ); ?>
                        </small>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="height"><?php _e( 'Height', 'neshan-maps' ); ?>:</label>
                        <input type="text" class="form-control ltr neshan_dynamic_changer" id="height" name="height"
                               value="<?php echo $current_map ? $current_map->data->height : ''; ?>"
                               placeholder="For example 400" aria-describedby="height_help">
                        <small id="height_help"
                               class="form-text text-muted">
							<?php _e( 'Set height in pixel for example 400', 'neshan-maps' ); ?>
                        </small>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="zoom"><?php _e( 'Zoom', 'neshan-maps' ); ?>:</label>
                        <input type="text" class="form-control ltr neshan_dynamic_changer" id="zoom" name="zoom"
                               value="<?php echo $current_map ? $current_map->data->zoom : ''; ?>"
                               placeholder="For example 12" aria-describedby="zoom_help">
                        <small id="zoom_help" class="form-text text-muted">
							<?php _e( 'Maximum zoom level is 19', 'neshan-maps' ); ?>
                        </small>
                    </div>
                </div>
            </div>
            <div class="neshan_form_box">
                <div class="neshan_form_title"><?php _e( 'Map Style', 'neshan-maps' ); ?></div>

                <div class="row" id="neshan_maptype_switcher_wrapper">
                    <div class="col-sm-3 col-6 mb-3">
                        <label rel="standard-day" for="maptype-standard-day"></label>
                        <input type="radio" name="maptype"
                               id="maptype-standard-day" <?php echo $maptype === 'standard-day' ? 'checked' : ''; ?>
                               value="standard-day">
                    </div>

                    <div class="col-sm-3 col-6 mb-3">
                        <label rel="standard-night" for="maptype-standard-night"></label>
                        <input type="radio" name="maptype"
                               id="maptype-standard-night" <?php echo $maptype === 'standard-night' ? 'checked' : ''; ?>
                               value="standard-night">
                    </div>

                    <div class="col-sm-3 col-6">
                        <label rel="neshan" for="maptype-neshan"></label>
                        <input type="radio" name="maptype"
                               id="maptype-neshan" <?php echo $maptype === 'neshan' ? 'checked' : ''; ?>
                               value="neshan">
                    </div>

                    <div class="col-sm-3 col-6">
                        <label rel="osm-bright" for="maptype-osm-bright"></label>
                        <input type="radio" name="maptype"
                               id="maptype-osm-bright" <?php echo $maptype === 'osm-bright' ? 'checked' : ''; ?>
                               value="osm-bright">
                    </div>
                </div>

            </div>

            <div class="neshan_locker"></div>
        </form>
    </div>
</div>