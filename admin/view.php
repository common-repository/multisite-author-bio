<?php
// Admin View Options Page for Multisite Author Bio Plugin

if( !current_user_can( 'manage_options' ) ) {
	wp_die( esc_html__( 'You do not have sufficient permissions to access this page.' ) );
}

// Get main site id
$main_site_id = get_main_site_id();

// Switch to main site if multisite
if ( function_exists( 'is_multisite' ) && is_multisite() && get_current_blog_id() != $main_site_id ) {
	switch_to_blog( $main_site_id );
}

// Retrieve the value of the 'mab_clear_data' option. If true, it means the user wants to clear the data on uninstall.
$clear_data = get_option( 'mab_clear_data' ) ? true : false;

// Restore the original blog
if ( function_exists( 'restore_current_blog' ) ) {
	restore_current_blog();
}

// Load the plugin's text domain for translations.
//mab()->plugin()->mab_load_plugin_textdomain();

?>
<div id="admin-view">
	<form id="mabForm" class="admin-view-form">

		<!-- Page Title -->
		<h1><?php echo esc_html_e( 'Multisite Author Bio', 'multisite-author-bio' ); ?></h1>

		<!-- Form Sections Wrapper -->
		<div class="sections">

			<!-- Section for Clearing Data on Uninstall -->
			<div class="section clear-data">
				<div class="checkbox">
					
					<!-- Checkbox for Clearing Data on Uninstall -->
					<div class="check">
						<input type="checkbox" name="cleardata" id="cleardata" value="1" <?php echo esc_attr( $clear_data ? 'checked' : '' ); ?>>
					</div>

					<!-- Label for Checkbox -->
					<div class="label">
						<label for="cleardata"><?php echo esc_html_e( 'Clear translation data on uninstall', 'multisite-author-bio' ); ?></label>
					</div>

					<!-- Description for Clear Data Option -->
					<div class="desc">
						<?php echo esc_html_e( 'Enabling this option will delete all the user meta data added by the plugin. It is highly advised to leave this unchecked if you plan to continue using this plugin.', 'multisite-author-bio' ); ?>
					</div>
				</div>
			</div>

			<!-- Save Button -->
			<input id="submitForm" class="button button-primary" name="submitForm" type="submit" value="<?php echo esc_html_e( 'Save Changes' ); ?>">

		</div>
	</form>
</div>
