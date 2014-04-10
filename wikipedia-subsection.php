<?php
	$string = $_GET["section"];
	$keywords = preg_split("/[,]+/", $string);
	$sectionHeader = $keywords[0];

	$url = $keywords[1];	
	$html = new DOMDocument();
	$html->loadHTMLFile($url);

	$xpath = new DOMXPath($html);
	$query = "//h3[preceding-sibling::h2[1][span='{$sectionHeader}']] | //h3[preceding-sibling::h2[1][span='{$sectionHeader}']]";
	$subsections = $xpath->query($query);



	print "\n<vxml version = \"2.1\" > \n  <property name=\"inputmodes\" value=\"dtmf\" />";  
	print "<form id=\"menu0\">\n <block> \n";
	
	if($subsections->length == 0) {
		print "<goto next=\"#result\"/>";
	} else {
		print "<goto next=\"#menu1\"/>";
	}
	print "\n</block> \n </form>";




	print "\n<form id=\"menu1\">\n <field name=\"section\"> \n<prompt bargein=\"true\">\n";
	print "You have chosen ";
	print $sectionHeader;
	print ". Which subsection would you like to read?";
	print "\n<enumerate>";
	print "\nFor <value expr=\"_prompt\"/>, press <value expr=\"_dtmf\"/>.";
	print "\n</enumerate> \n </prompt>";

	print "\n<option dtmf=\"0\" value=\"Back\">Go back to main menu</option>";

	$i = 2;

	foreach ($subsections as $subsection) {
		$subsectionName = $subsection->nodeValue;
		
		if($i == 2) {
			print "\n<option dtmf=\"1\" value=\"" . $sectionHeader . ",Abstract," . $url . "," . $subsectionName . "\">Abstract</option>";	
		}

		print "\n<option dtmf=\"" . $i . "\" value=\"" . $sectionHeader . "," . $subsectionName . "," . $url . "\">". $subsectionName . "</option>"; 
		
		$i++;
	}

	print "\n<noinput>Please enter a number.<reprompt/></noinput>";      
  	print "\n<nomatch>This is no option. Try again.<reprompt/></nomatch>";
	print "\n</field>";
        print "\n<filled namelist=\"section\">";
	print "\n<if cond=\"section == 'Back'\">";
	print "\n<goto next=\"wikipedia.xml\"/>";
	print "\n<else />";
	print "\n<submit next=\"wikipedia-section.php\" namelist=\"section\"/>";
	print "\n</if> \n </filled> \n </form>"; 




	print "\n\n<form id=\"result\">\n <block> \n<prompt bargein=\"true\">\n";
	print "You have chosen ";
	print $sectionHeader;
	print ".";

	$domNodeList = $html->getElementsByTagname('sup'); 
	$domElemsToRemove = array(); 
	foreach ( $domNodeList as $domElement ) { 
  		$domElemsToRemove[] = $domElement; 
	} 

	foreach( $domElemsToRemove as $domElement ){ 
  		$domElement->parentNode->removeChild($domElement); 
	} 

	$firstSection = $html->getElementsByTagname('h2')->item(1)->nodeValue;
 
        $xpath2 = new DOMXPath($html);

	if(strcmp($sectionHeader, 'Abstract') == 0) {
		$abstractQuery = "//p[following-sibling::h2[1][span='{$firstSection}']] | //ul[following-sibling::h2[1][span='{$firstSection}']]//li | //ol[following-sibling::h2[1][span='{$firstSection}']]//li";
		$abstractParagraphs = $xpath2->query($abstractQuery);

		foreach ($abstractParagraphs as $paragraph) {
			$content = $paragraph->nodeValue;
			print "<p>" . $content . "</p>";
		}
	} else {
		$query = "//p[preceding-sibling::h2[1][span='{$sectionHeader}']] | //ul[preceding-sibling::h2[1][span='{$sectionHeader}']]//li | //ol[preceding-sibling::h2[1][span='{$sectionHeader}']]//li";
		$paragraphs = $xpath2->query($query);

		foreach ($paragraphs as $paragraph) {
			$content = $paragraph->nodeValue;
			print "<p>" . $content . "</p>";
		}
	}		

	print "\n </prompt> \n <prompt>You will now return to the main menu.</prompt> \n<goto next=\"wikipedia.xml\"/>\n</block> \n </form>";

	print "\n </vxml>";
                
?>