<?php
	require("codelib.php");
	if (isset($_GET["b"]))
	{
		$b = $_GET["b"];
		$grp = new product;
		$myInfo = $grp->getItmInfo($b);
		$myGallery = $grp->getGallery($b);
?>
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel"><?php echo $myInfo["itmName"]; ?></h4>
      </div>
      <div class="modal-body">
		<div id="carousel-gallery" class="carousel slide" data-ride="carousel">
        	<div class="carousel-inner">
<?php
		$x = True;
		foreach ($myGallery as $myImg)
		{
			if ($x)	{ $y = "item active"; $x = false; }
			else { $y = "item"; }
?>
				<div class="<?php echo $y; ?>">
                	<img class="center-block img-responsive" src="<?php echo $myImg["imgurl"]; ?>">
                    <div class="carousel-caption"><h3><?php echo $myImg["title"]; ?></h3></div>
                </div>
<?php
		}
?>
       		</div>     	
            <a class="left carousel-control" href="#carousel-gallery" data-slide="prev">
              <span class="glyphicon glyphicon-chevron-left"></span>
            </a>
            <a class="right carousel-control" href="#carousel-gallery" data-slide="next">
              <span class="glyphicon glyphicon-chevron-right"></span>
            </a>
		</div>
      </div>
      <div class="modal-footer">
        <p><?php echo $myInfo["itmDesc"]; ?></p>
      </div>

<?php
	}
	else
	{
?>
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Item Retreival Error</h4>
      </div>
      <div class="modal-body">
        <h3>Item Information Unavailable</h3>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
<?php	
	}
?>