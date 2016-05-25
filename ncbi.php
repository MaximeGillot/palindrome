<?php

/**
* @brief return le Gi id a partir d'un chemin vers une chaine fasta selon le fichier liste_gff.txt
*
* @param path chemin vers la chaine fasta
* @return retourne une chaine de charactere correspondent au GI id 
* @author GillotMaxime
*/
function findGiIdFromFastaFile($path)
{

}

/**
* @brief télécharger le fichier correspondant au @a giId au format @a retmode par défault et le met dans @a path
* utiliser text et ft pour la feature table , sinon xml et native pour full information
* @param giId numéro d'accession
* @param path chemin ver le dossier de reception
* @param retmode mode de retour , xml ou text
* @return retourne une chaine de charactere correspondent au GI id 
* @author GillotMaxime
*/
function downloadFileFromGi($giId , $path , $db = "nuccore" , $retmode = "text" , $rettype = "ft" )
{
	$url = 'eutils.ncbi.nlm.nih.gov/entrez/eutils/efetch.fcgi?db='.$db.'&id='.$giId.'&retmode='.$retmode.'&rettype='.$rettype;
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_SSLVERSION,3);
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$response = curl_exec($ch);
	$file = fopen($path , "w+" );
	fwrite( $file , $response );
	curl_close($ch);

}

/**
* @brief return un tableau contenant les informations interessant , le palindrome peut etre sur plusieur protein , cela et prit  * en compte 
*
* @param position la position du palindrome dans la chaine
* @param taille la taille du palindrome
* @param path chemin vers le fichier 
* @return un tableau contenant les informations correspondant interessant
* @author GillotMaxime
*/
function findInformation($position , $taille , $path )
{

	$file = fopen($path , "r") or die("Couldn't get handle");
	if ($file) 
	{
		$find = false ;
		$result = array();
		$tabTmp;
		while (!feof($file)) 
		{
			$buffer = fgets($file, 4096);
		  	if (preg_match("/^([0-9]*\s[0-9]*\sgene\s)$/", $buffer)) 
		  	{
				$tabPosition = explode("\t", $buffer);
				if ( ( $tabPosition[0] < $position && $tabPosition[1] > $position ) ) 
				{
					$find = true ;

				}
				else
				{
					if ($find == true) 
					{
						array_push($result, $tabTmp);
						$tabTmp = array();
						if ($position + $taille < $tabPosition[0]) 
						{
							$find = false ;
						}
					}
					else
					{
						$find = false ;
					}
					
				}
		  	}
		  	else
		  	{
		  		if ($find == true ) 
		  		{
		  			$tab = explode("\t", $buffer );
		  			
		  			if (isset($tab[3])) 
		  			{
		  				$tabTmp[$tab[3]] = $tab[4];
		  			}
		  		}
		  	}
		}
	}
	return $result;
}

?>