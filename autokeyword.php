<?php
/*
Plugin Name: Auto Keyword
Plugin URI: http://sp62.com/
Description: Add keywords into your Wordpress blog automatically. Based on the award winning PHP Class <strong>Automatic Keyword Generator</strong>.
Version: 1.2.2
Author: Ver Pangonilo
Author URI: http://s.pangonilo.com
*/

/**  
	Copyright 2009  Ver Pangonilo  (email : smpangonilo@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

/**
 * define the Auto Keyword version
 */
define('AUTOKEYWORD_VERSION', '1.2.2');

/**
 * Use WordPress 2.6 Constants
 */
if (!defined('WP_CONTENT_DIR')) {
	define( 'WP_CONTENT_DIR', ABSPATH.'content');
}

if (!defined('WP_PLUGIN_DIR')) {
	define('WP_PLUGIN_DIR', WP_CONTENT_DIR.'/plugins');
}
// this plugin filename
$plugin_file = basename( dirname(__FILE__) ) . '.php';

/**
 * Include the Automatic Keyword Generator PHP Class
 */
require_once(WP_PLUGIN_DIR . '/autokeyword/class.autokeyword.php');

/**
 * Function: Auto Keyword Option Menu
 */
add_action('admin_menu', 'autokeyword_menu');

function autokeyword_menu() {
	if (function_exists('add_options_page')) {
		add_options_page(__('Auto Keyword', 'autokeyword'), __('Auto Keyword', 'autokeyword'), 'manage_options', 'autokeyword/autokeyword-options.php') ;
	}
}
/**
 * Get the autokeyword_options 
 * from the database 
 */
$autokeyword_options = get_option('autokeyword_options');

// Automatic or Manual inclusion to header
// happens here
if($autokeyword_options['include_meta_keywords'] !== 1):
	add_action('wp_head', 'autokeyword');
endif;	
/**
 *  Function: Auto Keyword
 */
function autokeyword() {
	global $autokeyword_options;
	echo "\n".'<!-- Generated By Auto Keyword ' . AUTOKEYWORD_VERSION . ' starts-->'."\n";
	echo '<meta name="keywords" content="';
	/**
	 * Read the all posts and set the contents
	 * to $autokeyword_options['content'] to be used
	 * the Automatic Keyword Generator Class
	 */
	if (have_posts()) : 
		while (have_posts()) : the_post();
			$autokeyword_options['content'] .= get_content();
		endwhile;
	endif;

	/**
	 * Create a new instance of the autokeyword object
	 */
	$keyword = new autokeyword($autokeyword_options, $autokeyword_options['character_encoding']);
	/**
	 * Check if single keywords are allowed
	 */
	if($autokeyword_options['show_1_words'] == 1)
		echo $keyword->parse_words();
	/**
	 * Check if 2 word phrase keywords are allowed
	 */
	if($autokeyword_options['show_2_words'] == 1)
		echo $keyword->parse_2words();
	/**
	 * Check if 3 word phrase keywords are allowed
	 */
	if($autokeyword_options['show_3_words'] == 1)
		echo $keyword->parse_3words();
	
	echo $autokeyword_options['user_added_keywords'] . '" />'."\n";
	echo '<!-- Generated By Auto Keyword ' . AUTOKEYWORD_VERSION . ' ends -->'."\n";
}

/**
 * Add plugin actions - Settings
 */
add_filter( 'plugin_action_links', 'autokeyword_plugin_actions', 10, 2 );

function autokeyword_plugin_actions( $links, $plugin_file) {
	 if( $plugin_file == 'autokeyword/autokeyword.php' && function_exists( "admin_url" ) ) {
		$settings_link = '<a href="' . admin_url( 'options-general.php?page=autokeyword/autokeyword-options.php' ) . '">' . __('Settings') . '</a>';
		array_unshift( $links, $settings_link ); // before other links
	} 
	return $links;
}

/**
 * Function: Auto Keyword Options
 */
add_action('activate_autokeyword/autokeyword.php', 'autokeyword_init');

function autokeyword_init() {
	// Add Options
	$autokeyword_options = array();
	$autokeyword_options['user_excluded_common_words'] = '';  //user excluded common words	
	$autokeyword_options['user_added_keywords'] = 'autokeyword';  //user entered keywords
	$autokeyword_options['include_meta_keywords'] = 0;  //automatic = 0 or manual = 1
	$autokeyword_options['character_encoding'] = 'UTF-8';  //character encoding
	$autokeyword_options['min_word_length'] = 5;  //minimum length of single words
	$autokeyword_options['min_word_occur'] = 2;  //minimum occur of single words
	$autokeyword_options['show_1_words'] = 1;  //show single words
	$autokeyword_options['min_2words_length'] = 3;  //minimum length of words for 2 word phrases
	$autokeyword_options['min_2words_phrase_length'] = 10; //minimum length of 2 word phrases
	$autokeyword_options['min_2words_phrase_occur'] = 2; //minimum occur of 2 words phrase
	$autokeyword_options['show_2_words'] = 0;  //show 2 words phrase - disabled by default
	$autokeyword_options['min_3words_length'] = 2;  //minimum length of words for 3 word phrases
	$autokeyword_options['min_3words_phrase_length'] = 12; //minimum length of 3 word phrases
	$autokeyword_options['min_3words_phrase_occur'] = 3; //minimum occur of 3 words phrase
	$autokeyword_options['show_3_words'] = 0;  //show 3 words phrase - disabled by default

	add_option('autokeyword_options', $autokeyword_options, 'autokeyword Options');
}

/**
 * Function: Get the content of the post or posts.
 */
function get_content() {
	$content = get_the_content();
	$content = apply_filters('the_content', $content);
	$content = str_replace(']]>', ']]&gt;', $content);
	return $content;
}

?>