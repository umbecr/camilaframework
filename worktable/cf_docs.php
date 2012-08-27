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


  require('../camila/header.php');

if (!CAMILA_FM_AJAXPLORER_ENABLED) {
  require(CAMILA_DIR.'datagrid/fs_report.class.php');

  global $_CAMILA;
    if ($_CAMILA['adm_user_group'] != CAMILA_ADM_USER_GROUP)
    {
        $report = new fs_report(CAMILA_FM_ROOTDIR, CAMILA_FM_EXTFS_ENABLED, $_CAMILA['adm_user_group'], camila_get_translation('camila.documents'), 'name', 'asc', false, false, false, false);
    }
    else
    if ($_CAMILA['adm_user_group'] == CAMILA_ADM_USER_GROUP)
    {
        $report = new fs_report(CAMILA_FM_ROOTDIR, CAMILA_FM_EXTFS_ENABLED, $_CAMILA['adm_user_group'], camila_get_translation('camila.documents'), 'name', 'asc');
    }


  $report->process();
  $report->draw();

  require('../camila/footer.php');
  exit;
}
else
{
/**
 * Copyright 2007-2009 Charles du Jeu
 * This file is part of AjaXplorer.
 * The latest code can be found at http://www.ajaxplorer.info/
 * 
 * This program is published under the LGPL Gnu Lesser General Public License.
 * You should have received a copy of the license along with AjaXplorer.
 * 
 * The main conditions are as follow : 
 * You must conspicuously and appropriately publish on each copy distributed 
 * an appropriate copyright notice and disclaimer of warranty and keep intact 
 * all the notices that refer to this License and to the absence of any warranty; 
 * and give any other recipients of the Program a copy of the GNU Lesser General 
 * Public License along with the Program. 
 * 
 * If you modify your copy or copies of the library or any portion of it, you may 
 * distribute the resulting library provided you do so under the GNU Lesser 
 * General Public License. However, programs that link to the library may be 
 * licensed under terms of your choice, so long as the library itself can be changed. 
 * Any translation of the GNU Lesser General Public License must be accompanied by the 
 * GNU Lesser General Public License.
 * 
 * If you copy or distribute the program, you must accompany it with the complete 
 * corresponding machine-readable source code or with a written offer, valid for at 
 * least three years, to furnish the complete corresponding machine-readable source code. 
 * 
 * Any of the above conditions can be waived if you get permission from the copyright holder.
 * AjaXplorer is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; 
 * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * 
 * Description : main script called at initialisation.
 */
include_once(CAMILA_VAR_ROOTDIR . "/ajaxplorer/conf/base.conf.php");
require_once(CAMILA_LIB_DIR . "ajaxplorer/server/classes/class.AJXP_Utils.php");
require_once(CAMILA_LIB_DIR . "ajaxplorer/server/classes/class.SystemTextEncoding.php");
require_once(CAMILA_LIB_DIR . "ajaxplorer/server/classes/class.HTMLWriter.php");
require_once(CAMILA_LIB_DIR . "ajaxplorer/server/classes/class.AJXP_XMLWriter.php");
require_once(CAMILA_LIB_DIR . "ajaxplorer/server/classes/class.Repository.php");
require_once(CAMILA_LIB_DIR . "ajaxplorer/server/classes/class.ConfService.php");
require_once(CAMILA_LIB_DIR . "ajaxplorer/server/classes/class.AuthService.php");
require_once(CAMILA_LIB_DIR . "ajaxplorer/server/classes/class.AJXP_Logger.php");
require_once(CAMILA_LIB_DIR . "ajaxplorer/server/classes/class.AJXP_Plugin.php");
require_once(CAMILA_LIB_DIR . "ajaxplorer/server/classes/class.AJXP_PluginsService.php");
require_once(CAMILA_LIB_DIR . "ajaxplorer/server/classes/class.AbstractAccessDriver.php");

if(!class_exists("DOMDocument")){
        die("Tou must have libxml PHP extension enabled on your server.");
}

HTMLWriter::charsetHeader();
$pServ = AJXP_PluginsService::getInstance();
$pServ->loadPluginsRegistry(INSTALL_PATH."/plugins", CAMILA_VAR_ROOTDIR . "/ajaxplorer/conf");

ConfService::init(CAMILA_VAR_ROOTDIR . "/ajaxplorer/conf/conf.php");
$confStorageDriver = ConfService::getConfStorageImpl();
include_once($confStorageDriver->getUserClassFileName());
session_name("AjaXplorer");
session_start();

$outputArray = array();
$testedParams = array();
$passed = true;
//if(!is_file(TESTS_RESULT_FILE)){
//	$passed = AJXP_Utils::runTests($outputArray, $testedParams);
//	if(!$passed && !isset($_GET["ignore_tests"])){
//		die(AJXP_Utils::testResultsToTable($outputArray, $testedParams));
//	}else{
//		AJXP_Utils::testResultsToFile($outputArray, $testedParams);
//	}
//}

$START_PARAMETERS = array("BOOTER_URL"=>"cf_ajaxplorer_content.php?get_action=get_boot_conf", "MAIN_ELEMENT" => "ajxp_desktop", "SERVER_PREFIX_URI"=>"../lib/ajaxplorer/");
if(AuthService::usersEnabled())
{
	AuthService::preLogUser((isSet($_GET["remote_session"])?$_GET["remote_session"]:""));
	AuthService::bootSequence($START_PARAMETERS);
	if(AuthService::getLoggedUser() != null || AuthService::logUser(null, null) == 1)
	{
		$loggedUser = AuthService::getLoggedUser();
		if(!$loggedUser->canRead(ConfService::getCurrentRootDirIndex()) 
				&& AuthService::getDefaultRootId() != ConfService::getCurrentRootDirIndex())
		{
			ConfService::switchRootDir(AuthService::getDefaultRootId());
		}
	}
}

AJXP_Utils::parseApplicationGetParameters($_GET, $START_PARAMETERS, $_SESSION);

$JSON_START_PARAMETERS = json_encode($START_PARAMETERS);
if(ConfService::getConf("JS_DEBUG")){
	$mess = ConfService::getMessages();
	include_once(INSTALL_PATH."/".CLIENT_RESOURCES_FOLDER."/html/gui_debug.html");
}else{
	$content = file_get_contents(CAMILA_DIR .'/templates/ajaxplorer_gui.html');
	$content = AJXP_XMLWriter::replaceAjxpXmlKeywords($content, false);
	if($JSON_START_PARAMETERS){
		$content = str_replace("//AJXP_JSON_START_PARAMETERS", "startParameters = ".$JSON_START_PARAMETERS.";", $content);
                $content = str_replace("CAMILA_APPLICATION_NAME", CAMILA_APPLICATION_NAME, $content);
	}
	print($content);
}

}
?>