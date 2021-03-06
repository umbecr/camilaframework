<?php

$path_extra = dirname(dirname(dirname(__FILE__)));
$path = ini_get('include_path');
$path = $path_extra . PATH_SEPARATOR . $path;
ini_set('include_path', $path);

function displayError($message) {
    $error = $message;
    include 'index.php';
    exit(0);
}

function doIncludes() {
    /**
     * Require the OpenID consumer code.
     */
    require_once CAMILA_LIB_DIR . 'php-openid/Auth/OpenID/Consumer.php';

    /**
     * Require the "file store" module, which we'll need to store
     * OpenID information.
     */
    require_once CAMILA_LIB_DIR . 'php-openid/Auth/OpenID/FileStore.php';

    /**
     * Require the Simple Registration extension API.
     */
    require_once CAMILA_LIB_DIR . 'php-openid/Auth/OpenID/SReg.php';

    /**
     * Require the PAPE extension module.
     */
    require_once CAMILA_LIB_DIR . 'php-openid/Auth/OpenID/PAPE.php';

    require_once CAMILA_LIB_DIR . 'php-openid/Auth/OpenID/AX.php';

}

doIncludes();

global $pape_policy_uris;
$pape_policy_uris = array(
			  PAPE_AUTH_MULTI_FACTOR_PHYSICAL,
			  PAPE_AUTH_MULTI_FACTOR,
			  PAPE_AUTH_PHISHING_RESISTANT
			  );

function &getStore() {
    /**
     * This is where the example will store its OpenID information.
     * You should change this path if you want the example store to be
     * created elsewhere.  After you're done playing with the example
     * script, you'll have to remove this directory manually.
     */
    $store_path = CAMILA_TMP_DIR;

    if (!file_exists($store_path) &&
        !mkdir($store_path)) {
        print "Could not create the FileStore directory '$store_path'. ".
            " Please check the effective permissions.";
        exit(0);
    }

    return new Auth_OpenID_FileStore($store_path);
}

function &getConsumer() {
    /**
     * Create a consumer object using the store object created
     * earlier.
     */
    $store = getStore();
    $consumer =& new Auth_OpenID_Consumer($store);
    return $consumer;
}

function getScheme() {
    $scheme = 'http';
    if (isset($_SERVER['HTTPS']) and $_SERVER['HTTPS'] == 'on') {
        $scheme .= 's';
    }
    return $scheme;
}

function getReturnTo() {
    return sprintf("%s://%s:%s%s/cf_login.php",
                   getScheme(), $_SERVER['SERVER_NAME'],
                   $_SERVER['SERVER_PORT'],
                   dirname($_SERVER['PHP_SELF']));
}

function getTrustRoot() {
    return sprintf("%s://%s:%s%s/",
                   getScheme(), $_SERVER['SERVER_NAME'],
                   $_SERVER['SERVER_PORT'],
                   dirname($_SERVER['PHP_SELF']));
}

?>