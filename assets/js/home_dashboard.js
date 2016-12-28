$(function() {
	$("#videos_table").dataTable();


	$('.add-new-video').click(function() {
		$('#video_file_upload').click();
	})

	// var video_input = document.getElementById('video_file_upload');
	// var video = document.getElementById('play_video');


	$('#video_file_upload').on('change', function() {
		var file = this.files[0];
		var reader = new FileReader();
		reader.onload = viewer.load;
		reader.readAsDataURL(file);
		viewer.setProperties(file);
		$("#preview-video-modal").modal();
	})

	var viewer = {
		load : function(e) {
			$("#preview-video").attr('src', e.target.result);
		},
		setProperties : function(file) {
			console.log(file.name);
			console.log(file.type);
			console.log(Math.round(file.size / 1024));
		}
	}

	$(".cancel-upload").click(function() {
		$("#preview-video").attr('src', "");
		$("#preview-video-modal").modal('hide');
	})

	$(".upload-this-file").click(function() {
		$('.video-upload-form form').submit();
	})

	
})