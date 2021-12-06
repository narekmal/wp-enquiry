<?php

function enquiry_results( $atts ){
    $user = wp_get_current_user();
    $is_admin = in_array("administrator", $user->roles);

    if(!$is_admin) {
        echo "<h2>You are not authorized to view enquiry results.</h2>";
        return;
    }

    global $wpdb;

    $sql = "SELECT SQL_CALC_FOUND_ROWS id, first_name, last_name, email, subject
        FROM {$wpdb->prefix}enquiry_form_data
        ORDER BY time_submitted DESC
        LIMIT 10";

    $form_data = $wpdb->get_results($sql);

    $total_count = $wpdb->get_var("SELECT FOUND_ROWS()");

    $page_count = ceil($total_count/10);

	ob_start();
    ?>

    <script>const ajaxUrl = "<?php echo admin_url('admin-ajax.php'); ?>";</script>

    <div class="enquiry-results">
        <div class="enquiry-results__grid">
            <div class="enquiry-results__grid-header">
                <div class="enquiry-results__cell"><?php _e("First Name", "enquiry"); ?></div>
                <div class="enquiry-results__cell"><?php _e("Last Name", "enquiry"); ?></div>
                <div class="enquiry-results__cell"><?php _e("Email", "enquiry"); ?></div>
                <div class="enquiry-results__cell"><?php _e("Subject", "enquiry"); ?></div>
                <div class="enquiry-results__cell"></div>
            </div>

            <div class="enquiry-results__grid-rows js-grid-rows">
                <?php foreach($form_data as $row) : ?>
                <div class="enquiry-results__grid-row js-grid-row" data-row-id="<?php echo $row->id; ?>">
                    <div class="enquiry-results__cell js-first-name"><?php echo $row->first_name; ?></div>
                    <div class="enquiry-results__cell js-last-name"><?php echo $row->last_name; ?></div>
                    <div class="enquiry-results__cell js-email"><?php echo $row->email; ?></div>
                    <div class="enquiry-results__cell js-subject"><?php echo $row->subject; ?></div>
                    <div class="enquiry-results__cell">
                        <img class="enquiry-results__row-expander js-row-expander" src="<?php echo ENQUIRY_BASE_URL . '/public/img/eye.svg'; ?>" alt="<?php _e("View Details", "enquiry"); ?>" title="<?php _e("View Details", "enquiry"); ?>">
                    </div>
                    <div class="enquiry-results__details">
                        <div class="enquiry-results__details-loading"><?php _e("Loading", "enquiry"); ?>...</div>
                        <div class="enquiry-results__details-main">
                            <div class="enquiry-results__details-title"><?php _e("Message", "enquiry"); ?>:</div>
                            <div class="enquiry-results__details-content"></div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            
        </div>
        <?php if($page_count > 1): ?>
        <div class="enquiry-results__pagination">
            <?php for($i=1; $i<=$page_count; $i++): ?>
            <a data-page-number="<?php echo $i; ?>" class="enquiry-results__page-link js-page-link <?php echo $i==1 ? "enquiry-results__page-link--active" : ""; ?>">
                <?php echo $i; ?>
            </a>
            <?php endfor; ?>
            <span class="enquiry-results__page-loading">
                <?php _e("Loading", "enquiry"); ?>...
            </span>
        </div>
        <?php endif; ?>
    </div>
    
    <?php
    $output = ob_get_contents();
    ob_end_clean();
    return $output;
}
add_shortcode( 'enquiry_results', 'enquiry_results' );


function enquiry_get_form_record() {
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
add_action( 'wp_ajax_nopriv_enquiry_get_form_record', 'enquiry_get_form_record' );
add_action( 'wp_ajax_enquiry_get_form_record', 'enquiry_get_form_record' );

function enquiry_get_form_data() {
    global $wpdb;

    $offset = ($_POST["page_number"] - 1) * 10;

    $sql = "SELECT id, first_name, last_name, email, subject
        FROM {$wpdb->prefix}enquiry_form_data
        ORDER BY time_submitted DESC
        LIMIT 10
        OFFSET $offset";

    $form_data = $wpdb->get_results($sql);

	echo json_encode([
        "status" => $form_data ? "success" : "failure",
        "data" => $form_data
    ]);

  	die();
}
add_action( 'wp_ajax_nopriv_enquiry_get_form_data', 'enquiry_get_form_data' );
add_action( 'wp_ajax_enquiry_get_form_data', 'enquiry_get_form_data' );