document.getElementById('showMembers').addEventListener('click', function() {
    var groupId = document.getElementById('groupSelect').value;
    if (groupId !== 'Select a Group') {
        // Menutup form Sintaks yang terbuka sebelum menampilkan anggota
        document.getElementById('formSintaks').style.display = 'none';
        
        var membersList = document.getElementById('membersList');
        membersList.innerHTML = '<div class="d-flex justify-content-center"><div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div></div>';
        membersList.style.display = 'block';

        fetch(getGroupMembersUrl + '?groupid=' + groupId)
        .then(response => response.json())
        .then(data => {
            if (data.members.length > 0) {
                var list = '<ul class="list-group">';
                data.members.forEach(member => {
                    list += '<li class="list-group-item">' + member.name + '</li>';
                });
                list += '</ul>';
                membersList.innerHTML = list;
                
                // Menampilkan tombol Sintaks setelah anggota berhasil dimuat
                document.getElementById('showSintaksBtns').style.display = 'block';
            } else {
                membersList.innerHTML = '<p>No members found.</p>';
            }
        })
        .catch(error => {
            membersList.innerHTML = '<p>Error loading members.</p>';
        });
    }
});

// Event listener untuk tombol Sintaks 1, 2, 3
document.getElementById('showSintaks1').addEventListener('click', function() {
    loadSintaksForm(1);
});

document.getElementById('showSintaks2').addEventListener('click', function() {
    loadSintaksForm(2);
});

document.getElementById('showSintaks3').addEventListener('click', function() {
    loadSintaksForm(3);
});

document.getElementById('showSintaks4').addEventListener('click', function() {
    loadSintaksForm(4);
});

document.getElementById('showSintaks5').addEventListener('click', function() {
    loadSintaksForm(5);
});

document.getElementById('showSintaks6').addEventListener('click', function() {
    loadSintaksForm(6);
});

document.getElementById('showSintaks7').addEventListener('click', function() {
    loadSintaksForm(7);
});

document.getElementById('showSintaks8').addEventListener('click', function() {
    loadSintaksForm(8);
});

// Fungsi untuk memuat form Sintaks berdasarkan nomor sintaks yang dipilih
function loadSintaksForm(sintaksNumber) {
    var groupId = document.getElementById('groupSelect').value;
    var cmid = document.getElementById('cmid').value;  
    var courseid = document.getElementById('courseid').value;  
    var formDiv = document.getElementById('formSintaks');
    formDiv.innerHTML = '<div class="d-flex justify-content-center"><div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div></div>';

    var url = '';

    // Menentukan URL berdasarkan sintaks yang dipilih
    if (sintaksNumber === 1) {
        url = showSintaksFormUrl1;
    } else if (sintaksNumber === 2) {
        url = showSintaksFormUrl2;
    } else if (sintaksNumber === 3) {
        url = showSintaksFormUrl3;
    } else if (sintaksNumber === 4) {
        url = showSintaksFormUrl4;
    } else if (sintaksNumber === 5) {
        url = showSintaksFormUrl5;
    } else if (sintaksNumber === 6) {
        url = showSintaksFormUrl6;
    } else if (sintaksNumber === 7) {
        url = showSintaksFormUrl7;
    } else if (sintaksNumber === 8) {
        url = showSintaksFormUrl8;
    }

    // Mengambil form dari server
    fetch(url + '?groupid=' + groupId + '&cmid=' + cmid + '&courseid=' + courseid)
    .then(response => response.text())
    .then(html => {
        formDiv.innerHTML = html;
    })
    .catch(error => {
        formDiv.innerHTML = '<p>Error loading form.</p>';
    });

    formDiv.style.display = 'block';
}

// Fungsi untuk mengirimkan form Sintaks
function submitSintaksForm(sintaksNumber) {
    var form = document.getElementById('formSintaks' + sintaksNumber + 'Submit'); // Menyesuaikan ID form berdasarkan sintaksNumber
    var formData = new FormData(form);
    var submitButton = form.querySelector('button[type="submit"]');
    submitButton.disabled = true;  // Menonaktifkan tombol submit untuk mencegah klik ganda

    var submitUrl = '';  // Menentukan URL pengiriman berdasarkan nomor sintaks

    if (sintaksNumber === 1) {
        submitUrl = submitSintaksFormUrl1;  // URL untuk Sintaks 1
    } else if (sintaksNumber === 2) {
        submitUrl = submitSintaksFormUrl2;  // URL untuk Sintaks 2
    } else if (sintaksNumber === 3) {
        submitUrl = submitSintaksFormUrl3;  // URL untuk Sintaks 3
    } else if (sintaksNumber === 4) {
        submitUrl = submitSintaksFormUrl4;  // URL untuk Sintaks 4
    } else if (sintaksNumber === 5) {
        submitUrl = submitSintaksFormUrl5;  // URL untuk Sintaks 5
    } else if (sintaksNumber === 6) {
        submitUrl = submitSintaksFormUrl6;  // URL untuk Sintaks 6
    } else if (sintaksNumber === 7) {
        submitUrl = submitSintaksFormUrl7;  // URL untuk Sintaks 7
    } else if (sintaksNumber === 8) {
        submitUrl = submitSintaksFormUrl8;  // URL untuk Sintaks 8
    }

    // Mengirim data ke server menggunakan fetch (AJAX)
    fetch(submitUrl, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Form submitted successfully!', 'success');
        } else {
            showNotification('Error submitting form.', 'danger');
        }
        submitButton.disabled = false;
    })
    .catch(error => {
        showNotification('Error submitting form.', 'danger');
        submitButton.disabled = false;
    });
}

// Fungsi untuk menampilkan notifikasi
function showNotification(message, type) {
    var notificationContainer = document.getElementById('notificationContainer');
    var notification = document.createElement('div');
    notification.classList.add('alert', 'alert-' + type);
    notification.textContent = message;
    notificationContainer.appendChild(notification);

    // Menghapus notifikasi setelah beberapa detik
    setTimeout(function() {
        notification.remove();
    }, 5000);
}