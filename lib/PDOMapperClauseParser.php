<?php // $Id$

// vim: expandtab sw=4 ts=4 sts=4:

/**
 * PDOMapperClauseParser
 *
 * @version     1.0
 * @copyright   2001-2012 Universite catholique de Louvain (UCL)
 * @author      Frederic Minne <zefredz@gmail.com>
 * @license     GNU LGPL version 3 or later https://www.gnu.org/copyleft/lesser.html
 * @package     pdocrud
 */

class PDOMapperClauseParser
{
    protected $schema;
    
    public function __construct( PDOMapperSchema $schema )
    {
        $this->schema = $schema;
    }
    
    public function parse( $clause )
    {
        if ( false === strpos( $clause, '%' ) )
        {
            return $clause;
        }
        
        $matchedParams = array();
        $matchedParamsRegexp = '/\%(.*?)\%/';
        
        if ( preg_match( $matchedParamsRegexp, $clause, $matchedParams ) )
        {
            if ( is_array( $matchedParams[1] ) )
            {
                foreach ( $matchedParams[1] as $param )
                {
                    $clause = str_replace ( "%{$param}%", $this->schema->getField($param), $clause  );
                }
            }
            else
            {
                $param = $matchedParams[1];
                $clause = str_replace ( "%{$param}%", $this->schema->getField($param), $clause  );
            }
            
            return $clause;
        }
        else
        {
            return $clause;
        }
    }
}
