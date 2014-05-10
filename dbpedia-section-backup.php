<?php
	$string = $_GET["section"];
	$keywords = preg_split("/[,]+/", $string);
	$sectionHeader = $keywords[0];
	$page = $keywords[1];

	//$page = 'Potato';
	//$sectionHeader = 'Associated food persons and organizations';

	print "\n<vxml version = \"2.1\" > \n  <property name=\"inputmodes\" value=\"dtmf\" />";  
	print "\n<form id=\"menu0\">\n <block> \n";
	
	if(strcmp($sectionHeader, 'Abstract') == 0) {
		print "<goto next=\"#result\"/>";
	} else {
		print "<goto next=\"#menu1\"/>";
	}
	print "\n</block> \n </form>";



	print "\n<form id=\"menu1\" accept-charset=\"UTF-8\">\n <field name=\"section\"> \n<prompt bargein=\"true\">\n";
	print "You have chosen ";
	print $sectionHeader;
	print ". Which subsection would you like to read?";
	print "\n<enumerate>";
	print "\nFor <value expr=\"_prompt\"/>, press <value expr=\"_dtmf\"/>.";
	print "\n</enumerate> \n </prompt>";


	if (strcmp($sectionHeader, 'Nutritional values') == 0) {
		$url = "http://neosound.nl/rianne/backup-query-results/" . $page . "-label-nutritional-values.xml";
	} elseif (strcmp($sectionHeader, 'Biological classification') == 0) {
		$url = "http://neosound.nl/rianne/backup-query-results/" . $page . "-label-biological-classification.xml";
	} else { //strcmp($sectionHeader, 'Associated food persons and organizations') == 0
		$url = "http://neosound.nl/rianne/backup-query-results/" . $page . "-label-associated.xml";
	}

	$query = file_get_contents($url);
	//var_dump($query);	
	$html = new DOMDocument();
	$html->loadXML($query);

	$i = 1;

	print "\n<option dtmf=\"0\" value=\"Back\">Go back to main menu</option>";
	
	foreach($html->getElementsByTagName('binding') as $section) {  
		$sectionName = $section->nodeValue;
		
		print "\n<option dtmf=\"" . $i . "\" value=\"" . $sectionName . "," . $sectionHeader . "," . $page . "\">". $sectionName . "</option>";
			
		$i++;
    	} 

	print "\n<noinput>Please enter a number.<reprompt/></noinput>";      
  	print "\n<nomatch>This is no option. Try again.<reprompt/></nomatch>";
	print "\n</field>";
	print "\n<filled namelist=\"section\">";
	print "\n<if cond=\"section == 'Back'\">";
	print "\n<goto next=\"dbpedia-backup.xml\"/>";
	print "\n<else />";
	print "\n<submit next=\"dbpedia-subsection-backup.php\" namelist=\"section\"/>";
	print "\n</if> \n </filled> \n </form>"; 	


	print "\n\n<form id=\"result\">\n <block> \n<prompt bargein=\"true\">\n";
	print "You have chosen ";
	print $sectionHeader;
	print ".";

	$url = "http://neosound.nl/rianne/backup-query-results/" . $page . "-abstract.xml";

	$query = file_get_contents($url);	
	$html = new DOMDocument();
	$html->loadXML($query);

	$content = $html->getElementsByTagName('binding')->item(0)->nodeValue;
	print "<p>" . $content . "</p>";

	print "\n </prompt> \n <prompt>You will now return to the main menu.</prompt> \n<goto next=\"dbpedia-backup.xml\"/>\n</block> \n </form>";
	print "\n </vxml>";

?>