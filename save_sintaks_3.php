<?php
require_once('../../config.php');
require_once($CFG->dirroot.'/mod/pjblsinawang/lib.php');
require_login();

$cmid = required_param('cmid', PARAM_INT);
$groupid = required_param('groupid', PARAM_INT);
$courseid = required_param('courseid', PARAM_INT);

// Mengambil semua task_name yang dikirim dari form
$task_names = array();
foreach ($_POST as $key => $value) {
    if (strpos($key, 'task_name_') === 0) {  // Cek apakah key dimulai dengan task_name_
        $user_id = substr($key, strlen('task_name_'));  // Ambil ID pengguna dari nama field
        $task_names[$user_id] = $value;  // Masukkan ke array dengan ID pengguna sebagai key
    }
}

$status = optional_param('status', 'incomplete', PARAM_TEXT);
$userid = $USER->id;  // Mendapatkan ID pengguna yang sedang login

// Cek apakah sudah ada data sintaks 3 sebelumnya untuk kelompok ini
$sql = "SELECT * FROM {pjblsinawang_sintaks_tiga}
        WHERE cmid = :cmid AND groupid = :groupid AND courseid = :courseid";
$params = ['cmid' => $cmid, 'groupid' => $groupid, 'courseid' => $courseid];
$existing_data = $DB->get_record_sql($sql, $params);

if ($existing_data) {
    // Update data jika sudah ada
    $existing_data->task_name = json_encode($task_names); // Menyimpan data task_name sebagai JSON
    $existing_data->status = $status;  // Update status
    $existing_data->userid = $userid;  // Menyimpan ID pengguna yang terakhir mengubah data
    
    // Update record di database
    $DB->update_record('pjblsinawang_sintaks_tiga', $existing_data);
} else {
    // Insert data baru jika belum ada
    $record = new stdClass();
    $record->cmid = $cmid;
    $record->groupid = $groupid;
    $record->courseid = $courseid;
    $record->task_name = json_encode($task_names);  // Menyimpan data task_name sebagai JSON
    $record->status = $status;
    $record->userid = $userid;  // Menyimpan ID pengguna yang pertama kali mengirimkan data
    
    // Insert record baru di database
    $DB->insert_record('pjblsinawang_sintaks_tiga', $record);
}

// Mengirim respons JSON untuk memberi tahu client bahwa data berhasil disimpan
echo json_encode(['success' => true]);
?>
