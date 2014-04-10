<?php
	$page = $_GET["page"];

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
        $myurl = 'http://dbpedia.org/sparql?query=' .$encoded_query;

	print "\n<vxml version = \"2.1\" > \n  <property name=\"inputmodes\" value=\"dtmf\" />  <form id=\"menu1\" accept-charset=\"UTF-8\">\n <field name=\"section\"> \n<prompt>\n";
	print "You have chosen ";
	print $page;
	print ". Which section would you like to read?";
	print "\n<enumerate>";
	print "\nFor <value expr=\"_prompt\"/>, press <value expr=\"_dtmf\"/>.";
	print "\n</enumerate> \n </prompt>";

	$query = file_get_contents($myurl);
	//$encodedquery = json_encode($query, true);
	//$result = json_decode($encodedquery, true);

	$html = new DOMDocument();
	$html->loadXML($query);
	$i = 1;
	$d = 1;
	$string;

	print "\n<option dtmf=\"0\" value=\"Back\">Go back to main menu</option>";
	
	foreach($html->getElementsByTagName('binding') as $section) {  
		//$->evaluate("/sparql/results//binding[@class='label']")
		if($i % 2 != 0) {
			$sectionName = $section->nodeValue;
			
			if(strcmp($sectionName, $string) != 0) {
				print "\n<option dtmf=\"" . $d . "\" value=\"" . $sectionName . "," . $page . "\">". $sectionName . "</option>";
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
	print "\n<submit next=\"dbpedia-section.php\" namelist=\"section\"/>";
	print "\n</if> \n </filled> \n </form>"; 
	print "\n </vxml>";

?>