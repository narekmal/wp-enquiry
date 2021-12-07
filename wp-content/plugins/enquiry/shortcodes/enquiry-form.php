<?php
/**
 * Define enquiry_form shortcode and its AJAX endpoints
 *
 * @package    Enquiry
 */

/**
 * Render output of enquiry_form shortcode
 */
function enquiry_form() {
	$user = wp_get_current_user();

	ob_start();
	?>

	<script>const ajaxUrl = "<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>";</script>

	<div class="enquiry-form">
		<h3 class="enquiry-form__heading"><?php esc_html_e( 'Submit your feedback', 'enquiry' ); ?></h3>
		<form class="enquiry-form__form js-enquiry-form">
			<div class="enquiry-form__row">
				<input name="first_name" type="text" placeholder="<?php esc_html_e( 'First Name', 'enquiry' ); ?>" class="enquiry-form__first-name" 
					value="<?php echo $user->exists() ? esc_attr( $user->user_firstname ) : ''; ?>" >
				<input name="last_name" type="text" placeholder="<?php esc_html_e( 'Last Name', 'enquiry' ); ?>" class="enquiry-form__last-name"
					value="<?php echo $user->exists() ? esc_attr( $user->user_lastname ) : ''; ?>" >
			</div>
			<div class="enquiry-form__row">
				<input name="email" type="text" placeholder="<?php esc_html_e( 'Email', 'enquiry' ); ?>" class="enquiry-form__email"
					value="<?php echo $user->exists() ? esc_attr( $user->user_email ) : ''; ?>" >
				<input name="subject" type="text" placeholder="<?php esc_html_e( 'Subject', 'enquiry' ); ?>" class="enquiry-form__subject">
			</div>
			<textarea name="message" placeholder="<?php esc_html_e( 'Message', 'enquiry' ); ?>" cols="30" rows="10" class="enquiry-form__message"></textarea>
			<div class="enquiry-form__last-row">
				<?php wp_nonce_field( 'enquiry_process_form_data', 'enquiry-form-nonce' ); ?>
				<input type="submit" class="enquiry-form__submit">
				<div class="enquiry-form__status">
					<div class="enquiry-form__status-processing"><?php esc_html_e( 'Processing', 'enquiry' ); ?>...</div>
					<div class="enquiry-form__status-success"><?php esc_html_e( 'Thank you for sending us your feedback', 'enquiry' ); ?></div>
					<div class="enquiry-form__status-failure"><?php esc_html_e( 'Something went wrong, please try to submit again', 'enquiry' ); ?></div>
					<div class="enquiry-form__status-invalid"><?php esc_html_e( 'Please fill out all fields', 'enquiry' ); ?></div>
				</div>
			</div>
		</form>
	</div>
	
	<?php
	$output = ob_get_contents();
	ob_end_clean();
	return $output;
}
add_shortcode( 'enquiry_form', 'enquiry_form' );

/**
 * AJAX endpoint for processing form data
 */
function process_enquiry_form_data() {
	if ( empty( $_POST['enquiry-form-nonce'] ) ||
		! wp_verify_nonce( $_POST['enquiry-form-nonce'], 'enquiry_process_form_data' ) ) {
		wp_send_json_error();
		die();
	}

	global $wpdb;

	$post_params = array( 'first_name', 'last_name', 'email', 'subject', 'message' );
	$db_params   = array();
	$valid       = true;

	foreach ( $post_params as $param ) {
		if ( empty( $_POST[ $param ] ) ) {
			$valid = false;
			break;
		}
		$db_params[ $param ] = ( 'email' == $param ? sanitize_email( wp_unslash( $_POST[ $param ] ) ) : sanitize_text_field( wp_unslash( $_POST[ $param ] ) ) );
	}

	if ( ! $valid ) {
		wp_send_json_error();
		die();
	}

	$result = $wpdb->insert( $wpdb->prefix . 'enquiry_form_data', $db_params );

	$result ? wp_send_json_success() : wp_send_json_error();

	die();
}
add_action( 'wp_ajax_nopriv_enquiry_process_form_data', 'process_enquiry_form_data' );
add_action( 'wp_ajax_enquiry_process_form_data', 'process_enquiry_form_data' );
