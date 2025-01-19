<?php
require_once('../../config.php');
require_once($CFG->dirroot.'/mod/pjblsinawang/lib.php');
require_login();

$cmid = required_param('cmid', PARAM_INT);
$groupid = required_param('groupid', PARAM_INT);
$courseid = required_param('courseid', PARAM_INT);

// Cek apakah pengguna adalah guru
$is_teacher = has_capability('moodle/course:manageactivities', context_course::instance($courseid));

// Cek apakah sudah ada data sintaks 2 sebelumnya
$sql = "SELECT * FROM {pjblsinawang_sintaks_dua}
        WHERE cmid = :cmid AND groupid = :groupid AND courseid = :courseid";
$params = ['cmid' => $cmid, 'groupid' => $groupid, 'courseid' => $courseid];
$existing_data = $DB->get_record_sql($sql, $params);

// Cek apakah form harus diblokir untuk siswa (jika status proyek sudah 'completed')
$form_locked_for_students = ($existing_data && $existing_data->status == 'completed' && !$is_teacher);

echo '<br>';
echo '<h3>Form Sintaks 2 - Menyusun Rencana Proyek</h3>';
echo '<br>';

echo '<form id="formSintaks2Submit" method="POST" action="javascript:void(0)">
        <input type="hidden" name="cmid" value="'.$cmid.'" />
        <input type="hidden" name="groupid" value="'.$groupid.'" />
        <input type="hidden" name="courseid" value="'.$courseid.'" />';  // Menyimpan ID Course

// Menambahkan JavaScript untuk mengunci form jika status sudah 'completed' untuk siswa
if ($form_locked_for_students) {
    echo '<script>
            document.getElementById("project_name").setAttribute("readonly", true);
            document.getElementById("start_date").setAttribute("readonly", true);
            document.getElementById("end_date").setAttribute("readonly", true);
            document.getElementById("status").setAttribute("disabled", true);
            document.querySelector("button[type=submit]").style.display = "none"; // Menyembunyikan tombol submit
          </script>';

    // Menambahkan pesan HTML jika form sudah divalidasi
    echo '<div class="alert alert-info mt-3" role="alert">
            Sintaks 2 sudah divalidasi. Anda tidak dapat mengubah data lagi.
          </div>';
}

echo '<div id="notificationContainer"></div>';

// Field: Nama Proyek
echo '<div class="mb-3">
        <label for="project_name" class="form-label">Nama Proyek</label>';
        if ($is_teacher || !$form_locked_for_students) {
            echo '<input type="text" id="project_name" name="project_name" class="form-control" value="';
            echo ($existing_data ? $existing_data->project_name : '') . '" />';
        } else {
            echo '<input type="text" id="project_name" name="project_name" class="form-control" value="';
            echo ($existing_data ? $existing_data->project_name : '') . '" readonly />';
        }
echo '</div>';

// Field: Tanggal Mulai
echo '<div class="mb-3">
        <label for="start_date" class="form-label">Tanggal Mulai</label>';
        if ($is_teacher || !$form_locked_for_students) {
            echo '<input type="date" id="start_date" name="start_date" class="form-control" value="';
            echo ($existing_data ? date('Y-m-d', $existing_data->start_date) : '') . '" />';
        } else {
            echo '<input type="date" id="start_date" name="start_date" class="form-control" value="';
            echo ($existing_data ? date('Y-m-d', $existing_data->start_date) : '') . '" readonly />';
        }
echo '</div>';

// Field: Tanggal Selesai
echo '<div class="mb-3">
        <label for="end_date" class="form-label">Tanggal Selesai</label>';
        if ($is_teacher || !$form_locked_for_students) {
            echo '<input type="date" id="end_date" name="end_date" class="form-control" value="';
            echo ($existing_data ? date('Y-m-d', $existing_data->end_date) : '') . '" />';
        } else {
            echo '<input type="date" id="end_date" name="end_date" class="form-control" value="';
            echo ($existing_data ? date('Y-m-d', $existing_data->end_date) : '') . '" readonly />';
        }
echo '</div>';

// Field: Status Proyek
if ($is_teacher && !$form_locked_for_students) {
    echo '<div class="mb-3">
            <label for="status" class="form-label">Status Proyek</label>
            <select id="status" name="status" class="form-control">';

    $status_options = [
        'incomplete' => 'Belum Lengkap', 
        'in-progress' => 'Sedang Berlangsung', 
        'completed' => 'Selesai'
    ];
    foreach ($status_options as $value => $label) {
        $selected = ($existing_data && $existing_data->status == $value) ? 'selected' : '';
        echo '<option value="' . $value . '" ' . $selected . '>' . $label . '</option>';
    }
    echo '</select>
          </div>';
}

if (!$form_locked_for_students) {
    echo '<button type="submit" class="btn btn-primary" onclick="submitSintaksForm(2)">Submit</button>';
}

echo '</form>';
?>
