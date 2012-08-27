<?php

/* This File is part of Camila PHP Framework
   Copyright (C) 2006-2009 Umberto Bresciani

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


  require('../camila/header.php');

  $query = 'SELECT url,short_title FROM ' . CAMILA_TABLE_PAGES . ', ' . CAMILA_TABLE_PLANG . ' WHERE ('. CAMILA_TABLE_PAGES .'.url = ' . CAMILA_TABLE_PLANG .'.page_url) and level>=' . $_CAMILA[user_level] .' AND visible='.$_CAMILA['db']->qstr('yes').' AND active=' . $_CAMILA['db']->qstr('yes') . ' and parent=' . $_CAMILA['db']->qstr($_CAMILA['page_url']) . " and lang=" . $_CAMILA['db']->qstr($_CAMILA['lang']) . " ORDER by label_order";

  $result = $_CAMILA['db']->Execute($query);
  if ($result === false)
      camila_error_page(camila_get_translation('camila.sqlerror') . ' ' . $_CAMILA['db']->ErrorMsg());

  while (!$result->EOF) {
      $link = new CHAW_link($result->fields['short_title'], $result->fields['url']);
      $_CAMILA['page']->add_link($link);
      $result->MoveNext();
  }

  require('../camila/footer.php');

?>