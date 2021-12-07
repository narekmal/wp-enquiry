<?php
/**
 * Define enquiry_results shortcode and its AJAX endpoints
 *
 * @package    Enquiry
 */

/**
 * Render output of enquiry_results shortcode
 */
function render_enquiry_results() {
	$user     = wp_get_current_user();
	$is_admin = in_array( 'administrator', $user->roles );

	if ( ! $is_admin ) {
		echo '<h2>You are not authorized to view enquiry results.</h2>';
		return;
	}

	global $wpdb;

	$form_data = $wpdb->get_results(
		$wpdb->prepare(
			'SELECT SQL_CALC_FOUND_ROWS id, first_name, last_name, email, subject
			FROM %1senquiry_form_data
			ORDER BY time_submitted DESC
			LIMIT 10',
			$wpdb->prefix
		)
	);

	$total_count = $wpdb->get_var( 'SELECT FOUND_ROWS()' );

	$page_count = ceil( $total_count / 10 );

	ob_start();
	?>

	<script>const ajaxUrl = "<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>";</script>

	<div class="enquiry-results">
		<div class="enquiry-results__grid">
			<div class="enquiry-results__grid-header">
				<div class="enquiry-results__cell"><?php esc_html_e( 'First Name', 'enquiry' ); ?></div>
				<div class="enquiry-results__cell"><?php esc_html_e( 'Last Name', 'enquiry' ); ?></div>
				<div class="enquiry-results__cell"><?php esc_html_e( 'Email', 'enquiry' ); ?></div>
				<div class="enquiry-results__cell"><?php esc_html_e( 'Subject', 'enquiry' ); ?></div>
				<div class="enquiry-results__cell"></div>
			</div>

			<div class="enquiry-results__grid-rows js-grid-rows">
				<?php foreach ( $form_data as $row ) : ?>
				<div class="enquiry-results__grid-row js-grid-row" data-row-id="<?php echo esc_attr( $row->id ); ?>">
					<div class="enquiry-results__cell js-first-name"><?php echo esc_attr( $row->first_name ); ?></div>
					<div class="enquiry-results__cell js-last-name"><?php echo esc_attr( $row->last_name ); ?></div>
					<div class="enquiry-results__cell js-email"><?php echo esc_attr( $row->email ); ?></div>
					<div class="enquiry-results__cell js-subject"><?php echo esc_attr( $row->subject ); ?></div>
					<div class="enquiry-results__cell">
						<img class="enquiry-results__row-expander js-row-expander" src="<?php echo esc_url( ENQUIRY_BASE_URL . '/public/img/eye.svg' ); ?>" alt="<?php esc_attr_e( 'View Details', 'enquiry' ); ?>" title="<?php esc_attr_e( 'View Details', 'enquiry' ); ?>">
					</div>
					<div class="enquiry-results__details">
						<div class="enquiry-results__details-loading"><?php esc_html_e( 'Loading', 'enquiry' ); ?>...</div>
						<div class="enquiry-results__details-main">
							<div class="enquiry-results__details-title"><?php esc_html_e( 'Message', 'enquiry' ); ?>:</div>
							<div class="enquiry-results__details-content"></div>
						</div>
					</div>
				</div>
				<?php endforeach; ?>
			</div>
			
		</div>
		<?php if ( $page_count > 1 ) : ?>
		<div class="enquiry-results__pagination">
			<?php for ( $i = 1; $i <= $page_count; $i++ ) : ?>
			<a data-page-number="<?php echo esc_attr( $i ); ?>" class="enquiry-results__page-link js-page-link <?php echo 1 == $i ? 'enquiry-results__page-link--active' : ''; ?>">
				<?php echo esc_attr( $i ); ?>
			</a>
			<?php endfor; ?>
			<span class="enquiry-results__page-loading">
				<?php esc_html_e( 'Loading', 'enquiry' ); ?>...
			</span>
		</div>
		<?php endif; ?>
	</div>
	
	<?php
	$output = ob_get_contents();
	ob_end_clean();
	return $output;
}
add_shortcode( 'enquiry_results', 'render_enquiry_results' );

/**
 * AJAX endpoint for getting form submission message by record id
 */
function get_enquiry_form_record() {
	global $wpdb;

	if ( empty( $_POST['id'] ) ) {
		wp_send_json_error();
		die();
	}

	$result = $wpdb->get_var(
		$wpdb->prepare(
			'SELECT message
			FROM %1senquiry_form_data
			WHERE id=%d',
			$wpdb->prefix,
			$_POST['id']
		)
	);

	$result ? wp_send_json_success( $result ) : wp_send_json_error();

	die();
}
add_action( 'wp_ajax_nopriv_enquiry_get_form_record', 'get_enquiry_form_record' );
add_action( 'wp_ajax_enquiry_get_form_record', 'get_enquiry_form_record' );

/**
 * AJAX endpoint for getting paged form records by page number
 */
function get_enquiry_form_data() {
	global $wpdb;

	$offset = ( $_POST['page_number'] - 1 ) * 10;

	$sql = "SELECT id, first_name, last_name, email, subject
		FROM {$wpdb->prefix}enquiry_form_data
		ORDER BY time_submitted DESC
		LIMIT 10
		OFFSET $offset";

	$form_data = $wpdb->get_results( $sql );

	echo json_encode(
		array(
			'status' => $form_data ? 'success' : 'failure',
			'data'   => $form_data,
		)
	);

	die();
}
add_action( 'wp_ajax_nopriv_enquiry_get_form_data', 'enquiry_get_form_data' );
add_action( 'wp_ajax_enquiry_get_form_data', 'enquiry_get_form_data' );
