<?php
require_once('../../config.php');
require_once($CFG->dirroot.'/mod/pjblsinawang/lib.php');
require_login();

$cmid = required_param('cmid', PARAM_INT);
$groupid = required_param('groupid', PARAM_INT);
$courseid = required_param('courseid', PARAM_INT);
$evaluation_date = required_param('evaluation_date', PARAM_INT);
$evaluation_result = optional_param('evaluation_result', '', PARAM_TEXT);
$revisions_required = optional_param('revisions_required', '', PARAM_TEXT);
$userid = $USER->id;  // Mendapatkan ID pengguna yang sedang login

// Konversi tanggal evaluasi menjadi format timestamp
$evaluation_timestamp = strtotime($evaluation_date);

// Cek apakah data sudah ada
$sql = "SELECT * FROM {pjblsinawang_sintaks_tujuh}
        WHERE cmid = :cmid AND groupid = :groupid AND courseid = :courseid";
$params = ['cmid' => $cmid, 'groupid' => $groupid, 'courseid' => $courseid];
$existing_data = $DB->get_record_sql($sql, $params);

// Update atau Insert data
if ($existing_data) {
    // Update data
    $existing_data->evaluation_date = $evaluation_timestamp;
    $existing_data->evaluation_result = $evaluation_result;
    $existing_data->revisions_required = $revisions_required;
    $DB->update_record('pjblsinawang_sintaks_tujuh', $existing_data);
} else {
    // Insert data baru
    $record = new stdClass();
    $record->cmid = $cmid;
    $record->courseid = $courseid;
    $record->groupid = $groupid;
    $record->userid = $userid;
    $record->evaluation_date = $evaluation_timestamp;
    $record->evaluation_result = $evaluation_result;
    $record->revisions_required = $revisions_required;
    $DB->insert_record('pjblsinawang_sintaks_tujuh', $record);
}

// Mengirim respons JSON
echo json_encode(['success' => true]);
?>
