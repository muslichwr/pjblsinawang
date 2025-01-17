export function loadSintaksForm(i, groupId, cmid, courseid, showSintaksFormUrls) {
    const url = showSintaksFormUrls[i - 1]; // URL untuk Sintaks sesuai index
    fetch(`${url}?groupid=${groupId}&cmid=${cmid}&courseid=${courseid}`)
        .then(response => response.text())
        .then(formHtml => {
            const formContainer = document.getElementById('formSintaks');
            formContainer.innerHTML = formHtml;
            formContainer.style.display = 'block';
        })
        .catch(error => console.error('Error loading Sintaks form:', error));
}

export function submitSintaksForm(i, formId, submitSintaksUrls) {
    const url = submitSintaksUrls[i - 1]; // URL untuk submit Sintaks sesuai index
    const form = document.getElementById(formId);
    const formData = new FormData(form);

    fetch(url, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Form submitted successfully!');
        } else {
            alert('There was an error submitting the form.');
        }
    })
    .catch(error => console.error('Error submitting form:', error));
}
