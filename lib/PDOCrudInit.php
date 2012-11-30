<?php // $Id$

// vim: expandtab sw=4 ts=4 sts=4:

/**
 * PDO Initialization script
 *
 * @version     1.0
 * @copyright   2001-2012 Universite catholique de Louvain (UCL)
 * @author      Frederic Minne <zefredz@gmail.com>
 * @license     GNU AGPL version 3 or later https://www.gnu.org/licenses/agpl-3.0.html
 * @package     pdocrud
 */

class PDOCrudInit
{
   public static function registerAll()
   {
       PDOFactory::register( 'mysql', 'PDOMySQLFactory' );
       PDOFactory::register( 'sqlite', 'PDOSqliteFactory' );
       PDOFactory::register( 'sqlite2', 'PDOSqliteFactory' );
       PDOFactory::register( 'pgsql', 'PDOPgsqlFactory' );
   }
}
