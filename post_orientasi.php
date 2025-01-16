<?php
require_once('../../config.php');
require_once($CFG->dirroot.'/mod/pjblsinawang/lib.php');
require_login();

$cmid = required_param('cmid', PARAM_INT);
$orientasi_masalah = required_param('orientasi_masalah', PARAM_TEXT);
$existing_orientasi_id = optional_param('existing_orientasi_id', 0, PARAM_INT);

$cm = get_coursemodule_from_id('pjblsinawang', $cmid, 0, false, MUST_EXIST);
$pjblsinawang = $DB->get_record('pjblsinawang', array('id' => $cm->instance), '*', MUST_EXIST);
$course = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);

// Cek apakah pengguna adalah guru
$is_teacher = has_capability('moodle/course:manageactivities', context_course::instance($course->id));
if (!$is_teacher) {
    echo json_encode(['success' => false, 'message' => 'You are not authorized to post orientasi masalah.']);
    exit;
}

// Menyimpan orientasi masalah untuk seluruh grup
$groups = groups_get_all_groups($course->id); // Mendapatkan semua grup dalam kursus
if ($groups) {
    foreach ($groups as $group) {
        // Cek jika ada orientasi masalah yang sudah ada berdasarkan groupid
        $existing_orientasi = $DB->get_record('pjblsinawang_sintaks_satu', 
            ['cmid' => $cmid, 'groupid' => $group->id, 'courseid' => $course->id]);

        if ($existing_orientasi) {
            // Update orientasi masalah yang sudah ada untuk grup ini
            $existing_orientasi->orientasi_masalah = $orientasi_masalah;
            $DB->update_record('pjblsinawang_sintaks_satu', $existing_orientasi);
        } else {
            // Menyimpan orientasi masalah baru untuk grup ini
            $record = new stdClass();
            $record->cmid = $cmid;
            $record->groupid = $group->id;
            $record->courseid = $course->id;
            $record->orientasi_masalah = $orientasi_masalah;

            // Gunakan anggota grup pertama (atau ID tertentu) untuk menyimpan userid
            $members = groups_get_members($group->id);
            if ($members) {
                $record->userid = reset($members)->id; // Ambil ID pengguna pertama dalam grup
            } else {
                $record->userid = 0; // Jika grup tidak memiliki anggota, set default ke 0
            }

            // Menyimpan data ke tabel hanya sekali per grup
            $DB->insert_record('pjblsinawang_sintaks_satu', $record);
        }
    }

    echo json_encode(['success' => true, 'message' => 'Orientasi masalah posted or updated successfully.']);
} else {
    echo json_encode(['success' => false, 'message' => 'No groups found for the selected course.']);
}
?>
