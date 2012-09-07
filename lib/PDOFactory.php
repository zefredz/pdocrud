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
     * PDO connection static factory
     *
     * Usage :
     *
     *  $db = PDOFactory::getConnection(
     *      'mysql://root:password@localhost/database' );
     */
    class PDOFactory
    {
        protected static $drivers = array();

        /**
         * Register a factory class for the given database type
         * @param   string $scheme database type
         * @param   string $factory factory class name for the database type
         */
        public static function register( $scheme, $factory )
        {
            self::$drivers[$scheme] = $factory;
        }

        /**
         * Get the factory class name for the given database type
         * @param   string $scheme database type
         * @return  string factory class name for the database type
         */
        protected static function getDriverClass( $scheme )
        {
            if ( array_key_exists( $scheme, self::$drivers ) )
            {
                return self::$drivers[$scheme];
            }
            else
            {
                throw new Exception('No driver registered for given dsn');
            }
        }

        /**
         * Get the PDO connection for the given DSN
         * @param   string $dsn
         * @return  PDO connection
         */
        public static function getConnection( $dsn )
        {
            $dsnArray = self::parseDsn( $dsn );

            $className = self::getDriverClass( $dsnArray['scheme'] );

            if ( ! class_exists( $className ) )
            {
                throw new Exception('No driver found for given dsn');
            }

            $db = call_user_func( array( $className, 'getConnection' ), $dsnArray );
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            return $db;
        }

        /**
         * Parse a DSN
         * @param   string $dsn
         * @return  array parsed dsn
         */
        protected static function parseDsn( $dsn )
        {
            $dsnArray = parse_url( $dsn );
            
            if ( ! $dsnArray )
            {
                throw new Exception('Could not parse DSN ' . $dsn);
            }
            
            if ( ! isset( $dsnArray['scheme'] ) )
            {
                throw new Exception('Missing driver scheme');
            }
            
            if ( ! isset( $dsnArray['host'] )
                && ! isset( $dsnArray['path'] ) )
            {
                throw new Exception('Invalid DSN : must at least define a host or a database');
            }
            
            return $dsnArray;
        }
    }

    // Drivers factory
    
    /**
     * Abstract factory to implement for each PDO driver
     */
    interface PDOAbstractFactory
    {
        /**
         * Get a PDO connection for the given array of parameters
         * @param   array $dsnArray
         * @return  PDO database connection
         */
        public static function getConnection( $dsnArray );
    }
    
    class PDOMysqlFactory implements PDOAbstractFactory
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

    // register factory
    PDOFactory::register( 'mysql', 'PDOMysqlFactory' );

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

    // register factory
    PDOFactory::register( 'sqlite', 'PDOSqliteFactory' );
    PDOFactory::register( 'sqlite2', 'PDOSqliteFactory' );

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

    // register factory
    PDOFactory::register( 'pgsql', 'PDOPgsqlFactory' );
    
