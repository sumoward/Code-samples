


<?php
// The functions for the metasearch engine http://csserver.ucd.ie/~98110098/
include_once ("sefunctions.php");
include_once('simple_html_dom.php');
include_once ("class_lib.php");
//include_once 'pagination.class.php';

//aggregation
function aggregation($yahooarray, $bingarray, $blekkoarray, $countyahoo, $countbing, $countblekko, &$non_aggregated_array, &$merge_array)


{

	$highest_rank = 50;
	//check if the search engines contributed to the aggregation
	if($countyahoo == 0){
		echo " YAHOO returned no results and is not contributing to the aggregated list<br/>";
		// create the merge array for clustering
		//$merge_array  =  array_merge_recursive($bingarray, $blekkoarray);
	}

	if($countbing == 0){
		echo " BING returned no results and is not contributing to the aggregated list<br/>";
		// create the merge array for clustering
		//$merge_array =  array_merge_recursive($yahooarray, $blekkoarray);
	}

	//check if bleeko returned results
	if($countblekko == 0){
		echo " BLEECKO returned no results and is not contributing to the aggregated list<br/>";
		// create the merge array for clustering
		//$merge_array =  array_merge_recursive($yahooarray, $bingarray);
	}
	//create the merge array for clustering
	$merge_array =  array_merge_recursive($yahooarray, $bingarray);
	$merge_array  =  array_merge_recursive($merge_array, $blekkoarray);


	//GET THE SIZE OF  ARRAYS

	// Score the URLs By rank

	//check that the aggregation list is made using lists of the same length

	if($countyahoo != 0){
		$a = $countyahoo;

	}
	else{$a = 50;}
	if($countbing != 0){
		$b = $countbing;

	}
	else{$b = 50;}

	if($countblekko != 0){
		$c = $countblekko;

	}
	else{
		$c = 50;
	}


	//highest rank is et to the size of the largest array
	if ($a<=$b && $a<=$c && $countyahoo != 0){$highest_rank = $a;}
	if ($b<$a && $b<$c && $countbing != 0){$highest_rank = $b;}
	if ($c<$a && $c<$b && $countblekko != 0){$highest_rank = $c;}
	//echo $highest_rank;
	//echo "<br/>LETS SCORE THE URLs BY RANK<br/>";
	for($j = 0;$j<$highest_rank;$j++)
	{
		if($countyahoo != 0){
			$yahooarray[$j][1] = $highest_rank - $j;}
			if($countbing != 0){
				$bingarray[$j][1] = $highest_rank - $j;}
				if ($countblekko != 0){
					$blekkoarray[$j][1] = $highest_rank - $j;}
					//$yahooarray[$i] =  $urlyahoo[$i];
					//echo $yahooarray[$j][0]." equals ".$yahooarray[$j][1]."<br/>";
					//echo $bingarray[$j][0]." equals ".$bingarray[$j][1]."<br/>";
					//echo $blekkoarray[$j][0]." equals ".$blekkoarray[$j][1]."<br/>";
	}


	//get the total score per URL //$yahooarray[$i][2]== 'false' &&
	if($countbing != 0 && $countyahoo != 0){
		for($i = 0;$i<$highest_rank;$i++){
			for($j = 0;$j<$highest_rank;$j++){

				if ( $yahooarray[$i][0]== $bingarray[$j][0])
				{
					$score = $yahooarray[$i][1]+ $bingarray[$j][1];
					$yahooarray[$i][1]= $score;
					$bingarray[$j][2] = 'true';
					//if a matching url is found skipp onto the next query
					$j = $highest_rank;
					//echo " <br/>yahoo and bing".$bingarray[$j][2];
				}

			}
		}
	}
	if ($countblekko != 0 && $countyahoo != 0){
		for($i = 0;$i<$highest_rank;$i++){
			for($j = 0;$j<$highest_rank;$j++){


				if ( $yahooarray[$i][0]== $blekkoarray[$j][0])
				{
					$score1 = $yahooarray[$i][1]+ $blekkoarray[$j][1];
					$yahooarray[$i][1]= $score1;
					$blekkoarray[$j][2] = 'true';
					//if a matching url is found skipp onto the next query
					$j = $highest_rank;
					//echo "<br/>Yahoo and Blekko".$blekkoarray[$j][2];
				}

			}
		}
	}
	if ($countblekko != 0 && $countbing != 0){
		for($i = 0;$i<$highest_rank;$i++){
			for($j = 0;$j<$highest_rank;$j++){


				if ( $bingarray[$i][0]== $blekkoarray[$j][0])
				{
					$score2 = $bingarray[$i][1]+ $blekkoarray[$j][1];
					$bingarray[$i][1]= $score2;
					$blekkoarray[$j][2] = 'true';
					//if a matching url is found skipp onto the next query
					$j = $highest_rank;
					//echo "<br/> bing and Blekko".$blekkoarray[$j][2];
				}
			}
		}
	}

	//REMOVE ANY DUPLCATES


	for($i = 0;$i<$highest_rank;$i++){
		for($j = 0;$j<$highest_rank;$j++){

			if(array_key_exists($j, $bingarray)) {

				if ($countbing != 0 && $countyahoo != 0 && $yahooarray[$i][0]== $bingarray[$j][0])
				{
					unset($bingarray[$j]);
					//echo "bing exterminate <br/>";
				}
					
				if($countblekko != 0 && $countyahoo != 0 && array_key_exists($j, $blekkoarray)) {

					if ( $yahooarray[$i][0]== $blekkoarray[$j][0])
					{
						unset($blekkoarray[$j]);
						//echo "blekko exterminate <br/>";

					}}
			}}
	}

	//NOW LETS MERGE OUR FINAL ARRAYS
	//use ARRAY MERGE RECURSIVE

	if ($countbing != 0){
		$finalarray  =  array_merge_recursive($yahooarray, $bingarray);}
		if ($countblekko != 0){
			$finalarray  =  array_merge_recursive($finalarray, $blekkoarray);}
			//Check that it is the lengh of the two arrays. it should be 6 long

			$lenght = sizeof($finalarray);

			//Save the non aggregated list so we can compare it to the aggregated one
			$non_aggregated_array = $finalarray;


			//REORDER THE FINAL ARRAY USING sksort
			//FUNCTION TO SORT THE ARRAY


			sksort($finalarray, "1", $sort_ascending=false);


			//Check that it is the lengh of the array. it should be 4 long
			//echo "The final array will be this long =".$size."<br/>";
			//echo "<br/>";

			return $finalarray;

}


//FUNCTION TO USE IN-ARRAY for a multidimensional ARRAY
function in_array_r($needle, $haystack) {
	foreach ($haystack as $item) {
		if ($item === $needle || (is_array($item) && in_array_r($needle, $item))) {
			return true;
		}
	}

	return false;
}
//print out the aggregated array
function aggregation_print($finalarray){

	//echo "<br/>---------------------------------FINAL SEARCH RESULTS -  this is an aggregated search----------------------------------------------------------<br/>";
	$size = sizeof($finalarray);
	for($j = 0;$j<$size;$j++)
	{
		echo "<br/>".$finalarray[$j][4]."<br/>";
		echo  '<a href="'.$finalarray[$j][0].'">'.$finalarray[$j][4].'</a>'."<br/>";
		echo "<br/>".$finalarray[$j][3]."<br/>";
		echo  $finalarray[$j][0]."<h3> Aggregation Score = ".$finalarray[$j][1]."</h3>";

	}
}
//draw down results from the blekko api
function blekko_curl($query){

	//edit the query so Blekko can use boolean criteria
	$bad = array("-", "+", );
	$good = array("NOT ", "and ", );
	$query = str_replace($bad,$good,$query);
	//echo $query." q2 <br/>";

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, 'http://blekko.com/ws/?q='.urlencode($query).'/rss+/ps=50&auth=bf458e41');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$output = curl_exec($ch);
	curl_close($ch);
	//echo $output;
	if ($output === FALSE) {

		echo "cURL Error: " . curl_error($ch);

	}
	else{
		$datablekko = new SimpleXMLElement($output);
			
		return $datablekko;
	}
}
//count the number of blekko results
function blekko_count($datablekko){
	$countblekko = 0;
	foreach ($datablekko->channel->item as $d) {
		//echo strip_tags($datablekko->channel->item->link);
		++$countblekko; // <= here you go
		//echo $countblekko."<br/>";
	}
	return $countblekko;
}
//populate the blekko array
function blekko_pop($countblekko, $datablekko){
	for($j = 0;$j<$countblekko;$j++){
		$blekkoarray[$j][0] = strip_tags($datablekko->channel->item[$j]->link);
		//echo $blekkoarray[$j][0]."<br/>";
		$blekkoarray[$j][1] = 3;
		$blekkoarray[$j][2] = 'false';
		$blekkoarray[$j][3] = strip_tags($datablekko->channel->item[$j]->description);
		$blekkoarray[$j][4] = strip_tags($datablekko->channel->item[$j]->title);
		$blekkoarray[$j][5] = 'false';
		$blekkoarray[$j][6] = 0.00;
		$blekkoarray[$j][7] = 0.00;
	}
	return $blekkoarray;
}

//print out the Blekko results
function blekko_print($countblekko, $blekkoarray){

	for($j = 0;$j<$countblekko;$j++){
		echo  '<a href="'.$blekkoarray[$j][0].'">'.$blekkoarray[$j][4].'</a>'."<br/>";
		echo "<br/>";
		echo $blekkoarray[$j][3].'<br />';
		echo  $blekkoarray[$j][0]."<br/>";
	}


}
//do the serach for bing
function bing_curl($query){
	//BING
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, 'http://api.search.live.net/xml.aspx?Appid=9E09DFF7C20367FDD76828005C2BAD06D5B85858&query='.urlencode($query).'&sources=web&web.count=50&web.offset=0&adult=off');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$output = curl_exec($ch);
	curl_close($ch);
	if ($output === FALSE) {

		echo "cURL Error: " . curl_error($ch);

	}

	$feed = new SimpleXMLElement($output);
	//use the web: namespace
	$children =  $feed->children('http://schemas.microsoft.com/LiveSearch/2008/04/XML/web');
	return $children;
}
//count out bing results
function bing_count($children){
	$countbing = 0;
	foreach ($children->Web->Results->WebResult as $d) {
		++$countbing; // <= count the number of returns
	}
	return $countbing;
}
//populate the bing array
function bing_pop($countbing,$children){
	for($j = 0;$j<$countbing;$j++){
		$bingarray[$j][0] =  	strip_tags($children->Web->Results->WebResult[$j]->Url);
		$bingarray[$j][1] = 2;
		$bingarray[$j][2] = 'false';
		$bingarray[$j][3] = strip_tags($children->Web->Results->WebResult[$j]->Description);
		$bingarray[$j][4] = strip_tags($children->Web->Results->WebResult[$j]->Title);
		$bingarray[$j][5] = 'false';
		$bingarray[$j][6] = 0.00;
		$bingarray[$j][7] = 0.00;
	}
	return $bingarray;
}

//print out bing results
function bing_print($countbing, $bingarray){

	for($j = 0;$j<$countbing;$j++){

		echo  '<a href="'.$bingarray[$j][0].'">'.$bingarray[$j][4].'</a>'."<br/>";
		echo "<br/>";
		echo $bingarray[$j][3].'<br/>';
		echo  $bingarray[$j][0]."<br/>";
		echo "<br/>";
	}

}
//print out the bing results in a paginated fashion
// Create the pagination object this allows our reulst to be printed to screen in paginated format
function pagination($pagcount,$pagarray){


	//dceclare the pagination class
	$pagination1 = new pagination;

	// If we have an array with items
	if (count($pagarray)) {
		// Parse through the pagination class
		$holderpages = $pagination1->generate($pagarray, 5);

		// If we have items
		if (count($holderpages) != 0) {

			$counter = 0;
				
			foreach ($holderpages as $productID => $holder_array) {
				// Show the information about the item
				echo  '<a href="'.$holder_array[0].'">'.$holder_array[4].'</a>'."<br/>";
				echo "<br/>";
				echo $holder_array[3].'<br/>';
				echo "<br/>";
				echo  $holder_array[0]."<br/>";
				echo "<br/>";
				$counter++;

			}

			// Create the page numbers
			echo $pageNumbers = '<div>'.$pagination1->links().'</div>';
		}
	}
}

//contact the yahoo api
function deadyahoo_curl($query){
	//echo $query;

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, 'http://query.yahooapis.com/v1/public/yql?q=%20SELECT%20title%2Cclickurl%2Cabstract%2Cdispurl%2C%20url%20FROM%20search.web(50)%20WHERE%20query%3D%27'.urlencode($query).'%27%20and%20appid%3D%22dj0yJmk9cDJUU3dBbVNQdTZBJmQ9WVdrOWFVdDRTbEF5TkdzbWNHbzlNVEUyT1RBME1qa%22%20%20&env=store%3A%2F%2Fdatatables.org%2Falltableswithkeys');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$output = curl_exec($ch);
	curl_close($ch);
	if ($output === FALSE) {

		echo "cURL Error: " . curl_error($ch);

	}

	$datayahoo = new SimpleXMLElement($output);

	return $datayahoo;
}
//FUNCTION TO CALL THE entirewebapi

function yahoo_curl($query){
	//echo $query;

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, 'http://www.entireweb.com/xmlquery?pz=f52f88b872b35b26c48d680613a6283c&ip=1.2.3.4&n=50&of=0&sc=9&format=xml&q='.urlencode($query));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$output = curl_exec($ch);
	curl_close($ch);
	if ($output === FALSE) {

		echo "cURL Error: " . curl_error($ch);

	}

	$datayahoo = new SimpleXMLElement($output);

	return $datayahoo;
}

//count out the yahoo results
function deadyahoo_count($datayahoo){
	$countyahoo = 0;
	foreach ($datayahoo->results->result as $d) {
			
		++$countyahoo; // <= here you go
	}
	return $countyahoo;
}

//count out the entireweb results
function yahoo_count($datayahoo){
	$countyahoo = 0;
	foreach ($datayahoo->QUERY->HIT as $d) {
			
		++$countyahoo; // <= here you go
	}
	return $countyahoo;
}
//NOT IN USE AT MOMENT
//function to scrape data from yahoo
function yahoo_scrape($query, &$countyahoo){
	$countyahoo = 0;

	//download the Yahoo page
	$html = file_get_html('http://uk.search.yahoo.com/search;_ylt=A0geu8dtkShOHyQA3ExLBQx.?p=".urlencode($query)."&n=40&ei=UTF-8&va_vt=any&vo_vt=any&ve_vt=any&vp_vt=any&vd=all&vst=0&vf=all&vm=p&fl=1&vl=lang_en&fr=yfp-t-710&pstart=1&b=41');
	//http://uk.search.yahoo.com/search;_ylt=A0geu8dtkShOHyQA3ExLBQx.?p=nasa&n=40&ei=UTF-8&va_vt=any&vo_vt=any&ve_vt=any&vp_vt=any&vd=all&vst=0&vf=all&vm=p&fl=1&vl=lang_en&fr=yfp-t-710&pstart=1&b=41
	//http://uk.search.yahoo.com/search;_ylt=A0geu8sSdShOPDsAqkVLBQx.?p=".urlencode($query)."&n=100&ei=UTF-8&va_vt=any&vo_vt=any&ve_vt=any&vp_vt=any&vd=all&vst=0&vf=all&vm=p&fl=1&vl=lang_en&fr=yfp-t-710&pstart=1&
	//echo $html;

	$yahooarray =array();

	$e ;
	$snippet = "snip";
	//count the number of results returned
	foreach($html->find('a.yschttl') as $e){
		++$countyahoo;
		//echo $html->find('a.yschttl spt');
		//echo $e ->href."<br/>";
		//echo $e ->innertext."<br/>";
		//echo $countyahoo."<br/>";
	}//echo "counter 1 = ".$countyahoo1;

	//populate the yahoo array;
	for($i =0; $i <$countyahoo; $i++){

		$yahoourl =$html->find('a.yschttl');
		$yahootitle= $html->find('a.yschttl');
		$yahoosnippet= $html->find('div.abstr');
		$yahooarray [$i][0] = $yahoourl[$i]->href;
		$yahooarray [$i][1] = "4";
		$yahooarray [$i][2] = "false";
		//populate with a holder piece of text
		$yahooarray [$i][3] = $snippet;
		//check that it returns a snippet
		if(isset($yahoosnippet[$i]->innertext)){

			$yahooarray [$i][3] = $yahoosnippet[$i]->innertext;
		}
		$yahooarray [$i][4] = $yahootitle[$i]->innertext;
		$yahooarray[$i][5] = 'false';
		$yahooarray[$i][6] = 0.00;
		$yahooarray[$i][7] = 0.00;
	}
	//var_dump($yahooarray);

	return $yahooarray;
}

//this is the old function which was made redundant by Yahoo's discontinuation of support
//populate the yahoo array
function deadyahoo_pop ($countyahoo, $datayahoo){
	for($j = 0;$j<$countyahoo;$j++)
	{
		$yahooarray[$j][0] =  strip_tags($datayahoo->results->result[$j]->url);// FILTER THIS
		$yahooarray[$j][1] = 1;
		$yahooarray[$j][2] = 'false';
		$yahooarray[$j][3] = strip_tags($datayahoo->results->result[$j]->abstract);
		$yahooarray[$j][4] = strip_tags($datayahoo->results->result[$j]->title);
		$yahooarray[$j][5] = 'false';
		$yahooarray[$j][6] = 0.00;
		$yahooarray[$j][7] = 0.00;
	}
	return $yahooarray;
}

//populate the ENTIREWEB array
function yahoo_pop ($countyahoo, $datayahoo){
	for($j = 0;$j<$countyahoo;$j++)
	{
		$yahooarray[$j][0] =  strip_tags($datayahoo->QUERY->HIT[$j]->URL);// FILTER THIS
		$yahooarray[$j][1] = 1;
		$yahooarray[$j][2] = 'false';
		$yahooarray[$j][3] = strip_tags($datayahoo->QUERY->HIT[$j]->SNIPPET);
		$yahooarray[$j][4] = strip_tags($datayahoo->QUERY->HIT[$j]->TITLE);
		$yahooarray[$j][5] = 'false';
		$yahooarray[$j][6] = 0.00;
		$yahooarray[$j][7] = 0.00;
	}
	return $yahooarray;
}
//print out the yahoo results
function yahoo_print($countyahoo,$yahooarray){

	for($j = 0;$j<$countyahoo;$j++)
	{
		echo  '<a href="'.$yahooarray[$j][0].'">'.$yahooarray[$j][4].'</a>'."<br/>";
		echo "<br/>";
		echo $yahooarray[$j][3].'<br />';
		echo  $yahooarray[$j][0]."<br/>";
		echo "<br/>";
			
	}

}
//function to gather html from google to parse for results
function google_scrape($query){

	// Create DOM from URL or file

	$googlecounter = 0;
	include_once('simple_html_dom.php');

	$html = file_get_html('http://www.google.com/custom?start=0&num=50&q='.urlencode($query).'&client=google-csbe&cx=AIzaSyD4gs8PDIPJ4ddbIhKGbcf-Hzyh1Fq6RkU');
	//echo $html;
	foreach($html->find('a.l') as $e){
		++$googlecounter;
	}//echo "counter 1 = ".$googlecounter1;
	for($i =0; $i <$googlecounter; $i++){

		$googleurl =$html->find('a.l');
		$googletitle= $html->find('a.l');
		$googlesnippet= $html->find('div.std');
		$googlearray [$i][0] = $googleurl[$i]->href;
		$googlearray [$i][1] = "4";
		$googlearray [$i][2] = "false";
		$googlearray [$i][3] = $googlesnippet[$i]->innertext;
		$googlearray [$i][4] = $googletitle[$i]->innertext;
	}
	//Clean dom to deal with the memory leak issue
	$html->clear();
	unset($html);
	return $googlearray ;

}
//print out the Google results
function google_print($googlearray){
	$googlecounter = sizeof($googlearray);
	//print out the google results
	for($i =0; $i <$googlecounter; $i++){
		echo  '<a href="'.$googlearray[$i][0].'">'.$googlearray [$i][4].'</a>'."<br/>";
		echo "<br/>".$googlearray [$i][3]."<br/>";
		echo  $googlearray[$i][0]."<br/>";
		echo "<br/>";
	}
	return $googlecounter;
}

//for for input for  query rewrite
function rewrite($query){

	$apikey = "K3M7Pz2YE8I6ovzxw0VF"; //  your own key
	$word = $query; // any word
	// replace the + sign with a space so we can break
	$encode4 = str_replace("+"," ", $query);

	// break them into individual tokens
	$explode_result1 = explode( " ",$encode4);

	//check its size
	$no3 = count($explode_result1);

	$language = "en_US"; // you can use: en_US, es_ES, de_DE, fr_FR, it_IT
	$endpoint = "http://thesaurus.altervista.org/thesaurus/v1";
	// set holder to | to avoid error messages
	$holder = "|";
	//loop the algorthym to rrtireve synonyms until all terms have synonums
	for($i = 0; $i <$no3; $i++){

		$word = $explode_result1[$i];

		//Now invoke the remote service using CURL library:

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "$endpoint?word=".urlencode($word)."&language=$language&key=$apikey&output=xml");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$data[$i] = curl_exec($ch);
		$info = curl_getinfo($ch);
		curl_close($ch);
s
		//echo $result;
		foreach ($result[$i]->list as $d) {
				
			$holder = $holder."|".$d->synonyms;
				
		}

	}

	//remove the phrase antonym
	$holder = str_replace(" (antonym)","", $holder);
	//remove duplicates from the string
	$holder = implode('|',array_unique(explode('|', $holder)));
	//remove | from the string
	$explode_result = explode( "|",$holder);
	//remove the empty first array field
	unset($explode_result[0]);
	// alphabetise the array
	sort($explode_result);
	return $explode_result;

}
//function automate query rewrite this allows for the random selection of query terms
function auto_query_rewrite(&$auto_no , $explode_result)
{

	//Check the size of the suggested queries and select a quater of them
	$auto_no = sizeof($explode_result);
	$auto_no=$auto_no/4;
	//if there are less than 4 words select at least one of them
	if ($auto_no <1){
		$auto_no = 1;
	}
	for ($i = 0; $i <$auto_no; $i++){
		$explode_result_other[$i] = $explode_result[$i];
	}
	//var_dump($explode_result_other);
	return $explode_result_other;

}

//filter the query so it can use BOOLEAN parameters
function filter_query($query) {

	//use string replace to allow our users to use AND as a Boolean search parameter
	//All of our search engines use OR so we do not need to edit our Query to facilitate its use
	//use string replace to allow our users to use NOT as a Boolean search parameter
	$bad = array("NOT ", "AND ", "'s", '"');
	$good = array("-", "+", "", "'");
	$query = str_replace($bad,$good,$query);

	return $query;
}
//the metasearch engine function
function metasearchengine($query,&$yahooarray, &$bingarray, &$blekkoarray, &$googlearray, &$finalarray,&$explode_result,&$no1, &$countyahoo, &$countbing, &$countblekko, &$non_aggregated_array, &$merge_array, &$kmean, &$length, &$explode_result_other, &$auto_no){


	//Filter the query for BOOlean interoperability

	$query = filter_query($query);
	$query  =  $query;


	// QUERY REWRITE assign the query term to the thesauraus to get sysnonyms back from function

	$explode_result=rewrite($query);
	$no1 =	count($explode_result);

	//if the user selects auto Query rewrite call the auto query function
	$explode_result_other = auto_query_rewrite($auto_no , $explode_result);


	//Google

	if(!$query == "" && $_GET['speedy1']== "on")
	{	//$googlearray = array();
		//populate the googlearray

		$googlearray  = google_scrape($query);

	}
	//Yahoo
	if($query != ""){
		//call the Yahoo api the OLD METHOD, YAHOO NO LONGER SUPPORTS THIS
		$datayahoo = yahoo_curl($query);
		//count the yahoo results
		$countyahoo = yahoo_count($datayahoo);
		//populate the array

		$yahooarray = yahoo_pop ($countyahoo, $datayahoo);
		//$yahooarray = yahoo_scrape($query, $countyahoo);

	}
	//Bing
	if($query != ""){
		//call function
		$children = bing_curl($query);
		//call function
		$countbing = bing_count($children);
		$bingarray = bing_pop($countbing,$children);
	}
	//BLEKKO
	if($query != ""){
		//call function
		$datablekko = blekko_curl($query);
		//call function
		$countblekko = blekko_count($datablekko);
		$blekkoarray = blekko_pop($countblekko, $datablekko);
	}
	//AGGREGATED
	//check if it has been requested
	if($query != "" && $_GET['sort'] == "Aggregated"){
		$finalarray = aggregation($yahooarray, $bingarray, $blekkoarray, $countyahoo, $countbing, $countblekko, $non_aggregated_array, $merge_array);
	}
	//CLUSTERING


	if($query != "" && $_GET['speedy1']== "off"){
		cluster($merge_array, $length, $kmean);
	}

	return $query;
	//-------------------------------END OF SEARCH ENGINE----------------------------------------------------------//
}
//sort function

function sksort(&$array, $subkey="id", $sort_ascending=false) {

	if (count($array))
	$temp_array[key($array)] = array_shift($array);

	foreach($array as $key => $val){
		$offset = 0;
		$found = false;
		foreach($temp_array as $tmp_key => $tmp_val)
		{
			if(!$found and strtolower($val[$subkey]) > strtolower($tmp_val[$subkey]))
			{
				$temp_array = array_merge(    (array)array_slice($temp_array,0,$offset),
				array($key => $val),
				array_slice($temp_array,$offset)
				);
				$found = true;
			}
			$offset++;
		}
		if(!$found) $temp_array = array_merge($temp_array, array($key => $val));
	}

	if ($sort_ascending) $array = array_reverse($temp_array);

	else $array = $temp_array;


}

//print out the results of the query
function print_query_evaluate(&$result_array)
{
	$count = sizeof($result_array);
	for ($i = 0; $i <$count; $i++){
		echo "<br/>The Precision for the Query ".$result_array[$i][0]." is ".$result_array[$i][1]."<br/>";
		echo "The Recall for the Query ".$result_array[$i][0]." is ".$result_array[$i][2]."<br/>";
		echo "The Average Precision for the Query ".$result_array[$i][0]." is ".$result_array[$i][3]."<br/>";
		echo "The F Measure/Harmonic mean for the Query ".$result_array[$i][0]." is ".$result_array[$i][4]."<br/>";
		echo "<br/>The NPrecision(where N = ".$result_array[$i][7].") for the Query ".$result_array[$i][0]." is ".$result_array[$i][5]." <br/>";
		echo "<br/>The rPrecision(where r = ".$result_array[$i][8].") for the Query ".$result_array[$i][0]." is ".$result_array[$i][6]." <br/>";
	}
}
// print out the avaegaes for the test results
//print out the final results for all the queries
function print_totalquery_evaluate($count_query, &$result_array, $set_r_count, $ncount){
	//results over the whole query set
	//var_dump($result_array);
	//MAP
	//initaise the averages to zero
	$average_precision = 0;
	$average_recall = 0;
	$average_harmonic_mean = 0;
	$avg_precision = 0;
	$avg_n_precision =0;
	$avg_r_precision =0;
	for ($k = 0; $k <$count_query; $k++){

		$average_precision = $average_precision + $result_array[$k][1];
		$average_recall  = $average_recall + $result_array[$k][2];
		$average_harmonic_mean  = $average_harmonic_mean + $result_array[$k][4];
		$avg_precision = $avg_precision + $result_array[$k][3];
		$avg_n_precision = $avg_n_precision  + $result_array[$k][5];
		$avg_r_precision = $avg_r_precision  + $result_array[$k][6];

	}
	//get the averages
	$average_precision = $average_precision/$count_query;
	$average__recall = $average_recall/$count_query;
	$average_harmonic_mean = $average_harmonic_mean/$count_query;
	$avg_n_precision = $avg_n_precision/$count_query;
	$avg_r_precision = $avg_r_precision/$count_query;
	$map = $avg_precision/$count_query;

	//populate an associative array
	$stats['Precision']=$average_precision;
	$stats['Recall']=$average__recall;
	$stats['Harmonic Mean']=$average_harmonic_mean;
	$stats['N-Precision']=$avg_n_precision;
	$stats['r-Precision']=$avg_r_precision;
	$stats['MAP']=$map ;

	//var_dump($stats);

	echo " The average precision  result for our ".$count_query. " results is ".$average_precision."<br>";
	echo " The average recall  result for our ".$count_query. " results is ".$average_recall/$count_query."<br>";
	echo " The average F Measure/Harmonic Mean result for our ".$count_query. " results is ".$average_harmonic_mean."<br>";
	echo " The average N Precision (where N = ".$ncount.") result for our ".$count_query. " results is ".$avg_n_precision."<br>";
	echo " The average R Precision (where r = ".$set_r_count.") result for our ".$count_query. " results is ".$avg_r_precision."<br>";
	echo " The MAP result for our ".$count_query. " results is ".$map."<br>";

	return $stats;
}

//function to remove duplicates from a Multidimensional array
function super_unique($array)
{
	$result = array_map("unserialize", array_unique(array_map("serialize", $array)));

	foreach ($result as $key => $value)
	{
		if ( is_array($value) )
		{
			$result[$key] = super_unique($value);
		}
	}

	return $result;
}
//function to create the refined snippets by removing tags, punctuation and stopwords and stemming the remaining terms
function snippet_polish($length, &$merge_array){

	//information arrays
	$stop_words= array("a", "about", "above", "across", "after", "again", "against", "all", "almost", "alone", "along", "already", "also", "although", "always", "among", "an", "and", "another", "any", "anybody", "anyone", "anything", "anywhere", "are", "area", "areas", "around", "as", "ask", "asked", "asking", "asks", "at", "away", "b", "back", "backed", "backing", "backs", "be", "became", "because", "become", "becomes", "been", "before", "began", "behind", "being", "beings", "best", "better", "between", "big", "both", "but", "by", "c", "came", "can", "cannot", "case", "cases", "certain", "certainly", "clear", "clearly", "come", "could", "d", "did", "differ", "different", "differently", "do", "does", "done", "down", "down", "downed", "downing", "downs", "during", "e", "each", "early", "either", "end", "ended", "ending", "ends", "enough", "even", "evenly", "ever", "every", "everybody", "everyone", "everything", "everywhere", "f", "face", "faces", "fact", "facts", "far", "felt", "few", "find", "finds", "first", "for", "four", "from", "full", "fully", "further", "furthered", "furthering", "furthers", "g", "gave", "general", "generally", "get", "gets", "give", "given", "gives", "go", "going", "good", "goods", "got", "great", "greater", "greatest", "group", "grouped", "grouping", "groups", "h", "had", "has", "have", "having", "he", "her", "here", "herself", "high", "high", "high", "higher", "highest", "him", "himself", "his", "how", "however", "i", "if", "important", "in", "interest", "interested", "interesting", "interests", "into",
  "is", "it", "its", "itself", "j", "just", "k", "keep", "keeps", "kind", "knew", "know", "known", "knows", "l", "large", "largely", "last", "later", "latest", "least", "less", "let", "lets", "like", "likely", "long", "longer", "longest", "m", "made", "make", "making", "man", "many", "may", "me", "member", "members", "men", "might", "more", "most", "mostly", "mr", "mrs", "much", "must", "my", "myself", "n", "necessary", "need", "needed", "needing", "needs", "never", "new", "new", "newer", "newest", "next", "no", "nobody", "non", "noone", "not", "nothing", "now", "nowhere", "number", "numbers", "o", "of", "off", "often", "old", "older", "oldest", "on", "once", "one", "only", "open", "opened", "opening", "opens", "or", "order", "ordered", "ordering", "orders", "other", "others", "our", "out", "over", "p", "part", "parted", "parting", "parts", "per", "perhaps", "place", "places", "point", "pointed", "pointing", "points", "possible", "present", "presented", "presenting", "presents", "problem", "problems", "put", "puts", "q", "quite", "r", "rather", "really", "right", "right", "room", "rooms", "s", "said", "same", "saw", "say", "says", "second", "seconds", "see", "seem", "seemed", "seeming", "seems", "sees", "several", "shall", "she", "should", "show", "showed", "showing", "shows", "side", "sides", "since", "small", "smaller", "smallest", "so", "some", "somebody", "someone", "something", "somewhere", "state", "states", "still", "still", "such", "sure", "t", "take", "taken", "than", "that", "the", "their", "them", "then", "there", "therefore", "these", "they", "thing", "things", "think", "thinks", "this", "those", "though", "thought", "thoughts", "three", "through", "thus", "to", "today", "together", "too", "took", "toward", "turn", "turned", "turning", "turns", "two", "u", "under", "until", "up", "upon", "us", "use", "used", "uses", "v", "very", "w", "want", "wanted", "wanting", "wants", "was", "way", "ways", "we", "well", "wells", "went", "were", "what", "when", "where", "whether", "which", "while", "who", "whole", "whose", "why", "will", "with", "within", "without", "work", "worked", "working", "works", "would", "x", "y", "year", "years", "yet", "you", "young", "younger", "youngest", "your", "yours", "z",);
	$good = array("", "","", "", "", "", "", "", "", "", " ", "zero", "one","two","three","four","five","six", "seven","eight", "nine");
	$bad = array(".", ",","...","-","(",")","!","|",":", ";", "  ", "0", "1", "2", "3","4", "5","6","7","8","9");


	for($i = 1; $i <$length; $i++){
		//concatanate the title and the snippet so we have access to more words ans store elswehwere in the array
		$merge_array [$i][5] = $merge_array [$i][3].$merge_array [$i][4];

		//strip the html tags
		$merge_array [$i][5] = strip_tags($merge_array [$i][5]);
		//remove the punctuation
		$merge_array [$i][5] = str_replace($bad, $good, $merge_array [$i][5]);
		//convert to lowercase
		$merge_array [$i][5] = strtolower ($merge_array [$i][5]);

		//disassemle the string and stem the words
		$words = explode(" ", $merge_array [$i][5]);
		foreach($words as $word) {
			$stem = porterstemmer::Stem($word);
			/* Remove stop words */
			if(!in_array($stem, $stop_words)) {
				$stem_words[] = $stem;
			}
		}
		//reassemble the string of the stemmed words
		$merge_array [$i][5] = implode(" ",$stem_words);
		//unset the variable so it is "clean for the next snippet
		unset($stem_words);

	}

}
//function to slect the best snippet for each document and return a unique list, the "best" snippet is the longest
function snippet_arrange($length, &$merge_array){
	//make a duplicate of the array
	$merge_arrayholder = $merge_array;

	//var_dump($merge_array);
	//for each document check if the duplicates of it have a better snippet the criteria is length
	for($i = 0;$i<$length;$i++){
		for($j = 0;$j<$length;$j++){
			//check if the urls are identical
			if ( $merge_array [$i][0] == $merge_arrayholder[$j][0])
			{
				$snippetlength1 = strlen($merge_array[$i][5]);//get the length of the snippet

				$snippetlength2 = strlen($merge_arrayholder[$j][5]);//get the length of the snippet

				if ($snippetlength1<$snippetlength2){
					//unset($merge_arrayholder[$j]);
					$merge_array[$i][5] = $merge_arrayholder[$j][5];
						
				}
			}
		}
	}

	//reset the arrays so thare identical again
	$merge_arrayholder = $merge_array;
	//var_dump($merge_array);

	//var_dump($merge_arrayholder);


	for($i = 0;$i<$length;$i++){
		for($j = 0;$j<$length;$j++){
			//$key = array_search($merge_array[$i][0], $merge_arrayholder);

			//if the array key has already been unset it skips ahead
			if(array_key_exists($j, $merge_arrayholder) && array_key_exists($i, $merge_array)) {
				if ( $merge_array[$i][0]== $merge_arrayholder[$j][0] && $i != $j){

					//echo "this is first url ".$merge_array[$i][0]." from ".$i." with ".$merge_arrayholder[$j][0]." from".$j."<br/>";
					//unset the duplicate in both arrays so we dont have a double removal of URLS
					unset($merge_arrayholder[$j]);
					unset($merge_array[$j]);
					//echo "test <br/>";
				}
			}
		}
	}


	$merge_array = array_values($merge_arrayholder);
	//var_dump($merge_array);
	$length = sizeof($merge_array);
	return $length;
}

//function to get Repetitions in an array

function getRepetitions($value, array $values){
	$length = count($values);
	if($length==0)
	return FALSE;
	$repetitions = 0;
	foreach($values as $v){
		if($v==$value)
		$repetitions++;
	}
	return $repetitions;
}

//function to get the score for each unique word in the collection of all snippets from all documents
function snip_unique($length,&$merge_array, &$score ){
	//keep score of number of times a word appears
	$score = array();

	$count = 0;
	for($i = 0;$i<$length;$i++)	{
		//echo $merge_array [$i][5];
		$words = explode(" ", $merge_array [$i][5]);
		//we only count the unique oocurences of the words in the snipper so remove all duplactes
		$words = array_unique($words);
		//$words = array_values($words);
		//echo sizeof($words)."<br/>";

		foreach($words as $word) {
			if ( array_key_exists($word, $score)){
				//COUNT IF THE WORD REoccurs
				$count = $score[$word];
				$score[$word] = $count + 1;
			}
			else {
				//RECORD THE WORD FOR THE FIRST TIME
				//START THE COUNT
				$score[$word] = 1;

			}
		}
	}

}
//function to cycle through each snippet and give it its individual score
function snippet_vectorise($length,&$merge_array, $score){

	$weight= array();
	//declare total
	$total = 0;

	//give a weighting to each word in a snippet
	for($i = 0;$i<$length;$i++){

		$words = explode(" ", $merge_array [$i][5]);
		//echo $merge_array [$i][5]."<br/>";
		foreach($words as $word){
			//find out how may times the word appears in the snippet
			$freq = getRepetitions($word, $words);
			//echo  " <br/>". $word." freq = ".$freq." <br/>";

			//give that word it apprpriate weighting
			$weight[$word] = $freq *log(($length/$score[$word]) ) ;
			//echo $word . " is ".$weight[$word]." <br/>";


		}


		//intialse the array that will hold our vector
		$vectorholder = array();
		//$vectorholder[] = 0.00;
		//var_dump($vectorholder);
		//generate the vector
		$words = array_unique($words);
		$words = array_values($words);
		//print_r($words);
		//print_r($score);
		//print_r($weight);
		//echo "<br/><br/>";
		foreach($score as $key => $value) {

			//echo $key." key was updated<br/>";
			//if the word from our score array is in the snippet give it its appropriate weight as a coord
			if(in_array($key,$words)){
				//if the word is present in the snuppet add its frequency as the vector address point
				//echo $weight[$key]." <br/>";
					
				$vectorholder[] = $weight[$key];
				//echo $weight[$key]." weight here for ".$key."<br/>" ;
					
				//var_dump($vectorholder);
				//echo $vectorholder."word was updated<br/>";
			}else{
				$vectorholder[] = 0.00;
				//echo " zero was for " .$key." <br/>" ;
			}

		}

		$merge_array[$i][1] = $vectorholder;

	}
}
//function to calculate te magnitude of a snippet
function magnitude($length,$size1, &$merge_array)
{
	//echo "****************cluster magnitude alogorythm********************<br/>";
	$magnitude2 = 0;
	$holder2 = 0 ;
	$dot_product1 = 0;
	$dot_product2 = 0;
	for($j = 0;$j<$length;$j++){//check the intial centroids distance from each other document
		for($i = 0;$i<$size1;$i++){//loop the not starting clusters
			if($merge_array[$j][1][$i] != 0){//if the vector coord is zero we can skipp by it
				$holder2 = $merge_array[$j][1][$i]*$merge_array[$j][1][$i];//square the coords
				$magnitude2 = $magnitude2 + $holder2;//get the total for the co0rds
				//echo $magnitude2." check <br/>";
			}
		}
		$magnitude2 = sqrt($magnitude2);
		//echo $magnitude2." ".$j." equals here <br/>";
		$merge_array[$j][6] = $magnitude2;
	}

	//var_dump($merge_array);
}

//function to calculate the centroid for clusttering
function cluster_centroid($length,$size1,$kmean, &$merge_array, $centroid){
	//calculate the centroids of a cluster
	//inialis ethe cluster arrays
	$centroid =array();
	for($k = 0;$k<$kmean;$k++){
		for($i = 0;$i<$size1;$i++){
			$centroid[$k][$i] = 0;
		}
	}
	//declare the variable
	$cluster_no = 0;

	//depending on their existing cluster us ethe coordinates of the documents to calculate the new centoid

	for($i = 0;$i<$size1;$i++){//loop for each coordinate

		for($j = 0;$j<$length;$j++){//loop for each document in thecluster
				
			for($k = 0;$k<$kmean;$k++){//loop for each cluster
				$cluster_no = $k + 1;
				if($merge_array[$j][7] == $cluster_no){// it has alreay been assigned to cluster
					$centroid[$k][$i]  =  $centroid[$k][$i] + $merge_array[$j][1][$i];// add each individual coordinate
				}
				$centroid[$k][$i] = $centroid[$k][$i]/$size1;//divide by the number of coordinates to gte the centroid coordinate

			}}
	}

	return $centroid;

}
//function to get the magnitude of a vector
function vector_magnitude($size1,$kmean, &$centroid){
	//echo "****************call magnitude alogorythm********************<br/>";
	$holder2 = 0;
	$magnitude1= 0;
	$magnitude2 = array();
	for($k = 0;$k<$kmean;$k++){//loop for each cluster
		$magnitude2[$k] = 0;
	}

	for($k = 0;$k<$kmean;$k++){//loop for each cluster
			
		for($i = 0;$i<$size1;$i++){//loop the not starting clusters
			//echo $centroid[$k][$i]." 1<br/>";
			$holder2 = $centroid[$k][$i]*$centroid[$k][$i];//square the coords
			//echo $holder2." 2<br/>";
			$magnitude1 = $magnitude1 + $holder2;//get the total for the co0rds
				
		}
		$magnitude2[$k] = sqrt($magnitude1);
		//echo $magnitude2[$k]." at ".$k." mag<br/>";
		//reset the holders

		$magnitude1= 0;


	}

	//var_dump($magnitude2);
	//record the magnitude of centroid
	$cluster_mag = $magnitude2;
	return  $cluster_mag;
}

//function to assign clusters to documents
function doc_cluster($centroid,$cluster_mag, $length,$kmean,$size1, &$merge_array)
{
	//echo "****************call cluster alogorythm********************<br/>";


	$magnitude2 = 0;
	$holder2 = 0 ;
	$dot_product1 = 0;
	$dot_product2 = 0;
	//we now need to assign each document to the cluster that it is closest to
	// check the distance between each document and the centroids
	for($j = 0;$j<$length;$j++){//loop for each document
		for($k = 0;$k<$kmean;$k++){//loop for each cluster
			for($i = 0;$i<$size1;$i++){//loop for each cordinate in the vector
				//check that it isnt already a centroid
				if($merge_array[$j][1][$i] != 0 && $centroid[$k] != 0)
				{
					$dot_product1 = $centroid[$k][$i]*$merge_array[$j][1][$i];
					//echo $dot_product1." is  dot product<br/>";
					$dot_product2 =  $dot_product2 + $dot_product1;
					//echo $dot_product2." is  dot product2<br/>";
				}
			}
			//get the distance between the two documents
			$distance[$k] = $dot_product2/($cluster_mag[$k]*$merge_array[$j][6]);
			$distance[$k] = 1 - $distance[$k];
				
			//reset the dot products to 0
				
			$dot_product2 = 0;
			//echo $k." is ".$distance[$k]." distance<br/>";

		}

		//check which has the smallest distance and its loaction in the array to decide which cluster it is assigned to
		$smallest = array_search(min($distance), $distance) + 1;

		//record the cluster number for each document
		$merge_array[$j][7] = $smallest;

	}//echo " ****************end of ****************************<br/>";

}
//function to print out our clustered results
function cluster_print($kmean, $length,&$merge_array){
	//var_dump($merge_array);
	//print you your clusters
	//set so that it starts count at 1 rather than 0
	$kmean = $kmean + 1;
	for($k = 1;$k<$kmean;$k++){

		echo "CLUSTER ".$k.'<br/>';

		for($j = 0;$j<$length;$j++){
			//echo $merge_array[$j][7]." is the cluster <br/>";
			if($merge_array[$j][7] == $k){
				echo  '<a href="'.$merge_array[$j][0].'">'.$merge_array[$j][4].'</a>'."<br/>";
				echo "<br/>";
				echo $merge_array[$j][3].'<br/>';
				echo "<br/>";
				echo  $merge_array[$j][0]."<br/>";
				echo "<br/>";
			}
		}
	}

}
//function to print out our clustered results in a paginated fashion
function cluster_print_pag($kmean, $length,&$merge_array){
	// Create the pagination object
	$pagination2 = new pagination;
	//sort the merge array in accending order of cluster
	sksort($merge_array, "7", $sort_ascending=true);


	// If we have an array with items
	if (count($merge_array)) {
		// Parse through the pagination class
		$holderpages = $pagination2->generate($merge_array, 150);


		// If we have items
		if (count($holderpages) != 0) {

			// Loop through all the items in the array

			$kmean = $kmean + 1;
			for($j = 1;$j<$kmean;$j++){
				echo "************************************************<br/>";
				echo "**************Cluster no ".$j."*****************<br/>";
				$counter = 0;

				foreach ($holderpages as $productID => $holder_array) {
					// Show the information about the item

					if($holder_array[7] == $j ){// if the document is part of the cluster print it
							
						//resort merge array by acceendin
						echo "Cluster no ".$holder_array[7]." <br/>";
						echo  '<a href="'.$holder_array[0].'">'.$holder_array[4].'</a>'."<br/>";
						echo "<br/>";
						echo $holder_array[3].'<br/>';
						echo "<br/>";
						echo  $holder_array[0]."<br/>";
						echo "<br/>";
						$counter++;
						//echo $counter." counter increment<br/>";

					}
				}
			}
			// Create the page numbers
			echo $pageNumbers = '<div>'.$pagination2->links().'</div>';
		}
	}
}

//function to cluster results
function cluster(&$merge_array, &$length, &$kmean){

	//length of the array
	$length = sizeof($merge_array);

	//things to replace in the snippert/title using the polish function//REMOVE ANY DUPLCATES
	snippet_polish($length,$merge_array);

	//rearrange the snippets
	$length = snippet_arrange($length, $merge_array);

	//reindex the array to get rid of the unset variables
	$merge_array = array_values($merge_array);
	$length = sizeof($merge_array);

	// call the function to score the unique Words in each snippet
	snip_unique($length, $merge_array, $score );

	//function to cycle through each snippet and give it its individual score and vectorise it
	snippet_vectorise($length,$merge_array, $score);

	//var_dump($merge_array);

	//kmeans
	//let K = the number of cluster you want
	$kmean = 5;
	//assign the first 3 documents as your starting centroids
	for($j = 0;$j<$kmean;$j++){
		$merge_array[$j][7] = $j + 1;//assign the cluster name to the document, the first is cluster 1
		//echo $merge_array[$j][7]."<br/>";
	}

	//Intialise the
	$magnitude2 = 0;
	$holder2 = 0 ;
	$dot_product1 = 0;
	$dot_product2 = 0;
	$cluster_mag = array() ;
	$centroid = array();

	//check the size of the vectors
	$size1 = sizeof($merge_array[0][1]);
	//echo $size1."<br/>";

	//echo $merge_array[0][1][0]."<br/>";

	//call the function to get the magnitude of each snippet
	magnitude($length, $size1, $merge_array);

	//var_dump($merge_array);

	//add the first 3 documents as centroids and select their magnitudes
	for($k = 0;$k<$kmean;$k++){
		//echo $merge_array[$k][6]." merge array<br/>";
		$cluster_mag[$k] = $merge_array[$k][6];
		$centroid[$k] = $merge_array[$k][1];
	}


	//we now need to assign each document to the cluster that it is closest too
	// check the distance between each document and the centroids
	doc_cluster($centroid,$cluster_mag, $length,$kmean,$size1, $merge_array);

	//loop 5 times to get convergence
	for($k = 0;$k<3;$k++){
		//call the function to work out the centre of the cluster
		$centroid = cluster_centroid($length,$size1,$kmean, $merge_array, $centroid);

		//call the function to  get the magnitude of the centroid
		$cluster_mag = vector_magnitude($size1,$kmean,$centroid);

		//call the function to cluster the documents around the centroid
		doc_cluster($centroid,$cluster_mag, $length,$kmean,$size1, $merge_array);
	}

}
//turn an array to xml
function assocArrayToXML($root_element_name,$ar)
{
	$xml = new SimpleXMLElement("<?xml version=\"1.0\"?><{$root_element_name}></{$root_element_name}>");
	$f = create_function('$f,$c,$a','
            foreach($a as $k=>$v) {
                if(is_array($v)) {
                    $ch=$c->addChild($k);
                    $f($f,$ch,$v);
                } else {
                    $c->addChild($k,$v);
                }
            }');
	$f($f,$xml,$ar);
	return $xml->asXML();
}

?>

