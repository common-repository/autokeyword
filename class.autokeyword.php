<?php

/******************************************************************
	Projectname:   Automatic Keyword Generator
	Version:       1.1
	Author:        Ver Pangonilo <smp_AT_itsp.info>
	Last modified: 03 July 2009
	Copyright (C): 2005 - 2009 Ver Pangonilo, All Rights Reserved
	Change Log:
	===========
	0.2 Ver Pangonilo - 22 July 2005
	================================
	Added user configurable parameters and commented codes
	for easier end user understanding.
						
	0.3 Vasilich  (vasilich_AT_grafin.kiev.ua) - 26 July 2006
	=========================================================
	Added encoding parameter to work with UTF texts, min number 
	of the word/phrase occurrences, 
	
	0.4 Peter Kahl, B.S.E.E. (www.dezzignz.com) - 24 May 2009 
	=========================================================
	To strip the punctuations CORRECTLY, moved the ';' to the
	end.
	
	Also added '&nbsp;', '&trade;', '&reg;'.
	
	1.0 Ver Pangonilo - 29 May 2009 
	=========================================================
	Additional Functionality: Enable user added common words 
	without hard-coding it into the class.

	1.1 Ver Pangonilo - 03 July 2009 
	=========================================================
	* Minor bug update
	* Converted class to PHP 5
	* Created a separate function for common words

	License:
	========
	* GNU General Public License (Version 2, June 1991)
	*
	* This program is free software; you can redistribute
	* it and/or modify it under the terms of the GNU
	* General Public License as published by the Free
	* Software Foundation; either version 2 of the License,
	* or (at your option) any later version.
	*
	* This program is distributed in the hope that it will
	* be useful, but WITHOUT ANY WARRANTY; without even the
	* implied warranty of MERCHANTABILITY or FITNESS FOR A
	* PARTICULAR PURPOSE. See the GNU General Public License
	* for more details.

	Description:
	============
	This class can generates automatically META Keywords for your
	web pages based on the contents of your articles. This will
	eliminate the tedious process of thinking what will be the best
	keywords that suits your article. The basis of the keyword
	generation is the number of iterations any word or phrase
	occured within an article.

	This automatic keyword generator will create single words,
	two word phrase and three word phrases. Single words will be
	filtered from a common words list.
	
******************************************************************/

class autokeyword {

	//declare variables
	//the site contents
	public $contents;
	// character encoding
	public $encoding;
	//the generated keywords
	public $keywords;
	//minimum word length for inclusion into the single word
	//metakeys
	public $wordLengthMin;
	public $wordOccuredMin;
	//minimum word length for inclusion into the 2 word
	//phrase metakeys
	public $word2WordPhraseLengthMin;
	public $phrase2WordLengthMinOccur;
	//minimum word length for inclusion into the 3 word
	//phrase metakeys
	public $word3WordPhraseLengthMin;
	//minimum phrase length for inclusion into the 2 word
	//phrase metakeys
	public $phrase2WordLengthMin;
	public $phrase3WordLengthMinOccur;
	//minimum phrase length for inclusion into the 3 word
	//phrase metakeys
	public $phrase3WordLengthMin;
	// list of common words
	private $commonWords = array();

	// constructor
	public function __construct($params, $encoding)
	{
		//get parameters
		$this->encoding = $encoding;
		mb_internal_encoding($encoding);

		// contents
		$this->contents = $this->replace_chars($params['content']);

		// single word
		$this->wordLengthMin = $params['min_word_length'];
		$this->wordOccuredMin = $params['min_word_occur'];

		// 2 word phrase
		$this->word2WordPhraseLengthMin = $params['min_2words_length'];
		$this->phrase2WordLengthMin = $params['min_2words_phrase_length'];
		$this->phrase2WordLengthMinOccur = $params['min_2words_phrase_occur'];

		// 3 word phrase
		$this->word3WordPhraseLengthMin = $params['min_3words_length'];
		$this->phrase3WordLengthMin = $params['min_3words_phrase_length'];
		$this->phrase3WordLengthMinOccur = $params['min_3words_phrase_occur'];
		
		// common words
		$this->commonWords = $this->commonWords( $this->list2array($params['user_excluded_common_words']) ); 
	}
	
	//parse single, two words and three words
	public function get_keywords()
	{
		$keywords = $this->parse_words().$this->parse_2words().$this->parse_3words();
		return substr($keywords, 0, -2);
	}

	//single words META KEYWORDS
	public function parse_words()
	{
		//create an array out of the site contents
		$s = split(' ', $this->contents);
		//initialize array
		$k = array();
		//iterate inside the array
		foreach( $s as $key=>$val ) {
			//delete single or two letter words and
			//Add it to the list if the word is not
			//contained in the common words list.
			if(mb_strlen(trim($val)) >= $this->wordLengthMin  && !in_array(trim($val), $this->commonWords)  && !is_numeric(trim($val))) {
				$k[] = trim($val);
			}
		}
		//count the words
		$k = array_count_values($k);
		//sort the words from
		//highest count to the
		//lowest.
		$occur_filtered = $this->occure_filter($k, $this->wordOccuredMin);
		arsort($occur_filtered);

		$imploded = $this->implode(', ', $occur_filtered);
		//release unused variables
		unset($k);
		unset($s);

		return $imploded;
	}
	/**
	 * 2 word phrase keywords
	 */
	public function parse_2words()
	{
		//initilize variables
		$x = array();
		$y = array();
		$count = '';

		//create an array out of the site contents
		$x = split(' ', $this->contents);
		//number of words
		$count = count($x) - 1;
		// iterate through the array
		for ($i=0; $i < $count; $i++) {
			// delete phrases lesser than word2WordPhraseLengthMin
			// number of characters
			if( (mb_strlen(trim($x[$i])) >= $this->word2WordPhraseLengthMin ) && (mb_strlen(trim($x[$i+1])) >= $this->word2WordPhraseLengthMin ) && ( !in_array(trim($x[$i+1]),$this->commonWords )) && (mb_strlen(trim($x[$i]) . trim($x[$i+1])) >= $this->phrase2WordLengthMin) )
			{
				$y[] = trim($x[$i]).' '.trim($x[$i+1]);
			}
		}

		//count the 2 word phrases
		$y = array_count_values($y);

		$occur_filtered = $this->occure_filter($y, $this->phrase2WordLengthMinOccur);
		//sort the words from highest count to the lowest.
		arsort($occur_filtered);

		$imploded = $this->implode(', ', $occur_filtered);
		//release unused variables
		unset($y);
		unset($x);
		unset($count);

		return $imploded;
	}
	/**
	 * 3 word phrase keywords
	 */
	public function parse_3words()
	{
		//initialize variables
		$a = array();
		$b = array();
		$count = '';		
		//create an array out of the site contents
		$a = split(' ', $this->contents);
		//number of words
		$count = count($a)-1;
		for ($i=0; $i < $count; $i++) {
			//delete phrases lesser than 5 characters
			if( (mb_strlen(trim($a[$i])) >= $this->word3WordPhraseLengthMin) && (mb_strlen(trim($a[$i+1])) > $this->word3WordPhraseLengthMin) && (mb_strlen(trim($a[$i+2])) > $this->word3WordPhraseLengthMin) && ( !in_array(trim($a[$i+2]),$this->commonWords )) && (mb_strlen(trim($a[$i]) . trim($a[$i+1]) . trim($a[$i+2])) >= $this->phrase3WordLengthMin) )
			{
				$b[] = trim($a[$i]) . ' ' . trim($a[$i+1]) . ' ' .trim($a[$i+2]);
			}
		}

		//count the 3 word phrases
		$b = array_count_values($b);
		//sort the words from
		//highest count to the
		//lowest.
		$occur_filtered = $this->occure_filter($b, $this->phrase3WordLengthMinOccur);
		arsort($occur_filtered);

		$imploded = $this->implode(', ', $occur_filtered);
		//release unused variables
		unset($a);
		unset($b);
		unset($count);

		return $imploded;
	}

	/**
	 * This section covers all private functions.
	 */
	// turn the site contents into an array
	// then replace common html tags.
	private function replace_chars($content, $userExcludedCommonWords = '')
	{
		//convert all characters to lower case
		$content = mb_strtolower($content);
		//$content = mb_strtolower($content, 'UTF-8');
		$content = strip_tags($content);

      	//updated in v0.3, 24 May 2009 
      	//updated in v1.0, July 2009      	
		$punctuations = array(',', ')', '(', '.', "'", '"',
		'<', '>', '!', '?', '/', '-',
		'_', '[', ']', ':', '+', '=', '#',
		'$', '&quot;', '&copy;', '&gt;', '&lt;', 
		'&nbsp;', '&trade;', '&reg;', ';', '&',
		chr(10), chr(13), chr(9));

		$content = str_replace($punctuations, ' ', $content);
		// replace multiple gaps
		$content = preg_replace('/ {2,}/si', ' ', $content);

		return $content;
	}

	// Function commonWords - to process common words in a single 
	// function & make it available within the class
	private function commonWords($userCommonWords){
	 	//list of commonly used words
		// this can be edited to suit your needs
		$common = array('able', 'about', 'above', 'act', 'add', 'afraid', 'after', 'again', 'against', 'age', 'ago', 'agree', 'all', 'almost', 'alone', 'along', 'already', 'also', 'although', 'always', 'am', 'amount', 'an', 'and', 'anger', 'angry', 'animal', 'another', 'answer', 'any', 'appear', 'apple', 'are', 'arrive', 'arm', 'arms', 'around', 'arrive', 'as', 'ask', 'at', 'attempt', 'aunt', 'away', 'back', 'bad', 'bag', 'bay', 'be', 'became', 'because', 'become', 'been', 'before', 'began', 'begin', 'behind', 'being', 'bell', 'belong', 'below', 'beside', 'best', 'better', 'between', 'beyond', 'big', 'body', 'bone', 'born', 'borrow', 'both', 'bottom', 'box', 'boy', 'break', 'bring', 'brought', 'bug', 'built', 'busy', 'but', 'buy', 'by', 'call', 'came', 'can', 'cause', 'choose', 'close', 'close', 'consider', 'come', 'consider', 'considerable', 'contain', 'continue', 'could', 'cry', 'cut', 'dare', 'dark', 'deal', 'dear', 'decide', 'deep', 'did', 'die', 'do', 'does', 'dog', 'doing', 'done', 'doubt', 'down', 'during', 'each', 'ear', 'early', 'eat', 'effort', 'either', 'else', 'end', 'enjoy', 'enough', 'enter', 'even', 'ever', 'every', 'except', 'expect', 'explain', 'fail', 'fall', 'far', 'fat', 'favor', 'fear', 'feel', 'feet', 'fell', 'felt', 'few', 'fill', 'find', 'fit', 'fly', 'follow', 'for', 'forever', 'forget', 'from', 'front', 'gave', 'get', 'gives', 'goes', 'gone', 'good', 'got', 'gray', 'great', 'green', 'grew', 'grow', 'guess', 'had', 'half', 'hang', 'happen', 'has', 'hat', 'have', 'he', 'hear', 'heard', 'held', 'hello', 'help', 'her', 'here', 'hers', 'high', 'hill', 'him', 'his', 'hit', 'hold', 'hot', 'how', 'however', 'I', 'if', 'ill', 'in', 'include', 'indeed', 'instead', 'into', 'iron', 'is', 'it', 'its', 'just', 'keep', 'kept', 'knew', 'know', 'known', 'late', 'later', 'least', 'led', 'left', 'lend', 'less', 'let', 'like', 'likely', 'likr', 'lone', 'long', 'look', 'lot', 'make', 'many', 'may', 'me', 'mean', 'met', 'might', 'mile', 'mine', 'moon', 'more', 'most', 'move', 'much', 'must', 'my', 'near', 'nearly', 'necessary', 'neither', 'never', 'next', 'no', 'none', 'nor', 'not', 'note', 'nothing', 'now', 'number', 'of', 'off', 'often', 'oh', 'on', 'once', 'only', 'or', 'other', 'ought', 'our', 'out', 'please', 'prepare', 'probable', 'pull', 'pure', 'push', 'put', 'raise', 'ran', 'rather', 'reach', 'realize', 'reply', 'require', 'rest', 'run', 'said', 'same', 'sat', 'saw', 'say', 'see', 'seem', 'seen', 'self', 'sell', 'sent', 'separate', 'set', 'shall', 'she', 'should', 'side', 'sign', 'since', 'so', 'sold', 'some', 'soon', 'sorry', 'stay', 'step', 'stick', 'still', 'stood', 'such', 'sudden', 'suppose', 'take', 'taken', 'talk', 'tall', 'tell', 'ten', 'than', 'thank', 'that', 'the', 'their', 'them', 'then', 'there', 'therefore', 'these', 'they', 'this', 'those', 'though', 'through', 'till', 'to', 'today', 'told', 'tomorrow', 'too', 'took', 'tore', 'tought', 'toward', 'tried', 'tries', 'trust', 'try', 'turn', 'two', 'under', 'until', 'up', 'upon', 'us', 'use', 'usual', 'various', 'verb', 'very', 'visit', 'want', 'was', 'we', 'well', 'went', 'were', 'what', 'when', 'where', 'whether', 'which', 'while', 'white', 'who', 'whom', 'whose', 'why', 'will', 'with', 'within', 'without', 'would', 'yes', 'yet', 'you', 'young', 'your', 'br', 'img', 'p','lt', 'gt', 'quot', 'copy');

		// Add user common words;
		// check if user common words is not empty
		if(!empty($userCommonWords))		
			// merge class common words &
			// user common words
			$common = array_merge($common, $userCommonWords);
			// delete duplicate contents if any
			array_unique($common);
		
		return $common;
	}

	private function occure_filter($array_count_values, $min_occur)
	{
		$occur_filtered = array();
		foreach ($array_count_values as $word => $occured) {
			if ($occured >= $min_occur) {
				$occur_filtered[$word] = $occured;
			}
		}

		return $occur_filtered;
	}

	private function implode($gule, $array)
	{
		$c = '';
		foreach($array as $key=>$val) {
			@$c .= $key.$gule;
		}
		return $c;
	}
	
	// Function: list to array
	// Usage: to convert user common words list 
	// to array
	private function list2array($list)
	{
		// initialize
		$list_array = array();
		$trim_array = array();
		// convert the list into an array
		$list_array = explode(',', trim($list));
		foreach ($list_array as $var)
		{
			$trim_array[] = trim($var);
		}
		//unset variables
		unset ($list_array);

		return $trim_array;
	}
}
?>
