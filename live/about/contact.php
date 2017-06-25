<?php
$relPath = "../";

include_once($relPath . "navbar/navbar.php");

echo getNavBar();

$raw = file_get_contents($relPath . "../data/societies.json");
$societies = json_decode($raw, true);

$ecss = $societies['ECSS'];

$links = "";
foreach($ecss as $key => $val){
    if(strlen(strstr($val,"http")) > 0 || strlen(strstr($val,"@")) > 0){
        $links .= '<td><a href="' . $val . '>' . $key . '</a></td>';
    }
}
?>
<link rel="stylesheet" href="<?= $relPath ?>theme.css">

<meta charset="UTF-8">
<title>Contact Us | ECSS</title>
        
<div style="padding: 15px">
<h3>Contact Us</h3>
<p>
    ECSS welcomes all communications from its society members!<br><br>
    Please feel free to contact us at any of the links below and we will get back to you as soon as possible.
</p>

<table>
    <tr>
        <?= $links ?>
    </tr>
</table>
</div>