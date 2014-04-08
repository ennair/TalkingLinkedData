<?php
	$string = $_GET["section"];
	$keywords = preg_split("/[,]+/", $string);
	$sectionHeader = $keywords[0];

	print "\n<vxml version = \"2.1\"> \n  <property name=\"inputmodes\" value=\"dtmf\" />  <form id=\"result\">\n <block> \n<prompt bargein=\"true\">\n";
	print "You have chosen ";
	print $sectionHeader;
	print ".";

	$url = $keywords[1];	
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

	$firstSection = $html->getElementsByTagname('h2')->item(1)->nodeValue;
 
        $xpath = new DOMXPath($html);

	if(strcmp($sectionHeader, 'Abstract') == 0) {
		$abstractQuery = "//p[following-sibling::h2[1][span='{$firstSection}']] | //p[following-sibling::h2[1][span='{$firstSection}']]";
		$abstractParagraphs = $xpath->query($abstractQuery);

		foreach ($abstractParagraphs as $paragraph) {
			$content = $paragraph->nodeValue;
			print "<p>" . $content . "</p>";
		}
	} else {
		$query = "//p[preceding-sibling::h2[1][span='{$sectionHeader}']] | //p[preceding-sibling::h2[1][span='{$sectionHeader}']]";
		$paragraphs = $xpath->query($query);

		foreach ($paragraphs as $paragraph) {
			$content = $paragraph->nodeValue;
			print "<p>" . $content . "</p>";
		}
	}		

	print "\n </prompt> \n <prompt>You will now return to the main menu.</prompt> \n<goto next=\"wikipedia.xml\"/>\n</block> \n </form> \n </vxml>";

?>