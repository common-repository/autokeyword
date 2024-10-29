=== Autokeyword ===
Contributors: Ver Pangonilo
Donate link: http://sp62.com/donate.php
Tags: SEO, keywords, autokeyword
Requires at least: 2.5.0
Tested up to: 2.8.1
Stable tag: 1.2.2

This plugin automatically adds meta keywords to your WordPress blog.

== Description ==

Autokeyword plugin adds meta keywords from the contents of your Wordpress blog. Autokeyword plugin generates keyword automatically and does not require manually typing keywords which is very tedious. It uses the award winning Automatic Keyword Generator PHP Class.

Related Links:

* [Plugin Homepage](http://sp62.com/)
* [Support Forum](http://wordpress.org/tags/autokeyword)

== Installation ==
1. Upload the Auto Keyword plugin <em>autokeyword</em> folder to the /wp-content/plugins/ directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Go to the Settings -> Auto Keyword and customize the options.
4. In the default option of the Auto Keyword plugin, the 2 words phrase and 3 words phrase keywords option are disabled.

== Screenshots ==

1. Auto Keyword Menu
2. Auto Keyword Options

== Changelog ==

= 1.0 =
* Initial release of Auto Keyword.

= 1.1 =
* Added Manual placement option of meta keywords.
* Included autokeyword.pot file.

= 1.2 =
* Added functionality to enable users excluded common words
* Added functionality to enable users to append keywords into the automatically generated keywords list.

= 1.2.1 =
*  Minor update: Bug - excluded keywords remain included in list [Solved]

= 1.2.2 =
* Automatic Keyword Generator class converted to PHP 5
* Improved functionality for better 2 word phrase & 3 word phrase keywords selection
* Minor bug updates - Word Length & Phrase length values for 2 word phrase & 3 word phrase keywords interchanged [Solved]


== Frequently Asked Questions ==

= How do I add keywords manually? =

To add keywords manually, type in comma separated keywords on the textbox labelled <strong>Added Keywords</strong>. These keywords will be appended into the automatically generated keywords list. 

By default, the word <em>autokeyword</em> is added. Replace it with your desired keywords separated by  a comma (,).

= I want to exclude some words, how to I do it? =

Type in words in the textbox labelled <strong>Excluded Words</strong> you wanted to exlude on the automatically generated list separated by a comma (,). 

= What character encoding do I use? =

The Auto Keyword plugin by default uses UTF-8 which supports Unicode standards. If your website is using a non-unicode language, ISO-8859-1 is recommended.

= What is Word Length? =

Word length is the number of character in a single word.

= What is Length of 2 Word Phrases? =

The total number of character in a 2 word phrase including spaces.

= What is Length of 3 Word Phrases? =

The total number of character in a 3 word phrase including spaces.

= What is Occurrence? =

It is the number of times the keyword appeared in your post.

= What is Show Single Word? =

The option "Show Single Word", "Show 2 Word Phrase" or "Show 3 Word Phrase" select whether you want the keyword combination to show up in your header.

= I want to locate the meta keywords manually, how will I do it? =

The Autokeyword plugin be default automatically place the meta keywords on the header of your blog. 
To locate the meta keywords manually, set the "Meta Keywords Setting" to manual, then include this following code into the current template <em>header.php</em>.

`<?php autokeyword(); ?>`

= My question isn't even answered there =

Please post your question at the [WordPress support forum](http://wordpress.org/tags/autokeyword).