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
            document.querySelectorAll("[id^=\'task_name_\']").forEach(function(input) {
                input.setAttribute("readonly", true);
            });
            document.querySelector("button[type=submit]").setAttribute("disabled", true);
          </script>';

    // Menambahkan pesan HTML jika form sudah divalidasi
    echo '<div class="alert alert-info mt-3" role="alert">
    Sintaks 3 sudah divalidasi. Anda tidak dapat mengubah data lagi.
  </div>';
}

echo '<div id="notificationContainer"></div>';

// Mengambil anggota kelompok menggunakan API Moodle
$group_members = groups_get_members($groupid); // Mendapatkan anggota berdasarkan groupid

echo '<div id="groupMembersContainer">';

// Menampilkan anggota kelompok
foreach ($group_members as $member) {
    // Jika sudah ada data sebelumnya, dekode JSON task_name
    $existing_task_names = $existing_data ? json_decode($existing_data->task_name, true) : [];
    $existing_task_name = isset($existing_task_names[$member->id]) ? $existing_task_names[$member->id] : '';  // Ambil tugas berdasarkan ID anggota

    echo '<div class="mb-3">
            <label for="task_name_' . $member->id . '" class="form-label">Nama Tugas untuk ' . $member->firstname . ' ' . $member->lastname . '</label>';
    if ($is_teacher || !$form_locked_for_students) {
        echo '<input type="text" id="task_name_' . $member->id . '" name="task_name_' . $member->id . '" class="form-control" value="' . $existing_task_name . '" />';
    } else {
        echo '<input type="text" id="task_name_' . $member->id . '" name="task_name_' . $member->id . '" class="form-control" value="' . $existing_task_name . '" readonly />';
    }
    echo '</div>';
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

// Tombol submit (hanya aktif jika form tidak terkunci)
if (!$form_locked_for_students) {
    echo '<button type="submit" class="btn btn-primary" onclick="submitSintaksForm(3)">Submit</button>';
}

echo '</form>';
?>
