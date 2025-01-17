<?php
require_once('../../config.php');
require_once($CFG->dirroot.'/mod/pjblsinawang/lib.php');
require_login();

$cmid = required_param('cmid', PARAM_INT);
$groupid = required_param('groupid', PARAM_INT);
$courseid = required_param('courseid', PARAM_INT);

// Cek apakah pengguna adalah guru
$is_teacher = has_capability('moodle/course:manageactivities', context_course::instance($courseid));

// Cek apakah sudah ada data sintaks 3 sebelumnya
$sql = "SELECT * FROM {pjblsinawang_sintaks_tiga}
        WHERE cmid = :cmid AND groupid = :groupid AND courseid = :courseid";
$params = ['cmid' => $cmid, 'groupid' => $groupid, 'courseid' => $courseid];
$existing_data = $DB->get_record_sql($sql, $params);

// Cek apakah form harus diblokir untuk siswa (jika status tugas sudah 'completed')
$form_locked_for_students = ($existing_data && $existing_data->status == 'completed' && !$is_teacher);

echo '<br>';
echo '<h3>Form Sintaks 3 - Membuat Jadwal Proyek</h3>';
echo '<br>';

echo '<form id="formSintaks3Submit" method="POST" action="javascript:void(0)">
        <input type="hidden" name="cmid" value="'.$cmid.'" />
        <input type="hidden" name="groupid" value="'.$groupid.'" />
        <input type="hidden" name="courseid" value="'.$courseid.'" />';  // Menyimpan ID Course

// Menambahkan JavaScript untuk mengunci form jika status sudah 'completed' untuk siswa
if ($form_locked_for_students) {
    echo '<script>
            document.getElementById("task_name").setAttribute("readonly", true);
            document.getElementById("assigned_to").setAttribute("disabled", true);
            document.getElementById("due_date").setAttribute("readonly", true);
            document.getElementById("status").setAttribute("disabled", true);
            document.querySelector("button[type=submit]").setAttribute("disabled", true);
          </script>';
}

echo '<div id="notificationContainer"></div>';

// Field: Nama Tugas
echo '<div class="mb-3">
        <label for="task_name" class="form-label">Nama Tugas</label>';
        if ($is_teacher || !$form_locked_for_students) {
            echo '<input type="text" id="task_name" name="task_name" class="form-control" value="';
            echo ($existing_data ? $existing_data->task_name : '') . '" />';
        } else {
            echo '<input type="text" id="task_name" name="task_name" class="form-control" value="';
            echo ($existing_data ? $existing_data->task_name : '') . '" readonly />';
        }
echo '</div>';

// Field: Siswa yang diberi tugas
echo '<div class="mb-3">
        <label for="assigned_to" class="form-label">Siswa yang Diberi Tugas</label>';
        if ($is_teacher || !$form_locked_for_students) {
            echo '<input type="number" id="assigned_to" name="assigned_to" class="form-control" value="';
            echo ($existing_data ? $existing_data->assigned_to : '') . '" />';
        } else {
            echo '<input type="number" id="assigned_to" name="assigned_to" class="form-control" value="';
            echo ($existing_data ? $existing_data->assigned_to : '') . '" readonly />';
        }
echo '</div>';

// Field: Tanggal Batas Waktu
echo '<div class="mb-3">
        <label for="due_date" class="form-label">Tanggal Batas Waktu</label>';
        if ($is_teacher || !$form_locked_for_students) {
            echo '<input type="date" id="due_date" name="due_date" class="form-control" value="';
            echo ($existing_data ? date('Y-m-d', $existing_data->due_date) : '') . '" />';
        } else {
            echo '<input type="date" id="due_date" name="due_date" class="form-control" value="';
            echo ($existing_data ? date('Y-m-d', $existing_data->due_date) : '') . '" readonly />';
        }
echo '</div>';

// Field: Status Tugas
if ($is_teacher && !$form_locked_for_students) {
    echo '<div class="mb-3">
            <label for="status" class="form-label">Status Tugas</label>
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

echo '<button type="submit" class="btn btn-primary" onclick="submitSintaksForm(3)">Submit</button>
    </form>';
?>
