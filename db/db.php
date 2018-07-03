<?php

$table_name = $wpdb->prefix . "neshan_maps";

function neshan_maps_install()
{
    global $wpdb;
 
    /*$table_name = $wpdb->prefix . "neshan_maps";*/
    global $table_name;
    $usersTable = "CREATE TABLE IF NOT EXISTS $table_name
    (
        id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
        map_name TEXT,
        map_date DATETIME NOT NULL,
        map_data VARCHAR(255) NOT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
 
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($usersTable);
}
 
function pluginUninstall()
{
    global $wpdb;
    /*$table_name = $wpdb->prefix . "neshan_maps";*/
    global $table_name;
    $wpdb->query("DROP TABLE IF EXISTS $table_name");
}

/* Inset Data Maps In Dtabase WP */

function insert_data_in_dbneshan(){
    
$map_name = $_POST["map-name"];
$map_date = current_time( 'mysql', 0 );
$api_key = $_POST["key"];
$lat = $_POST["lat"];
$lng = $_POST["lng"];
$width = $_POST["width"];
$height = $_POST["height"];
$zoomlevel = $_POST["zoom"];
$maptype = $_POST["maptype"];
    
$map_items = array( array("api_key"=>"$api_key", "lat"=>"$lat", "lng"=>"$lng", "width"=>"$width", "height"=>"$height", "zoomlevel"=>"$zoomlevel", "maptype"=>"$maptype") );
    
$map_data = json_encode($map_items);

global $wpdb;
/*$table_name = $wpdb->prefix . "neshan_maps";*/
global $table_name;

$wpdb->query(
    $wpdb->prepare(
        "INSERT INTO $table_name( map_name, map_date, map_data ) VALUES ( %s, %s, %s )", $map_name, $map_date, $map_data
                  ) 
            );
    
$shortcode_id_map = '[neshan_map id="'.$wpdb->insert_id.'"]'; 
   echo '<p class="bg-success" style="color:#f5f5f5;padding: 10px 5px;"><span style="display: inline-block;">'.__('Map shortcode Use In Pages.', 'neshan').'</span><span style="display: inline-block;margin-right: 35%;direction: ltr;">'.$shortcode_id_map.'</span>';
$url = admin_url('admin.php?page=neshan_page_item');
wp_redirect($url); 
 ?>
 <script type="text/javascript">
    function go2NewUrl(newUrl, sec) {
        setTimeout("location.href='" + newUrl + "'", sec * 1000);
    }
    go2NewUrl('<?php $url = admin_url('admin.php?page=neshan_page_item'); echo $url; ?>', 0);
</script>
<?php 
}

/* Show All Maps Items In Dashboard */

function show_maps(){
global $wpdb;
/*$table_name = $wpdb->prefix . "neshan_maps";*/
global $table_name;
$show_maps = $wpdb->get_results(
    "SELECT * FROM $table_name ORDER BY map_date DESC"
);
	if( !empty( $show_maps ) ) :
 $num_style = 0;
		foreach( $show_maps as $show_map ) :; 
       if( $num_style%2 == 0 ){
           $style = 'background-color: #f9f9f9';
       }else{
           $style = '';
       }
    $time = explode("-", $show_map->map_date);
    $year = $time['0'];
    $month = $time['1'];
    $HMS = $time['2'];
    $xpld_HMS = explode(" ", $HMS);
    $day = $xpld_HMS['0'];
    $time_hms = $xpld_HMS['1'];
        ?>
        <tr id="map-<?php echo $show_map->id; ?>" style="<?php echo $style; ?>">
        <form method="post">
        <input id="id_item" name="id_item" value="<?php echo $show_map->id; ?>" type="hidden">
		<td class="title column-title has-row-actions column-primary page-title" data-colname="عنوان">
<strong>
<a class="row-title" href="<?php echo '?page=create_map_page&edit_item=update&id_item='.$show_map->id;?>"><?php echo $show_map->map_name; ?>
</a>
</strong>
<div class="row-actions">
            <span class="edit">
            <button id="edit_item" name="edit_item" style="border:none;background:transparent;color:#124964;cursor: pointer;padding: 0;" type="submit"><?php _e('Edit Item', 'neshan'); ?></button> | 
            </span>
            <span class="trash">
            <button id="delete_item" name="delete_item" type="submit" style="border:none;background:transparent;color:#a00;cursor: pointer;padding: 0;" onclick="delete_map_js()"><?php _e('Delete Item', 'neshan');?></button>
            </span>
</div>
           <button type="button" class="toggle-row">
            <span class="screen-reader-text"><?php _e('Show More Detales', 'neshan'); ?></span>
            </button>
</td>
				<td><?php $shortcode_id_map = '[neshan_map id="'.$show_map->id.'"]'; echo '<textarea style="width:100%;height:30px;">'.$shortcode_id_map.'</textarea>'; ?></td>
				<td><?php echo $year.'-'.$month.'-'.$day; echo '<br>'. mc_gregorian_to_jalali($year,$month,$day, '-').'<br>'.$time_hms; ?></td>
				</form>
			</tr>
			<?php
        $num_style ++;
		endforeach;
	else : ?>
		<tr>
			<td colspan="3"><?php _e('No data found', 'neshan'); ?></td>
		</tr>
		<?php 
	endif; 

}

/* Delete Item */

function delete_map_item(){
    $id_item = $_POST['id_item'];
    global $wpdb;
    /*$table_name = $wpdb->prefix . "neshan_maps";*/
    global $table_name;
    $wpdb->delete( $table_name, array( 'id' => $id_item ), array( '%d' ) );
}

/* Edit Item */

function update_map_items(){

$id_item = $_POST['map_id'];
$map_date = current_time( 'mysql', 0 );
if( !empty( $_POST["map-name"] ) ){
    $map_name = $_POST["map-name"];
}else{
    echo '<p class="bg-danger">'.__('No Set Map Name', 'neshan').'</p>';
}
if( !empty( $_POST["key"] ) ){
    $api_key = $_POST["key"];
}else{
    echo '<p class="bg-danger">'.__('No Set API Key', 'neshan').'</p>';
}
if( !empty( $_POST["lat"] ) ){
    $lat = $_POST["lat"];
}else{
    echo '<p class="bg-danger">'.__('No Set LAT', 'neshan').'</p>';
}
if( !empty( $_POST["lng"] ) ){
    $lng = $_POST["lng"];
}else{
    echo '<p class="bg-danger">'.__('No Set LNG', 'neshan').'</p>';
}
if( !empty( $_POST["width"] ) ){
    $width = $_POST["width"];
}else{
    echo '<p class="bg-danger">'.__('No Set Width', 'neshan').'</p>';
}
if( !empty( $_POST["height"] ) ){
    $height = $_POST["height"];
}else{
    echo '<p class="bg-danger">'.__('No Set Height', 'neshan').'</p>';
}
if( !empty( $_POST["zoom"] ) ){
    $zoomlevel = $_POST["zoom"];
}else{
    echo '<p class="bg-danger">'.__('No Set ZoomLevel', 'neshan').'</p>';
}
if( !empty( $_POST["maptype"] ) ){
    $maptype = $_POST["maptype"];
}else{
    echo '<p class="bg-danger">'.__('No Set MapType', 'neshan').'</p>';
}
 
$map_items = array( array("api_key"=>"$api_key", "lat"=>"$lat", "lng"=>"$lng", "width"=>"$width", "height"=>"$height", "zoomlevel"=>"$zoomlevel", "maptype"=>"$maptype") );
    
$map_data = json_encode($map_items);
    
    global $wpdb;
    $table_name = $wpdb->prefix . "neshan_maps";
    
    $wpdb->update(
    $table_name,
    array(
        'map_name' => $map_name,
        'map_date' => $map_date,
        'map_data' => $map_data   
    ),
    array( 'id' => $id_item ),
    array(
        '%s',
        '%s',
        '%s'   
    ),
    array( '%d' )
);
$url = admin_url('admin.php?page=neshan_page_item');
wp_redirect($url); 
 ?>
 <script type="text/javascript">
    function go2NewUrl(newUrl, sec) {
        setTimeout("location.href='" + newUrl + "'", sec * 1000);
    }
    go2NewUrl('<?php $url = admin_url('admin.php?page=neshan_page_item'); echo $url; ?>', 0);
</script>
<?php   
}

/* convert gtoj date */

function mc_gregorian_to_jalali($gy,$gm,$gd,$mod=''){
 $g_d_m=array(0,31,59,90,120,151,181,212,243,273,304,334);
 if($gy>1600){
  $jy=979;
  $gy-=1600;
 }else{
  $jy=0;
  $gy-=621;
 }
 $gy2=($gm>2)?($gy+1):$gy;
 $days=(365*$gy) +((int)(($gy2+3)/4)) -((int)(($gy2+99)/100)) +((int)(($gy2+399)/400)) -80 +$gd +$g_d_m[$gm-1];
 $jy+=33*((int)($days/12053)); 
 $days%=12053;
 $jy+=4*((int)($days/1461));
 $days%=1461;
 if($days > 365){
  $jy+=(int)(($days-1)/365);
  $days=($days-1)%365;
 }
 $jm=($days < 186)?1+(int)($days/31):7+(int)(($days-186)/30);
 $jd=1+(($days < 186)?($days%31):(($days-186)%30));
 return($mod=='')?array($jy,$jm,$jd):$jy.$mod.$jm.$mod.$jd;
}

?>
