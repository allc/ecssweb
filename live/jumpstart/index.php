<?php
$relPath = "../";
include_once($relPath . "includes/setLang.php");
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <?php
    setTextDomain('title');
    ?>
    <title><?= _('Jumpstart') ?> | ECSS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="/theme.css" />
    <link rel="stylesheet" type="text/css" href="jumpstart.css" />
</head>
<body>
<?php
include_once($relPath . "navbar/navbar.php");
echo getNavBar();
?>
<div>
    <div><p>Jumpstart is an opportunity for freshers to meet other students in the faculty, take part in a range of activities, and settle into Southampton. It is the first event of the year organised by ECSS, and is sponsored by IBM. See below for the week’s timetable, and information on the City Challenge. If you have any questions feel free to <a href="/about/contact.php?lang=<?= $lang ?>">contact our committee</a>, and have fun!</p></div>
    <div>
        <div><img src="/images/jumpstart/jumpstart_sponsor_ibm.jpg" alt="Jumpstart sponsor IBM logo"></div>
        <div><img src="/images/jumpstart/jumpstart_logo.png" alt="Jumpstart logo"></div>
        <div>
            <ul class="jumpstartLinks">
                <li>Timetable - MSc</li>
                <li>Timetable - UG</li>
                <li>City Challenge</li>
            </ul>
        </div>
        <div>
            <p>The Jumpstart City Challenge is our take on introducing you to Southampton. We’ll be splitting you into teams, assigning you a Jumpstart Helper (a current ECS student), and giving you the aim of getting as many points as possible.</p>
            <p>Points can be achieved by exploring the main areas this side of the city; Highfield Campus, The Common, and Portswood; and completing various other challenges outlined below. In doing so, you’ll be introduced to the members of the ECSS committee, find your bearings in Southampton, and make some new friends!</p>
            <p>The winning team will be announced at the Jumpstart reception on Friday 29th September, with prizes from the faculty, ECSS, and IBM up for grabs. The challenges are listed below, you can do as many or as few as you like, in any order. Good luck!</p>
        </div>
    </div>
</div>
</body>
</html>
