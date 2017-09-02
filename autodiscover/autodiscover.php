<?php

require '../config.php';

if(defined('ZPUSH_URL')) $config['MobileSync']['Url'] = ZPUSH_URL;
else $config['MobileSync']['Url'] = 'https://'.$_SERVER['HTTP_HOST'].'/Microsoft-Server-ActiveSync';

// IMAP configuration settings.

$config['IMAP']['Server']       = parse_url(ZIMBRA_URL)['host'];
$config['IMAP']['Port']         = '993';
$config['IMAP']['SSL']          = 'on';
$config['IMAP']['SPA']          = 'off';
$config['IMAP']['AuthRequired'] = 'on';

// SMTP configuration settings.

$config['SMTP']['Server']       = $config['IMAP']['Server'];
$config['SMTP']['Port']         = '465';
$config['SMTP']['SSL']          = 'on';
$config['SMTP']['SPA']          = 'off';
$config['SMTP']['AuthRequired'] = 'on';
/*** End Configuration ***/

// For other supported protocols and more protocol settings, see:
// http://technet.microsoft.com/en-us/library/cc511507.aspx

// Get contents of request made to Autodiscover.

$request = file_get_contents('php://input');

// XML document heading.

header('Content-Type: text/xml');
echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";

// Get the schema from the request.

preg_match('/\<AcceptableResponseSchema\>(.*?)\<\/AcceptableResponseSchema\>/', $request, $schema);

// Determine the type of device requesting Autodiscover.

preg_match('/\<EMailAddress\>(.*?)\<\/EMailAddress\>/', $request, $email_address);

if(!empty($email_address[1])){
	$config['IMAP']['LoginName'] = $email_address[1];
	$config['SMTP']['LoginName'] = $email_address[1];
}

if (preg_match('/\/mobilesync\//', $schema[1])){ ?>
    <Autodiscover xmlns="http://schemas.microsoft.com/exchange/autodiscover/responseschema/2006">
        <Response xmlns="<?=$schema[1]?>">
            <Culture>en:en</Culture>
            <User>
                <DisplayName><?=$email_address[1]?></DisplayName>
                <EMailAddress><?=$email_address[1]?></EMailAddress>
            </User>
            <Action>
                <Settings>
                    <Server>
                        <Type>MobileSync</Type>
                        <Url><?=$config['MobileSync']['Url']?></Url>
                        <Name><?=$config['MobileSync']['Url']?></Name>
                    </Server>
                </Settings>
            </Action>
        </Response>
    </Autodiscover>
<?php } else if (preg_match('/\/outlook\//', $schema[1])){ /* MUA (mail client)*/ ?>
    <Autodiscover xmlns="http://schemas.microsoft.com/exchange/autodiscover/responseschema/2006">
        <Response xmlns="<?=$schema[1]?>">
            <Account>
                <AccountType>email</AccountType>
                <Action>settings</Action>
                <?php while(list($protocol, $settings) = each($config)){ // Loop through each configured protocol.
                    // Skip ActiveSync protocol.
                    if ($protocol == 'MobileSync') continue; ?>
                    <Protocol>
                        <Type><?=$protocol; ?></Type>
                        <?php while(list($setting, $value) = each($settings)){ // Loop through each setting for this protocol.
                        echo "\t\t\t\t\t\t\t<$setting>$value</$setting>\n";
                        } ?>
                    </Protocol>
                <?php } ?>
            </Account>
        </Response>
    </Autodiscover>
<?php } else{ // Unknown.
	list($usec, $sec) = explode(' ', microtime()); ?>
    <Autodiscover xmlns="http://schemas.microsoft.com/exchange/autodiscover/responseschema/2006">
        <Response>
            <Error Time="<?=date('H:i:s', $sec) . substr($usec, 0, strlen($usec) - 2) ?>" Id="2477272013">
                <ErrorCode>600</ErrorCode>
                <Message>Invalid Request</Message>
                <DebugData />
            </Error>
        </Response>
    </Autodiscover>
<?php } ?>