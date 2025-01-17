<?php
require_once('../../config.php');
require_once($CFG->dirroot.'/mod/pjblsinawang/lib.php');
require_login();

$groupid = required_param('groupid', PARAM_INT);

// Ambil data anggota grup
$members = groups_get_members($groupid);

// Output data anggota
$response = array();
if ($members) {
    foreach ($members as $member) {
        $response['members'][] = array('name' => fullname($member));
    }
} else {
    $response['members'] = array();
}

echo json_encode($response);
