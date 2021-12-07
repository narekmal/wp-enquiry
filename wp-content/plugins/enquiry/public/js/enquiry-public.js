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
		blockElement.classList.remove("enquiry-form--success");

		const formData = new FormData();
		const inputNames = ["first_name", "last_name", "email", "subject", "message"];
		let valid = true;

		inputNames.forEach(name => {
			const value = form.querySelector(`[name='${name}']`).value;
			valid = (value !== "");
			formData.append(name, value);
		});

		if(!valid) {
			blockElement.classList.add("enquiry-form--invalid");
			return;
		}

		blockElement.classList.remove("enquiry-form--invalid");
		blockElement.classList.add("enquiry-form--processing");
		formData.append('action', 'enquiry_process_form_data');

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
	initEnquiryResults_expanders();
	initEnquiryResults_page_links();
}

const initEnquiryResults_page_links = () => {
	const blockClass = "enquiry-results";
	const pageLinks = document.querySelectorAll(".js-page-link");

	const handlePageLinkClick = e => {
		const clickedLink = e.target;
		const pagination = clickedLink.closest(`.${blockClass}__pagination`);
        const pageNumber = clickedLink.getAttribute("data-page-number");

		const activeClassName = `${blockClass}__page-link--active`;

		pageLinks.forEach(item => {
			item.classList.remove(activeClassName);
		});
		clickedLink.classList.add(activeClassName);

		pagination.classList.add(`${blockClass}__pagination--loading`);

		fetch(`${ajaxUrl}?action=enquiry_get_form_data&page_number=${pageNumber}`, {
			method: "GET"
		})
		.then(response => { 
			response.json().then(response => {
				pagination.classList.remove(`${blockClass}__pagination--loading`);
				renderRows(response);
			})
		});
    }

    pageLinks.forEach(item => item.addEventListener("click", handlePageLinkClick));
}

const initEnquiryResults_expanders = () => {
	const blockClass = "enquiry-results";
	const expanders = document.querySelectorAll(".js-row-expander");

	const handleExpanderClick = e => {
        const row = e.target.closest(`.${blockClass}__grid-row`);
        const details = row.querySelector(`.${blockClass}__details`);

		if(details.classList.contains(`${blockClass}__details--open`)) {
			details.classList.remove(`${blockClass}__details--open`);
			return;
		}

		const rowId = row.getAttribute("data-row-id");
		details.classList.add(`${blockClass}__details--open`);

		fetch(`${ajaxUrl}?action=enquiry_get_form_record&id=${rowId}`, {
			method: "GET"
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

const renderRows = (rows) => {
	const blockClass = "enquiry-results";
	const rowsContainer = document.querySelector('.js-grid-rows');

	const rowTemplate = document.querySelector('.js-grid-row').cloneNode(true);
	rowTemplate.querySelector(`.${blockClass}__details`).classList.remove(`${blockClass}__details--open`);

	const newRows = document.createElement("div");
	newRows.style.display = "contents";

	rows.data.forEach(row => {
		const clone = rowTemplate.cloneNode(true);
		clone.querySelector(".js-first-name").innerText = row.first_name;
		clone.querySelector(".js-last-name").innerText = row.last_name;
		clone.querySelector(".js-subject").innerText = row.subject;
		clone.querySelector(".js-email").innerText = row.email;
		newRows.appendChild(clone);
	});

	rowsContainer.innerHTML = '';
	rowsContainer.appendChild(newRows);
	initEnquiryResults_expanders();
}