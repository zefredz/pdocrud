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
 * Factory to implement for Postrgesql driver
 */

class PDOPgsqlFactory implements PDOAbstractFactory
{
    public static function getConnection( $dsnArray )
    {
        $db = new PDO( self::getPdoDsn($dsnArray) );

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

            if ( isset( $dsnArray['user'] ) )
            {
                $dsn[] = 'user=' . $dsnArray['user'];
            }

            if ( isset( $dsnArray['pass'] ) )
            {
                $dsn[] = 'password=' . $dsnArray['pass'];
            }

            return implode( ' ', $dsn );
        }
    }
}