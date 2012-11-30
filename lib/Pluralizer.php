<?php // $Id$

// vim: expandtab sw=4 ts=4 sts=4:

/**
 * Utility class to pluralize english words
 *
 * @access public
 * @version     1.0
 * @copyright   2001-2012 Universite catholique de Louvain (UCL)
 * @author      Frederic Minne <zefredz@gmail.com>
 * @license     GNU AGPL version 3 or later https://www.gnu.org/licenses/agpl-3.0.html
 * @package     pdocrud
 */
class Pluralizer
{
    /** irregular plural forms */
    var $plurals = array(
        'person'    => 'people',
        'man'       => 'men',
        'woman'     => 'women'
    );
    
    /**
     * Method to add one irregular plural form
     *
     * @access public
     * @param string singular - singular form of the word
     * @param string plural - plural form of the word
     */
    function addIrregularPlural( $singular, $plural )
    {
        $this->plurals[$singular] = $plural;
    }
    
    /**
     * Method to add a list of irregular plural forms
     *
     * @access public
     * @param array arrSingularPlural - associative array of irregulars
     *  forms with singular form as key and plural form as value
     */
    function addIrregularPluralList( $arrSingularPlural )
    {
        $this->plurals = array_merge( $this->plurals, $arrSingularPlural );
    }
    
    /**
     * Pluralize English names.
     *
     * This method tries to construct the grammatically
     * correct plural of an English word using common
     * grammar rules and a list of irregular plurals.
     *
     * @access protected
     * @param string name - singular word
     * @return string - plural of input word
     */
    function pluralize( $word )
    {
        if (array_key_exists($word, $this->plurals))
        {
            return $this->plurals[$word];
        }
        elseif ( substr($word, -1) == 'y' )
        {
            return substr($word, 0, -1 ) . 'ies';
        }
        elseif ( substr($word, -1) == 's' )
        {
            return $word . 'es';
        }
        else
        {
            return $word . 's';
        }
    }
}

