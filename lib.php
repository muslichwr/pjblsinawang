<?php

defined('MOODLE_INTERNAL') || die();

function pjblsinawang_add_instance($pjblsinawang) {
    global $DB;

    $pjblsinawang->timecreated = time();
    $pjblsinawang->timemodified = $pjblsinawang->timecreated;

    return $DB->insert_record('pjblsinawang', $pjblsinawang);
}

function pjblsinawang_update_instance($pjblsinawang) {
    global $DB;

    $pjblsinawang->timemodified = time();
    $pjblsinawang->id = $pjblsinawang->instance;

    return $DB->update_record('pjblsinawang', $pjblsinawang);
}

function pjblsinawang_delete_instance($id) {
    global $DB;

    return $DB->delete_records('pjblsinawang', array('id' => $id));
}

function mod_pjblsinawang_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options = array()) {
    // Pastikan ini adalah konteks modul
    if ($context->contextlevel != CONTEXT_MODULE) {
        return false;
    }

    // Validasi filearea
    $valid_fileareas = [
        'file_pjblsinawang_empat',   // File area untuk Sintaks 4
        'file_pjblsinawang_lima_laporan', // File area untuk Laporan Tahap 5
        'file_pjblsinawang_lima_proyek'  // File area untuk Proyek Tahap 5
    ];
    if (!in_array($filearea, $valid_fileareas)) {
        return false;  // Jika filearea tidak valid, kembalikan false
    }

    // Ambil itemid dan nama file dari $args
    $itemid = array_shift($args); // ID item, pastikan ini sesuai dengan ID yang disimpan
    $filename = array_pop($args); // Nama file terakhir dalam args
    $filepath = '/' . implode('/', $args) . '/'; // Path file jika ada subfolder

    // Ambil file dari file storage
    $fs = get_file_storage();
    $file = $fs->get_file($context->id, 'mod_pjblsinawang', $filearea, $itemid, $filepath, $filename);

    // Jika file tidak ditemukan atau file berupa folder
    if (!$file || $file->is_directory()) {
        return false; // Jika file tidak ditemukan, kembalikan false
    }

    // Kirimkan file ke pengguna (menggunakan download atau tampilkan sesuai keperluan)
    send_stored_file($file, 0, 0, $forcedownload, $options);
}

// function mod_pjblsinawang_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options = array()) {
//     // Pastikan ini adalah konteks modul
//     if ($context->contextlevel != CONTEXT_MODULE) {
//         return false;
//     }

//     // Validasi filearea
//     $valid_fileareas = [
//         'file_pjblsinawang_tiga',  // File area untuk Tahap 3
//         'file_pjblsinawang_lima_proyek',   // File area untuk Tahap 5 Proyek
//         'file_pjblsinawang_lima_laporan'   // File area untuk Tahap 5 Laporan
//     ]; // Sesuaikan dengan filearea yang digunakan
//     if (!in_array($filearea, $valid_fileareas)) {
//         return false;
//     }

//     // Ambil itemid dan nama file dari $args
//     $itemid = array_shift($args); // ID item sesuai dengan user ID atau lainnya
//     $filename = array_pop($args); // Ambil nama file terakhir dalam args
//     $filepath = '/' . implode('/', $args) . '/'; // Path file jika ada subfolder

//     // Ambil file dari file storage
//     $fs = get_file_storage();
//     $file = $fs->get_file($context->id, 'mod_pjblsinawang', $filearea, $itemid, $filepath, $filename);

//     // Jika file tidak ditemukan atau file berupa folder
//     if (!$file || $file->is_directory()) {
//         return false;
//     }

//     // Kirimkan file ke pengguna (menggunakan download atau tampilkan sesuai keperluan)
//     send_stored_file($file, 0, 0, $forcedownload, $options);
// }
?>