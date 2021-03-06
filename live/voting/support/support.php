<?php
$relPath = "../../";

$dbLoc = realpath($relPath . "../db/ecss.db");

$db = new PDO('sqlite:' . $dbLoc);

require_once($relPath . '../config/config.php');

if (DEBUG) {
    //debug version
    $attributes = [
    	"http://schemas.microsoft.com/ws/2008/06/identity/claims/windowsaccountname" => array("hb15g16"),
    	"http://schemas.xmlsoap.org/claims/Group" => array(
    		"Domain Users",
    		"allStudent",
    		"fpStudent",
    		"jfNISSync",
    		"fpappvmatlab2009b",
    		"AllStudentsMassEmail",
    		"f7_All_Faculty_Student",
    		"ebXRDDataSharedResourceRead",
    		"isiMUSI2015Users",
    		"jfSW_Deploy_OpenChoiceDesktop_2.2_SCCM"
    		),
    	"http://schemas.xmlsoap.org/ws/2005/05/identity/claims/emailaddress" => array("hb15g16@ecs.soton.ac.uk"),
    	"http://schemas.xmlsoap.org/ws/2005/05/identity/claims/givenname" => array("Harry"),
    	"http://schemas.xmlsoap.org/ws/2005/05/identity/claims/surname" => array("Brown")
    ];
} else {
    //live version
    require_once('/var/www/auth/lib/_autoload.php');
    $as = new SimpleSAML_Auth_Simple('default-sp');
    $as->requireAuth();
    $attributes = $as->getAttributes();
}

// csrf token
session_name('csrf_protection');
session_start();
if (empty($_SESSION['csrftoken'])) {
    $_SESSION['csrftoken'] = bin2hex(random_bytes(32));
}
$csrftoken = $_SESSION['csrftoken'];

$userInfo = array(
	'username' => $attributes["http://schemas.microsoft.com/ws/2008/06/identity/claims/windowsaccountname"][0],
	'groups' => $attributes["http://schemas.xmlsoap.org/claims/Group"],
	'email' => $attributes["http://schemas.xmlsoap.org/ws/2005/05/identity/claims/emailaddress"][0],
	'firstName' => $attributes["http://schemas.xmlsoap.org/ws/2005/05/identity/claims/givenname"][0],
	'lastName' => $attributes["http://schemas.xmlsoap.org/ws/2005/05/identity/claims/surname"][0]
);

$emailParts = explode("@", $userInfo['email']);

if(!(in_array("fpStudent", $userInfo['groups']) || in_array("fpStaff", $userInfo['groups']))){
	echo json_encode(['status' => false, 'message' => "You're not a member of ECS"]);
	exit;
}

$nominationID = $_POST['nominationID'];
$token = $_POST['token'];

//check for token validity
$sql = "SELECT supportTokenID AS tokenID
		FROM supportToken
		WHERE supportToken = :token
		AND supportEmail = :email
		AND supportTokenUsed = 0";

$statement = $db->prepare($sql);
$statement->execute([':email' => $userInfo['email'], ':token' => $token]);

if ($row = $statement->fetchObject()) {
	//we good
	$sql = "UPDATE supportToken SET supportTokenUsed = 1 WHERE supportTokenID = :tokenID";
	$statement = $db->prepare($sql);
	$statement->execute([':tokenID' => $row->tokenID]);
} else {
	//we not good
	echo json_encode(['status' => true, 'message' => "Your token was not valid"]);
	exit;
}


//clear old support
$sql = "DELETE FROM support WHERE supportUsername = :username;";
$statement = $db->prepare($sql);
$statement->execute([':username' => $userInfo['username']]);

//add new support
$sql = "INSERT INTO support(nominationID, supportUsername) VALUES(:nominationID, :username);";
$statement = $db->prepare($sql);
$statement->execute([':nominationID' => $nominationID, ':username' => $userInfo['username']]);

echo json_encode(['status' => true, 'message' => "You have sucessfully supported your candidate"]);