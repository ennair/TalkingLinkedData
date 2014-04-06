<?php
	$string = $_GET["section"];
	$keywords = preg_split("/[,]+/", $string);
	$sectionHeader = $keywords[0];

	print "\n<vxml version = \"2.1\" > \n  <property name=\"inputmodes\" value=\"dtmf\" />  <form id=\"menu1\">\n <field name=\"subsection\"> \n<prompt bargein=\"true\">\n";
	print "You have chosen ";
	print $sectionHeader;
	print ". Which subsection would you like to read?";
	print "\n<enumerate>";
	print "\nFor <value expr=\"_prompt\"/>, press <value expr=\"_dtmf\"/>.";
	print "\n</enumerate> \n </prompt>";

	$url = $keywords[1];	
	$html = new DOMDocument();
	$html->loadHTMLFile($url);
	$i = 1;
 
        $xpath = new DOMXPath($html);
	$query = "//h3[preceding-sibling::h2[1][span='{$sectionHeader}']] | //p[preceding-sibling::h2[1][span='{$sectionHeader}']]";
	$subsections = $xpath->query($query);

	foreach ($subsections as $subsection) {
		$content = $subsection->nodeValue;
		print "\n<option dtmf=\"" . $i . "\" value=\"" . $content . "," . $url . "\">". $content . "</option>"; 
		
		$i++;
	}

	print "\n<noinput>Please enter a number.<reprompt/></noinput>";      
  	print "\n<nomatch>This is no option. Try again.<reprompt/></nomatch>";
	print "\n</field>";
        print "\n<filled>";
	print "\n<submit next=\"wikipedia-section.php\" namelist=\"subsection\"/>";
	print "\n</filled> \n </form>"; 
	print "\n </vxml>";
?>