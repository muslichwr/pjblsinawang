<?php
require_once('../../config.php');
require_once($CFG->dirroot.'/lib/grouplib.php');
require_login();

$cmid = required_param('id', PARAM_INT);
$cm = get_coursemodule_from_id('pjblsinawang', $cmid, 0, false, MUST_EXIST);
$pjblsinawang = $cm->instance;
$pjblsinawang = $DB->get_record('pjblsinawang', array('id' => $pjblsinawang), '*', MUST_EXIST);
$course = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);

$context = context_module::instance($cm->id);
$PAGE->set_context($context);
$PAGE->set_cm($cm, $course);
$PAGE->set_url(new moodle_url('/mod/pjblsinawang/view.php', array('id' => $cmid)));
$PAGE->set_title(format_string($pjblsinawang->name));
$PAGE->set_heading($course->fullname);

echo $OUTPUT->header();

$groups = groups_get_all_groups($course->id);

echo '<link rel="stylesheet" type="text/css" href="'.$CFG->wwwroot.'/mod/pjblsinawang/css/style.css">';
echo '<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">';
echo '<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">';
echo '<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>';

echo '<select id="groupSelect" class="form-select mb-3">';
echo '<option>Select a Group</option>';
$groups = groups_get_all_groups($course->id);
foreach ($groups as $group) {
    echo '<option value="'.$group->id.'">'.$group->name.'</option>';
}
echo '</select>';

echo '<button id="showMembers" class="btn btn-primary mb-3">Show Members</button>';

// Div untuk menampilkan anggota (di bawah tombol)
echo '<div id="membersList" class="mt-3" style="display:none;"></div>';

// Tambahkan tombol untuk menampilkan Sintaks 1 form
echo '<button id="showSintaksForm" class="btn btn-secondary mt-3" style="display:none;">Show Sintaks Form</button>';

echo '<div id="formSintaks1" style="display:none;"></div>';

// **Tambahkan input tersembunyi untuk cmid**
echo '<input type="hidden" id="cmid" value="'.$cmid.'" />'; // Menyimpan cmid untuk digunakan oleh JavaScript

echo '<script>
        const getGroupMembersUrl = "'.$CFG->wwwroot.'/mod/pjblsinawang/ajax_load_members.php";
        const showSintaksFormUrl = "'.$CFG->wwwroot.'/mod/pjblsinawang/form_sintaks_1.php";
      </script>';

echo '<script src="'.$CFG->wwwroot.'/mod/pjblsinawang/js/script.js"></script>';

echo $OUTPUT->footer();
?>