<?php $roles_array = array();

foreach ( UM()->roles()->get_roles() as $key => $value ) {
	$_um_roles = UM()->query()->get_meta_value( '_um_roles', $key );
	if ( ! empty( $_um_roles ) ) {
		$roles_array[] = $_um_roles;
	}
}

$show_these_users = get_post_meta( get_the_ID(), '_um_show_these_users', true );
if ( $show_these_users ) {
	$show_these_users = implode( "\n", str_replace( "\r", "", $show_these_users ) );
}

$post_id = get_the_ID();
$_um_view_types = get_post_meta( $post_id, '_um_view_types', true );
$_um_view_types = empty( $_um_view_types ) ? array( 'grid' ) : $_um_view_types; ?>

<div class="um-admin-metabox">

	<?php $fields = array(
		array(
			'id'		=> '_um_mode',
			'type'		=> 'hidden',
			'value'		=> 'directory',
		),
		array(
			'id'		=> '_um_view_types',
			'type'		=> 'select',
			'multi'		=> true,
			'label'		=> __( 'View type(s)', 'ultimate-member' ),
			'tooltip'	=> __( 'View type a specific parameter in the directory', 'ultimate-member' ),
			'options'	=> array(
				'grid'      => __( 'Grid', 'ultimate-member' ),
				'list'      => __( 'List', 'ultimate-member' ),
			),
			'value'		=> $_um_view_types,
		),
		array(
			'id'		=> '_um_default_view',
			'type'		=> 'select',
			'label'		=> __( 'Default view type', 'ultimate-member' ),
			'tooltip'	=> __( 'Default directory view type', 'ultimate-member' ),
			'options'	=> array(
				'grid'      => __( 'Grid', 'ultimate-member' ),
				'list'      => __( 'List', 'ultimate-member' ),
			),
			'value'		=> UM()->query()->get_meta_value( '_um_default_view', null, '' ),
			'conditional' => array( '_um_view_types', 'length', 2 )
		),
		array(
			'id'		=> '_um_roles',
			'type'		=> 'select',
			'label'		=> __( 'User Roles to Display', 'ultimate-member' ),
			'tooltip'	=> __( 'If you do not want to show all members, select only user roles to appear in this directory', 'ultimate-member' ),
			'options'	=> UM()->roles()->get_roles(),
			'multi'		=> true,
			'value'		=> $roles_array,
		),
		array(
			'id'		=> '_um_has_profile_photo',
			'type'		=> 'checkbox',
			'label'		=> __( 'Only show members who have uploaded a profile photo', 'ultimate-member' ),
			'tooltip'	=> __( 'If \'Use Gravatars\' as profile photo is enabled, this option is ignored', 'ultimate-member' ),
			'value'		=> UM()->query()->get_meta_value( '_um_has_profile_photo' ),
		),
		array(
			'id'		=> '_um_has_cover_photo',
			'type'		=> 'checkbox',
			'label'		=> __( 'Only show members who have uploaded a cover photo', 'ultimate-member' ),
			'value'		=> UM()->query()->get_meta_value( '_um_has_cover_photo' ),
		),
		array(
			'id'		    => '_um_show_these_users',
			'type'		    => 'textarea',
			'label'		    => __( 'Only show specific users (Enter one username per line)', 'ultimate-member' ),
			'value'		    => $show_these_users,
		)
	);

	/**
	 * UM hook
	 *
	 * @type filter
	 * @title um_admin_extend_directory_options_general
	 * @description Extend Directory options fields
	 * @input_vars
	 * [{"var":"$fields","type":"array","desc":"Directory options fields"}]
	 * @change_log
	 * ["Since: 2.0"]
	 * @usage add_filter( 'um_admin_directory_sort_users_select', 'function_name', 10, 1 );
	 * @example
	 * <?php
	 * add_filter( 'um_admin_directory_sort_users_select', 'my_directory_sort_users_select', 10, 1 );
	 * function my_directory_sort_users_select( $sort_types ) {
	 *     // your code here
	 *     return $sort_types;
	 * }
	 * ?>
	 */
	$fields = apply_filters( 'um_admin_extend_directory_options_general', $fields );

	UM()->admin_forms( array(
		'class'		=> 'um-member-directory-general um-half-column',
		'prefix_id'	=> 'um_metadata',
		'fields' 	=> $fields
	) )->render_form(); ?>

	<div class="um-admin-clear"></div>

</div>