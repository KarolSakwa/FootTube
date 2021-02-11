<?php include_once('includes/header.php');?>
<?php include_once("classes/VideoDetailsForm.php");?>
	<div class="column">
	
	<?php
		$videoDetailsForm = new VideoDetailsForm($con);
		
		echo $videoDetailsForm->createUploadForm();

	?>

	
	</div>

	<script>
	$("form").submit(function(){
		// show the modal
		$("#loadingModal").modal("show");
	})
	</script>

	<!-- Modal -->
	<div class="modal fade" id="loadingModal" tabindex="-1" role="dialog" aria-labelledby="loadingModal" aria-hidden="true" data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
				<div class="modal-body">
					Please wait. Video upload in progress...
					<img src="static/img/icons/loading-spinner.gif" />
				</div>
			</div>
		</div>
	</div>

<?php include_once('includes/footer.php');?>