$(function() {
	$('.clip-data').click(function() {
		// alert($(this).data('start'));
	})
	$('.tree-toggle').click(function () {
		$(this).parent().children('ul.tree').toggle(200);
	});

	$('.tree-toggle').parent().children('ul.tree').toggle(200);

	$(".category-clips > li").each(function(index) {
		var element = $(this);
		if (element.find('li').length > 0) {
			element.addClass('active');
		}
	})

	var category_name = "";
	var category_time_stamp = "";
	$(".category-clips > li").click(function() {
		$(".category-clips > li").removeClass('selected');
		$(".category-clips > li.active").removeClass('selected');
		$(this).addClass('selected');
		if ($(this).hasClass('default_category')) {
			category_name = $(this).find('label').data('category-name');
			category_time_stamp = $(this).find('label').data('category-name');
		} else {
			category_name = $(this).find('label').data('category-name');
			category_time_stamp = $(this).find('label').data('category-timestamp');
		}
	});

	$("#show-share-video-modal").click(function() {
		$("#share-with-other-modal").modal();
	})
	
	$('#share-users').tokenfield({
		autocomplete: {
			source: users
		},
		showAutocompleteOnFocus: true
	})

	$("#start-share").click(function(event) {
		var data = {
			share_with: $('#share-users').val(),
			video: $(this).data('timestamp')
		};

		$.ajax({
			url: base_url + 'home/share',
			type: "POST",
			data: data,
			success: function(result){
				console.log(result);
				if (result.status == "ok") {
					$("#share-with-other-modal").modal('hide');
					$('#share-users').val("");
				}
			}
		})
	})

	$("#add-new-category").click(function() {
		$("#new-category-name").val('');
		$('#add-new-category-modal').modal();
	})

	$("#save-new-category").click(function(){
		alert("test");
		if ($("#new-category-name").val() == "") {
			alert("Please input category name");
		} else {
			var data = {
				video: $(this).data('video'),
				category_name: $("#new-category-name").val()
			};
			$.ajax({
				url: base_url + "home/save_category",
				type: 'POST',
				data: data,
				success: function( result ) {
					console.log(result);
					
					if (result.status == 'fail') {
						alert("Category Name already taken. Please try again with another name.");
					} else {
						var html = '<li>' +
										'<label class="tree-toggle nav-header glyphicon-icon-rpad">' +
											result.name + '<span class="caret"></span>' +
										'</label>' +
										'<ul class="nav nav-list tree bullets" style="display: none;">' +
										'</ul>' +
									'</li>';
						$("ul.category-clips").append(html);
					}
				}
			})
		}
	})

	var start_video_time = 0;
	var video_element = document.getElementById("play_video");
	var end_video_time = video_element.duration;

	$("#play_all_button").click(function() {
		start_video_time = 0;
		end_video_time = video_element.duration;
		video_element.play();
	})
	
	$('.clip-data').click(function() {
		start_video_time = parseInt($(this).data('start'));
		end_video_time = parseInt($(this).data('end'));
		video_element.currentTime = start_video_time;
		video_element.play();
	});

	setInterval(function(){
		if (video_element.currentTime > end_video_time) {
			video_element.currentTime = start_video_time;
		}
	},300)

	var clip_start = 0;
	var clip_end = 0;
	$("#clip_record_start").click(function() {
		if (category_name == "") {
			alert("Please select category name...");
		} else {
			clip_start = video_element.currentTime;
		}
	})

	$("#clip_record_end").click(function() {
		if (category_name == "") {
			alert("Please select category name...");
		} else {
			clip_end = video_element.currentTime;
		}

		$("#category_name_for_clip").text(category_name);
		$("#clip-start-position").text(clip_start);
		$("#clip-end-position").text(clip_end);
		$("#save-clip-modal").modal();
	})
	$("#save-new-clip").click(function() {
		var data = {
			start: clip_start,
			end: clip_end,
			category: category_time_stamp,
			email: email,
			video: $(this).data('video')
		}

		$.ajax({
			url: base_url + "home/save_clip",
			data: data,
			method: 'POST',
			dataType: 'json',
			success: function(response) {
				console.log(response);
				if (response.status == 'ok') {
					window.history.go();
				}
			}
		})
	})

	
})