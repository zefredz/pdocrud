<?php // $Id$

    // vim: expandtab sw=4 ts=4 sts=4:
    
    if ( count( get_included_files() ) == 1 )
    {
        die( 'The file ' . basename(__FILE__) . ' cannot be accessed directly, use include instead' );
    }

    /**
     * Utility class to convert between camel case and underscore notations
     *
     * @access public
     */
    class Camelizer
    {
        /**
         * Turn object names
         * with undercores into camel case.
         *
         * @access public
         * @param string text - text in underscore notation
         * @return string - text in camel case notation
         */
        function underToCamel($text, $upperCamelCase = false)
        {
            $text = preg_replace('/_([a-z])/e', "strtoupper('\\1')", $text);
            
            $text = $upperCamelCase
                ? ucfirst($text)
                : strtolower( substr($text, 0, 1) ) . substr($text, 1)
                ;
            
            return $text;
        }
        
        /**
         * Turn object names
         * with camel case into undercores.
         *
         * @access public
         * @param string text - text in camel case notation
         * @return text - text in underscore notation
         */
        function camelToUnder($text)
        {
            $text = preg_replace('/([a-z])([A-Z])/e'
                , "'\\1' . '_' . strtolower('\\2')", $text);
                
            return strtolower($text);
        }
    }
?>