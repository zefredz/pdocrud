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

class Comment
{
    public $id;
    public $post;
    public $author;
    public $title;
    public $postedTime;
    public $content;

    public function dump()
    {
        var_dump( $this );
    }
}
