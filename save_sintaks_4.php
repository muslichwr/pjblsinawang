<?php
require_once('../../config.php');
require_once($CFG->dirroot.'/mod/pjblsinawang/lib.php');
require_once($CFG->libdir . '/filelib.php');

require_login();

$cmid = required_param('cmid', PARAM_INT);
$groupid = required_param('groupid', PARAM_INT);
$courseid = required_param('courseid', PARAM_INT);
$status = required_param('status', PARAM_TEXT);
$comments = optional_param('comments', '', PARAM_TEXT);
$userid = $USER->id;  // Mendapatkan ID pengguna yang sedang login

// Mendapatkan draft item ID jika sudah ada, jika belum, buat baru
$draft_item_id = file_get_submitted_draft_itemid('file_empat');
file_prepare_draft_area($draft_item_id, context_module::instance($cmid)->id, 'mod_pjblsinawang', 'file_pjblsinawang_empat', 0, array('subdirs' => 0));

// Cek apakah file diunggah
$file_empat_id = null;
if (!empty($_FILES['file_empat']['name'])) {
    // Simpan file ke dalam draft area
    $file_manager = get_file_storage();
    $context = context_module::instance($cmid);

    // Proses unggah file ke dalam draft area
    $file_record = array(
        'contextid' => $context->id,
        'component' => 'mod_pjblsinawang',
        'filearea' => 'file_pjblsinawang_empat', // filearea yang sesuai
        'itemid' => $draft_item_id, // Gunakan draft item ID
        'filepath' => '/',
        'filename' => $_FILES['file_empat']['name']
    );

    // Unggah file ke draft area
    $file_empat = $file_manager->create_file_from_pathname($file_record, $_FILES['file_empat']['tmp_name']);
    $file_empat_id = $file_empat->get_id();  // Mendapatkan ID file yang diunggah ke draft area
}

// Cek apakah sudah ada data sintaks 4 sebelumnya
$sql = "SELECT * FROM {pjblsinawang_sintaks_empat}
        WHERE cmid = :cmid AND groupid = :groupid AND courseid = :courseid";
$params = ['cmid' => $cmid, 'groupid' => $groupid, 'courseid' => $courseid];
$existing_data = $DB->get_record_sql($sql, $params);

if ($existing_data) {
    // Update data jika sudah ada
    $existing_data->status = $status;
    $existing_data->comments = $comments;
    if ($file_empat_id) {
        $existing_data->file_empat_id = $file_empat_id;  // Update file ID jika ada file baru
    }
    $existing_data->userid = $userid;  // Menyimpan ID pengguna yang terakhir mengubah data
    
    $DB->update_record('pjblsinawang_sintaks_empat', $existing_data);
} else {
    // Insert data baru jika belum ada
    $record = new stdClass();
    $record->cmid = $cmid;
    $record->groupid = $groupid;
    $record->courseid = $courseid;
    $record->status = $status;
    $record->comments = $comments;
    if ($file_empat_id) {
        $record->file_empat_id = $file_empat_id;  // Menyimpan ID file yang diunggah
    }
    $record->userid = $userid;  // Menyimpan ID pengguna yang pertama kali mengirimkan data
    
    $DB->insert_record('pjblsinawang_sintaks_empat', $record);
}

// Mengirim respons JSON untuk memberi tahu client bahwa data berhasil disimpan
echo json_encode(['success' => true]);