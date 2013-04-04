<?php

require(CAMILA_LIB_DIR . 'minitemplator/MiniTemplator.class.php');

$_CAMILA['page']->camila_worktable = true;

$wt_id = substr($_SERVER['PHP_SELF'], 12, -4);

if (intval($wt_id) > 0)
    $_CAMILA['page']->camila_worktable_id = $wt_id;

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

    
    require_once(CAMILA_DIR . 'datagrid/elements/form/integer.php');
    
    require_once(CAMILA_DIR . 'datagrid/elements/form/textbox.php');
    
    require_once(CAMILA_DIR . 'datagrid/elements/form/static_listbox.php');
    
    require_once(CAMILA_DIR . 'datagrid/elements/form/date.php');
    
    require_once(CAMILA_DIR . 'datagrid/elements/form/datetime.php');
    
    require_once(CAMILA_DIR . 'datagrid/elements/form/phonenumber.php');
    
    require_once(CAMILA_DIR . 'datagrid/elements/form/textarea.php');
    

    $form = new dbform('worktable_worktable6', 'id');

    if ($_CAMILA['adm_user_group'] != CAMILA_ADM_USER_GROUP)
    {
        $form->caninsert = true;
        $form->candelete = true;
        $form->canupdate = true;
    }
    else
    if ($_CAMILA['adm_user_group'] == CAMILA_ADM_USER_GROUP)
    {
        $form->caninsert = true;
        $form->candelete = true;
        $form->canupdate = true;
    }

    $form->drawrules = true;
    $form->drawheadersubmitbutton = true;

    new form_textbox($form, 'id', camila_get_translation('camila.worktable.field.id'));
    if (is_object($form->fields['id'])) {
        if ($_REQUEST['camila_update'] == 'new' && !isset($_REQUEST['camila_phpform_sent'])) {
            $_CAMILA['db_genid'] = $_CAMILA['db']->GenID(CAMILA_APPLICATION_PREFIX.'worktableseq', 100000);
            $form->fields['id']->defaultvalue = $_CAMILA['db_genid'];
        }
        $form->fields['id']->updatable = false;
        $form->fields['id']->forcedraw = true;
    }

    
    new form_textbox($form, 'tenda', 'TENDA', false, 30, 255, '');

    
    new form_textbox($form, 'cognome', 'COGNOME', false, 30, 255, '');

    
    new form_textbox($form, 'nome', 'NOME', false, 30, 255, '');

    
    new form_textbox($form, 'annodinascita', 'ANNO DI NASCITA', false, 30, 255, '');

    
    new form_textbox($form, 'codicebadge', 'CODICE BADGE', false, 30, 255, '');

    
    new form_date($form, 'ospitedal', 'OSPITE DAL', false, '');

    
    new form_date($form, 'ospiteal', 'OSPITE AL', false, '');
if (is_object($form->fields['ospiteal'])) $form->fields['ospiteal']->defaultvalue = date('Y-m-d');

    
    new form_static_listbox($form, 'sesso', 'SESSO', 'M,F', false, '');

    
    new form_textbox($form, 'motivazione', 'MOTIVAZIONE', false, 30, 255, '');

    
    new form_textbox($form, 'note', 'NOTE', false, 30, 255, '');

    

    if (CAMILA_WORKTABLE_SPECIAL_ICON_ENABLED || $_CAMILA['adm_user_group'] == CAMILA_ADM_USER_GROUP)
        new form_static_listbox($form, 'cf_bool_is_selected', camila_get_translation('camila.worktable.field.selected'), camila_get_translation('camila.worktable.options.noyes'));

    if (CAMILA_WORKTABLE_SELECTED_ICON_ENABLED || $_CAMILA['adm_user_group'] == CAMILA_ADM_USER_GROUP)
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

    if (is_object($form->fields['tenda']))
{
$form->fields['tenda']->autosuggest_table = 'worktable_worktable5';
$form->fields['tenda']->autosuggest_field = 'tenda';
$form->fields['tenda']->autosuggest_idfield = 'id';
$form->fields['tenda']->autosuggest_infofields = 'cognome,nome,annodinascita,codicebadge,ospitedal,sesso,note';
$form->fields['tenda']->autosuggest_pickfields = 'cognome,nome,annodinascita,codicebadge,ospitedal,sesso,note';
$form->fields['tenda']->autosuggest_destfields = 'cognome,nome,annodinascita,codicebadge,ospitedal,sesso,note';
}
if (is_object($form->fields['cognome']))
{
$form->fields['cognome']->autosuggest_table = 'worktable_worktable5';
$form->fields['cognome']->autosuggest_field = 'cognome';
$form->fields['cognome']->autosuggest_idfield = 'id';
$form->fields['cognome']->autosuggest_infofields = 'nome,annodinascita,codicebadge,ospitedal,sesso,note,tenda';
$form->fields['cognome']->autosuggest_pickfields = 'nome,annodinascita,codicebadge,ospitedal,sesso,note,tenda';
$form->fields['cognome']->autosuggest_destfields = 'nome,annodinascita,codicebadge,ospitedal,sesso,note,tenda';
}
if (is_object($form->fields['nome']))
{
$form->fields['nome']->autosuggest_table = 'worktable_worktable5';
$form->fields['nome']->autosuggest_field = 'nome';
$form->fields['nome']->autosuggest_idfield = 'id';
$form->fields['nome']->autosuggest_infofields = 'annodinascita,codicebadge,ospitedal,sesso,note,tenda,cognome';
$form->fields['nome']->autosuggest_pickfields = 'annodinascita,codicebadge,ospitedal,sesso,note,tenda,cognome';
$form->fields['nome']->autosuggest_destfields = 'annodinascita,codicebadge,ospitedal,sesso,note,tenda,cognome';
}
if (is_object($form->fields['annodinascita']))
{
$form->fields['annodinascita']->autosuggest_table = 'worktable_worktable5';
$form->fields['annodinascita']->autosuggest_field = 'annodinascita';
$form->fields['annodinascita']->autosuggest_idfield = 'id';
$form->fields['annodinascita']->autosuggest_infofields = 'codicebadge,ospitedal,sesso,note,tenda,cognome,nome';
$form->fields['annodinascita']->autosuggest_pickfields = 'codicebadge,ospitedal,sesso,note,tenda,cognome,nome';
$form->fields['annodinascita']->autosuggest_destfields = 'codicebadge,ospitedal,sesso,note,tenda,cognome,nome';
}
if (is_object($form->fields['codicebadge']))
{
$form->fields['codicebadge']->autosuggest_table = 'worktable_worktable5';
$form->fields['codicebadge']->autosuggest_field = 'codicebadge';
$form->fields['codicebadge']->autosuggest_idfield = 'id';
$form->fields['codicebadge']->autosuggest_infofields = 'ospitedal,sesso,note,tenda,cognome,nome,annodinascita';
$form->fields['codicebadge']->autosuggest_pickfields = 'ospitedal,sesso,note,tenda,cognome,nome,annodinascita';
$form->fields['codicebadge']->autosuggest_destfields = 'ospitedal,sesso,note,tenda,cognome,nome,annodinascita';
}
if (is_object($form->fields['ospitedal']))
{
$form->fields['ospitedal']->autosuggest_table = 'worktable_worktable5';
$form->fields['ospitedal']->autosuggest_field = 'ospitedal';
$form->fields['ospitedal']->autosuggest_idfield = 'id';
$form->fields['ospitedal']->autosuggest_infofields = 'sesso,note,tenda,cognome,nome,annodinascita,codicebadge';
$form->fields['ospitedal']->autosuggest_pickfields = 'sesso,note,tenda,cognome,nome,annodinascita,codicebadge';
$form->fields['ospitedal']->autosuggest_destfields = 'sesso,note,tenda,cognome,nome,annodinascita,codicebadge';
}
if (is_object($form->fields['sesso']))
{
$form->fields['sesso']->autosuggest_table = 'worktable_worktable5';
$form->fields['sesso']->autosuggest_field = 'sesso';
$form->fields['sesso']->autosuggest_idfield = 'id';
$form->fields['sesso']->autosuggest_infofields = 'note,tenda,cognome,nome,annodinascita,codicebadge,ospitedal';
$form->fields['sesso']->autosuggest_pickfields = 'note,tenda,cognome,nome,annodinascita,codicebadge,ospitedal';
$form->fields['sesso']->autosuggest_destfields = 'note,tenda,cognome,nome,annodinascita,codicebadge,ospitedal';
}
if (is_object($form->fields['note']))
{
$form->fields['note']->autosuggest_table = 'worktable_worktable5';
$form->fields['note']->autosuggest_field = 'note';
$form->fields['note']->autosuggest_idfield = 'id';
$form->fields['note']->autosuggest_infofields = 'tenda,cognome,nome,annodinascita,codicebadge,ospitedal,sesso';
$form->fields['note']->autosuggest_pickfields = 'tenda,cognome,nome,annodinascita,codicebadge,ospitedal,sesso';
$form->fields['note']->autosuggest_destfields = 'tenda,cognome,nome,annodinascita,codicebadge,ospitedal,sesso';
}


    $form->process();
    
    $form->draw();

} else {

      require(CAMILA_DIR . 'datagrid/report.class.php');

      $report_fields = 'id,cf_bool_is_special,cf_bool_is_selected,tenda,cognome,nome,annodinascita,codicebadge,ospitedal,ospiteal,sesso,motivazione,note,created,created_by,created_by_surname,created_by_name,last_upd,last_upd_by,last_upd_by_surname,last_upd_by_name,mod_num';
      $default_fields = 'cf_bool_is_special,cf_bool_is_selected,tenda,cognome,nome,annodinascita,codicebadge,ospitedal,ospiteal,sesso,motivazione,note';

      if (isset($_REQUEST['camila_rest'])) {
          $report_fields = str_replace('cf_bool_is_special,', '', $report_fields);
          $report_fields = str_replace('cf_bool_is_selected,', '', $report_fields);
          $default_fields = $report_fields;
      }

      if ($_CAMILA['page']->camila_exporting())
          $mapping = 'created=Data creazione#last_upd=Ultimo aggiornamento#last_upd_by=Utente ult. agg.#last_upd_src=Sorgente Ult. agg.#last_upd_by_name=Nome Utente ult. agg.#last_upd_by_surname=Cognome Utente ult. agg.#mod_num=Num. mod.#id=Cod. riga#created_by=Utente creaz.#created_src=Sorgente creaz.#created_by_surname=Cognome Utente creaz.#created_by_name=Nome Utente creaz.#cf_bool_is_special=contrassegnati come speciali#cf_bool_is_selected=selezionati#tenda=TENDA#cognome=COGNOME#nome=NOME#annodinascita=ANNO DI NASCITA#codicebadge=CODICE BADGE#ospitedal=OSPITE DAL#ospiteal=OSPITE AL#sesso=SESSO#motivazione=MOTIVAZIONE#note=NOTE';
      else
          $mapping = 'created=Data creazione#last_upd=Ultimo aggiornamento#last_upd_by=Utente ult. agg.#last_upd_src=Sorgente Ult. agg.#last_upd_by_name=Nome Utente ult. agg.#last_upd_by_surname=Cognome Utente ult. agg.#mod_num=Num. mod.#id=Cod. riga#created_by=Utente creaz.#created_src=Sorgente creaz.#created_by_surname=Cognome Utente creaz.#created_by_name=Nome Utente creaz.#cf_bool_is_special=contrassegnati come speciali#cf_bool_is_selected=selezionati#tenda=TENDA#cognome=COGNOME#nome=NOME#annodinascita=ANNO DI NASCITA#codicebadge=CODICE BADGE#ospitedal=OSPITE DAL#ospiteal=OSPITE AL#sesso=SESSO#motivazione=MOTIVAZIONE#note=NOTE';

      $filter = '';

      if ($_CAMILA['user_visibility_type']=='personal')
          $filter= ' where created_by='.$_CAMILA['db']->qstr($_CAMILA['user']);

      $stmt = 'select ' . $report_fields . ' from worktable_worktable6';
      $report = new report($stmt.$filter, '', 'ospiteal', 'desc', $mapping, null, 'id', 'cf_bool_is_special,cf_bool_is_selected,tenda,cognome,nome,annodinascita,codicebadge,ospitedal,ospiteal,sesso,motivazione,note', '', (isset($_REQUEST['camila_rest'])) ? false : true, (isset($_REQUEST['camila_rest'])) ? false : true);

      if (true && !isset($_REQUEST['camila_rest'])) {
          $report->additional_links = Array(camila_get_translation('camila.report.insertnew') => basename($_SERVER['PHP_SELF']) . '?camila_update=new');

          $myImage1 = new CHAW_image(CAMILA_IMG_DIR . 'wbmp/add.wbmp', CAMILA_IMG_DIR . 'png/add.png', '-');
          $report->additional_links_images = Array(camila_get_translation('camila.report.insertnew') => $myImage1);

          if (($_CAMILA['adm_user_group'] == CAMILA_ADM_USER_GROUP) || CAMILA_WORKTABLE_IMPORT_ENABLED)          
          $report->additional_links[camila_get_translation('camila.worktable.import')] = 'cf_worktable_wizard_step4.php?camila_custom=' . $wt_id . '&camila_returl=' . urlencode($_SERVER['PHP_SELF']);
      }

      if ($_CAMILA['adm_user_group'] == CAMILA_ADM_USER_GROUP) {
          $report->additional_links[camila_get_translation('camila.worktable.rebuild')] = 'cf_worktable_admin.php?camila_custom=' . $wt_id . '&camila_worktable_op=rebuild' . '&camila_returl=' . urlencode($_SERVER['PHP_SELF']);
          $report->additional_links[camila_get_translation('camila.worktable.reconfig')] = 'cf_worktable_wizard_step2.php?camila_custom=' . $wt_id . '&camila_returl=' . urlencode($_SERVER['PHP_SELF']);
      }

      if (CAMILA_WORKTABLE_CONFIRM_VIA_MAIL_ENABLED) {
          $report->additional_links[camila_get_translation('camila.worktable.confirm')] = basename($_SERVER['PHP_SELF']) . '?camila_visible_cols_only=y&camila_worktable_export=dataonly&camila_pagnum=-1&camila_export_filename=WORKTABLE&camila_export_action=sendmail&hidden=camila_xls&camila_export_format=camila_xls&camila_xls=Esporta';

          $myImage1 = new CHAW_image(CAMILA_IMG_DIR . 'wbmp/accept.wbmp', CAMILA_IMG_DIR . 'png/accept.png', '-');
          $report->additional_links_images[camila_get_translation('camila.worktable.confirm')]=$myImage1;

      }

      $report->formulas=Array();
      $report->queries=Array();

      $jarr=Array();
$jarr['url'] = "javascript:camila_inline_update_selected('tenda','')";
$jarr['visible'] = 'yes';
$jarr['short_title'] = 'MODIFICA TENDA...';
$jarr['parent'] = 'index.php';
$report->menuitems[]=$jarr;
$jarr=Array();
$jarr['url'] = "javascript:camila_inline_update_selected('cognome','')";
$jarr['visible'] = 'yes';
$jarr['short_title'] = 'MODIFICA COGNOME...';
$jarr['parent'] = 'index.php';
$report->menuitems[]=$jarr;
$jarr=Array();
$jarr['url'] = "javascript:camila_inline_update_selected('nome','')";
$jarr['visible'] = 'yes';
$jarr['short_title'] = 'MODIFICA NOME...';
$jarr['parent'] = 'index.php';
$report->menuitems[]=$jarr;
$jarr=Array();
$jarr['url'] = "javascript:camila_inline_update_selected('annodinascita','')";
$jarr['visible'] = 'yes';
$jarr['short_title'] = 'MODIFICA ANNO DI NASCITA...';
$jarr['parent'] = 'index.php';
$report->menuitems[]=$jarr;
$jarr=Array();
$jarr['url'] = "javascript:camila_inline_update_selected('codicebadge','')";
$jarr['visible'] = 'yes';
$jarr['short_title'] = 'MODIFICA CODICE BADGE...';
$jarr['parent'] = 'index.php';
$report->menuitems[]=$jarr;
$jarr=Array();
$jarr['url'] = "javascript:camila_inline_update_selected('ospitedal','')";
$jarr['visible'] = 'yes';
$jarr['short_title'] = 'MODIFICA OSPITE DAL...';
$jarr['parent'] = 'index.php';
$report->menuitems[]=$jarr;
$jarr=Array();
$jarr['url'] = "javascript:camila_inline_update_selected('ospiteal','')";
$jarr['visible'] = 'yes';
$jarr['short_title'] = 'MODIFICA OSPITE AL...';
$jarr['parent'] = 'index.php';
$report->menuitems[]=$jarr;
$jarr=Array();
$jarr['url'] = 'sesso';
$jarr['visible'] = 'yes';
$jarr['short_title'] = 'MODIFICA SESSO';
$jarr['parent'] = 'index.php';
$report->menuitems[]=$jarr;
$jarr=Array();
$jarr['url'] = "javascript:camila_inline_update_selected('sesso','M')";
$jarr['visible'] = 'yes';
$jarr['short_title'] = 'M';
$jarr['parent'] = 'sesso';
$report->menuitems[]=$jarr;
$jarr=Array();
$jarr['url'] = "javascript:camila_inline_update_selected('sesso','F')";
$jarr['visible'] = 'yes';
$jarr['short_title'] = 'F';
$jarr['parent'] = 'sesso';
$report->menuitems[]=$jarr;
$jarr=Array();
$jarr['url'] = "javascript:camila_inline_update_selected('motivazione','')";
$jarr['visible'] = 'yes';
$jarr['short_title'] = 'MODIFICA MOTIVAZIONE...';
$jarr['parent'] = 'index.php';
$report->menuitems[]=$jarr;
$jarr=Array();
$jarr['url'] = "javascript:camila_inline_update_selected('note','')";
$jarr['visible'] = 'yes';
$jarr['short_title'] = 'MODIFICA NOTE...';
$jarr['parent'] = 'index.php';
$report->menuitems[]=$jarr;


      $report->process();
      $report->draw();

}
?>