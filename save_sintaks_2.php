<?php
require_once('../../config.php');
require_once($CFG->dirroot.'/mod/pjblsinawang/lib.php');
require_login();

$cmid = required_param('cmid', PARAM_INT);
$groupid = required_param('groupid', PARAM_INT);
$courseid = required_param('courseid', PARAM_INT);
$project_name = required_param('project_name', PARAM_TEXT);
$start_date = required_param('start_date', PARAM_INT);  // Tanggal dalam format UNIX timestamp
$end_date = required_param('end_date', PARAM_INT);  // Tanggal dalam format UNIX timestamp
$status = optional_param('status', 'incomplete', PARAM_TEXT);
$userid = $USER->id;  // Mendapatkan ID pengguna yang sedang login

// Mengonversi tanggal yang diterima dari format 'YYYY-MM-DD' ke UNIX timestamp
$start_date = strtotime($start_date);
$end_date = strtotime($end_date);

// Cek apakah sudah ada data sintaks 2 sebelumnya untuk kelompok ini
$sql = "SELECT * FROM {pjblsinawang_sintaks_dua}
        WHERE cmid = :cmid AND groupid = :groupid AND courseid = :courseid";
$params = ['cmid' => $cmid, 'groupid' => $groupid, 'courseid' => $courseid];
$existing_data = $DB->get_record_sql($sql, $params);

if ($existing_data) {
    // Update data jika sudah ada
    $existing_data->project_name = $project_name;
    $existing_data->start_date = $start_date;
    $existing_data->end_date = $end_date;
    $existing_data->status = $status;  // Update status
    $existing_data->userid = $userid;  // Menyimpan ID pengguna yang terakhir mengubah data
    
    $DB->update_record('pjblsinawang_sintaks_dua', $existing_data);
} else {
    // Insert data baru jika belum ada
    $record = new stdClass();
    $record->cmid = $cmid;
    $record->groupid = $groupid;
    $record->courseid = $courseid;
    $record->project_name = $project_name;
    $record->start_date = $start_date;
    $record->end_date = $end_date;
    $record->status = $status;
    $record->userid = $userid;  // Menyimpan ID pengguna yang pertama kali mengirimkan data
    
    $DB->insert_record('pjblsinawang_sintaks_dua', $record);
}

// Mengirim respons JSON untuk memberi tahu client bahwa data berhasil disimpan
echo json_encode(['success' => true]);
?>
