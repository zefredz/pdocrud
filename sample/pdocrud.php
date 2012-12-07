<!DOCTYPE html>
<html>
<head>
<title>PDO-based ORM test</title>
</head>
<body>
<?php // $Id$

    // vim: expandtab sw=4 ts=4 sts=4:
    
    /**
     * Description
     *
     * @version     1.9 $Revision$
     * @copyright   2001-2012 Universite catholique de Louvain (UCL)
     * @author      Frederic Minne <zefredz@claroline.net>
     * @license     GNU LGPL version 3 or later https://www.gnu.org/copyleft/lesser.html
     * @package     pdocrud
     */

    require_once dirname(__FILE__) . '/../lib/PDOCrudLoader.php';
    
    $loader = new PDOCrudLoader;
    $loader->register();
    
    require_once dirname(__FILE__) . '/estrict.inc.php';
    enable_eStrictLog();
    
    PDOFactory::register( 'mysql', 'PDOMySQLFactory' );
    
    define ( 'SCHEMA_PATH', dirname(__FILE__).'/blog/schema' );
    
    try
    {
        echo "<h1>1. Script initialization</h1>\n";
        
        echo "<h2>1.1 initializing PDO connection</h2>\n";        
        // initialize PDO connection
        $dsn = 'mysql://claroline:claroline@localhost/pdocrud_test';
        $pdoconn = PDOFactory::getConnection( $dsn );
        echo "done\n";
        
        echo "<h2>1.2 initializing the database</h2>\n";
        // initialize the test database
        $script = new PDOSQLScript( $pdoconn );
        $script->execute( file_get_contents( dirname(__FILE__) . '/blog/sql/User.sql' ) );
        $script->execute( file_get_contents( dirname(__FILE__) . '/blog/sql/Post.sql' ) );
        $script->execute( file_get_contents( dirname(__FILE__) . '/blog/sql/Comment.sql' ) );
        $script->execute( file_get_contents( dirname(__FILE__) . '/blog/sql/Tag.sql' ) );
        $script->execute( file_get_contents( dirname(__FILE__) . '/blog/sql/Post_Tag.sql' ) );
        echo "done";
        
        // PDO CRUD
        
        echo "<h2>1.3 initializing the classes</h2>\n";
        require_once dirname(__FILE__) . '/blog/classes/User.php';
        require_once dirname(__FILE__) . '/blog/classes/Comment.php';
        require_once dirname(__FILE__) . '/blog/classes/Post.php';
        require_once dirname(__FILE__) . '/blog/classes/Tag.php';
        echo "done\n";
        
        echo "<h2>1.4 initializing PDO mappers</h2>\n";
        $mapperBuilder = new PDOMapperBuilder( $pdoconn );
        
        echo "<h3>1.4.1 loadings schemas</h3>\n";
        // load the schema
        $mapperBuilder->register( PDOMapperSchema::fromFile(SCHEMA_PATH.'/User.xml'));
        $mapperBuilder->register( PDOMapperSchema::fromFile(SCHEMA_PATH.'/Post.xml'));
        $mapperBuilder->register( PDOMapperSchema::fromFile(SCHEMA_PATH.'/Comment.xml'));
        $mapperBuilder->register( PDOMapperSchema::fromFile(SCHEMA_PATH.'/Tag.xml'));
        echo "done\n";
        
        echo "<h3>1.4.2 getting mappers</h3>\n";
        $userMapper = $mapperBuilder->getMapper( 'User' );
        $postMapper = $mapperBuilder->getMapper( 'Post' );
        $commentMapper = $mapperBuilder->getMapper( 'Comment' );
        $tagMapper = $mapperBuilder->getMapper( 'Tag' );
        echo "done\n";
        
        echo "<h1>2. testing PDO CRUD</h1>\n";
        
        echo "<h2>2.1 loading one comment from user with id = 4</h2>\n";
        // get one comment from a user
        $comment = $commentMapper->selectOne(
            $commentMapper->getSchema()->getField( 'author' ) . ' = :id',
            array( ':id' => '4' ));
            
        if ( $comment )
        {
            $comment->dump();
        }
        else
        {
            echo "failure<br />\n";
        }
        
        echo "done\n";
        
         echo "<h2>2.1bis loading one comment from user with id = 4, using clause parser</h2>\n";
        // get one comment from a user
        $comment = $commentMapper->selectOne(
            '%author% = :id',
            array( ':id' => '4' ));
            
        if ( $comment )
        {
            $comment->dump();
        }
        else
        {
            echo "failure<br />\n";
        }
        
        echo "done\n";
        
        echo "<h2>2.2 loading user with id = 4</h2>\n";
        
        $user4 = $commentMapper->hasOne( $comment, 'author' );
        
        if ( $user4 )
        {
            $user4->dump();
        }
        else
        {
            echo "failure<br />\n";
        }
         
        echo "done\n"; 
        
        echo "<h2>2.3 loading all users</h2>\n";
        
        $users = $userMapper->selectAll();
        
        foreach ( $users as $user )
        {
            $user->dump();
        }
        echo "done\n";
        
        echo "<h2>2.4 loading all posts by user with id = 4</h2>\n";
            
        $posts = $userMapper->hasMany( $user4, 'posts' );
        
        foreach ( $posts as $obj )
        {
            $obj->dump();
        }
        
        echo "done\n";  
        
        echo "<h2>2.5 change uid of user with id = 4</h2>\n";
        
        $user4->uid = 'Zelda';
        $userMapper->update( $user4 );
        
        $user4b = $userMapper->selectOne(
            $userMapper->getSchema()->getField( 'uid' ) . ' = :uid',
            array( ':uid' => 'Zelda' ) );
            
        if ( $user4b && ( $user4b->id == $user4->id ) )
        {
            echo "success<br />\n";
        }
        else
        {
            echo "failure<br />\n";
        }
        
        echo "done\n";
        
        echo "<h2>2.6 create new user (6)</h2>\n";
        
        date_default_timezone_set("Europe/Brussels");
        $now = date( "Y-m-d H:i:s" );
        
        $user6 = new User;
        $user6->uid = 'mithrandir';
        $user6->password = 'L0rien';
        $user6->firstName = 'Gandalf';
        $user6->lastName = 'Le Gris';
        $user6->email = 'gandalf@root.org';
        $user6->registration = $now;
        
        $user6 = $userMapper->create( $user6 );
        
        $user6b = $userMapper->selectOne(
            $userMapper->getSchema()->getField( 'id' ) . ' = :id',
            array( ':id' => $user6->id ) );
            
        if ( $user6b )
        {
            $user6b->dump();
        }
        else
        {
            echo "failure<br />\n";
        }
        
        echo "done\n";
        
        echo "<h2>2.7 delete user 6</h2>\n";
        
        $userMapper->delete( $user6 );
        
        $user6b = $userMapper->selectOne(
            $userMapper->getSchema()->getField( 'id' ) . ' = :id',
            array( ':id' => $user6->id ) );
            
        if ( $user6b )
        {
            echo "failure<br />\n";
        }
        else
        {
            echo "success<br />\n";
        }
        
        echo "done\n";
        
        echo "<h2>2.8 delete user with on delete</h2>\n";
        
        $user4 = $userMapper->selectOne(
            $userMapper->getSchema()->getField( 'id' ) . ' = :id',
            array( ':id' => 4 ) );
            
        if ( $user4 )
        {
            $posts = $userMapper->hasMany( $user4, 'posts' );
            $before = count ( $posts );
            echo "Posts before : {$before}<br />\n";
            
            $cmts = $userMapper->hasMany( $user4, 'comments' );
            $before = count ( $cmts );
            echo "Comments before : {$before}<br />\n";
            
            echo "Delete user 4<br />\n";
            $userMapper->delete( $user4 );
            
            $posts = $userMapper->hasMany( $user4, 'posts' );
            $after = count ( $posts );
            echo "Posts after : {$after}<br />\n";
            
            if ( $after > 0 )
            {
                echo "failure<br />\n";
            }
            
            $cmts = $userMapper->hasMany( $user4, 'comments' );
            $after = count ( $cmts );
            echo "Comments before : {$after}<br />\n";
            
            if ( $after > 0 )
            {
                echo "failure<br />\n";
            }
            
            echo "success<br />\n";
        }
        else
        {
            echo "failure<br />\n";
        }
        
        echo "done\n";      
    }
    catch ( Exception $e )
    {
        echo "<h1>failure : EXCEPTION OCCURS ! </h1>\n";
        echo '<pre>'.$e.'</pre>';
    }
?>
</body>
</html>
