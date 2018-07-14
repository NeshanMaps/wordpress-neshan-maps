<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       https://platform.neshan.org
 * @since      1.0.0
 *
 * @package    Neshan_Maps
 * @subpackage Neshan_Maps/public/partials
 */
?>

<?php echo $output; ?>

<script type="text/javascript">
    var neshanMap<?php echo $id ?>;

    function initNeshanMap <?php echo $id ?>() {
        neshanMap<?php echo $id ?> = new ol.Map({
            target: 'neshan_map_<?php echo $id; ?>',
            maptype: '<?php echo $data->maptype; ?>',
            key: '<?php echo $data->api_key; ?>',
            view: new ol.View({
                center: ol.proj.fromLonLat([<?php echo $data->lng; ?>, <?php echo $data->lat; ?>]),
                zoom: <?php echo $data->zoom; ?>,
                minZoom: 5,
                maxZoom: 19,
                extent: [4891969.8103, 2856910.3692, 7051774.4815, 4847942.0820]
            })
        });
    }
</script>