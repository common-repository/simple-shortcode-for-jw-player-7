
tinymce.PluginManager.add('ssfjwplayer', function(editor) {
	function showDialog() {
		var html, win;

		html = '<table class="form-table"><tr><th scope="row"><label for="url">From medialibrary</label></th><td><button class="ssfjwplayer-open-medialibrary button button-primary button-large">Open</button></td></tr><tr><th scope="row"><label for="url">URL</label></th><td><input id="ssfjwplayer-url" type="text" class="regular-text" name="url" value="" /></td></tr><tr><th scope="row"><label for="controls">Controls</label></th><td><input id="ssfjwplayer-controls" type="checkbox" name="controls" checked="checked" /></td></tr><tr><th scope="row"><label for="autostart">Autostart</label></th><td><input id="ssfjwplayer-autostart" type="checkbox" name="autostart" /></td></tr></table>';
		
		var panel = {
			type: 'container',
			html: html,
		};

		win = editor.windowManager.open({
			title: "JWPlayer invoegen",
			spacing: 10,
			padding: 10,
			items: [
				panel,
			],
			buttons: [
				{text: "OK", onclick: function() {
					var controls = '" controls="',
						autostart = '" autostart="';
					
					controls += document.getElementById('ssfjwplayer-controls').checked ? 'true' : 'false' ;
					autostart += document.getElementById('ssfjwplayer-autostart').checked ? 'true' : 'false' ;
					
					editor.execCommand('mceInsertContent', false, '[jwplayer file="' + document.getElementById('ssfjwplayer-url').value + controls + autostart + '"]');
					win.close();
				}}
			]
		});

		jQuery( '.ssfjwplayer-open-medialibrary' ).on( 'click', function( e ) {
			var frame, image;

			frame = wp.media({
				title: 'Medialibrary',
				multiple: false,
			});

			image = frame.open().on( 'select', function( e ) {
				var sImages = image.state().get('selection').toArray();
				editor.execCommand('mceInsertContent', false, '[jwplayer mediaid="' + sImages[0].attributes.id + '"]');
				win.close();
			});
		});
	}

	editor.addCommand('InsertSSFJWPlayer', showDialog);
	editor.addButton('ssfjwplayer', {
		icon: 'media',
		tooltip: 'JWPlayer',
		cmd: 'InsertSSFJWPlayer'
	});

	editor.addMenuItem('ssfjwplayer', {
		icon: 'media',
		text: 'JWPlayer',
		cmd: 'InsertSSFJWPlayer',
		context: 'insert'
	});
});
