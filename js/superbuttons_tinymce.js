tinyMCEPopup.requireLangPack();

function insertSuperButton() {
	var winder = window.top;
	var text = jQuery('#text').val();
	var link = jQuery('#link').val();
	var title = jQuery('#title').val();
	var image = jQuery('#image').val();
	var cssclass = jQuery('#cssclass').val();
	var target = jQuery('#target').val();
	var rel = jQuery('#rel').val();
	var button = '[superbutton link="' + link + '" title="' + title + '" image="' + image + '" class="' + cssclass + '" target="' + target + '" rel="' + rel + '"]' + text + '[/superbutton]';
	
	tinyMCEPopup.execCommand('mceInsertContent', false, button);

	// Refocus in window
	if (tinyMCEPopup.isWindow)
		window.focus();

	tinyMCEPopup.editor.focus();
	tinyMCEPopup.close();
}