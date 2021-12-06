<?php

function enquiry_form( $atts ){
    $user = wp_get_current_user();

	ob_start();
    ?>

    <script>const ajaxUrl = "<?php echo admin_url('admin-ajax.php'); ?>";</script>

    <div class="enquiry-form">
        <h3 class="enquiry-form__heading">Submit your feedback</h3>
        <form class="enquiry-form__form js-enquiry-form">
            <div class="enquiry-form__row">
                <input name="first_name" type="text" placeholder="First Name" class="enquiry-form__first-name" 
                    value="<?php echo $user->exists() ? $user->user_firstname : ""; ?>" >
                <input name="last_name" type="text" placeholder="Last Name" class="enquiry-form__last-name"
                    value="<?php echo $user->exists() ? $user->user_lastname : ""; ?>" >
            </div>
            <div class="enquiry-form__row">
                <input name="email" type="text" placeholder="Email" class="enquiry-form__email"
                    value="<?php echo $user->exists() ? $user->user_email : ""; ?>" >
                <input name="subject" type="text" placeholder="Subject" class="enquiry-form__subject">
            </div>
            <textarea name="message" placeholder="Message" cols="30" rows="10" class="enquiry-form__message"></textarea>
            <input type="submit" class="enquiry-form__submit">
        </form>
        <div class="enquiry-form__overlay enquiry-form__overlay--processing">
            <span>Processing...</span>
        </div>
        <div class="enquiry-form__overlay enquiry-form__overlay--success">
            <span>Thank you for sending us your feedback</span>
        </div>
        <div class="enquiry-form__overlay enquiry-form__overlay--failure">
            <span>Something went wrong, please try to submit again</span>
        </div>
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
    
    foreach($post_params as $param) {
        $db_params[$param] = $_POST[$param];
    }

    $result = $wpdb->insert($wpdb->prefix . 'enquiry_form_data', $db_params);

	echo json_encode([
        "status" => $result ? "success" : "failure"
    ]);

  	die();
}
add_action( 'wp_ajax_nopriv_enquiry_process_form_data', 'enquiry_process_form_data' );
add_action( 'wp_ajax_enquiry_process_form_data', 'enquiry_process_form_data' );