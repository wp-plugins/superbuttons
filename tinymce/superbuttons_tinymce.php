<?php
require_once('../../../../wp-blog-header.php');
global $wpdb;
$nofollow = get_option('pl_nofollow');
$shortcode = get_option('pl_shortcode');
$bFirstAndSelect = 0;
if(get_option('pl_select') == 'on' && $_REQUEST['validate'] == 1 && strlen($_REQUEST['tri'])>0)
	$bFirstAndSelect = 1;
?><html>
<head>
	<script type='text/javascript' src='js/jquery.js'></script>
	<script type="text/javascript" src="<?php bloginfo('wpurl'); ?>/wp-includes/js/tinymce/tiny_mce_popup.js"></script>
	<script type="text/javascript" src="<?php bloginfo('wpurl'); ?>/wp-includes/js/tinymce/utils/mctabs.js"></script>
	<script type="text/javascript" src="<?php bloginfo('wpurl'); ?>/wp-includes/js/tinymce/utils/form_utils.js"></script>
	<script type="text/javascript" src="js/superbuttons_tinymce.js"></script>
	<link rel='stylesheet' type='text/css' href='css/superbuttons_tinymce.css' />
</head>
<body>
	<form id="superbutton_form" action="#">
		<div class="tabs">
			<ul>
				<li id="lier_tab" class="current"><span>{#superbuttons.superbutton_title}</span></li>
			</ul>
		</div>

		<div class="panel_wrapper">
			<div id="general_panel" class="panel current">
				<table border="0" cellpadding="4" cellspacing="0">
					<tr>
						<td class="nowrap"><label for="text">{#superbuttons.text}</label></td>
						<td><input id="text" name="text" type="text" class="mceFocus" value="Button Text" style="width: 200px" onfocus="try{this.select();}catch(e){}" /></td>
					</tr>
					<tr>
						<td class="nowrap"><label for="link">{#superbuttons.button_link}</label></td>
						<td><input id="link" name="link" type="text" class="mceFocus" value="http://" style="width: 200px" onfocus="try{this.select();}catch(e){}" /></td>
					</tr>
					<tr>
						<td class="nowrap"><label for="title">{#superbuttons.title}</label></td>
						<td><input id="title" name="title" type="text" class="mceFocus" value="" style="width: 200px" onfocus="try{this.select();}catch(e){}" /></td>
					</tr>
					<tr>
						<td class="nowrap"><label for="image">{#superbuttons.image_url}</label></td>
						<td><input id="image" name="image" type="text" class="mceFocus" value="" style="width: 200px" onfocus="try{this.select();}catch(e){}" /></td>
					</tr>
					<tr>
						<td class="nowrap"><label for="cssclass">{#superbuttons.style}</label></td>
						<td>
							<select name="cssclass" id="cssclass">
								<?php
									// Get a dynamic list of available CSS styles
									$cssfiles = array( 'superbuttons.css', 'custom_styles.css');
									foreach ($cssfiles as $cssfile) {
										$cssfile = WP_PLUGIN_DIR . '/superbuttons/' . $cssfile;
										$cssfile = file_get_contents($cssfile);
										$cssp = new cssparser;
										$cssp -> ParseStr($cssfile);
										foreach ($cssp->css as $style => $stuff) {
											if ( strripos( $style, 'sprbtn_' ) ) {
												if ( !strrpos( $style, ':') && !strrpos( $style, ' ') ) { 
													$stylename = ucfirst( substr( $style, 8 ) ); // Extract a nicer name and capitalize it!
													$style = substr ( $style, 1 ); // get rid of the dot (.)
													echo '<option value="'.$style.'">'.$stylename.$pos.'</option>';
												}
											}
										}
									}
								?>
							</select>
						</td>
					</tr>
					<tr>
						<td class="nowrap"><label for="target">{#superbuttons.target}</label></td>
						<td>
							<select id="target" name="target" style="width: 200px;" onfocus="try{this.select();}catch(e){}" class="mceFocus">
								<option value="">{#superbuttons.open_in_the_same_window}</option>
								<option value="_blank">{#superbuttons.open_in_a_new_window}</option>
							</select>
						</td>
					</tr>
					<tr>
						<td class="nowrap"><label for="target">{#superbuttons.rel}</label></td>
						<td><input id="rel" name="rel" type="text" class="mceFocus" value="" style="width: 200px" onfocus="try{this.select();}catch(e){}" /></td>
					</tr>
				</table>
			</div>
		</div>

		<div class="mceActionPanel"> 
			<div style="float: left"> 
				<input type="button" id="cancel" name="cancel" value="{#cancel}" onclick="tinyMCEPopup.close();" /> 
			</div> 
	 
			<div style="float: right"> 
				<input type="button" id="insert" name="insert" value="{#insert}" onclick="return insertSuperButton();" /> 
			</div> 
		</div>
	</form>
</body>
</html>