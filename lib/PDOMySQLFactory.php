<?php // $Id$

// vim: expandtab sw=4 ts=4 sts=4:

/**
 * PDO Factory
 *
 * @version     1.0
 * @copyright   2001-2012 Universite catholique de Louvain (UCL)
 * @author      Frederic Minne <zefredz@gmail.com>
 * @license     https://www.gnu.org/licenses/lgpl.html
 *              GNU LESSER GENERAL PUBLIC LICENSE version 3 or later
 * @package     pdocrud
 */

 /**
 * Factory to implement for MySQL driver
 */
class PDOMySQLFactory implements PDOAbstractFactory
{
    public static function getConnection( $dsnArray )
    {
        $user = isset( $dsnArray['user'] )
            ? $dsnArray['user']
            : ''
            ;

        $pass = isset( $dsnArray['pass'] )
            ? $dsnArray['pass']
            : ''
            ;

        $db = new PDO( self::getPdoDsn($dsnArray), $user, $pass );

        return $db;
    }

    protected static function getPdoDsn( $dsnArray )
    {
        $dsn = array();

        if ( !isset($dsnArray['scheme'])
            || !isset($dsnArray['host']) )
        {
            throw new Exception('Missing parameters in dsn');
        }
        else
        {
            $dsn[] = $dsnArray['scheme'] . ':host=' . $dsnArray['host'];

            if ( isset( $dsnArray['port'] ) )
            {
                $dsn[] = 'port=' . $dsnArray['port'];
            }

            if ( isset( $dsnArray['path'] ) )
            {
                $dsn[] = 'dbname=' . substr( $dsnArray['path'], 1 );
            }

            return implode( ';', $dsn );
        }
    }
}