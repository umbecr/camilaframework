<?php
/**
 * @package info.ajaxplorer.plugins
 * 
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
 * Description : Config file for the access.fs plugin.
 */
defined('AJXP_EXEC') or die( 'Access not allowed');

$DRIVER_CONF = array(
	/**************************************************/
	/* Hide defined files and folders
	/**************************************************/
	// Show or hide hidden files (beginning with ".") Values : 0 or 1	
	"SHOW_HIDDEN_FILES" => false,
	// Dont't show files with the the extensions in the comma separated list
	// example: $hide_extensions=array('lnk', 'ini'); // Hide all files with the extension lnk (Windows links) and ini-Files
	// default: $hide_extensions=0; // Hide no extensions
	"HIDE_EXTENSIONS"=>0,
	
	// Don't show the filenames in the comma separated list
	// example: $hide_filenames=array('desktop.ini', 'thumbs.db'); // Microsoft Windows special files
	// default: $hide_filenames=0; // Hide no filenames
	"HIDE_FILENAMES"=>0,
	
	// Don't show the folders in the comma separated list
	// example: $hide_folders=array('system volume information', 'recycler'); // Microsoft Windows special folders
	// default: $hide_folders=0; // Hide no folders
	"HIDE_FOLDERS"=>0	
);
?>