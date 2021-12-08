/* eslint-disable linebreak-style */

const initEnquiryForm = () => {
  const form = document.querySelector('.js-enquiry-form');

  if (!form) { return; }

  form.addEventListener('submit', (e) => {
    e.preventDefault();

    const blockElement = form.closest('.enquiry-form');
    blockElement.classList.remove('enquiry-form--success');
    blockElement.classList.remove('enquiry-form--failure');

    const formData = new FormData();
    const inputNames = ['first_name', 'last_name', 'email', 'subject', 'message', 'enquiry-form-nonce'];
    let valid = true;

    inputNames.forEach((name) => {
      const { value } = form.querySelector(`[name='${name}']`);
      if (valid) { valid = (value !== ''); }
      formData.append(name, value);
    });

    if (!valid) {
      blockElement.classList.add('enquiry-form--invalid');
      return;
    }

    blockElement.classList.remove('enquiry-form--invalid');
    blockElement.classList.add('enquiry-form--processing');
    formData.append('action', 'enquiry_process_form_data');

    fetch(ajaxUrl, {
      method: 'POST',
      body: formData,
    })
      .then((response) => {
        response.json().then((response) => {
          blockElement.classList.remove('enquiry-form--processing');
          blockElement.classList.add(response.success ? 'enquiry-form--success' : 'enquiry-form--failure');
        });
      })
      .catch((response) => {
        blockElement.classList.remove('enquiry-form--processing');
        blockElement.classList.add('enquiry-form--failure');
      });
  });
};

const initEnquiryResultsExpanders = () => {
  const blockClass = 'enquiry-results';
  const expanders = document.querySelectorAll('.js-row-expander');

  const handleExpanderClick = (e) => {
    const row = e.target.closest(`.${blockClass}__grid-row`);
    const details = row.querySelector(`.${blockClass}__details`);

    if (details.classList.contains(`${blockClass}__details--open`)) {
      details.classList.remove(`${blockClass}__details--open`);
      return;
    }

    const rowId = row.getAttribute('data-row-id');
    details.classList.add(`${blockClass}__details--open`);

    fetch(`${ajaxUrl}?action=enquiry_get_form_record&id=${rowId}`, {
      method: 'GET',
    })
      .then((response) => {
        response.json().then((response) => {
          details.classList.add(`${blockClass}__details--loaded`);
          details.querySelector(`.${blockClass}__details-content`).innerText = response.data;
        });
      });
  };

  expanders.forEach((item) => item.addEventListener('click', handleExpanderClick));
};

const initEnquiryResultsPageLinks = () => {
  const blockClass = 'enquiry-results';
  const pageLinks = document.querySelectorAll('.js-page-link');

  const handlePageLinkClick = (e) => {
	  const clickedLink = e.target;
	  const pagination = clickedLink.closest(`.${blockClass}__pagination`);
	  const pageNumber = clickedLink.getAttribute('data-page-number');

	  const activeClassName = `${blockClass}__page-link--active`;

	  pageLinks.forEach((item) => {
      item.classList.remove(activeClassName);
	  });
	  clickedLink.classList.add(activeClassName);

	  pagination.classList.add(`${blockClass}__pagination--loading`);

	  fetch(`${ajaxUrl}?action=enquiry_get_form_data&page_number=${pageNumber}`, {
      method: 'GET',
	  })
      .then((response) => {
		  response.json().then((response) => {
          pagination.classList.remove(`${blockClass}__pagination--loading`);
          renderEnquiryResultsRows(response);
		  });
      });
  };

  pageLinks.forEach((item) => item.addEventListener('click', handlePageLinkClick));
};

const renderEnquiryResultsRows = (rows) => {
  const blockClass = 'enquiry-results';
  const rowsContainer = document.querySelector('.js-grid-rows');

  const rowTemplate = document.querySelector('.js-grid-row').cloneNode(true);
  rowTemplate.querySelector(`.${blockClass}__details`).classList.remove(`${blockClass}__details--open`);
  rowTemplate.querySelector(`.${blockClass}__details`).classList.remove(`${blockClass}__details--loaded`);

  const newRows = document.createElement('div');
  newRows.style.display = 'contents';

  rows.data.forEach((row) => {
    const clone = rowTemplate.cloneNode(true);
    clone.setAttribute('data-row-id', row.id);
    clone.querySelector('.js-first-name').innerText = row.first_name;
    clone.querySelector('.js-last-name').innerText = row.last_name;
    clone.querySelector('.js-subject').innerText = row.subject;
    clone.querySelector('.js-email').innerText = row.email;
    newRows.appendChild(clone);
  });

  rowsContainer.innerHTML = '';
  rowsContainer.appendChild(newRows);
  initEnquiryResultsExpanders();
};

document.addEventListener('DOMContentLoaded', () => {
  initEnquiryForm();

  initEnquiryResultsExpanders();
  initEnquiryResultsPageLinks();
});
