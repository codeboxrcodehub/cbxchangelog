<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

do_action( 'cbxchangelog_form_admin_start', $instance, $this );
?>
    <!-- Custom  Title Field -->
    <p>
        <label for="<?php echo esc_attr($this->get_field_id( 'title' )); ?>"><?php esc_html_e( 'Title', "cbxchangelog" ); ?></label>
        <input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'title' )); ?>"
               name="<?php echo esc_attr($this->get_field_name( 'title' )); ?>" type="text" value="<?php echo esc_attr($title); ?>"/>
    </p>

    <p>
        <label for="<?php echo esc_attr($this->get_field_id( 'post_id' )); ?>"><?php esc_html_e( 'Set Post ID(Change log post type or other supported)', "cbxchangelog" ); ?></label>
        <input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'post_id' )); ?>"
               name="<?php echo esc_attr($this->get_field_name( 'post_id' )); ?>" type="text"
               value="<?php echo absint( $post_id ); ?>"/>
    </p>
    <p>
        <label for="<?php echo esc_attr($this->get_field_id( 'release' )); ?>"><?php esc_html_e( 'Display Specific Release\'s Changelog', "cbxchangelog" ); ?></label>
        <input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'release' )); ?>"
               name="<?php echo esc_attr($this->get_field_name( 'release' )); ?>" type="text"
               value="<?php echo absint( $release ); ?>"/>
    </p>
    <p>
        <label for="<?php echo esc_attr($this->get_field_id( 'show_label' )); ?>"><?php esc_html_e( 'Show Label', "cbxchangelog" ); ?></label>
        <select class="widefat" name="<?php echo esc_attr($this->get_field_name( 'show_label' )); ?>"
                id="<?php echo esc_attr($this->get_field_id( 'show_label' )); ?>">
            <option value="" <?php selected( '', $show_label ); ?> ><?php esc_html_e( 'Choose from post meta', 'cbxchangelog' ); ?></option>
            <option value="1" <?php selected( 1, $show_label ); ?> ><?php esc_html_e( 'Yes', 'cbxchangelog' ); ?></option>
            <option value="0" <?php selected( 0, $show_label ); ?> ><?php esc_html_e( 'No', 'cbxchangelog' ); ?></option>
        </select>
    </p>
    <p>
        <label for="<?php echo esc_attr($this->get_field_id( 'show_date' )); ?>"><?php esc_html_e( 'Show Date', "cbxchangelog" ); ?></label>
        <select class="widefat" name="<?php echo esc_attr($this->get_field_name( 'show_date' )); ?>"
                id="<?php echo esc_attr($this->get_field_id( 'show_date' )); ?>">
            <option value="" <?php selected( '', $show_date ); ?> ><?php esc_html_e( 'Choose from post meta', 'cbxchangelog' ); ?></option>
            <option value="1" <?php selected( 1, $show_date ); ?> ><?php esc_html_e( 'Yes', 'cbxchangelog' ); ?></option>
            <option value="0" <?php selected( 0, $show_date ); ?> ><?php esc_html_e( 'No', 'cbxchangelog' ); ?></option>
        </select>
    </p>

    <p>
        <label for="<?php echo esc_attr($this->get_field_id( 'relative_date' )); ?>"><?php esc_html_e( 'Show Relative date', "cbxchangelog" ); ?></label>
        <select class="widefat" name="<?php echo esc_attr($this->get_field_name( 'relative_date' )); ?>"
                id="<?php echo esc_attr($this->get_field_id( 'relative_date' )); ?>">
            <option value="" <?php selected( '', $relative_date ); ?> ><?php esc_html_e( 'Choose from post meta', 'cbxchangelog' ); ?></option>
            <option value="1" <?php selected( 1, $relative_date ); ?> ><?php esc_html_e( 'Yes', 'cbxchangelog' ); ?></option>
            <option value="0" <?php selected( 0, $relative_date ); ?> ><?php esc_html_e( 'No', 'cbxchangelog' ); ?></option>
        </select>
    </p>
    <p>
        <label for="<?php echo esc_attr($this->get_field_id( 'show_url' )); ?>"><?php esc_html_e( 'Show Url', "cbxchangelog" ); ?></label>
        <select class="widefat" name="<?php echo esc_attr($this->get_field_name( 'show_url' )); ?>"
                id="<?php echo esc_attr($this->get_field_id( 'show_url' )); ?>">
            <option value="" <?php selected( '', $show_url ); ?> ><?php esc_html_e( 'Choose from post meta', 'cbxchangelog' ); ?></option>
            <option value="1" <?php selected( 1, $show_url ); ?> ><?php esc_html_e( 'Yes', 'cbxchangelog' ); ?></option>
            <option value="0" <?php selected( 0, $show_url ); ?> ><?php esc_html_e( 'No', 'cbxchangelog' ); ?></option>
        </select>
    </p>
    <p>
        <label for="<?php echo esc_attr($this->get_field_id( 'group_label' )); ?>"><?php esc_html_e( 'Group Label', "cbxchangelog" ); ?></label>
        <select class="widefat" name="<?php echo esc_attr($this->get_field_name( 'group_label' )); ?>"
                id="<?php echo esc_attr($this->get_field_id( 'group_label' )); ?>">
            <option value="" <?php selected( '', $group_label ); ?> ><?php esc_html_e( 'Choose from post meta', 'cbxchangelog' ); ?></option>
            <option value="1" <?php selected( 1, $group_label ); ?> ><?php esc_html_e( 'Yes', 'cbxchangelog' ); ?></option>
            <option value="0" <?php selected( 0, $group_label ); ?> ><?php esc_html_e( 'No', 'cbxchangelog' ); ?></option>
        </select>
    </p>
    <p>
        <label for="<?php echo esc_attr($this->get_field_id( 'layout' )); ?>"><?php esc_html_e( 'Choose layout', "cbxchangelog" ); ?></label>
        <select class="widefat" name="<?php echo esc_attr($this->get_field_name( 'layout' )); ?>"
                id="<?php echo esc_attr($this->get_field_id( 'layout' )); ?>">
            <option value="" <?php selected( '', $layout ); ?> ><?php esc_html_e( 'Choose from post meta', 'cbxchangelog' ); ?></option>
			<?php
			$layout_options = CbxchangelogHelper::get_layouts();
			foreach ( $layout_options as $layout_key => $layout_name ) {
				echo sprintf( '<option value="%s" ' . selected( $layout, $layout_key, false ) . ' >%s</option>',
					esc_attr( $layout_key ), esc_attr( $layout_name ) );

			}
			?>
        </select>
    </p>
    <p>
        <label for="<?php echo esc_attr($this->get_field_id( 'orderby' )); ?>"><?php esc_html_e( 'Order By', "cbxchangelog" ); ?></label>
        <select class="widefat" name="<?php echo esc_attr($this->get_field_name( 'orderby' )); ?>"
                id="<?php echo esc_attr($this->get_field_id( 'orderby' )); ?>">
            <option value="" <?php selected( '', $orderby ); ?> ><?php esc_html_e( 'Choose from post meta', 'cbxchangelog' ); ?></option>
            <option value="default" <?php selected( 'default', $orderby ); ?> ><?php esc_html_e( 'Default', 'cbxchangelog' ); ?></option>
            <option value="id" <?php selected( 'id', $orderby ); ?> ><?php esc_html_e( 'Release No/ID', 'cbxchangelog' ); ?></option>
            <option value="date" <?php selected( 'date', $orderby ); ?> ><?php esc_html_e( 'Date', 'cbxchangelog' ); ?></option>
        </select>
    </p>
    <p>
        <label for="<?php echo esc_attr($this->get_field_id( 'order' )); ?>"><?php esc_html_e( 'Order', "cbxchangelog" ); ?></label>
        <select class="widefat" name="<?php echo esc_attr($this->get_field_name( 'order' )); ?>"
                id="<?php echo esc_attr($this->get_field_id( 'order' )); ?>">
            <option value="" <?php selected( '', $order ); ?> ><?php esc_html_e( 'Choose from post meta', 'cbxchangelog' ); ?></option>
            <option value="desc" <?php selected( 'desc', $order ); ?> ><?php esc_html_e( 'Desc', 'cbxchangelog' ); ?></option>
            <option value="asc" <?php selected( 'asc', $order ); ?> ><?php esc_html_e( 'Asc', 'cbxchangelog' ); ?></option>
        </select>
    </p>
    <p>
        <label for="<?php echo esc_attr($this->get_field_id( 'count' )); ?>"><?php esc_html_e( 'Count(0 = all, -1 = Choose from post meta)', "cbxchangelog" ); ?></label>
        <input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'count' )); ?>"
               name="<?php echo esc_attr($this->get_field_name( 'count' )); ?>" type="number" step="1" min="-1" value="<?php echo intval($count); ?>"/>
    </p>
    <?php
        do_action( 'cbxchangelog_classic_widget_form_extend', $instance, $this );
    ?>

    <input type="hidden" id="<?php echo esc_attr($this->get_field_id( 'submit' )); ?>"
           name="<?php echo esc_attr($this->get_field_name( 'submit' )); ?>" value="1"/>
<?php
do_action( 'cbxchangelog_form_admin_end', $instance, $this );