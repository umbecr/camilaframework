<?php
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
 * Description : main script called by any Ajax query. Will dispatch the actions on the plugins.
 */
include_once("server/conf/base.conf.php");

require_once("server/classes/class.AJXP_Utils.php");
require_once("server/classes/class.SystemTextEncoding.php");
require_once("server/classes/class.Repository.php");
require_once("server/classes/class.AJXP_Exception.php");
require_once("server/classes/class.AJXP_Plugin.php");
require_once("server/classes/class.AJXP_PluginsService.php");
require_once("server/classes/class.AbstractAccessDriver.php");
require_once("server/classes/class.ConfService.php");
require_once("server/classes/class.AuthService.php");
require_once("server/classes/class.UserSelection.php");
require_once("server/classes/class.HTMLWriter.php");
require_once("server/classes/class.AJXP_XMLWriter.php");
require_once("server/classes/class.RecycleBinManager.php");
if(isSet($_GET["ajxp_sessid"]))
{
    // Don't overwrite cookie
    if (!isSet($_COOKIE["AjaXplorer"]))
    	$_COOKIE["AjaXplorer"] = $_GET["ajxp_sessid"];
}
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
require_once("server/classes/class.AJXP_Logger.php");
set_error_handler(array("AJXP_XMLWriter", "catchError"), E_ALL & ~E_NOTICE );
set_exception_handler(array("AJXP_XMLWriter", "catchException"));
$pServ = AJXP_PluginsService::getInstance();
$pServ->loadPluginsRegistry(INSTALL_PATH."/plugins", INSTALL_PATH."/server/conf");
ConfService::init("server/conf/conf.php");

$confStorageDriver = ConfService::getConfStorageImpl();
require_once($confStorageDriver->getUserClassFileName());
session_name("AjaXplorer");
session_start();

if(isSet($_GET["tmp_repository_id"])){
	ConfService::switchRootDir($_GET["tmp_repository_id"], true);
}

if(AuthService::usersEnabled())
{
	$rememberLogin = "";
	$rememberPass = "";
	if(isset($_GET["get_action"]) && $_GET["get_action"] == "get_seed"){
		HTMLWriter::charsetHeader("text/plain");
		print AuthService::generateSeed();				
		exit(0);
	}	
	if(isSet($_GET["get_action"]) && $_GET["get_action"] == "logout")
	{
		AuthService::disconnect();		
		$loggingResult = 2;
	}
    if(isSet($_GET["get_action"]) && $_GET["get_action"] == "back")
    {
		AJXP_XMLWriter::header("url");
        echo AuthService::getLogoutAddress(false);
        AJXP_XMLWriter::close("url");
		exit(1);
    }
	if(isSet($_GET["get_action"]) && $_GET["get_action"] == "login")
	{
		$userId = (isSet($_GET["userid"])?$_GET["userid"]:null);
		$userPass = (isSet($_GET["password"])?$_GET["password"]:null);
		$rememberMe = ((isSet($_GET["remember_me"]) && $_GET["remember_me"] == "on")?true:false);
		$cookieLogin = (isSet($_GET["cookie_login"])?true:false); 
		$loggingResult = AuthService::logUser($userId, $userPass, false, $cookieLogin, $_GET["login_seed"]);
		if($rememberMe && $loggingResult == 1){
			$rememberLogin = $userId;
			$loggedUser = AuthService::getLoggedUser();
			$rememberPass =  $loggedUser->getCookieString();
		}
	}
	else 
	{
		AuthService::logUser(null, null);	
	}
	// Check that current user can access current repository, try to switch otherwise.
	$loggedUser = AuthService::getLoggedUser();
	if($loggedUser != null)
	{
		if(isSet($_SESSION["PENDING_REPOSITORY_ID"]) && isSet($_SESSION["PENDING_FOLDER"])){
			$loggedUser->setArrayPref("history", "last_repository", $_SESSION["PENDING_REPOSITORY_ID"]);
			$loggedUser->setArrayPref("history", $_SESSION["PENDING_REPOSITORY_ID"], $_SESSION["PENDING_FOLDER"]);
			$loggedUser->save();
			unset($_SESSION["PENDING_REPOSITORY_ID"]);
			unset($_SESSION["PENDING_FOLDER"]);
		}
		$currentRepoId = ConfService::getCurrentRootDirIndex();
		$lastRepoId  = $loggedUser->getArrayPref("history", "last_repository");
		$defaultRepoId = AuthService::getDefaultRootId();
		if($lastRepoId != "" && $lastRepoId!=$currentRepoId && !isSet($_GET["tmp_repository_id"]) && $loggedUser->canSwitchTo($lastRepoId)){
			ConfService::switchRootDir($lastRepoId);
		}else if(!$loggedUser->canSwitchTo($currentRepoId)){
			ConfService::switchRootDir($defaultRepoId);
		}
	}
	if($loggedUser == null)
	{
		$requireAuth = true;
	}
	if(isset($loggingResult))
	{
		AJXP_XMLWriter::header();
		AJXP_XMLWriter::loggingResult($loggingResult, $rememberLogin, $rememberPass);
		AJXP_XMLWriter::close();
		exit(1);
	}
}else{
	if(isSet($_GET["get_action"]) && $_GET["get_action"] == "logged_user")
	{
		AJXP_XMLWriter::header();
		print("<user id=\"shared\">");
		print("<active_repo id=\"".ConfService::getCurrentRootDirIndex()."\" write=\"1\" read=\"1\"/>");
		print(AJXP_XMLWriter::writeRepositoriesData(null));
		print("</user>");
		AJXP_XMLWriter::close();
		exit(1);
	}	
}

//Set language
$loggedUser = AuthService::getLoggedUser();
if($loggedUser != null && $loggedUser->getPref("lang") != "") ConfService::setLanguage($loggedUser->getPref("lang"));
else if(isSet($_COOKIE["AJXP_lang"])) ConfService::setLanguage($_COOKIE["AJXP_lang"]);
$mess = ConfService::getMessages();

$action = "";
if(isSet($_GET["action"]) || isSet($_GET["get_action"])) $action = (isset($_GET["get_action"])?$_GET["get_action"]:$_GET["action"]);
else if(isSet($_POST["action"]) || isSet($_POST["get_action"])) $action = (isset($_POST["get_action"])?$_POST["get_action"]:$_POST["action"]);

//------------------------------------------------------------
// SPECIAL HANDLING FOR FANCY UPLOADER RIGHTS FOR THIS ACTION
//------------------------------------------------------------
if(AuthService::usersEnabled())
{
	$loggedUser = AuthService::getLoggedUser();	
	if($action == "upload" && ($loggedUser == null || !$loggedUser->canWrite(ConfService::getCurrentRootDirIndex()."")) && isSet($_FILES['Filedata']))
	{
		header('HTTP/1.0 ' . '410 Not authorized');
		die('Error 410 Not authorized!');
	}
}

// THIS FIRST DRIVERS DO NOT NEED ID CHECK
$ajxpDriver = AJXP_PluginsService::findPlugin("gui", "ajax");
$ajxpDriver->init(ConfService::getRepository());
$authDriver = ConfService::getAuthDriverImpl();
// DRIVERS BELOW NEED IDENTIFICATION CHECK
if(!AuthService::usersEnabled() || ALLOW_GUEST_BROWSING || AuthService::getLoggedUser()!=null){
	$confDriver = ConfService::getConfStorageImpl();
	$Driver = ConfService::loadRepositoryDriver();
}
ConfService::initActivePlugins();
require_once(INSTALL_PATH."/server/classes/class.AJXP_Controller.php");
$xmlResult = AJXP_Controller::findActionAndApply($action, array_merge($_GET, $_POST), $_FILES);
if($xmlResult !== false && $xmlResult != ""){
	AJXP_XMLWriter::header();
	print($xmlResult);
	AJXP_XMLWriter::close();
	exit(1);
}
if(isset($requireAuth))
{
	AJXP_XMLWriter::header();
	AJXP_XMLWriter::requireAuth();
	AJXP_XMLWriter::close();
	exit(1);
}	
session_write_close();
?>
