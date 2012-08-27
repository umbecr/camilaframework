<?php

/* This File is part of Camila PHP Framework
   Copyright (C) 2006-2010 Umberto Bresciani

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


require(CAMILA_LIB_DIR . '/axmls/adodb-xmlschema.inc.php');


function create_table($schemaFile, $prefix, $db, $drop=true) {

    $result = Array();
    $schema = new adoSchema($db);
    $schema->setPrefix($prefix);
    $sql = $schema->ParseSchema($schemaFile);

    $dbTable = $schema->obj;
    $adoDB = $schema->db;

    $stmt = sprintf($adoDB->_dropSeqSQL, $dbTable->name);

    $dropresult = true;

    if ($drop) {
        $ok = $db->Execute($stmt);

        if (!$ok)
            $dropresult = false;

        $schema = new adoSchema($db);
        $schema->setPrefix($prefix);
        $sql = $schema->ParseSchema($schemaFile);

    }

    $result = $schema->ExecuteSchema($sql);

    ob_start();
    print_r($sql);
    $sql_r = ob_get_contents();

    ob_end_clean();

    return Array('result' => $result, 'sql' => $sql_r);

}

?>