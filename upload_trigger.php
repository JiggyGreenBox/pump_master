<!DOCTYPE html>
<html>
<head>
	<title>Upload Trigger</title>
	<script type="text/javascript" src="js/jquery.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){
			////////     Do not uncomment ///////////
			function master_upload() {
				var upload = "upload";
				$.ajax({
					url: 'pm_upload.php',
					type: 'POST',
					data: { upload : upload },
					success: function(data) {
						console.log(data);
					}
				});
			}


			// master_upload();			
		});
	</script>
</head>
<body>
<p>Upload Trigger</p>
</body>
</html>