<?php

/* This File is part of Camila PHP Framework
   Copyright (C) 2006-2011 Umberto Bresciani

   Camila PHP Framework is free software; you can redistribute it and/or modify
   it under the terms of the GNU General Public License as published by
   the Free Software Foundation; either version 2 of the License, or
   (at your option) any later version.

   Camila PHP Framework is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU General Public License for more details.

   You should have received a copy of the GNU General Public License
   along with Camila PHP Framework; if not, write to the Free Software
   Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA */


  require_once(CAMILA_DIR . 'datagrid/form.class.php');
  require_once(CAMILA_DIR . 'datagrid/elements/form/hidden.php');
  require_once(CAMILA_DIR . 'datagrid/elements/form/filebox.php');
  require_once(CAMILA_DIR . 'datagrid/elements/form/textbox.php');
  require_once(CAMILA_DIR . 'datagrid/elements/form/template_file_listbox.php');
  require_once(CAMILA_DIR . 'datagrid/elements/form/checklist.php');
  require_once(CAMILA_DIR . 'datagrid/elements/form/text_separator.php');


  $export_deck_title = new CHAW_text(camila_get_translation('camila.export.options'), $_CAMILA['page_title_attributes']);
  $export_deck_title->set_br(2);
  $export_deck_title->set_color($_CAMILA['page_title_color'], $_CAMILA['page_title_boxcolor']);
  $_CAMILA['page']->add_text($export_deck_title);

  $form = new phpform('camila');
  $form->submitbutton = camila_get_translation('camila.export.xml2pdf');
  $form->drawrules = false;
  $form->preservecontext = true;

  global $_CAMILA;
  $pos = strrpos($_CAMILA['page_url'], '?');
  if ($pos !== false)
    new form_hidden($form, substr($_CAMILA['page_url'], $pos + 1));

  new form_template_file_listbox($form, 'xml2pdf', camila_get_translation('camila.export.template'), CAMILA_TMPL_DIR.'/'.$_CAMILA['lang'], false, $_CAMILA['adm_usergroup'], '', true);

  new form_text_separator(&$form, camila_get_translation('camila.export.options'));

  $nodata = 'n';
  new form_checklist($form, xml2pdf_checklist_options, '', Array(camila_get_translation('camila.export.nodata')), Array('y'));

  new form_text_separator(&$form, '');


  $form->process();

  $form->draw();

  $export_format = 'camila_xml2pdf';
  $url = $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING'];
  $url = ereg_replace("&" . $export_format, '', $url);
  $url = ereg_replace("\?" . $export_format, '', $url);
  $myLink = new CHAW_link(camila_get_translation('camila.back.page'), $url);
  $myImage = new HAW_image(CAMILA_IMG_DIR.'wbmp/resultset_previous.wbmp', CAMILA_IMG_DIR.'png/resultset_previous.png', '-');
  $myLink->add_image($myImage);
  $_CAMILA['page']->add_link($myLink);


  $_CAMILA['page']->use_simulator(CAMILA_CSS_DIR . 'skin0.css');

  require(CAMILA_DIR . 'deck_settings.php');
  require(CAMILA_DIR . 'footer.php');
  exit();
?>