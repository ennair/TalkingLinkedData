<?php
	$page = $_GET["page"];

	print "<vxml version = \"2.1\" > \n  <property name=\"inputmodes\" value=\"dtmf\" />  <form id=\"menu1\" accept-charset=\"UTF-8\">\n <field name=\"section\"> \n<prompt bargein=\"true\">\n";
	print "You have chosen ";
	print $page;
	print ". Which section would you like to read?";
	print "\n<enumerate>";
	print "\nFor <value expr=\"_prompt\"/>, press <value expr=\"_dtmf\"/>.";
	print "\n</enumerate> \n </prompt>";

	print "\n<option dtmf=\"0\" value=\"Back\">Go back to main menu</option>";
	print "\n<option dtmf=\"1\" value=\"Abstract," . $page . "\">Abstract</option>";
	print "\n<option dtmf=\"2\" value=\"Nutritional values," . $page . "\">Nutritional values</option>";
	print "\n<option dtmf=\"3\" value=\"Biological classification," . $page . "\">Biological classification</option>";
	print "\n<option dtmf=\"4\" value=\"Associated food persons and organizations," . $page . "\">Associated food, persons and organizations</option>";

	print "\n<noinput>Please enter a number.<reprompt/></noinput>";      
  	print "\n<nomatch>This is no option. Try again.<reprompt/></nomatch>";
	print "\n</field>";
	print "\n<filled namelist=\"section\">";
	print "\n<if cond=\"section == 'Back'\">";
	print "\n<goto next=\"dbpedia-backup.xml\"/>";
	print "\n<else />";
	print "\n<submit next=\"dbpedia-section-backup.php\" namelist=\"section\"/>";
	print "\n</if> \n </filled> \n </form>"; 
	print "\n </vxml>";

?>