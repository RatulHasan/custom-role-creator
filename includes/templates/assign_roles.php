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
}
?>
<div class="wrap">
    <form id="crc_assign_cap_form" action="" method="post">
        <input name="crc_current_role_name"  type="hidden" value="<?php echo esc_attr( $current_role_name ); ?>">
        <?php wp_nonce_field( 'crc_assign_cap_form', 'crc_assign_cap_form_field' ); ?>
        <h1 class="wp-heading-inline"><?php esc_html_e( 'Capabilities for', 'custom-role-creator' ); ?> <u><?php echo esc_html( $role_name ); ?></u> Role</h1>
        <button name="crc_cap_submit" type="submit" class="page-title-action"><i class="fas fa-check"></i> <?php esc_html_e( 'Save Capabilities', 'custom-role-creator' ); ?></button>
        <div class="tablenav top">
            <label for="crc_all_roles_dropdown"><?php esc_html_e( 'All available roles', 'custom-role-creator' ); ?></label>
            <select name="crc_all_roles_dropdown" id="crc_all_roles_dropdown">
                <option value="all"><?php esc_html_e( 'All capabilities', 'custom-role-creator' ); ?></option>
                <?php
                $nonce = wp_create_nonce( 'crc_assign_role_nonce' );
                foreach ( $all_role_names as $key => $v_all_role ) {
                    ?>
                    <option id="<?php echo esc_html( $key ); ?>" value="<?php echo esc_html( $key ); ?>"><?php echo esc_html( $v_all_role ); ?></option>
                    <?php
                }
                ?>
            </select>
            <label for="crc_select_all_caps"><?php esc_html_e( 'Select all', 'custom-role-creator' ); ?></label>
            <input id="crc_select_all_caps" data-crc_cap_value='all' type="checkbox">
            <br class="clear">
        </div>
        <div class="flex-columns">
            <?php
            foreach ( $all_caps as $cap ) {
                ?>
                <p class="all <?php echo esc_attr( $classes[ $cap ] ); ?>">
                    <label>
                        <input class="all <?php echo esc_attr( $classes[ $cap ] ); ?>" name="crc_add_cap[]" <?php echo checked( in_array( $cap, $checked_caps, true ), 1, true ); ?> type="checkbox" value="<?php echo esc_attr( $cap ); ?>"> <?php echo esc_html( $cap ); ?>
                    </label>
                    <br>
                </p>
                <?php
            }
            ?>
        </div>
        <div class="floating-button">
            <button name="crc_cap_submit" type="submit" class="button action alignright"><i class="fas fa-check"></i> <?php esc_html_e( 'Save Capabilities', 'custom-role-creator' ); ?></button>
        </div>
    </form>
</div>
