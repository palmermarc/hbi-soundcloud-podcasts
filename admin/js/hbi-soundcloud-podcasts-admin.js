(function() {
	tinymce.create('tinymce.plugins.podcasts', {
		init : function(ed, url){
			ed.addButton('podcasts', {
				title : 'Insert Podcasts',
				onclick : function() {
					ed.windowManager.open({
						title: 'Which podcasts do you want to display?',
						body: [
							{
								type: 'listbox',
								label: 'Select a Show :',
								name: 'podcast_show',
								'values': hbi_soundcloud_podcasts_shows
							},
							{
								type: 'textbox', 
								name: 'podcast_tag', 
								label: 'Tag Slug'
							},
							{
								type: 'listbox',
								label: 'Select a Segment: ',
								name: 'podcast_segment',
								'values' : hbi_soundcloud_podcasts_segments
							}
						],
						onsubmit: function(e) {
							var output = '[podcasts';
							
							if( '' !== e.data.podcast_show ) {
								output += ' podcast_show="' + e.data.podcast_show + '"';
							}
							if( '' !== e.data.podcast_tag ) {
								output += ' podcast_tag="' + e.data.podcast_tag + '"';
							}
							if( '' !== e.data.podcast_segment ) {
								output += ' podcast_segment="' + e.data.podcast_segment + '"';
							}
							output += ']';
							
							ed.insertContent(output);
						}
					});
				},
                class : 'podcasts',
            });
        }
    });
 
    tinymce.PluginManager.add('podcasts', tinymce.plugins.podcasts);
})();