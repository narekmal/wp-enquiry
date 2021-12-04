document.addEventListener("DOMContentLoaded", () => { 
	"use strict";

    const form = document.querySelector(".js-enquiry-form");

	form.addEventListener("submit", e => {
		e.preventDefault();

		const blockElement = form.closest(".enquiry-form");
		blockElement.classList.add("enquiry-form--processing");

		const formData = new FormData();
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

    // const handleCardClick = e => {
    //     const clickedCard = e.target.closest(".js-card")
    //     cards.forEach(card => {
    //         card.isSameNode(clickedCard) ? card.classList.remove("js-invert-colors") : card.classList.add("js-invert-colors");
    //     });
    // }

    // cards.forEach(card => card.addEventListener("click", handleCardClick));
});