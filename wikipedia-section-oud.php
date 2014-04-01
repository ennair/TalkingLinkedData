<?php
	 $sectionHeader = $_GET["section"];

	print "\n<vxml version = \"2.1\"> \n  <property name=\"inputmodes\" value=\"dtmf\" />  <form id=\"result\">\n <block> \n<prompt bargein=\"true\">\n";

	$url = 'http://en.wikipedia.org/wiki/Maize';
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
 
        $xpath = new DOMXPath($html);
	$query = "//p[preceding-sibling::h2[1][span='{$sectionHeader}']] | //li[preceding-sibling::h2[1][span='{$sectionHeader}']]";
	$paragraphs = $xpath->query($query);

	foreach ($paragraphs as $paragraph) {
		$content = $paragraph->nodeValue;
		print "<p>" . $content . "</p>";
	}

	print "\n </prompt> \n <goto next=\"wikipedia-test.xml\"/>\n</block> \n </form> \n </vxml>";

?>