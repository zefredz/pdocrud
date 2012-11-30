<?php  // vim: expandtab sw=4 ts=4 sts=4:

/**
 * Class loader
 *
 * @version     2.2
 * @copyright   2001-2012 Universite catholique de Louvain (UCL)
 * @author      Frederic Minne <frederic.minne@uclouvain.be>
 * @license     GNU AGPL version 3 or later https://www.gnu.org/licenses/agpl-3.0.html
 */

class PDOCrudLoader
{
    public function load( $className )
    {
        if ( strpos( $className, '\\' ) )
        {
            $path = __DIR__ . '/' . str_replace( '\\', '/', $className ) . '.php';
        }
        else
        {
            $path = __DIR__ . '/' . str_replace( '_', '/', $className ) . '.php';
        }
        
        if ( file_exists($path))
        {
            require $path;
        }
    }
    
    /**
    * Register the main class loader
    */
    public function register()
    {
        spl_autoload_register( array( $this, 'load' ) );
    }
}
