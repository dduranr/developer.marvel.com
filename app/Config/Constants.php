<?php

// namespace App\Config;


/*
 | --------------------------------------------------------------------
 | App Namespace
 | --------------------------------------------------------------------
 |
 | This defines the default Namespace that is used throughout
 | CodeIgniter to refer to the Application directory. Change
 | this constant to change the namespace that all application
 | classes should use.
 |
 | NOTE: changing this will require manually modifying the
 | existing namespaces of App\* namespaced-classes.
 */
defined('APP_NAMESPACE') || define('APP_NAMESPACE', 'App');

/*
 | --------------------------------------------------------------------------
 | Composer Path
 | --------------------------------------------------------------------------
 |
 | The path that Composer's autoload file is expected to live. By default,
 | the vendor folder is in the Root directory, but you can customize that here.
 */
defined('COMPOSER_PATH') || define('COMPOSER_PATH', ROOTPATH . 'vendor/autoload.php');

/*
 |--------------------------------------------------------------------------
 | Timing Constants
 |--------------------------------------------------------------------------
 |
 | Provide simple ways to work with the myriad of PHP functions that
 | require information to be in seconds.
 */
defined('SECOND') || define('SECOND', 1);
defined('MINUTE') || define('MINUTE', 60);
defined('HOUR')   || define('HOUR', 3600);
defined('DAY')    || define('DAY', 86400);
defined('WEEK')   || define('WEEK', 604800);
defined('MONTH')  || define('MONTH', 2_592_000);
defined('YEAR')   || define('YEAR', 31_536_000);
defined('DECADE') || define('DECADE', 315_360_000);

/*
 | --------------------------------------------------------------------------
 | Exit Status Codes
 | --------------------------------------------------------------------------
 |
 | Used to indicate the conditions under which the script is exit()ing.
 | While there is no universal standard for error codes, there are some
 | broad conventions.  Three such conventions are mentioned below, for
 | those who wish to make use of them.  The CodeIgniter defaults were
 | chosen for the least overlap with these conventions, while still
 | leaving room for others to be defined in future versions and user
 | applications.
 |
 | The three main conventions used for determining exit status codes
 | are as follows:
 |
 |    Standard C/C++ Library (stdlibc):
 |       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
 |       (This link also contains other GNU-specific conventions)
 |    BSD sysexits.h:
 |       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
 |    Bash scripting:
 |       http://tldp.org/LDP/abs/html/exitcodes.html
 |
 */
defined('EXIT_SUCCESS')        || define('EXIT_SUCCESS', 0);        // no errors
defined('EXIT_ERROR')          || define('EXIT_ERROR', 1);          // generic error
defined('EXIT_CONFIG')         || define('EXIT_CONFIG', 3);         // configuration error
defined('EXIT_UNKNOWN_FILE')   || define('EXIT_UNKNOWN_FILE', 4);   // file not found
defined('EXIT_UNKNOWN_CLASS')  || define('EXIT_UNKNOWN_CLASS', 5);  // unknown class
defined('EXIT_UNKNOWN_METHOD') || define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     || define('EXIT_USER_INPUT', 7);     // invalid user input
defined('EXIT_DATABASE')       || define('EXIT_DATABASE', 8);       // database error
defined('EXIT__AUTO_MIN')      || define('EXIT__AUTO_MIN', 9);      // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      || define('EXIT__AUTO_MAX', 125);    // highest automatically-assigned error code

/**
 * @deprecated Use \CodeIgniter\Events\Events::PRIORITY_LOW instead.
 */
define('EVENT_PRIORITY_LOW', 200);

/**
 * @deprecated Use \CodeIgniter\Events\Events::PRIORITY_NORMAL instead.
 */
define('EVENT_PRIORITY_NORMAL', 100);

/**
 * @deprecated Use \CodeIgniter\Events\Events::PRIORITY_HIGH instead.
 */
define('EVENT_PRIORITY_HIGH', 10);







// ---------------------------------------------------
// --------------------   CUSTOM  --------------------
// ---------------------------------------------------

define('GOOGLE_RECAPTCHA_VERSION', 2);

defined('FOLDER_TOKENS') OR define('FOLDER_TOKENS', 'C:\\laragon\\www\\developer-marvel\\public\\app\\config\\tokens\\');
defined('FOLDER_LOGS') OR define('FOLDER_LOGS', 'C:\\laragon\\www\\developer-marvel\\writable\\logs\\');
defined('FOLDER_ROOT') OR define('FOLDER_ROOT', 'C:\\laragon\\www\\developer-marvel\\public\\');
defined('BASEURL_1') OR define('BASEURL_1', 'developer-marvel-covalto.local.local');
defined('BASEURL_2') OR define('BASEURL_2', 'http://developer-marvel-covalto.local.local');
defined('FOLDERUPLOADSUSERS') OR define('FOLDERUPLOADSUSERS', 'C:\\laragon\\www\\developer-marvel\\public\\uploads\\users\\');

if (GOOGLE_RECAPTCHA_VERSION===2) {
    defined('GOOGLE_SITEKEY') OR define('GOOGLE_SITEKEY', '6LeuwdIUAAAAAJcFnSQLZnXskC_vcvMJl6kX54DH');
    defined('GOOGLE_SECRETKEY') OR define('GOOGLE_SECRETKEY', '6LeuwdIUAAAAAA8MKlwI3zTMGsIKQcm2hQ6JhwTI');
}
elseif (GOOGLE_RECAPTCHA_VERSION===3) {
    defined('GOOGLE_SITEKEY') OR define('GOOGLE_SITEKEY', '6Lf93PshAAAAAEpXN4kQ-dXwQRjFHUwBckQoIopP');
    defined('GOOGLE_SECRETKEY') OR define('GOOGLE_SECRETKEY', '6Lf93PshAAAAAMkRBg-iGnTE_rP2xVm8UsWJeKSR');
    defined('RECAPTCHA_SCORE') OR define('RECAPTCHA_SCORE', 0.5);
}

// ID's de los grupos que son administradores
defined('GROUPADMINS') OR define('GROUPADMINS', array(1,4));

// ID's de los administradores normales
defined('ADMINS') OR define('ADMINS', array(1, 2));

defined('APPNAME') OR define('APPNAME', 'Developer Marvel');

defined('MIMETYPESIMG') OR define('MIMETYPESIMG', array(
    'image/png',
    'image/jpeg',
    'image/gif',
));

// Salt para crear encriptaciones
defined('SALTFORCRYPTING') OR define('SALTFORCRYPTING', '009ekK8Ghjde1f6e601f1c363ebe0dTa54bDF7af60F31cff8502a4');

// Marvel API
defined('MARVEL_API_PUBLIC_KEY') OR define('MARVEL_API_PUBLIC_KEY', 'b754d27e7061cec9af3ac24456411a3a');
defined('MARVEL_API_PRIVATE_KEY') OR define('MARVEL_API_PRIVATE_KEY', '97ddd75b3d77ad6ce3b7589f7dcc7125ea8e6be7');
