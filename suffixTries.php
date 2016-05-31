<?php

class node {

    public $pair;
    public $impair;
    public $fils;

    function  __construct() 
    {
    	$this->pair = 0 ;
        $this->impair = 0 ;
        $this->fils = [] ;
    }

    /**
    * @brief permet de construire un arbre des suffixes en insérant @a string dans l'arbre des suffixes @a this
    * @param string le chaine à insérer dans l'arbre
    * @author GillotMaxime
    */
    function makeFils($string) {
        if ( strlen($string) <= 1 ) 
        {
            if ( strlen($string) == 0 ) 
            {
                $this->pair = 1 ;
            }
            else
            {
                if (!isset($this->fils[$string[0]])) 
                {
                    $this->fils[$string[0]] = new node() ;
                }
                $this->fils[$string[0]]->impair = 1 ;
            }
            
        }
        else
        {
            if (!isset($this->fils[$string[0]])) 
            {
                $this->fils[$string[0]] = new node() ;
            }
            $this->fils[$string[0]]->makeFils(substr($string,1,strlen($string)-2));
        }
    } #fin de methode makeFils

    /**
    * @brief détermine si @a string est présent dans l'arbre des suffixes @a this
    * @param string le palindrome à tester
    * @return renvoit vrai si @a string est comprit dans l'arbre des suffixes , sinon faux .
    * @author GillotMaxime
    */
    function isInTree($string) 
    {
        if (strlen($string) <= 1 ) 
        {
            if ( strlen($string) == 0 ) 
            {
                if ( $this->pair > 0 ) 
                {
                    return true ;
                }
                else
                {
                    return false ;
                }

            }
            else
            {
                if (isset($this->fils[$string[0]])) 
                {
                    if ($this->fils[$string[0]]->impair > 0) 
                    {
                        return true ;
                    }
                    else
                    {
                        return false ;
                    }
                }
            }
        }
        else
        {

            if (isset($this->fils[$string[0]])) 
            {
                if( $this->fils[$string[0]]->isInTree(substr($string,1,strlen($string)-2))  )
                {
                    return true ;
                }
                else
                {
                    return false ;
                }
            }
            else
            {
                return false ;
            }
        }

    } #fin de methode isInTree

    /**
    * @brief retourne le plus long palindrome comprit dans l'arbre des suffixes @a this , l'appel de la fonction se fait ainsi :
    * $best = "";
    * $arbre->longestSuffix( "" ,  $best );
    *
    * @param current mettre a "" lors de l'appel de la fonction
    * @param best chaine de caractere passer par référence qui aura la valeur du plus grand palindrome comprit dans l'arbre des suffixes
    * @return pas de retour, le résultat est comprit dans @a best
    * @author GillotMaxime
    */
    function longestSuffix($current , &$best ) 
    {

        $sizelongest = strlen($best);

        if ($this->pair > 0 ) 
        {
            if ( $sizelongest < strlen($current)*2 ) 
            {
                $best = $current.strrev($current);
                $sizelongest = strlen($best);
            }
        }
        elseif ($this->impair > 0) 
        {
            if ( $sizelongest < ( strlen($current)*2 ) -1 ) 
            {
                $best = $current.substr(strrev($current), 1 ) ;
                $sizelongest = strlen($best);
            }
        }

        foreach ($this->fils as $key => $value) 
        {
            $this->fils[$key]->longestSuffix($current.$key , $best );
        }        
    } #fin de methode longestSuffix

        /**
    * @brief retourne le plus long palindrome comprit dans l'arbre des suffixes @a this avec comme valeur pair ou impaire == @a number , l'appel de la fonction se fait ainsi :
    * $best = "";
    * $arbre->longestSuffix( "" ,  $best );
    *
    * @param current mettre a "" lors de l'appel de la fonction
    * @param best chaine de caractere passer par référence qui aura la valeur du plus grand palindrome comprit dans l'arbre des suffixes
    * @return pas de retour, le résultat est comprit dans @a best
    * @author GillotMaxime
    */
    function longestSuffixWithNumber($current , &$best , $number ) 
    {

        $sizelongest = strlen($best);

        if ($this->pair == $number ) 
        {
            if ( $sizelongest < strlen($current)*2 ) 
            {
                $best = $current.strrev($current);
                $sizelongest = strlen($best);
            }
        }
        elseif ($this->impair == $number) 
        {
            if ( $sizelongest < ( strlen($current)*2 ) -1 ) 
            {
                $best = $current.substr(strrev($current), 1 ) ;
                $sizelongest = strlen($best);
            }
        }

        foreach ($this->fils as $key => $value) 
        {
            $this->fils[$key]->longestSuffixWithNumber($current.$key , $best , $number);
        }        
    } #fin de methode longestSuffix

        ###################################################################
    # return le plus long palindrome comprit dans $tree mais pas dans $this en double
    # @tree un arbre des suffixes
    # @return string: le plus long suffixe
    ###################################################################
    function longestSuffixNotInThisTree( $tree  , $current ,  &$best  ) {
        
       $sizelongest = strlen($best);

       if ($tree->pair > 0 && $this->pair == 1 ) 
        {
            if ( $sizelongest < strlen($current)*2 ) 
            {
                $best = $current.strrev($current);
                $sizelongest = strlen($best);
            }
        }
        elseif ($tree->impair > 0 && $this->impair == 1) 
        {
            if ( $sizelongest < ( strlen($current)*2 ) -1 ) 
            {
                $best = $current.substr(strrev($current), 1 ) ;
                $sizelongest = strlen($best);
            }
        }

       foreach ($tree->fils as $key => $value) 
       {
            if (isset($this->fils[$key])) 
            {
                $this->fils[$key]->longestSuffixNotInThisTree($tree->fils[$key] , $current.$key , $best );
            }
          
       }

    } #fin de methode longestSuffixNotInThisTree

     /**
    * @brief ajout à @a this l'arbre des suffixes @a tree
    * @param tree l'arbre des suffixes à ajouter a @a this
    * @author GillotMaxime
    */
    function addTree($tree) {

        if ($tree->pair > 0) 
        {
            $this->pair++;
        }

        if ($tree->impair > 0) 
        {
            $this->impair++;
        }
             
        foreach ($tree->fils as $key => $value) 
        {
                if (!isset($this->fils[$key])) 
                {
                    $newNode = new node();
                    $this->fils[$key] = $newNode;
                }
                $this->fils[$key]->addTree($tree->fils[$key]);
        }

    } #fin de methode addTree

    // défnie tous els valeur de $this a 0
    function setToZero()
    {
        $this->pair = 0 ;
        $this->impair = 0 ;
        foreach ($this->fils as $key => $value)
         {
            $this->fils[$key]->setToZero();
        }
    }

    function inter($tree)
    {
        if ($tree->pair > 0 && $this->pair > 0) 
        {
            $this->pair++;
        }
        else
        {
            $this->pair = 0 ;
        }

        if ($tree->impair > 0 && $this->impair > 0) 
        {
            $this->impair++ ;
        }
        else
        {
            $this->impair = 0 ;
        }

        foreach ($this->fils as $key => $value) 
        {
            if (isset($tree->fils[$key])) 
            {
                 $this->fils[$key]->inter($tree->fils[$key]);
            }
            else
            {
                 $this->fils[$key]->setToZero();
            }
        }
    }



    /**
    * @brief charge l'arbre des suffixes @a this depuis le fichier csv @a path
    * @param path chemin vers le fichier
    * @author GillotMaxime
    */
    function loadTreeWithCSVFile( $path )
    {   
        $file = fopen($path, "r");
        while (($data = fgetcsv($file )) !== FALSE) 
        {
            $this->makeFils($data[0]);
        }
       
    } #fin de méthode loadTreeWithFile


    /**
    * @brief charge un arbre des suffixe avec un fichier .tree
    * @param path chemin vers le fichier .tree
    * @author GillotMaxime
    */
    function loadTreeWithTreeFile( $path )
    {   
        $handle = fopen($path , "r") or die("fichier introuvable , mauvais chemin");
        if ($handle) 
        {
            while (!feof($handle)) 
            {
                $buffer = fgets($handle, 4096);
                $this->makeFils(str_replace("\n", "",$buffer));
            }
            fclose($handle);
        }
    } #fin de méthode loadTreeWithTreeFile

}

?>