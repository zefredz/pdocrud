<?php // $Id$

// vim: expandtab sw=4 ts=4 sts=4:

/**
 * PDO Factory
 *
 * @version     1.0
 * @copyright   2001-2012 Universite catholique de Louvain (UCL)
 * @author      Frederic Minne <zefredz@gmail.com>
 * @license     GNU AGPL version 3 or later https://www.gnu.org/licenses/agpl-3.0.html
 * @package     pdocrud
 */

/**
 * Factory to implement for Sqlite driver
 */
class PDOSqliteFactory implements PDOAbstractFactory
{
    public static function getConnection( $dsnArray )
    {
        $db = new PDO( self::getPdoDsn($dsnArray) );

        return $db;
    }

    protected static function getPdoDsn( $dsnArray )
    {
        $dsn = array();

        if ( !isset($dsnArray['scheme']) )
        {
            throw new Exception('Missing parameters in dsn');
        }
        else
        {
            $dsn[] = $dsnArray['scheme'] . ':';

            if ( isset( $dsnArray['path'] ) )
            {
                $dsn[] = 'dbname=' . $dsnArray['path'];
            }
            else
            {
                throw new Exception('Missing sqlite database path');
            }

            return implode( '', $dsn );
        }
    }
}