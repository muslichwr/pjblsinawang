export function loadGroupMembers(groupId, url) {
    fetch(`${url}?groupid=${groupId}`)
        .then(response => response.json())
        .then(data => {
            // Pastikan data adalah array sebelum menggunakan forEach
            if (Array.isArray(data)) {
                const membersList = document.getElementById('membersList');
                membersList.innerHTML = ''; // Kosongkan konten sebelumnya
                data.forEach(member => {
                    const memberElement = document.createElement('p');
                    memberElement.textContent = member.name;
                    membersList.appendChild(memberElement);
                });
                membersList.style.display = 'block';
            } else {
                console.error('Data returned is not an array:', data);
            }
        })
        .catch(error => {
            console.error('Error loading group members:', error);
        });
}
