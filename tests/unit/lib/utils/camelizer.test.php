<?php // $Id$

    // vim: expandtab sw=4 ts=4 sts=4:
    
    require_once dirname(__FILE__) . '/../../../simpletest/autorun.php';
    require_once dirname(__FILE__) . '/../../../../lib/utils/camelizer.lib.php';
        
    class CamelizerTestCase extends UnitTestCase
    {
        function CamelizerTestCase()
        {
            $this->UnitTestCase( 'CamelizerTestCase' );
        }
        
        function testUnderToCamel()
        {
            $testSubjects = array (
                "one_word_and_another" => "oneWordAndAnother",
                "one_word_andAnother" => "oneWordAndAnother",
                "One_word_and_another" => "oneWordAndAnother"
            );
            
            foreach ( $testSubjects as $input => $output )
            {
                $result = Camelizer::underToCamel( $input );
                
                $this->assertEqual( $output
                    , $result
                    , "failed for $input : wanted $output , got $result" );
            }
            
            $input = "one_word_and_another";
            $output = "OneWordAndAnother";
            
            $result = Camelizer::underToCamel( $input, true );
            
            $this->assertEqual( $output
                , $result
                , "failed for $input : wanted $output , got $result" );
        }
        
        function testCamelToUnder()
        {
            $testSubjects = array (
                "oneWordAndAnother",
                "oneWordAndAnother"
            );
            
            $output = "one_word_and_another";
            
            foreach ( $testSubjects as $input )
            {
                $this->assertEqual( $output
                    , Camelizer::camelToUnder( $input )
                        , "failed for $input" );
            }
        }
    }
?>
