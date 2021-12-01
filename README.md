# Codeable-WordPress-Full-Stack-Test

Welcome to the Codeable WordPress Full Stack test!

We're excited to get you started! I'm going to detail a project for you below...

Think of this as working with a real client and there is a hard deadline needed with a launch planned for the next day after delivery. If you don't get back to us within seven days you'll fail the test, the launch will be delayed and the client lost.

## Your Task

Please create a custom form (no form plugins please) that will be printed on frontend through a shortcode or a Gutenberg block. The form needs to allow users to submit a generic enquiry.

**Title**: 'Submit your feedback'

The form must have the following mandatory fields:

- First name
- Last name
- Email
- Subject
- Message

The form can be submitted by both logged in and not logged in users. If the user is logged in the form must be pre-filled with first and last name and email. The form must be sent via AJAX and saved in the WordPress database. It's totally up to you whether to save the form in a custom table or use the existing WordPress DB tables. As long as you meet the requirements of this exercise, either is good. Once the form is sent, it must be replaced with the message "Thank you for sending us your feedback".

Create then a second shortcode or Gutenberg block to show the list of entries with first name, last name, email and subject. The list must be visible only to admin users. If non-authorized users try to access the page with the list, you must show the message 'You are not authorized to view the content of this page.'. For Authorized users, clicking on any item of the list will show the complete entry below the list of entries (complete entry meaning all of the fields). Please paginate the entries if there are more than ten. The entries must NOT be visible nor searchable by non admin users.

Please don't pre-load the entries, they must be loaded via AJAX when they are clicked.

Pagination must also work via AJAX.

## Requirements

- Please style form and list for desktop view only, no need to make them responsive.

- You cannot use the library 'Datatables' for this project.

- The Gutenberg block is optional but if you choose to develop it, we expect a full implementation which includes a preview in the editor.

- In the case where the shortcode is added to multiple pages, treat it as if they are the same form in multiple places and store all the data together.

- The plugin must be translation-ready.

- PHP version: Your solution must use/be compatible with `PHP version 7.4`.

- We require that you follow a coding standard. We prefer of course the WP coding standard because it makes it easier to collaborate with other people but if you are used with and prefer a different coding standard it's ok as long as you declare it to us and then are consistent in your code.

If you plan to transpile the CSS and JS code, while this is perfect for a production situation, it makes it more challenging for a code review and so we will ask you to please include all source code for the review process.

**Note** that the [composer.json](composer.json) file may only be updated to add any third-party dependencies required for your solution. The existing dependencies and versions specified in the file must not be changed.

Place anything else you would like us to note about your submission in a `notes.txt` file at the root of this repository.

**VERY IMPORTANT**: As part of terms of the the Codeable application process, we do not grant you the permission to make the requirements for project, or the code for this plugin, public. You are also not allowed to publish this plugin on any public plugin directory. This includes, but is not limited to, the WordPress plugin directory, or any free or paid plugin marketplace. If you don't follow this requirement, you will be blacklisted and will not be allowed to reapply again.

##

This is a test but you are supposed to show us the very best you can do. Keep in mind that a 'this could be made better, but will do' approach could lead to a fail in the end. We want you to sign your name with pride knowing you’ve delivered your very best work. This is what clients on Codeable expect and we want to know you’re up for the challenge.

Please feel free to ask questions if you have any doubts.

We look forward to hearing from you.

Kind regards & best of luck!

## Submitting your solution

Please push your changes to the `master branch` of this repository. You can push one or more commits. <br>

Once you are finished with the task, please click the `Complete task` link on <a href="https://app.codescreen.dev/#/codescreentest9de03ba2-ea8b-496c-936e-936e9d28de01" target="_blank">this screen</a>.