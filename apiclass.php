<?php 
/**
 * A class to parse a search and search the daft Api
 *
 * PHP version 5.3.4
 *
 * LICENSE: This source file is subject to version 3.01 of the PHP license
 * that is available through the world-wide-web at the following URI:
 * http://www.php.net/license/3_01.txt.  If you did not receive a copy of
 * the PHP License and are unable to obtain it through the web, please
 * send a note to license@php.net so we can mail you a copy immediately.
 *
 * @category  CategoryName
 * @package   Dafthomeversion
 * @author    Brian Ward <Brian.g.ward@gmail.com>
 * @copyright 1997-2005 The PHP Group
 * @license   http://www.php.net/license/3_01.txt  PHP License 3.01
 * @link      {@link URL}  
 */

/**
 *Class doc comment
 *A class to parse a search and search the daft Api
 *
 * @category  CategoryName
 * @package   Dafthomeversion
 * @author    Brian Ward <Brian.g.ward@gmail.com>
 * @copyright 1997-2005 The PHP Group
 * @license   http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version   Release: @package_version@
 * @link      http://pear.php.net/package/PackageName
 */
class ApiInterface
{
    //Class variables
    var $_API = '11aab89709f590f08be455861ad5fc679e4d5add';//the api key
    var $_URL_API = "http://api.daft.ie/v1/wsdl.xml";//the url for the daft api
    var $_DaftAPI;//soap client
    var $_Search_Type = "";// whether the search is for a sale or rental
    var $_Bedroom_Number = 1;//the number of bedrooms
    var $_Price_Max = 1000000000;// the maximum price for a property
    var $_Price_Min = 1;//the minimum price for a property
    var $_Area = "";//the area that the property 
    var $_Search_Array;// the array of the search terms
    var $_Query;
    var $_Parameters;//parameters derived from parsing, used to search Daft
    var $_Results;
    var $_Search_String = "";//a string of the query terms
    //synonyms for renting properties to check against the query string
    var $_Rental_Array = array('1' => 'Rental', '2' => 'Let', '3' => 'Rent',);
    //synonyms for properties on saleto check against the query string
    var $_Sale_Array = array('1' => 'Sale', '2' => 'Buy',);
    //array for number of rooms
    var $_Number_Array = array('1' => '1', '2' => '2', '3' => '3', '4' => '4',
            '5' => '5', '6' => '6', '7' => '7', '8' => '8',);

    /**
     *Constructs the Object of ApiInterface Class
     *
     *@param array $query the input from the search box
     */
    function apiInterface($query)
    {
        //set all words in the search string with a capital letter first
        $this ->_Search_String = ucwords($query);
        //create the soap client
        $this->_DaftAPI = new SoapClient(
            "http://api.daft.ie/v2/wsdl.xml",
            array('features' => SOAP_SINGLE_ELEMENT_ARRAYS)
        );
    }

    /**
    * Parses to check whether the search is for rental or sales
    * 
    * @return string whether its dale or rental
    */
    function parseSearchType()
    {

        //explode the string
        $this->_Search_Array = explode(" ", $this->_Search_String);

        //Check for rental or sale
        foreach ($this->_Rental_Array as $str) {
            if (in_array($str, $this->_Search_Array)) {
                //sets for rental search
                $this->_Search_Type = 'search_rental';
            }
        }
        foreach ($this->_Sale_Array as $str) {
            if (in_array($str, $this->_Search_Array)) {
                //sets for sales search
                $this->_Search_Type = 'search_sale';
            }
        }
    }
    /**
     * Parses to get the number of rooms required
     * 
     * @return int the number of rooms
     */
    function parseNumberOfRooms()
    {
        //check  for numbers less than 8, this should be the number of rooms
        foreach ($this->_Number_Array as $No) {
            if (in_array($No, $this->_Search_Array)) {
                //set the number of rooms
                $this->_Bedroom_Number = $No;
            }
        } 
    }
    /**
     * Parses to get the price the user has stipulated
     * 
     * @return int the Maximum price
     * @return int the minimum price
     */
    function parsePrice()
    {
        $price_array = array( 1 => 1 );
        foreach ($this->_Search_Array as $No) {
            //if there is a number greater than 100 we assume this is the price.
            if ($No >= 100) {
                //we add these numbers to the price array
                $price_array[] = $No;
            }
        }
        if (!isset($pricearray)) {
            //set the max and minimum prices from the array
            $this->_Price_Max = max($price_array);
            $this->_Price_Min = min($price_array);
        }
    }
    /**
     * parses the location required by the query
     * 
     * @return string the area
     */
    function parseLocation()
    {
        //this gives a list of all possible valid locations in the daft api
        $parameters = array('api_key' => $this->_API, 'area_type' => "area");
        $arrayAreas = $this->_DaftAPI->areas($parameters);
        $holder = array();
        //populates an array of vaid areas
        foreach ($arrayAreas->areas as $area) {
            array_push($holder, $area->name);
        }   
        //Check for a valid area in the query
        foreach ($holder as $str) {
            if (in_array($str, $this->_Search_Array)) {
                 $this->_Area = $str;
                print $str;
            }
        }
    }
    /**
     * Prints out the result of all the parses
     * Also prints out the urls returned by the api
     * 
     * @return string the search type
     * @return int the number of rooms
     * @return int the max price
     *  
     *  
     */
    function printResults()
    {

        //print out the requested information as per instructions
        print  "The Search type requested is : " . $this->_Search_Type."</br>";
        print "The Number of bedrooms is : " . $this->_Bedroom_Number . "</br>";
        print "The max price is : " . $this->_Price_Max . "</br>";
        print "The minimum price is : " . $this->_Price_Min . "</br>";
        print "The desired area is : " . $this->_Area . "</br>";
        //print out the url
        print "</br>Your results are :</br>"; 
        //check that results exist    
        if (isset($this->_Results)) {
            foreach ($this->_Results->ads as $ad) {
                print "</br>";
                printf('<a href="%s">%s</a><br />', $ad->daft_url, $ad->full_address);
            }
        } else {
            print "Sorry we have no results for that query";
        }       
    }
    /**
     * Generates the parametrs for querying the daft api
     * 
     * @return array the array of parameters
     */
    function generateQueryParameters()
    {
        // Populate query variable
        $this->_Query = array(
                'area' => $this->_Area,
              'bedrooms' => $this->_Bedroom_Number,
                'price' => $this->_Price_Max,             
        ); 
        //set the parameters for our search using the query we have generated
        $this->_Parameters = array('api_key' => $this->_API,
                'query' => $this->_Query);
    }
    /**
     * Coonect to the Daft api
     * 
     * @return array the results from the api
     */
    function connect()
    {
    
        //add the search type to the holdervariable  to generate our search type.
        $holderForSearchType = $this->_Search_Type;
        //var_dump($holderForSearchType);
        //var_dump($this->_DaftAPI);
        //var_dump($this->_Parameters);
     
        //generate the  response using information in class variables
        $response = $this->_DaftAPI->$holderForSearchType($this->_Parameters);
        //var_dump($response);
        
        //generate teh results array
        $this->_Results = $response->results;
        
    }
}

?>