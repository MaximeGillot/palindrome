<?php
include 'palindrome.php' ;

$handle = fopen($argv[1] , "r") or die("Couldn't get handle");
if ($handle) 
{
	while (!feof($handle)) 
	{
	    $buffer = fgets($handle, 4096);
	  	$buffer = str_replace("\n", '', $buffer);
	  	$file_name_csv = str_replace(".fasta", '.csv', $buffer);
	    if (!file_exists("allPalindromes/".$file_name_csv)) 
	    {
	    	echo "calcule en cours\n" ;
	    	FromFastaFileToCSVFile("allGenomes/".$buffer , "allPalindromes/".$file_name_csv ) ;
	    }
	    else
	    {
	    	echo " fichier ".$file_name_csv." déjà calculer \n" ;
	    }
	}
	
}

?>