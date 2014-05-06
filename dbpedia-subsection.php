<?php
	$string = $_GET["section"];
	$keywords = preg_split("/[,]+/", $string);
	$subsectionHeader = $keywords[0];
	$sectionHeader = $keywords[1];
	$page = $keywords[2];

	//$subsectionHeader = 'sugars';
	//$sectionHeader = 'Nutritional values';
	//$page = 'Tomato';

	print "\n<vxml version = \"2.1\"> \n  <property name=\"inputmodes\" value=\"dtmf\" />  <form id=\"result\">\n <block> \n<prompt bargein=\"true\">\n";
	print "You have chosen ";
	print $subsectionHeader;
	print ".";

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

	print "\n </prompt> \n <prompt>You will now return to the main menu.</prompt> \n<goto next=\"dbpedia.xml\"/>\n</block> \n </form> \n </vxml>";

?>