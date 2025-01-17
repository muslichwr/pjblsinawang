<?php
require_once('../../config.php');
require_once($CFG->dirroot.'/lib/grouplib.php');
require_login();

$cmid = required_param('id', PARAM_INT);
$cm = get_coursemodule_from_id('pjblsinawang', $cmid, 0, false, MUST_EXIST);
$pjblsinawang = $cm->instance;
$pjblsinawang = $DB->get_record('pjblsinawang', array('id' => $pjblsinawang), '*', MUST_EXIST);
$course = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);

// Cek apakah pengguna adalah guru
$is_teacher = has_capability('moodle/course:manageactivities', context_course::instance($course->id));

$context = context_module::instance($cm->id);
$PAGE->set_context($context);
$PAGE->set_cm($cm, $course);
$PAGE->set_url(new moodle_url('/mod/pjblsinawang/view.php', array('id' => $cmid)));
$PAGE->set_title(format_string($pjblsinawang->name));
$PAGE->set_heading($course->fullname);

echo $OUTPUT->header();

$groups = groups_get_all_groups($course->id);
$current_user_group = groups_get_all_groups($course->id, $USER->id); // Menampilkan grup saat ini untuk siswa

echo '<link rel="stylesheet" type="text/css" href="'.$CFG->wwwroot.'/mod/pjblsinawang/css/style.css">';
echo '<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">';
echo '<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">';
echo '<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>';

echo '<div id="contentArea">'; // Div untuk konten yang dinamis

if ($is_teacher) {
    // Dropdown untuk memilih grup bagi guru
    echo '<select id="groupSelect" class="form-select mb-3 col-3">
            <option>Select a Group</option>';
    foreach ($groups as $group) {
        echo '<option value="'.$group->id.'">'.$group->name.'</option>';
    }
    echo '</select>';
} else {
    // Untuk siswa, tampilkan grup mereka langsung
    if ($current_user_group) {
        $group = reset($current_user_group); // Mengambil grup pertama (satu grup)
        echo '<p><strong>Group: </strong>' . $group->name . '</p>';
        echo '<input type="hidden" id="groupSelect" value="'.$group->id.'" />';
    } else {
        echo '<p>No group found for this user.</p>';
    }
}

echo '<button id="showMembers" class="btn btn-primary mb-3">Pilih</button>';
echo '<br>';

if ($is_teacher) {
    echo '<a href="'.$CFG->wwwroot.'/mod/pjblsinawang/post_orientasi_form.php?id='.$cmid.'" class="btn btn-info">Post Orientasi Masalah</a>';
}

echo '<div id="membersList" class="mt-3" style="display:none;"></div>';

echo '<div id="showSintaksBtns" style="display:none;">
        <button id="showSintaks1" class="btn btn-secondary mt-3">Sintaks 1</button>
        <button id="showSintaks2" class="btn btn-secondary mt-3">Sintaks 2</button>
        <button id="showSintaks3" class="btn btn-secondary mt-3">Sintaks 3</button>
      </div>';

echo '<div id="formSintaks" style="display:none;"></div>';

echo '</div>';

echo '<input type="hidden" id="cmid" value="'.$cmid.'" />';
echo '<input type="hidden" id="courseid" value="'.$course->id.'" />';

echo '<script>
        const getGroupMembersUrl = "'.$CFG->wwwroot.'/mod/pjblsinawang/ajax_load_members.php";
        const showSintaksFormUrl1 = "'.$CFG->wwwroot.'/mod/pjblsinawang/form_sintaks_1.php";
        const showSintaksFormUrl2 = "'.$CFG->wwwroot.'/mod/pjblsinawang/form_sintaks_2.php";
        const showSintaksFormUrl3 = "'.$CFG->wwwroot.'/mod/pjblsinawang/form_sintaks_3.php";
        const showSintaksFormUrl4 = "'.$CFG->wwwroot.'/mod/pjblsinawang/form_sintaks_4.php";
        const showSintaksFormUrl5 = "'.$CFG->wwwroot.'/mod/pjblsinawang/form_sintaks_5.php";
        const showSintaksFormUrl6 = "'.$CFG->wwwroot.'/mod/pjblsinawang/form_sintaks_6.php";
        const showSintaksFormUrl7 = "'.$CFG->wwwroot.'/mod/pjblsinawang/form_sintaks_7.php";
        const showSintaksFormUrl8 = "'.$CFG->wwwroot.'/mod/pjblsinawang/form_sintaks_8.php";
        const submitSintaksFormUrl1 = "'.$CFG->wwwroot.'/mod/pjblsinawang/save_sintaks_1.php";
        const submitSintaksFormUrl2 = "'.$CFG->wwwroot.'/mod/pjblsinawang/save_sintaks_2.php";
        const submitSintaksFormUrl3 = "'.$CFG->wwwroot.'/mod/pjblsinawang/save_sintaks_3.php";
        const submitSintaksFormUrl4 = "'.$CFG->wwwroot.'/mod/pjblsinawang/save_sintaks_4.php";
        const submitSintaksFormUrl5 = "'.$CFG->wwwroot.'/mod/pjblsinawang/save_sintaks_5.php";
        const submitSintaksFormUrl6 = "'.$CFG->wwwroot.'/mod/pjblsinawang/save_sintaks_6.php";
        const submitSintaksFormUrl7 = "'.$CFG->wwwroot.'/mod/pjblsinawang/save_sintaks_7.php";
        const submitSintaksFormUrl8 = "'.$CFG->wwwroot.'/mod/pjblsinawang/save_sintaks_8.php";
      </script>';

echo '<script type="module" src="'.$CFG->wwwroot.'/mod/pjblsinawang/js/script.js"></script>';


echo $OUTPUT->footer();
?>
