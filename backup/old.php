<?xml version="1.0" encoding="iso-8859-1"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>PDO-based ORM test</title>
</head>
<body>
<?php // $Id$

    // vim: expandtab sw=4 ts=4 sts=4:

    /**
     * PDO Mapper and Factory test script
     *
     * @version     $Revision$
     * @copyright   2001-2007 Universite catholique de Louvain (UCL)
     * @author      Frederic Minne <zefredz@claroline.net>
     * @license     http://www.gnu.org/copyleft/gpl.html
     *              GNU GENERAL PUBLIC LICENSE version 2.0
     * @package     pdocrud
     */
    
    require_once dirname(__FILE__) . '/../lib/pdofactory.lib.php';
    require_once dirname(__FILE__) . '/../lib/pdomapper.lib.php';
    
    require_once dirname(__FILE__) . '/estrict.inc.php';
    
    enable_eStrictLog();
    
    // declare class for the crud object
    class Comment
    {
        public $id;
        // public $codeId;
        public $author;
        public $email;
        public $title;
        public $postedTime;
        public $content;

        public function dump()
        {
            var_dump( $this );
        }
    }
    
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
    
    date_default_timezone_set("Europe/Brussels");
    $date = date( "Y-m-d H:i:s" );
    
    echo "<p>{$date}</p>";
    
    $schema = <<<__SCHEMA__
<schema>
<class name="Comment" table="comment" />
<attribute name="id" field="comment_id" />
<!-- attribute name="codeId" field="comment_code_id" required="true" / -->
<attribute name="author" field="comment_author" required="true" />
<attribute name="email" field="comment_email" />
<attribute name="title" field="comment_title" required="true" />
<attribute name="postedTime" field="comment_time" default="{$date}" />
<attribute name="content" field="comment_content" />
<hasone name="author" class="User" rel="Comment.author:User.uid" />
<key name="id" />
</schema>
__SCHEMA__;

    $uschema = <<<__SCHEMA__
<schema>
<class name="User" table="users" />
<attribute name="id" field="user_id" />
<attribute name="uid" field="user_uid" required="true" />
<attribute name="password" field="user_password" required="true" />
<attribute name="firstName" field="user_firstname" required="true" />
<attribute name="lastName" field="user_lastname" required="true" />
<attribute name="email" field="user_email" />
<attribute name="registration" field="user_registration" />
<hasmany name="comments" class="Comment" rel="User.uid:Comment.author" />
<key name="id" />
</schema>
__SCHEMA__;
    
    // define mapping schema

    try
    {
        $dsn = 'mysql://root@localhost/pastecode';

        $mapperBuilder = new PDOMapperBuilder(
            PDOFactory::getConnection( $dsn ) );
                
        $mapperBuilder->register( PDOMapperSchema::fromString($schema) );
        $mapperBuilder->register( PDOMapperSchema::fromString($uschema) );
        
        $commentMapper = $mapperBuilder->getMapper( 'Comment' );
        
        var_dump( $commentMapper->getSchema() );
            
        echo ">>>>>> Select all entries\n";
            
        $statement = $commentMapper->select();

        foreach ( $statement as $obj )
        {
            $obj->dump();
        }
        
        echo ">>>>>> Select one entry\n";
        
        $statement = $commentMapper->select(
            $commentMapper->getSchema()->getField( 'id' ) . ' = :id',
            array( ':id' => '2' ) );
        
        foreach ( $statement as $obj )
        {
            $obj->dump();
        }
        
        echo ">>>>>> Select one entry using selectOne\n";
        
        $obj = $commentMapper->selectOne(
            $commentMapper->getSchema()->getField( 'id' ) . ' = :id',
            array( ':id' => '2' ) );
            
        $obj->dump();
        
        $commentMapper->hasOne( $obj, 'author' );
        
        $userMapper = $mapperBuilder->getMapper( 'User' );
        $usr = new User;
        $usr->uid = 'ZeFredz';
        $userMapper->hasMany( $usr, 'comments' );
        
        echo ">>>>>> Select all entries using selectAll\n";
        
        $objList = $commentMapper->selectAll();
        
        foreach ( $objList as $obj )
        {
            $obj->dump();
        }
         
//        try
//        {
//            echo ">>>>>> Cannot pass an invalid class\n";
//            $a = new StdClass();
//            $commentMapper->update( $a );
//        }
//        catch ( Exception $e )
//        {
//            echo '<pre>' . $e . '</pre>' . "\n";
//        }
//        
//        echo ">>>>>> Select one entry with SQL injection\n";
//
//        $statement = $commentMapper->select(
//            $commentMapper->getField( 'id' ) . ' = :id',
//            array( ':id' => '2 OR 1' ) );
//
//        foreach ( $statement as $obj )
//        {
//            $obj->dump();
//        }
//
//
//        echo ">>>>>> Modify an entry\n";
//        $obj->email = 'zefredz@frimouvy.org';
//        $commentMapper->update( $obj );
//
//        try
//        {
//            unset( $obj->codeId );
//            
//            // will generate an exception since codeId is required !
//            $commentMapper->update( $obj );
//        }
//        catch ( Exception $e )
//        {
//            echo '<pre>' . $e . '</pre>' . "\n";
//        }
//        
//        echo ">>>>>> Create an entry\n";
//        $cmt = new Comment;
//        $cmt->title = 'Titre';
//        $cmt->content = 'contenu';
//        $cmt->author = 'Moi';
//
//        try
//        {
//            // will generate an exception since codeId is missing !
//            $id = $commentMapper->create( $cmt );
//        }
//        catch ( Exception $e )
//        {
//            echo $e . "\n";
//        }
//        
//        $cmt = new Comment;
//        $cmt->codeId = 0;
//        $cmt->title = 'Titre';
//        $cmt->content = 'contenu';
//        $cmt->author = 'Moi';
//        
//        // will generate an exception since codeId is missing !
//        $id = $commentMapper->create( $cmt );
//
//        $statement = $commentMapper->select(
//            $commentMapper->getField( 'id' ) . ' = :id',
//            array( ':id' => $id ) );
//
//        foreach ( $statement as $obj )
//        {
//            $obj->dump();
//        }
//
//
//        echo ">>>>>> Delete an entry\n";
//        $commentMapper->delete( $obj );
    }
    catch( Exception $e )
    {
        die($e);
    }
?>
</body>
</html>