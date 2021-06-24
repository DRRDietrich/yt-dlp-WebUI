<?php
    require_once("config.php"); 
    require_once("sessions.php");
    require_once("utilities.php");

    startSession($_POST['passwd']);
    if(isset($_GET['logout']) && $_GET['logout'] == 1) endSession();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>WebUI</title>
        <link rel="stylesheet" href="css/bootstrap.css" media="screen">
        <link rel="stylesheet" href="css/bootswatch.min.css">
    </head>
    <body>
        <div class="navbar navbar-default">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-responsive-collapse">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="<?php echo $mainPage; ?>">Youtube-dl</a>
            </div>
            <div class="navbar-collapse collapse navbar-responsive-collapse">
                <ul class="nav navbar-nav">
                    <li class="active"><a href="<?php echo $mainPage; ?>">Download</a></li>
                    <li><a href="<?php echo $listPage; ?>">Liste der Dateien</a></li>
                </ul>
            </div>
        </div>
        <div class="container">
            <h1>Download</h1>
<?php
    if(isset($_GET['url']) && isset($_GET['f']) && !empty($_GET['url']) && !empty($_GET['f']) && $_SESSION['logged'] == 1)
    {
        $url = $_GET['url'];
	$format = $_GET['f'];
	switch ($format) {
	case 140: 
		$f = 'bestaudio[ext=m4a]';
		break;
	case 251:
		$f = 'bestaudio[ext=webm]';
		break;
	case 137:
		$f = 'bestvideo[ext=mp4]+bestaudio[ext=m4a]/best[ext=mp4]/best';
		break;
	case 248:
		$f = 'bestvideo[ext=webm]+bestaudio[ext=webm]/best[ext=webm]/best';
		break;
	default: 
		$f = 'bestvideo+bestaudio';
		break;
	}
        $cmd = 'youtube-dl -f ' . $f . ' -o ' . escapeshellarg($folder.'%(title)s-%(uploader)s.%(ext)s') . ' ' . escapeshellarg($url) . ' 2>/dev/null >/dev/null &';
        shell_exec($cmd);
        echo '<div class="alert alert-success">
              <strong>Download gestartet!</strong> <a href="'.$listPage.'" class="alert-link">Zu den Dateien</a>.
              </div>';
    }
    elseif(isset($_SESSION['logged']) && $_SESSION['logged'] == 1)
    { ?>
            <form class="form-horizontal" action="<?php echo $mainPage; ?>">
                <fieldset>
                    <div class="form-group">
                        <div class="col-lg-8">
                            <input class="form-control" id="url" name="url" placeholder="Link" type="text">
                        </div>
			<div class="col-lg-2">
			<select name="f">
			  <option value="140">M4A (Audio-Only)</option>
			  <option value="251">OPUS (Audio-Only)</option>
                          <option value="137">MP4</option>
                          <option value="248">MKV</option>
                          <option value="999">BEST</option>
                        </select>
                        </div>
                        <div class="col-lg-2">
                        <button type="submit" class="btn btn-primary">Download</button>
                        </div>
                    </div>
                    
                </fieldset>
            </form>
            <br>
<?php
    }
    else{ ?>
        <form class="form-horizontal" action="<?php echo $mainPage; ?>" method="POST" >
            <fieldset>
                <legend>Anmelden!</legend>
                <div class="form-group">
                    <div class="col-lg-4"></div>
                    <div class="col-lg-4">
                        <input class="form-control" id="passwd" name="passwd" placeholder="Password" type="password">
                    </div>
                    <div class="col-lg-4"></div>
                </div>
            </fieldset>
        </form>
<?php
        }
    if(isset($_SESSION['logged']) && $_SESSION['logged'] == 1 && isset($_POST['passwd'])) echo '<p><a href="index.php?logout=1">Logout</a></p>';
?>
        </div><!-- End container -->
    </body>
</html>
