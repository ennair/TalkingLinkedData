<?php
	$string = $_GET["section"];
	$keywords = preg_split("/[,]+/", $string);
	$sectionHeader = $keywords[0];

	print "\n<vxml version = \"2.1\"> \n  <property name=\"inputmodes\" value=\"dtmf\" />  <form id=\"result\">\n <block> \n<prompt bargein=\"true\">\n";
	print "You have chosen ";
	print $sectionHeader;
	print ".";

	$url = $keywords[1];
	$query = file_get_contents($url);	
	$html = new DOMDocument();
	$html->loadXML($query);

	$i = 1;
	$print;
	
	foreach($html->getElementsByTagName('binding') as $section) {  
		$content = $section->nodeValue;
		if($i % 2 != 0) { //property names
			if(strcmp($content, $sectionHeader) == 0) {
				$print = true;
			}
		} elseif ($print == true) { //property value, which should be printed
			print "<p>" . $content . "</p>";
			$print = false;
		} else { //property value, which should not be printed

		}	
		
		$i++;
    	} 

	print "\n </prompt> \n <prompt>You will now return to the main menu.</prompt> \n<goto next=\"dbpedia.xml\"/>\n</block> \n </form> \n </vxml>";

?>