<?php
	$sectionHeader = 'Genetics';

	$url = 'http://en.wikipedia.org/wiki/Maize';
	$result = file_get_contents($url);

	$result = str_replace('<ul>', ' ', $result);
	$result = str_replace('</ul>', ' ', $result);
	$result = str_replace('<li>', '<p>', $result);
	$result = str_replace('<li ', '<p ', $result);
	$result = str_replace('</li>', '</p>', $result);

	echo $result;

	$html = new DOMDocument();
	$html->loadHTML($result);

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

?>