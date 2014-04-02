<?php
	$page = $_GET["page"];

	print "\n<vxml version = \"2.1\" > \n  <property name=\"inputmodes\" value=\"dtmf\" />  <form id=\"menu1\">\n <field name=\"section\"> \n<prompt bargein=\"true\">\n";
	print "You have chosen ";
	print $page;
	print ". Which section would you like to read?";
	print "\n<enumerate>";
	print "\nFor <value expr=\"_prompt\"/>, press <value expr=\"_dtmf\"/>.";
	print "\n</enumerate> \n </prompt>";

	$url = 'http://en.wikipedia.org/wiki/' .$page;	
	$html = new DOMDocument();
	$html->loadHTMLFile($url);
	$i = 1;

	foreach($html->getElementsByTagName('h2') as $section) {  
		$sectionName = $section->nodeValue;
		print "\n<option dtmf=\"" . $i . "\" value=\"". $sectionName . "\">". $sectionName . "</option>";
		$i++;
    	} 
	print "\n<noinput>Please enter a number.<reprompt/></noinput>";      
  	print "\n<nomatch>This is no option. Try again.<reprompt/></nomatch>";
	print "\n</field>";
        print "\n<filled>";
	print "\n<submit next=\"wikipedia-section.php\" namelist=\"section\"/>";
	print "\n</filled> \n </form> \n </vxml>";
                
         
  
 



	
	//$result = file_get_contents($url);
	//$result1 = strip_tags($result, '<h2>,<p>');
	//echo $result1;

	//$xml2 = new DOMDocument();
	//$xml2->loadHTMLFile($url);

	

	//create replacement
	//$replacement  = $xml1->createDocumentFragment();
	//$replacement  ->appendXML('<p></p>');

	//make replacement
	//$xp = new DOMXPath($xml1);
	//$entries = $xp->query('//li');
	//foreach($entries as $entry) {
	//	$entry->parentNode->replaceChild($replacement  , $entry);
	//	$new_html = $xml1->saveXml($xml1->documentElement);
	//}

	//echo $new_html;

	//$xml2 = new DOMDocument();
	//$xml2->loadHTML($new_html);

	//foreach($xml2->getElementsByTagName('h2') as $link) { 
        //	$xpath = new DOMXPath($xml2);
	//	$heading = $link->nodeValue;
	//	$query = "//p[preceding-sibling::h2[1][span='{$heading}']] | //li";
	//	$entries = $xpath->query($query);
	//
	//	foreach ($entries as $entry) {
	//		print "<p>";
   	//	 	echo $entry->nodeValue;
	//		print "</p>";
	//	}
	//	print "<br><br><br><br>";
    	//} 

	


	
	//$body = $xml2->getElementsByTagName('body')->item(0);
	//print $body;

	//$xml = new DOMDocument(); 
	//$xml->loadHTMLFile($result1); 
	//$links = array(); 

	//foreach($xml2->getElementsByTagName('h2') as $link) { 
        //	$links[] = array('text' => $link->nodeValue); 
	//	//print $link->nodeValue;
	//	//print "<br><br>";
    	//} 

	//$xmlresult = simplexml_load_string($result); 

	
	//$result2 = preg_replace("(.*)<div>(.*?)</div>(.*)", '', $result1)
	//echo $xml->saveXML();
	
	//print "</prompt>\n</field></form></vxml>";

?>