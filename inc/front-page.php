<?php

/* short code custom */

function neshan_map_generate( $atts ) {
    $id= shortcode_atts( array(
        'id'   => $content['id']
    ), $atts );
    
    $id_map = $id['id'];

global $wpdb;
$table_name = $wpdb->prefix . "neshan_maps";
$show_maps = $wpdb->get_results(
    "SELECT * FROM $table_name WHERE id=$id_map"
);
    foreach ( $show_maps as $show_map )
{
    $encode = $show_map->map_data;
        $items_array = json_decode( $encode, true );
        foreach ($items_array as $key => $value) {
            ?>
<div id="map_neshan" style="width: <?php echo $value["width"]; ?>; height: <?php echo $value["height"]; ?>; background: #eee; border: 2px solid #aaa;"></div>
<script type="text/javascript">
    function initMyMap() {
        var myMap = new ol.Map({
            target: 'map_neshan',
            key: '<?php echo $value["api_key"]; ?>',
            view: new ol.View({
                center: ol.proj.fromLonLat([<?php echo $value["lng"]; ?>, <?php echo $value["lat"]; ?>]),
                zoom: <?php echo $value["zoomlevel"]; ?>
            })
        });
        myMap.setMapType('<?php echo $value["maptype"]; ?>');
    }
</script>
       <?php
        }
}
    
}
add_shortcode( 'neshan_map', 'neshan_map_generate' );
?>