<?php

/* This File is part of Camila PHP Framework
   Copyright (C) 2006-2012 Umberto Bresciani

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

if (CAMILA_ANON_LOGIN & !isset($_REQUEST['camila_autosuggest'])) {

    if ($dh2 = opendir('./lang/')) {
        while (($file2 = readdir($dh2)) !== false) {
            if (substr($file2,-9) == '.lang.php') {
                $url = 'index.php?username=' . CAMILA_ANON_USER . '&pwd=' . CAMILA_ANON_PASS . '&js=enabled&camila_pwloginbox=yes&submit=yes&lang='.substr($file2,0,2);
                $link = new CHAW_link(CAMILA_APPLICATION_NAME . ' ('.camila_get_translation('camila.lang.' . substr($file2,0,2)).', '.camila_get_translation('camila.lang.'.substr($file2,0,2).'.user.anon').')', $url);
                $_CAMILA['page']->add_link($link);

                $url = 'index.php?username=' . CAMILA_ADMIN_USER . '&pwd=' . CAMILA_ADMIN_PASS . '&js=enabled&camila_pwloginbox=yes&submit=yes&lang='.substr($file2,0,2);
                $link = new CHAW_link(CAMILA_APPLICATION_NAME . ' ('.camila_get_translation('camila.lang.' . substr($file2,0,2)).', '.camila_get_translation('camila.lang.'.substr($file2,0,2).'.user.admin').')', $url);
                $link->set_br(2);
                $_CAMILA['page']->add_link($link);

            }
        }
        closedir($dh2);
    }

}


if (!CAMILA_ANON_LOGIN & !isset($_REQUEST['camila_autosuggest'])) {

if (CAMILA_PRIVATE_SERVER) {
    $myText = new CHAW_text(camila_get_translation('camila.serveraddress'));
    $_CAMILA['page']->add_text($myText);

    if (getenv('COMPUTERNAME') != '') {
        $url = 'http://'.getenv('COMPUTERNAME') . ':' . $_SERVER['SERVER_PORT'] . '/' . CAMILA_APP_DIR;
        $link = new CHAW_link($url, $url);
        $_CAMILA['page']->add_link($link);
    }

    if ($_SERVER['SERVER_ADDR'] != '') {
        $url = 'http://'.$_SERVER['SERVER_ADDR'] . ':' . $_SERVER['SERVER_PORT'] . '/' . CAMILA_APP_DIR;
        $link = new CHAW_link($url, $url);
        $_CAMILA['page']->add_link($link);
    }
}
  $query = 'select * from ' . CAMILA_APPLICATION_PREFIX.'camila_bookmarks order by sequence';

  $result = $_CAMILA['db']->Execute($query);
  if ($result === false)
      camila_error_page(camila_get_translation('camila.sqlerror') . ' ' . $_CAMILA['db']->ErrorMsg());


    $myText = new CHAW_text('');
    $_CAMILA['page']->add_text($myText);


  while (!$result->EOF) {


if (strpos($result->fields['url'], 'gby')===false)
{
    $myLink = new CHAW_link($result->fields['title'], $result->fields['url']);
    $myLink->set_br(0);
    $_CAMILA['page']->add_link($myLink);

    $code = "<span id='".$result->fields['id']."'>0</span>";

    $pos = strrpos($result->fields['url'], '?');
    if ($pos !== false)
        $url = $result->fields['url']."&camila_json&tqx=reqId:".$result->fields['id'];
    else
        $url = $result->fields['url']."?camila_json&tqx=reqId:".$result->fields['id'];

    $code .= "<script defer src = '".$url."'>\n";
    $code .= "</script>\n";

    $js = new CHAW_js($code);
    $_CAMILA['page']->add_userdefined($js);

    $myText = new CHAW_text('');
    $_CAMILA['page']->add_text($myText);
}

    $result->MoveNext();

  }

}


if (isset($_REQUEST['camila_autosuggest'])) {
    header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
    header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
    header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
    header("Pragma: no-cache"); // HTTP/1.0
    header("Content-Type: application/json");

    $ifArr = explode(',', $_REQUEST['infofields']);
    $count = count($ifArr);

    $query = 'select ' . $_REQUEST['id'] . ', ' . $_REQUEST['field'] .' as value';

    if ($_REQUEST['pickfields'] != '')
        $query .= ',' . $_REQUEST['pickfields'];

    $where = $_REQUEST['field'].' LIKE '.$_CAMILA['db']->qstr('%' . $_REQUEST['input'].'%');


    if ($_REQUEST['objectid'] != '')
        $where = 'id='.$_CAMILA['db']->qstr($_REQUEST['objectid']);

    if ($_CAMILA['user_visibility_type']=='personal')
        $where .= ' and '.CAMILA_WORKTABLE_EXT_TABLE_PERSONAL_VISIBILITY_FIELD.'='.$_CAMILA['db']->qstr($_CAMILA['user']);

    $result = $_CAMILA['db']->SelectLimit($query . ' from ' . $_REQUEST['table'] . ' where ' . $where, $_REQUEST['maxresults']);
    if ($result === false)
        camila_error_page(camila_get_translation('camila.sqlerror') . ' ' . $_CAMILA['db']->ErrorMsg());

    $count = 0;
    while (!$result->EOF) {
        $count++;

        $infof = '';
        foreach($ifArr as $value) {
            $v = $result->fields[$value];

            if(strlen($v)==10 && preg_match("/^[0-9]{4}-[0-9]{2}-[0-9]{2}/", $v)) {
                $v = $_CAMILA['db']->UserDate($v , $_CAMILA['date_format']);
                $result->fields[$value] = $v;
            }
            $infof .= $v .' ';
        }
        $result->fields['info'] = $infof;

        $fields[] = $result->fields;

        $result->MoveNext();
    }


    if ($count > 0) {
        $json = new Services_JSON();
        camila_utf8_encode_array($fields);
        echo $json->encode(Array('results' => $fields));
    } else {
        echo "{\"results\": [";
        echo "]}";
    }

exit();
} 

if (isset($_REQUEST['camila_autosuggest_filterbox'])) {
    header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
    header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
    header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
    header("Pragma: no-cache"); // HTTP/1.0
    header("Content-Type: application/json");


    $query = 'select distinct '. $_REQUEST['field'] .' as value';


    $where = $_REQUEST['field'].' LIKE '.$_CAMILA['db']->qstr('%' . $_REQUEST['input'].'%');

//    if ($_REQUEST['objectid'] != '')
//        $where = 'id='.$_CAMILA['db']->qstr($_REQUEST['objectid']);

    $result = $_CAMILA['db']->SelectLimit($query . ' from ' . $_REQUEST['table'] . ' where ' . $where, $_REQUEST['maxresults']);
    if ($result === false)
        camila_error_page(camila_get_translation('camila.sqlerror') . ' ' . $_CAMILA['db']->ErrorMsg());

    $count = 0;
    while (!$result->EOF) {
        $count++;

        $result->fields['id'] = $result->fields['value'];
        $result->fields['info'] = '';

        $fields[] = $result->fields;

        $result->MoveNext();
    }


    if ($count > 0) {
        $json = new Services_JSON();
        camila_utf8_encode_array($fields);
        echo $json->encode(Array('results' => $fields));
    } else {
        echo "{\"results\": [";
        echo "]}";
    }

exit();
}


require_once('../camila/footer.php');
?>
