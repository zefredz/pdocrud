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