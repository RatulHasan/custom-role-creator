<?php
/***
 * CRC Project
 *
 * @since 1.0.0
 *
 * @author Ratul Hasan <tanjilhasanratul@gmail.com>
 *
 * @package CRC
 */

// To prevent direct access, if not define WordPress ABSOLUTE PATH then exit.
if ( ! defined( 'ABSPATH' ) ) {
    exit();
}
if ( isset( $_GET['saved'] ) ) {
    if ( 1 == $_GET['saved'] ) {
        echo '<div id="crc_settings_message" class="alert-success">
    <p><strong>' . esc_html__( 'Success! Data have been saved.', 'custom-role-creator' ) . '</strong></p>
  </div>';
    }
    if ( 0 == $_GET['saved'] ) {
        echo '<div id="crc_settings_message" class="alert-warning">
    <p><strong>' . esc_html__( 'Warning! Data not saved.', 'custom-role-creator' ) . '</strong></p>
  </div>';
    }
    if ( 2 == $_GET['saved'] ) {
        echo '<div id="crc_settings_message" class="alert-warning">
    <p><strong>' . esc_html__( 'Warning! Name of a role can\'t be empty.', 'custom-role-creator' ) . '</strong></p>
  </div>';
    }
}
if ( isset( $_GET['deleted'])) {
    if ( 3 == $_GET[ 'deleted' ] ) {
        echo '<div id="crc_settings_message" class="alert-success">
    <p><strong>' . esc_html__( 'Success! Data have been deleted.', 'custom-role-creator' ) . '</strong></p>
  </div>';
    }
    if ( 4 == $_GET[ 'deleted' ] ) {
        echo '<div id="crc_settings_message" class="alert-warning">
    <p><strong>' . esc_html__( 'Warning! This is not a valid role.', 'custom-role-creator' ) . '</strong></p>
  </div>';
    }
}
?>
<div class="wrap">
    <div class="box-header">
        <h1 class="wp-heading-inline"><?php esc_html_e( 'All Roles', 'custom-role-creator' ); ?></h1>
        <a href="#myRole" data-bs-toggle="modal" class="page-title-action"><?php esc_html_e( 'Add New Role', 'custom-role-creator' ); ?></a>
    </div>
    <div class="box-body table-responsive">
        <table class="table table-bordered border-dark wp-list-table widefat fixed striped table-view-list posts">
            <thead>
            <tr>
                <th width="10%"><?php esc_html_e( 'SL', 'custom-role-creator' ); ?></th>
                <th><?php esc_html_e( 'Role Name', 'custom-role-creator' ); ?></th>
                <th><?php esc_html_e( 'Assigned Capabilities', 'custom-role-creator' ); ?></th>
                <th><?php esc_html_e( 'Action', 'custom-role-creator' ); ?></th>
            </tr>
            </thead>
            <tbody>
            <?php
            $i            = 1;
            $nonce        = wp_create_nonce( 'crc_assign_role_nonce' );
            $delete_nonce = wp_create_nonce( 'crc_delete_role_nonce' );
            foreach ( $all_role_names as $key => $v_all_role ) {
                $all_capability = $all_roles_obj->get_role( $key );

                $count = 0;
                if ( ! empty( $all_capability->capabilities ) ) {
                    $count = count( $all_capability->capabilities );
                }
                ?>
                <tr>
                    <td><?php echo esc_html( $i ); ?></td>
                    <td><?php echo esc_html( $v_all_role ); ?></td>
                    <td class="text-sm-end"><?php echo esc_html( $count ); ?></td>
                    <td class="text-sm-end">
                        <?php
                        if ( 'administrator' !== $key ) {
                            ?>
                            <a href="users.php?page=custom-role-creator&role=<?php echo esc_html( $key ); ?>&action=assign&_wpnonce=<?php echo esc_attr( $nonce ); ?>" class="btn btn-success btn-flat btn-xs edit_button">
                                <i class="fas fa-cogs"></i> <?php esc_html_e( 'Assign capabilities', 'custom-role-creator' ); ?>
                            </a>
                            <button type="button" class="btn btn-primary btn-flat btn-xs crc_role_edit_button" data-bs-toggle="modal" data-bs-target="#myRoleEdit" data-edit_crc_role_display_name="<?php echo esc_attr( $v_all_role ); ?>" data-edit_crc_role_name="<?php echo esc_attr( $key ); ?>">
                                <i class="fas fa-pencil-alt"></i> <?php esc_html_e( 'Edit role', 'custom-role-creator' ); ?>
                            </button>
                            <a onclick="return confirm('<?php esc_html_e( 'Confirm to delete?', 'custom-role-creator' ); ?>')" href="users.php?page=custom-role-creator&role=<?php echo esc_html( $key ); ?>&action=delete&_wpnonce=<?php echo esc_attr( $delete_nonce ); ?>" class="btn btn-danger btn-flat btn-xs delete_button">
                                <i class="fas fa-trash-alt"></i> <?php esc_html_e( 'Delete role', 'custom-role-creator' ); ?>
                            </a>
                            <?php
                        }
                        ?>
                    </td>
                </tr>
                <?php
                $i ++;
            }
            ?>
            </tbody>
        </table>
    </div>
</div>
<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="myRole" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title"><?php esc_html_e( 'Create Role', 'custom-role-creator' ); ?></h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12 col-12 col-12">
                        <div class="x_content">
                            <form method="post" action="" data-parsley-validate class="form-horizontal form-label-left" enctype="multipart/form-data">
                                <div class="form-group">
                                    <label class="control-label col-3 col-3 col-12" for="crc_role_name"><?php esc_html_e( 'Role Name', 'custom-role-creator' ); ?>
                                        <span class="required">*</span>
                                    </label>
                                    <div class="col-6 col-6 col-12">
                                        <input type="text" name="crc_role_name" required id="crc_role_name" class="form-control col-7 col-12">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-3 col-3 col-12" for="crc_copy_of"><?php esc_html_e( 'Make copy of', 'custom-role-creator' ); ?></label>
                                    <div class="col-6 col-6 col-12">
                                        <select name="crc_copy_of" id="crc_copy_of" class="form-control">
                                            <option value="" hidden><?php esc_html_e( 'None', 'custom-role-creator' ); ?></option>
                                            <?php
                                            foreach ( $all_role_names as $key => $v_all_role ) {
                                                ?>
                                                <option id="<?php echo esc_html( $key ); ?>" value="<?php echo esc_html( $key ); ?>"><?php echo esc_html( $v_all_role ); ?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mt-20">
                                    <div class="col-auto me-auto"></div>
                                    <div class="col-auto">
                                        <button type="submit" id="crc_new_role_submit" name="crc_new_role_submit" class="btn btn-info btn-flat btn-sm">
                                            <i class="fas fa-check"></i> <?php esc_html_e( 'Save', 'custom-role-creator' ); ?></button>
                                    </div>
                                </div>
                                <?php wp_nonce_field( 'crc_new_role', 'crc_new_role_fields' ); ?>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!--EDIT MODAL-->
<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="myRoleEdit" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title"><?php esc_html_e( 'Update Role', 'custom-role-creator' ); ?> </h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12 col-12 col-12">
                        <div class="x_content">
                            <form method="post" action="" data-parsley-validate class="form-horizontal form-label-left" enctype="multipart/form-data">
                                <div class="form-group">
                                    <label class="control-label col-3 col-3 col-12" for="edit_crc_role_name"><?php esc_html_e( 'Role Name', 'custom-role-creator' ); ?><span class="required">*</span></label>
                                    <div class="col-6 col-6 col-12">
                                        <input type="text" name="crc_role_name" required id="edit_crc_role_name" class="form-control col-7 col-12">
                                        <input type="hidden" name="crc_pre_role_name" required id="edit_crc_pre_role_name" class="form-control col-7 col-12">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-3 col-3 col-12" for="edit_crc_copy_of"><?php esc_html_e( 'Make copy of', 'custom-role-creator' ); ?></label>
                                    <div class="col-6 col-6 col-12">
                                        <select name="crc_copy_of" id="edit_crc_copy_of" class="form-control">
                                            <option value="" hidden><?php esc_html_e( 'None', 'custom-role-creator' ); ?></option>
                                            <?php
                                            foreach ( $all_role_names as $key => $v_all_role ) {
                                                ?>
                                                <option id="<?php echo esc_html( $key ); ?>" value="<?php echo esc_html( $key ); ?>"><?php echo esc_html( $v_all_role ); ?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mt-20">
                                    <div class="col-auto me-auto"></div>
                                    <div class="col-auto">
                                        <button type="submit" id="crc_role_update" name="crc_role_update" class="btn btn-info btn-flat btn-sm">
                                            <i class="fas fa-pencil-alt"></i> <?php esc_html_e( 'Update', 'custom-role-creator' ); ?></button>
                                    </div>
                                </div>
                                <?php wp_nonce_field( 'crc_update_role', 'crc_update_role_fields' ); ?>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!--EDIT MODAL-->
