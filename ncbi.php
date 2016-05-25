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
function downloadFileFromGi($giId , $path , $retmode = "xml" , $rettype = "native")
{
	$url = 'eutils.ncbi.nlm.nih.gov/entrez/eutils/efetch.fcgi?db=nuccore&id='.$giId.'&retmode='.$retmode.'&rettype='.$rettype;
	//echo "$url";
	$ch = curl_init($url);
	$response = curl_exec($ch);
	$json = json_decode($response, true);
	curl_close($ch);
	print_r($json);
	//print_r($json);
}

/**
* @brief return un tableau contenant les informations interessant 
*
* @param position la position du palindrome dans la chaine
* @param taille la taille du palindrome
* @param path chemin vers le fichier 
* @return un tableau contenant les informations correspondant interessant
* @author GillotMaxime
*/
function findInformation($position , $taille , $path )
{
	
}

downloadFileFromGi("NC_016894" , "ncbiFiles/test" , "text" , "ft");

//NC_016894
?>