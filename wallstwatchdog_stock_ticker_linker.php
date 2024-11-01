<?php
/*
Plugin Name: Wallstwatchdog Stock Ticker Linker
Version: 1.0
Author: Wallstwatchdog.com
Description: This plugin automatically search blog content for ticker symbols and link the tickers to company research pages at wallstwatchdog.com
The tickers can be indentified from content in two ways: (TICKER) or (MARKET:TICKER). For example, AAPL can be identified from either (AAPL) or (NASDAQ:AAPL).
*/

function link_ticker ($content) {

   $content = preg_replace_callback('/\((.*?)\)/', "modify_match", $content);

     return $content;
}

function modify_match($matches){
	
    $match = $matches[1];
    
    if(strpos($match,"</a>")!==false) return '('.$match.')'; // if link exists
    
	if(strpos($match,":")===false){		// no colon found
	    $ticker = strtoupper(trim($match));	    
	    if(check_valid_ticker($ticker))
           return '(<a href="http://wallstwatchdog.com/company?symbol='.$ticker.'">'.$match."</a>)";
      } 	
	 else {  // colon found
	 	
	    $tokens = split(":", $match);

	    if(count($tokens)==2&&check_valid_ticker(trim($tokens[1])))
	 	return '(<a href="http://wallstwatchdog.com/company?symbol='.trim($tokens[1]).'">'.$match."</a>)";
	 }
	 
	 return '('.$match.')'; // no ticker found
	 
}

function check_valid_ticker($token){

	if(strlen($token)>=1&&strlen($token)<=6&&ctype_alpha($token)) return 1;
	else return 0;		
}

add_filter ('the_content', 'link_ticker');
add_filter ('the_content_limit', 'link_ticker');
add_filter ('the_content_rss', 'link_ticker');


?>
