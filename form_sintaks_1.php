<?php
require_once('../../config.php');
require_once($CFG->dirroot.'/mod/pjblsinawang/lib.php');
require_login();

$cmid = required_param('cmid', PARAM_INT);
$groupid = required_param('groupid', PARAM_INT);
$courseid = required_param('courseid', PARAM_INT);

// Cek apakah pengguna adalah guru
$is_teacher = has_capability('moodle/course:manageactivities', context_course::instance($courseid));

// Cek apakah sudah ada data sintaks sebelumnya
$sql = "SELECT * FROM {pjblsinawang_sintaks_satu}
        WHERE cmid = :cmid AND groupid = :groupid AND courseid = :courseid";
$params = ['cmid' => $cmid, 'groupid' => $groupid, 'courseid' => $courseid];
$existing_data = $DB->get_record_sql($sql, $params);

// Cek apakah status sudah 'ready_for_next' dan apakah form harus diblokir untuk siswa
$form_locked_for_students = ($existing_data && $existing_data->status == 'ready_for_next' && !$is_teacher);

echo '<br>';
echo '<h3>Form Sintaks 1</h3>';
echo '<br>';

echo '<form id="formSintaks1Submit" method="POST" action="javascript:void(0)">
        <input type="hidden" name="cmid" value="'.$cmid.'" />
        <input type="hidden" name="groupid" value="'.$groupid.'" />
        <input type="hidden" name="courseid" value="'.$courseid.'" />';  // Menyimpan ID Course

// Menambahkan JavaScript untuk mengunci form jika status sudah 'ready_for_next' untuk siswa
if ($form_locked_for_students) {
    echo '<script>
            document.getElementById("orientasi_masalah").setAttribute("readonly", true);
            document.getElementById("rumusan_masalah").setAttribute("readonly", true);
            document.getElementById("indikator").setAttribute("readonly", true);
            document.getElementById("analisis").setAttribute("readonly", true);
            document.getElementById("status").setAttribute("disabled", true);
            document.getElementById("feedback").setAttribute("readonly", true);
            document.querySelector("button[type=submit]").setAttribute("disabled", true);
          </script>';
}

echo '<div id="notificationContainer"></div>';

// Field: Orientasi Masalah
echo '<div class="mb-3">
        <label for="orientasi_masalah" class="form-label">Orientasi Masalah</label>';

        // Cek apakah pengguna adalah guru atau bukan
        if ($is_teacher || !$form_locked_for_students) {
            // Jika guru atau siswa dan form tidak terkunci, form bisa diubah
            echo '<textarea id="orientasi_masalah" name="orientasi_masalah" class="form-control" rows="3">';
            if ($existing_data) {
                echo $existing_data->orientasi_masalah; // Menampilkan data yang ada jika ada
            }
            echo '</textarea>';
        } else {
            // Jika form terkunci untuk siswa, hanya bisa dibaca
            echo '<input type="text" id="orientasi_masalah" name="orientasi_masalah" class="form-control" value="';
            echo ($existing_data ? $existing_data->orientasi_masalah : '') . '" readonly />';
        }
echo '</div>';

// Field: Rumusan Masalah
echo '<div class="mb-3">
        <label for="rumusan_masalah" class="form-label">Rumusan Masalah</label>';
        if ($is_teacher || !$form_locked_for_students) {
            // Jika guru atau siswa dan form tidak terkunci, form bisa diubah
            echo '<textarea id="rumusan_masalah" name="rumusan_masalah" class="form-control" rows="3">';
            if ($existing_data) {
                echo $existing_data->rumusan_masalah;
            }
            echo '</textarea>';
        } else {
            // Jika form terkunci untuk siswa, hanya bisa dibaca
            echo '<input type="text" id="rumusan_masalah" name="rumusan_masalah" class="form-control" value="';
            echo ($existing_data ? $existing_data->rumusan_masalah : '') . '" readonly />';
        }
echo '</div>';

// Field: Indikator
echo '<div class="mb-3">
        <label for="indikator" class="form-label">Indikator</label>';
        if ($is_teacher || !$form_locked_for_students) {
            // Jika guru atau siswa dan form tidak terkunci, form bisa diubah
            echo '<textarea id="indikator" name="indikator" class="form-control" rows="3">';
            if ($existing_data) {
                echo $existing_data->indikator;
            }
            echo '</textarea>';
        } else {
            // Jika form terkunci untuk siswa, hanya bisa dibaca
            echo '<input type="text" id="indikator" name="indikator" class="form-control" value="';
            echo ($existing_data ? $existing_data->indikator : '') . '" readonly />';
        }
echo '</div>';

// Field: Analisis
echo '<div class="mb-3">
        <label for="analisis" class="form-label">Analisis</label>';
        if ($is_teacher || !$form_locked_for_students) {
            // Jika guru atau siswa dan form tidak terkunci, form bisa diubah
            echo '<textarea id="analisis" name="analisis" class="form-control" rows="3">';
            if ($existing_data) {
                echo $existing_data->analisis;
            }
            echo '</textarea>';
        } else {
            // Jika form terkunci untuk siswa, hanya bisa dibaca
            echo '<input type="text" id="analisis" name="analisis" class="form-control" value="';
            echo ($existing_data ? $existing_data->analisis : '') . '" readonly />';
        }
echo '</div>';

// Field: Status dan Feedback (Untuk Guru)
if ($is_teacher && !$form_locked_for_students) {
    echo '<div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select id="status" name="status" class="form-control">';

    $status_options = [
        'incomplete' => 'Belum Lengkap', 
        'revised' => 'Perlu Revisi', 
        'ready_for_next' => 'Siap untuk Tahap Berikutnya'
    ];
    foreach ($status_options as $value => $label) {
        $selected = ($existing_data && $existing_data->status == $value) ? 'selected' : '';
        echo '<option value="' . $value . '" ' . $selected . '>' . $label . '</option>';
    }
    echo '</select>
          </div>';

    echo '<div class="mb-3">
            <label for="feedback" class="form-label">Feedback</label>
            <textarea id="feedback" name="feedback" class="form-control" rows="3">' . ($existing_data ? $existing_data->feedback : '') . '</textarea>
          </div>';
} else {
    // Untuk siswa, tampilkan dropdown status yang hanya bisa dibaca (readonly)
    echo '<div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select id="status" name="status" class="form-control" disabled>';

    $status_options = [
        'incomplete' => 'Belum Lengkap', 
        'revised' => 'Perlu Revisi', 
        'ready_for_next' => 'Siap untuk Tahap Berikutnya'
    ];
    foreach ($status_options as $value => $label) {
        $selected = ($existing_data && $existing_data->status == $value) ? 'selected' : '';
        echo '<option value="' . $value . '" ' . $selected . '>' . $label . '</option>';
    }
    echo '</select>
          </div>';

    echo '<div class="mb-3">
            <label for="feedback" class="form-label">Feedback</label>
            <textarea id="feedback" name="feedback" class="form-control" rows="3" readonly>' . ($existing_data ? $existing_data->feedback : '') . '</textarea>
          </div>';
}

echo '<button type="submit" class="btn btn-primary" onclick="submitSintaksForm(1)">Submit</button>
    </form>';
?>
