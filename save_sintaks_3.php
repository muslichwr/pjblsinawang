<?php
require_once('../../config.php');
require_once($CFG->dirroot.'/mod/pjblsinawang/lib.php');
require_login();

$cmid = required_param('cmid', PARAM_INT);
$groupid = required_param('groupid', PARAM_INT);
$courseid = required_param('courseid', PARAM_INT);
$task_name = required_param('task_name', PARAM_TEXT);
$assigned_to = required_param('assigned_to', PARAM_INT);
$due_date = required_param('due_date', PARAM_INT);  // Tanggal dalam format UNIX timestamp
$status = optional_param('status', 'incomplete', PARAM_TEXT);
$userid = $USER->id;  // Mendapatkan ID pengguna yang sedang login

// Mengonversi tanggal yang diterima dari format 'YYYY-MM-DD' ke UNIX timestamp
$due_date = strtotime($due_date);

// Cek apakah sudah ada data sintaks 3 sebelumnya untuk kelompok ini
$sql = "SELECT * FROM {pjblsinawang_sintaks_tiga}
        WHERE cmid = :cmid AND groupid = :groupid AND courseid = :courseid";
$params = ['cmid' => $cmid, 'groupid' => $groupid, 'courseid' => $courseid];
$existing_data = $DB->get_record_sql($sql, $params);

if ($existing_data) {
    // Update data jika sudah ada
    $existing_data->task_name = $task_name;
    $existing_data->assigned_to = $assigned_to;
    $existing_data->due_date = $due_date;
    $existing_data->status = $status;  // Update status
    $existing_data->userid = $userid;  // Menyimpan ID pengguna yang terakhir mengubah data
    
    $DB->update_record('pjblsinawang_sintaks_tiga', $existing_data);
} else {
    // Insert data baru jika belum ada
    $record = new stdClass();
    $record->cmid = $cmid;
    $record->groupid = $groupid;
    $record->courseid = $courseid;
    $record->task_name = $task_name;
    $record->assigned_to = $assigned_to;
    $record->due_date = $due_date;
    $record->status = $status;
    $record->userid = $userid;  // Menyimpan ID pengguna yang pertama kali mengirimkan data
    
    $DB->insert_record('pjblsinawang_sintaks_tiga', $record);
}

// Mengirim respons JSON untuk memberi tahu client bahwa data berhasil disimpan
echo json_encode(['success' => true]);
?>
