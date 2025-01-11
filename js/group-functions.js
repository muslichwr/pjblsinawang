$(document).ready(function() {
    $('.details-button').click(function() {
        var groupId = $(this).data('groupid');
        var detailsDiv = $('#group-details-' + groupId);
        if (detailsDiv.is(':empty')) {
            $.ajax({
                url: '/mod/pjblsinawang/group_details_ajax.php',
                type: 'GET',
                data: {groupid: groupId},
                success: function(response) {
                    detailsDiv.html(response);
                    detailsDiv.show();
                },
                error: function() {
                    detailsDiv.html('<p>Error loading details.</p>');
                }
            });
        } else {
            detailsDiv.toggle(); // Toggle visibility if already loaded
        }
    });
});