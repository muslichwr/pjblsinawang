<?php
require_once('../../config.php');
require_once($CFG->dirroot.'/mod/pjblsinawang/lib.php');
require_login();

$cmid = required_param('cmid', PARAM_INT);
$groupid = required_param('groupid', PARAM_INT);
$courseid = required_param('courseid', PARAM_INT);

// Cek apakah sudah ada data refleksi sebelumnya
$sql = "SELECT * FROM {pjblsinawang_sintaks_delapan}
        WHERE cmid = :cmid AND groupid = :groupid AND courseid = :courseid";
$params = ['cmid' => $cmid, 'groupid' => $groupid, 'courseid' => $courseid];
$existing_data = $DB->get_record_sql($sql, $params);

// Cek apakah form sudah dikunci untuk siswa
$form_locked_for_students = ($existing_data && !empty($existing_data->reflection_date));

echo '<br>';
echo '<h3>Form Sintaks 8 - Refleksi Pembelajaran</h3>';
echo '<br>';

echo '<form id="formSintaks8Submit" method="POST" action="javascript:void(0)"> 
        <input type="hidden" name="cmid" value="'.$cmid.'" />
        <input type="hidden" name="groupid" value="'.$groupid.'" />
        <input type="hidden" name="courseid" value="'.$courseid.'" />';  // Menyimpan ID Course

// Menambahkan JavaScript untuk mengunci form jika refleksi sudah dilakukan
if ($form_locked_for_students) {
    echo '<script>
            document.getElementById("reflection_date").setAttribute("disabled", true);
            document.getElementById("reflection_notes").setAttribute("disabled", true);
            document.querySelector("button[type=submit]").setAttribute("disabled", true);
          </script>';
}

echo '<div id="notificationContainer"></div>';

// Field: Tanggal Refleksi
echo '<div class="mb-3">
        <label for="reflection_date" class="form-label">Tanggal Refleksi</label>';
        if (!$form_locked_for_students) {
            echo '<input type="datetime-local" id="reflection_date" name="reflection_date" class="form-control" value="';
            echo ($existing_data && !empty($existing_data->reflection_date)) ? date('Y-m-d\TH:i', $existing_data->reflection_date) : '';
            echo '">';
        } else {
            echo '<input type="datetime-local" id="reflection_date" name="reflection_date" class="form-control" value="';
            echo ($existing_data && !empty($existing_data->reflection_date)) ? date('Y-m-d\TH:i', $existing_data->reflection_date) : '';
            echo '" disabled>';
        }
echo '</div>';

// Field: Catatan Refleksi
echo '<div class="mb-3">
        <label for="reflection_notes" class="form-label">Catatan Refleksi mengenai Pembelajaran dan Proyek</label>';
        if (!$form_locked_for_students) {
            echo '<textarea id="reflection_notes" name="reflection_notes" class="form-control" rows="4">';
            echo ($existing_data && !empty($existing_data->reflection_notes)) ? $existing_data->reflection_notes : '';
            echo '</textarea>';
        } else {
            echo '<textarea id="reflection_notes" name="reflection_notes" class="form-control" rows="4" disabled>';
            echo ($existing_data && !empty($existing_data->reflection_notes)) ? $existing_data->reflection_notes : '';
            echo '</textarea>';
        }
echo '</div>';

echo '<button type="submit" class="btn btn-primary" onclick="submitSintaksForm(8)">Submit</button>
    </form>';
?>
