<?php
require_once('../../config.php');
require_once($CFG->dirroot.'/mod/pjblsinawang/lib.php');
require_login();

$cmid = required_param('id', PARAM_INT);  // Pastikan parameter 'id' ada
$groupid = required_param('groupid', PARAM_INT);

// Menampilkan form Sintaks 1
echo '<h3>Form Sintaks 1</h3>';
echo '<form id="formSintaks1Submit" method="POST" action="'.$CFG->wwwroot.'/mod/pjblsinawang/save_sintaks_1.php">
        <input type="hidden" name="cmid" value="'.$cmid.'" />
        <input type="hidden" name="groupid" value="'.$groupid.'" />
        <div class="mb-3">
            <label for="orientasi_masalah" class="form-label">Orientasi Masalah</label>
            <textarea id="orientasi_masalah" name="orientasi_masalah" class="form-control" rows="3"></textarea>
        </div>
        <div class="mb-3">
            <label for="rumusan_masalah" class="form-label">Rumusan Masalah</label>
            <textarea id="rumusan_masalah" name="rumusan_masalah" class="form-control" rows="3"></textarea>
        </div>
        <div class="mb-3">
            <label for="indikator" class="form-label">Indikator</label>
            <textarea id="indikator" name="indikator" class="form-control" rows="3"></textarea>
        </div>
        <div class="mb-3">
            <label for="analisis" class="form-label">Analisis</label>
            <textarea id="analisis" name="analisis" class="form-control" rows="3"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>';
?>
