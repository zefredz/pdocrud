<?php // $Id$

// vim: expandtab sw=4 ts=4 sts=4:

/**
 * Description
 *
 * @version     1.9 $Revision$
 * @copyright   2001-2012 Universite catholique de Louvain (UCL)
 * @author      Claroline Team <info@claroline.net>
 * @author      Frederic Minne <zefredz@claroline.net>
 * @license     GNU AGPL version 3 or later https://www.gnu.org/licenses/agpl-3.0.html
 * @package     PACKAGE_NAME
 */

class User
{
    public $id;
    public $uid;
    public $password;
    public $firstName;
    public $lastName;
    public $email;
    public $registration;

    public function dump()
    {
        var_dump( $this );
    }
}
