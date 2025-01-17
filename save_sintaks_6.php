<?php
require_once('../../config.php');
require_once($CFG->dirroot.'/mod/pjblsinawang/lib.php');
require_login();

$cmid = required_param('cmid', PARAM_INT);
$groupid = required_param('groupid', PARAM_INT);
$courseid = required_param('courseid', PARAM_INT);
$presentation_date = required_param('presentation_date', PARAM_INT);
$presenter = optional_param('presenter', '', PARAM_TEXT);
$feedback = optional_param('feedback', '', PARAM_TEXT);
$userid = $USER->id;  // Mendapatkan ID pengguna yang sedang login

// Konversi tanggal dan waktu presentasi menjadi format timestamp
$presentation_timestamp = strtotime($presentation_date);

// Cek apakah data sudah ada
$sql = "SELECT * FROM {pjblsinawang_sintaks_enam}
        WHERE cmid = :cmid AND groupid = :groupid AND courseid = :courseid";
$params = ['cmid' => $cmid, 'groupid' => $groupid, 'courseid' => $courseid];
$existing_data = $DB->get_record_sql($sql, $params);

// Update atau Insert data
if ($existing_data) {
    // Update data
    $existing_data->presentation_date = $presentation_timestamp;
    $existing_data->presenter = $presenter;
    $existing_data->feedback = $feedback;
    $DB->update_record('pjblsinawang_sintaks_enam', $existing_data);
} else {
    // Insert data baru
    $record = new stdClass();
    $record->cmid = $cmid;
    $record->courseid = $courseid;
    $record->groupid = $groupid;
    $record->userid = $userid;
    $record->presentation_date = $presentation_timestamp;
    $record->presenter = $presenter;
    $record->feedback = $feedback;
    $DB->insert_record('pjblsinawang_sintaks_enam', $record);
}

// Mengirim respons JSON
echo json_encode(['success' => true]);
?>
