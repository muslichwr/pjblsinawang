<?php
require_once('../../config.php');
require_login();

$groupid = required_param('groupid', PARAM_INT);
$group_members = groups_get_members($groupid, 'u.id, u.firstname, u.lastname', 'lastname ASC');

$output = '<ul class="list-group">';
foreach ($group_members as $member) {
    $output .= '<li class="list-group-item">' . format_string($member->firstname . ' ' . $member->lastname) . '</li>';
}
$output .= '</ul>';

echo $output;