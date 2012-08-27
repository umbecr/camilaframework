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


if (camila_form_in_update_mode(worktable_worktable6)) {

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
    

    $form = new dbform('worktable_worktable6', 'id');

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

    
    new form_textbox($form, 'organizzazione', 'ORGANIZZAZIONE', true, 30, 255, 'uppercase');

    
    new form_textbox($form, 'cognome', 'COGNOME', true, 30, 255, 'uppercase');

if (is_object($form->fields['cognome'])) $form->fields['cognome']->autofocus = true;
    
    new form_textbox($form, 'nome', 'NOME', true, 30, 255, 'uppercase');

    
    new form_textbox($form, 'codicefiscale', 'CODICE FISCALE', false, 30, 255, 'unique');

    
    new form_textbox($form, 'codicecurvo', 'CODICE C.U.R.V.O.', false, 30, 255, '');

    
    new form_textbox($form, 'cellulare', 'CELLULARE', false, 30, 255, '');

    
    new form_static_listbox($form, 'responsabile', 'RESPONSABILE', 'N,S', false, '');
if (is_object($form->fields['responsabile'])) $form->fields['responsabile']->defaultvalue = worktable_parse_default_expression('N', $form);

    
    new form_date($form, 'dataarrivo', 'DATA ARRIVO', true, '');
if (is_object($form->fields['dataarrivo'])) $form->fields['dataarrivo']->defaultvalue = date('Y-m-d');

    
    new form_date($form, 'datapartenza', 'DATA PARTENZA', false, '');
if (is_object($form->fields['datapartenza'])) $form->fields['datapartenza']->defaultvalue = date('Y-m-d');

    
    new form_static_listbox($form, 'mansione', 'MANSIONE', 'OPERATORE LOGISTICO,OPERATORE SANITARIO,OPERATORE SEGRETERIA,OPERATORE RADIO,OPERATORE SALA OPERATIVA', false, '');

    
    new form_static_listbox($form, 'beneficidilegge', 'BENEFICI DI LEGGE', 'N,S', false, '');
if (is_object($form->fields['beneficidilegge'])) $form->fields['beneficidilegge']->defaultvalue = worktable_parse_default_expression('N', $form);

    
    new form_datetime($form, 'dataoraregistrazione', 'DATA/ORA REGISTRAZIONE', false, '');
if (is_object($form->fields['dataoraregistrazione'])) $form->fields['dataoraregistrazione']->hslots = 60;
if (is_object($form->fields['dataoraregistrazione'])) $form->fields['dataoraregistrazione']->defaultvalue = date('Y-m-d H:i:s');

    
    new form_datetime($form, 'dataorauscitadefinitiva', 'DATA/ORA USCITA DEFINITIVA', false, '');
if (is_object($form->fields['dataorauscitadefinitiva'])) $form->fields['dataorauscitadefinitiva']->hslots = 60;

    
    new form_textbox($form, 'codicebadge', 'CODICE BADGE', false, 30, 255, '');
if (is_object($form->fields['codicebadge'])) $form->fields['codicebadge']->defaultvalue = worktable_parse_default_expression('${prefissocodiceabarre}${codice riga}', $form);

    
    new form_static_listbox($form, 'turno', 'TURNO', '1,2,3,4,1-2,2-3,1-2-3,3-4,1-2-3-4,ALTRO', false, '');
if (is_object($form->fields['turno'])) $form->fields['turno']->defaultvalue = worktable_get_last_value_from_file('TURNO');
if (is_object($form->fields['turno'])) $form->fields['turno']->write_value_to_file = worktable_get_safe_temp_filename('TURNO');

    
    new form_textbox($form, 'note', 'NOTE', false, 30, 255, '');

    

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

    if (is_object($form->fields['organizzazione']))
{
$form->fields['organizzazione']->autosuggest_table = 'worktable_worktable10';
$form->fields['organizzazione']->autosuggest_field = 'organizzazione';
$form->fields['organizzazione']->autosuggest_idfield = 'id';
$form->fields['organizzazione']->autosuggest_infofields = 'cognome,nome,codicefiscale,codicecurvo,cellulare,responsabile,note';
$form->fields['organizzazione']->autosuggest_pickfields = 'cognome,nome,codicefiscale,codicecurvo,cellulare,responsabile,note';
$form->fields['organizzazione']->autosuggest_destfields = 'cognome,nome,codicefiscale,codicecurvo,cellulare,responsabile,note';
}
if (is_object($form->fields['cognome']))
{
$form->fields['cognome']->autosuggest_table = 'worktable_worktable10';
$form->fields['cognome']->autosuggest_field = 'cognome';
$form->fields['cognome']->autosuggest_idfield = 'id';
$form->fields['cognome']->autosuggest_infofields = 'nome,codicefiscale,codicecurvo,cellulare,responsabile,note,organizzazione';
$form->fields['cognome']->autosuggest_pickfields = 'nome,codicefiscale,codicecurvo,cellulare,responsabile,note,organizzazione';
$form->fields['cognome']->autosuggest_destfields = 'nome,codicefiscale,codicecurvo,cellulare,responsabile,note,organizzazione';
}
if (is_object($form->fields['nome']))
{
$form->fields['nome']->autosuggest_table = 'worktable_worktable10';
$form->fields['nome']->autosuggest_field = 'nome';
$form->fields['nome']->autosuggest_idfield = 'id';
$form->fields['nome']->autosuggest_infofields = 'codicefiscale,codicecurvo,cellulare,responsabile,note,organizzazione,cognome';
$form->fields['nome']->autosuggest_pickfields = 'codicefiscale,codicecurvo,cellulare,responsabile,note,organizzazione,cognome';
$form->fields['nome']->autosuggest_destfields = 'codicefiscale,codicecurvo,cellulare,responsabile,note,organizzazione,cognome';
}
if (is_object($form->fields['codicefiscale']))
{
$form->fields['codicefiscale']->autosuggest_table = 'worktable_worktable10';
$form->fields['codicefiscale']->autosuggest_field = 'codicefiscale';
$form->fields['codicefiscale']->autosuggest_idfield = 'id';
$form->fields['codicefiscale']->autosuggest_infofields = 'codicecurvo,cellulare,responsabile,note,organizzazione,cognome,nome';
$form->fields['codicefiscale']->autosuggest_pickfields = 'codicecurvo,cellulare,responsabile,note,organizzazione,cognome,nome';
$form->fields['codicefiscale']->autosuggest_destfields = 'codicecurvo,cellulare,responsabile,note,organizzazione,cognome,nome';
}
if (is_object($form->fields['codicecurvo']))
{
$form->fields['codicecurvo']->autosuggest_table = 'worktable_worktable10';
$form->fields['codicecurvo']->autosuggest_field = 'codicecurvo';
$form->fields['codicecurvo']->autosuggest_idfield = 'id';
$form->fields['codicecurvo']->autosuggest_infofields = 'cellulare,responsabile,note,organizzazione,cognome,nome,codicefiscale';
$form->fields['codicecurvo']->autosuggest_pickfields = 'cellulare,responsabile,note,organizzazione,cognome,nome,codicefiscale';
$form->fields['codicecurvo']->autosuggest_destfields = 'cellulare,responsabile,note,organizzazione,cognome,nome,codicefiscale';
}
if (is_object($form->fields['cellulare']))
{
$form->fields['cellulare']->autosuggest_table = 'worktable_worktable10';
$form->fields['cellulare']->autosuggest_field = 'cellulare';
$form->fields['cellulare']->autosuggest_idfield = 'id';
$form->fields['cellulare']->autosuggest_infofields = 'responsabile,note,organizzazione,cognome,nome,codicefiscale,codicecurvo';
$form->fields['cellulare']->autosuggest_pickfields = 'responsabile,note,organizzazione,cognome,nome,codicefiscale,codicecurvo';
$form->fields['cellulare']->autosuggest_destfields = 'responsabile,note,organizzazione,cognome,nome,codicefiscale,codicecurvo';
}
if (is_object($form->fields['responsabile']))
{
$form->fields['responsabile']->autosuggest_table = 'worktable_worktable10';
$form->fields['responsabile']->autosuggest_field = 'responsabile';
$form->fields['responsabile']->autosuggest_idfield = 'id';
$form->fields['responsabile']->autosuggest_infofields = 'note,organizzazione,cognome,nome,codicefiscale,codicecurvo,cellulare';
$form->fields['responsabile']->autosuggest_pickfields = 'note,organizzazione,cognome,nome,codicefiscale,codicecurvo,cellulare';
$form->fields['responsabile']->autosuggest_destfields = 'note,organizzazione,cognome,nome,codicefiscale,codicecurvo,cellulare';
}
if (is_object($form->fields['note']))
{
$form->fields['note']->autosuggest_table = 'worktable_worktable10';
$form->fields['note']->autosuggest_field = 'note';
$form->fields['note']->autosuggest_idfield = 'id';
$form->fields['note']->autosuggest_infofields = 'organizzazione,cognome,nome,codicefiscale,codicecurvo,cellulare,responsabile';
$form->fields['note']->autosuggest_pickfields = 'organizzazione,cognome,nome,codicefiscale,codicecurvo,cellulare,responsabile';
$form->fields['note']->autosuggest_destfields = 'organizzazione,cognome,nome,codicefiscale,codicecurvo,cellulare,responsabile';
}


    $form->process();
    
    $form->draw();

} else {

      require(CAMILA_DIR . 'datagrid/report.class.php');

      $report_fields = 'id,cf_bool_is_special,cf_bool_is_selected,organizzazione,cognome,nome,codicefiscale,codicecurvo,cellulare,responsabile,dataarrivo,datapartenza,mansione,beneficidilegge,dataoraregistrazione,dataorauscitadefinitiva,codicebadge,turno,note,created,created_by,created_by_surname,created_by_name,last_upd,last_upd_by,last_upd_by_surname,last_upd_by_name,mod_num';
      $default_fields = 'cf_bool_is_special,cf_bool_is_selected,organizzazione,cognome,nome,codicefiscale,codicecurvo,cellulare,responsabile,dataarrivo,datapartenza,mansione,beneficidilegge,dataoraregistrazione,dataorauscitadefinitiva,codicebadge,turno,note';
      if ($_CAMILA['page']->camila_exporting())
          $mapping = 'created=Data creazione#last_upd=Ultimo aggiornamento#last_upd_by=Utente ult. agg.#last_upd_src=Sorgente Ult. agg.#last_upd_by_name=Nome Utente ult. agg.#last_upd_by_surname=Cognome Utente ult. agg.#mod_num=Num. mod.#id=Cod. riga#created_by=Utente creaz.#created_src=Sorgente creaz.#created_by_surname=Cognome Utente creaz.#created_by_name=Nome Utente creaz.#cf_bool_is_special=contrassegnati come speciali#cf_bool_is_selected=selezionati#organizzazione=ORGANIZZAZIONE#cognome=COGNOME#nome=NOME#codicefiscale=CODICE FISCALE#codicecurvo=CODICE C.U.R.V.O.#cellulare=CELLULARE#responsabile=RESPONSABILE#dataarrivo=DATA ARRIVO#datapartenza=DATA PARTENZA#mansione=MANSIONE#beneficidilegge=BENEFICI DI LEGGE#dataoraregistrazione=DATA/ORA REGISTRAZIONE#dataorauscitadefinitiva=DATA/ORA USCITA DEFINITIVA#codicebadge=CODICE BADGE#turno=TURNO#note=NOTE';
      else
          $mapping = 'created=Data creazione#last_upd=Ultimo aggiornamento#last_upd_by=Utente ult. agg.#last_upd_src=Sorgente Ult. agg.#last_upd_by_name=Nome Utente ult. agg.#last_upd_by_surname=Cognome Utente ult. agg.#mod_num=Num. mod.#id=Cod. riga#created_by=Utente creaz.#created_src=Sorgente creaz.#created_by_surname=Cognome Utente creaz.#created_by_name=Nome Utente creaz.#cf_bool_is_special=contrassegnati come speciali#cf_bool_is_selected=selezionati#organizzazione=ORGANIZZAZIONE#cognome=COGNOME#nome=NOME#codicefiscale=CODICE FISCALE#codicecurvo=CODICE C.U.R.V.O.#cellulare=CELLULARE#responsabile=RESPONSABILE#dataarrivo=DATA ARRIVO#datapartenza=DATA PARTENZA#mansione=MANSIONE#beneficidilegge=BENEFICI DI LEGGE#dataoraregistrazione=DATA/ORA REG.#dataorauscitadefinitiva=DATA/ORA USCITA#codicebadge=CODICE BADGE#turno=TURNO#note=NOTE';

      $stmt = 'select ' . $report_fields . ' from worktable_worktable6';
      $report = new report($stmt, '', 'organizzazione', 'asc', $mapping, null, 'id', 'cf_bool_is_special,cf_bool_is_selected,organizzazione,cognome,nome,codicefiscale,codicecurvo,cellulare,responsabile,dataarrivo,datapartenza,mansione,beneficidilegge,dataoraregistrazione,dataorauscitadefinitiva,codicebadge,turno,note', '', true, true);

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