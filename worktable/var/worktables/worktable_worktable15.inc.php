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


if (camila_form_in_update_mode(worktable_worktable15)) {

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
    

    $form = new dbform('worktable_worktable15', 'id');

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

    
    new form_textbox($form, 'organizzazione', 'ORGANIZZAZIONE', false, 30, 255, 'uppercase');

    
    new form_textbox($form, 'cognome', 'COGNOME', false, 30, 255, 'uppercase');

    
    new form_textbox($form, 'nome', 'NOME', false, 30, 255, 'uppercase');

    
    new form_textbox($form, 'codicefiscale', 'CODICE FISCALE', false, 30, 255, 'uppercase');

    
    new form_textbox($form, 'codicecurvo', 'CODICE C.U.R.V.O.', false, 30, 255, '');

    
    new form_textbox($form, 'cellulare', 'CELLULARE', false, 30, 255, '');

    
    new form_static_listbox($form, 'responsabile', 'RESPONSABILE', 'N,S', false, 'uppercase');

    
    new form_date($form, 'dataarrivo', 'DATA ARRIVO', false, '');

    
    new form_textbox($form, 'oraarrivo', 'ORA ARRIVO', false, 30, 255, '');

    
    new form_date($form, 'datapartenza', 'DATA PARTENZA', false, '');

    
    new form_textbox($form, 'orapartenza', 'ORA PARTENZA', false, 30, 255, '');

    
    new form_static_listbox($form, 'turno', 'COMPITO', 'Assistenza alla popolazione,Assistenza ai soggetti più vulnerabili,Informazione alla popolazione,Logistica,Soccorso e assistenza sanitaria,Uso di attrezzature speciali - conduzione mezzi speciali,Predisposizione e somministrazione di pasti,Prevenzione e lotta attiva incendi boschivi e di interfaccia,Supporto organizzativo (sale operative - segreteria),Presidio del territorio,Ripristino stato dei luoghi di tipo non specialistico,Attività formative,Radio e telecomunicazioni,Attività subacquee,Attività cinofile', false, '');

    
    new form_static_listbox($form, 'nuovocampo', 'MANSIONE', 'OPERATORE LOGISTICO (GENERICO),OPERATORE LOGISTICO (INSACCHETTAMENTO),OPERATORE LOGISTICO (MOTOSEGA),OPERATORE LOGISTICO (MOTOPOMPE),OPERATORE LOGISTICO (SUB),OPERATORE CINOFILO,OPERATORE SEGRETERIA,OPERATORE SALA OPERATIVA,OPERATORE RADIO,OPERATORE NAUTICO,ELETTRICISTA,MURATORE,IDRAULICO,OPERATORE SANITARIO,OPERATORE CUCINA,OPERATORE ANTINCENDIO,OPERATORE A CAVALLO,OPERATORE SUBACQUEO', false, '');

    
    new form_textbox($form, 'nuovocampo1', 'TURNO', false, 30, 255, '');

    
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

    

    $form->process();
    
    $form->draw();

} else {

      require(CAMILA_DIR . 'datagrid/report.class.php');

      $report_fields = 'id,cf_bool_is_special,cf_bool_is_selected,organizzazione,cognome,nome,codicefiscale,codicecurvo,cellulare,responsabile,dataarrivo,oraarrivo,datapartenza,orapartenza,turno,nuovocampo,nuovocampo1,note,created,created_by,created_by_surname,created_by_name,last_upd,last_upd_by,last_upd_by_surname,last_upd_by_name,mod_num';
      $default_fields = 'cf_bool_is_special,cf_bool_is_selected,organizzazione,cognome,nome,codicefiscale,codicecurvo,cellulare,responsabile,dataarrivo,oraarrivo,datapartenza,orapartenza,turno,nuovocampo,nuovocampo1,note';

      if (isset($_REQUEST['camila_rest'])) {
          $report_fields = str_replace('cf_bool_is_special,', '', $report_fields);
          $report_fields = str_replace('cf_bool_is_selected,', '', $report_fields);
          $default_fields = $report_fields;
      }

      if ($_CAMILA['page']->camila_exporting())
          $mapping = 'created=Data creazione#last_upd=Ultimo aggiornamento#last_upd_by=Utente ult. agg.#last_upd_src=Sorgente Ult. agg.#last_upd_by_name=Nome Utente ult. agg.#last_upd_by_surname=Cognome Utente ult. agg.#mod_num=Num. mod.#id=Cod. riga#created_by=Utente creaz.#created_src=Sorgente creaz.#created_by_surname=Cognome Utente creaz.#created_by_name=Nome Utente creaz.#cf_bool_is_special=contrassegnati come speciali#cf_bool_is_selected=selezionati#organizzazione=ORGANIZZAZIONE#cognome=COGNOME#nome=NOME#codicefiscale=CODICE FISCALE#codicecurvo=CODICE C.U.R.V.O.#cellulare=CELLULARE#responsabile=RESPONSABILE#dataarrivo=DATA ARRIVO#oraarrivo=ORA ARRIVO#datapartenza=DATA PARTENZA#orapartenza=ORA PARTENZA#turno=COMPITO#nuovocampo=MANSIONE#nuovocampo1=TURNO#note=NOTE';
      else
          $mapping = 'created=Data creazione#last_upd=Ultimo aggiornamento#last_upd_by=Utente ult. agg.#last_upd_src=Sorgente Ult. agg.#last_upd_by_name=Nome Utente ult. agg.#last_upd_by_surname=Cognome Utente ult. agg.#mod_num=Num. mod.#id=Cod. riga#created_by=Utente creaz.#created_src=Sorgente creaz.#created_by_surname=Cognome Utente creaz.#created_by_name=Nome Utente creaz.#cf_bool_is_special=contrassegnati come speciali#cf_bool_is_selected=selezionati#organizzazione=ORGANIZZAZIONE#cognome=COGNOME#nome=NOME#codicefiscale=CODICE FISCALE#codicecurvo=CODICE C.U.R.V.O.#cellulare=CELLULARE#responsabile=RESPONSABILE#dataarrivo=DATA ARRIVO#oraarrivo=ORA ARRIVO#datapartenza=DATA PARTENZA#orapartenza=ORA PARTENZA#turno=COMPITO#nuovocampo=MANSIONE#nuovocampo1=TURNO#note=NOTE';

      $filter = '';

      if ($_CAMILA['user_visibility_type']=='personal')
          $filter= ' where created_by='.$_CAMILA['db']->qstr($_CAMILA['user']);

      $stmt = 'select ' . $report_fields . ' from worktable_worktable15';
      $report = new report($stmt.$filter, '', 'organizzazione', 'asc', $mapping, null, 'id', 'cf_bool_is_special,cf_bool_is_selected,organizzazione,cognome,nome,codicefiscale,codicecurvo,cellulare,responsabile,dataarrivo,oraarrivo,datapartenza,orapartenza,turno,nuovocampo,nuovocampo1,note', '', (isset($_REQUEST['camila_rest'])) ? false : true, (isset($_REQUEST['camila_rest'])) ? false : true);

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
$jarr['url'] = "javascript:camila_inline_update_selected('organizzazione','')";
$jarr['visible'] = 'yes';
$jarr['short_title'] = 'MODIFICA ORGANIZZAZIONE...';
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
$jarr['url'] = "javascript:camila_inline_update_selected('codicefiscale','')";
$jarr['visible'] = 'yes';
$jarr['short_title'] = 'MODIFICA CODICE FISCALE...';
$jarr['parent'] = 'index.php';
$report->menuitems[]=$jarr;
$jarr=Array();
$jarr['url'] = "javascript:camila_inline_update_selected('codicecurvo','')";
$jarr['visible'] = 'yes';
$jarr['short_title'] = 'MODIFICA CODICE C.U.R.V.O....';
$jarr['parent'] = 'index.php';
$report->menuitems[]=$jarr;
$jarr=Array();
$jarr['url'] = "javascript:camila_inline_update_selected('cellulare','')";
$jarr['visible'] = 'yes';
$jarr['short_title'] = 'MODIFICA CELLULARE...';
$jarr['parent'] = 'index.php';
$report->menuitems[]=$jarr;
$jarr=Array();
$jarr['url'] = 'responsabile';
$jarr['visible'] = 'yes';
$jarr['short_title'] = 'MODIFICA RESPONSABILE';
$jarr['parent'] = 'index.php';
$report->menuitems[]=$jarr;
$jarr=Array();
$jarr['url'] = "javascript:camila_inline_update_selected('responsabile','N')";
$jarr['visible'] = 'yes';
$jarr['short_title'] = 'N';
$jarr['parent'] = 'responsabile';
$report->menuitems[]=$jarr;
$jarr=Array();
$jarr['url'] = "javascript:camila_inline_update_selected('responsabile','S')";
$jarr['visible'] = 'yes';
$jarr['short_title'] = 'S';
$jarr['parent'] = 'responsabile';
$report->menuitems[]=$jarr;
$jarr=Array();
$jarr['url'] = "javascript:camila_inline_update_selected('dataarrivo','')";
$jarr['visible'] = 'yes';
$jarr['short_title'] = 'MODIFICA DATA ARRIVO...';
$jarr['parent'] = 'index.php';
$report->menuitems[]=$jarr;
$jarr=Array();
$jarr['url'] = "javascript:camila_inline_update_selected('oraarrivo','')";
$jarr['visible'] = 'yes';
$jarr['short_title'] = 'MODIFICA ORA ARRIVO...';
$jarr['parent'] = 'index.php';
$report->menuitems[]=$jarr;
$jarr=Array();
$jarr['url'] = "javascript:camila_inline_update_selected('datapartenza','')";
$jarr['visible'] = 'yes';
$jarr['short_title'] = 'MODIFICA DATA PARTENZA...';
$jarr['parent'] = 'index.php';
$report->menuitems[]=$jarr;
$jarr=Array();
$jarr['url'] = "javascript:camila_inline_update_selected('orapartenza','')";
$jarr['visible'] = 'yes';
$jarr['short_title'] = 'MODIFICA ORA PARTENZA...';
$jarr['parent'] = 'index.php';
$report->menuitems[]=$jarr;
$jarr=Array();
$jarr['url'] = 'turno';
$jarr['visible'] = 'yes';
$jarr['short_title'] = 'MODIFICA COMPITO';
$jarr['parent'] = 'index.php';
$report->menuitems[]=$jarr;
$jarr=Array();
$jarr['url'] = "javascript:camila_inline_update_selected('turno','Assistenza alla popolazione')";
$jarr['visible'] = 'yes';
$jarr['short_title'] = 'Assistenza alla popolazione';
$jarr['parent'] = 'turno';
$report->menuitems[]=$jarr;
$jarr=Array();
$jarr['url'] = "javascript:camila_inline_update_selected('turno','Assistenza ai soggetti più vulnerabili')";
$jarr['visible'] = 'yes';
$jarr['short_title'] = 'Assistenza ai soggetti più vulnerabili';
$jarr['parent'] = 'turno';
$report->menuitems[]=$jarr;
$jarr=Array();
$jarr['url'] = "javascript:camila_inline_update_selected('turno','Informazione alla popolazione')";
$jarr['visible'] = 'yes';
$jarr['short_title'] = 'Informazione alla popolazione';
$jarr['parent'] = 'turno';
$report->menuitems[]=$jarr;
$jarr=Array();
$jarr['url'] = "javascript:camila_inline_update_selected('turno','Logistica')";
$jarr['visible'] = 'yes';
$jarr['short_title'] = 'Logistica';
$jarr['parent'] = 'turno';
$report->menuitems[]=$jarr;
$jarr=Array();
$jarr['url'] = "javascript:camila_inline_update_selected('turno','Soccorso e assistenza sanitaria')";
$jarr['visible'] = 'yes';
$jarr['short_title'] = 'Soccorso e assistenza sanitaria';
$jarr['parent'] = 'turno';
$report->menuitems[]=$jarr;
$jarr=Array();
$jarr['url'] = "javascript:camila_inline_update_selected('turno','Uso di attrezzature speciali - conduzione mezzi speciali')";
$jarr['visible'] = 'yes';
$jarr['short_title'] = 'Uso di attrezzature speciali - conduzione mezzi speciali';
$jarr['parent'] = 'turno';
$report->menuitems[]=$jarr;
$jarr=Array();
$jarr['url'] = "javascript:camila_inline_update_selected('turno','Predisposizione e somministrazione di pasti')";
$jarr['visible'] = 'yes';
$jarr['short_title'] = 'Predisposizione e somministrazione di pasti';
$jarr['parent'] = 'turno';
$report->menuitems[]=$jarr;
$jarr=Array();
$jarr['url'] = "javascript:camila_inline_update_selected('turno','Prevenzione e lotta attiva incendi boschivi e di interfaccia')";
$jarr['visible'] = 'yes';
$jarr['short_title'] = 'Prevenzione e lotta attiva incendi boschivi e di interfaccia';
$jarr['parent'] = 'turno';
$report->menuitems[]=$jarr;
$jarr=Array();
$jarr['url'] = "javascript:camila_inline_update_selected('turno','Supporto organizzativo (sale operative - segreteria)')";
$jarr['visible'] = 'yes';
$jarr['short_title'] = 'Supporto organizzativo (sale operative - segreteria)';
$jarr['parent'] = 'turno';
$report->menuitems[]=$jarr;
$jarr=Array();
$jarr['url'] = "javascript:camila_inline_update_selected('turno','Presidio del territorio')";
$jarr['visible'] = 'yes';
$jarr['short_title'] = 'Presidio del territorio';
$jarr['parent'] = 'turno';
$report->menuitems[]=$jarr;
$jarr=Array();
$jarr['url'] = "javascript:camila_inline_update_selected('turno','Ripristino stato dei luoghi di tipo non specialistico')";
$jarr['visible'] = 'yes';
$jarr['short_title'] = 'Ripristino stato dei luoghi di tipo non specialistico';
$jarr['parent'] = 'turno';
$report->menuitems[]=$jarr;
$jarr=Array();
$jarr['url'] = "javascript:camila_inline_update_selected('turno','Attività formative')";
$jarr['visible'] = 'yes';
$jarr['short_title'] = 'Attività formative';
$jarr['parent'] = 'turno';
$report->menuitems[]=$jarr;
$jarr=Array();
$jarr['url'] = "javascript:camila_inline_update_selected('turno','Radio e telecomunicazioni')";
$jarr['visible'] = 'yes';
$jarr['short_title'] = 'Radio e telecomunicazioni';
$jarr['parent'] = 'turno';
$report->menuitems[]=$jarr;
$jarr=Array();
$jarr['url'] = "javascript:camila_inline_update_selected('turno','Attività subacquee')";
$jarr['visible'] = 'yes';
$jarr['short_title'] = 'Attività subacquee';
$jarr['parent'] = 'turno';
$report->menuitems[]=$jarr;
$jarr=Array();
$jarr['url'] = "javascript:camila_inline_update_selected('turno','Attività cinofile')";
$jarr['visible'] = 'yes';
$jarr['short_title'] = 'Attività cinofile';
$jarr['parent'] = 'turno';
$report->menuitems[]=$jarr;
$jarr=Array();
$jarr['url'] = 'nuovocampo';
$jarr['visible'] = 'yes';
$jarr['short_title'] = 'MODIFICA MANSIONE';
$jarr['parent'] = 'index.php';
$report->menuitems[]=$jarr;
$jarr=Array();
$jarr['url'] = "javascript:camila_inline_update_selected('nuovocampo','OPERATORE LOGISTICO (GENERICO)')";
$jarr['visible'] = 'yes';
$jarr['short_title'] = 'OPERATORE LOGISTICO (GENERICO)';
$jarr['parent'] = 'nuovocampo';
$report->menuitems[]=$jarr;
$jarr=Array();
$jarr['url'] = "javascript:camila_inline_update_selected('nuovocampo','OPERATORE LOGISTICO (INSACCHETTAMENTO)')";
$jarr['visible'] = 'yes';
$jarr['short_title'] = 'OPERATORE LOGISTICO (INSACCHETTAMENTO)';
$jarr['parent'] = 'nuovocampo';
$report->menuitems[]=$jarr;
$jarr=Array();
$jarr['url'] = "javascript:camila_inline_update_selected('nuovocampo','OPERATORE LOGISTICO (MOTOSEGA)')";
$jarr['visible'] = 'yes';
$jarr['short_title'] = 'OPERATORE LOGISTICO (MOTOSEGA)';
$jarr['parent'] = 'nuovocampo';
$report->menuitems[]=$jarr;
$jarr=Array();
$jarr['url'] = "javascript:camila_inline_update_selected('nuovocampo','OPERATORE LOGISTICO (MOTOPOMPE)')";
$jarr['visible'] = 'yes';
$jarr['short_title'] = 'OPERATORE LOGISTICO (MOTOPOMPE)';
$jarr['parent'] = 'nuovocampo';
$report->menuitems[]=$jarr;
$jarr=Array();
$jarr['url'] = "javascript:camila_inline_update_selected('nuovocampo','OPERATORE LOGISTICO (SUB)')";
$jarr['visible'] = 'yes';
$jarr['short_title'] = 'OPERATORE LOGISTICO (SUB)';
$jarr['parent'] = 'nuovocampo';
$report->menuitems[]=$jarr;
$jarr=Array();
$jarr['url'] = "javascript:camila_inline_update_selected('nuovocampo','OPERATORE CINOFILO')";
$jarr['visible'] = 'yes';
$jarr['short_title'] = 'OPERATORE CINOFILO';
$jarr['parent'] = 'nuovocampo';
$report->menuitems[]=$jarr;
$jarr=Array();
$jarr['url'] = "javascript:camila_inline_update_selected('nuovocampo','OPERATORE SEGRETERIA')";
$jarr['visible'] = 'yes';
$jarr['short_title'] = 'OPERATORE SEGRETERIA';
$jarr['parent'] = 'nuovocampo';
$report->menuitems[]=$jarr;
$jarr=Array();
$jarr['url'] = "javascript:camila_inline_update_selected('nuovocampo','OPERATORE SALA OPERATIVA')";
$jarr['visible'] = 'yes';
$jarr['short_title'] = 'OPERATORE SALA OPERATIVA';
$jarr['parent'] = 'nuovocampo';
$report->menuitems[]=$jarr;
$jarr=Array();
$jarr['url'] = "javascript:camila_inline_update_selected('nuovocampo','OPERATORE RADIO')";
$jarr['visible'] = 'yes';
$jarr['short_title'] = 'OPERATORE RADIO';
$jarr['parent'] = 'nuovocampo';
$report->menuitems[]=$jarr;
$jarr=Array();
$jarr['url'] = "javascript:camila_inline_update_selected('nuovocampo','OPERATORE NAUTICO')";
$jarr['visible'] = 'yes';
$jarr['short_title'] = 'OPERATORE NAUTICO';
$jarr['parent'] = 'nuovocampo';
$report->menuitems[]=$jarr;
$jarr=Array();
$jarr['url'] = "javascript:camila_inline_update_selected('nuovocampo','ELETTRICISTA')";
$jarr['visible'] = 'yes';
$jarr['short_title'] = 'ELETTRICISTA';
$jarr['parent'] = 'nuovocampo';
$report->menuitems[]=$jarr;
$jarr=Array();
$jarr['url'] = "javascript:camila_inline_update_selected('nuovocampo','MURATORE')";
$jarr['visible'] = 'yes';
$jarr['short_title'] = 'MURATORE';
$jarr['parent'] = 'nuovocampo';
$report->menuitems[]=$jarr;
$jarr=Array();
$jarr['url'] = "javascript:camila_inline_update_selected('nuovocampo','IDRAULICO')";
$jarr['visible'] = 'yes';
$jarr['short_title'] = 'IDRAULICO';
$jarr['parent'] = 'nuovocampo';
$report->menuitems[]=$jarr;
$jarr=Array();
$jarr['url'] = "javascript:camila_inline_update_selected('nuovocampo','OPERATORE SANITARIO')";
$jarr['visible'] = 'yes';
$jarr['short_title'] = 'OPERATORE SANITARIO';
$jarr['parent'] = 'nuovocampo';
$report->menuitems[]=$jarr;
$jarr=Array();
$jarr['url'] = "javascript:camila_inline_update_selected('nuovocampo','OPERATORE CUCINA')";
$jarr['visible'] = 'yes';
$jarr['short_title'] = 'OPERATORE CUCINA';
$jarr['parent'] = 'nuovocampo';
$report->menuitems[]=$jarr;
$jarr=Array();
$jarr['url'] = "javascript:camila_inline_update_selected('nuovocampo','OPERATORE ANTINCENDIO')";
$jarr['visible'] = 'yes';
$jarr['short_title'] = 'OPERATORE ANTINCENDIO';
$jarr['parent'] = 'nuovocampo';
$report->menuitems[]=$jarr;
$jarr=Array();
$jarr['url'] = "javascript:camila_inline_update_selected('nuovocampo','OPERATORE A CAVALLO')";
$jarr['visible'] = 'yes';
$jarr['short_title'] = 'OPERATORE A CAVALLO';
$jarr['parent'] = 'nuovocampo';
$report->menuitems[]=$jarr;
$jarr=Array();
$jarr['url'] = "javascript:camila_inline_update_selected('nuovocampo','OPERATORE SUBACQUEO')";
$jarr['visible'] = 'yes';
$jarr['short_title'] = 'OPERATORE SUBACQUEO';
$jarr['parent'] = 'nuovocampo';
$report->menuitems[]=$jarr;
$jarr=Array();
$jarr['url'] = "javascript:camila_inline_update_selected('nuovocampo1','')";
$jarr['visible'] = 'yes';
$jarr['short_title'] = 'MODIFICA TURNO...';
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