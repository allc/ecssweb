<?php
exit;
$relPath = "../";
include_once ($relPath . 'includes/setLang.php');

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

$sql = "SELECT *
FROM company AS c
WHERE datetime(c.applicationStartDate) < datetime('now')
AND datetime(c.applicationEndDate) > datetime('now')";

$statement = $db->query($sql);

$current = $statement->fetchObject();

?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <?php
    setTextDomain('title');
    ?>
    <title><?= $current->companyName ?> Application Page | ECSS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrftoken" content="<?= $csrftoken ?>">
    <link rel="stylesheet" type="text/css" href="<?= $relPath ?>theme.css" />
</head>
<body>
<link rel="stylesheet" type="text/css" href="/cv/cv.css" />
<script src="/cv/cv.js"></script>
<?php
include_once($relPath . "navbar/navbar.php");
echo getNavBar();

//check for previous entries!
$sql = "SELECT *
        FROM application AS a
        WHERE a.applicationUsername = :username
        AND a.companyID = :companyID";

$statement = $db->prepare($sql);
$statement->execute([':username' => $userInfo['username'], ':companyID' => $current->companyID]);

?>
<div id='intro'>
<h3>Following Ultra Electronics' information talk and interview briefing on Tuesday 23rd October, applications are now open for their Interview Day that is taking place on campus on Tuesday 21st November.</h3>
<p>Please enter your details below, along with your CV and cover letter (both in PDF format) to apply. The cover letter should outline the reasons why you would like to apply for an internship, placement, or graduate role with Ultra Electronics, and is your chance to differentiate yourself from other applicants.</p>
<p>The deadline for applications is 6:00pm on Wednesday 8th November, and applicants will be notified in due course if they are successful and invited to the Interview Day.</p>
<p style='font-weight: bold;'>This deadline is a hard deadline, we will not accept any late applications.</p>
</div>
<?php
//wow! previous entry!
if($entry = $statement->fetchObject()){
    $errorBlock = "Hi " . $userInfo['firstName'] . ", you seem to have already entered your information on this form.<br><br>If you need to make any changes, email Harry Brown at <a href='mailto:hb15g16@soton.ac.uk'>hb15g16@soton.ac.uk</a>.";
    //check for cover letter and cv
    if($entry->applicationCV == ""){
        $errorBlock .= "<br><br>You seem to be missing a CV, email Harry Brown at <a href='mailto:hb15g16@soton.ac.uk'>hb15g16@soton.ac.uk</a> if you want to add one!";
    } else {
        $errorBlock .= "<br><br>Your CV has been uploaded successfully.";
    }

    if($entry->applicationCover == ""){
        $errorBlock .= "<br><br>You seem to be missing a Cover Letter, email Harry Brown at <a href='mailto:hb15g16@soton.ac.uk'>hb15g16@soton.ac.uk</a> if you want to add one!";
    } else {
        $errorBlock .= "<br><br>Your cover letter has been uploaded successfully.";
    }

    //print that error block!
    echo "<p id='errorBlock'>" . $errorBlock . "</p>";
    exit;
}
?>
<script src="/jquery.js"></script>
<script src="/ajaxfileupload.js"></script>
<div id="formDiv">
    <h3>
    <?= $current->companyName ?> Application Page
    </h3>

    <table>
        <tr>
            <td>
                Name
            </td>
            <td>
                <input type="text" id="name" name="name">
            </td>
        </tr>
        <tr>
            <td>
                Course
            </td>
            <td>
                <input type="text" id="course" name="course">
            </td>
        </tr>
        <tr>
            <td>
                Graduation Year
            </td>
            <td>
                <select id='year'>
                    <option value='2017'>2017</option>
                    <option value='2018'>2018</option>
                    <option value='2019'>2019</option>
                    <option value='2020'>2020</option>
                    <option value='2021'>2021</option>
                    <option value='2022'>2022</option>
                    <option value='2023'>2023</option>
                </select>
            </td>
        </tr>
        
        <tr>
            <td>
                Upload your CV
            </td>
            <td>
                <input type="file" name="CV" id="CV"></input>
                <progress id="progCV" value='0' min='0' max='100'></progress>
            </td>
        </tr>
        <tr>
            <td>
                Upload your cover letter<br>(mandatory, not optional)
            </td>
            <td>
                <input type="file" name="Cover" id="Cover"></input>
                <progress id="progCover" value='0' min='0' max='100'></progress>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                Accepted file types are: .doc, .docx, .pdf, .rtf, .txt, .wks, .wkp and .wpd
            </td>
        </tr>
        <tr>
            <td>
                <button id="submit" onclick='submit()'>Submit</button>
            </td>
            <td></td>
        </tr>
    </table>
</div>