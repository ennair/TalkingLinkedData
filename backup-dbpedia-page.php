<?php
	//$page = $_GET["page"];
	$page = 'Maize';

	$myquery = "
	SELECT DISTINCT ?label ?value
	WHERE {
   
   	{SELECT ?label ?value 
    	WHERE {
	?value2 ?property <http://dbpedia.org/resource/" . $page . "> .
	?property <http://www.w3.org/2000/01/rdf-schema#label> ?label .
	?value2 <http://www.w3.org/2000/01/rdf-schema#label> ?value .
	FILTER NOT EXISTS {?value2 <http://dbpedia.org/ontology/wikiPageRedirects> ?x} .
	FILTER NOT EXISTS {?value2 <http://dbpedia.org/ontology/wikiPageDisambiguates> ?x} .
	FILTER NOT EXISTS {?value2 <http://dbpedia.org/property/aux> ?x} .
	FILTER NOT EXISTS {?value2 <http://dbpedia.org/property/data> ?x} .
	FILTER NOT EXISTS {?value2 <http://dbpedia.org/property/sub> ?x} .
	FILTER NOT EXISTS {?value2 <http://dbpedia.org/property/resources> ?x} .
	FILTER (LANG(?label) = 'en' && LANG(?value) = 'en')
	}
	}
        
            UNION
            
            {
            {SELECT (?label AS ?abstract) ?value
            WHERE {
            <http://dbpedia.org/resource/" . $page . "> ?property ?value .
            ?property <http://www.w3.org/2000/01/rdf-schema#label> ?label .
            ?property <http://www.w3.org/2000/01/rdf-schema#range> <http://www.w3.org/2001/XMLSchema#string> .
            FILTER (LANG(?label) = 'en' && LANG(?value) = 'en')
      	    }
            }
            
            UNION
            
            {
            {SELECT DISTINCT ?label ?value
	    WHERE {
	    <http://dbpedia.org/resource/" . $page . "> ?property ?value2 .
	    ?property <http://www.w3.org/2000/01/rdf-schema#label> ?label .
	    ?value2 <http://www.w3.org/2000/01/rdf-schema#label> ?value .
	    FILTER NOT EXISTS {?property <http://www.w3.org/2000/01/rdf-schema#range> <http://www.w3.org/2001/XMLSchema#string>} .
	    FILTER (LANG(?label) = 'en' && LANG(?value) = 'en')
	    }
      	    }
            
            UNION
            
            {SELECT ?label ?value
	    WHERE {
	    <http://dbpedia.org/resource/" . $page . "> ?property ?value .
	    ?property <http://www.w3.org/2000/01/rdf-schema#label> ?label .
	    FILTER NOT EXISTS {?property <http://www.w3.org/2000/01/rdf-schema#range> <http://www.w3.org/2001/XMLSchema#string>} .
	    FILTER NOT EXISTS {?value <http://www.w3.org/2000/01/rdf-schema#label> ?x} .
	    FILTER NOT EXISTS {?property <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://www.w3.org/2002/07/owl#ObjectProperty>} .
	    FILTER NOT EXISTS {?property <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://www.w3.org/2002/07/owl#DatatypeProperty>} .
	    FILTER NOT EXISTS {?x <http://dbpedia.org/property/align> ?value} .
	    FILTER NOT EXISTS {?x <http://dbpedia.org/property/caption> ?value} .
	    FILTER NOT EXISTS {?x <http://dbpedia.org/property/image> ?value} .
	    FILTER NOT EXISTS {?x <http://dbpedia.org/property/imageCaption> ?value} .
	    FILTER NOT EXISTS {?x <http://dbpedia.org/property/note> ?value} .
	    FILTER (LANG(?label) = 'en')
	    }
      }
      }
      }
            
      }
	ORDER BY ASC(UCASE(str(?label)))
	";

	$encoded_query = urlencode($myquery);
        $myurl = 'http://eculture.cs.vu.nl:1717/sparql/?query=' .$encoded_query. '&default-graph-uri=http%3A%2F%2Fdbpedia.org&format=application%2Fsparql-results%2Bxml&timeout=30000&debug=on';

	print "\n<vxml version = \"2.1\" > \n  <property name=\"inputmodes\" value=\"dtmf\" />  <form id=\"menu1\" accept-charset=\"UTF-8\">\n <field name=\"section\"> \n<prompt>\n";
	print "You have chosen ";
	print $page;
	print ". Which section would you like to read?";
	print "\n<enumerate>";
	print "\nFor <value expr=\"_prompt\"/>, press <value expr=\"_dtmf\"/>.";
	print "\n</enumerate> \n </prompt>";

	$query = file_get_contents($myurl);
	var_dump($query);
	//$xmlresult = simplexml_load_string($query);
	//print $xmlresult;
	//$encodedquery = json_encode($query, true);
	//$result = json_decode($encodedquery, true);

	$html = new DOMDocument();
	$html->loadXML($query);
	$i = 1;
	$d = 2;
	$string;

	print "\n<option dtmf=\"0\" value=\"Back\">Go back to main menu</option>";
	print "\n<option dtmf=\"1\" value=\"has abstract," . $page . "\">Abstract</option>";
	
	foreach($html->getElementsByTagName('binding') as $section) {  
		//$->evaluate("/sparql/results//binding[@class='label']")
		if($i % 2 == 0 and $i > 1) {
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
	print "\n<goto next=\"backup-dbpedia.xml\"/>";
	print "\n<else />";
	print "\n<submit next=\"backup-dbpedia-section.php\" namelist=\"section\"/>";
	print "\n</if> \n </filled> \n </form>"; 
	print "\n </vxml>";

?>