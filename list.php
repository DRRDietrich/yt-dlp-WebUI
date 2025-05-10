<?php
    require_once("config.php"); 
    require_once("sessions.php");
    require_once("utilities.php");

    startSession($_POST['passwd'] ?? '');
    if(isset($_GET['logout']) && $_GET['logout'] == 1) endSession();
    if(isset($_POST['downall'])) {
        $zip = new ZipArchive();
        $filename = "./Archiv.zip";
	set_time_limit(0);
        if ($zip->open($filename, ZipArchive::CREATE)!==TRUE) {
            exit("<$filename> kann nicht erstellt werden\n");
        }
        foreach(glob($folder."*") as $file) {
            $zip->addFile($file);
        }
        $zip->close();
    }
    if(isset($_POST['kill'])) {
	$cmd = 'killall -9 youtube-dl';
        shell_exec($cmd);

    }
?>
<html>
    <head>
        <meta charset="utf-8">
        <title>Dateiliste</title>
        <link rel="stylesheet" href="css/bootstrap.css" media="screen">
        <link rel="stylesheet" href="css/bootstrap.min.css">
    </head>
    <body >
        <div class="navbar navbar-default">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-responsive-collapse">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="https://github.com/DRRDietrich/Youtube-dl-WebUI">Youtube-dl</a>
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
    if(isset($_GET['fileToDel'])) {
      $fileToDel = urldecode($_GET['fileToDel']);
      if($fileToDel == "Archiv.zip" && file_exists("Archiv.zip") && ! unlink("Archiv.zip")){
            echo '<div class="panel panel-danger">';
            echo '<div class="panel-heading"><h3 class="panel-title">Datei löschen: Archiv.zip</h3></div>';
            echo '<div class="panel-body">Die Datei Archiv.zip konnte nicht gelöscht werden!</div>';
            echo '</div>';
      } 
      if(file_exists($folder.$fileToDel) && ! unlink($folder.$fileToDel)) {
                echo '<div class="panel panel-danger">';
                echo '<div class="panel-heading"><h3 class="panel-title">Datei löschen: '.$fileToDel.'</h3></div>';
                echo '<div class="panel-body">Die Datei '.$fileToDel.' konnte nicht gelöscht werden!</div>';
                echo '</div>';
      }      
    }
    ?>
        <h2>Liste der verfügbaren Dateien:</h2>
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th style="min-width:800px; height:35px">Titel</th>
                        <th style="min-width:80px">Größe</th>
                        <th style="min-width:80px">Löschen</th>
                    </tr>
                </thead>
                <tbody>
<?php
            foreach(glob($folder."*") as $file)
            {
                $filename = str_replace($folder, "", $file); // Need to fix accent problem with something like this : utf8_encode
                echo "<tr>"; //New line
                echo "<td height=\"30px\"><a href='$folder".rawurlencode($filename)."'>$filename</a></td>"; //1st col
                echo "<td>".human_filesize(filesize($folder.$filename))."</td>"; //2nd col
		echo "<td><a href=\"".$listPage."?fileToDel=".urlencode($filename)."\" class=\"text-danger\">Löschen</a></td>"; //3rd col
                echo "</tr>"; //End line
            }
	    if(file_exists("Archiv.zip")) {
                echo "<tr>"; //New line
                echo "<td height=\"30px\"><a href=\"Archiv.zip\">Archiv.zip</a></td>"; //1st col
                echo "<td>".human_filesize(filesize("Archiv.zip"))."</td>"; //2nd col
		echo "<td><a href=\"".$listPage."?fileToDel=".urlencode('Archiv.zip')."\" class=\"text-danger\">Löschen</a></td>"; //3rd col
                echo "</tr>"; //End line
            }
?>
                </tbody>
            </table>
	    <div>
                <form action="list.php" method="post">
                    <input type="submit" class="btn btn-info" name="downall" value="Archiv erstellen" />
                    <input type="submit" class="btn btn-warning" name="kill" value="Download stoppen" />
                    <input type="submit" class="btn btn-danger" name="delall" value="Alle Löschen" />
                </form>
            </div>
<?php
} 
else {
    echo '<div class="alert alert-danger"><strong>Zugriff verweigert:</strong> Sie müssen sich anmelden!</div>';
} ?>
        </div><!-- End container -->
        <br>
    </body>
</html>
