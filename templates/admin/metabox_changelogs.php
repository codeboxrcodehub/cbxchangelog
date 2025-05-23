<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://codeboxr.com
 * @since      1.0.0
 *
 * @package    Cbxchangelog
 * @subpackage Cbxchangelog/templates/admin
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

echo '<div class="cbx-chota cbxchangelog-page-wrapper cbxchangelog-edit-wrapper">';

//ready made templates
echo '<!-- mustache template -->
<script id="release_template" type="x-tmpl-mustache">
	<div class="cbxchangelog_release" data-boxincrement="{{increment}}">
	    <input type="hidden" name="cbxchangelog_logs[{{increment}}][id]" value="0"  />
	    <div class="release-toolbar">
	        <div class="release-toolbar-left">
                <span data-balloon-pos="up" aria-label="' . esc_attr__( 'Drag and drop for sort', 'cbxchangelog' ) . '" class="cbx-icon cbx-icon-move-white move-release" role="button" title="' . esc_html__( 'Sort Releases', 'cbxchangelog' ) . '"></span>
                <span data-balloon-pos="up" aria-label="' . esc_attr__( 'Release ID/No', 'cbxchangelog' ) . '" class="release_no">' . esc_html__( 'Release No', 'cbxchangelog' ) . '#{{incrementplus}}</span>
                <input required class="cbxchangelog_input cbxchangelog_input_text cbxchangelog_input_version"  type="text" name="cbxchangelog_logs[{{increment}}][version]" value="" placeholder="' . esc_html__( 'Version', 'cbxchangelog' ) . '" />
                <input class="cbxchangelog_input cbxchangelog_input_text cbxchangelog_input_date cbxchangelog_datepicker" autocomplete="new-password" type="text" name="cbxchangelog_logs[{{increment}}][date]" value="" placeholder="' . esc_html__( 'Release Date', 'cbxchangelog' ) . '" />
	        </div>            
            <div class="release-toolbar-right">			
                <span data-id="0" data-balloon-pos="up" aria-label="' . esc_attr__( 'Click to delete', 'cbxchangelog' ) . '" class="cbx-icon cbx-icon-delete-white trash-release" role="button"  title="' . esc_html__( 'Delete Release', 'cbxchangelog' ) . '"></span>
                <span data-balloon-pos="up" aria-label="' . esc_attr__( 'Click to show/hide', 'cbxchangelog' ) . '" class="cbx-icon cbx-icon-minus-white toggle-release" role="button"   title="' . esc_attr__( 'Show/hide', 'cbxchangelog' ) . '"></span>
            </div>
	    </div>	    
		<div class="release-content">
		    <div class="release-note">
                <p class="release-note-markdown">' . esc_html__( 'Release Note(Supports markdown syntax, if enabled in settings)', 'cbxchangelog' ) . '</p>
                <textarea  placeholder="' . esc_html__( 'Brief release note(Supports markdown syntax, if enabled in settings)', 'cbxchangelog' ) . '" class="cbxchangelog_input cbxchangelog_input_textarea cbxchangelog_input_note large-text" name="cbxchangelog_logs[{{increment}}][note]"></textarea>            
            </div>
            <p class="release-label">' . esc_html__( 'Release Url', 'cbxchangelog' ) . '</p>
            <div class="release-url">
                <input class="cbxchangelog_input cbxchangelog_input_url cbxchangelog_input_url"  type="url" name="cbxchangelog_logs[{{increment}}][url]" value="" placeholder="' . esc_html__( 'External url', 'cbxchangelog' ) . '" />
                	
            </div>
            <p class="release-label">' . esc_html__( 'New Features/Changes', 'cbxchangelog' ) . '</p>
            <div class="release-feature-wrap" data-boxincrement="{{increment}}">
                <div class="feature" data-boxincrement="{{increment}}">
                    <div class="feature_main">
                        <input required  class="regular-text"  type="text" name="cbxchangelog_logs[{{increment}}][feature][]" value="" placeholder="' . esc_html__( 'Write a new feature for this release', 'cbxchangelog' ) . '" />
                        <select class="regular-text" name="cbxchangelog_logs[{{increment}}][label][]">';
                            $label_options = CbxchangelogHelper::cbxchangelog_labels();
                            foreach ( $label_options as $label_key => $label_name ) {
                                echo sprintf( '<option value="%s" >%s</option>',
                                    esc_attr( $label_key ), esc_attr( $label_name ) );

                            }
                            echo '</select>';

                            $log_edit_feature_action_buttons = '';
                            $log_edit_feature_action_buttons .= '<a data-balloon-pos="up"  aria-label="' . esc_attr__( 'Drag and drop for sort', 'cbxchangelog' ) . '" href="#" title="' . esc_html__( 'Sort Features', 'cbxchangelog' ) . '"  class="cbx-icon cbx-icon-move move-feature"></a>';
                            $log_edit_feature_action_buttons .= '<a data-balloon-pos="up" aria-label="' . esc_attr__( 'Click to delete', 'cbxchangelog' ) . '" href="#" title="' . esc_html__( 'Delete Feature', 'cbxchangelog' ) . '"  class="cbx-icon cbx-icon-minus trash-feature"></a>';
                            $log_edit_feature_action_buttons .= '<a data-balloon-pos="up" aria-label="' . esc_attr__( 'Click to add new', 'cbxchangelog' ) . '" href="#" title="' . esc_html__( 'Add new feature', 'cbxchangelog' ) . '"  class="cbx-icon cbx-icon-plus add-feature"></a>';
                            
                            echo apply_filters( 'cbxchangelog_log_edit_feature_action_buttons', $log_edit_feature_action_buttons ); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                    echo '</div>'; //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

                    do_action('cbxchangelog_log_edit_js_feature_inside_end');
                    
                echo '</div>
            </div>
            <div class="clearfix"></div>
		</div>
	</div>
</script>';
//phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
echo '<!-- mustache template -->
<script id="feature_template" type="x-tmpl-mustache">
	<div class="feature" data-boxincrement="{{increment}}">
        <div class="feature_main">
            <input  class="regular-text" type="text" name="cbxchangelog_logs[{{increment}}][feature][]" value="" placeholder="' . esc_html__( 'Write a new feature for this release', 'cbxchangelog' ) . '">
            <select class="regular-text" name="cbxchangelog_logs[{{increment}}][label][]">
            ';
            $label_options = CbxchangelogHelper::cbxchangelog_labels();
            foreach ( $label_options as $label_key => $label_name ) {
                echo sprintf( '<option value="%s" >%s</option>',
                    esc_attr( $label_key ), esc_attr( $label_name ) );

            }
            echo '</select>'; //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

            $log_edit_feature_action_buttons = '';
            $log_edit_feature_action_buttons .= '<a data-balloon-pos="up"  aria-label="' . esc_attr__( 'Drag and drop for sort', 'cbxchangelog' ) . '" href="#" title="' . esc_html__( 'Sort Features', 'cbxchangelog' ) . '"  class="cbx-icon cbx-icon-move move-feature"></a>';
            $log_edit_feature_action_buttons .= '<a data-balloon-pos="up" aria-label="' . esc_attr__( 'Click to delete', 'cbxchangelog' ) . '" href="#" title="' . esc_html__( 'Delete Feature', 'cbxchangelog' ) . '"  class="cbx-icon cbx-icon-minus trash-feature"></a>';
            $log_edit_feature_action_buttons .= '<a data-balloon-pos="up" aria-label="' . esc_attr__( 'Click to add new', 'cbxchangelog' ) . '" href="#" title="' . esc_html__( 'Add new feature', 'cbxchangelog' ) . '"  class="cbx-icon cbx-icon-plus add-feature"></a>';
            
            echo apply_filters( 'cbxchangelog_log_edit_feature_action_buttons', $log_edit_feature_action_buttons); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        echo '</div>'; //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        do_action('cbxchangelog_log_edit_js_feature_inside_end');
    //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped               
    echo '</div>
</script>
';

?>
<?php


$meta_extra = get_post_meta( $post_id, '_cbxchangelog_extra', true );


if ( ! is_array( $meta_extra ) ) {
	$meta_extra = [];
}


$meta_extra['show_url']      = $show_url = isset( $meta_extra['show_url'] ) ? absint( $meta_extra['show_url'] ) : 1;
$meta_extra['group_label']      = $group_label = isset( $meta_extra['group_label'] ) ? absint( $meta_extra['group_label'] ) : 0;
$meta_extra['show_label']    = $show_label = isset( $meta_extra['show_label'] ) ? absint( $meta_extra['show_label'] ) : 1;
$meta_extra['show_date']     = $show_date = isset( $meta_extra['show_date'] ) ? absint( $meta_extra['show_date'] ) : 1;
$meta_extra['relative_date'] = $relative_date = isset( $meta_extra['relative_date'] ) ? absint( $meta_extra['relative_date'] ) : 0;
$meta_extra['layout']        = $layout = isset( $meta_extra['layout'] ) ? sanitize_text_field( wp_unslash( $meta_extra['layout'] ) ) : 'prepros';
$meta_extra['order']         = $order = isset( $meta_extra['order'] ) ? sanitize_text_field( wp_unslash( $meta_extra['order'] ) ) : 'desc';
$meta_extra['orderby']       = $order_by = isset( $meta_extra['orderby'] ) ? sanitize_text_field( wp_unslash( $meta_extra['orderby'] ) ) : 'order';
$meta_extra['count']         = $count = isset( $meta_extra['count'] ) ? absint( $meta_extra['count'] ) : 0;

$more_v_svg = cbxchangelog_load_svg( 'icon_more_v_white' );
?>

<?php do_action( 'cbxchangelog_before_meta_display', $post ); ?>
    <p style="color: #fff;"><?php esc_html_e( 'Note: Following general setting are applied in frontend.', 'cbxchangelog' ); ?></p>
    <div id="cbxchangelog_toolbar">
        <label for="show_label" class="show_label_wrap">
			<?php esc_html_e( 'Show Label', 'cbxchangelog' ); ?>
            <select name="cbxchangelog_extra[show_label]" id="show_label">
                <option value="1" <?php selected( $show_label, 1 ); ?> ><?php esc_html_e( 'Yes', 'cbxchangelog' ); ?></option>
                <option value="0" <?php selected( $show_label, 0 ); ?> ><?php esc_html_e( 'No', 'cbxchangelog' ); ?></option>
            </select> </label>

        <label for="show_date" class="show_date_wrap">
			<?php esc_html_e( 'Show Date', 'cbxchangelog' ); ?>
            <select name="cbxchangelog_extra[show_date]" id="show_date">
                <option value="1" <?php selected( $show_date, 1 ); ?> ><?php esc_html_e( 'Yes', 'cbxchangelog' ); ?></option>
                <option value="0" <?php selected( $show_date, 0 ); ?> ><?php esc_html_e( 'No', 'cbxchangelog' ); ?></option>
            </select> </label>
        <label for="relative_date" class="relative_date_wrap">
			<?php esc_html_e( 'Show Relative date', 'cbxchangelog' ); ?>
            <select name="cbxchangelog_extra[relative_date]" id="relative_date">
                <option value="1" <?php selected( $relative_date, 1 ); ?> ><?php esc_html_e( 'Yes', 'cbxchangelog' ); ?></option>
                <option value="0" <?php selected( $relative_date, 0 ); ?> ><?php esc_html_e( 'No', 'cbxchangelog' ); ?></option>
            </select> </label>
        <label for="show_url" class="show_url_wrap">
			<?php esc_html_e( 'Show Url', 'cbxchangelog' ); ?>
            <select name="cbxchangelog_extra[show_url]" id="show_url">
                <option value="1" <?php selected( $show_url, 1 ); ?> ><?php esc_html_e( 'Yes', 'cbxchangelog' ); ?></option>
                <option value="0" <?php selected( $show_url, 0 ); ?> ><?php esc_html_e( 'No', 'cbxchangelog' ); ?></option>
            </select>
        </label>

        <label for="group_label" class="group_label_wrap">
		    <?php esc_html_e( 'Group Label', 'cbxchangelog' ); ?>
            <select name="cbxchangelog_extra[group_label]" id="group_label">
                <option value="1" <?php selected( $group_label, 1 ); ?> ><?php esc_html_e( 'Yes', 'cbxchangelog' ); ?></option>
                <option value="0" <?php selected( $group_label, 0 ); ?> ><?php esc_html_e( 'No', 'cbxchangelog' ); ?></option>
            </select>
        </label>

        <label for="layout" class="layout_wrap">
			<?php esc_html_e( 'Choose layout', 'cbxchangelog' ); ?>
            <select name="cbxchangelog_extra[layout]" id="layout">
				<?php
				$layout_options = CbxchangelogHelper::get_layouts();
				foreach ( $layout_options as $layout_key => $layout_name ) {
					echo sprintf( '<option value="%s" ' . selected( $layout, $layout_key, false ) . ' >%s</option>',
						esc_attr( $layout_key ), esc_attr( $layout_name ) );
				}
				?>
            </select>
        </label>
        <label for="sort_orderby" class="orderby_wrap">
			<?php esc_html_e( 'Order By', 'cbxchangelog' ); ?>
            <select name="cbxchangelog_extra[orderby]" id="sort_orderby">
                <option value="order" <?php selected( $order_by, 'order' ); ?> ><?php esc_html_e( 'Default(Index)', 'cbxchangelog' ); ?></option>
                <option value="id" <?php selected( $order_by, 'id' ); ?> ><?php esc_html_e( 'Release No/ID', 'cbxchangelog' ); ?></option>
                <option value="date" <?php selected( $order_by, 'date' ); ?> ><?php esc_html_e( 'Date', 'cbxchangelog' ); ?></option>
            </select>
        </label>
        <label for="sort_order" class="order_wrap">
			<?php esc_html_e( 'Order', 'cbxchangelog' ); ?>
            <select name="cbxchangelog_extra[order]" id="sort_order">
                <option value="desc" <?php selected( $order, 'desc' ); ?> ><?php esc_html_e( 'Descending', 'cbxchangelog' ); ?></option>
                <option value="asc" <?php selected( $order, 'asc' ); ?> ><?php esc_html_e( 'Ascending', 'cbxchangelog' ); ?></option>
            </select>
        </label>
        <label for="count" class="show_count">
			<?php esc_html_e( 'Count(0 = all)', 'cbxchangelog' ); ?>
            <input id="count" type="number" min="0" step="1" name="cbxchangelog_extra[count]"
                   value="<?php echo absint( $count ); ?>"/>
        </label>
        <?php do_action( 'cbxchangelog_meta_toolbar_extra', $meta_extra ); ?>
    </div>
    <div class="clear clearfix"></div>

<?php
$meta_data = CBXChangelogHelper::get_changelog_data( $post_id );
$meta      = $meta_data->getAll();
$nextIndex = $counter = $meta_data->getNextIndex();
?>

    <div id="cbxchangelog_toolbar_extras">
        <div class="cbxchangelog_toolbar_extra cbxchangelog_toolbar_extra_l">
            <a href="#" data-position="bottom"
               class="button primary cbxchangelog_add_release"><?php esc_html_e( 'Add New Release(Bottom)', 'cbxchangelog' ); ?></a>
            <a href="#" data-position="top"
               class="button primary cbxchangelog_add_release"><?php esc_html_e( 'Add New Release(Top)', 'cbxchangelog' ); ?></a>
        </div>
        <div class="cbxchangelog_toolbar_extra cbxchangelog_toolbar_extra_r">
            <details class="dropdown dropdown-menu ml-10">
                <summary class="button icon icon-only outline primary icon-inline"><i
                            class="cbx-icon"><?php echo $more_v_svg; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></i>
                </summary>
                <div class="card card-menu card-menu-right">
                    <ul>
                        <li>
                            <a href="#" data-post-id="<?php echo absint( $post_id ); ?>" data-dir="1"
                               class="button primary cbxchangelog_resync_releases ld-ext-right"
                               data-balloon-pos="up"
                               aria-label="<?php esc_attr_e( 'From top: First row will get id 1, 2nd row will get id 2 ...', 'cbxchangelog' ); ?>"><?php esc_html_e( 'Re-sync release no/ID with index(top to bottom)', 'cbxchangelog' ); ?>
                                <span class="ld ld-spin ld-ring"></span></a>

                        </li>
                        <li><a href="#" data-post-id="<?php echo absint( $post_id ); ?>" data-dir="0"
                               class="button primary cbxchangelog_resync_releases ld-ext-right"
                               data-balloon-pos="up"
                               aria-label="<?php esc_attr_e( 'From bottom: LAst row will get id 1, 2nd from last will get id 2 ...', 'cbxchangelog' ); ?>"><?php esc_html_e( 'Re-sync release no/ID with index(bottom to top)', 'cbxchangelog' ); ?>
                                <span class="ld ld-spin ld-ring"></span>
                            </a>
                        </li>
                        <li>
                            <a href="#" data-post-id="<?php echo absint( $post_id ); ?>"
                               class="button primary cbxchangelog_delete_releases ld-ext-right"
                               data-balloon-pos="up"
                               aria-label="<?php esc_attr_e( 'Delete all releases. This process can not be undone. When done, this window will be reload.', 'cbxchangelog' ); ?>"><?php esc_html_e( 'Delete all releases', 'cbxchangelog' ); ?>
                                <span class="ld ld-spin ld-ring"></span>
                            </a>
                        </li>
                    </ul>
                </div>
            </details>
        </div>
    </div>

    <p style="color: #fff;">
        <span><?php esc_html_e( 'Note: In dashboard releases are displayed as per index. Display index and release id/no is not same. Drag adn drop sorting changes index but id doesn\'t change.', 'cbxchangelog' ); ?></span>
    </p>


    <div id="cbxchangelog_wrapper" data-counter="<?php echo absint( $counter ); ?>">
		<?php
		if ( sizeof( $meta ) > 0 ) {

			$boxes = $meta;

			foreach ( $boxes as $index => $box ) {
				$id           = isset( $box['id'] ) ? absint( $box['id'] ) : 0; //note $id and $inde
				$version      = isset( $box['version'] ) ? sanitize_text_field( $box['version'] ) : '';
				$url          = isset( $box['url'] ) ? esc_url( $box['url'] ) : '';
				$date         = isset( $box['date'] ) ? sanitize_text_field( $box['date'] ) : '';
				$release_note = isset( $box['note'] ) ? sanitize_textarea_field( $box['note'] ) : '';

				$feature = isset( $box['feature'] ) ? wp_unslash( $box['feature'] ) : [];
				$label   = isset( $box['label'] ) ? wp_unslash( $box['label'] ) : [];
				

				$feature = ( ! is_array( $feature ) ) ? [] : array_filter( $feature );
				$label   = ( ! is_array( $label ) ) ? [] : array_filter( $label );
				

				echo '
				<div class="cbxchangelog_release" data-boxincrement="' . absint( $index ) . '">
				    <input type="hidden" name="cbxchangelog_logs[' . absint( $index ) . '][id]" value="' . absint( $id ) . '"  />
				    <div class="release-toolbar">
				        <div class="release-toolbar-left">
				            <span data-balloon-pos="up" aria-label="' . esc_attr__( 'Drag and drop for sort', 'cbxchangelog' ) . '" class="cbx-icon cbx-icon-move-white move-release" role="button" title="' . esc_html__( 'Sort Releases', 'cbxchangelog' ) . '"></span>
					        <span data-balloon-pos="up" aria-label="' . esc_attr__( 'Release ID/No', 'cbxchangelog' ) . '" title="' . esc_attr__( 'Release ID/No', 'cbxchangelog' ) . '" class="release_no">' . esc_html__( 'Release No', 'cbxchangelog' ) . '#' . absint( $id ) . '</span>
					        <input required class="cbxchangelog_input cbxchangelog_input_text cbxchangelog_input_version"   type="text" name="cbxchangelog_logs[' . absint( $index ) . '][version]" value="' . esc_attr( $version ) . '" placeholder="' . esc_html__( 'Version', 'cbxchangelog' ) . '" />
					        <input class="cbxchangelog_input cbxchangelog_input_text cbxchangelog_input_date cbxchangelog_datepicker" autocomplete="new-password"  type="text" name="cbxchangelog_logs[' . absint( $index ) . '][date]" value="' . esc_attr( $date ) . '" placeholder="' . esc_html__( 'Release Date', 'cbxchangelog' ) . '" />
                        </div>   				        
					    <div class="release-toolbar-right">
					        <span data-post-id="' . absint( $post_id ) . '" data-id="' . absint( $id ) . '" data-balloon-pos="up" aria-label="' . esc_attr__( 'Click to delete', 'cbxchangelog' ) . '"  class="cbx-icon cbx-icon-delete-white trash-release" role="button" title="' . esc_html__( 'Delete Release', 'cbxchangelog' ) . '"></span>
					        <span data-balloon-pos="up" aria-label="' . esc_attr__( 'Click to show/hide', 'cbxchangelog' ) . '" class="cbx-icon cbx-icon-plus-white toggle-release" role="button" title="' . esc_attr__( 'Show/hide', 'cbxchangelog' ) . '"></span>
                        </div>															    
                    </div>
                    <div class="release-content" style="display: none;">
                        <div class="release-note">
                            <p class="release-label release-note-markdown">' . esc_html__( 'Release Note(Supports markdown syntax, if enabled in settings)', 'cbxchangelog' ) . '</p>
                            <textarea  placeholder="' . esc_html__( 'Brief release note(Supports markdown syntax, if enabled in settings)', 'cbxchangelog' ) . '" class="cbxchangelog_input cbxchangelog_input_textarea cbxchangelog_input_note large-text" name="cbxchangelog_logs[' . absint( $index ) . '][note]">' . esc_textarea( $release_note ) . '</textarea>
                        </div>
                        <p class="release-label ">' . esc_html__( 'Release Url', 'cbxchangelog' ) . '</p>
                        <div class="release-url">
                            <input class="cbxchangelog_input cbxchangelog_input_url cbxchangelog_input_url"  type="url" name="cbxchangelog_logs[' . absint( $index ) . '][url]" value="' . esc_url( $url ) . '" placeholder="' . esc_html__( 'External url', 'cbxchangelog' ) . '" />                            	
                        </div>
                        <p class="release-label">' . esc_html__( 'New Features/Changes', 'cbxchangelog' ) . '</p>  
                        <div class="release-feature-wrap" data-boxincrement="' . absint( $index ) . '">';

				if ( sizeof( $feature ) > 0 ) {
					foreach ( $feature as $f_index => $single_feature ) {

						$single_feature = esc_html( $single_feature );
						$label_options  = CbxchangelogHelper::cbxchangelog_labels();
						echo '
                            <div class="feature" data-boxincrement="' . absint( $index ) . '">
                                <div class="feature_main">
                                    <input required class="regular-text"  type="text" name="cbxchangelog_logs[' . absint( $index ) . '][feature][]" value="' . esc_attr( $single_feature ) . '" placeholder="' . esc_html__( 'Write a new feature for this release', 'cbxchangelog' ) . '" />
                                    
                                    <select class="regular-text" name="cbxchangelog_logs[' . absint( $index ) . '][label][]">';
                                    foreach ( $label_options as $label_key => $label_name ) {
                                        $found_label = isset( $label[ $f_index ] ) ? $label[ $f_index ] : 'added';
                                        echo sprintf( '<option value="%s" ' . selected( $found_label, $label_key, false ) . ' >%s</option>',
                                            esc_attr( $label_key ), esc_attr( $label_name ) );

                                    }
                                    
                                    echo '</select>';


                                    $log_edit_feature_action_buttons = '';
                                    $log_edit_feature_action_buttons .= '<a data-balloon-pos="up"  aria-label="' . esc_attr__( 'Drag and drop for sort', 'cbxchangelog' ) . '" href="#" title="' . esc_html__( 'Sort Features', 'cbxchangelog' ) . '"  class="cbx-icon cbx-icon-move move-feature"></a>';
                                    $log_edit_feature_action_buttons .= '<a data-balloon-pos="up" aria-label="' . esc_attr__( 'Click to delete', 'cbxchangelog' ) . '" href="#" title="' . esc_html__( 'Delete Feature', 'cbxchangelog' ) . '"  class="cbx-icon cbx-icon-minus trash-feature"></a>';
                                    $log_edit_feature_action_buttons .= '<a data-balloon-pos="up" aria-label="' . esc_attr__( 'Click to add new', 'cbxchangelog' ) . '" href="#" title="' . esc_html__( 'Add new feature', 'cbxchangelog' ) . '"  class="cbx-icon cbx-icon-plus add-feature"></a>';
                                    
                                    echo apply_filters( 'cbxchangelog_log_edit_feature_action_buttons', $log_edit_feature_action_buttons, $index, $f_index );  //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                                echo '</div>';  //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

                                do_action('cbxchangelog_log_edit_feature_inside_end' , $index, $f_index , $box);
                            echo '</div>';
					}//end for loop
				}//end if

				echo '</div><div class="clearfix"></div>
                   </div>			    					
				</div>';

				//$j--;

				//$counter --;

				prev( $boxes ); // Move the pointer to the previous element
			}//end for loop
		}
		?>

    </div>

<?php do_action( 'cbxchangelog_after_meta_display', $post );
echo '</div>';