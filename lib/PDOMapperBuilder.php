<?php // $Id$

// vim: expandtab sw=4 ts=4 sts=4:

/**
 * PDOMapperBuilder
 *
 * @version     1.0
 * @copyright   2001-2012 Universite catholique de Louvain (UCL)
 * @author      Frederic Minne <zefredz@gmail.com>
 * @license     GNU LGPL version 3 or later https://www.gnu.org/copyleft/lesser.html
 * @package     pdocrud
 */

/**
 * PDOMapperBuilder creates PDOMapper objects describe by the given xml schema
 * and manage a list of PDOMapper's objects
 * 
 * Sample schema :
 *
 *  <schema>
 *  <class name"CLASSNAME" table="TABLENAME" />
 *  <attribute  name="ATTRIBUTENAME"
 *              field="TABLE FIELDNAME"
 *              [required="(true|false)"]
 *              [default="DEFAULTVALUE"] />
 *  ...
 *  <key name="ATTRIBUTENAME" />
 *  </schema>
 */
class PDOMapperBuilder
{
    protected $database;
    protected $mapperRegistry = array();
    protected $schemaRegistry = array();
    
    /**
     * Constructor
     * @param   PDO $pdo database connection
     */
    public function __construct( $pdo )
    {
        $this->database = $pdo;
    }
    
    /**
     * Register the given schema to the mapper builder
     * @param   PDOMapperSchema $schemaObj
     * @return  string class name
     */
    public function register( $schemaObj )
    {
        $className = $schemaObj->getClass();
        
        if ( ! class_exists( $className ) )
        {
            throw new Exception( $className . ' not declared' );
        }
        
        if ( ! array_key_exists( $className, $this->mapperRegistry ) )
        {
            $this->mapperRegistry[$className] = new PDOMapper(
                $this->database, $schemaObj,  $this, new PDOMapperClauseParser( $schemaObj ) );
        }
        
        if ( ! array_key_exists( $className, $this->schemaRegistry ) )
        {
            $this->schemaRegistry[$className] = $schemaObj;
        }
        
        return $className;
    }
    
    public function unregister( $className )
    {
        if ( ! array_key_exists( $className, $this->mapperRegistry ) )
        {
            throw new Exception('No mapper found for class ' . $className);
        }
        
        unset( $this->mapperRegistry[$className] );
        
        if ( ! array_key_exists( $className, $this->schemaRegistry ) )
        {
            throw new Exception('No schema found for class ' . $className);
        }
        
        unset( $this->schemaRegistry[$className] );
    }
    
    /**
     * Get the mapper object for the given class described by a registered
     * schema
     * @param   string $className name of the class to map
     * @return  PDOMapper
     */
    public function getMapper( $className )
    {        
        if ( ! array_key_exists( $className, $this->mapperRegistry ) )
        {
            throw new Exception('No mapper found for class ' . $className);
        }
        
        if ( ! class_exists( $className ) )
        {
            throw new Exception( $className . ' not declared' );
        }
        
        return $this->mapperRegistry[$className];
    }
    
    /**
     * Get the schema corresponding to the given class
     * @param   string $className
     * @return  PDOMapperSchema
     */
    public function getSchema( $className )
    {
        if ( ! array_key_exists( $className, $this->schemaRegistry ) )
        {
            throw new Exception('No schema found for class ' . $className);
        }
        
        return $this->schemaRegistry[$className];
    }
}
