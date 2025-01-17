<?php
require_once('../../config.php');
require_once($CFG->dirroot.'/mod/pjblsinawang/lib.php');
require_login();

$cmid = required_param('cmid', PARAM_INT);
$groupid = required_param('groupid', PARAM_INT);
$courseid = required_param('courseid', PARAM_INT);
$reflection_date = required_param('reflection_date', PARAM_INT);
$reflection_notes = optional_param('reflection_notes', '', PARAM_TEXT);
$userid = $USER->id;  // Mendapatkan ID pengguna yang sedang login

// Konversi tanggal refleksi menjadi format timestamp
$reflection_timestamp = strtotime($reflection_date);

// Cek apakah data sudah ada
$sql = "SELECT * FROM {pjblsinawang_sintaks_delapan}
        WHERE cmid = :cmid AND groupid = :groupid AND courseid = :courseid";
$params = ['cmid' => $cmid, 'groupid' => $groupid, 'courseid' => $courseid];
$existing_data = $DB->get_record_sql($sql, $params);

// Update atau Insert data
if ($existing_data) {
    // Update data
    $existing_data->reflection_date = $reflection_timestamp;
    $existing_data->reflection_notes = $reflection_notes;
    $DB->update_record('pjblsinawang_sintaks_delapan', $existing_data);
} else {
    // Insert data baru
    $record = new stdClass();
    $record->cmid = $cmid;
    $record->courseid = $courseid;
    $record->groupid = $groupid;
    $record->userid = $userid;
    $record->reflection_date = $reflection_timestamp;
    $record->reflection_notes = $reflection_notes;
    $DB->insert_record('pjblsinawang_sintaks_delapan', $record);
}

// Mengirim respons JSON
echo json_encode(['success' => true]);
?>
