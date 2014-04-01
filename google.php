<?php

        $product = $_GET["product"];
        print "<!--".$product."-->";

        $myquery1 = "SELECT DISTINCT ?personvoice ?quant ?meas ?price ?currency
        WHERE { 
        ?p <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://purl.org/collections/w4ra/radiomarche/Person> . 
        ?o <http://purl.org/collections/w4ra/radiomarche/has_contact> ?p . 
        ?o <http://purl.org/collections/w4ra/radiomarche/prod_name> ?pn . 
        ?pn <http://www.w3.org/2000/01/rdf-schema#label> '" .$product ."'.
        ?p <http://purl.org/collections/w4ra/speakle/voicelabel_en>        ?personvoice.
        ?o <http://purl.org/collections/w4ra/radiomarche/quantity> ?quantu . 
         ?quantu <http://purl.org/collections/w4ra/speakle/voicelabel_en>        ?quant.
#       ?quantu <http://www.w3.org/2000/01/rdf-schema#label>  ?quant.
        ?o <http://purl.org/collections/w4ra/radiomarche/unit_measure> ?measu . 
        ?measu <http://purl.org/collections/w4ra/speakle/voicelabel_en>        ?meas.
#        ?measu <http://www.w3.org/2000/01/rdf-schema#label> ?meas.
        ?o <http://purl.org/collections/w4ra/radiomarche/price> ?priceu.
#        ?priceu  <http://www.w3.org/2000/01/rdf-schema#label> ?price .         
        ?priceu <http://purl.org/collections/w4ra/speakle/voicelabel_en>        ?price.
        ?o <http://purl.org/collections/w4ra/radiomarche/currency> ?currencyu.
        ?currencyu <http://purl.org/collections/w4ra/speakle/voicelabel_en>        ?currency.
        } 
        LIMIT 3";
                

        
        $encoded_query = urlencode($myquery1);
        #print $encoded_query;
        $myurl = 'http://semanticweb.cs.vu.nl/radiomarche/sparql/?query=' .$encoded_query;        
        print "<!--".$myurl."-->";

        $result1 = file_get_contents($myurl);
        $xmlresult = simplexml_load_string($result1);

        print "\n<vxml version = \"2.1\" > \n  <property name=\"inputmodes\" value=\"dtmf\" />  <form id=\"result\">\n <block> \n<prompt>\n";
        print "The following is a list of the top three current offerings for ".$product ."\n" ;        
        print "<break time=\"0.5s\"/>\n";

        
         foreach($xmlresult->results->result as $result){
         $personvoice = $result->binding[0]->uri;
         $quant = $result->binding[1]->uri;
         $meas =  $result->binding[2]->uri;         
#         if ($meas=="kg"){ $meas = "kilos";}
         $price =  $result->binding[3]->uri;
         $currency =  $result->binding[4]->uri;



        print "<audio src=\"" .$personvoice ."\"/> offers " ;
        print "<audio src=\"" .$quant ."\"/> ";
        print "<audio src=\"" .$meas ."\"/> for ";
        print "<audio src=\"" .$price  ."\"/> ";
        print "<audio src=\"" .$currency ."\"/> ";
        print "<break time=\"0.5s\"/>\n";;
         }


        print "You will now return to the main menu. <break time=\"0.5s\"/>\n </prompt><goto next=\"mytest.xml\"/>\n</block></form></vxml>";

?>