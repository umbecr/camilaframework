<?php

if (!isset($_SERVER['PHP_AUTH_USER'])) {

    header('WWW-Authenticate: Basic realm="Camila Framework"');
    header('HTTP/1.0 401 Unauthorized');
    echo 'Authentication required';
    exit;

} else {
    
    $url = $_SERVER['REQUEST_URI'];
    $method = $_SERVER['REQUEST_METHOD'];
    $getArgs = $_GET;
    $postArgs = $_POST;
    parse_str(file_get_contents('php://input'), $putArgs);
    parse_str(file_get_contents('php://input'), $deleteArgs);

    $_REQUEST['pwd'] = $_SERVER['PHP_AUTH_USER'];
    $_REQUEST['username'] = $_SERVER['PHP_AUTH_PW'];
    $_REQUEST['camila_pwloginbox'] = 'yes';
    $_REQUEST['submit'] = true;
    $_REQUEST['camila_json'] = '';
    $_REQUEST['camila_rest'] = '';
    $_REQUEST['camila_pagnum'] = -1;
    
    $urlParts = parse_url($url);
    // substring from 1 to avoid leading slash
    $pathParts = explode('/', substr($urlParts['path'], 1));
    
    $collection = $pathParts[2];
    $collectionId = $pathParts[3];
    
    switch ($collection) {
    case 'worktable':

        if ($collectionId != '') {

            require('../camila/header.php');
            global $_CAMILA;
            $_CAMILA['page']->camila_worktable_id = $collectionId;
            
            require(CAMILA_WORKTABLES_DIR . '/' . CAMILA_TABLE_WORKP . $collectionId . '.inc.php');
            require('../camila/footer.php');

        } else {

            require('../camila/header.php');
            
            require(CAMILA_DIR . 'datagrid/report.class.php');
            $report_fields = 'id,sequence,short_title,full_title,category,share_key,share_caninsert,share_canupdate,share_candelete';
            $default_fields = $report_fields;
            $mapping = camila_get_translation('camila.worktable.mapping.worktable.admin');
            $stmt = 'select ' . $report_fields . ' from ' . CAMILA_TABLE_WORKT;
            $report = new report($stmt, '', 'sequence', 'asc', $mapping, null, 'id', '', '', false, false);            
            $report->process();
            $report->draw();
            require('../camila/footer.php');
            
        }
        
        break;
    case 'worktablecolumn':
        
        if ($collectionId != '') {

            require('../camila/header.php');            
            require(CAMILA_DIR . 'datagrid/report.class.php');

            $report_fields = 'wt_id,sequence,type,size,maxlength,name,name_abbrev,col_name,required,readonly,listbox_options,field_options,visible,default_value,force_case,must_be_unique,autosuggest_wt_colname,autosuggest_wt_name';
            $default_fields = $report_fields;
            $mapping = camila_get_translation('camila.worktable.mapping.worktable');
            $stmt = 'select ' . $report_fields . ' from ' . CAMILA_TABLE_WORKC . ' where (wt_id=' . $_CAMILA['db']->qstr($collectionId) . ' and is_deleted<>'.$_CAMILA['db']->qstr('y').')';
            $report = new report($stmt, '', 'sequence', 'asc', $mapping, null, 'id', '', '', false, false);
            $report->process();
            $report->draw();
            require('../camila/footer.php');
            
        } else {
            
        }
        
        break;
    }
    
    
}

?>