<?php
ini_set('display_errors', '1');
error_reporting(E_ALL); 
include 'palindrome.php' ;
set_time_limit(100000);
?>

<!DOCTYPE html>

<p> obtenir le plus grand palindrome d'une chaine fasta : + rajouter info NCBI </p>

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

if (isset($_POST["multiRecherche"])) 
{
	$arbreCommun = new node();
	$arbreCommun = makeCommonSuffixTreeFromFile($_FILES['fasta']['tmp_name']);
	$longest = "";
	$arbreCommun->longestSuffix("",$longest);
	echo "le plus long palindrome commun est : " ;
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
	echo "</form>" ;


echo "<p> obtenir le plus grand palindrome commun parmit plusieur chaine fasta : </p>";

echo '<form method="post" action="index.php" enctype="multipart/form-data" >
	       <label for="fasta"> choisir un fichier </label><br />
	       <input type="file" name="fasta" />';

	echo " <input type=\"submit\" name = \"submit\" value=\"envoyer\" ></code> ";
	echo " <input type=\"hidden\" name=\"multiRecherche\" value=\"multiRecherche\" ></code> ";
	echo "</form>";

?>