<?php
/**
 * Wordpress Plugin: Auto Keyword 1.2.1 
 * Copyright (2) 2009 Ver Pangonilo
 * File Information:
 *	- Auto Keyword Options Page
 *  - wp-content/plugins/autokeyword/autokeyword-options.php
 */

/**
 * Initialize variables
 */
$base_name = plugin_basename('autokeyword/autokeyword-options.php');
$base_page = 'admin.php?page='.$base_name;
$autokeyword_settings = array('autokeyword_options');
/**
 * Form Processing
 * Update Options
 */
if(!empty($_POST['Submit'])) {
	$autokeyword_options = array();
	/**
	 * Users could add keyword into the plugin
	 * using Auto Keyword options.
	 */
	$autokeyword_options['user_added_keywords'] = stripslashes(trim($_POST['autokeyword_user_added_keywords']));	

	/**
	 * Users could exclude common words into the plugin
	 * using Auto Keyword options.
	 */
	$autokeyword_options['user_excluded_common_words'] = stripslashes(trim($_POST['autokeyword_user_excluded_common_words']));	
	/**
	 * Option to select automatic or manual
	 * place meta keyword on the website
	 * header.
	 */
	$autokeyword_options['include_meta_keywords'] = intval(trim($_POST['autokeyword_include_meta_keywords'])); 
	/**
	 * character encoding
	 */
	$autokeyword_options['character_encoding'] = stripslashes(trim($_POST['autokeyword_character_encoding']));
	/**
	 * Single Words
	 */	
	$autokeyword_options['min_word_length'] = intval(trim($_POST['autokeyword_min_word_length']));
	$autokeyword_options['min_word_occur'] = intval(trim($_POST['autokeyword_min_word_occur']));
	$autokeyword_options['show_1_words'] = intval(trim($_POST['autokeyword_show_1_words']));
	/**
	 * 2 Words Phrase
	 */	
	$autokeyword_options['min_2words_length'] = intval(trim($_POST['autokeyword_min_2words_length']));
	$autokeyword_options['min_2words_phrase_length'] = intval(trim($_POST['autokeyword_min_2words_phrase_length']));
	$autokeyword_options['min_2words_phrase_occur'] = intval(trim($_POST['autokeyword_min_2words_phrase_occur']));
	$autokeyword_options['show_2_words'] = intval(trim($_POST['autokeyword_show_2_words']));
	/**
	 * 3 Words Phrase
	 */	
	$autokeyword_options['min_3words_length'] = intval(trim($_POST['autokeyword_min_3words_length']));
	$autokeyword_options['min_3words_phrase_length'] = intval(trim($_POST['autokeyword_min_3words_phrase_length']));
	$autokeyword_options['min_3words_phrase_occur'] = intval(trim($_POST['autokeyword_min_3words_phrase_occur']));
	$autokeyword_options['show_3_words'] = intval(trim($_POST['autokeyword_show_3_words']));
	/**
	 * Update the database with the 
	 * new values
	 */	
	$update_autokeyword_queries = array();
	$update_autokeyword_text = array();
	$update_autokeyword_queries[] = update_option('autokeyword_options', $autokeyword_options);
	$update_autokeyword_text[] = __('Auto Keyword Options', 'autokeyword');

	$i=0;
	$text = '';
	foreach($update_autokeyword_queries as $update_autokeyword_query) {
		if($update_autokeyword_query) {
			$text .= '<font color="green">' . $update_autokeyword_text[$i] . ' ' . __('Updated', 'autokeyword').'</font><br />';
		}
		$i++;
	}
	if(empty($text)) {
		$text = '<font color="red">'.__('No Auto Keyword Option Updated', 'autokeyword').'</font>';
	}
}
/**
 * Load the saved Auto Keyword options
 */
$autokeyword_options = get_option('autokeyword_options');

/**
 * Check if $text is not empty 
 */
if(!empty($text)) { echo '<div id="message" class="updated fade"><p>'.$text.'</p></div>'; } 
?>
<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>"> 
<div class="wrap"> 
	<h2><?php _e('Auto Keyword Options', 'autokeyword'); ?></h2>
	<p><?php _e('The <strong>Auto Keyword</strong> plugin default settings can be changed here to suit your particular requirement!', 'autokeyword'); ?> </p>
	<p class="submit">
		<input type="submit" name="Submit" class="button" value="<?php _e('Save Changes', 'autokeyword'); ?>" />
	</p>
	<table class="form-table">
		<!-- User Added Keywords -->
		<tr><td colspan="2">
		<h3><?php _e('User Added Keywords', 'autokeyword'); ?></h3>
		<p><em><?php _e('Add keywords to be included in the keyword list', 'autokeyword'); ?></em></p>
		</td></tr>
		<tr>
			<th scope="row" valign="top"><?php _e('Added Keywords', 'autokeyword'); ?></th>
			<td>
				<textarea name="autokeyword_user_added_keywords" cols="40" rows="4" ><?php echo stripslashes(trim($autokeyword_options['user_added_keywords'])); ?></textarea>
				<p><em><?php _e('Please separate words with a comma (,)', 'autokeyword'); ?></em></p>				
			</td> 
		</tr>
		<!-- Excluded common words -->
		<tr><td colspan="2">
		<h3><?php _e('Excluded Common Words', 'autokeyword'); ?></h3>
		<p><em><?php _e('Enter keywords to be excluded in the keyword list', 'autokeyword'); ?></em></p>
		</td></tr>
		<tr>
			<th scope="row" valign="top"><?php _e('Excluded Words', 'autokeyword'); ?></th>
			<td>
				<textarea name="autokeyword_user_excluded_common_words" cols="40" rows="4" ><?php echo stripslashes(trim($autokeyword_options['user_excluded_common_words'])); ?></textarea>
				<p><em><?php _e('Please separate words with a comma (,)', 'autokeyword'); ?></em></p>				
			</td> 
		</tr>
		<!-- Include Meta Keywords -->
		<tr><td colspan="2">
		<h3><?php _e('Meta Keywords Placement', 'autokeyword'); ?></h3>
		<p><em><?php _e('Placement of meta keywords will be Automatic or Manual', 'autokeyword'); ?></em></p>
		</td></tr>
		<tr>
			<th scope="row" valign="top"><?php _e('Meta Keywords Setting', 'autokeyword'); ?></th>
			<td>
				<select name="autokeyword_include_meta_keywords" size="1">
					<option value="0"<?php selected('0', $autokeyword_options['include_meta_keywords']); ?>><?php _e('Automatic', 'autokeyword'); ?></option>
					<option value="1"<?php selected('1', $autokeyword_options['include_meta_keywords']); ?>><?php _e('Manual', 'autokeyword'); ?></option>
				</select>
				<p><em><?php _e('If set to Manual, do not forget to add <strong>&lt;?php autokeyword(); ?&gt;</strong><br />into your template <strong>header.php</strong>', 'autokeyword'); ?></em></p>				
			</td> 
		</tr>

		<!-- Character Encoding -->
		<tr><td colspan="2">
		<h3><?php _e('Character Encoding', 'autokeyword'); ?></h3>
		<p><em><?php _e('Change the character encoding here', 'autokeyword'); ?></em></p>
		</td></tr>
		<tr>
			<th scope="row" valign="top"><?php _e('Character Encoding', 'autokeyword'); ?></th>
			<td>
				<select name="autokeyword_character_encoding" size="1">
					<option value="UTF-8"<?php selected('UTF-8', $autokeyword_options['character_encoding']); ?>>UTF-8</option>
					<option value="iso-8859-1"<?php selected('iso-8859-1', $autokeyword_options['character_encoding']); ?>>ISO-8859-1</option>
				</select>
			</td> 
		</tr>
	<!--
		Minimum length for Single Words
	-->
		<tr><td colspan="2">
		<h3><?php _e('Single Words', 'autokeyword'); ?></h3>
		<p><em><?php _e('Change the settings for single words here', 'autokeyword'); ?></em></p>
		</td></tr>
		<tr>
			<th scope="row" valign="top"><?php _e('Word Length', 'autokeyword'); ?></th>
			<td>
				<input type="text" name="autokeyword_min_word_length" value="<?php echo intval($autokeyword_options['min_word_length']); ?>" size="4" />
			</td>
		</tr>
		<tr>
			<th scope="row" valign="top"><?php _e('Occurrence', 'autokeyword'); ?></th>
			<td>
				<input type="text" name="autokeyword_min_word_occur" value="<?php echo intval($autokeyword_options['min_word_occur']); ?>" size="4" />
			</td>
		</tr>
		<tr>
			<th scope="row" valign="top"><?php _e('Show Single Word', 'autokeyword'); ?></th>
			<td>
				<select name="autokeyword_show_1_words" size="1">
					<option value="1"<?php selected('1', $autokeyword_options['show_1_words']); ?>><?php _e('Yes', 'autokeyword'); ?></option>
					<option value="0"<?php selected('0', $autokeyword_options['show_1_words']); ?>><?php _e('No', 'autokeyword'); ?></option>
				</select>
			</td> 
		</tr>
		<!--
		Minimum length for 2 Words
		-->
		<tr><td colspan="2">
		<h3><?php _e('2 Words Phrases', 'autokeyword'); ?></h3>
		<p><em><?php _e('Change the settings for 2 words phrases here', 'autokeyword'); ?></em></p>		
		</td></tr>
		<tr>
			<th scope="row" valign="top"><?php _e('Word Length', 'autokeyword'); ?></th>
			<td>
				<input type="text" name="autokeyword_min_2words_length" value="<?php echo intval($autokeyword_options['min_2words_length']); ?>" size="4" />
			</td>
		</tr>
		<tr>
			<th scope="row" valign="top"><?php _e('Length of 2 Words Phrases', 'autokeyword'); ?></th>
			<td>
				<input type="text" name="autokeyword_min_2words_phrase_length" value="<?php echo intval($autokeyword_options['min_2words_phrase_length']); ?>" size="4" />
			</td>
		</tr>
		<tr>
			<th scope="row" valign="top"><?php _e('Occurrence', 'autokeyword'); ?></th>
			<td>
				<input type="text" name="autokeyword_min_2words_phrase_occur" value="<?php echo intval($autokeyword_options['min_2words_phrase_occur']); ?>" size="4" />
			</td>
		</tr>
		<tr>
			<th scope="row" valign="top"><?php _e('Show 2 Word Phrase', 'autokeyword'); ?></th>
			<td>
				<select name="autokeyword_show_2_words" size="1">
					<option value="1"<?php selected('1', $autokeyword_options['show_2_words']); ?>><?php _e('Yes', 'autokeyword'); ?></option>
					<option value="0"<?php selected('0', $autokeyword_options['show_2_words']); ?>><?php _e('No', 'autokeyword'); ?></option>
				</select>
			</td> 
		</tr>

		<!--
		Minimum length for 3 Words phrases
		-->
		<tr><td colspan="2">
		<h3><?php _e('3 Words Phrases', 'autokeyword'); ?></h3>
		<p><em><?php _e('Change the settings for 3 words phrases here', 'autokeyword'); ?></em></p>		
		</td></tr>
		<tr>
			<th scope="row" valign="top"><?php _e('Word Length', 'autokeyword'); ?></th>
			<td>
				<input type="text" name="autokeyword_min_3words_length" value="<?php echo intval($autokeyword_options['min_3words_length']); ?>" size="4" />
			</td>
		</tr>

		<tr>
			<th scope="row" valign="top"><?php _e('Length of 3 Words Phrases', 'autokeyword'); ?></th>
			<td>
				<input type="text" name="autokeyword_min_3words_phrase_length" value="<?php echo intval($autokeyword_options['min_3words_phrase_length']); ?>" size="4" />
			</td>
		</tr>
		<tr>
			<th scope="row" valign="top"><?php _e('Occurrence', 'autokeyword'); ?></th>
			<td>
				<input type="text" name="autokeyword_min_3words_phrase_occur" value="<?php echo intval($autokeyword_options['min_3words_phrase_occur']); ?>" size="4" />
			</td>
		</tr>
		<tr>
			<th scope="row" valign="top"><?php _e('Show 3 Word Phrase', 'autokeyword'); ?></th>
			<td>
				<select name="autokeyword_show_3_words" size="1">
					<option value="1"<?php selected('1', $autokeyword_options['show_3_words']); ?>><?php _e('Yes', 'autokeyword'); ?></option>
					<option value="0"<?php selected('0', $autokeyword_options['show_3_words']); ?>><?php _e('No', 'autokeyword'); ?></option>
				</select>
			</td> 
		</tr>
	</table>
	<p class="submit">
		<input type="submit" name="Submit" class="button" value="<?php _e('Save Changes', 'autokeyword'); ?>" />
	</p>
</div>
</form> 
<p>&nbsp;</p>

