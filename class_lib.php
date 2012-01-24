<?php
// this libraray contains the classes we use for the project
// the first of these is the Testengine Class used for testing our individual queries
//the second is a bespoke pagination class
// http://csserver.ucd.ie/~98110098/testengine.php

//Our test Engine class
class testengine {

	//private
	private $testquery;
	private $googleurl;
	private $compareurl;
	private $resultarray;
	private $gsize;
	private $csize;
	//constructor methods
	function __construct($testengine_name, $new_googleurl, $new_compareurl)

	{
		$this->testquery = $testengine_name;//constructor for Query
		$this->googleurl = $new_googleurl;//constructor for Google List that we will use as our gold Standard
		$this->compareurl = $new_compareurl;//constructor for Liist we will be comparing to Google
		$this->resultarray[0] = $testengine_name;//constructor for name of the name

		//size of the compare array
		$this->csize = sizeof($this->compareurl);
		//size of google array
		$this->gsize = sizeof($this->googleurl);
	}

	//setter and getter methods for result array
	function get_resultarray()
	{
		return $this->resultarray;
	}

	//method for average precison
	function avg_precision(){
		//intialise the variables
		$count_precision = 0;
		$avg_precision = 0;

		for ($k = 0; $k <$this->csize; $k++){// loop through all URLs
			for ($j = 0; $j <$this->gsize; $j++){
				if ($this->compareurl[$k][0]== $this->googleurl[$j][0])
				{
					++$count_precision;
					$avg_precision = $avg_precision + ($count_precision/($k+1));// first rank is $k + 1 so it starts at one not zero
					$j =$this->gsize;
					
				}
			}
		}
		//store our intial calculation precision, Recall, Avg Precision Harmonic Mean
		$this->resultarray[1] = (($count_precision/$this->csize));//precision
		$this->resultarray[2]= (($count_precision/$this->gsize));//recall
		$this->resultarray[3]  = (($avg_precision/$count_precision));//avg precision
		$this->resultarray[4] = (2*($this->resultarray[1])*($this->resultarray[2]))/(($this->resultarray[1])+($this->resultarray[2]));//harmonic mean or F measure

	}
	//method to derive the Precion at N
	function nprecision($ncount){
		$this->resultarray[7]= $ncount;// store the N Count for later use, it defaults to 10 on our interface
		$n_precision = 0;
		for ($k = 0; $k <$ncount; $k++){
			for ($j = 0; $j <$this->gsize; $j++){
				if ($this->compareurl[$k][0]== $this->googleurl[$j][0])
				{
					++$n_precision ;
					$j =$this->gsize;
					
				}
			}
		}$n_precision = (($n_precision/$ncount));//divide by the count to get out metric
		//store our result
		$this->resultarray[5] = $n_precision;


	}
//method to Work out the precision at r
	function rprecision($set_r_count){
		$this->resultarray[8]= $set_r_count;// store the r Count for later use, it defaults to .3 on our interface
	
		$rcount =($set_r_count)*(($this->resultarray[2])*($this->gsize));// configure the size of the loop we need for the r count
		
		$r_precision = 0;
		for ($k = 0; $k <$rcount; $k++){// count results untill we have reached out desired recall
			for ($j = 0; $j <$this->gsize; $j++){
				if ($this->compareurl[$k][0]== $this->googleurl[$j][0])
				{
					++$r_precision;
					$j =$this->gsize;
		
				}
			}
		}
		$r_precision = (($r_precision/$rcount));//divide by the count to get out metric
		//store our result
		$this->resultarray[6] = $r_precision;

	}
	//method to print out our results
	function print_query_result()
	{
		echo "<br/>The Precision for the Query ".$this->resultarray[0]." is ".$this->resultarray[1]."<br/>";
		echo "The Recall for the Query ".$this->resultarray[0]." is ".$this->resultarray[2]."<br/>";
		echo "The Average Precision for the Query ".$this->resultarray[0]." is ".$this->resultarray[3]."<br/>";
		echo "The F Measure/Harmonic mean for the Query ".$this->resultarray[0]." is ".$this->resultarray[4]."<br/>";
		echo "The NPrecision(where N = ".$this->resultarray[7].") for the Query ".$this->resultarray[0]." is ".$this->resultarray[5]." <br/>";
		echo "The rPrecision(where r = ".$this->resultarray[8].") for the Query ".$this->resultarray[0]." is ".$this->resultarray[6]." <br/>";
	}

}

/************************************************************\
 *
 *	  PHP Array Pagination Copyright 2007 - Derek Harvey
 *	  www.lotsofcode.com
 *
 *	  This file is part of PHP Array Pagination .
 *
 *	  PHP Array Pagination is free software; you can redistribute it and/or modify
 *	  it under the terms of the GNU General Public License as published by
 *	  the Free Software Foundation; either version 2 of the License, or
 *	  (at your option) any later version.
 *
 *	  PHP Array Pagination is distributed in the hope that it will be useful,
 *	  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *	  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.	See the
 *	  GNU General Public License for more details.
 *
 *	  You should have received a copy of the GNU General Public License
 *	  along with PHP Array Pagination ; if not, write to the Free Software
 *	  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA	02111-1307	USA
 *
 \************************************************************/

class pagination
{
	var $page = 1; // Current Page
	var $perPage = 10; // Items on each page, defaulted to 10
	var $showFirstAndLast = false; // if you would like the first and last page options.

	function generate($array, $perPage = 10)
	{
		// Assign the items per page variable
		if (!empty($perPage))
		$this->perPage = $perPage;

		// Assign the page variable
		if (!empty($_GET['page'])) {
			$this->page = $_GET['page']; // using the get method
		} else {
			$this->page = 1; // if we don't have a page number then assume we are on the first page
		}

		// Take the length of the array
		$this->length = count($array);

		// Get the number of pages
		$this->pages = ceil($this->length / $this->perPage);

		// Calculate the starting point
		$this->start  = ceil(($this->page - 1) * $this->perPage);

		// Return the part of the array we have requested
		return array_slice($array, $this->start, $this->perPage);
	}

	function links()
	{
		// Initiate the links array
		$plinks = array();
		$links = array();
		$slinks = array();

		// Concatenate the get variables to add to the page numbering string
		if (count($_GET)) {
			$queryURL = '';
			foreach ($_GET as $key => $value) {
				if ($key != 'page') {
					$queryURL .= '&'.$key.'='.$value;
				}
			}
		}

		// If we have more then one pages
		if (($this->pages) > 1)
		{
			// Assign the 'previous page' link into the array if we are not on the first page
			if ($this->page != 1) {
				if ($this->showFirstAndLast) {
					$plinks[] = ' <a href="?page=1'.$queryURL.'">&laquo;&laquo; First </a> ';
				}
				$plinks[] = ' <a href="?page='.($this->page - 1).$queryURL.'">&laquo; Prev</a> ';
			}

			// Assign all the page numbers & links to the array
			for ($j = 1; $j < ($this->pages + 1); $j++) {
				if ($this->page == $j) {
					$links[] = ' <a class="selected">'.$j.'</a> '; // If we are on the same page as the current item
				} else {
					$links[] = ' <a href="?page='.$j.$queryURL.'">'.$j.'</a> '; // add the link to the array
				}
			}

			// Assign the 'next page' if we are not on the last page
			if ($this->page < $this->pages) {
				$slinks[] = ' <a href="?page='.($this->page + 1).$queryURL.'"> Next &raquo; </a> ';
				if ($this->showFirstAndLast) {
					$slinks[] = ' <a href="?page='.($this->pages).$queryURL.'"> Last &raquo;&raquo; </a> ';
				}
			}

			// Push the array into a string using any some glue
			return implode(' ', $plinks).implode($this->implodeBy, $links).implode(' ', $slinks);
		}
		return;
	}
}

?>