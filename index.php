<?php
ini_set('display_errors', '1');
error_reporting(E_ALL); 
include 'palindrome.php' ;
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

$allPalindromes = scandir("allPalindromes/");

	if (count($allPalindromes) <= 2) // si aucun fichier .pal est calculer
	{
		echo "<h1> Aucune chaine est disponible pour l'analyse </h1>";
	}
	else // sinon on affiche les fichier dispo
	{	
		echo "<div> <h1> recherche de palindrome </h1> </div>";
		echo '<div> <form method="post" action="affichage.php">
		       <p> <label for="fasta"> choisir une chaine fasta </label> </p> 
		      <div> <select name="fasta" id="fasta"> ';
		       for ($i=2; $i < count($allPalindromes); $i++) 
		       { 
		       		echo "<option value=\"".$allPalindromes[$i]."\">".str_replace(".csv", "", $allPalindromes[$i])."</option>"; // j'enleve le .csv pour l'affichage
		       }
		echo "</select>";
		echo " <input type=\"submit\" value=\"Rechercher\" />";
		echo " <input type=\"hidden\" name=\"simpleRecherche\" value=\"simpleRecherche\" /> </div>";
		echo "</form> </div> "  ;
	}


echo "<p> obtenir le plus grand palindrome commun parmit plusieur chaine fasta : </p>";
echo "<p> il est conseiller de choisir des fichier fasta déjà calculer, les fichier déjà calculer sont présent dans la bar de recherche au dessus </p>";
echo '<div> <form method="post" action="affichage.php" enctype="multipart/form-data" >
	     <p>  <label for="fasta"> choisir un fichier </label> </p>
	      <div> <input type="file" name="fasta" /> ';

	echo "  <input type=\"submit\" name = \"submit\" value=\"envoyer\" />  ";
	echo " <input type=\"hidden\" name=\"multiRecherche\" value=\"multiRecherche\" /> </div> ";
	echo "</form> </div>";

echo "</body>

      </html>";

?>