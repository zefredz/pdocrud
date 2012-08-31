<?php // $Id$

    // vim: expandtab sw=4 ts=4 sts=4:
    
    require_once dirname(__FILE__) . '/../../simpletest/autorun.php';
    require_once dirname(__FILE__) . '/../../../lib/pdomapper.lib.php';
    require_once dirname(__FILE__) . '/../../../lib/pdomapperbuilder.lib.php';
    require_once dirname(__FILE__) . '/../../../lib/pdomapperschema.lib.php';
    
    class PDOMapperSchemaTestCase extends UnitTestCase
    {
        function PDOMapperSchemaTestCase()
        {
            $this->UnitTestCase( 'PDOMapperSchemaTestCase' );
        }
    }
    
    class PDOMapperBuilderTestCase extends UnitTestCase
    {
        function PDOMapperBuilderTestCase()
        {
            $this->UnitTestCase( 'PDOMapperBuilderTestCase' );
        }
    }
        
    class PDOMapperTestCase extends UnitTestCase
    {
        function PDOMapperTestCase()
        {
            $this->UnitTestCase( 'PDOMapperTestCase' );
        }
    }
?>