<?php
/* Display Item Map Neshan In Dashboard Site */

function neshan_content_page_item(){
    echo '<div class="wrap">';
    _e('<h1 class="wp-heading-inline">All Map Item</h1>', 'neshan');
    echo '<a href="?page=create_map_page" class="page-title-action">'.__('Add Map', 'neshan').'</a>';
    echo '<hr class="wp-header-end">';
    ?>

<table class="wp-list-table widefat fixed posts">
	<thead>
		<tr>
			<th><?php _e('Map Name', 'neshan'); ?></th>
			<th><?php _e('Map Shortcode', 'neshan'); ?></th>
			<th><?php _e('Update Lasted', 'neshan'); ?></th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<th><?php _e('Map Name', 'neshan'); ?></th>
			<th><?php _e('Map Shortcode', 'neshan'); ?></th>
			<th><?php _e('Update Lasted', 'neshan'); ?></th>
		</tr>
	</tfoot>
	<tbody id="the-list">
<?php show_maps(); ?>	
	</tbody>
</table>

<?php 
    echo '</div>';
}

if( isset($_POST['delete_item']) && !empty($_POST['id_item']) ){
    delete_map_item($_POST['id_item']);
    echo 'alert("'.__('Delete Done', 'neshan').'");';
    }

if( isset($_POST['edit_item']) && !empty($_POST['id_item']) ){
    header ( 'location: ?page=create_map_page&edit_item=update&id_item='.$_POST['id_item'].'' );
}
?>
