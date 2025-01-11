<?php

require_once('../../config.php');
require_login();

$cmid = required_param('id', PARAM_INT);
$cm = get_coursemodule_from_id('codepix', $cmid, 0, false, MUST_EXIST); 
$codepixid = $cm->instance;
$codepix = $DB->get_record('codepix', array('id' => $codepixid), '*', MUST_EXIST);
$course = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST); 

$context = context_module::instance($cm->id);
$PAGE->set_context($context);
$PAGE->set_cm($cm, $course);
$PAGE->set_url(new moodle_url('/mod/codepix/view.php', array('id' => $cmid)));
$PAGE->set_title(format_string($codepix->name));
$PAGE->set_heading($course->fullname);

$PAGE->requires->css(new moodle_url('https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css'));
$PAGE->requires->css(new moodle_url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css'));
$PAGE->requires->css(new moodle_url('/mod/codepix/css/style.css'));

$PAGE->requires->js(new moodle_url('https://code.jquery.com/jquery-3.5.1.slim.min.js'), true);
$PAGE->requires->js(new moodle_url('https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js'), true);
$PAGE->requires->js(new moodle_url('https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js'), true);

echo $OUTPUT->header();

?>

<div class="d-flex">
    <div id="sidebar-menu" class="bg-dark text-white p-3">
        <h4 class="text-center">Menu</h4>
        <ul class="list-unstyled">
            <?php
            // Mengecek apakah pengguna adalah guru
            $is_teacher = has_capability('moodle/course:manageactivities', context_course::instance($course->id));

            // Menentukan URL untuk masing-masing menu
            $base_url = 'id=' . $cmid;
            $menu_items = [
                'view.php' => 'Home',
                'satu.php' => 'Tahap 1 Orientasi Masalah',
                'dua.php' => 'Tahap 2 Membuat Indikator',
                'tigaplus.php' => 'Tahap 3 Merancang Program',
                'tiga.php' => 'Tahap 4 Menyusun Jadwal',
                'empat.php' => 'Tahap 5 Pelaksanaan Proyek',
                'lima.php' => 'Tahap 6 Pengumpulan Proyek',
                'enam.php' => 'Tahap 7 Penilaian Dan Evaluasi'
            ];

            // Jika pengguna adalah guru, arahkan ke halaman manajemen
            if ($is_teacher) {
                $menu_items = [
                    'satu_manage.php' => 'Tahap 1 Orientasi Masalah',
                    'dua_manage.php' => 'Tahap 2 Membuat Indikator',
                    'tigaplus_manage.php' => 'Tahap 3 Merancang Program',
                    'tiga_manage.php' => 'Tahap 4 Menyusun Jadwal',
                    'review_revisions.php' => 'Tahap 5 Pelaksanaan Proyek',
                    'lima_manage.php' => 'Tahap 6 Pengumpulan Proyek',
                    'enam_manage.php' => 'Tahap 7 Penilaian Dan Evaluasi'
                ];
            }

            // Menampilkan menu
            foreach ($menu_items as $file => $label) {
                echo '<li><a href="' . $file . '?' . $base_url . '" 
                    class="text-white small ' . (basename($_SERVER['PHP_SELF']) == $file ? 'active' : '') . '">
                    <i class=""></i> ' . $label . '
                    </a></li>';
            }
            ?>
        </ul>
    </div>

    <div class="container">
        <div class="container">
            <h1 class="text-primary text-center mb-4"><?php echo format_string($codepix->name); ?></h1>

            <div class="row">
                <div class="col-md-6 mb-4">
                    <div class="card">
                        <div class="card-header bg-primary text-white text-center">
                            User Information
                        </div>
                        <div class="card-body">
                            <p><strong>Logged in as:</strong> <?php echo fullname($USER); ?></p>
                            <p><strong>Email:</strong> <?php echo $USER->email; ?></p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 mb-4">
                    <div class="card">
                        <div class="card-header bg-success text-white text-center">
                            Group Information
                        </div>
                        <div class="card-body">
                            <?php
                            $groups = groups_get_all_groups($course->id, $USER->id);

                            if ($groups) {
                                foreach ($groups as $group) {
                                    echo '<p><strong>Group Name:</strong> ' . $group->name . '</p>';
                                    echo '<p><strong>Members:</strong></p>';
                                    echo '<ul>';
                                    $members = groups_get_members($group->id, 'u.id, u.firstname, u.lastname');
                                    foreach ($members as $member) {
                                        echo '<li>' . fullname($member) . '</li>';
                                    }
                                    echo '</ul>';
                                }
                            } else {
                                echo '<p>You are not a member of any groups.</p>';
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<?php

echo $OUTPUT->footer();

?>