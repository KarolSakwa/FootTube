<?php include_once('includes/header.php');?>
<?php include_once('includes/sidenavsmall.php');?>
	
<div class="videoSection">
	<?php
	$subscriptions = new Subscriptions($con, $userLoggedInObj);
	$subscriptionVideos = $subscriptions->getVideos();
	

	$videoGrid = new VideoGrid($con, $userLoggedInObj->getUsername());

	if(User::isLoggedIn() && sizeof($subscriptionVideos) > 0)
	{
		echo $videoGrid->create($subscriptionVideos, "Subscriptions", false);	
	}

	if ($videoGrid != null)
	{
		echo $videoGrid->create(null, "Suggestions", false);
	}
	?>
</div>
	
	</div>
<?php include_once('includes/footer.php');?>
