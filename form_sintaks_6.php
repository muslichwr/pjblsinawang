<?php
require_once('../../config.php');
require_once($CFG->dirroot.'/mod/pjblsinawang/lib.php');
require_login();

$cmid = required_param('cmid', PARAM_INT);
$groupid = required_param('groupid', PARAM_INT);
$courseid = required_param('courseid', PARAM_INT);

// Cek apakah pengguna adalah guru
$is_teacher = has_capability('moodle/course:manageactivities', context_course::instance($courseid));

// Cek apakah sudah ada data sintaks 6 sebelumnya
$sql = "SELECT * FROM {pjblsinawang_sintaks_enam}
        WHERE cmid = :cmid AND groupid = :groupid AND courseid = :courseid";
$params = ['cmid' => $cmid, 'groupid' => $groupid, 'courseid' => $courseid];
$existing_data = $DB->get_record_sql($sql, $params);

// Cek apakah form harus diblokir untuk siswa (jika presentasi sudah dilakukan)
$form_locked_for_students = ($existing_data && !empty($existing_data->presentation_date) && !$is_teacher);

echo '<br>';
echo '<h3>Form Sintaks 6 - Presentasi Proyek</h3>';
echo '<br>';

echo '<form id="formSintaks6Submit" method="POST" action="javascript:void(0)"> 
        <input type="hidden" name="cmid" value="'.$cmid.'" />
        <input type="hidden" name="groupid" value="'.$groupid.'" />
        <input type="hidden" name="courseid" value="'.$courseid.'" />';  // Menyimpan ID Course

// Menambahkan JavaScript untuk mengunci form jika presentasi sudah dilakukan
if ($form_locked_for_students) {
    echo '<script>
            document.getElementById("presentation_date").setAttribute("disabled", true);
            document.getElementById("presenter").setAttribute("disabled", true);
            document.querySelector("button[type=submit]").setAttribute("disabled", true);
            
            document.querySelector("button[type=submit]").style.display = "none";  // Sembunyikan tombol submit jika form terkunci
          </script>';
          
          echo '<div class="alert alert-info mt-3" role="alert">
          Sintaks 6 sudah dilakukan. Anda tidak dapat mengubah data lagi.
          </div>';
}

echo '<div id="notificationContainer"></div>';

// Field: Tanggal dan Waktu Presentasi
echo '<div class="mb-3">
        <label for="presentation_date" class="form-label">Tanggal dan Waktu Presentasi</label>';
        if ($is_teacher || !$form_locked_for_students) {
            echo '<input type="datetime-local" id="presentation_date" name="presentation_date" class="form-control" value="';
            echo ($existing_data && !empty($existing_data->presentation_date)) ? date('Y-m-d\TH:i', $existing_data->presentation_date) : '';
            echo '">';
        } else {
            echo '<input type="datetime-local" id="presentation_date" name="presentation_date" class="form-control" value="';
            echo ($existing_data && !empty($existing_data->presentation_date)) ? date('Y-m-d\TH:i', $existing_data->presentation_date) : '';
            echo '" disabled>';
        }
echo '</div>';

// Field: Nama Presenter
echo '<div class="mb-3">
        <label for="presenter" class="form-label">Nama Presenter</label>';
        if ($is_teacher || !$form_locked_for_students) {
            echo '<input type="text" id="presenter" name="presenter" class="form-control" value="';
            echo ($existing_data && !empty($existing_data->presenter)) ? $existing_data->presenter : '';
            echo '">';
        } else {
            echo '<input type="text" id="presenter" name="presenter" class="form-control" value="';
            echo ($existing_data && !empty($existing_data->presenter)) ? $existing_data->presenter : '';
            echo '" disabled>';
        }
echo '</div>';

// Field: Umpan Balik (Feedback) - hanya untuk guru
echo '<div class="mb-3">
        <label for="feedback" class="form-label">Komentar dan Feedback dari Guru</label>';
        if ($is_teacher) {
            echo '<textarea id="feedback" name="feedback" class="form-control" rows="4">';
            echo ($existing_data && !empty($existing_data->feedback)) ? $existing_data->feedback : '';
            echo '</textarea>';
        } else {
            echo '<textarea id="feedback" name="feedback" class="form-control" rows="4" disabled>';
            echo ($existing_data && !empty($existing_data->feedback)) ? $existing_data->feedback : '';
            echo '</textarea>';
        }
echo '</div>';

if (!$form_locked_for_students) {
    echo '<button type="submit" class="btn btn-primary" onclick="submitSintaksForm(6)">Submit</button>';
}

echo '</form>';
?>
