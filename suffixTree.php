<?php

class node {
    public $position;
    public $filsA;
    public $filsC;
    public $filsG;
    public $filsT;
    public $filsN;

    function  __construct() {
    
    }


	###################################################################
	# inser @string dans l'arbre des suffixes
	# @value 
	###################################################################
    function makeFils($string) {
    	switch ($string[0])
        {
            case 'A':
                if (isset($this->filsA)) 
                {
                    $this->filsA->makeFils(substr($string,1));
                }
                else
                {
                    $this->filsA = new node() ;
                    $this->filsA->makeFils(substr($string,1));
                }
                break;
            
            case 'C':
                if (isset($this->filsC)) 
                {
                    $this->filsC->makeFils(substr($string,1));
                }
                else
                {
                    $this->filsC = new node() ;
                    $this->filsC->makeFils(substr($string,1));
                }
                break;

            case 'G':
                if (isset($this->filsG)) 
                {
                    $this->filsG->makeFils(substr($string,1));
                }
                else
                {
                    $this->filsG = new node() ;
                    $this->filsG->makeFils(substr($string,1));
                }
                break;

            case 'T':
                if (isset($this->filsT)) 
                {
                    $this->filsT->makeFils(substr($string,1));
                }
                else
                {
                    $this->filsT = new node() ;
                    $this->filsT->makeFils(substr($string,1));
                }
                break;

            case 'N':
                if (isset($this->filsN)) 
                {
                    $this->filsN->makeFils(substr($string,1));
                }
                else
                {
                    $this->filsN = new node() ;
                    $this->filsN->makeFils(substr($string,1));
                }
                break;
        }

    } #fin de methode makeFils

	###################################################################
	# determiner si un suffixe est present dans l'arbre
	# @string chaine a tester
	# @return true si la chaine est présente dans l'arbre des suffixes , faux sinon
	###################################################################
    function isInTree($string) {
        if (strlen($string) == 0) {
            return true ;
        }
        else
        {
             switch ($string[0])
            {
                case 'A':
                    if (isset($this->filsA)) 
                    {
                       if( $this->filsA->isInTree(substr($string,1)) )
                       {
                        return true ;
                       }
                    }
                    else
                    {
                        return false ;
                    }
                    break;
                
                case 'C':
                    if (isset($this->filsC)) 
                    {
                        if ( $this->filsC->isInTree(substr($string,1)) )
                        {
                            return true ;
                        }
                    }
                    else
                    {
                        return false ;
                    }
                    break;

                case 'G':
                    if (isset($this->filsG)) 
                    {
                       if( $this->filsG->isInTree(substr($string,1)) )
                       {
                        return true ;
                       }
                    }
                    else
                    {
                        return false ;
                    }
                    break;

                case 'T':
                    if (isset($this->filsT)) 
                    {
                       if( $this->filsT->isInTree(substr($string,1)) )
                       {
                        return true ;
                       }
                    }
                    else
                    {
                       return false ;
                    }
                    break;

                case 'N':
                    if (isset($this->filsN)) 
                    {
                       if( $this->filsN->isInTree(substr($string,1)) )
                       {
                        return true ;
                       }
                    }
                    else
                    {
                       return false ;
                    }
                    break;
                }
            }
    } #fin de methode isInTree
		

    
	###################################################################
	# determiner le plus grand palindrome présent dans l'arbre
	# @current mettre a vide
	# @best un string vide a l'origine qui devienra le plus grand palindrome
	# @return rien
	# @exemple :
	# $best = "";
	# $arbre-> longestSuffix("",$best);
	# echo "le plus grand palindrome est : $best "; 
	###################################################################
    function longestSuffix($current , &$best ) {

        $sizelongest = strlen($best);

        if (isset($this->filsA)) 
        {
            if (strlen($current)+1 > $sizelongest ) {
                $best = $current."A";
                $sizelongest++;
            }
            $this->filsA->longestSuffix($current."A" , $best );
        }

        if (isset($this->filsC)) 
        {
            if (strlen($current)+1 > $sizelongest ) {
                $best = $current."C";
                $sizelongest++;
            }
            $this->filsC->longestSuffix($current."C" , $best );
        }

        if (isset($this->filsG)) 
        {
            if (strlen($current)+1 > $sizelongest ) {
                $best = $current."G";
                $sizelongest++;
            }
            $this->filsG->longestSuffix($current."G" , $best );
        }

        if (isset($this->filsT)) 
        {
            if (strlen($current)+1 > $sizelongest ) {
                $best = $current."T";
                $sizelongest++;
            }
            $this->filsT->longestSuffix($current."T" , $best );
        }
        
    } #fin de methode longestSuffix
}

?>