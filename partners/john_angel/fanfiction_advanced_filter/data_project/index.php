<?php
include_once('../../../../simplehtmldom_1_5/simple_html_dom.php');

$paramURL = 'https://www.fanfiction.net/book/Harry-Potter/?&srt=1&lan=1&r=10&len=40';
$rpcR = 0;
$wpcR = 0;
$fpcR = 0;
$flpcR = 0;
$wprR = 0;

$urlR = str_replace("~", "&", $paramURL);

function judgeStory($stories, $index, $RpC, $WpR, $WpC, $FpC, $FlpC, $ifVerbose){
		
	//Correct the links so that they point back to the original site.
	foreach($stories[$index]->find("a") as $ele){
		$ele->href="http://www.fanfiction.net".($ele->href);
	}
	
	if($ifVerbose == 1){//A verbose clause for printing test results
		echo $stories[$index];
	}
	
	//Correct the image so that it points back to the original site.
	$stories[$index]->find("img", 0)->src="http://www.fanfiction.net".($stories[$index]->find("img", 0)->src);
	//var_dump(str_replace("'/static/images/d_60_90.jpg' data-original=", "", ($stories[$index]->find("img", 0)->outertext)));
	//$pos1 = (strpos(($stories[$index]->find("img", 0)->outertext), "data-original=")) + 15;
	//$pos2 = (strpos(($stories[$index]->find("img", 0)->outertext), "width")) - 2;
	//if($pos2 - $pos1 < 80)
		//$stories[$index]->find("img", 0)->src=(substr($stories[$index]->find("img", 0)->outertext, $pos1, ($pos2-$pos1)));
	
	//Stores the cover image of the story
	$picture = $stories[$index]->find("img", 0);
	
	if($ifVerbose == 1){//A verbose clause for printing test results
		echo "\nPicture: ".$picture;
	}
	
	//Stores the title of the story
	$title = $stories[$index]->find("a", 0)->plaintext;
	
	if($ifVerbose == 1){//A verbose clause for printing test results
		echo "\nTitle: ".$title;
	}
	
	//Stores the author of the story
	$author = $stories[$index]->find("a", 2)->plaintext;
	
	if($ifVerbose == 1){//A verbose clause for printing test results
		echo "\nAuthor: ".$author;
	}
	
	//Stores the statistics element of the story.
	$stats = $stories[$index]->find("div", 1)->plaintext;
	
	if($ifVerbose == 1){//A verbose clause for printing test results
		echo "\nStats: ".$stats;
	}
	
	//Stores the description of the story followed by the stats element of the story.
	$desc = $stories[$index]->find("div", 0)->plaintext;
	
	//Stores only the desciption element of the story
	$description = strchr($desc, $stats, true);
	
	if($ifVerbose == 1){//A verbose clause for printing test results
		echo "\nDescription: ".$description;
	}
	
	//This set parses the stats element and stores the various stats of the element
	$workStats = explode(" ", $stats);
	$age = $workStats[1];
	$language = $workStats[3];
	if($workStats[5] != "Chapters:")//This is used to find if there is a genra element to the story specified
		$genre = $workStats[5];
	$iter = array_search("Chapters:", $workStats);//Because it is possible for elements to be missing, we search for the lable
	if($iter != FALSE)
		$chapters = floatval(preg_replace("/[^-0-9\.]/","",$workStats[$iter+1]));//and then fill in the adjacent slot for that lable
	$iter = array_search("Words:", $workStats);
	if($iter != FALSE)
		$words = floatval(preg_replace("/[^-0-9\.]/","",$workStats[$iter+1]));
	$iter = array_search("Reviews:", $workStats);
	if($iter != FALSE)
		$reviews = floatval(preg_replace("/[^-0-9\.]/","",$workStats[$iter+1]));
	$iter = array_search("Favs:", $workStats);
	if($iter != FALSE)
		$favs = floatval(preg_replace("/[^-0-9\.]/","",$workStats[$iter+1]));
	$iter = array_search("Follows:", $workStats);
	if($iter != FALSE)
		$follows = floatval(preg_replace("/[^-0-9\.]/","",$workStats[$iter+1]));
	
	if($ifVerbose == 1){//A verbose clause for printing test results
		echo "\nAge: ".$age." Language: ".$language." Genre: ".$genre." Chapters: ".$chapters." Words: ".$words." Reviews: ".$reviews." Favorites: ".$favs." Follows: ".$follows;
	}

	//This calculates additional statistics about the story
	$revPerChap = round($reviews/$chapters); //calculates Reviews per Chapter
	$wordsPerRev = round($words/$reviews); //calculates Words per Review
	$wordPerChap = round($words/$chapters); //calculates Words per Chapter
	$favPerChap = round($favs/$chapters); //calculates Favorites per Chapter
	$folPerChap = round($follows/$chapters); //calculates Followers per Chapter
	
	if($ifVerbose == 1){//A verbose clause for printing test results
		echo "\nReviews/Chapter: ".$revPerChap." Words/Review: ".$wordsPerRev." Words/Chapter: ".$wordPerChap." Favorites/Chapter: ".$favPerChap." Follows/Chapter: ".$folPerChap;
	}
	
	//This calculates the final rank of the story against the requirements set up by the user.
	$rankA = 0; 
	$rankB = 0;
	$rankC = 0;
	$rankD = 0;
	$rankE = 0;
	if($RpC != 0)
		$rankA = $rank + $revPerChap - $RpC;
	if($WpR != 0 || $reviews == 0 || $reviews == null)
		$rankB = ($rank + ($WpR - $wordsPerRev)/($wordsPerRev/$WpR) - ($wordsPerRev/25))/10;
	if($WpC != 0)
		$rankC = ($rank + $wordPerChap - $WpC)/(725);
	if($FpC != 0)
		$rankD = $rank + $favPerChap - $FpC;
	if($FlpC != 0)
		$rankE = $rank + $folPerChap - $FlpC;
	
	if($ifVerbose == 1){//A verbose clause for printing test results
		echo "\nReviews/Chapter Rank: ".$rankA." Words/Review Rank: ".$rankB." Words/Chapter Rank: ".$rankC." Favorites/Chapter Rank: ".$rankD." Follows/Chapter Rank: ".$rankE;
	}
	
	echo $data = '"'.$title.'",'.'"'.$author.'",'.$age.','.$language.','.$genre.','.$chapters.','.$words.','.$reviews.','.$favs.','.$follows.','.$revPerChap.','.$wordsPerRev.','.$wordPerChap.','.$favPerChap.','.$folPerChap.'<br />';
	
	return $rankTotal = $rankA + $rankB + $rankC + $rankD + $rankE;

}

function minorEncode($str){
	
	for($i = 0; $i < strlen($str); $i++){
		
		if(!($str[$i] == ":" || $str[$i] == "/" || $str[$i] == "?" || $str[$i] == "&" || $str[$i] == "=")){
			
			$str = str_replace($str[$i], urlencode($str[$i]), $str);
			
		}
		
	}
	
	return $str;
	
}

$worthy = array();
$currentPage = $_POST["cp"];

for($j = 1;$j <= 40; $j++){

	$url = minorEncode($urlR);
	$newURL = $url."&p=".$j;
	$html = file_get_html($newURL);
	$stories = $html->find("div[class='z-list zhover zpointer']");

	for($i = 0;$i < 25;$i++){
		
		if(judgeStory($stories, $i, $rpcR, $wprR, $wpcR, $fpcR, $flpcR, 0) > 0){
			
			array_push($worthy, $stories[$i]);
			
		}
	}
	
}
	
//foreach ($worthy as $worthyStory)
	//echo $worthyStory;

?>