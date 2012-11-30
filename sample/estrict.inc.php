<?php // $Id$

// vim: expandtab sw=4 ts=4 sts=4:

/**
 * Description
 *
 * @version     1.9 $Revision$
 * @copyright   2001-2012 Universite catholique de Louvain (UCL)
 * @author      Claroline Team <info@claroline.net>
 * @author      Frederic Minne <zefredz@claroline.net>
 * @license     GNU LGPL version 3 or later https://www.gnu.org/copyleft/lesser.html
 * @package     PACKAGE_NAME
 */


if ( version_compare(phpversion(), '5.0') >= 0 )
{
    function eStrictHandler( $number, $string, $file, $line, $context )
    {
        $error = "------------------------------------\n";
        $error .= "Date:   [".date('c')."]\n";
        $error .= "Number: [$number]\n";
        $error .= "String: [$string]\n";
        $error .= "File:   [$file]\n";
        $error .= "Line:   [$line]\n";
        // $error .= "Context:\n" . print_r($context, TRUE) . "\n\n";

        error_log($error, 3, dirname(__FILE__) . '/e_strict_error.log');
    }
    
    function enable_eStrictLog()
    {
        set_error_handler( 'eStrictHandler', E_STRICT );
    }   
}
else
{
    function enable_eStrictLog()
    {
        // do nothing
    }
}
