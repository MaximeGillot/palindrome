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

function needDownloading($path)
{
	if(file_exists($path))
	{
		$file = fopen($path , "r") or die("Couldn't get handle");
		while (!feof($file)) 
		{
			$buffer = fgets($file, 4096);
			if(strpos($buffer, "<ERROR>Unable to obtain query") > 0 )
			{
				return true ;
			} 

		}
		return false;
	}
	else
	{
		return true ; 
	}
}


/**
* @brief télécharger la table feature correspondant a la chaine fasta @a $fastaFile
* @param giId numéro d'accession
* @param path chemin ver le dossier de reception
* @param retmode mode de retour , xml ou text
* @return retourne une chaine de charactere correspondent au GI id 
* @author GillotMaxime
*/
function downloadFeaturesFromFastaFile( $fastaFile, $name , $db = "nucleotide" , $retmode = "text" , $rettype = "ft" )
{

	$file = fopen($fastaFile, "r");
	$search = fgets ( $file ) ; // premiere ligne du fichier
	fclose($file);
	$search = strstr($search," ");
	$positionVirgule = strpos($search, ",");
	if($positionVirgule !== False )
	{
		$search = substr($search, 0 , $positionVirgule);
	}
	$search = substr($search, 1);	// on initilise le term pour la requete


	if(needDownloading("ncbiFiles/".$name.".txt") == true) // on test si le fichier a déjà été télécharger 
	{
		
		sleep(1);
		$search = str_replace(" ", "+", $search);
		$search = str_replace("\n", "", $search);
		$base = "http://eutils.ncbi.nlm.nih.gov/entrez/eutils/";
		$url = $base . "esearch.fcgi?db=nucleotide&term=%22".$search."%22&usehistory=y"; // %22 est utiliser a la place de " "
	//	echo "url : $url \n" ;
		$response = "";
		do
		{
			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_SSLVERSION,3);
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			$response = curl_exec($ch);
			$search = substr($search, 0 , strrpos($search, "+"));
			$url = $base . "esearch.fcgi?db=nucleotide&term=%22".$search."%22&usehistory=y";
		}
		while( strpos("$response", "No items found.") !== false ); // on repete l'operation en enlevant le derniere mots de $search jusqu'a temps d'avoir une reponse autre que " aucun objet trouvé".

		$webEnv = substr($response, strpos($response, "<WebEnv>")+8); 
		$webEnv = substr($webEnv, 0 , strpos($webEnv, "</WebEnv>")); // on extraie <webEnv> pour faire le lien dans la requete efetch
		curl_close($ch);


		$url = $base . "efetch.fcgi?db=nucleotide&query_key=1&WebEnv=".$webEnv."retmode='.$retmode.'&rettype='.$rettype"; // reqeute efetch pour récuperer la features table
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_SSLVERSION,3);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$response = curl_exec($ch);
		$file = fopen("ncbiFiles/".$name.".txt" , "w+" ); // on insere le résultat dans le fichier du dossier ncbi correspondent.
		fwrite( $file , $response );
	}
	else
	{
		//echo " le fichier $name existe déjà , le supprimer pour forcer le téléchargement.\n";
	}
	
	
}

/**
* @brief return un tableau contenant les informations interessantes , le palindrome peut etre sur plusieur protein , cela et prit  * en compte 
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
		$current_ref;
		while (!feof($file)) 
		{
			$buffer = fgets($file, 4096);
			if(preg_match("/^>Feature\s[a-zA-Z0-9|._]*$/", $buffer))
			{
				$current_feature_ref = substr($buffer, 0 , strlen($buffer)-1);				
			}
		  	if (preg_match("/^([0-9]*\s[0-9]*\sgene\s)$/", $buffer)) 
		  	{
				$tabPosition = explode("\t", $buffer);
				if ( (  $position > $tabPosition[0]  &&   $position < $tabPosition[1]  ) ) 
				{
					$find = true ;

				}
				else
				{
					if ($find == true) 
					{
						$tabTmp["FeatureVersion"] = $current_feature_ref ;
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

/**
* @brief retourne un tableau contenant des informations sur la proteine avec comme id @a $giId
*
* @param giId giId de la proteine , 
* @return un tableau contenant les informations interessantes
* @author GillotMaxime
*/
function findProteinInformationFromGiId($giId)//ADL13830
{
	
	$url = "eutils.ncbi.nlm.nih.gov/entrez/eutils/efetch.fcgi?db=protein&id=".$giId."&retmode=xml";
	// enlever le commentaire pour affichier l'adresse de la requete .
	//echo $url;
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_SSLVERSION,3);
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$response = curl_exec($ch);
	$GBSeq_definition = substr($response, strpos($response, "<GBSeq_definition>")+18); 
	$GBSeq_definition = substr($GBSeq_definition, 0 , strpos($GBSeq_definition, "</GBSeq_definition>"));
	$result["GBSeq_definition"] = $GBSeq_definition;
	return $result ;
}

//print_r(findProteinInformationFromGiId("ADL13830"));

//print_r(findInformation(600 , 10 , $argv[1]));

//downloadFeaturesFromFastaFile($argv[1]);


?>