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


if (isset($_POST["simpleRecherche"])) 
{
	$csvFile = $_POST["fasta"];
	echo " le plus grand palindrome de ";
	echo str_replace(".csv", "", $csvFile); // pour plus de propreter je supprimer de l'affiahge le .csv
	echo " est : ";
	$arbre = new node(); // création d'un arbre vide
	$arbre->loadTreeWithCSVFile("allPalindromes/".$csvFile); // chargement de l'arbre depuis un fichier csv
	$longest = "";
	$arbre->longestSuffix("",$longest); // $longest devient égal au plus long palindrome
	echo $longest ; // affichage de $longest
}

if (isset($_POST["multiRecherche"])) 
{
	$arbreCommun = new node();
	$arbreCommun = makeInterSuffixTreeFromFile($_FILES['fasta']['tmp_name']);
	$longest = "";
	$arbreCommun->longestSuffix("",$longest);
	echo "<div>le plus long palindrome commun est : <br/>" ;
	echo "<h1>".$longest."</h1>" ;
	echo "<br/></div>";
	
	
	$AllArbre = new node();
	$handle = fopen($_FILES['fasta']['tmp_name'] , "r") or die("Couldn't get handle");
	if ($handle) 
	{
		$i=0;
		while (!feof($handle))  // construction de l'arbre commun et téléchargement des données .
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
				downloadFeaturesFromFastaFile("allGenomes/".$buffer , str_replace(".fasta","", $buffer));

			}
		}
	}


	
	echo "<div>--------------------------------------informations complementaires ------------------------------------------<br/></div>";
	
	$handle = fopen($_FILES['fasta']['tmp_name'] , "r") or die("Couldn't get handle");
	if ($handle) 
	{
		
		while (!feof($handle)) 
		{
			echo "<div>-----------------------------------------------------------------------------------------------------</div>";
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
				$longestSpecifique = "";
				$longestIndividuel = "";
				$currentTree->longestSuffix("",$longestIndividuel);
				$AllArbre->longestSuffixNotInThisTree($currentTree , "" , $longestSpecifique);
				echo "<h2>".str_replace(".fasta", "", $buffer)."</h2>" ;	

				$palindromePositionSpecifique = getAllPosition("allPalindromes/".str_replace(".fasta", ".csv", $buffer),$longestSpecifique);
				$palindromePositionIndividuel = getAllPosition("allPalindromes/".str_replace(".fasta", ".csv", $buffer),$longestIndividuel);

				echo " <h3> plus grand palindrome : $longestIndividuel </h3> <div> <br/> </div> ";
				echo "<table border='1'>";
				for ($o=0; $o < count($palindromePositionIndividuel); $o++)  // affichage des informations sur les proteines 
				{ 
					echo "<tr>";
						echo "<td colspan ='2' >position : ".$palindromePositionIndividuel[$o]."</td>" ;
					echo "</tr>";
					$info = findInformation( $palindromePositionIndividuel[$o] , strlen($longest) , "ncbiFiles/".str_replace(".fasta", ".txt", $buffer));
					if (!isset($info[0])) 
					{
						echo "<tr> <td> le palindrome n'est pas situé sur une proteine </td> </tr>";
					}
					for($k = 0 ; $k < count($info) ; $k++)
					{
						echo "<tr>";
								echo "<td>";
									echo substr($info[$k]["FeatureVersion"],1);
								echo "</td>";
							$ttmp = explode("|", $info[$k]["protein_id"]);
							$proteineInfo = findProteinInformationFromGiId($ttmp[1]);
							foreach ($proteineInfo as $key => $value) 
							{
								echo "<td>";
									echo "$key : $value ";
								echo "</td>";
							}
						echo "</tr>";
					}
				}
				echo "</table>";

				echo "<h3>plus grand palindrome specifiqe : $longestSpecifique </h3> <div> <br/> </div>";
				echo "<table border='1'>";
				for ($o=0; $o < count($palindromePositionSpecifique); $o++)  // affichage des informations sur les proteines 
				{ 
					echo "<tr>";
					 echo "<td colspan ='2' >position : ".$palindromePositionSpecifique[$o]."</td>" ;
					echo "</tr>";
					$info = findInformation( $palindromePositionSpecifique[$o] , strlen($longestSpecifique) , "ncbiFiles/".str_replace(".fasta", ".txt", $buffer));
					if (!isset($info[0])) 
					{	
						echo "<tr> <td> le palindrome n'est pas situé sur une proteine </td> </tr>";
					}
					for($k = 0 ; $k < count($info) ; $k++)
					{
						echo "<tr>";
								echo "<td>";
									echo substr($info[$k]["FeatureVersion"],1);
								echo "</td>";
							$ttmp = explode("|", $info[$k]["protein_id"]);
							$proteineInfo = findProteinInformationFromGiId($ttmp[1]);
							foreach ($proteineInfo as $key => $value) 
							{

								echo "<td>";
									echo "$key : $value ";
								echo "</td>";
							}
						echo "</tr>";
					}
				}
				echo "</table>";
				
		  	}
		}
	}


}


echo "</body>

      </html>";


?>