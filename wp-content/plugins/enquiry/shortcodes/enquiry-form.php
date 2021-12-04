<?php

function enquiry_form( $atts ){
	ob_start();
    ?>

    <script>const ajaxUrl = "<?php echo admin_url('admin-ajax.php'); ?>";</script>

    <div class="enquiry-form">
        <h3 class="enquiry-form__heading">Submit your feedback</h3>
        <form action="" class="enquiry-form__form js-enquiry-form">
            <div class="enquiry-form__row">
                <input type="text" placeholder="First Name" class="enquiry-form__first-name">
                <input type="text" placeholder="Last Name" class="enquiry-form__last-name">
            </div>
            <div class="enquiry-form__row">
                <input type="text" placeholder="Email" class="enquiry-form__email">
                <input type="text" placeholder="Subject" class="enquiry-form__subject">
            </div>
            <textarea name="" placeholder="Message" cols="30" rows="10" class="enquiry-form__message"></textarea>
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
	//update_post_meta($_POST['offer_id'], 'favorite_count', $_POST['favorite_count']);
	echo json_encode([
        "status" => "failure"
    ]);
  	die();
}
add_action( 'wp_ajax_nopriv_enquiry_process_form_data', 'enquiry_process_form_data' );
add_action( 'wp_ajax_enquiry_process_form_data', 'enquiry_process_form_data' );