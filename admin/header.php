<?php
	require("../codelib.php");
	session_start();
	if (!isset($_SESSION['loggedInAs'])) {
		if (isset($_POST['user'])) {
			$user = $_POST['user'];
			$pass = $_POST['pass'];
			$chck = new login;
			$rslt = $chck->authenticate($user, $pass);
			if ($rslt) {
				$_SESSION['loggedInAs'] = $rslt;
				$l = "LOGGED";
			} else {
				$l = "FAILED";
			}
		} else {
			$l = "LOGIN";
		}
	} else {
		$l = "LOGGED";
	}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Majestic Stone Import, Inc.</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link href="../css/bootstrap.css" rel="stylesheet" media="screen">
    <link rel="stylesheet" href="css/jquery.fileupload.css">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
  <div class="container" style="margin-bottom: 150px;">
    <div class="navbar navbar-default">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a href="index.php" class="navbar-brand">Majestic Stone Import</a>
            </div>
<?php
	if ($l == "LOGGED")
	{
?>
            <div class="navbar-collapse collapse">
                <ul class="nav navbar-nav navbar-right">
                    <li><a runat="server" href="homescreen.php">Home Screen</a></li>
                    <li><a runat="server" href="groups.php">Product Groups</a></li>
                    <li class="dropdown">
                      <a href="groups.php" class="dropdown-toggle" data-toggle="dropdown">Products <b class="caret"></b></a>
                      <ul class="dropdown-menu">
                      <?php
					  	$SQL = new mSQL;
						$groups = $SQL->selectQ("groups");
						foreach ($groups as $grp)
						{
							$a = $grp["ID"];
							$b = $grp["groupname"];
							echo "
                        <li><a href='product.php?a=$a'>$b</a></li>";
						}
						unset($SQL);
					  ?>
                      </ul>
                    </li>
                </ul>
            </div>
<?php
	}
?>
        </div>
    </div>