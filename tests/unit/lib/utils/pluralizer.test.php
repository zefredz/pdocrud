<?php // $Id$

    // vim: expandtab sw=4 ts=4 sts=4:
    
    require_once dirname(__FILE__) . '/../../../simpletest/autorun.php';
    require_once dirname(__FILE__) . '/../../../../lib/utils/pluralizer.lib.php';
        
    class PluralizerTestCase extends UnitTestCase
    {
        function PluralizerTestCase()
        {
            $this->UnitTestCase( 'PluralizerTestCase' );
        }
        
        function testPluralize()
        {
            $pluralizer = new Pluralizer;
            
            $testSubjects = array(
                'person'    => 'people',
                'man'       => 'men',
                'woman'     => 'women',
                'fly'       => 'flies',
                'plus'      => 'pluses',
                'bird'      => 'birds'
            );
            
            foreach ( $testSubjects as $input => $output )
            {
                $result = $pluralizer->pluralize( $input );
                
                $this->assertEqual( $output
                    , $result
                    , "failed for $input : wanted $output , got $result" );
            }
        }
        
        function testPluralizeWithExtraIrregularPlurals()
        {
            $pluralizer = new Pluralizer;

            $testSubjects = array(
                'person'    => 'people',
                'man'       => 'men',
                'woman'     => 'women',
                'mouse'     => 'mice',
                'plus'      => 'plusses'
            );
            
            $pluralizer->addIrregularPlural( 'mouse', 'mice' );
            $pluralizer->addIrregularPluralList( array( 'plus' => 'plusses' ) );

            foreach ( $testSubjects as $input => $output )
            {
                $result = $pluralizer->pluralize( $input );
                
                $this->assertEqual( $output
                    , $result
                    , "failed for $input : wanted $output , got $result" );
            }
        }
    }
?>