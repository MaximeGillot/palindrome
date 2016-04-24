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

########################### AFFICHE LE PLUS GRAND PALINDROME COMMUN ENTRE PLUSISUER GENOME D'UN FICHIER ####################################

$timestart=microtime(true);
$filePath = $argv[1] ;
$fasta1 = fromFastaFileIntoArray($filePath) ;
$arbre = ManacherPalindromeToSuffixTree($fasta1[0]);
$timeend=microtime(true);
$time=$timeend-$timestart;
echo $time ;
echo "\n";
echo "\n";

echo "\n";

for ($i=1; $i < count($fasta1) ; $i++) { 
	$timestart=microtime(true);

	echo "taille de la chaine courante : " ;
	echo strlen($fasta1[$i]);
	echo "\n";
	$arbre = CompareFastaWithTree($fasta1[$i] , $arbre );
	$new = "";
	$arbre-> longestSuffix("",$new);
	$timeend=microtime(true);
	$time=$timeend-$timestart;
	echo " temps d'exe : " ;
	echo $time ;
	echo "\n";
	echo " plus grand palindrome commun : ";
	echo $new ;
	echo "\n";
	echo "analyse $i finit sur ";
	echo count($fasta1);

	echo "\n ";
	echo "\n";
	echo "\n";

}

	$new = "";
	 $arbre-> longestSuffix("",$new);
	 echo $new ;
	echo "\n";



########################################################################
/*
$filePath = $argv[1] ;
$fasta1 = fromFastaFileIntoArray($filePath) ;
$arbre = ManacherPalindromeToSuffixTree($fasta1[0]);
print_r($arbre);*/

############################TMP####################################""
/*
$filePath = $argv[1] ;
$fasta1 = fromFastaFileIntoArray($filePath) ;
$arbre = ManacherPalindromeToSuffixTree($fasta1[13]);
$new = "";
	$arbre-> longestSuffix("",$new);
	echo $new ;
*/
?>