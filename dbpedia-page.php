<?php
	$page = $_GET["section"];

	$myquery = "
	SELECT ?label ?value
	WHERE {
   
   	{SELECT ?label ?value 
      	WHERE {
         ?value2 ?property <http://dbpedia.org/resource/Maize> .
         ?property <http://www.w3.org/2000/01/rdf-schema#label> ?label .
         ?value2 <http://www.w3.org/2000/01/rdf-schema#label> ?value .
         FILTER (LANG(?label) = 'en' and LANG(?value) = 'en')
      	}
      	}
            
            UNION
            
            {
            {SELECT ?label ?value
            WHERE {
            <http://dbpedia.org/resource/Maize> ?property ?value .
            ?property <http://www.w3.org/2000/01/rdf-schema#label> ?label .
            ?property <http://www.w3.org/2000/01/rdf-schema#range> <http://www.w3.org/2001/XMLSchema#string> .
            FILTER (LANG(?label) = 'en' and LANG(?value) = 'en')
      	}
      	}
            
            UNION
            
            {
            {SELECT DISTINCT ?label ?value
            WHERE {
            <http://dbpedia.org/resource/Maize> ?property ?value2 .
            ?property <http://www.w3.org/2000/01/rdf-schema#label> ?label .
            ?value2 <http://www.w3.org/2000/01/rdf-schema#label> ?value .
            MINUS {?property <http://www.w3.org/2000/01/rdf-schema#range> <http://www.w3.org/2001/XMLSchema#string>} .
            FILTER (LANG(?label) = 'en' and LANG(?value) = 'en')
      	}
      	}
            
            UNION
            
            {SELECT ?label ?value
            WHERE {
            <http://dbpedia.org/resource/Maize> ?property ?value .
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
	//print $encoded_query;
        $myurl = 'http://dbpedia.org/sparql?query=' .$encoded_query. '&format=json';
	//print "<!--".$myurl."-->";

	print "\n<vxml version = \"2.1\" > \n  <property name=\"inputmodes\" value=\"dtmf\" />  <form id=\"menu1\">\n <field name=\"section\"> \n<prompt>\n";
	print "Which section would you like to read?";
	print "\n<enumerate>";
	print "\nFor <value expr=\"_prompt\"/>, press <value expr=\"_dtmf\"/>.";
	print "\n</enumerate> \n </prompt>";

	$results = file_get_contents($myurl);
	//print $results;
	$results = utf8_encode($results);
	//print $foo;
	$jsonArr = json_decode($results);
	print $jsonArr;
	//$html = new DOMDocument();
	//$html->loadHTMLFile($result);
	//$xmlresult = simplexml_load_string($result);

	while($item = array_shift($jsonArr))
	{
		foreach($item as $key => $value){
	 		//$property = $value['label']['value'];
	 		//print "\n<option dtmf=\"" . $i . "\" value=\"". $property . "\">". $property . "</option>";
			print $key;
			print $value;
		}
	}

?>