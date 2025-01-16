<?php
require_once('../../config.php');
require_once($CFG->dirroot.'/mod/pjblsinawang/lib.php');
require_login();

$cmid = required_param('id', PARAM_INT);
$cm = get_coursemodule_from_id('pjblsinawang', $cmid, 0, false, MUST_EXIST);
$pjblsinawang = $DB->get_record('pjblsinawang', array('id' => $cm->instance), '*', MUST_EXIST);
$course = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);

// Cek apakah pengguna adalah guru
$is_teacher = has_capability('moodle/course:manageactivities', context_course::instance($course->id));

$context = context_module::instance($cm->id);
$PAGE->set_context($context);
$PAGE->set_cm($cm, $course);
$PAGE->set_url(new moodle_url('/mod/pjblsinawang/post_orientasi_form.php', array('id' => $cmid)));
$PAGE->set_title(format_string($pjblsinawang->name));
$PAGE->set_heading($course->fullname);

echo $OUTPUT->header();

// Hanya tampilkan form untuk guru
if ($is_teacher) {
    // Cek apakah orientasi masalah sudah ada untuk grup tertentu
    $existing_orientasi = null;

    echo '<h3>Post Orientasi Masalah</h3>';
    echo '<form action="post_orientasi.php" method="POST">
            <div class="form-group">
                <label for="orientasi_masalah">Orientasi Masalah</label>
                <textarea id="orientasi_masalah" name="orientasi_masalah" class="form-control" rows="4"></textarea>
            </div>
            <input type="hidden" name="cmid" value="' . $cmid . '" />
            <button type="submit" class="btn btn-primary mt-3">Post Orientasi</button>
          </form>';
} else {
    echo '<p>You are not authorized to post an orientasi masalah.</p>';
}

echo $OUTPUT->footer();
?>
