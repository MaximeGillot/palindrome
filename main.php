<?php 
include 'palindrome.php' ;

########################LIT UN FIchIER ET AFFICHE LE PLUS GRAND PALINDROME ###########################################
/*
$filePath = $argv[1] ;
$fasta1 = readFastaFileAsString($filePath) ;
echo ManacherBestPalindrome($fasta1);
*/
########################### TRANSFORME UN FICHIER EN UN ARBRE DES SUFFIXE ET AFFICHE LE PLUS GRAND PALINDROME ####################################
/*
$filePath = $argv[1] ;
$fasta1 = readFastaFileAsString($filePath) ;
$arbre = ManacherPalindromeToSuffixTree($fasta1);
$new = "";
echo $arbre-> longestSuffix("",$new);
*/

########################### AFFICHE LEs plus grand palindome commun de l'ensenble des fichier du dossier allGenome ainsi que le plus grand palindrome spécifique ####################################
/*
	$arbre = GetCommonPalindrome($argv[1]);

	$new = "";
	 $arbre-> longestSuffix("",$new);
	 echo $new ;
	echo "\n";

*/
/*
	$allGenomes = scandir("allGenomes/");
	$allArbreFusionner = new node();
	$file = fopen("resultat.csv","a+");
	$allArbre = array();

	for ($i=2; $i < count($allGenomes) ; $i++) 
	{ 
		echo "analyse en cours de ";
		echo $allGenomes[$i];
		$arbre = GetCommonPalindrome("allGenomes/".$allGenomes[$i]) ;
		$allArbreFusionner->addTree($arbre);
		array_push($allArbre,$arbre);
		$new = "";
		$arbre->longestSuffix("",$new);
		$arraytmp = array($allGenomes[$i],"nbSequence",$new);
		fputcsv($file,$arraytmp);
		echo "\n";
		echo " plus long palindrome : " ;
		echo $new ;
		echo "\n\n";
	}

	for ($i=0; $i < count($allArbre) ; $i++) 
	{ 
		echo "palindrome spécifique a ";
		echo $allGenomes[$i+2];
		echo " : ";
		$new = "";
		$allArbreFusionner->longestSuffixNotInThisTree( $allArbre[$i] , "" , $new  );
		echo $new ;
		echo "\n";
	}*/

########################################################################
/*
$filePath = $argv[1] ;
$fasta1 = fromFastaFileIntoArray($filePath) ;
$arbre = ManacherPalindromeToSuffixTree($fasta1[0]);
print_r($arbre);*/

############################TMP####################################""
/*$file = fopen("resultat.csv","a+");
$array = array("pseudomonas","25","ACGTCG","FTF");
fputcsv($file,$array);
fclose($file);
$file = fopen("resultat.csv","a+");
$array = array("pseudomonas","25","ACGTCG","FTF");
fputcsv($file,$array);
fclose($file);*/
//$arbre->addTree($arbre2);

//$arbre = GetCommonPalindrome("allGenomes/".$argv[1]) ;
//$arbre->treeToFile($argv[1],"");
/*
	$allGenomes = scandir("allGenomes/");
	$allArbreFusionner = new node();
	$file = fopen("resultat.csv","a+");

	for ($i=2; $i < count($allGenomes) ; $i++) 
	{ 
		echo "analyse en cours de ";
		echo $allGenomes[$i];
		$arbre = GetCommonPalindrome("allGenomes/".$allGenomes[$i]) ;
		$allArbreFusionner->addTree($arbre);
		$arbre->treeToFile(str_replace(".fasta", "", $allGenomes[$i]),"");
		echo "\n";
	}

	for ($i=2; $i < count($allGenomes) ; $i++) 
	{ 

		$currentArbre = new node();
		$currentArbre->loadTreeWithFile("trees/".str_replace(".fasta", ".tree", $allGenomes[$i]));
		echo "palindrome spécifique a ";
		echo $allGenomes[$i];
		echo " : ";
		$new = "";
		$allArbreFusionner->longestSuffixNotInThisTree( $currentArbre , "" , $new  );
		echo $new ;
		echo "\n";
		$plusGrandPalindromeSpecifique = $new ;
		$nbSequence = count(fromFastaFileIntoArray("allGenomes/".$allGenomes[$i]));
		$new = "";
		$currentArbre->longestSuffix("",$new);
		$plusGrandPalindromeCommun = $new ;
		$arraytmp = array($allGenomes[$i],$nbSequence,$plusGrandPalindromeCommun,$plusGrandPalindromeSpecifique);
		fputcsv($file,$arraytmp);

	}*/


$url = 'eutils.ncbi.nlm.nih.gov/entrez/eutils/efetch.fcgi?db=nucleotide&id=610413304';
$ch = curl_init($url);
$response = curl_exec($ch);
$json = json_decode($response, true);
curl_close($ch);
print_r($json);




?>