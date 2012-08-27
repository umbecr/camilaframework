<?php
define('CAMILA_VAR_ROOTDIR', dirname(__FILE__));
define('CAMILA_APP_DIR', basename(dirname(CAMILA_VAR_ROOTDIR)) );
define('CAMILA_PRIVATE_SERVER', true);


define('CAMILA_HOMEDIR', dirname(CAMILA_VAR_ROOTDIR));
define('CAMILA_HOMEURL', dirname($_SERVER['SCRIPT_URI']));
define('CAMILA_HELPURL','');


$dbpath = urlencode(CAMILA_VAR_ROOTDIR.'/db/camila.db');
$dbpath = str_replace ("+", "%20", $dbpath);

define('CAMILA_DB_DSN', "sqlite://$dbpath");

define('CAMILA_LOG_DIR', CAMILA_VAR_ROOTDIR.'/log');
define('CAMILA_TMP_DIR', CAMILA_VAR_ROOTDIR.'/tmp');
define('CAMILA_VAR_DIR', CAMILA_VAR_ROOTDIR.'/kfkfkfkfkf/counters');
define('CAMILA_TABLES_DIR', CAMILA_VAR_ROOTDIR.'/tables');
define('CAMILA_NEWS_DIR', CAMILA_VAR_ROOTDIR.'/news');
define('CAMILA_TMPL_DIR', CAMILA_VAR_ROOTDIR.'/templates');
define('CAMILA_WORKTABLES_DIR', CAMILA_VAR_ROOTDIR.'/worktables');

define('CAMILA_LOG_LEVEL', 0);

define('CAMILA_MAIL_ON_AUTHENTICATION', '0');
define('CAMILA_MAIL_ON_DB_ERROR', '0');
define('CAMILA_MAIL_ON_ERROR', '0');

define('CAMILA_MAIL_HOST', 'mail.example.com');
define('CAMILA_MAIL_IS_SMTP', true);
define('CAMILA_MAIL_SMTP_AUTH', false);

define('CAMILA_ANON_LOGIN', false);
define('CAMILA_ANON_USER', 'anon');
define('CAMILA_ANON_PASS', 'anon');
define('CAMILA_ADMIN_USER', 'admin');
define('CAMILA_ADMIN_PASS', 'admin');

define('CAMILA_LOGIN_URL', 'cf_login.php');
define('CAMILA_LOGOUT_URL', 'cf_logout.php');
define('CAMILA_LOGIN_HOME', 'index.php');
define('CAMILA_HOME', 'index.php');
define('CAMILA_SPLASH_IMG', '');

define('CAMILA_FM_AJAXPLORER_ENABLED', true);
define('CAMILA_FM_EXTFS_ENABLED', false);
define('CAMILA_FM_ROOTDIR', CAMILA_VAR_ROOTDIR.'/files');
define('CAMILA_FM_PUBDIR', CAMILA_FM_ROOTDIR.'/public');
define('CAMILA_FM_PREFIX', 7);
define('CAMILA_FM_DEFAULT_FOLDER', 'Allegati');
define('CAMILA_FM_MAX_UPLOAD_SIZE', 5000000);

define('CAMILA_SHOW_ERRORS', false);
define('CAMILA_BAN_IP_SECS', 20);
define('CAMILA_ADMINISTRATOR_EMAIL', 'mail@example.com');

?>