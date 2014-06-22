<?php
	$string = $_GET["section"];
	$keywords = preg_split("/[,]+/", $string);
	$sectionHeader = $keywords[0];
	$subsectionHeader = $keywords[1];

	print "\n<vxml version = \"2.1\"> \n  <property name=\"inputmodes\" value=\"dtmf\" />  <form id=\"result\">\n <block> \n<prompt bargein=\"true\">\n";
	print "You have chosen ";
	print $subsectionHeader;
	print ".";

	$url = $keywords[2];	
	$html = new DOMDocument();
	$html->loadHTMLFile($url);

	$domNodeList = $html->getElementsByTagname('sup'); 
	$domElemsToRemove = array(); 
	foreach ( $domNodeList as $domElement ) { 
  		$domElemsToRemove[] = $domElement; 
	} 

	foreach( $domElemsToRemove as $domElement ){ 
  		$domElement->parentNode->removeChild($domElement); 
	} 

	//$firstSection = $html->getElementsByTagname('h3')->item(1)->nodeValue;
 
        $xpath = new DOMXPath($html);

	if(strcmp($subsectionHeader, 'Abstract') == 0) {
		$firstSubsection = $keywords[3];
		$abstractQuery = "//p[preceding-sibling::h2[1][span='{$sectionHeader}'] and following-sibling::h3[1][span='{$firstSubsection}']] | //ul[preceding-sibling::h2[1][span='{$sectionHeader}'] and following-sibling::h3[1][span='{$firstSubsection}']]//li | //ol[preceding-sibling::h2[1][span='{$sectionHeader}'] and following-sibling::h3[1][span='{$firstSubsection}']]//li";
		$abstractParagraphs = $xpath->query($abstractQuery);

		foreach ($abstractParagraphs as $paragraph) {
			$content = $paragraph->nodeValue;
			print "<p>" . $content . "</p>";
		}
	} else {
		$query = "//p[preceding-sibling::h3[1][span='{$subsectionHeader}'] and preceding-sibling::h2[1][span='{$sectionHeader}']] | //ul[preceding-sibling::h3[1][span='{$subsectionHeader}'] and preceding-sibling::h2[1][span='{$sectionHeader}']]//li | //ol[preceding-sibling::h3[1][span='{$subsectionHeader}'] and preceding-sibling::h2[1][span='{$sectionHeader}']]//li";
		$paragraphs = $xpath->query($query);

		foreach ($paragraphs as $paragraph) {
			$content = $paragraph->nodeValue;
			print "<p>" . $content . "</p>";
		}
	}		

	print "\n </prompt> \n <prompt>You will now return to the main menu.</prompt> \n<goto next=\"wikipedia.xml\"/>\n</block> \n </form> \n </vxml>";

?>