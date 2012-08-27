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


  require_once('../camila/header.php');

  if (isset($_REQUEST['dbschema']))
      require_once(CAMILA_DIR . '/admin/db_schema.php');

  if (isset($_REQUEST['dbfiles']) || isset($_REQUEST['camila_dbfiles']))
      require_once(CAMILA_DIR . '/admin/db_files.php');

  if (isset($_REQUEST['dbquery']) || isset($_REQUEST['camila_dbquery']) || isset($_REQUEST['camila_custom']))
      require_once(CAMILA_DIR . '/admin/db_query.php');

  if (isset($_REQUEST['dbexport']))
      require_once(CAMILA_DIR . '/admin/db_export.php');

  if (isset($_REQUEST['dbimport']))
      require_once(CAMILA_DIR . '/admin/db_import.php');

  if (isset($_REQUEST['tmplfiles']) || isset($_REQUEST['camila_tmplfiles']))
      require_once(CAMILA_DIR . '/admin/tmpl_files.php');

  if (isset($_REQUEST['tmplimages']) || isset($_REQUEST['camila_tmplimages']))
      require_once(CAMILA_DIR . '/admin/tmpl_images.php');

  if (isset($_REQUEST['users']) || isset($_REQUEST['camila_users']))
      require_once(CAMILA_DIR . '/admin/users.php');

  if (isset($_REQUEST['bookmarks']) || isset($_REQUEST['camila_bookmarks']))
      require_once(CAMILA_DIR . '/admin/bookmarks.php');

  require_once('../camila/footer.php');
?>
