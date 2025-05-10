<?php
	// Start session
	session_start();

	// Include config.php
    require_once("config.php");

	function startSession($pass)
	{

		$pass = htmlentities((string)$pass, ENT_QUOTES, 'UTF-8');
		global $security;
		if($security == 1)
		{
			if(passwordMatch($pass)) $_SESSION['logged'] = 1;
			else $_SESSION['logged'] = 0;
		} else {
			$_SESSION['logged'] = 1;
		}
	}

	function passwordMatch($pass)
	{
		global $secretPassword;
		if(md5($pass) == $secretPassword) return 1;
		else return 0;
	}

	function endSession()
	{
		global $security;
		if($security == 1) session_destroy();
	}
?>
