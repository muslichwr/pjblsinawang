<?php
require_once('../../config.php');
require_once($CFG->dirroot.'/mod/pjblsinawang/lib.php');
require_login();

$cmid = required_param('cmid', PARAM_INT);
$groupid = required_param('groupid', PARAM_INT);
$courseid = required_param('courseid', PARAM_INT);
$status = required_param('status', PARAM_TEXT);
$userid = $USER->id;  // Mendapatkan ID pengguna yang sedang login

// Cek apakah file proyek diunggah
$file_proyek_id = null;
if (!empty($_FILES['file_proyek']['name'])) {
    // Proses unggahan file proyek
    $file_manager = get_file_storage();
    $context = context_module::instance($cmid);
    $file_record = array(
        'contextid' => $context->id,
        'component' => 'mod_pjblsinawang',
        'filearea' => 'file_pjblsinawang_lima_proyek',
        'itemid' => 0, // ID item (untuk file yang tidak terkait dengan entitas tertentu)
        'filepath' => '/',
        'filename' => $_FILES['file_proyek']['name']
    );
    $file_proyek = $file_manager->create_file_from_pathname($file_record, $_FILES['file_proyek']['tmp_name']);
    $file_proyek_id = $file_proyek->get_id();  // Mendapatkan ID file yang diunggah
}

// Cek apakah file laporan diunggah
$file_laporan_id = null;
if (!empty($_FILES['file_laporan']['name'])) {
    // Proses unggahan file laporan
    $file_manager = get_file_storage();
    $context = context_module::instance($cmid);
    $file_record = array(
        'contextid' => $context->id,
        'component' => 'mod_pjblsinawang',
        'filearea' => 'file_pjblsinawang_lima_laporan',
        'itemid' => 0,
        'filepath' => '/',
        'filename' => $_FILES['file_laporan']['name']
    );
    $file_laporan = $file_manager->create_file_from_pathname($file_record, $_FILES['file_laporan']['tmp_name']);
    $file_laporan_id = $file_laporan->get_id();  // Mendapatkan ID file yang diunggah
}

// Cek apakah data sudah ada
$sql = "SELECT * FROM {pjblsinawang_sintaks_lima}
        WHERE cmid = :cmid AND groupid = :groupid AND courseid = :courseid";
$params = ['cmid' => $cmid, 'groupid' => $groupid, 'courseid' => $courseid];
$existing_data = $DB->get_record_sql($sql, $params);

// Update atau Insert data
if ($existing_data) {
    // Update data
    $existing_data->status = $status;
    $existing_data->file_proyek_id = $file_proyek_id ?: $existing_data->file_proyek_id;
    $existing_data->file_laporan_id = $file_laporan_id ?: $existing_data->file_laporan_id;
    $existing_data->project_submission_date = time();
    $DB->update_record('pjblsinawang_sintaks_lima', $existing_data);
} else {
    // Insert data baru
    $record = new stdClass();
    $record->cmid = $cmid;
    $record->courseid = $courseid;
    $record->groupid = $groupid;
    $record->userid = $userid;
    $record->status = $status;
    $record->file_proyek_id = $file_proyek_id;
    $record->file_laporan_id = $file_laporan_id;
    $record->project_submission_date = time();
    $DB->insert_record('pjblsinawang_sintaks_lima', $record);
}

// Mengirim respons JSON
echo json_encode(['success' => true]);
?>
