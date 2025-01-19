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

// Cek apakah status sudah 'ready_for_next' dan apakah form harus diblokir
$form_locked = ($existing_data && $existing_data->status == 'ready_for_next' && !$is_teacher);

echo '<br>';
echo '<h3>Form Sintaks 1 - Orientasi Masalah</h3>';
echo '<br>';

echo '<form id="formSintaks1Submit" method="POST" action="javascript:void(0)">
        <input type="hidden" name="cmid" value="'.$cmid.'" />
        <input type="hidden" name="groupid" value="'.$groupid.'" />
        <input type="hidden" name="courseid" value="'.$courseid.'" />';  // Menyimpan ID Course

// Menambahkan JavaScript untuk mengunci form jika status sudah 'ready_for_next'
if ($form_locked) {
    echo '<script>
            document.getElementById("orientasi_masalah").setAttribute("readonly", true);
            document.getElementById("rumusan_masalah").setAttribute("readonly", true);
            document.getElementById("indikator").setAttribute("readonly", true);
            document.getElementById("analisis").setAttribute("readonly", true);
            document.getElementById("feedback").setAttribute("readonly", true);
            document.querySelector("button[type=submit]").style.display = "none"; // Menyembunyikan tombol submit
          </script>';

    // Menambahkan pesan HTML jika form sudah divalidasi
    echo '<div class="alert alert-info mt-3" role="alert">
            Sintaks 1 sudah divalidasi. Anda tidak dapat mengubah data lagi.
          </div>';
}

echo '<div id="notificationContainer"></div>';

echo '<div class="mb-3">
        <label for="orientasi_masalah" class="form-label">Orientasi Masalah</label>';
        if ($is_teacher || !$form_locked ) {
            echo '<textarea id="orientasi_masalah" name="orientasi_masalah" class="form-control" rows="3">';
            echo ($existing_data ? $existing_data->orientasi_masalah : '') . '</textarea>';
        } else {
            echo '<input type="text" id="orientasi_masalah" name="orientasi_masalah" class="form-control" value="';
            echo ($existing_data ? $existing_data->orientasi_masalah : '') . '" readonly />';
        }
echo '</div>

<div class="mb-3">
    <label for="rumusan_masalah" class="form-label">Rumusan Masalah</label>';
    if (!$form_locked || !$form_locked ) {
        echo '<textarea id="rumusan_masalah" name="rumusan_masalah" class="form-control" rows="3">';
        echo ($existing_data ? $existing_data->rumusan_masalah : '') . '</textarea>';
    } else {
        echo '<input type="text" id="rumusan_masalah" name="rumusan_masalah" class="form-control" value="';
        echo ($existing_data ? $existing_data->rumusan_masalah : '') . '" readonly />';
    }
echo '</div>

<div class="mb-3">
    <label for="indikator" class="form-label">Indikator</label>';
    if (!$form_locked || !$form_locked ) {
        echo '<textarea id="indikator" name="indikator" class="form-control" rows="3">';
        echo ($existing_data ? $existing_data->indikator : '') . '</textarea>';
    } else {
        echo '<input type="text" id="indikator" name="indikator" class="form-control" value="';
        echo ($existing_data ? $existing_data->indikator : '') . '" readonly />';
    }
echo '</div>

<div class="mb-3">
    <label for="analisis" class="form-label">Analisis</label>';
    if (!$form_locked || !$form_locked ) {
        echo '<textarea id="analisis" name="analisis" class="form-control" rows="3">';
        echo ($existing_data ? $existing_data->analisis : '') . '</textarea>';
    } else {
        echo '<input type="text" id="analisis" name="analisis" class="form-control" value="';
        echo ($existing_data ? $existing_data->analisis : '') . '" readonly />';
    }
echo '</div>';

// Form status hanya untuk guru
if ($is_teacher) {
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

    // Feedback - hanya untuk guru yang bisa mengedit
    echo '<div class="mb-3">
            <label for="feedback" class="form-label">Feedback</label>';
            if ($is_teacher) {
                echo '<textarea id="feedback" name="feedback" class="form-control" rows="3">';
                echo ($existing_data ? $existing_data->feedback : '') . '</textarea>';
            } else {
                echo '<input type="text" id="feedback" name="feedback" class="form-control" value="';
                echo ($existing_data ? $existing_data->feedback : '') . '" readonly />';
            }
    echo '</div>';
}

if (!$form_locked) {
    echo '<button type="submit" class="btn btn-primary" onclick="submitSintaksForm(1)">Submit</button>';
}

echo '</form>';
?>
