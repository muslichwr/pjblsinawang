<?php
require_once('../../config.php');
require_once($CFG->dirroot.'/mod/pjblsinawang/lib.php');
require_login();

$cmid = required_param('cmid', PARAM_INT);
$groupid = required_param('groupid', PARAM_INT);
$courseid = required_param('courseid', PARAM_INT);

// Cek apakah pengguna adalah guru
$is_teacher = has_capability('moodle/course:manageactivities', context_course::instance($courseid));

// Cek apakah sudah ada data sintaks 5 sebelumnya
$sql = "SELECT * FROM {pjblsinawang_sintaks_lima}
        WHERE cmid = :cmid AND groupid = :groupid AND courseid = :courseid";
$params = ['cmid' => $cmid, 'groupid' => $groupid, 'courseid' => $courseid];
$existing_data = $DB->get_record_sql($sql, $params);

// Cek apakah form harus diblokir untuk siswa (jika status sudah 'submitted')
$form_locked_for_students = ($existing_data && $existing_data->status == 'submitted' && !$is_teacher);

echo '<br>';
echo '<h3>Form Sintaks 5 - Pengumpulan Proyek</h3>';
echo '<br>';

echo '<form id="formSintaks5Submit" method="POST" action="javascript:void(0)" enctype="multipart/form-data">
        <input type="hidden" name="cmid" value="'.$cmid.'" />
        <input type="hidden" name="groupid" value="'.$groupid.'" />
        <input type="hidden" name="courseid" value="'.$courseid.'" />';  // Menyimpan ID Course

// Menambahkan JavaScript untuk mengunci form jika status sudah 'submitted' untuk siswa
if ($form_locked_for_students) {
    echo '<script>
            document.getElementById("status").setAttribute("disabled", true);
            document.getElementById("file_proyek").setAttribute("disabled", true);
            document.getElementById("file_laporan").setAttribute("disabled", true);
            document.querySelector("button[type=submit]").setAttribute("disabled", true);
          </script>';
}

echo '<div id="notificationContainer"></div>';

// Field: Status Pengumpulan
echo '<div class="mb-3">
        <label for="status" class="form-label">Status Pengumpulan</label>';
        if ($is_teacher || !$form_locked_for_students) {
            echo '<select id="status" name="status" class="form-control">';
            $status_options = [
                'incomplete' => 'Belum Tersubmit', 
                'submitted' => 'Tersubmit', 
                'late' => 'Terlambat'
            ];
            foreach ($status_options as $value => $label) {
                $selected = ($existing_data && $existing_data->status == $value) ? 'selected' : '';
                echo '<option value="' . $value . '" ' . $selected . '>' . $label . '</option>';
            }
            echo '</select>';
        } else {
            echo '<select id="status" name="status" class="form-control" disabled>';
            $status_options = [
                'incomplete' => 'Belum Tersubmit', 
                'submitted' => 'Tersubmit', 
                'late' => 'Terlambat'
            ];
            foreach ($status_options as $value => $label) {
                $selected = ($existing_data && $existing_data->status == $value) ? 'selected' : '';
                echo '<option value="' . $value . '" ' . $selected . '>' . $label . '</option>';
            }
            echo '</select>';
        }
echo '</div>';

// Field: Unggah File Proyek
echo '<div class="mb-3">
        <label for="file_proyek" class="form-label">Unggah File Proyek</label>';
        if ($is_teacher || !$form_locked_for_students) {
            echo '<input type="file" id="file_proyek" name="file_proyek" class="form-control">';
            if ($existing_data && $existing_data->file_proyek_id) {
                echo '<small>File yang sudah diunggah: <a href="'.$CFG->wwwroot.'/pluginfile.php/'.$context->id.'/mod_resource/content/'.$existing_data->file_proyek_id.'" target="_blank">Lihat File</a></small>';
            }
        } else {
            echo '<input type="file" id="file_proyek" name="file_proyek" class="form-control" disabled>';
            if ($existing_data && $existing_data->file_proyek_id) {
                echo '<small>File yang sudah diunggah: <a href="'.$CFG->wwwroot.'/pluginfile.php/'.$context->id.'/mod_resource/content/'.$existing_data->file_proyek_id.'" target="_blank">Lihat File</a></small>';
            }
        }
echo '</div>';

// Field: Unggah File Laporan
echo '<div class="mb-3">
        <label for="file_laporan" class="form-label">Unggah File Laporan</label>';
        if ($is_teacher || !$form_locked_for_students) {
            echo '<input type="file" id="file_laporan" name="file_laporan" class="form-control">';
            if ($existing_data && $existing_data->file_laporan_id) {
                echo '<small>File yang sudah diunggah: <a href="'.$CFG->wwwroot.'/pluginfile.php/'.$context->id.'/mod_resource/content/'.$existing_data->file_laporan_id.'" target="_blank">Lihat File</a></small>';
            }
        } else {
            echo '<input type="file" id="file_laporan" name="file_laporan" class="form-control" disabled>';
            if ($existing_data && $existing_data->file_laporan_id) {
                echo '<small>File yang sudah diunggah: <a href="'.$CFG->wwwroot.'/pluginfile.php/'.$context->id.'/mod_resource/content/'.$existing_data->file_laporan_id.'" target="_blank">Lihat File</a></small>';
            }
        }
echo '</div>';

echo '<button type="submit" class="btn btn-primary" onclick="submitSintaksForm(5)">Submit</button>
    </form>';
?>
