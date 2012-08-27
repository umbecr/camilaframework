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


require_once('var/config.php');

require_once(CAMILA_LIB_DIR.'adodb/adodb.inc.php');
require_once(CAMILA_LIB_DIR.'hawhaw/hawhaw.inc');
require_once(CAMILA_DIR.'db/import.inc.php');
require_once(CAMILA_DIR.'db/schema.inc.php');


function camila_delete_files($directory) {

    if( !$dirhandle = @opendir($directory) )
        return;

        while( false !== ($filename = readdir($dirhandle)) ) {
            if( $filename != '.' && $filename != '..' ) {
                $filename = $directory. '/'. $filename;

                if (!unlink($filename))
                    echo 'Error deleting ' . $filename;
        }
    }
}


$db = NewADOConnection(CAMILA_DB_DSN);

$page = new HAW_deck('DB Init (reset)');

$page->use_simulator('');

if (is_dir(CAMILA_TABLES_DIR)) {
    if ($dh = opendir(CAMILA_TABLES_DIR)) {
        while (($file = readdir($dh)) !== false) {
            if ($file != '.' && $file != '..' && substr($file,-3) == 'xml') {
                $result = create_table(CAMILA_TABLES_DIR.'/'.$file, CAMILA_APPLICATION_PREFIX, $db);

                if ($result['result'] == 2)
                    $text = new HAW_text($file . ' - OK');
                else
                    $text = new HAW_text($file . ' - KO - ' . $result['sql'], HAW_TEXTFORMAT_BOLD);

                $page->add_text($text);
            }
        }
        closedir($dh);
    }
}

if (is_dir(CAMILA_TABLES_DIR)) {
    if ($dh = opendir(CAMILA_TABLES_DIR)) {
        while (($file = readdir($dh)) !== false) {
            if ($file != '.' && $file != '..' && substr($file,-3) == 'csv') {
                $result = CSV_import(CAMILA_TABLES_DIR.'/'.$file, CAMILA_APPLICATION_PREFIX . substr($file,0,-4), $db);
                if ($result['result'] == 2)
                    $text = new HAW_text($file . ' - inserted: ' . $result['processed']);
                else
                    $text = new HAW_text($file . ' - error: ' . $result['error'] . ', failed: ' . $result['failed'] . ', inserted: ' . $result['processed'], HAW_TEXTFORMAT_BOLD);
                $page->add_text($text);
            }

        }
        closedir($dh);
    }
}


function listdir($dir='.') { 
    if (!is_dir($dir)) { 
        return false; 
    } 
    
    $files = array(); 
    listdiraux($dir, $files); 

    return $files; 
} 

function listdiraux($dir, &$files) { 
    $handle = opendir($dir); 
    while (($file = readdir($handle)) !== false) { 
        if ($file == '.' || $file == '..') { 
            continue; 
        } 
        $filepath = $dir == '.' ? $file : $dir . '/' . $file; 
        if (is_link($filepath)) 
            continue; 
        if (is_file($filepath)) 
            $files[] = $file; 
        else if (is_dir($filepath)) 
            listdiraux($filepath, $files); 
    } 
    closedir($handle); 
} 

$files = listdir(CAMILA_TABLES_DIR.'/xls/'.$_REQUEST['lang']); 
sort($files, SORT_LOCALE_STRING);

foreach($files as $file) {
            if ($file != '.' && $file != '..' && substr($file,-3) == 'xls') {
                $result = XLS_import(CAMILA_TABLES_DIR.'/xls/'.$_REQUEST['lang'].'/'.$file, CAMILA_APPLICATION_PREFIX . substr($file,0,-4), $db);
            }

}

/*if (is_dir(CAMILA_TABLES_DIR)) {
    if ($dh = opendir(CAMILA_TABLES_DIR.'/xls/'.$_REQUEST['lang'])) {
        while (($file = readdir($dh)) !== false) {
            if ($file != '.' && $file != '..' && substr($file,-3) == 'xls') {
                $result = XLS_import(CAMILA_TABLES_DIR.'/xls/'.$_REQUEST['lang'].'/'.$file, CAMILA_APPLICATION_PREFIX . substr($file,0,-4), $db);
            }

        }
        closedir($dh);
    }
}*/

if (is_dir(CAMILA_TABLES_DIR)) {
/*    if ($dh = opendir(CAMILA_TABLES_DIR.'/xls/'.$_REQUEST['lang'])) {
        while (($file = readdir($dh)) !== false) {
            if ($file != '.' && $file != '..' && substr($file,-3) == 'xls') {
                $result = XLS_import2(CAMILA_TABLES_DIR.'/xls/'.$_REQUEST['lang'].'/'.$file, CAMILA_APPLICATION_PREFIX . substr($file,0,-4), $db);
                if ($result['result'] == 2)
                    $text = new HAW_text($file . ' - inserted: ' . $result['processed']);
                else
                    $text = new HAW_text($file . ' - error: ' . $result['error'] . ', failed: ' . $result['failed'] . ', inserted: ' . $result['processed'], HAW_TEXTFORMAT_BOLD);
                $page->add_text($text);
            }

        }
        closedir($dh);

    }*/

        foreach($files as $file) {
            if ($file != '.' && $file != '..' && substr($file,-3) == 'xls') {
                $result = XLS_import2(CAMILA_TABLES_DIR.'/xls/'.$_REQUEST['lang'].'/'.$file, CAMILA_APPLICATION_PREFIX . substr($file,0,-4), $db);
                if ($result['result'] == 2)
                    $text = new HAW_text($file . ' - inserted: ' . $result['processed']);
                else
                    $text = new HAW_text($file . ' - error: ' . $result['error'] . ', failed: ' . $result['failed'] . ', inserted: ' . $result['processed'], HAW_TEXTFORMAT_BOLD);
                $page->add_text($text);

            }
}

    $res = $db->Execute('update ' . CAMILA_TABLE_PLANG . ' set full_title=short_title where page_url LIKE '.$db->qstr('cf_app.php?cat%') . ' and lang='.$db->qstr($_REQUEST['lang']));
    if ($res === false)
        camila_error_page(camila_get_translation('camila.sqlerror') . ' ' . $db->ErrorMsg());

}

camila_delete_files(CAMILA_TMP_DIR);

$myLink = new HAW_link($_REQUEST['msg'], 'index.php');
$page->add_link($myLink);

$page->create_page();
?>
