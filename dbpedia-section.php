<?php
	$string = $_GET["section"];
	$keywords = preg_split("/[,]+/", $string);
	$sectionHeader = $keywords[0];

	print "\n<vxml version = \"2.1\"> \n  <property name=\"inputmodes\" value=\"dtmf\" />  <form id=\"result\">\n <block> \n<prompt bargein=\"true\">\n";
	print "You have chosen ";
	print $sectionHeader;
	print ".";

	$page = $keywords[1];

	$myquery = "
	SELECT ?label ?value
	WHERE {
   
   	{SELECT ?label ?value 
      	WHERE {
         ?value2 ?property <http://dbpedia.org/resource/" . $page . "> .
         ?property <http://www.w3.org/2000/01/rdf-schema#label> ?label .
         ?value2 <http://www.w3.org/2000/01/rdf-schema#label> ?value .
         FILTER (LANG(?label) = 'en' and LANG(?value) = 'en')
      	}
      	}
            
            UNION
            
            {
            {SELECT ?label ?value
            WHERE {
            <http://dbpedia.org/resource/" . $page . "> ?property ?value .
            ?property <http://www.w3.org/2000/01/rdf-schema#label> ?label .
            ?property <http://www.w3.org/2000/01/rdf-schema#range> <http://www.w3.org/2001/XMLSchema#string> .
            FILTER (LANG(?label) = 'en' and LANG(?value) = 'en')
      	}
      	}
            
            UNION
            
            {
            {SELECT DISTINCT ?label ?value
            WHERE {
            <http://dbpedia.org/resource/" . $page . "> ?property ?value2 .
            ?property <http://www.w3.org/2000/01/rdf-schema#label> ?label .
            ?value2 <http://www.w3.org/2000/01/rdf-schema#label> ?value .
            MINUS {?property <http://www.w3.org/2000/01/rdf-schema#range> <http://www.w3.org/2001/XMLSchema#string>} .
            FILTER (LANG(?label) = 'en' and LANG(?value) = 'en')
      	}
      	}
            
            UNION
            
            {SELECT ?label ?value
            WHERE {
            <http://dbpedia.org/resource/" . $page . "> ?property ?value .
            ?property <http://www.w3.org/2000/01/rdf-schema#label> ?label .
            MINUS {?property <http://www.w3.org/2000/01/rdf-schema#range> <http://www.w3.org/2001/XMLSchema#string>} .
            MINUS {?value <http://www.w3.org/2000/01/rdf-schema#label> ?x} .
            FILTER (LANG(?label) = 'en')
      	}
      	}
      	}
      	}
            
      	}
            ORDER BY ?label
	";

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
			if(strcmp($content, $sectionHeader) == 0) {
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