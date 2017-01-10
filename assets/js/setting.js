$(function() {
	$("#change_user_name").click(function() {
		$("#save_user_name").show();
		$("#input-user-first-name").removeAttr('disabled');
		$("#input-user-last-name").removeAttr('disabled');
	})
	$("#save_user_name").click(function() {
		var data = {
			email: email,
			first_name: $("#input-user-first-name").val(),
			last_name: $("#input-user-last-name").val()
		}
		$.ajax({
			url: base_url + 'user/change_user_name',
			data: data,
			dataType: 'json',
			method: 'post',
			success: function(response) {
				if (response.status == 'ok') {
					window.history.go();
				}
			}
		})
	})

	$("#change_email").click(function() {
		$("#change_new_email").show();
		$("#input-new-email").removeAttr('disabled');
	})

	$("#change_new_email").click(function() {
		var data = {
			email: email,
			new_email: $("#input-new-email").val()
		}
		$.ajax({
			url: base_url + 'user/change_user_email',
			data: data,
			dataType: 'json',
			method: 'post',
			success: function(response) {
				if (response.status == 'ok') {
					window.history.go();
				}
			}
		})
	})
	$("#change_photo").click(function() {
		$("#photo_file_upload").click();
	})
	$("#photo_file_upload").change(function(event) {
		var files = event.target.files;
		var image = files[0];
		var reader = new FileReader();
		reader.onload = function(file) {
			var img = $("#new-photo-img");
			img.attr('src', file.target.result);
		}
		reader.readAsDataURL(image);
		$("#preview-photo-modal").modal();
	})


	$(".upload-this-file").click(function() {
		$("#photo-upload-form").submit();
	})
})
