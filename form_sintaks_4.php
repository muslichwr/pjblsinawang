<?php
require_once('../../config.php');
require_once($CFG->dirroot.'/mod/pjblsinawang/lib.php');
require_login();

$cmid = required_param('cmid', PARAM_INT);
$groupid = required_param('groupid', PARAM_INT);
$courseid = required_param('courseid', PARAM_INT);

// Cek apakah pengguna adalah guru
$is_teacher = has_capability('moodle/course:manageactivities', context_course::instance($courseid));

// Cek apakah sudah ada data sintaks 4 sebelumnya
$sql = "SELECT * FROM {pjblsinawang_sintaks_empat}
        WHERE cmid = :cmid AND groupid = :groupid AND courseid = :courseid";
$params = ['cmid' => $cmid, 'groupid' => $groupid, 'courseid' => $courseid];
$existing_data = $DB->get_record_sql($sql, $params);

// Cek apakah form harus diblokir untuk siswa (jika status tugas sudah 'completed')
$form_locked_for_students = ($existing_data && $existing_data->status == 'completed' && !$is_teacher);

echo '<br>';
echo '<h3>Form Sintaks 4 - Monitoring Pelaksanaan</h3>';
echo '<br>';

echo '<form id="formSintaks4Submit" method="POST" action="javascript:void(0)" enctype="multipart/form-data">
        <input type="hidden" name="cmid" value="'.$cmid.'" />
        <input type="hidden" name="groupid" value="'.$groupid.'" />
        <input type="hidden" name="courseid" value="'.$courseid.'" />';  // Menyimpan ID Course

// Menambahkan JavaScript untuk mengunci form jika status sudah 'completed' untuk siswa
if ($form_locked_for_students) {
    echo '<script>
            document.getElementById("status").setAttribute("disabled", true);
            document.getElementById("file_empat").setAttribute("disabled", true);
            document.getElementById("comments").setAttribute("readonly", true);
            document.querySelector("button[type=submit]").setAttribute("disabled", true);
          </script>';
}

echo '<div id="notificationContainer"></div>';

// Field: Status Penyelesaian
echo '<div class="mb-3">
        <label for="status" class="form-label">Status Penyelesaian</label>';
        if ($is_teacher || !$form_locked_for_students) {
            echo '<select id="status" name="status" class="form-control">';
            $status_options = [
                'incomplete' => 'Belum Lengkap', 
                'in-progress' => 'Sedang Berlangsung', 
                'completed' => 'Selesai'
            ];
            foreach ($status_options as $value => $label) {
                $selected = ($existing_data && $existing_data->status == $value) ? 'selected' : '';
                echo '<option value="' . $value . '" ' . $selected . '>' . $label . '</option>';
            }
            echo '</select>';
        } else {
            echo '<select id="status" name="status" class="form-control" disabled>';
            $status_options = [
                'incomplete' => 'Belum Lengkap', 
                'in-progress' => 'Sedang Berlangsung', 
                'completed' => 'Selesai'
            ];
            foreach ($status_options as $value => $label) {
                $selected = ($existing_data && $existing_data->status == $value) ? 'selected' : '';
                echo '<option value="' . $value . '" ' . $selected . '>' . $label . '</option>';
            }
            echo '</select>';
        }
echo '</div>';

// Field: Unggah File
echo '<div class="mb-3">
        <label for="file_empat" class="form-label">Unggah File (Opsional)</label>';
        if ($is_teacher || !$form_locked_for_students) {
            echo '<input type="file" id="file_empat" name="file_empat" class="form-control">';
            if ($existing_data && $existing_data->file_empat_id) {
                echo '<small>File yang sudah diunggah: <a href="'.$CFG->wwwroot.'/pluginfile.php/'.$context->id.'/mod_resource/content/'.$existing_data->file_empat_id.'" target="_blank">Lihat File</a></small>';
            }
        } else {
            echo '<input type="file" id="file_empat" name="file_empat" class="form-control" disabled>';
            if ($existing_data && $existing_data->file_empat_id) {
                echo '<small>File yang sudah diunggah: <a href="'.$CFG->wwwroot.'/pluginfile.php/'.$context->id.'/mod_resource/content/'.$existing_data->file_empat_id.'" target="_blank">Lihat File</a></small>';
            }
        }
echo '</div>';

// Field: Komentar Guru
echo '<div class="mb-3">
        <label for="comments" class="form-label">Komentar Guru</label>';
        if ($is_teacher || !$form_locked_for_students) {
            echo '<textarea id="comments" name="comments" class="form-control" rows="3">' . ($existing_data ? $existing_data->comments : '') . '</textarea>';
        } else {
            echo '<textarea id="comments" name="comments" class="form-control" rows="3" readonly>' . ($existing_data ? $existing_data->comments : '') . '</textarea>';
        }
echo '</div>';

echo '<button type="submit" class="btn btn-primary" onclick="submitSintaksForm(4)">Submit</button>
    </form>';
?>
