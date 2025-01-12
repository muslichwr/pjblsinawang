<?php
require_once('../../config.php');
require_once($CFG->dirroot.'/mod/pjblsinawang/lib.php');
require_login();

$groupid = required_param('groupid', PARAM_INT);  // Mendapatkan ID grup
$cmid = required_param('id', PARAM_INT);  // Mendapatkan ID modul
$tab = required_param('tab', PARAM_ALPHA);  // Mendapatkan tab (Sintaks 1-8)

switch ($tab) {
    case 'sintaks1':
        require_once($CFG->dirroot.'/mod/pjblsinawang/forms/sintaks_1.php');
        break;
    case 'sintaks2':
        require_once($CFG->dirroot.'/mod/pjblsinawang/forms/sintaks_2.php');
        break;
    case 'sintaks3':
        require_once($CFG->dirroot.'/mod/pjblsinawang/forms/sintaks_3.php');
        break;
    case 'sintaks4':
        require_once($CFG->dirroot.'/mod/pjblsinawang/forms/sintaks_4.php');
        break;
    case 'sintaks5':
        require_once($CFG->dirroot.'/mod/pjblsinawang/forms/sintaks_5.php');
        break;
    case 'sintaks6':
        require_once($CFG->dirroot.'/mod/pjblsinawang/forms/sintaks_6.php');
        break;
    case 'sintaks7':
        require_once($CFG->dirroot.'/mod/pjblsinawang/forms/sintaks_7.php');
        break;
    case 'sintaks8':
        require_once($CFG->dirroot.'/mod/pjblsinawang/forms/sintaks_8.php');
        break;
    default:
        echo 'Invalid syntax tab requested.';
        break;
}
?>
