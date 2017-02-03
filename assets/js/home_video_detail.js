$(function() {
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
		if ($("#share-users").val() != "") {
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
		} else {
			alert("Please input email that want to share with.");
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
			video_element.stop()
		}
	},300)
	
	var clipboard = new Clipboard('.copybtn');
	clipboard.on('success', function(e) {
		console.log(e);
		$(".copybtn").attr('title', 'Copied')
		        .tooltip('fixTitle')
		        .tooltip('show')
		        .attr('title', "Copy to Clipboard")
		        .tooltip('fixTitle');
	});
	
	clipboard.on('error', function(e) {
		console.log(e);
	});
})