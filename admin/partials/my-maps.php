<?php

/**
 * Provide admin my maps view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://platform.neshan.org
 * @since      1.0.0
 *
 * @package    Neshan_Maps
 * @subpackage Neshan_Maps/admin/partials
 */

?>

<div class="wrap" id="neshan_my_maps_wrapper">
    <h1 class="wp-heading-inline">
		<?php _e( 'My Maps', 'neshan-maps' ); ?>
    </h1>

    <a href="<?php echo admin_url( 'admin.php?page=neshan_maps_create' ); ?>" class="page-title-action">
		<?php _e( 'Add New', 'neshan-maps' ); ?>
    </a>

    <hr class="wp-header-end">

    <form id="posts-filter" method="get">
        <h2 class="screen-reader-text">Posts list</h2>
        <table class="wp-list-table widefat fixed striped posts">
            <thead>
            <tr>
                <th scope="col" id="title" class="manage-column column-title column-primary">
					<?php _e( 'Title' ); ?>
                </th>
                <th scope="col" id="shortcode" class="manage-column column-shortcode">
					<?php _e( 'Shortcode' ); ?>
                </th>
                <th scope="col" id="date" class="manage-column column-date">
					<?php _e( 'Date' ); ?>
                </th>
            </tr>
            </thead>

            <tbody id="the-list">
            <?php foreach ( $maps as $map ): ?>
            <tr id="neshan-map-<?php echo $map->id; ?>" class="iedit level-0">
                <td class="title column-title has-row-actions column-primary" data-colname="Title">
                    <strong>
                        <a class="row-title"
                           href="<?php echo admin_url( 'admin.php?page=neshan_maps_create' ); ?>&amp;id=<?php echo $map->id; ?>&amp;action=edit"
                           aria-label="“<?php echo $map->title; ?>” (<?php _e( 'Edit' ); ?>)">
	                        <?php echo $map->title; ?>
                        </a>
                    </strong>

                    <div class="row-actions">
                        <span class="edit"><a
                                    href="<?php echo admin_url( 'admin.php?page=neshan_maps_create' ); ?>&amp;id=<?php echo $map->id; ?>&amp;action=edit"
                                    aria-label="<?php _e( 'Edit' ); ?> “<?php echo $map->title; ?>”"><?php _e( 'Edit' ); ?></a> | </span>
                        <span class="trash"><a
                                    href="javascript:;" rel="<?php echo wp_create_nonce( 'delete_neshan_map_'.$map->id ); ?>"
                                    rev="<?php echo $map->id; ?>"
                                    class="" aria-label="<?php _e( 'Trash' ); ?> “<?php echo $map->title; ?>”"><?php _e( 'Trash' ); ?></a></span>
                    </div>
                </td>
                <td class="shortcode column-shortcode"><pre>[neshan-map id="<?php echo $map->id; ?>"]</pre></td>
                <td class="date column-date"><?php _e( 'Published' ); ?><br><abbr><?php echo $map->updated_at; ?></abbr></td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </form>
</div>
