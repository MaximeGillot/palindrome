<?php 
include 'suffixTries.php' ;
###################################################################
# fonction qui détermine si une chaine de caracter est un palindrome au sens de gilles hunault : si les lettres sont différent. AAAAAAAA n'est pas  un palindrome
# @string chaine de caractere à tester
# @lengthInteresting taille minimun qu'un palindrome doit faire, exemeple si lengthIntersting = 4 , alors aba n'est pas un palindrome
# @return vrai si $string est un palindrome, faux sinon
###################################################################
function isPalindrome($string , $lengthInteresting ) {
	$length = strlen($string) ;
	if ($length < $lengthInteresting) {
		return false; 
	}
	for ($i=0; $i < $length/2 ; ++$i) { 
		if ($string[$i] != $string[$length-$i-1]) {
			return false;
		}
	}
	if(UniqueCharinString($string)) {
		return false ;
	}
	return true;
} # fin de fonction isPalindrome


###################################################################
# fonction qui détermine si une chaine de caracter est un palindrome au sens strict 
# @string chaine de caractere à tester
# @return vrai si $string est un palindrome, faux sinon
###################################################################
function isPalindromeStrict($string) {
	$sizeOfString = strlen($string) ;
	for ($i=0; $i < $sizeOfString/2 ; ++$i) { 
		if ($string[$i] != $string[$sizeOfString-$i-1]) {
			return false;
		}
	}
	return true;
} # fin de fonction isPalindromeStrict


###################################################################
# fonction qui détermine si une chaine de caracter est constinuer d'un unique caractere
# @string chaine de caractere à tester
# @return vrai si $string est une chaine qui est continuer d'un unique character
###################################################################
function UniqueCharinString($string) {
	$sizeOfString = strlen($string)-1 ;
	for ($i=0; $i < $sizeOfString; ++$i) { 
		if ($string[$i] != $string[$i+1]) {
			return false ;
		}
	}
	return true;
} # fin de fonction UniqueCharInString



###################################################################
# fonction qui transforme un fichier en sequence FASTA prore, en supprimant le nom et les \n
# @path chemin vers le fichier
# @return retour une chaine de caracter qui correspond a la sequence FASTA uniquement, avec suppresion des \n
###################################################################
function readFastaFileAsString($path) {
	$file = file_get_contents($path);
	$file = strstr($file,"\n");
	$file = str_replace("\n", '', $file);
	return $file;
} # fin de fonction readFastaFileAsString


###################################################################
# fonction qui transforme un fichier en sequence FASTA propre, en chaine pour l'algo manacher , donc avec des # entre chaque lettre avant et aprés 
# CGTC => #C#G#T#C#
# @string string a transformer
# @return retour une chaine de caracter qui correspond au string prêt pour manacher
###################################################################
function fromFastaToManacher($string) {
	$chaine = "#" ;
	for ($i=0; $i < strlen($string) ; $i++) { 
			$chaine .= $string[$i];
			$chaine .= "#";
		}	
	return $chaine;
} # fin de fonction fromFastaToManacher

###################################################################
# fonction qui transforme un fichier contenant plusieur génome en un tableau de sequence fasta propre
# @path chemin vers le fichier a parser
# @return un tableau contenant les sequences FASTA uniquement, avec suppresion des \n
###################################################################
function fromFastaFileIntoArray($path) {
	$file = file_get_contents($path);
	$array = explode(">", $file);
	array_shift($array);
	$arraylength = count($array);
	for ($i=0; $i < $arraylength; $i++) { 
		$array[$i] = strstr($array[$i],"\n");
		$array[$i] = str_replace("\n", '', $array[$i]);
	}
	return $array;
} # fin de fonction fromFastaFileIntoArray

###################################################################
# fonction qui determerne tout les palindromes et construit l'arbre des suffixes associé
# @string sequence fasta a analyser
# @return retourne un arbre des suffixes contenant tous les palindromes
###################################################################
function ManacherPalindromeToSuffixTree($string) {

	$string = fromFastaToManacher($string);

	$center = 0 ;
	$size = 2;
	$result = new node();
	$sizeOfString = strlen($string) ;

	while ($center < $sizeOfString ) { 
		
		do {
			$current = str_replace("#","",substr($string  , $center - $size , 2*$size+1)) ; 
			if (isPalindrome($current,5) === true ) {
					$result->makeFils($current);
				
			}
			++$size;
		} while ( isPalindromeStrict( $current ) );

		 $size = 2 ;
		 ++$center ; 
		
	}
	return $result ;
} # fin de fonction ManacherPalindromeToSuffixTree

###################################################################
# fonction qui détermine tout les palindromes ainsi que leur position et crée un fichier csv associé
#
###################################################################
function FromFastaFileToCSVFile($path , $fileName) {
	$string = readFastaFileAsString($path);
	$string = fromFastaToManacher($string);
	$center = 0 ;
	$size = 2;
	$result = array();
	$sizeOfString = strlen($string) ;

	while ($center < $sizeOfString ) { 
		
		do {
			$current = str_replace("#","",substr($string  , $center - $size , 2*$size+1)) ; 
			if (isPalindrome($current,5) === true ) {
				if(!isset($result[$current]))
				{
					$result[$current] = array() ;
				}
				array_push($result[$current],  floor((($center - $size)/2)) +1 );
				
			}
			$size+=2;
		} while ( isPalindromeStrict( $current ) );

		 $size = 2 ;
		 ++$center ; 
		
	}

	$file = fopen($fileName,"a+");

	foreach ($result as $key => $value) 
	{
		$array = array();
		array_push($array, $key);
		foreach ($value as $value2) 
		{
			array_push($array, $value2);
		}
		fputcsv($file,$array);
	}
		

	
} # fin de fonction ManacherPalindromeToCSVFile

###################################################################
# fonction qui determine le plus grand palindrome
# @string sequence fasta a analyser
# @return string équivalent au plus grand palindrome
# PAS A JOUR
###################################################################
function ManacherBestPalindrome($string) { 
	$string = fromFastaToManacher($string);
	$center = 0 ;
	$size = 0;
	$result = $string[0];
	$sizeOfString = strlen($string) ;


	while ($center < $sizeOfString ) { 
		
		do {
			$current = str_replace("#","",substr($string  , $center - $size , 2*$size+1)) ; 
			if (isPalindrome($current,4) === true ) {
				if (strlen($result) < strlen($current)) {
					echo "meilleur trouvé : " ;
					echo $current ;
					echo " taille : " ;
					echo strlen($current);
					echo "\n";
					$result = $current ;
				}
			}
		 	++$size;
		} while ( isPalindromeStrict( $current ) );
		 $center++ ; 
		 $size = 0 ;
		  
		
	}
	return $result ;
} # fin de fonction ManacherBestPalindrome


###################################################################
# fonction qui detecte tout les palindromes de toutes les tailles de façon naive. conpléxiter quadratique
# @string sequence FASTA
###################################################################
function findPalindromeBruteForce($string) {
	$length = strlen($string);
	for ($size = $length; $size > 5 ; $size--) { 
		for ($i=0; $i < $length - $size ; ++$i) { 
			$currentString = substr($string, $i , $size) ;
			if (isPalindrome($currentString)) {
				echo " palindrome trouver " ;
				echo $currentString ;
				echo " position $i et taille $size " ;
				echo "\n" ;
			}
		}
	}
} # fin de fonction findPalindromeBruteForce

###################################################################
# fonction qui determine tout les palindromes communs contenue dans la sequence fasta $string et l'arbre des suffixe $tree
# @string la sequence fasta a analyser
# @$tree l'arbre des sufixe a comparer
# @return un arbre des suffixes contenant les palindrome commun a @string et @tree
###################################################################
function CompareFastaWithTree($string , $tree ) {
	$string = fromFastaToManacher($string);
	$center = 0 ;
	$size = 3;
	$sizeOfString = strlen($string) ;
	$result = new node();
	
	while ($center < $sizeOfString ) { 
		
		do {
			$current = str_replace("#","",substr($string  , $center - $size , 2*$size+1)) ; 
			if (isPalindrome($current,5) === true ) {
				if ($tree->isInTree($current)) {
					$result->makeFils($current);
				}
			}
		 	++$size;
		} while ( isPalindromeStrict( $current )  && $tree->isInTree($current) );

		 $size = 2 ;
		 ++$center ; 
		 $continue = true ;
		
	}
	return $result;
} # fin de fonction CompareFastaWithTree

###################################################################
# fonction qui retourne l'abre des suffixes des palindromes commun à plusieur génome comprit dans le même fichier @path
# @path chemin vers le fichier
# @return arbre des suffixes
###################################################################
function GetCommonPalindrome($path) 
{

	$fasta1 = fromFastaFileIntoArray($path) ;
	$arbre = ManacherPalindromeToSuffixTree($fasta1[0]);

	for ($i=1; $i < count($fasta1) ; $i++) 
	{ 
		$arbre = CompareFastaWithTree($fasta1[$i] , $arbre );
	}
	return $arbre ;
} # fin de fonction GetCommonPalindrome


###################################################################
# crée tous les fichier .pal des chaine fasta comprit dans le fichier $path
# @path chemin vers le fichier qui comprend l'ensemble des chaine fasta a traiter
# @return rien
###################################################################
function makeAllPalFile($path)
{
	$handle = fopen($path , "r") or die("Couldn't get handle");
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
		  	$file_name_csv = str_replace(".fasta", '.csv', $buffer);
		    if (!file_exists("allPalindromes/".$file_name_csv)) 
		    {
		    	FromFastaFileToCSVFile("allGenomes/".$buffer , "allPalindromes/".$file_name_csv ) ;
		    }
		}
	}
} #fin de fonction makeAllPalFile

###################################################################
# fonction qui retourne l'arbre des suffixe commun des chaine fasta indiquer dans $path
# reconstruit les .pal si besoin
# $path chemin vers le fichier qui contient l'ensenble des chaine fasta à traiter
# @return l'arbre des suffixes commun
###################################################################
function makeCommonSuffixTreeFromFile($path)
{
	makeAllPalFile($path);
	$handle = fopen($path , "r") or die("Couldn't get handle");
	if ($handle) 
	{
		$arbreDesSuffixesCommun = new node();
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
		  	$file_name_csv = str_replace(".fasta", '.csv', $buffer);
		    $currentTree = new node();
			$currentTree->loadTreeWithCSVFile("allPalindromes/".$file_name_csv);
			$arbreDesSuffixesCommun->addTree($currentTree);
		}
	}
	return $arbreDesSuffixesCommun;
}

function makeInterSuffixTreeFromFile($path)
{
	makeAllPalFile($path);
	$handle = fopen($path , "r") or die("Couldn't get handle");
	if ($handle) 
	{
		$i = 0;
		$arbreDesSuffixesCommun = new node();
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
		  	$file_name_csv = str_replace(".fasta", '.csv', $buffer);
		    $currentTree = new node();
			$currentTree->loadTreeWithCSVFile("allPalindromes/".$file_name_csv);
			if ($i == 0 ) 
			{
				//echo "buffer : $buffer ";
				$arbreDesSuffixesCommun->loadTreeWithCSVFile("allPalindromes/".$file_name_csv);
			}
			else
			{
				
				if (strlen($buffer) > 5) 
				{
					$arbreDesSuffixesCommun->inter($currentTree);
				}
				
			}
			$i++;
		}
	}
	return $arbreDesSuffixesCommun;
}

function getAllPosition($path , $palindrome )
{
	$tab = array();
	$file = fopen($path, "r");
	while (($data = fgetcsv($file )) !== FALSE) 
	{
	    if ($data[0] == $palindrome) 
	    {
	    	for ($i=1; $i < count($data) ; $i++) 
	    	{ 
	    		array_push($tab, $data[$i]);
	    	}
	    }
	}
	return $tab;
}

?>