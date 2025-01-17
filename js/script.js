import { loadGroupMembers } from './groupMembers.js';
import { loadSintaksForm, submitSintaksForm } from './sintaks.js';

// Definisikan URL untuk form Sintaks dan pengiriman data
const showSintaksFormUrls = [
    showSintaksFormUrl1, showSintaksFormUrl2, showSintaksFormUrl3,
    showSintaksFormUrl4, showSintaksFormUrl5, showSintaksFormUrl6,
    showSintaksFormUrl7, showSintaksFormUrl8
];
const submitSintaksUrls = [
    submitSintaksFormUrl1, submitSintaksFormUrl2, submitSintaksFormUrl3,
    submitSintaksFormUrl4, submitSintaksFormUrl5, submitSintaksFormUrl6,
    submitSintaksFormUrl7, submitSintaksFormUrl8
];

// Event listener untuk tombol "Pilih"
document.addEventListener('DOMContentLoaded', function() {
    const showMembersButton = document.getElementById('showMembers');
    const showSintaksBtns = document.getElementById('showSintaksBtns');
    
    if (showMembersButton) {
        showMembersButton.addEventListener('click', function() {
            const groupId = document.getElementById('groupSelect').value;
            if (groupId !== 'Select a Group') {
                loadGroupMembers(groupId, getGroupMembersUrl);
                showSintaksBtns.style.display = 'block';  // Menampilkan tombol Sintaks setelah memilih grup
            }
        });
    }

    // Event listeners untuk tombol Sintaks 1 hingga 8
    for (let i = 1; i <= 8; i++) {
        const showSintaksButton = document.getElementById(`showSintaks${i}`);
        if (showSintaksButton) {
            showSintaksButton.addEventListener('click', function() {
                const groupId = document.getElementById('groupSelect').value;
                const cmid = document.getElementById('cmid').value;
                const courseid = document.getElementById('courseid').value;
                loadSintaksForm(i, groupId, cmid, courseid, showSintaksFormUrls);
            });
        }
    }

    // Event listener untuk submit form Sintaks
    function attachSubmitListeners() {
        for (let i = 1; i <= 8; i++) {
            const formId = `formSintaks${i}Submit`;
            const form = document.getElementById(formId);
            if (form) {
                form.addEventListener('submit', function(event) {
                    event.preventDefault();
                    submitSintaksForm(i, formId, submitSintaksUrls);
                });
            }
        }
    }

    attachSubmitListeners();
});