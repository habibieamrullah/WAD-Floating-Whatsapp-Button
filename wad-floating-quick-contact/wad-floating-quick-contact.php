<?php

/*
 * Plugin Name: WAD Floating Quick Contact Button
 * Description: Showing a floating Green Chat button on your website.
 * Version: 1.0.0
 * Author: Habibie
 * Author URI: https://webappdev.my.id/
 * License: GPL2
 * Requires at least: 2.9
 * Requires PHP: 5.2
 * Text Domain: wad-floating-quick-contact
 * Domain Path: /languages
 */
 
 
//Plugin settings page
function wad_fwb_settings_page(){
	
	if ( isset( $_GET['settings-updated'] ) ) {
        // add settings saved message with the class of "updated"
        add_settings_error( 'wporg_messages', 'wporg_message', __( 'Settings Saved', 'wporg' ), 'updated' );
    }
	
	// show error/update messages
    settings_errors( 'wporg_messages' );
	
	?>
	<div class="wrap">
		<h1>WAD Floating Green "WhatsApp" Chat Button Settings</h1>	
		<form method="post" action="options.php">
			<?php
			settings_fields("wad_fwb_generalsettings");
			do_settings_sections("wad_fwb_settingsandoptions");
			
			submit_button('Save');
			?>
		</form>	
	</div>
	<?php
}

//Setting fields
function wad_fwb_settingsfields(){
	
	//General Settings
	add_settings_section('wad_fwb_generalsettings', null, null, 'wad_fwb_settingsandoptions');	
	
	add_settings_field('wad_fwb_field_generalsettings', 'Phone Number and Appearance:', 'wad_settingsform', 'wad_fwb_settingsandoptions', 'wad_fwb_generalsettings');
	$args = array(
		'default' => NULL,
	);
	register_setting('wad_fwb_generalsettings', 'wad_fwb_phonenumber', $args);
	$args = array(
		'default' => 1,
	);
	register_setting('wad_fwb_generalsettings', 'wad_fwb_buttonposition', $args);
	$args = array(
		'default' => 1,
	);
	register_setting('wad_fwb_generalsettings', 'wad_fwb_buttonimage', $args);
	
	//Message Setting
	add_settings_section('wad_fwb_messagesettings', null, null, 'wad_fwb_settingsandoptions');
	add_settings_field('wad_fwb_field_whatsappmessage', 'Chat Message:', 'wad_messageform', 'wad_fwb_settingsandoptions', 'wad_fwb_messagesettings');
	$args = array(
		'default' => 'Hello, I want to ask you a question regarding to your product...',
	);
	register_setting('wad_fwb_generalsettings', 'wad_fwb_wamessage', $args);
	
}
add_action('admin_init', 'wad_fwb_settingsfields');


function wad_settingsform(){
	?>
	<p><b>Phone Number (Include country code like 6288877766554):</b></p>
	<input type="number" name="wad_fwb_phonenumber" id="wad_fwb_phonenumber" value="<?php echo get_option('wad_fwb_phonenumber'); ?>">
	<br>
	<br>
	
	<p><b>Button Position:</b></p>
	<select name ="wad_fwb_buttonposition">
		<?php
		if(get_option('wad_fwb_buttonposition') == 1){
			?>
			<option value=0>Bottom Left</option>
			<option value=1 selected>Bottom Right</option>
			<?php
		}else{
			?>
			<option value=0 selected>Bottom Left</option>
			<option value=1>Bottom Right</option>
			<?php
		}
		?>
	</select>
	<br>
	<br>
	
	<p><b>Choose button style:</b></p>
	<?php
	for($i = 1; $i <= 10; $i++){
		?>
		<img class="wad_fwb_butimage" id="wad_fwb_butimage<?php echo $i ?>" src="<?php echo plugin_dir_url(__FILE__) ?>/imgs/wa<?php echo $i ?>.png" style="width: 64px; display: inline-block; margin: 10px; cursor: pointer; border: 2px solid white;" onclick="wad_fwb_setbutimage(<?php echo $i ?>)">
		<?php
	}
	
	?>
	<input name="wad_fwb_buttonimage" id="wad_fwb_buttonimage" value="<?php echo get_option('wad_fwb_buttonimage') ?>" style="display: none;">
	<script>
		function wad_fwb_setbutimage(num){
			jQuery("#wad_fwb_buttonimage").val(num);
			wad_fwb_highlightbutimage(num);
		}
		function wad_fwb_highlightbutimage(num){
			jQuery(".wad_fwb_butimage").css({ "border" : "2px solid white"});
			jQuery("#wad_fwb_butimage" + num).css({ "border" : "2px solid green"});
		}
		wad_fwb_highlightbutimage(<?php echo get_option('wad_fwb_buttonimage') ?>);
	</script>
	<?php
}

function wad_messageform(){
	?>
	<p><b>Default message that your user will send to you:</b></p>
	<input name="wad_fwb_wamessage" style="width: 100%; box-sizing: border-box;" value="<?php echo get_option('wad_fwb_wamessage') ?>">
	<?php
}

//Showing the plugin settings page on Admin Dashboard
function wad_fwb_settings(){
	add_menu_page('WAD Floating Chat Button Settings', 'WAD Button', 'manage_options', 'wad-fwb-settings', 'wad_fwb_settings_page', 'dashicons-admin-tools', 99);
}
add_action('admin_menu', 'wad_fwb_settings');

function somecallback(){
	echo "TADAAAA";
}

//HTML for WA Button
function wad_fwb_footer() {
	$wapos = "left: 0; ";
	if(get_option('wad_fwb_buttonposition') == 1){
		$wapos = "right: 0; ";
	}
	
	?>
	<div style="position: fixed; <?php echo $wapos ?>bottom: 0; margin: 50px; z-index: 10; cursor: pointer;" onclick="wad_fwb_wame()">
		<img src="<?php echo plugin_dir_url(__FILE__) ?>imgs/wa<?php echo get_option('wad_fwb_buttonimage') ?>.png" style="width: 64px;">
	</div>
	<script>
		function wad_fwb_wame(){
			var wamsg = encodeURI("<?php echo get_option('wad_fwb_wamessage') ?>");
			location.href = "https://wa.me/<?php echo get_option('wad_fwb_phonenumber') ?>?text="+wamsg;
		}
	</script>
	<?php
	
}

add_action('wp_footer', 'wad_fwb_footer');

?>