"use strict";

document.addEventListener("DOMContentLoaded", () => { 
	initEnquiryForm();
	initEnquiryResults();
});

const initEnquiryForm = () => {
	const form = document.querySelector(".js-enquiry-form");

	if(!form)
		return;

	form.addEventListener("submit", e => {
		e.preventDefault();

		const blockElement = form.closest(".enquiry-form");
		blockElement.classList.add("enquiry-form--processing");

		const formData = new FormData();
		formData.append('action', 'enquiry_process_form_data');
		const inputNames = ["first_name", "last_name", "email", "subject", "message"];
		inputNames.forEach(name => {
			formData.append(name, form.querySelector(`[name='${name}']`).value);
		});

		fetch(ajaxUrl, {
			method: "POST",
			body: formData
		})
		.then(response => { 
			response.json().then(response => {
				console.log(response);
				blockElement.classList.remove("enquiry-form--processing");
				blockElement.classList.add(response.status == "success" ? "enquiry-form--success" : "enquiry-form--failure");
			})
		})
		.catch(function(response){ 
			console.log(response); 
			blockElement.classList.remove("enquiry-form--processing");
			blockElement.classList.add("enquiry-form--failure");
		});
	});
}

const initEnquiryResults = () => {
	const blockClass = "enquiry-results";
	const expanders = document.querySelectorAll(".js-row-expander");

	const handleExpanderClick = e => {
        const row = e.target.closest(`.${blockClass}__grid-row`);
        const details = row.querySelector(`.${blockClass}__details`);
		const rowId = row.getAttribute("data-row-id");
		details.classList.add(`${blockClass}__details--open`);

		const formData = new FormData();
		formData.append('action', 'enquiry_get_form_data');
		formData.append('id', rowId);

		fetch(ajaxUrl, {
			method: "POST",
			body: formData
		})
		.then(response => { 
			response.json().then(response => {
				console.log(response);
				details.classList.add(`${blockClass}__details--loaded`);
				console.log(details.querySelector(`.${blockClass}__details-content`));
				details.querySelector(`.${blockClass}__details-content`).innerText = response.data;
			})
		});
    }

    expanders.forEach(item => item.addEventListener("click", handleExpanderClick));
}

