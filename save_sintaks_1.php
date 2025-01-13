<?php
require_once('../../config.php');
require_once($CFG->dirroot.'/mod/pjblsinawang/lib.php');
require_login();

$cmid = required_param('cmid', PARAM_INT);
$groupid = required_param('groupid', PARAM_INT);
$courseid = required_param('courseid', PARAM_INT);
$orientasi_masalah = required_param('orientasi_masalah', PARAM_TEXT);
$rumusan_masalah = required_param('rumusan_masalah', PARAM_TEXT);
$indikator = required_param('indikator', PARAM_TEXT);
$analisis = required_param('analisis', PARAM_TEXT);
$status = optional_param('status', 'incomplete', PARAM_TEXT);
$feedback = optional_param('feedback', '', PARAM_TEXT);

// Cek apakah sudah ada data
$sql = "SELECT * FROM {pjblsinawang_sintaks_satu}
        WHERE cmid = :cmid AND groupid = :groupid AND courseid = :courseid";
$params = ['cmid' => $cmid, 'groupid' => $groupid, 'courseid' => $courseid];
$existing_data = $DB->get_record_sql($sql, $params);

if ($existing_data) {
    // Update data jika sudah ada
    $existing_data->orientasi_masalah = $orientasi_masalah;
    $existing_data->rumusan_masalah = $rumusan_masalah;
    $existing_data->indikator = $indikator;
    $existing_data->analisis = $analisis;
    $existing_data->status = $status;  // Update status
    $existing_data->feedback = $feedback;  // Update feedback
    $DB->update_record('pjblsinawang_sintaks_satu', $existing_data);
} else {
    // Insert data baru jika belum ada
    $record = new stdClass();
    $record->cmid = $cmid;
    $record->groupid = $groupid;
    $record->courseid = $courseid;
    $record->orientasi_masalah = $orientasi_masalah;
    $record->rumusan_masalah = $rumusan_masalah;
    $record->indikator = $indikator;
    $record->analisis = $analisis;
    $record->status = $status;
    $record->feedback = $feedback;
    
    $DB->insert_record('pjblsinawang_sintaks_satu', $record);
}

// Mengirim respons JSON untuk memberi tahu client bahwa data berhasil disimpan
echo json_encode(['success' => true]);
?>
