<?php
ini_set('display_errors', '1');
error_reporting(E_ALL); 
include 'palindrome.php' ;
include 'ncbi.php';
set_time_limit(100000);



// prend en argv[1] un fichier qui contient plusieur chaine fasta, affiche le plus gran commun , le pluss grand de chaque et le plus grand spécifique .


	$arbreCommun = new node();
	$arbreCommun = makeInterSuffixTreeFromFile($argv[1]);
	$longest = "";
	$arbreCommun->longestSuffix("",$longest);
	echo "le plus long palindrome commun est : " ;
	echo $longest ;
	echo "\n";
	
	
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
				echo "\n";
				echo "--------------------------------------------------------------------------------------------------------\n";
				echo "le plus long palindrome de $buffer est : " ;
				$longest = "";
				$currentTree->longestSuffix("",$longest);
				echo $longest ;
				echo "\n";
				downloadFeaturesFromFastaFile("allGenomes/".$buffer , str_replace(".fasta","", $buffer));
				$palindromePosition = getAllPosition("allPalindromes/".str_replace(".fasta", ".csv", $buffer),$longest);
				for ($o=0; $o < count($palindromePosition); $o++) 
				{ 
					echo " information sur le palindrome a la position $palindromePosition[$o] : \n\n";
					$info = findInformation( $palindromePosition[$o] , strlen($longest) , "ncbiFiles/".str_replace(".fasta", ".txt", $buffer));
					if (!isset($info[0])) 
					{
						echo "le palindrome n'est pas situé sur une proteine";
					}
					for($k = 0 ; $k < count($info) ; $k++)
					{
						echo $info[$k]["FeatureVersion"];
						echo "\n";
						echo "informations sur la proteine :\n";
						$ttmp = explode("|", $info[$k]["protein_id"]);
						$proteineInfo = findProteinInformationFromGiId($ttmp[1]);
						foreach ($proteineInfo as $key => $value) 
						{
							echo "$key : $value \n";
						}
					}
				}
				//print_r($info);
				$i++;
		  	}
		}
	}
	echo "--------------------------------------SPECIFIQUE------------------------------------------\n";
	
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
				echo "\n";
				echo "--------------------------------------------------------------------------------------------------------\n";
				echo "\n le plus long palindrome specifique de $file_name_csv est : ";
				$longest = "";
				$AllArbre->longestSuffixNotInThisTree($currentTree , "" , $longest);
				echo $longest ;
				echo "\n";
				$palindromePosition = getAllPosition("allPalindromes/".str_replace(".fasta", ".csv", $buffer),$longest);
				for ($o=0; $o < count($palindromePosition); $o++) 
				{ 
					echo " information sur le palindrome a la position $palindromePosition[$o] : \n\n";
					$info = findInformation( $palindromePosition[$o] , strlen($longest) , "ncbiFiles/".str_replace(".fasta", ".txt", $buffer));
					if (!isset($info[0])) 
					{
						echo "le palindrome n'est pas situé sur une proteine";
					}
					for($k = 0 ; $k < count($info) ; $k++)
					{
						echo $info[$k]["FeatureVersion"];
						echo "\n";
						echo "informations sur la proteine :\n";
						$ttmp = explode("|", $info[$k]["protein_id"]);
						$proteineInfo = findProteinInformationFromGiId($ttmp[1]);
						foreach ($proteineInfo as $key => $value) 
						{
							echo "$key : $value \n";
						}
					}
				}
				
		  	}
		}
	}











?>