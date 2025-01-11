<?php
require_once('../../config.php');
require_login();

$cmid = required_param('id', PARAM_INT);
$cm = get_coursemodule_from_id('pjblsinawang', $cmid, 0, false, MUST_EXIST);
$pjblsinawang = $DB->get_record('pjblsinawang', array('id' => $cm->instance), '*', MUST_EXIST);
$course = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);

$context = context_module::instance($cm->id);
$PAGE->set_context($context);
$PAGE->set_cm($cm, $course);
$PAGE->set_url(new moodle_url('/mod/pjblsinawang/view.php', array('id' => $cmid)));
$PAGE->set_title(format_string($pjblsinawang->name));
$PAGE->set_heading($course->fullname);

$PAGE->requires->css(new moodle_url('https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css'));
$PAGE->requires->css(new moodle_url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css'));
$PAGE->requires->css(new moodle_url('/mod/pjblsinawang/css/style.css'));

$PAGE->requires->js(new moodle_url('https://code.jquery.com/jquery-3.5.1.slim.min.js'), true);
$PAGE->requires->js(new moodle_url('https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js'), true);
$PAGE->requires->js(new moodle_url('https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js'), true);
$PAGE->requires->js(new moodle_url('/mod/pjblsinawang/js/group-functions.js'), true);

echo $OUTPUT->header();

global $DB;
$groups = $DB->get_records('groups', ['courseid' => $course->id]);
echo '<div class="container">';
foreach ($groups as $group) {
    echo '<div class="group-item mt-3">';
    echo '<h3>'. format_string($group->name) .'</h3>';
    echo '<button class="btn btn-primary details-button" data-groupid="' . $group->id . '">View Members</button>';
    echo '<div id="group-details-' . $group->id . '" class="group-details mt-2" style="display: none;"></div>';
    echo '</div>';
}
echo '</div>';

echo $OUTPUT->footer();