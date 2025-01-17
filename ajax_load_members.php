<?php
require_once('../../config.php');
require_once($CFG->dirroot.'/mod/pjblsinawang/lib.php');
require_login();

$groupid = required_param('groupid', PARAM_INT);

// Ambil anggota grup berdasarkan groupid
$members = groups_get_members($groupid);

$response = [];
foreach ($members as $member) {
    $response[] = ['name' => fullname($member)]; // Mengambil nama lengkap anggota
}

header('Content-Type: application/json');
echo json_encode($response);