<?php // $Id$

    // vim: expandtab sw=4 ts=4 sts=4:
    
    if ( count( get_included_files() ) == 1 )
    {
        die( 'The file ' . basename(__FILE__) . ' cannot be accessed directly, use include instead' );
    }

    /**
     * PDO Mapper
     *
     * @version     $Revision$
     * @copyright   2001-2007 Universite catholique de Louvain (UCL)
     * @author      Frederic Minne <zefredz@claroline.net>
     * @license     http://www.gnu.org/copyleft/gpl.html
     *              GNU GENERAL PUBLIC LICENSE version 2 or later
     * @package     pdocrud
     */

    /**
     * PDOMapperBuilder creates PDOMapper objects and manage a list 
     * of PDOMapper's objects
     */
    class PDOMapperBuilder
    {
        protected $database;
        protected $mapperRegistry;
        
        /**
         * Constructor
         * @param   PDO $pdo database connection
         */
        public function __construct( $pdo )
        {
            $this->database = $pdo;
            $this->mapperRegistry = array();
        }
        
        /**
         * Get the mapper object for the given class described by the given
         * schema
         * @param   string $className
         * @param   string $schema xml schema
         * @return  PDOMapper
         */
        public function getMapper( $className, $schema )
        {
            if ( ! class_exists( $className ) )
            {
                throw new Exception( $className . ' not declared' );
            }
            
            if ( ! array_key_exists( $className, $this->mapperRegistry ) )
            {
                $this->mapperRegistry[$className] = new PDOMapper(
                    $this->database, $schema );
            }
            
            return $this->mapperRegistry[$className];
        }
    }

    /**
     * PDOMapper class : map object to database using a xml schema
     *
     * Sample schema :
     *
     *  <schema>
     *  <class name"CLASSNAME" table="TABLENAME" key="TABLEKEYNAME" />
     *  <attribute  name="ATTRIBUTENAME"
     *              field="TABLE FIELDNAME"
     *              [required="(true|false)"]
     *              [default="DEFAULTVALUE"] />
     *  ...
     *  </schema>
     *
     * The mapped object must have public attributes for the attributes
     * corresponding to fields in the database
     */
    class PDOMapper
    {
        protected $schema;
        protected $db;
        
        protected $className;
        protected $tableName;
        protected $tableKey;
        
        protected $fields = array();
        protected $requiredAttributes = array();
        protected $defaultValues = array();

        /**
         * Constructor
         * @param   PDO $pdo PDO database connection
         * @param   string $schema XML schema of the object mapping
         */
        public function __construct( $pdo, $schema )
        {
            $this->schema = $schema;
            $this->db = $pdo;
            
            // use exception to report error
            if ( $this->db->getAttribute(PDO::ATTR_ERRMODE) != PDO::ERRMODE_EXCEPTION )
            {
                $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            }
            
            // load the mapping schema
            $this->loadMapping();
        }

        /**
         * Select one or more objects from the database matching the optional
         * clause. If no clause given, returns all the objects from DB
         * @param   string $clause
         * @param   array $params values to put in the clause string
         *          (see PDOStatement)
         */
        public function select( $clause = '1', $params = null )
        {
            $mapping = array();

            foreach ( $this->fields as $name => $field )
            {
                $mapping[] = $field . " AS " . $name;
            }

            $sql = "SELECT \n"
                . implode( ",\n", $mapping ) . "\n"
                . "FROM " . $this->tableName . "\n"
                . "WHERE " . $clause
                ;

            if ( ! is_array( $params ) || empty( $params ) )
            {
                $statement = $this->db->query( $sql );
            }
            else
            {
                $statement = $this->db->prepare( $sql );
                $statement->execute( $params );
            }
            
            $statement->setFetchMode(PDO::FETCH_CLASS, $this->className);

            return $statement;
        }
        
        /**
         * Update the given object in the database
         * @param   Object $obj object matching the mapping schema
         * @return  bool
         */
        public function update( $obj )
        {
            $key = $this->tableKey;
            $setFields = array();
            $params = array();
            
            if ( ! isset( $obj->$key ) )
            {
                throw new Exception('Cannot update object : missing object key !');
            }

            foreach ( $this->fields as $attr => $field )
            {
                if ( ! isset( $obj->$attr )
                    && in_array( $attr, $this->requiredAttributes ) )
                {
                    throw new Exception('Cannot update object : missing argument : ' . $attr);
                }
                
                $setFields[] = $field . ' = :' . $attr;
                $params[':'.$attr] = $obj->$attr;
            }
            
            $sql = "UPDATE " . $this->tableName . "\n"
                . "SET "
                . implode( ",\n", $setFields ) . "\n"
                . "WHERE " . $this->getField($key) . " = :".$key
                ;
                
            $statement = $this->db->prepare( $sql );
            return $statement->execute( $params );
        }
        
        /**
         * Add the given object to the database
         * @param   Object $obj object matching the mapping schema
         * @return  mixed inserted key
         *          bool false on error
         */
        public function create( $obj )
        {
            $key = $this->tableKey;
            $insertFields = array();
            $insertValues = array();
            $params = array();
            
            foreach ( $this->fields as $attr => $field )
            {
                if ( isset( $obj->$attr ) )
                {
                    $insertFields[] = $field;
                    $insertValues[] = ':' . $attr;
                    $params[':'.$attr] = $obj->$attr;
                }
                elseif ( array_key_exists( $attr, $this->defaultValues ) )
                {
                    $insertFields[] = $field;
                    $insertValues[] = ':' . $attr;
                    $params[':'.$attr] = $this->defaultValues[$attr];
                }
                elseif ( in_array( $attr, $this->requiredAttributes ) )
                {
                    throw new Exception('Cannot create object : missing argument : ' . $attr);
                }
                else
                {
                    continue;
                }
            }
            
            $sql = "INSERT INTO " . $this->tableName . "\n"
                . '(' . implode( ', ', $insertFields ) . ')' . "\n"
                . 'VALUES(' . implode( ', ', $insertValues ) . ')'
                ;
                
            $statement = $this->db->prepare( $sql );
            
            if ( $statement->execute( $params ) )
            {
                if ( ! isset( $obj->$key ) )
                {
                    $obj->$key = $this->db->lastInsertId();
                }
                
                return $obj->$key;
            }
            else
            {
                return false;
            }
        }
        
        /**
         * Delete the given object from the database
         * @param   Object $obj object matching the mapping schema
         * @return  bool
         */
        public function delete( $obj )
        {
            $key = $this->tableKey;
            
            if ( ! isset( $obj->$key ) )
            {
                throw new Exception('Cannot delete object : missing object key !');
            }
            
            $params = array(
                ':'.$key => $obj->$key
            );
            
            $sql = "DELETE FROM " . $this->tableName . "\n"
                . "WHERE " . $this->getField($key) . " = :".$key
                ;
                
            $statement = $this->db->prepare( $sql );

            return $statement->execute( $params );
        }
        
        /**
         * Get the database field name for the given attribute name
         * @param   string $attribute
         * @return  string field name
         */
        public function getField( $attribute )
        {
            if ( array_key_exists( $attribute, $this->fields ) )
            {
                return $this->fields[$attribute];
            }
            else
            {
                throw new Exception('No mapping for given attribute');
            }
        }
        
        public function getClass()
        {
            return $this->className;
        }

        public function getTable()
        {
            return $this->tableName;
        }

        public function getKey()
        {
            return $this->tableKey;
        }

        /**
         * Load the mapping schema
         */
        protected function loadMapping()
        {
            $xml = simplexml_load_string( $this->schema );

            // class element
            
            $classElem = $xml->class->attributes();
            
            $this->className = "{$classElem['name']}";
            $this->tableName = "{$classElem['table']}";
            $this->tableKey = "{$classElem['key']}";

            // attribute elements
            
            foreach ( $xml->attribute as $attribute )
            {
                $attr = $attribute->attributes();
                
                // attribute-field mapping
                
                $this->fields["{$attr['name']}"] = "{$attr['field']}";
                
                // is attribute required ?
                
                if ( isset( $attr['required'] )
                    && "{$attr['required']}" == 'true' )
                {
                    $this->requiredAttributes[] = "{$attr['name']}";
                }
                
                // attribute has a default value ?
                
                if ( isset( $attr['default'] ) )
                {
                    $this->defaultValues["{$attr['name']}"] = "{$attr['default']}";
                }
            }
        }
    }
?>