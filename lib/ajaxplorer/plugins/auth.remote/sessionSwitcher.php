<?php
/** A useful class for session switching */
defined('AJXP_EXEC') or die( 'Access not allowed');

class SessionSwitcher
{
    /** The current session stack */
    public static $sessionArray;
    
    /** Construction. This kills the current session if any started, and restart the given session */
    public function __construct($name, $cleanPreviousSession = false)
    {    	
        if (session_id() == "")
        {
			// Mysterious fix, necessary for joomla.
			ini_set('session.save_handler', 'files');
            // Start a default session and save on the handler
            session_start();
            SessionSwitcher::$sessionArray[] = array('id'=>session_id(), 'name'=>session_name());
            AJXP_Logger::debug("Session switching 1: ", SessionSwitcher::$sessionArray);
            session_write_close();
        }else{
        	SessionSwitcher::$sessionArray[] = array('id'=>session_id(), 'name'=>session_name());
        }
        // Please note that there is no start here, session might be already started
        if (session_id() != "")
        {
            // There was a previous session
            if ($cleanPreviousSession)
            {
                if (isset($_COOKIE[session_name()]))
				setcookie(session_name(), '', time() - 42000, '/');
                session_destroy();
            }
            // Close the session
            session_write_close();
            session_regenerate_id(false);
            $_SESSION = array();
            // Need to generate a new session id
        }
		// Mysterious fix, necessary for joomla.
		ini_set('session.save_handler', 'files');

		$newId = md5(SessionSwitcher::$sessionArray[0]['id'].$name);
        AJXP_Logger::debug("Session switching  new id: ", $newId);
		session_id($newId);
        session_name($name);
        session_start();
    }
};
?>