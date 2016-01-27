(function ($) {
	"use strict";
	$(function () {
		
		$('.podcast').click(function(event) {
			
			event.preventDefault();
			event.stopPropagation();
			
			// Destroy the HTML inside of the placeholder
			var placeholder = $(this).parent().parent().siblings('.podcast_placeholder');
			placeholder.empty();
			
			var podcast_frame = $(this).data('soundcloud_embed');
			podcast_frame = podcast_frame.replace('">', '&amp;auto_play=true">' );
			
			var podcast_title = $(this).children('.podcast_title').text();
			var podcast_segment = $(this).children('.podcast_segment').text();
			
			// Remove all instances of the active class
			$(this).siblings().removeClass('active');
			
			// Add the active class to this instance
			$(this).addClass('active');

			// Copy the html from the hidden div into the placeholder
			placeholder.html(podcast_frame);
			
			__gaTracker('send', 'pageview', $(this).attr('href'));
			__gaTracker('send', 'event', 'Audio Play', podcast_title, document.URL);
			__gaTracker('send', 'event', 'Audio Segment', podcast_segment, document.URL);
		});
		
		// Switch between the tabs when an audio category is selected
		$('.podcast_tab').bind('touchstart click', function() {
			$('.load_more_audio').attr('href', '/podcasts/?podcast_tab=' + $(this).data('segment'));
			var tab = '#' + $(this).data('segment');
			$('#segments,#hourly').hide();
			$(tab).show();
		});
		
	});
}(jQuery));