<?php require("codelib.php"); ?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Majestic Stone Import, Inc.</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link href="css/bootstrap.css" rel="stylesheet" media="screen">
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
                <span class="navbar-brand">Majestic Stone Import</span>
            </div>
            <div class="navbar-collapse collapse">
                <ul class="nav navbar-nav navbar-right">
                    <li><a runat="server" href="index.php">Home</a></li>
                    <li><a runat="server" href="about.php">About</a></li>
                    <li class="dropdown">
                      <a href="products.php" class="dropdown-toggle" data-toggle="dropdown">Products <b class="caret"></b></a>
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
                    <li><a runat="server" href="contact.php">Contact</a></li>
                </ul>
            </div>
        </div>
    </div>