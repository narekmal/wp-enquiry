<?php

function enquiry_form( $atts ){
	ob_start();
    ?>

    <div class="enquiry-form">
        <h3 class="enquiry-form__heading">Submit your feedback</h3>
        <form action="" class="enquiry-form__form">
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
    </div>
    
    <?php
    $output = ob_get_contents();
    ob_end_clean();
    return $output;
}
add_shortcode( 'enquiry_form', 'enquiry_form' );