<?php // $Id$

// vim: expandtab sw=4 ts=4 sts=4:

/**
 * Description
 *
 * @version     1.0
 * @copyright   2001-2012 Universite catholique de Louvain (UCL)
 * @author      Frederic Minne <zefredz@gmail.com>
 * @license     GNU LGPL version 3 or later https://www.gnu.org/copyleft/lesser.html
 * @package     pdocrud
 */

class PDOSQLScript extends SQLScript
{
    protected $_pdo;
    
    public function __construct( $pdo )
    {
        $this->_pdo = $pdo;
    }
    
    public function execute( $sqlScript )
    {
        $queries = $this->parseMultipleQuery( $sqlScript );
        
        if ( !is_array( $queries ) || empty( $queries ) )
        {
            throw new Exception( 'Invalid SQL script' );
        }
        
        if ( $this->_pdo->getAttribute(PDO::ATTR_ERRMODE) != PDO::ERRMODE_EXCEPTION )
        {
            $this->_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        
        foreach ( $queries as $query )
        {
            $this->_pdo->query( $query );
        }
    }
}
