<?php include("header.php"); ?>
<div class="panel panel-default">
	<div class="panel-body">
    	<div class="col-md-1"></div>
    	<div class="col-md-2"><img src="../images/logo2.gif" width="211" height="99" class="img-responsive"></div>
		<div class="col-md-8 lead">Importer and Wholesale Distributor of Natural Stone Tile</div>
        <div class="col-md-1"></div>
    </div>
</div>
<div class="panel panel-primary center" style="max-width: 530px;">
    <div class="panel-heading text-center"><h3 class="panel-title">Web Site Administration</h3></div>
    <div class="panel-body">
<?php
	if ($l == "LOGGED") {
		echo "    	<p class=\"lead text-center\">You are currently logged in as:<br>" . $_SESSION['loggedInAs'] . "</p>";
	} else {
?>
    	<form class="form-horizontal" name="login" method="post" action="index.php">
        	<fieldset>
            	<legend<?php
		if ($l == "FAILED") {
			echo " class=\"text-warning\">User Name and/or Password are incorrect.";
		} else {
			echo ">Log In";
		}
	?></legend>
				<div class="form-group">
					<label for="userName" class="col-lg-3 control-label">User Name</label>
					<div class="col-lg-9">
						<input class="form-control" id="userName" name="user" placeholder="User Name" type="text">
					</div>
				</div>
                <div class="form-group">
					<label for="pass" class="col-lg-3 control-label">Password</label>
                    <div class="col-lg-9">
                        <input class="form-control" id="password" name="pass" placeholder="Password" type="password">
                    </div>
                </div>
                <div class="form-group">
					<div class="col-lg-9 col-lg-offset-3">
				        <button class="btn btn-default">Cancel</button>
				        <button type="submit" class="btn btn-primary">Submit</button>
					</div>
				</div>
			</fieldset>
		</form>
<?php
	}
?>
    </div>
</div>
<?php
	include("footer.php");
	include("standardjs.php");
?>
</body>
</html>