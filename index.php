<?php
	include("header.php");
	$SQL = new mSQL;
	$myCarousel = $SQL->selectQ("carousel", "*");
?>
<div class="panel panel-default">
	<div class="panel-body">
    	<div class="col-md-1"></div>
    	<div class="col-md-2"><img src="images/logo2.gif" width="211" height="99" class="img-responsive"></div>
		<div class="col-md-8 lead">Importer and Wholesale Distributor of Natural Stone Tile</div>
        <div class="col-md-1"></div>
    </div>
</div>
<div class="panel panel-primary center">
    <div class="panel-heading text-center"><h3 class="panel-title">Supplier of Quality Natural Stone & Fine Floor Coverings</h3></div>
    <div class="panel-body">
    	<!--<img class="img-responsive" src="images/travertine+pic+fp.jpg">-->
        <div id="carousel-gallery" class="carousel slide" data-ride="carousel">
        	<ol class="carousel-indicators">
<?php
	for ($x = 0; $x < count($myCarousel); $x++)
	{
?>
				<li data-target="#carousel-gallery" data-slide-to="<?php echo $x; ?>"<?php if($x == 0) { echo " class=\"active\""; } ?>></li>
<?php
	}
?>
			</ol>
        	<div class="carousel-inner">
<?php
	for ($x = 0; $x < count($myCarousel); $x++)
	{
?>
				<div class="item<?php if($x == 0) { echo " active"; } ?>">
                	<img class="center-block img-responsive" src="<?php echo $myCarousel[$x]["imgurl"]; ?>">
                    <div class="carousel-caption"><h3><?php echo $myCarousel[$x]["caption"]; ?></h3></div>
                </div>
<?php
	}
?>
            </div>
        </div>
    </div>
</div>
<div class="col-sm-6">
<div class="panel panel-primary center" style="max-width: 300px;">
    <div class="panel-heading text-center"><h3 class="panel-title"><b>Arizona</b></h3></div>
    <div class="panel-body"><p class="text-primary text-center"><b>129 East Pima Street<br>Phoenix, Arizona 85004<br>office: 602-253-7800<br>fax: 602-253-7801</b></p></div>
</div>
</div>
<div class="col-sm-6">
<div class="panel panel-primary center" style="max-width: 300px;">
    <div class="panel-heading text-center"><h3 class="panel-title"><b>Utah</b></h3></div>
    <div class="panel-body"><p class="text-primary text-center"><b>2668 South 300 West<br>Salt Lake City, Utah 84115<br>office: 801-649-4750<br>fax: 801-649-4776</b></p></div>
</div>
</div>
<?php
	include("footer.php");
	include("standardjs.php");
?>
</body>
</html>