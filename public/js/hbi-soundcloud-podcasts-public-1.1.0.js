(function ( $ ) {
	"use strict";
	$(function () {
		
		$('.podcast').click(function() {
			// Destroy the HTML inside of the placeholder
			var placeholder = $(this).parent().parent().siblings('.podcast_placeholder');
			placeholder.empty();
			
			// Remove all instances of the active class
			$(this).siblings().removeClass('active');
			
			// Add the active class to this instance
			$(this).addClass('active');

			// Copy the html from the hidden div into the placeholder
			placeholder.html( $(this).find('.podcast_embed').html() );
			placeholder.find('iframe').attr( 'src', placeholder.find('iframe').attr( 'src' ) + "&amp;auto_play=true" )
			
			// Push a page view for the audio
			_gaq.push( ['_trackPageview', $(this).data('page_link')] );
			
			// Push an event to Google Analytics
			_gaq.push( ['_trackEvent', 'Audio', 'Played From', document.URL ] );
			
		});
		
		// Switch between the tabs when an audio category is selected
		$('.podcast_tab').bind( 'touchstart click', function() {
			$('.load_more_audio').attr( 'href', '/podcasts/?podcast_tab=' + $(this).data('segment') );
			var tab = '#' + $(this).data('segment');
			$('#segments,#hourly').hide();
			$( tab ).show();
		});
		
	});
}(jQuery));