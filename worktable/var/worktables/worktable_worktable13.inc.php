<?php

require(CAMILA_LIB_DIR . 'minitemplator/MiniTemplator.class.php');

$_CAMILA['page']->camila_worktable = true;

$wt_id = substr($_SERVER['PHP_SELF'], 12, -4);

function worktable_get_safe_temp_filename($name) {
    global $_CAMILA;
    return CAMILA_TMP_DIR . '/lastval_' . $_CAMILA['lang'] . '_' . preg_replace('/[^a-z]/', '', strtolower($name));
}

function worktable_get_last_value_from_file($name) {
    return file_get_contents(worktable_get_safe_temp_filename($name));
}


function worktable_get_next_autoincrement_value($table, $column) {

    global $_CAMILA;

    $result = $_CAMILA['db']->Execute('select max('.$column.') as id from ' . $table);
    if ($result === false)
        camila_error_page(camila_get_translation('camila.sqlerror') . ' ' . $_CAMILA['db']->ErrorMsg());

    return intval($result->fields['id']) + 1;

}


function worktable_parse_default_expression($expression, $form) {
    return camila_parse_default_expression($expression, $form->fields['id']->defaultvalue);
}


if (camila_form_in_update_mode(worktable_worktable13)) {

    require_once(CAMILA_DIR . 'datagrid/db_form.class.php');

    require_once(CAMILA_DIR . 'datagrid/elements/form/hidden.php');
    require_once(CAMILA_DIR . 'datagrid/elements/form/static_listbox.php');
    require_once(CAMILA_DIR . 'datagrid/elements/form/textbox.php');
    require_once(CAMILA_DIR . 'datagrid/elements/form/datetime.php');

    
    require_once(CAMILA_DIR . 'datagrid/elements/form/textbox.php');
    
    require_once(CAMILA_DIR . 'datagrid/elements/form/static_listbox.php');
    
    require_once(CAMILA_DIR . 'datagrid/elements/form/datetime.php');
    
    require_once(CAMILA_DIR . 'datagrid/elements/form/phonenumber.php');
    
    require_once(CAMILA_DIR . 'datagrid/elements/form/textarea.php');
    
    require_once(CAMILA_DIR . 'datagrid/elements/form/date.php');
    
    require_once(CAMILA_DIR . 'datagrid/elements/form/integer.php');
    

    $form = new dbform('worktable_worktable13', 'id');

    $form->caninsert = true;
    $form->candelete = true;
    $form->canupdate = true;

    $form->drawrules = true;
    $form->drawheadersubmitbutton = true;

    new form_textbox($form, 'id', camila_get_translation('camila.worktable.field.id'));
    if (is_object($form->fields['id'])) {
        if ($_REQUEST['camila_update'] == 'new' && !isset($_REQUEST['camila_phpform_sent'])) {
            $_CAMILA['db_genid'] = $_CAMILA['db']->GenID('worktableseq', 100000);
            $form->fields['id']->defaultvalue = $_CAMILA['db_genid'];
        }
        $form->fields['id']->updatable = false;
        $form->fields['id']->forcedraw = true;
    }

    
    new form_textbox($form, 'codicebadge', 'CODICE BADGE', false, 30, 255, '');

if (is_object($form->fields['codicebadge'])) $form->fields['codicebadge']->autofocus = true;
    
    new form_textbox($form, 'cognome', 'COGNOME', true, 30, 255, '');

    
    new form_textbox($form, 'nome', 'NOME', true, 30, 255, '');

    
    new form_textbox($form, 'organizzazione', 'ORGANIZZAZIONE', false, 30, 255, '');

    
    new form_static_listbox($form, 'tipopasto', 'TIPO PASTO', 'COLAZIONE,PRANZO,CENA', false, '');
if (is_object($form->fields['tipopasto'])) $form->fields['tipopasto']->defaultvalue = worktable_get_last_value_from_file('TIPO PASTO');
if (is_object($form->fields['tipopasto'])) $form->fields['tipopasto']->write_value_to_file = worktable_get_safe_temp_filename('TIPO PASTO');

    
    new form_datetime($form, 'oraregistrazione', 'ORA REGISTRAZIONE', false, '');
if (is_object($form->fields['oraregistrazione'])) $form->fields['oraregistrazione']->hslots = 60;
if (is_object($form->fields['oraregistrazione'])) $form->fields['oraregistrazione']->defaultvalue = date('Y-m-d H:i:s');

    
    new form_textbox($form, 'codicepasto', 'CODICE PASTO', false, 30, 255, '');
if (is_object($form->fields['codicepasto'])) $form->fields['codicepasto']->defaultvalue = worktable_parse_default_expression('P${gg}${mm}${aaaa}${hh24}', $form);

    

    new form_static_listbox($form, 'cf_bool_is_selected', camila_get_translation('camila.worktable.field.selected'), camila_get_translation('camila.worktable.options.noyes'));
    new form_static_listbox($form, 'cf_bool_is_special', camila_get_translation('camila.worktable.field.special'), camila_get_translation('camila.worktable.options.noyes'));

    if ($_REQUEST['camila_update'] != 'new') {

    new form_datetime($form, 'created', camila_get_translation('camila.worktable.field.created'));
    if (is_object($form->fields['created'])) $form->fields['created']->updatable = false;

    new form_textbox($form, 'created_by', camila_get_translation('camila.worktable.field.created_by'));
    if (is_object($form->fields['created_by'])) $form->fields['created_by']->updatable = false;

    new form_textbox($form, 'created_by_surname', camila_get_translation('camila.worktable.field.created_by_surname'));
    if (is_object($form->fields['created_by_surname'])) $form->fields['created_by_surname']->updatable = false;

    new form_textbox($form, 'created_by_name', camila_get_translation('camila.worktable.field.created_by_name'));
    if (is_object($form->fields['created_by_name'])) $form->fields['created_by_name']->updatable = false;

    new form_static_listbox($form, 'created_src', camila_get_translation('camila.worktable.field.created_src'), camila_get_translation('camila.worktable.options.recordmodsrc'));
    if (is_object($form->fields['created_src'])) $form->fields['created_src']->updatable = false;

    new form_datetime($form, 'last_upd', camila_get_translation('camila.worktable.field.last_upd'));
    if (is_object($form->fields['last_upd'])) $form->fields['last_upd']->updatable = false;

    new form_textbox($form, 'last_upd_by', camila_get_translation('camila.worktable.field.last_upd_by'));
    if (is_object($form->fields['last_upd_by'])) $form->fields['last_upd_by']->updatable = false;

    new form_textbox($form, 'last_upd_by_surname', camila_get_translation('camila.worktable.field.last_upd_by_surname'));
    if (is_object($form->fields['last_upd_by_surname'])) $form->fields['last_upd_by_surname']->updatable = false;

    new form_textbox($form, 'last_upd_by_name', camila_get_translation('camila.worktable.field.last_upd_by_name'));
    if (is_object($form->fields['last_upd_by_name'])) $form->fields['last_upd_by_name']->updatable = false;

    new form_textbox($form, 'last_upd_by_name', camila_get_translation('camila.worktable.field.last_upd_by_name'));
    if (is_object($form->fields['last_upd_by_name'])) $form->fields['last_upd_by_name']->updatable = false;

    new form_static_listbox($form, 'last_upd_src', camila_get_translation('camila.worktable.field.last_upd_src'), camila_get_translation('camila.worktable.options.recordmodsrc'));
    if (is_object($form->fields['last_upd_src'])) $form->fields['last_upd_src']->updatable = false;

    new form_textbox($form, 'mod_num', camila_get_translation('camila.worktable.field.mod_num'));
    if (is_object($form->fields['mod_num'])) $form->fields['mod_num']->updatable = false;
}

    if (is_object($form->fields['codicebadge']))
{
$form->fields['codicebadge']->autosuggest_table = 'worktable_worktable6';
$form->fields['codicebadge']->autosuggest_field = 'codicebadge';
$form->fields['codicebadge']->autosuggest_idfield = 'id';
$form->fields['codicebadge']->autosuggest_infofields = 'cognome,nome,organizzazione';
$form->fields['codicebadge']->autosuggest_pickfields = 'cognome,nome,organizzazione';
$form->fields['codicebadge']->autosuggest_destfields = 'cognome,nome,organizzazione';
}
if (is_object($form->fields['cognome']))
{
$form->fields['cognome']->autosuggest_table = 'worktable_worktable6';
$form->fields['cognome']->autosuggest_field = 'cognome';
$form->fields['cognome']->autosuggest_idfield = 'id';
$form->fields['cognome']->autosuggest_infofields = 'nome,organizzazione,codicebadge';
$form->fields['cognome']->autosuggest_pickfields = 'nome,organizzazione,codicebadge';
$form->fields['cognome']->autosuggest_destfields = 'nome,organizzazione,codicebadge';
}
if (is_object($form->fields['nome']))
{
$form->fields['nome']->autosuggest_table = 'worktable_worktable6';
$form->fields['nome']->autosuggest_field = 'nome';
$form->fields['nome']->autosuggest_idfield = 'id';
$form->fields['nome']->autosuggest_infofields = 'organizzazione,codicebadge,cognome';
$form->fields['nome']->autosuggest_pickfields = 'organizzazione,codicebadge,cognome';
$form->fields['nome']->autosuggest_destfields = 'organizzazione,codicebadge,cognome';
}
if (is_object($form->fields['organizzazione']))
{
$form->fields['organizzazione']->autosuggest_table = 'worktable_worktable6';
$form->fields['organizzazione']->autosuggest_field = 'organizzazione';
$form->fields['organizzazione']->autosuggest_idfield = 'id';
$form->fields['organizzazione']->autosuggest_infofields = 'codicebadge,cognome,nome';
$form->fields['organizzazione']->autosuggest_pickfields = 'codicebadge,cognome,nome';
$form->fields['organizzazione']->autosuggest_destfields = 'codicebadge,cognome,nome';
}


    $form->process();
    
    $form->draw();

} else {

      require(CAMILA_DIR . 'datagrid/report.class.php');

      $report_fields = 'id,cf_bool_is_special,cf_bool_is_selected,codicebadge,cognome,nome,organizzazione,tipopasto,oraregistrazione,codicepasto,created,created_by,created_by_surname,created_by_name,last_upd,last_upd_by,last_upd_by_surname,last_upd_by_name,mod_num';
      $default_fields = 'cf_bool_is_special,cf_bool_is_selected,codicebadge,cognome,nome,organizzazione,tipopasto,oraregistrazione,codicepasto';
      if ($_CAMILA['page']->camila_exporting())
          $mapping = 'created=Data creazione#last_upd=Ultimo aggiornamento#last_upd_by=Utente ult. agg.#last_upd_src=Sorgente Ult. agg.#last_upd_by_name=Nome Utente ult. agg.#last_upd_by_surname=Cognome Utente ult. agg.#mod_num=Num. mod.#id=Cod. riga#created_by=Utente creaz.#created_src=Sorgente creaz.#created_by_surname=Cognome Utente creaz.#created_by_name=Nome Utente creaz.#cf_bool_is_special=contrassegnati come speciali#cf_bool_is_selected=selezionati#codicebadge=CODICE BADGE#cognome=COGNOME#nome=NOME#organizzazione=ORGANIZZAZIONE#tipopasto=TIPO PASTO#oraregistrazione=ORA REGISTRAZIONE#codicepasto=CODICE PASTO';
      else
          $mapping = 'created=Data creazione#last_upd=Ultimo aggiornamento#last_upd_by=Utente ult. agg.#last_upd_src=Sorgente Ult. agg.#last_upd_by_name=Nome Utente ult. agg.#last_upd_by_surname=Cognome Utente ult. agg.#mod_num=Num. mod.#id=Cod. riga#created_by=Utente creaz.#created_src=Sorgente creaz.#created_by_surname=Cognome Utente creaz.#created_by_name=Nome Utente creaz.#cf_bool_is_special=contrassegnati come speciali#cf_bool_is_selected=selezionati#codicebadge=CODICE BADGE#cognome=COGNOME#nome=NOME#organizzazione=ORGANIZZAZIONE#tipopasto=TIPO PASTO#oraregistrazione=ORA REGISTRAZIONE#codicepasto=CODICEPASTO';

      $stmt = 'select ' . $report_fields . ' from worktable_worktable13';
      $report = new report($stmt, '', 'oraregistrazione', 'desc', $mapping, null, 'id', 'cf_bool_is_special,cf_bool_is_selected,codicebadge,cognome,nome,organizzazione,tipopasto,oraregistrazione,codicepasto', '', true, true);

      if (true)
          $report->additional_links = Array(camila_get_translation('camila.report.insertnew') => basename($_SERVER['PHP_SELF']) . '?camila_update=new');

      if ($_CAMILA['adm_user_group'] == CAMILA_ADM_USER_GROUP) {
          $report->additional_links[camila_get_translation('camila.worktable.import')] = 'cf_worktable_wizard_step4.php?camila_custom=' . $wt_id . '&camila_returl=' . urlencode($_SERVER['PHP_SELF']);
          $report->additional_links[camila_get_translation('camila.worktable.rebuild')] = 'cf_worktable_admin.php?camila_custom=' . $wt_id . '&camila_worktable_op=rebuild' . '&camila_returl=' . urlencode($_SERVER['PHP_SELF']);
          $report->additional_links[camila_get_translation('camila.worktable.reconfig')] = 'cf_worktable_wizard_step2.php?camila_custom=' . $wt_id . '&camila_returl=' . urlencode($_SERVER['PHP_SELF']);
      }

      $report->process();
      $report->draw();

}
?>