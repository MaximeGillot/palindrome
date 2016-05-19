<?php
ini_set('display_errors', '1');
error_reporting(E_ALL); 
include 'palindrome.php' ;
echo "ok";
?>

<!DOCTYPE html>





<p> obtenir le plus grand palindrome </p>

<p> obtenir le plus grand palindrome commun </p>

</html>

<?php


if (isset($_POST["simpleRecherche"])) 
{
	echo " le plus grand palindrome de ";
	echo $_POST["fasta"] ;
	echo " est : " ;
	$csvFile = str_replace("fasta", "csv", $_POST["fasta"]) ;
	if (!file_exists("allPalindromes/".$csvFile)) 
	{
		FromFastaFileToCSVFile("allGenomes/".$_POST["fasta"] , "allPalindromes/".$csvFile );
	}
	
	$arbre = new node();
	$arbre->loadTreeWithCSVFile("allPalindromes/".$csvFile);
	$longest = "";
	$arbre->longestSuffix("",$longest);
	echo $longest ;

	
}

$allGenomes = scandir("allGenomes/");

	echo "<h1> recherche de palindrome <h1>";

	echo '<form method="post" action="index.php">
	       <label for="fasta"> choisir une chaine fasta </label><br />
	       <select name="fasta" id="fasta"> </form>';
	       for ($i=2; $i < count($allGenomes); $i++) 
	       { 
	       		echo "<option value=\"".$allGenomes[$i]."\">".$allGenomes[$i]."</option>";
	       }
	echo "</select>";
	echo " <input type=\"submit\" value=\"Rechercher\" ></code> ";
	echo " <input type=\"hidden\" name=\"simpleRecherche\" value=\"simpleRecherche\" ></code> ";
	echo "</form>"
?>