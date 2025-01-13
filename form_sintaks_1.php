<?php
require_once('../../config.php');
require_once($CFG->dirroot.'/mod/pjblsinawang/lib.php');
require_login();

$cmid = required_param('cmid', PARAM_INT);
$groupid = required_param('groupid', PARAM_INT);
$courseid = required_param('courseid', PARAM_INT);

// Mengecek apakah pengguna adalah guru
$is_teacher = has_capability('moodle/course:manageactivities', context_course::instance($courseid));

// Cek apakah sudah ada data sintaks sebelumnya
$sql = "SELECT * FROM {pjblsinawang_sintaks_satu}
        WHERE cmid = :cmid AND groupid = :groupid AND courseid = :courseid";
$params = ['cmid' => $cmid, 'groupid' => $groupid, 'courseid' => $courseid];
$existing_data = $DB->get_record_sql($sql, $params);

echo '<br>';
echo '<h3>Form Sintaks 1</h3>';
echo '<br>';

echo '<form id="formSintaks1Submit" method="POST" action="javascript:void(0)">
        <input type="hidden" name="cmid" value="'.$cmid.'" />
        <input type="hidden" name="groupid" value="'.$groupid.'" />
        <input type="hidden" name="courseid" value="'.$courseid.'" />
        
        <div class="mb-3">
            <label for="orientasi_masalah" class="form-label">Orientasi Masalah</label>';
            // Jika pengguna adalah guru, biarkan field bisa diubah. Jika siswa, set readonly
            if ($is_teacher) {
                echo '<textarea id="orientasi_masalah" name="orientasi_masalah" class="form-control" rows="3">';
                if ($existing_data) {
                    echo $existing_data->orientasi_masalah;
                }
                echo '</textarea>';
            } else {
                echo '<input type="text" id="orientasi_masalah" name="orientasi_masalah" class="form-control" value="';
                echo ($existing_data ? $existing_data->orientasi_masalah : '') . '" readonly />';
            }
            echo '</div>
        
        <div class="mb-3">
            <label for="rumusan_masalah" class="form-label">Rumusan Masalah</label>
            <textarea id="rumusan_masalah" name="rumusan_masalah" class="form-control" rows="3">';
            if ($existing_data) {
                echo $existing_data->rumusan_masalah;
            }
            echo '</textarea>
        </div>
        
        <div class="mb-3">
            <label for="indikator" class="form-label">Indikator</label>
            <textarea id="indikator" name="indikator" class="form-control" rows="3">';
            if ($existing_data) {
                echo $existing_data->indikator;
            }
            echo '</textarea>
        </div>
        
        <div class="mb-3">
            <label for="analisis" class="form-label">Analisis</label>
            <textarea id="analisis" name="analisis" class="form-control" rows="3">';
            if ($existing_data) {
                echo $existing_data->analisis;
            }
            echo '</textarea>
        </div>';

        // Menambahkan field status (dropdown) dan feedback untuk guru
        if ($is_teacher) {
            echo '<div class="mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select id="status" name="status" class="form-control">';
            
            // Menyediakan pilihan status untuk guru dalam Bahasa Indonesia
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
            
            // Menyediakan pilihan status untuk siswa dalam Bahasa Indonesia
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

echo '<div id="toastContainer" class="toast-container"></div>';

echo '<button type="submit" class="btn btn-primary" onclick="submitSintaksForm(1)">Submit</button>
    </form>';
?>
