<!DOCTYPE html>
<?php
    require_once("config.php"); 
    require_once("sessions.php");
    require_once("utilities.php");

    if(isset($_GET['logout']) && $_GET['logout'] == 1) endSession();
    if(isset($_POST['downall'])) {
        $zip = new ZipArchive();
        $filename = "./Archiv.zip";
        if ($zip->open($filename, ZipArchive::CREATE)!==TRUE) {
            exit("<$filename> kann nicht erstellt werden\n");
        }
        foreach(glob($folder."*") as $file) {
            $zip->addFile($file);
        }
        $zip->close();
    }
?>
<html>
    <head>
        <meta charset="utf-8">
        <title>Dateiliste</title>
        <link rel="stylesheet" href="css/bootstrap.css" media="screen">
        <link rel="stylesheet" href="css/bootswatch.min.css">
    </head>
    <body >
        <div class="navbar navbar-default">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-responsive-collapse">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="<?php echo $mainPage; ?>">Youtube-dl</a>
            </div>
            <div class="navbar-collapse  collapse navbar-responsive-collapse">
                <ul class="nav navbar-nav">
                    <li><a href="<?php echo $mainPage; ?>">Download</a></li>
                    <li class="active"><a href="<?php echo $listPage; ?>">Liste der Dateien</a></li>
                </ul>
            </div>
        </div>
        <div class="container">
<?php
if(isset($_SESSION['logged']) && $_SESSION['logged'] == 1)
{
    if(isset($_POST['delall'])) {
        if(file_exists("Archiv.zip") && ! unlink("Archiv.zip")){
            echo '<div class="panel panel-danger">';
            echo '<div class="panel-heading"><h3 class="panel-title">Datei löschen: Archiv.zip</h3></div>';
            echo '<div class="panel-body">Die Datei Archiv.zip konnte nicht gelöscht werden!</div>';
            echo '</div>';
        }
        foreach(glob($folder."*") as $file) {
            if(! unlink($file)){
                echo '<div class="panel panel-danger">';
                echo '<div class="panel-heading"><h3 class="panel-title">Datei löschen: '.$file.'</h3></div>';
                echo '<div class="panel-body">Die Datei '.$file.' konnte nicht gelöscht werden!</div>';
                echo '</div>';
            }
        }
    }
    ?>
        <h2>Liste der verfügbaren Dateien:</h2>
            <table class="table table-striped table-hover ">
                <thead>
                    <tr>
                        <th style="min-width:800px; height:35px">Titel</th>
                        <th style="min-width:80px">Größe</th>
                    </tr>
                </thead>
                <tbody>
<?php
            foreach(glob($folder."*") as $file)
            {
                $filename = str_replace($folder, "", $file); // Need to fix accent problem with something like this : utf8_encode
                echo "<tr>"; //New line
                echo "<td height=\"30px\">$filename</td>"; //1st col
                echo "<td>".human_filesize(filesize($folder.$filename))."</td>"; //2nd col
                echo "</tr>"; //End line
            }
	    if(file_exists("Archiv.zip")) {
                echo "<tr>"; //New line
                echo "<td height=\"30px\"><a href=\"Archiv.zip\">Archiv.zip</a></td>"; //1st col
                echo "<td>".human_filesize(filesize("Archiv.zip"))."</td>"; //2nd col
                echo "</tr>"; //End line
            }
?>
                </tbody>
            </table>
	    <div><form action="list.php" method="post"><input type="submit" name="downall" value="Download Archiv erstellen" /></form><br/>
            <form action="list.php" method="post"><input type="submit" name="delall" value="Alle Löschen" /></form></div>
<?php
} 
else {
    echo '<div class="alert alert-danger"><strong>Zugriff verweigert:</strong> Sie müssen sich anmelden!</div>';
} ?>
	    <br/>
            <?php if(!isset($_GET['fileToDel'])) echo "<a href=".$mainPage.">Zurück zur Download-Seite</a>"; ?>
        </div><!-- End container -->
        <br>
    </body>
</html>
