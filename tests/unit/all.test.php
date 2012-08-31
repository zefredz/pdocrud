<?php // $Id$

    // vim: expandtab sw=4 ts=4 sts=4:
    
    /**
     * Run all tests in the current directory
     *
     * @version     1.9 $Revision$
     * @copyright   2001-2007 Universite catholique de Louvain (UCL)
     * @author      Claroline Team <info@claroline.net>
     * @author      Frederic Minne <zefredz@claroline.net>
     * @license     http://www.gnu.org/copyleft/gpl.html
     *              GNU GENERAL PUBLIC LICENSE version 2.0
     * @package     tests
     */

    require_once dirname(__FILE__) . '/../simpletest/autorun.php';
    
    class AllTests extends TestSuite
    {
        function AllTests()
        {
            parent::TestSuite();
            
            $files = new RecursiveIteratorIterator( 
                new RecursiveDirectoryIterator( dirname(__FILE__) ) );
            
            foreach ( $files as $file )
            {
                if ( $file->isFile() 
                    && substr( $file->getFileName(), -9 ) == '.test.php' 
                    && $file->getFileName() != basename(__FILE__) )
                {
                    $this->addTestFile( $file->getRealPath() );
                }
            }
        }
    }
?>