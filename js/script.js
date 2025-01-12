document.getElementById('showMembers').addEventListener('click', function() {
    var groupId = document.getElementById('groupSelect').value;
    if (groupId !== 'Select a Group') {
        var membersList = document.getElementById('membersList');
        membersList.innerHTML = '<div class="d-flex justify-content-center"><div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div></div>';

        membersList.style.display = 'block';
        membersList.style.maxHeight = '0';
        membersList.style.transition = 'max-height 0.5s ease-out';
        setTimeout(function() {
            membersList.style.maxHeight = '500px';
        }, 50);

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
                document.getElementById('showSintaksForm').style.display = 'block';
            } else {
                membersList.innerHTML = '<p>No members found.</p>';
            }
        })
        .catch(error => {
            membersList.innerHTML = '<p>Error loading members.</p>';
        });
    }
});

document.getElementById('showSintaksForm').addEventListener('click', function() {
    var groupId = document.getElementById('groupSelect').value;
    var cmid = document.getElementById('cmid').value;  // Ambil nilai cmid dari input tersembunyi
    var formDiv = document.getElementById('formSintaks1');
    formDiv.innerHTML = '<div class="d-flex justify-content-center"><div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div></div>';

    fetch(showSintaksFormUrl + '?groupid=' + groupId + '&id=' + cmid)  // Tambahkan &id=cmid
    .then(response => response.text())
    .then(html => {
        formDiv.innerHTML = html;
    })
    .catch(error => {
        formDiv.innerHTML = '<p>Error loading form.</p>';
    });

    formDiv.style.display = 'block';
});