<?php
	$string = $_GET["section"];
	$keywords = preg_split("/[,]+/", $string);
	$subsectionHeader = $keywords[0];
	$sectionHeader = $keywords[1];
	$page = $keywords[2];

	print "\n<vxml version = \"2.1\"> \n  <property name=\"inputmodes\" value=\"dtmf\" />  <form id=\"result\">\n <block> \n<prompt bargein=\"true\">\n";
	print "You have chosen ";
	print $subsectionHeader;
	print ".";

	if (strcmp($sectionHeader, 'Nutritional values') == 0) {
		$url = "http://neosound.nl/rianne/backup-query-results/" . $page . "-nutritional-values.xml";
	} elseif (strcmp($sectionHeader, 'Biological classification') == 0) {
		$url = "http://neosound.nl/rianne/backup-query-results/" . $page . "-biological-classification.xml";		
	} else { //strcmp($sectionHeader, 'Associated food persons and organizations') == 0
		$url = "http://neosound.nl/rianne/backup-query-results/" . $page . "-associated.xml";
	}

	$query = file_get_contents($url);	
	$html = new DOMDocument();
	$html->loadXML($query);

	$i = 1;
	$print;
	
	foreach($html->getElementsByTagName('binding') as $section) {  
		$content = $section->nodeValue;
		if($i % 2 != 0) { //property names
			if(strcmp($content, $subsectionHeader) == 0) {
				$print = true;
			}
		} elseif ($print == true) { //property value, which should be printed
			print "<p>" . $content . "</p>";
			$print = false;
		} else { //property value, which should not be printed

		}	
		
		$i++;
    	} 

	print "\n </prompt> \n <prompt>You will now return to the main menu.</prompt> \n<goto next=\"dbpedia-backup.xml\"/>\n</block> \n </form> \n </vxml>";

?>