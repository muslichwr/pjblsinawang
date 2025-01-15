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
$sql = "SELECT * FROM {pjblsinawang_sintaks_dua}
        WHERE cmid = :cmid AND groupid = :groupid AND courseid = :courseid";
$params = ['cmid' => $cmid, 'groupid' => $groupid, 'courseid' => $courseid];
$existing_data = $DB->get_record_sql($sql, $params);

echo '<br>';
echo '<h3>Form Sintaks 2 - Menyusun Rencana Proyek</h3>';
echo '<br>';

echo '<form id="formSintaks2Submit" method="POST" action="javascript:void(0)">
        <input type="hidden" name="cmid" value="'.$cmid.'" />
        <input type="hidden" name="groupid" value="'.$groupid.'" />
        <input type="hidden" name="courseid" value="'.$courseid.'" />
        
        <div class="mb-3">
            <label for="project_title" class="form-label">Judul Proyek</label>';
            echo '<input type="text" id="project_title" name="project_title" class="form-control" value="';
            echo ($existing_data ? $existing_data->project_title : '') . '" />';
            echo '</div>
        
        <div class="mb-3">
            <label for="project_schedule" class="form-label">Jadwal Proyek</label>';
            echo '<textarea id="project_schedule" name="project_schedule" class="form-control" rows="6" placeholder="Contoh format jadwal proyek:

                Minggu 1: Deskripsi tugas yang perlu dilakukan pada minggu pertama.
                Minggu 2: Deskripsi tugas untuk minggu kedua.
                Minggu 3: Deskripsi tugas untuk minggu ketiga, dan seterusnya.
                Isilah sesuai rencana proyek Anda.
                ">';
            echo ($existing_data ? $existing_data->project_schedule : '') . '</textarea>';
            echo '</div>
        
        <div class="mb-3">
            <label for="tasks" class="form-label">Tugas Anggota</label>';
            // Mendapatkan anggota kelompok menggunakan groups_get_members
            $members = groups_get_members($groupid); // Ambil anggota kelompok berdasarkan groupid
            // Mendekodekan tugas anggota yang disimpan dalam format JSON
            $tasks = $existing_data ? json_decode($existing_data->tasks, true) : [];

            foreach ($members as $index => $member) {
                echo '<div class="task-group mb-3">
                        <label for="task_'.$member->id.'" class="form-label">Tugas untuk '.$member->firstname.' '.$member->lastname.'</label>
                        <textarea id="task_'.$member->id.'" name="tasks['.$member->id.']" class="form-control" rows="3">';
                        
                        // Tampilkan tugas anggota jika ada, atau biarkan kosong
                        echo isset($tasks[$member->id]) ? $tasks[$member->id] : '';
                        
                echo '</textarea>
                      </div>';
            }
            echo '</div>';

        // Menambahkan status dan feedback untuk guru
        if ($is_teacher) {
            echo '<div class="mb-3">
                    <label for="status" class="form-label">Status Proyek</label>
                    <select id="status" name="status" class="form-control">';
            
            // Menyediakan pilihan status
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
            // Untuk siswa, status dan feedback hanya bisa dibaca (readonly)
            echo '<div class="mb-3">
                    <label for="status" class="form-label">Status Proyek</label>
                    <input type="text" id="status" name="status" class="form-control" value="' . ($existing_data ? $existing_data->status : 'Belum Lengkap') . '" readonly />
                  </div>';

            echo '<div class="mb-3">
                    <label for="feedback" class="form-label">Feedback</label>
                    <textarea id="feedback" name="feedback" class="form-control" rows="3" readonly>' . ($existing_data ? $existing_data->feedback : '') . '</textarea>
                  </div>';
        }

echo '<div id="toastContainer" class="toast-container"></div>';

echo '<button type="submit" class="btn btn-primary" onclick="submitSintaksForm(2)">Submit</button>
    </form>';
?>
