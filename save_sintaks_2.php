<?php
require_once('../../config.php');
require_once($CFG->dirroot.'/mod/pjblsinawang/lib.php');
require_login();

$cmid = required_param('cmid', PARAM_INT);
$groupid = required_param('groupid', PARAM_INT);
$courseid = required_param('courseid', PARAM_INT);
$project_title = required_param('project_title', PARAM_TEXT);
$project_schedule = required_param('project_schedule', PARAM_TEXT);
$tasks = required_param('tasks', PARAM_RAW); // Tugas anggota dalam format array
$status = optional_param('status', 'incomplete', PARAM_TEXT);
$feedback = optional_param('feedback', '', PARAM_TEXT);

// Cek apakah sudah ada data sintaks sebelumnya
$sql = "SELECT * FROM {pjblsinawang_sintaks_dua}
        WHERE cmid = :cmid AND groupid = :groupid AND courseid = :courseid";
$params = ['cmid' => $cmid, 'groupid' => $groupid, 'courseid' => $courseid];
$existing_data = $DB->get_record_sql($sql, $params);

if ($existing_data) {
    // Update data jika sudah ada
    $existing_data->project_title = $project_title;
    $existing_data->project_schedule = $project_schedule;
    $existing_data->tasks = json_encode($tasks); // Menyimpan tugas anggota dalam format JSON
    $existing_data->status = $status;  // Update status
    $existing_data->feedback = $feedback;  // Update feedback
    $DB->update_record('pjblsinawang_sintaks_dua', $existing_data);
} else {
    // Insert data baru jika belum ada
    $record = new stdClass();
    $record->cmid = $cmid;
    $record->groupid = $groupid;
    $record->courseid = $courseid;
    $record->project_title = $project_title;
    $record->project_schedule = $project_schedule;
    $record->tasks = json_encode($tasks);  // Menyimpan tugas anggota dalam format JSON
    $record->status = $status;
    $record->feedback = $feedback;
    
    $DB->insert_record('pjblsinawang_sintaks_dua', $record);
}

// Mengirim respons JSON untuk memberi tahu client bahwa data berhasil disimpan
echo json_encode(['success' => true]);
?>
