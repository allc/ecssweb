<?php
$relPath = "../";

include_once($relPath . "navbar/navbar.php");

if (!isset($_GET['section'])) {
    echo "Not a valid leaflet section!";
    exit;
}
$section = $_GET['section'];

$raw = file_get_contents($relPath . "../data/" . $lang . "/leaflet.json");
$sectionData = json_decode($raw, true);

$sectionData = $sectionData[$section];

$body = "";

//todo: print out title
$body .= "<div id=\"titleDiv\">";
$body .= "<h1 id=\"sectionTitle\">" . $section . "</h1>";

foreach ($sectionData as $key => $value) {
    if ($key === "Overview") {
        //todo: print out overview
        $body .= "<p id=\"overview\">" . $value . "</p>";
        $body .= "</div>";
    } else
    if ($key === "Subsections") {
        //$body .= "<p>lolool</p>";
        foreach ($value as $subkey => $subvalue) {
            //$body .= "<p>sub-lolool</p>";
            //todo: print out subsection title
            $body .= "<div class=\"subsection\">";
            $body .= "<div class=\"subsectionText\">";
            $body .= "<h1 class=\"subsectionTitle\">" . $subkey . "</h1>";

            foreach ($subvalue as $subsubkey => $subsubvalue) {
                if ($subsubkey == "Text") {
                    //$body .= "<p>subsub-lolool</p>";
                    //todo: print out text
                    $body .= "<p class=\"subsectionText\">" . $subsubvalue . "</p>";
                    $body .= "</div>";
                }

                if ($subsubkey == "Image") {
                    //$body .= "<p>sub-image-lolool</p>";
                    //todo: print out image
                    $body .= "<img class=\"subsectionImage\" src=\"" . $relPath . $subsubvalue . "\">";
                }

                if ($subsubkey == "SlideShow") {
                    $files = scandir($relPath . $subsubvalue);

                    ?>
                    <script>
                        var files = <?= json_encode($files) ?>;
                    </script>
                    <?

                    $body .= "<div class=\"slideshow\"></div>";
                }
            }
            
            $body .= "</div>";
        }
    }
}

/*
  for($i = 0; $i < count($sectionData['text']); $i++){
  if(in_array($i, $sectionData['titleIndexes'])){
  $body .= "<h3>" . $sectionData['text'][$i] . "</h3>";
  } else {
  $body .= "<p>" . $sectionData['text'][$i] . "</p>";
  }

  if(isset($sectionData['images'][$i])){
  $body .= "<img class='leafletImage' src='" . $relPath . $sectionData['images'][$i] . "'>";
  }
  }

 */
?>
<html>
    <title><?= $sectionData['title'] ?></title>
    
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="<?= $relPath ?>theme.css"/>
    <link rel="stylesheet" type="text/css" href="/leaflet/leaflet.css"/>

    <body>
<?= getNavBar(); ?>
<?= $body ?>
    </body>
</html>
