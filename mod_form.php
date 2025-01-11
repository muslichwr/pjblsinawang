
<?php

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot.'/course/moodleform_mod.php');

class mod_pjblsinawang_mod_form extends moodleform_mod {
    public function definition() {
        $mform = $this->_form;

        $mform->addElement('header', 'general', get_string('general', 'form'));
        $iconurl = new moodle_url('/mod/pjblsinawang/pix/icon.png');
        $mform->addElement('html', '<div class="mod-icon"><img src="' . $iconurl . '" alt="' . get_string('modulename', 'pjblsinawang') . '" /></div>');
        $mform->addElement('text', 'name', get_string('name'), array('size' => '64'));
        $mform->setType('name', PARAM_TEXT);
        $mform->addRule('name', null, 'required', null, 'client');

        $this->standard_intro_elements();
        $this->standard_coursemodule_elements();

        $this->add_action_buttons();
    }
}
