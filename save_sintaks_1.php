<?php
require_once('../../config.php');
require_once($CFG->dirroot.'/mod/pjblsinawang/lib.php');
require_login();

$cmid = required_param('cmid', PARAM_INT);
$groupid = required_param('groupid', PARAM_INT);
$orientasi_masalah = required_param('orientasi_masalah', PARAM_TEXT);
$rumusan_masalah = required_param('rumusan_masalah', PARAM_TEXT);
$indikator = required_param('indikator', PARAM_TEXT);
$analisis = required_param('analisis', PARAM_TEXT);
$feedback = required_param('feedback', PARAM_TEXT);
$status = required_param('status', PARAM_TEXT);

// Menyimpan data ke dalam database
$record = new stdClass();
$record->cmid = $cmid;
$record->groupid = $groupid;
$record->orientasi_masalah = $orientasi_masalah;
$record->rumusan_masalah = $rumusan_masalah;
$record->indikator = $indikator;
$record->analisis = $analisis;
$record->feedback = $feedback;
$record->status = $status;

// Insert data ke tabel `pjblsinawang_sintaks_satu`
$DB->insert_record('pjblsinawang_sintaks_satu', $record);

redirect(new moodle_url('/mod/pjblsinawang/view.php', array('id' => $cmid)));
?>
