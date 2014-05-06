<?php
	$string = $_GET["section"];
	$keywords = preg_split("/[,]+/", $string);
	$sectionHeader = $keywords[0];
	$page = $keywords[1];

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
		$myquery = "
		SELECT DISTINCT ?label ?value
		WHERE {

		{SELECT ?label (CONCAT(?number, \" gram\") AS ?value)
		WHERE {
			<http://dbpedia.org/resource/" . $page . "> ?property ?number .
			?property <http://www.w3.org/2000/01/rdf-schema#label> ?label .
			FILTER (LANG(?label) = 'en' and datatype(?number) = <http://dbpedia.org/datatype/gram>)
		}
		ORDER BY DESC(?number)
		}

		UNION

		{SELECT ?label (CONCAT(?number, \" milligram\") AS ?value	)
		WHERE {
			<http://dbpedia.org/resource/" . $page . "> ?property ?number .
			?property <http://www.w3.org/2000/01/rdf-schema#label> ?label .
			FILTER (LANG(?label) = 'en' and (datatype(?number) = xsd:double and regex(?label, \"mg\")))
		}
		ORDER BY DESC(?number)}
		}

		LIMIT 10
		";

	} elseif (strcmp($sectionHeader, 'Biological classification') == 0) {
		$myquery = "
		SELECT DISTINCT ?label ?value
		WHERE {
			<http://dbpedia.org/resource/" . $page . "> ?property ?value2 .
			?property <http://www.w3.org/2000/01/rdf-schema#label> ?label .
			?property <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://www.w3.org/2002/07/owl#ObjectProperty> .
			?value2 rdfs:label ?value .
			FILTER (LANG(?label) = 'en' and LANG(?value) = 'en')
		}
		ORDER BY ASC(?label)
		";

	} else { //strcmp($sectionHeader, 'Associated food persons and organizations') == 0
		$myquery = "
		SELECT DISTINCT ?label ?value 
		WHERE {

		{SELECT ?label ?value 
		WHERE {
			?value2 ?property <http://dbpedia.org/resource/" . $page . "> .
			?property <http://www.w3.org/2000/01/rdf-schema#label> ?label .
			?value2 <http://www.w3.org/2000/01/rdf-schema#label> ?value .
			?property rdfs:domain dbpedia-owl:Food .
			FILTER (LANG(?label) = 'en' and LANG(?value) = 'en')
		}}

		UNION

		{SELECT ?label ?value 
		WHERE {
			?value2 ?property <http://dbpedia.org/resource/" . $page . "> .
			?property <http://www.w3.org/2000/01/rdf-schema#label> ?label .
			?value2 <http://www.w3.org/2000/01/rdf-schema#label> ?value .
			?property rdfs:domain dbpedia-owl:Organisation .
			FILTER (LANG(?label) = 'en' and LANG(?value) = 'en' and !regex(?value, \"&\"))
		}}

		UNION

		{SELECT ?label ?value 
		WHERE {
			?value2 ?property <http://dbpedia.org/resource/" . $page . "> .
			?property <http://www.w3.org/2000/01/rdf-schema#label> ?label .
			?value2 <http://www.w3.org/2000/01/rdf-schema#label> ?value .
			?property rdfs:domain dbpedia-owl:Person .
			FILTER (LANG(?label) = 'en' and LANG(?value) = 'en')
		}}}

		ORDER BY ASC(?label)
		";
	}

	$encoded_query = urlencode($myquery);
        $url = 'http://dbpedia.org/sparql?query=' .$encoded_query;

	$query = file_get_contents($url);	
	$html = new DOMDocument();
	$html->loadXML($query);

	$i = 1;
	$d = 1;
	$string;

	print "\n<option dtmf=\"0\" value=\"Back\">Go back to main menu</option>";
	
	foreach($html->getElementsByTagName('binding') as $section) {  
		if($i % 2 != 0) {
			$sectionName = $section->nodeValue;
		
			if(strcmp($sectionName, $string) != 0) {
				print "\n<option dtmf=\"" . $d . "\" value=\"" . $sectionName . "," . $sectionHeader . "," . $page . "\">". $sectionName . "</option>";
				$d++;
			}
				
			$string = $sectionName;
		}
	
		$i++;
    	} 

	print "\n<noinput>Please enter a number.<reprompt/></noinput>";      
  	print "\n<nomatch>This is no option. Try again.<reprompt/></nomatch>";
	print "\n</field>";
	print "\n<filled namelist=\"section\">";
	print "\n<if cond=\"section == 'Back'\">";
	print "\n<goto next=\"dbpedia.xml\"/>";
	print "\n<else />";
	print "\n<submit next=\"dbpedia-subsection.php\" namelist=\"section\"/>";
	print "\n</if> \n </filled> \n </form>"; 	


	print "\n\n<form id=\"result\">\n <block> \n<prompt bargein=\"true\">\n";
	print "You have chosen ";
	print $sectionHeader;
	print ".";

	$abstractquery = "
	SELECT DISTINCT ?label ?value
	WHERE {
		<http://dbpedia.org/resource/" . $page . "> <http://dbpedia.org/ontology/abstract> ?value .
		<http://dbpedia.org/ontology/abstract> <http://www.w3.org/2000/01/rdf-schema#label> ?label .
	FILTER (LANG(?label) = 'en' and LANG(?value) = 'en')
	}
	";

	$encoded_query = urlencode($abstractquery);
        $url = 'http://dbpedia.org/sparql?query=' .$encoded_query;

	$query = file_get_contents($url);	
	$html = new DOMDocument();
	$html->loadXML($query);

	$content = $html->getElementsByTagName('binding')->item(1)->nodeValue;
	print "<p>" . $content . "</p>";

	print "\n </prompt> \n <prompt>You will now return to the main menu.</prompt> \n<goto next=\"dbpedia.xml\"/>\n</block> \n </form>";
	print "\n </vxml>";

?>