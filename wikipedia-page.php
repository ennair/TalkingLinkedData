<?php
	$page = $_GET["page"];

	print "\n<vxml version = \"2.1\" > \n  <property name=\"inputmodes\" value=\"dtmf\" />  <form id=\"menu1\">\n <field name=\"section\"> \n<prompt bargein=\"true\">\n";
	print "You have chosen ";
	print $page;
	print ". Which section would you like to read?";
	print "\n<enumerate>";
	print "\nFor <value expr=\"_prompt\"/>, press <value expr=\"_dtmf\"/>.";
	print "\n</enumerate> \n </prompt>";

	$url = 'http://en.wikipedia.org/wiki/' .$page;	
	$html = new DOMDocument();
	$html->loadHTMLFile($url);

	$domNodeList = $html->getElementsByTagname('h3'); 
	$domElemsToRemove = array(); 
	foreach ( $domNodeList as $domElement ) { 
  		$domElemsToRemove[] = $domElement; 
	} 

	foreach ( $domElemsToRemove as $domElement ) { 
  		$domElement->parentNode->removeChild($domElement); 
	} 

	$domNodeList = $html->getElementsByTagname('h4'); 
	$domElemsToRemove = array(); 
	foreach ( $domNodeList as $domElement ) { 
  		$domElemsToRemove[] = $domElement; 
	} 

	foreach ( $domElemsToRemove as $domElement ) { 
  		$domElement->parentNode->removeChild($domElement); 
	} 


	print "\n<option dtmf=\"0\" value=\"Back\">Go back to main menu</option>";
	print "\n<option dtmf=\"1\" value=\"Abstract," . $url . "\">Abstract</option>";

	$i = 2;

	foreach(getElementsByClassName($html, 'mw-headline') as $section) {
		$sectionName = $section->nodeValue;
	
		if(strcmp($sectionName, 'See also') == 0) {
			break;
		}

		print "\n<option dtmf=\"" . $i . "\" value=\"" . $sectionName . "," . $url . "\">". $sectionName . "</option>"; 
		
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
	print "\n </vxml>";
                
         
  	function getElementsByClassName(DOMDocument $DOMDocument, $ClassName) {
    		$Elements = $DOMDocument -> getElementsByTagName("*");
    		$Matched = array();
 
    		foreach($Elements as $node) {
        		if( ! $node -> hasAttributes())
            			continue;
 
        		$classAttribute = $node -> attributes -> getNamedItem('class');
 
        		if( ! $classAttribute)
            			continue;
 
        		$classes = explode(' ', $classAttribute -> nodeValue);
 
        		if(in_array($ClassName, $classes))
            			$Matched[] = $node;
    		}
 
    		return $Matched;
	}
 
?>