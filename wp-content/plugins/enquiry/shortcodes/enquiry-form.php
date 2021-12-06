<?php

function enquiry_form( $atts ){
    $user = wp_get_current_user();

	ob_start();
    ?>

    <script>const ajaxUrl = "<?php echo admin_url('admin-ajax.php'); ?>";</script>

    <div class="enquiry-form">
        <h3 class="enquiry-form__heading"><?php _e("Submit your feedback", "enquiry"); ?></h3>
        <form class="enquiry-form__form js-enquiry-form">
            <div class="enquiry-form__row">
                <input name="first_name" type="text" placeholder="<?php _e("First Name", "enquiry"); ?>" class="enquiry-form__first-name" 
                    value="<?php echo $user->exists() ? $user->user_firstname : ""; ?>" >
                <input name="last_name" type="text" placeholder="<?php _e("Last Name", "enquiry"); ?>" class="enquiry-form__last-name"
                    value="<?php echo $user->exists() ? $user->user_lastname : ""; ?>" >
            </div>
            <div class="enquiry-form__row">
                <input name="email" type="text" placeholder="<?php _e("Email", "enquiry"); ?>" class="enquiry-form__email"
                    value="<?php echo $user->exists() ? $user->user_email : ""; ?>" >
                <input name="subject" type="text" placeholder="<?php _e("Subject", "enquiry"); ?>" class="enquiry-form__subject">
            </div>
            <textarea name="message" placeholder="<?php _e("Message", "enquiry"); ?>" cols="30" rows="10" class="enquiry-form__message"></textarea>
            <div class="enquiry-form__last-row">
                <input type="submit" class="enquiry-form__submit">
                <div class="enquiry-form__status">
                    <div class="enquiry-form__status-processing"><?php _e("Processing", "enquiry"); ?>...</div>
                    <div class="enquiry-form__status-success"><?php _e("Thank you for sending us your feedback", "enquiry"); ?></div>
                    <div class="enquiry-form__status-failure"><?php _e("Something went wrong, please try to submit again", "enquiry"); ?></div>
                    <div class="enquiry-form__status-invalid"><?php _e("Please fill out all fields", "enquiry"); ?></div>
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


function enquiry_process_form_data() {
    global $wpdb;

    $post_params = ["first_name", "last_name", "email", "subject", "message"];
    $db_params = [];
    $valid = true;
    
    foreach($post_params as $param) {
        if(empty($_POST[$param])) {
            $valid = false;
            break;
        }
        $db_params[$param] = $_POST[$param];
    }

    if(!$valid) {
        echo json_encode([
            "status" => "failure"
        ]);
        die();
    }

    $result = $wpdb->insert($wpdb->prefix . 'enquiry_form_data', $db_params);

	echo json_encode([
        "status" => $result ? "success" : "failure"
    ]);

  	die();
}
add_action( 'wp_ajax_nopriv_enquiry_process_form_data', 'enquiry_process_form_data' );
add_action( 'wp_ajax_enquiry_process_form_data', 'enquiry_process_form_data' );