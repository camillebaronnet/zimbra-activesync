<?php
/**********************************************************************************
 *  Default settings
 */
    // Defines the default time zone, change e.g. to "Europe/London" if necessary
    define('TIMEZONE', 'Europe/Paris');
 
    // Defines the base path on the server
    define('BASE_PATH', dirname($_SERVER['SCRIPT_FILENAME']). '/');
 
    // Try to set unlimited timeout
    define('SCRIPT_TIMEOUT', 0);
 
    //Max size of attachments to display inline. Default is 1MB
    define('MAX_EMBEDDED_SIZE', 2097152);
 
/**********************************************************************************
 *  Default FileStateMachine settings
 */
    define('STATE_DIR', '/var/lib/z-push/');
 
/**********************************************************************************
 *  Logging settings
 */
    define('LOGFILEDIR', '/home/logs/z-push/');
    define('LOGFILE', LOGFILEDIR . 'z-push.log');
    define('LOGERRORFILE', LOGFILEDIR . 'z-push-error.log');
    define('LOGLEVEL', LOGLEVEL_INFO);
    //Valeur par defaut : 'LOGAUTHFAIL', false
    define('LOGAUTHFAIL', true);
 
    // To save e.g. WBXML data only for selected users, add the usernames to the array
    // The data will be saved into a dedicated file per user in the LOGFILEDIR
    define('LOGUSERLEVEL', LOGLEVEL_DEVICEID);
    $specialLogUsers = array();
 
/**********************************************************************************
 *  Mobile settings
 */
    // Device Provisioning
    define('PROVISIONING', true);
 
    // This option allows the 'loose enforcement' of the provisioning policies for older
    // devices which don't support provisioning (like WM 5 and HTC Android Mail) - dw2412 contribution
    // false (default) - Enforce provisioning for all devices
    // true - allow older devices, but enforce policies on devices which support it
    define('LOOSE_PROVISIONING', false);
 
    // Default conflict preference
    // Some devices allow to set if the server or PIM (mobile)
    // should win in case of a synchronization conflict
    //   SYNC_CONFLICT_OVERWRITE_SERVER - Server is overwritten, PIM wins
    //   SYNC_CONFLICT_OVERWRITE_PIM    - PIM is overwritten, Server wins (default)
    define('SYNC_CONFLICT_DEFAULT', SYNC_CONFLICT_OVERWRITE_PIM);
 
    // Global limitation of items to be synchronized
    // The mobile can define a sync back period for calendar and email items
    // For large stores with many items the time period could be limited to a max value
    // If the mobile transmits a wider time period, the defined max value is used
    // Applicable values:
    //   SYNC_FILTERTYPE_ALL (default, no limitation)
    //   SYNC_FILTERTYPE_1DAY, SYNC_FILTERTYPE_3DAYS, SYNC_FILTERTYPE_1WEEK, SYNC_FILTERTYPE_2WEEKS,
    //   SYNC_FILTERTYPE_1MONTH, SYNC_FILTERTYPE_3MONTHS, SYNC_FILTERTYPE_6MONTHS
    //define('SYNC_FILTERTIME_MAX', SYNC_FILTERTYPE_ALL);
    define('SYNC_FILTERTIME_MAX', SYNC_FILTERTYPE_3MONTHS);
 
    // Interval in seconds before checking if there are changes on the server when in Ping.
    // It means the highest time span before a change is pushed to a mobile. Set it to
    // a higher value if you have a high load on the server.
    define('PING_INTERVAL', 30);
 
    // Interval in seconds to force a re-check of potentially missed notifications when
    // using a changes sink. Default are 300 seconds (every 5 min).
    // This can also be disabled by setting it to false
    define('SINK_FORCERECHECK', 300);
 
/**********************************************************************************
 *  Backend settings
 */
    // The data providers that we are using (see configuration below)
    define('BACKEND_PROVIDER', "BackendZimbra");
 
    // ************************
    //  BackendZarafa settings
    // ************************
    // Defines the server to which we want to connect
    define('MAPI_SERVER', 'file:///var/run/zarafa');
 
    // ************************
    //  BackendZimbra settings
    // ************************

    $zpush_url = '#ZPUSH_HOST#';
    if($zpush_url != "zpush_default") define('ZPUSH_URL', 'https://'.$zpush_url.'/Microsoft-Server-ActiveSync');
    
    define('ZIMBRA_URL','https://#ZIMBRA_HOST#');

    define('ZIMBRA_USER_DIR','zimbra');
    define('ZIMBRA_SYNC_CONTACT_PICTURES', true);
    define('ZIMBRA_VIRTUAL_CONTACTS',true);
    define('ZIMBRA_VIRTUAL_APPOINTMENTS',true);
    define('ZIMBRA_VIRTUAL_TASKS',true);
    define('ZIMBRA_IGNORE_EMAILED_CONTACTS',true);
    define('ZIMBRA_HTML',true);
    define('ZIMBRA_ENFORCE_VALID_EMAIL',false);
    define('ZIMBRA_SMART_FOLDERS',true);
 
?>