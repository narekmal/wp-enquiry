<?php

function enquiry_results( $atts ){

    global $wpdb;
    $table_name = $wpdb->prefix . 'enquiry_form_data';

    $sql = "SELECT id, first_name, last_name, email, subject
        FROM {$wpdb->prefix}enquiry_form_data
        ORDER BY time_submitted DESC";

    $form_data = $wpdb->get_results($sql);

	ob_start();
    ?>

    <script>const ajaxUrl = "<?php echo admin_url('admin-ajax.php'); ?>";</script>

    <div class="enquiry-results">
        <div class="enquiry-results__grid">
            <div class="enquiry-results__grid-header">
                <div class="enquiry-results__cell">First Name</div>
                <div class="enquiry-results__cell">Last Name</div>
                <div class="enquiry-results__cell">Email</div>
                <div class="enquiry-results__cell">Subject</div>
                <div class="enquiry-results__cell"></div>
            </div>

            <?php foreach($form_data as $row) : ?>
            <div class="enquiry-results__grid-row" data-row-id="<?php echo $row->id; ?>">
                <div class="enquiry-results__cell"><?php echo $row->first_name; ?></div>
                <div class="enquiry-results__cell"><?php echo $row->last_name; ?></div>
                <div class="enquiry-results__cell"><?php echo $row->email; ?></div>
                <div class="enquiry-results__cell"><?php echo $row->subject; ?></div>
                <div class="enquiry-results__cell"><button class="js-row-expander">S</button></div>
                <div class="enquiry-results__details">
                    <div class="enquiry-results__details-loading">Loading...</div>
                    <div class="enquiry-results__details-main">
                        <div class="enquiry-results__details-title">Message</div>
                        <div class="enquiry-results__details-content"></div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    
    <?php
    $output = ob_get_contents();
    ob_end_clean();
    return $output;
}
add_shortcode( 'enquiry_results', 'enquiry_results' );


function enquiry_get_form_data() {
    global $wpdb;

    $sql = "SELECT message
        FROM {$wpdb->prefix}enquiry_form_data
        WHERE id={$_POST['id']}";

    $result = $wpdb->get_var($sql);

	echo json_encode([
        "status" => $result ? "success" : "failure",
        "data" => $result
    ]);

  	die();
}
add_action( 'wp_ajax_nopriv_enquiry_get_form_data', 'enquiry_get_form_data' );
add_action( 'wp_ajax_enquiry_get_form_data', 'enquiry_get_form_data' );