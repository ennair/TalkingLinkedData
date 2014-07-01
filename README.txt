=== Talking to Linked Data ===

Developed by Rianne Nieland

== Content ==

- Backup of query results
- Code of DBpedia backup voice interface
- Code of DBpedia voice interface
- Code of Wikipedia voice interface
- Forms
- Results

== Explanation of code files ==

The voice interface for Wikipedia and DBpedia have separate code files. The files dbpedia.xml and wikipedia.xml contain the code of part one of the process, which is the main menu. Dbpedia-page.php and wikipedia-page.php contain the code of part two, which is the section menu, and dbpedia-section.php and wikipedia-section.php contain the code of part three, which are the subsection menus. These files generate a VXML document, which makes the (sub)section menu. The files dbpedia-subsection.php and wikipedia-subsection.php contain the code of part four for reading subsections, and dbpedia-section.php and wikipedia-section.php contain the code for reading sections. In the files for reading a section or subsection VXML files are generated with PHP. 