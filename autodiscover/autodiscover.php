<?

require '../config.php';

if(defined('ZPUSH_URL')) $_CONFIG['MobileSync']['Url'] = ZPUSH_URL;
else $_CONFIG['MobileSync']['Url'] = 'https://'.$_SERVER['HTTP_HOST'].'/Microsoft-Server-ActiveSync';

// IMAP configuration settings.

$_CONFIG['IMAP']['Server']       = parse_url(ZIMBRA_URL)['host'];
$_CONFIG['IMAP']['Port']         = "993";
$_CONFIG['IMAP']['SSL']          = "on";
$_CONFIG['IMAP']['SPA']          = "off";
$_CONFIG['IMAP']['AuthRequired'] = "on";
 
// SMTP configuration settings.

$_CONFIG['SMTP']['Server']       = $_CONFIG['IMAP']['Server'];
$_CONFIG['SMTP']['Port']         = "465";
$_CONFIG['SMTP']['SSL']          = "on";
$_CONFIG['SMTP']['SPA']          = "off";
$_CONFIG['SMTP']['AuthRequired'] = "on";
/*** End Configuration ***/
 
// For other supported protocols and more protocol settings, see:
// http://technet.microsoft.com/en-us/library/cc511507.aspx
 
// Get contents of request made to Autodiscover.

$request = file_get_contents("php://input");
 
// XML document heading.

header("Content-Type: text/xml");
echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
 
// Get the schema from the request.

preg_match("/\<AcceptableResponseSchema\>(.*?)\<\/AcceptableResponseSchema\>/", $request, $schema);

// Determine the type of device requesting Autodiscover.

preg_match("/\<EMailAddress\>(.*?)\<\/EMailAddress\>/", $request, $email_address);

if(!empty($email_address[1])){
	$_CONFIG['IMAP']['LoginName'] = $email_address[1];
	$_CONFIG['SMTP']['LoginName'] = $email_address[1];
}

if (preg_match("/\/mobilesync\//", $schema[1])){ ?>
		<Autodiscover xmlns="http://schemas.microsoft.com/exchange/autodiscover/responseschema/2006">
			<Response xmlns="<? echo $schema[1]; ?>">
				<Culture>en:en</Culture>
				<User>
					<DisplayName><? echo $email_address[1]; ?></DisplayName>
					<EMailAddress><? echo $email_address[1]; ?></EMailAddress>
				</User>
				<Action>
					<Settings>
						<Server>
							<Type>MobileSync</Type>
							<Url><? echo $_CONFIG['MobileSync']['Url']; ?></Url>
							<Name><? echo $_CONFIG['MobileSync']['Url']; ?></Name>
						</Server>
					</Settings>
				</Action>
			</Response>
		</Autodiscover>
	<?
}
else if (preg_match("/\/outlook\//", $schema[1])){

	// MUA (mail client).
	?>
		<Autodiscover xmlns="http://schemas.microsoft.com/exchange/autodiscover/responseschema/2006">
			<Response xmlns="<? echo $schema[1]; ?>">
				<Account>
					<AccountType>email</AccountType>
					<Action>settings</Action>
					<?
					// Loop through each configured protocol.
					while(list($protocol, $settings) = each($_CONFIG)){
						// Skip ActiveSync protocol.
						if ($protocol == "MobileSync") continue; ?>
						<Protocol>
							<Type><? echo $protocol; ?></Type>
					<?
						// Loop through each setting for this protocol.
						while(list($setting, $value) = each($settings)){
							echo "\t\t\t\t\t\t\t<$setting>$value</$setting>\n";
						}
					?>
						</Protocol>
					<?
					}
				?>
				</Account>
			</Response>
		</Autodiscover>
	<?
}

else{
	// Unknown.
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
	<?
}
?>