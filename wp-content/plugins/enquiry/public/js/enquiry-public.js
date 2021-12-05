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
	const pageLinks = document.querySelectorAll(".js-page-link");

	const handleExpanderClick = e => {
        const row = e.target.closest(`.${blockClass}__grid-row`);
        const details = row.querySelector(`.${blockClass}__details`);
		const rowId = row.getAttribute("data-row-id");
		details.classList.add(`${blockClass}__details--open`);

		const formData = new FormData();
		formData.append('action', 'enquiry_get_form_record');
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

		const formData = new FormData();
		formData.append('action', 'enquiry_get_form_data');
		formData.append('page_number', pageNumber);

		fetch(ajaxUrl, {
			method: "POST",
			body: formData
		})
		.then(response => { 
			response.json().then(response => {
				pagination.classList.remove(`${blockClass}__pagination--loading`);
				renderRows(response);
			})
		});
    }

    expanders.forEach(item => item.addEventListener("click", handleExpanderClick));
    pageLinks.forEach(item => item.addEventListener("click", handlePageLinkClick));
}

const renderRows = (rows) => {
	const rowsContainer = document.querySelector('.js-grid-rows');
	const firstRow = document.querySelector('.js-grid-row');
	const newRows = document.createElement("div");
	newRows.style.display = "contents";

	rows.data.forEach(row => {
		const clone = firstRow.cloneNode(true);
		clone.querySelector(".js-first-name").innerText = row.first_name;
		clone.querySelector(".js-last-name").innerText = row.last_name;
		clone.querySelector(".js-subject").innerText = row.subject;
		clone.querySelector(".js-email").innerText = row.email;
		newRows.appendChild(clone);
	});

	rowsContainer.innerHTML = '';
	rowsContainer.appendChild(newRows);
}
