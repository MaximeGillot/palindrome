<?php
ini_set('display_errors', '1');
error_reporting(E_ALL); 
include 'palindrome.php' ;
include 'ncbi.php';
set_time_limit(100000);

echo '<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
      <html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="fr">
        <head>
              <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
          <title>resultat</title>
        </head>
        <body>';

// prend en argv[1] un fichier qui contient plusieur chaine fasta, affiche le plus gran commun , le pluss grand de chaque et le plus grand spécifique .


	$arbreCommun = new node();
	$arbreCommun = makeInterSuffixTreeFromFile($argv[1]);
	$longest = "";
	$arbreCommun->longestSuffix("",$longest);
	echo "<div>le plus long palindrome commun est : " ;
	echo $longest ;
	echo "<br/></div>";
	
	
	$AllArbre = new node();
	$handle = fopen($argv[1] , "r") or die("Couldn't get handle");
	if ($handle) 
	{
		$i=0;
		while (!feof($handle)) 
		{
			$buffer = fgets($handle, 4096);
		  	$buffer = str_replace("\n", '', $buffer);
		  	if( strrchr($buffer,"/") !== FALSE )
		  	{
		  		$buffer = strrchr($buffer,"/");
		  		if ($buffer[0] == "/") 
			  	{
			  		$buffer = substr($buffer, 1);
			  	}
		  	}
		  	if (strlen($buffer) > 5 )
		  	 {

			  	$file_name_csv = str_replace(".fasta", '.csv', $buffer);
			  	$currentTree = new node();
				$currentTree->loadTreeWithCSVFile("allPalindromes/".$file_name_csv);
				$AllArbre->addTree($currentTree);
echo "<div><br/>--------------------------------------------------------------------------------------------------------<br/></div>";
				echo "<div> le plus long palindrome de $buffer est : " ;
				$longest = "";
				$currentTree->longestSuffix("",$longest);
				echo $longest ;
				echo "<br/></div>";
				downloadFeaturesFromFastaFile("allGenomes/".$buffer , str_replace(".fasta","", $buffer));
				$palindromePosition = getAllPosition("allPalindromes/".str_replace(".fasta", ".csv", $buffer),$longest);
				for ($o=0; $o < count($palindromePosition); $o++) 
				{ 
					echo " <div>information sur le palindrome a la position $palindromePosition[$o] :<br/><br/></div>";
					$info = findInformation( $palindromePosition[$o] , strlen($longest) , "ncbiFiles/".str_replace(".fasta", ".txt", $buffer));
					if (!isset($info[0])) 
					{
						echo "<div>le palindrome n'est pas situé sur une proteine<br/></div>";
					}
					for($k = 0 ; $k < count($info) ; $k++)
					{
						echo "<div>".substr($info[$k]["FeatureVersion"],1)."</div>";
						echo "<div>binformations sur la proteine :<br/></div>";
						$ttmp = explode("|", $info[$k]["protein_id"]);
						$proteineInfo = findProteinInformationFromGiId($ttmp[1]);
						foreach ($proteineInfo as $key => $value) 
						{
							echo "<div>$key : $value <br/></div>";
						}
					}
				}
				//print_r($info);
				$i++;
		  	}
		}
	}
	echo "<div>--------------------------------------SPECIFIQUE------------------------------------------<br/></div>";
	
	$handle = fopen($argv[1] , "r") or die("Couldn't get handle");
	if ($handle) 
	{
		
		while (!feof($handle)) 
		{
			$buffer = fgets($handle, 4096);
		  	$buffer = str_replace("\n", '', $buffer);
		  	if( strrchr($buffer,"/") !== FALSE )
		  	{
		  		$buffer = strrchr($buffer,"/");
		  		if ($buffer[0] == "/") 
			  	{
			  		$buffer = substr($buffer, 1);
			  	}
		  	}
		  	if (strlen($buffer) > 5 )
		  	 {

			  	$file_name_csv = str_replace(".fasta", '.csv', $buffer);
			  	$currentTree = new node();
				$currentTree->loadTreeWithCSVFile("allPalindromes/".$file_name_csv);
echo "<div><br/>--------------------------------------------------------------------------------------------------------<br/></div>";
				echo "<div><br/> le plus long palindrome specifique de $file_name_csv est : ";
				$longest = "";
				$AllArbre->longestSuffixNotInThisTree($currentTree , "" , $longest);
				echo $longest ;
				echo "<br/></div>";
				$palindromePosition = getAllPosition("allPalindromes/".str_replace(".fasta", ".csv", $buffer),$longest);
				for ($o=0; $o < count($palindromePosition); $o++) 
				{ 
					echo "<div> information sur le palindrome a la position $palindromePosition[$o] : <br/></div>";
					$info = findInformation( $palindromePosition[$o] , strlen($longest) , "ncbiFiles/".str_replace(".fasta", ".txt", $buffer));
					if (!isset($info[0])) 
					{
						echo "<div>le palindrome n'est pas situé sur une proteine </div>";
					}
					for($k = 0 ; $k < count($info) ; $k++)
					{
						echo "<div>".substr($info[$k]["FeatureVersion"],1)."</div>";
						echo "<div>informations sur la proteine :<br/></div>";
						$ttmp = explode("|", $info[$k]["protein_id"]);
						$proteineInfo = findProteinInformationFromGiId($ttmp[1]);
						foreach ($proteineInfo as $key => $value) 
						{
							echo "<div>$key : $value <br/></div>";
						}
					}
				}
				
		  	}
		}
	}





echo "</body>

      </html>";





?>