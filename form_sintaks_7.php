<?php
require_once('../../config.php');
require_once($CFG->dirroot.'/mod/pjblsinawang/lib.php');
require_login();

$cmid = required_param('cmid', PARAM_INT);
$groupid = required_param('groupid', PARAM_INT);
$courseid = required_param('courseid', PARAM_INT);

// Cek apakah pengguna adalah guru
$is_teacher = has_capability('moodle/course:manageactivities', context_course::instance($courseid));

// Cek apakah sudah ada data sintaks 7 sebelumnya
$sql = "SELECT * FROM {pjblsinawang_sintaks_tujuh}
        WHERE cmid = :cmid AND groupid = :groupid AND courseid = :courseid";
$params = ['cmid' => $cmid, 'groupid' => $groupid, 'courseid' => $courseid];
$existing_data = $DB->get_record_sql($sql, $params);

// Cek apakah form sudah dikunci untuk siswa
$form_locked_for_students = ($existing_data && !empty($existing_data->evaluation_date) && !$is_teacher);

echo '<br>';
echo '<h3>Form Sintaks 7 - Penilaian dan Evaluasi Proyek</h3>';
echo '<br>';

echo '<form id="formSintaks7Submit" method="POST" action="javascript:void(0)"> 
        <input type="hidden" name="cmid" value="'.$cmid.'" />
        <input type="hidden" name="groupid" value="'.$groupid.'" />
        <input type="hidden" name="courseid" value="'.$courseid.'" />';  // Menyimpan ID Course

// Menambahkan JavaScript untuk mengunci form jika evaluasi sudah dilakukan
if ($form_locked_for_students) {
    echo '<script>
            document.getElementById("evaluation_date").setAttribute("disabled", true);
            document.getElementById("evaluation_result").setAttribute("disabled", true);
            document.getElementById("revisions_required").setAttribute("disabled", true);
            document.querySelector("button[type=submit]").setAttribute("disabled", true);
          </script>';
}

echo '<div id="notificationContainer"></div>';

// Field: Tanggal Evaluasi Proyek
echo '<div class="mb-3">
        <label for="evaluation_date" class="form-label">Tanggal Evaluasi Proyek</label>';
        if ($is_teacher || !$form_locked_for_students) {
            echo '<input type="datetime-local" id="evaluation_date" name="evaluation_date" class="form-control" value="';
            echo ($existing_data && !empty($existing_data->evaluation_date)) ? date('Y-m-d\TH:i', $existing_data->evaluation_date) : '';
            echo '">';
        } else {
            echo '<input type="datetime-local" id="evaluation_date" name="evaluation_date" class="form-control" value="';
            echo ($existing_data && !empty($existing_data->evaluation_date)) ? date('Y-m-d\TH:i', $existing_data->evaluation_date) : '';
            echo '" disabled>';
        }
echo '</div>';

// Field: Hasil Evaluasi Proyek
echo '<div class="mb-3">
        <label for="evaluation_result" class="form-label">Hasil Evaluasi Proyek</label>';
        if ($is_teacher || !$form_locked_for_students) {
            echo '<textarea id="evaluation_result" name="evaluation_result" class="form-control" rows="4">';
            echo ($existing_data && !empty($existing_data->evaluation_result)) ? $existing_data->evaluation_result : '';
            echo '</textarea>';
        } else {
            echo '<textarea id="evaluation_result" name="evaluation_result" class="form-control" rows="4" disabled>';
            echo ($existing_data && !empty($existing_data->evaluation_result)) ? $existing_data->evaluation_result : '';
            echo '</textarea>';
        }
echo '</div>';

// Field: Rekomendasi Revisi untuk Proyek
echo '<div class="mb-3">
        <label for="revisions_required" class="form-label">Rekomendasi Revisi untuk Proyek</label>';
        if ($is_teacher || !$form_locked_for_students) {
            echo '<textarea id="revisions_required" name="revisions_required" class="form-control" rows="4">';
            echo ($existing_data && !empty($existing_data->revisions_required)) ? $existing_data->revisions_required : '';
            echo '</textarea>';
        } else {
            echo '<textarea id="revisions_required" name="revisions_required" class="form-control" rows="4" disabled>';
            echo ($existing_data && !empty($existing_data->revisions_required)) ? $existing_data->revisions_required : '';
            echo '</textarea>';
        }
echo '</div>';

echo '<button type="submit" class="btn btn-primary" onclick="submitSintaksForm(7)">Submit</button>
    </form>';
?>
