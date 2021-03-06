#  PDO-based Object Relational Mapper

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU Lesser General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.

**IDEAS :**
- add triggers on relations for the other member of the relation (default: keep) :
    - ondelete="(keep|delete)" done
    - onupdate="(keep|update)"
- use camelizer and pluralizer classes to compute missing table or field names
- DatabaseToSchema class to convert an existing database table to a PDOMapperSchema
  
DRAFT - WORKING COPY !!!!

  
This document describes a simple ORM implementation based on PHP Data Object.

  
    @version     2012-08-31
    @copyright   2001-2012 Universite catholique de Louvain (UCL)
    @author      Frederic Minne <zefredz@claroline.net>
    @license     http://creativecommons.org/licenses/by-nc-sa/2.0/be/ CreativeCommons Attribution-Noncommercial-Share Alike 2.0

##  0. Motivation and introduction

###  Make the code more simple

Retreiving data from a database always uses similar SQL queries or PHP code.
Abstraction layers are good the provide helpers to easily get data from the
database but they do not reduce the amount of code needed for the queries
themselves.

  
Most of this code could be generated automaticaly and let the developpers
focus on more important issues such as security, application architecture...

###  Make the code more secure

Automatic SQL queries generation could lead to more security since the
verification and filtering of the data passed to and retreived from the
database can be included in the automatic generation process (PDO is really
great at this).

  
Another advantage is that the SQL code is contained into one single class. So
bugs and security flaws are easier to find and correct.

###  A simple ORM framework for PHP5

The ORM architecture described here is aimed to provide the following features : 

  1. a lightweight easy to use and understand object-oriented ORM architecture 
  2. based on PDO, simplexml and other PHP 5 powerfull features 
  3. CRUD (Create Read Update Delete) objects based on PHP classes with no need to implement the SQL queries 
  4. basic relations : 
    1. hasone : object has another object mapped from the db mapped to one of his attribute 
    2. hasmany : object has many objects mapped from the db mapped to one of his attribute 

###  Acronyms and conventions

**PDO**: PHP Data Object
**ORM**: Object-Relationnal Mapper
**DSN**: Data Source Name
**CRUD**: Create Read Update Delete
  
code source are in courier new

###  Warning

**At this time the basic operations select(One/All), create, update, delete, hasOne and hasMany are already working. Planned feature : hasAndBelongsToOne/Many based on a n:m relation table.**
  
Note that the database and the tables used by the PDO-based ORM must exist in
the DBMS.

##  1. Classes and API

###  1.1. Utility classes : PDOFactory and PDOSQLScript

The **PDOFactory** class is not directly related with the PDO-based ORM. It is
used to have the same DSN syntax for all the PDO drivers.

  
    $dsn = 'mysql://user:password@host/database';

    $dsn = 'sqlite:/path/to/database';

    $dsn = 'sqlite::memory:';

    $pdo = PDOFactory::getConnection( $dsn );

      
The PDOFactory is based uses driver-specific factories implementing a
PDOAbstractFactory interface :

      
    /**
    * Abstract factory to implement for each PDO driver
    */
    interface PDOAbstractFactory

    {

        /**  
         * Get a PDO connection for the given array of parameters  
         * @param   array $dsnArray  
         * @return  PDO database connection  
         */  
        public static function getConnection( $dsnArray );  
    }

  
At this time the PDOFactory provides drivers for mysql (tested), sqlite (not
tested) and pgsql (not tested).

  
PDOFactory methods summary :


  * _static_ **register**( $scheme, $factory ), register a factory for a given driver (scheme) 
  * _static_ **getConnection**( $dsn ), get a connection for the given DSN

  
The **PDOSQLScript** uses a PDO connection to a database to execute an SQL
script :

  
    $sqlScript = <<<__SQL__

    CREATE TABLE `comment` (

      `comment_id` int(11) NOT NULL auto_increment,
      `comment_code_id` int(11) NOT NULL default '0',
      `comment_author` varchar(255) NOT NULL default '',
      `comment_email` varchar(255) default NULL,
      `comment_title` varchar(255) NOT NULL default '',
      `comment_time` datetime default '0000-00-00 00:00:00',
      `comment_content` text NOT NULL,
      PRIMARY KEY  (`comment_id`)
    );

    __SQL__;

      
    $pdoScript = new PDOScript( $pdo );

    $pdoScript->execute( $sqlScript );

###  1.2. Mappable objects and PDOMapperSchema

A mappable object is an object with public attributes corresponding to fields
in a database table with no constructor or a constructor with no parameters :

  
    class Comment
    {
        public $id;  
        public $codeId;  
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
  
The mapping information is given by a XML schema. The elements of the schema
are :

  
**attribute**: denotes a required xml attribute or element
_attribute_ : denotes an optional xml attribute or element

  

  * one **class** element with 

    * **name**: class name (denoted "_current class_" in the following)

    * **table**: database table name

  * one or more **attribute** element with 

    * **name**: name of the attribute of the object of the current class to map 
    * **field**: name of the corresponding field in the database table 
    * _required_: this field is required and then could not be empty in the object 
    * _default_: default value for the object attribute if missing 

  * one **key** element : 

    * **name** : name of the attribute used as key (must be declared in an attribute element)

  * zero or more _hasone/hasmany_ elements with 

    * **name**: name of the attribute to map to the hasone relation (must be declared in an attribute element) 
    * **class**: name of the class to map (denoted "_hasone-related class_" in the following)

    * _rel_: description of the relation mapping in one of the following format : 

      * Class1.attr1:Class2.attr2 : use attribute attr1 from the current class and attribute attr2 from the hasone relatedclass

      * Class1.attr or Class1.attr: : use attribute attr from class one and declared key for the hasone-related class 
      * :Class2.attr : use the key of the current class and the attr attribute of the hasone-related class 
      * empty or missing : use the declared key for both the current class and the hasone-related class 

    * _ondelete_: trigger if the current object is deleted : value keep (default) or delete

  
Sample schema for the Comment class :

  
    $commentXMLSChema = <<<__SCHEMA__

    <schema>

        <class name="Comment" table="comment" />  
        <attribute name="id" field="comment_id" />  
        <attribute name="codeId" field="comment_code_id" required="true" />  
        <attribute name="author" field="comment_author" required="true" />  
        <attribute name="email" field="comment_email" />  
        <attribute name="title" field="comment_title" required="true" />  
        <attribute name="postedTime" field="comment_time" default="{$date}" />  
        <attribute name="content" field="comment_content" />  
        <hasone name="author" class="User" rel="Comment.id:User.id" />  
        <key name="id" />  
    </schema>

    __SCHEMA__;

  
The XML schema is parsed and represented by an object of the class
PDOMapperSchema :

  
    $commentSchemaObj = new PDOMapperSchema( $commentXMLSchema );

    $commentSchemaObj = PDOMapperSchema::fromString( $commentXMLSchema );

  
Alternatively, you can use a file :

  
    $commentSchemaObj = PDOMapperSchema::fromFile( 'path/to/comment.xml' );

  
The **PDOMapperSchema** class provides methods to get informations about the
schema :

  

  * **getClass**():string, return the name of the class of the mapped object
  * **getTable**():string, return the name of the table for the mapped object
  * **getKey**():string, return the name of the table key for the mapped object
  * **getField**( $attribute ):string, return the name of the field mapped by the given attribute

  
Some informative methods used in PDOMapper internals are also provided as
public methods :

  

  * **getFieldList**():array, return the list of all fields
  * **getAttributeList**():array, return the list of all attributes
  * **isRequired**( $attribute ):boolean, check if the attribute is required or not
  * **hasDefaultValue**( $attribute ):boolean, check if the given attribute has a default value
  * **getDefaultValue**( $attribute ):string, return the default value af the given attribute
  * **hasOne**( $attribute ):boolean, check if the attribute is part of a has one relation
  * **getHasOneRelation**( $attribute ):boolean, get the has one relation for the given attribute
  * **countHasManyRelations**():int, count the has one relations
  * **getHasManyRelationList**():array, get the has one relations
  * **hasMany**( $attribute ):boolean, check if the attribute is part of a has many relation
  * **getHasManyRelation**( $attribute ):boolean, get the has many relation for the given attribute
  * **countHasManyRelation**():int, count the has many relations
  * **getHasManyRelationList**():array, get the has many relations

###  1.3. PDOMapper

The **PDOMapper** is the main class of the PDO-ORM and implements the CRUD
methods for the mapped objects represented by **PDOMapperSchema**:

  
    $commentSchemaObj = new PDOMapperSchema( $commentXMLSchema );

    $commentMapper = new PDOMapper( $commentSchemaObj );

  
The **PDOMapper** declares the following CRUD methods :

  

  * **select**( $clause = '1', $params = null ):PDOStatement, select one or more objects from the database matching the optional clause and the given prepared query parameters (see PHP manual entry for PDOStatement for more information). If no clause given, returns all the objects from DB
  * **update**( $obj ):object, update the given object in the database
  * **create**( $obj ):object, create the given object in the database
  * **delete**( $obj ):boolean, delete the given object from the database
  * **selectOne**( $clause = "1", $params = null ):object or false, select one object of the current schema matching the given clause
  * **selectAll**( $clause = "1", $params = null ):array of objects, select all the objects of the current schema matching the given clause
  * **hasOne**( $obj, $name ):object or false, return the object of the has one relation
  * **hasMany**( $obj, $name ): array of object, returns the objects of the has many relation
  * **deleteWhere**( $clause = '1', $params = null ):boolean, delete objects giving a clause (warning : deleteWhere do not use triggers !)
  * **deleteAll**():boolean, delete all objects (warning : deleteAll do not use triggers !)
  * **deleteHasOne**( $obj, $name ):boolean, delete an object related to the current object through a has one relation
  * **deleteHasMany**( $obj, $name ):boolean, delete an object related to the current object through a has many relation
  * **getSchema**():PDOMapperSchema, return the current schema

###  1.4. PDOMapperBuilder

The **PDOMapperBuilder** is the registry and factory for the PDOMapper objects
and made instanciation of mapped objects easier :

  
First you have to instanciate the builder with the given PDO connection :

  
    $dsn = 'mysql://root@localhost/pastecode';

    $mapperBuilder = new PDOMapperBuilder( PDOFactory::getConnection( $dsn ) );

  
Then you have to register the PDOMapperSchema for the mapped objects :

                
$mapperBuilder->register( new PDOMapperSchema( $commentXMLSchema ) );

  
and you can get the object mapper for a registered schema by using the
getMapper method :

        
    $commentMapper = $mapperBuilder->getMapper( 'Comment' );

  
You can get the retated schema through the getSchema method :

  
    $commentMapperSchema = $mapperBuilder->getSchema( 'Comment' );

##  2. Using the framework

Here is some usage sample for the framework. In this sample, we will consider
a simple blog system with users (represented by a User class) and posts
(represented by the Post class). A user can write many posts and a post
belongs to one user.

###  2.1. Simple usage

Let's define the user class, database table and XML schema for the user :

  
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

  
    CREATE TABLE `users` (

      `user_id` int(11) NOT NULL auto_increment,  
      `user_uid` varchar(255) NOT NULL default '',  
      `user_password` varchar(255) NOT NULL default '',  
      `user_firstname` varchar(255) NOT NULL default '',  
      `user_lastname` varchar(255) NOT NULL default '',  
      `user_email` varchar(255) default NULL,  
      `user_registration` datetime default '0000-00-00 00:00:00',  
      PRIMARY KEY  (`user_id`)  
    );

  
    <schema>

    <class name="User" table="users" />

    <attribute name="id" field="user_id" />

    <attribute name="uid" field="user_uid" required="true" />

    <attribute name="password" field="user_password" required="true" />

    <attribute name="firstName" field="user_firstname" required="true" />

    <attribute name="lastName" field="user_lastname" required="true" />

    <attribute name="email" field="user_email" />

    <attribute name="registration" field="user_registration" />

    <key name="id" />

    </schema>

  
One can then register and get the mapper by calling the following methods :

  
    $mapperBuilder->register( PDOMapperSchema::fromFile(SCHEMA_PATH.'/user.xml'));

    $userMapper = $mapperBuilder->getMapper( 'User' );

  
Then one can get the list of all users by calling PDOMapper::selectAll :

  
    $users = $userMapper->selectAll();

              
    foreach ( $users as $user )

    {

        $user->dump();  
    }

  
Select one user :

  
    $user = $userMapper->selectOne( $userMapper->getSchema()->getField( 'id' ) . '= :id',  array( ':id' => 4 ) );

  
Update a user :

  
    $user->uid = 'Zelda';

    $userMapper->update( $user );

  
Delete a user :

  
    $userMapper->delete( $user );

  
Delete all users :

  
    $userMapper->deleteAll();

  
Create a new user :

      
    $user = new User;

    $user->uid = 'mithrandir';

    $user->password = 'L0rien';

    $user->firstName = 'Gandalf';

    $user->lastName = 'Le Gris';

    $user->email = 'gandalf@root.org';

    $user->registration = date( "Y-m-d H:i:s" );

           
    $user6 = $userMapper->create( $user );

###  2.2. Working with relations

To illustrate relations, let's add the Post class to our blog.

  
Let's define the following class, database and schema for the Post object :

  
    class Post

    {

            public $id;  
            public $chapo;  
            public $author;  
            public $title;  
            public $postedTime;  
            public $content;  
      
            public function dump()  
            {  
                var_dump( $this );  
            }  
    }

  
    CREATE TABLE IF NOT EXISTS `posts` (

      `post_id` int(11) NOT NULL auto_increment,  
      `post_author` int(11) NOT NULL default 0,  
      `post_chapo` varchar(255) NOT NULL default '',  
      `post_title` varchar(255) NOT NULL default '',  
      `post_content` text NOT NULL,  
      `post_time` datetime default '0000-00-00 00:00:00',  
      PRIMARY KEY  (`post_id`)  
    );

  
    <schema>

    <class name="Post" table="posts" />

    <attribute name="id" field="post_id" />

    <attribute name="author" field="post_author" required="true" />

    <attribute name="title" field="post_title" required="true" />

    <attribute name="chapo" field="post_chapo" />

    <attribute name="content" field="post_content" />

    <attribute name="postedTime" field="post_time" />

    <key name="id" />

    </schema>

  

**2.2.1. Has One relation : Getting the author of a post :**

  
So we can get the author of a Post by calling the following :

  
    $mapperBuilder->register( PDOMapperSchema::fromFile(SCHEMA_PATH.'/user.xml'));

    $mapperBuilder->register( PDOMapperSchema::fromFile(SCHEMA_PATH.'/post.xml'));

    $userMapper = $mapperBuilder->getMapper( 'User' );

    $postMapper = $mapperBuilder->getMapper( 'Post' );

      
    // 1. load a given post by it's id :

    $post = $postsMapper->selectOne( 
        $postMapper->getSchema()->getField( 'author').' = :id',  
        array( ':id' => 1 ) );

    // 2. load the user with id corresponding to the Post author :

    $user4 = $userMapper->selectOne( 
        $userMapper->getSchema()->getField( 'id' ).'= :uid', 
        array( ':uid' => $post->author ) );

  
This method is great and simple but it has a flaw : we have to know about the
way a user is connected to his posts in the database to write the code ! We
can get rid of this limitation by defining a relation between User and Post :
one Post is owned by one User. In our schema, this will be represented by
**ahasone** element in the Post schema :

  
    <schema>

    <class name="Post" table="posts" />

    <attribute name="id" field="post_id" />

    <attribute name="author" field="post_author" required="true" />

    <attribute name="title" field="post_title" required="true" />

    <attribute name="chapo" field="post_chapo" />

    <attribute name="content" field="post_content" />

    <attribute name="postedTime" field="post_time" />

    **<hasone name="author" class="User" rel="Post.author:User.id" />**
    <key name="id" />

    </schema>

  
The added hasone element means that "_one post is owned by one user and that
this user is found by searching the user in the database having the post
author has its id_".

  
Using that, we can get the author of a post without the need to know the way
the relation is represented in the database since this is encapsulated within
the schema itself  :

  
    $mapperBuilder->register( PDOMapperSchema::fromFile(SCHEMA_PATH.'/user.xml'));

    $mapperBuilder->register( PDOMapperSchema::fromFile(SCHEMA_PATH.'/post.xml'));

    $userMapper = $mapperBuilder->getMapper( 'User' );

    $postMapper = $mapperBuilder->getMapper( 'Post' );

      
    // 1. load a given post by it's id :

    $post = $postMapper->selectOne( 
        $postMapper->getSchema()->getField( 'author').' = :id',  
        array( ':id' => 1 ) );

    // 2. load the user with id corresponding to the Post author :

    $user4 = $postMapper->hasOne( $post, 'author' ) );

  
**2.2.2. Has Many relation : Getting the posts of a user :**
  
We can also get the posts of a given user by calling :

  
    $mapperBuilder->register( PDOMapperSchema::fromFile(SCHEMA_PATH.'/user.xml'));

    $mapperBuilder->register( PDOMapperSchema::fromFile(SCHEMA_PATH.'/post.xml'));

    $userMapper = $mapperBuilder->getMapper( 'User' );

    $postMapper = $mapperBuilder->getMapper( 'Post' );

      
    // 1. load the user with the given user name :

    $user = $userMapper->selectOne( 
        $userMapper->getSchema()->getField( 'uid' ).' = :uid',  
        array( ':uid' => 'Zelda' ) );

    // 2. load the posts of the user :

    $posts = $postsMapper->selectAll( 
        $postMapper->getSchema()->getField( 'author').' = :id',  
        array( ':id' => $user->id ) );

  
Once again, it is great but we still need to know the relation between User
and Post in the database to run the code. One more time, we are going to use
relations to simplify the code. Let's add a has many relation between User and
Post since one User can write several Posts :

  
    <schema>

    <class name="User" table="users" />

    <attribute name="id" field="user_id" />

    <attribute name="uid" field="user_uid" required="true" />

    <attribute name="password" field="user_password" required="true" />

    <attribute name="firstName" field="user_firstname" required="true" />

    <attribute name="lastName" field="user_lastname" required="true" />

    <attribute name="email" field="user_email" />

    <attribute name="registration" field="user_registration" />

    **<hasmany name="posts" class="Post" rel="User.id:Post.author" />**
    <key name="id" />

    </schema>

  
The added **hasmany** element means that "_one user owns many posts and that
the posts of the user can be found by searching the posts in the database
having the user id has their author_".

  
We can get the posts of a given user by calling the following :

  
    $mapperBuilder->register( PDOMapperSchema::fromFile(SCHEMA_PATH.'/user.xml'));

    $mapperBuilder->register( PDOMapperSchema::fromFile(SCHEMA_PATH.'/post.xml'));

    $userMapper = $mapperBuilder->getMapper( 'User' );

    $postMapper = $mapperBuilder->getMapper( 'Post' );

      
    // 1. load the user with the given user name :

    $user = $userMapper->selectOne( 
        $userMapper->getSchema()->getField( 'uid' ).'= :uid',
        array( ':uid' => 'Zelda' ) );

    // 2. load the posts of the user :
    
    $posts = $userMapper->hasMany( $user, 'posts' );

  
Now we do not need to know about the internals of the relation between User
and Post anymore !

  
**2.2.3. Deleting with relations**
  
Let's see how relations can simplify the deletion of objects related through a
has one or has many relation.

  
In our blog application, if one delete a user, all the posts of this user must
be deleted. Without relation, we have to write the following.

  
    $mapperBuilder->register( PDOMapperSchema::fromFile(SCHEMA_PATH.'/user.xml'));

    $mapperBuilder->register( PDOMapperSchema::fromFile(SCHEMA_PATH.'/post.xml'));

    $userMapper = $mapperBuilder->getMapper( 'User' );

    $postMapper = $mapperBuilder->getMapper( 'Post' );

      
    // 1. load the user with the given user name :

    $user = $userMapper->selectOne( 
        $userMapper->getSchema()->getField( 'uid' ).' = :uid',  
        array( ':uid' => 'Zelda' ) );

    // 2. delete the posts of the given user :

    $postsMapper->deleteWhere(
        $postMapper->getSchema()->getField( 'author' ).' = :id', 
        array( ':id' => $user->id ));

    // 3. delete the user :

    $userMapper->delete( $user );

  
With relation we can also write something like this :

  
    $mapperBuilder->register( PDOMapperSchema::fromFile(SCHEMA_PATH.'/user.xml'));

    $mapperBuilder->register( PDOMapperSchema::fromFile(SCHEMA_PATH.'/post.xml'));

    $userMapper = $mapperBuilder->getMapper( 'User' );

    $postMapper = $mapperBuilder->getMapper( 'Post' );

  
    // 1. load the user with the given user name :

    $user = $userMapper->selectOne( 
        $userMapper->getSchema()->getField( 'uid' ).' = :uid', 
        array( ':uid' => 'Zelda' ) );

    // 2. load the posts of the user :

    $posts = $userMapper->hasMany( $user, 'posts' );

      
    foreach ( $posts as $post )

    {

        $postMapper->delete( $post );  
    }

  
But this is not great since we have to perform one SQL query for each
deletion. How can we optimize this ? By using deleteHasOne or deleteHasMany :

  
    $mapperBuilder->register( PDOMapperSchema::fromFile(SCHEMA_PATH.'/user.xml'));

    $mapperBuilder->register( PDOMapperSchema::fromFile(SCHEMA_PATH.'/post.xml'));

    $userMapper = $mapperBuilder->getMapper( 'User' );

    $postMapper = $mapperBuilder->getMapper( 'Post' );

  
    // 1. load the user with the given user name :

    $user = $userMapper->selectOne( 
        $userMapper->getSchema()->getField( 'uid' ).' = :uid', 
        array( ':uid' => 'Zelda' ) );

    // 2. delete the user posts :

    $userMapper->deleteHasMany( $user, 'posts' );

    // 3. delete the user :

    $userMapper->delete( $user );

  
But we still have to delete the posts "by hand". In fact we can do something
better by using relation triggers :

  
    <schema>

    <class name="User" table="users" />

    <attribute name="id" field="user_id" />

    <attribute name="uid" field="user_uid" required="true" />

    <attribute name="password" field="user_password" required="true" />

    <attribute name="firstName" field="user_firstname" required="true" />

    <attribute name="lastName" field="user_lastname" required="true" />

    <attribute name="email" field="user_email" />

    <attribute name="registration" field="user_registration" />

    **<hasmany name="posts" class="Post" rel="User.id:Post.author" ondelete="delete" />**
    <key name="id" />

    </schema>

  
The ondelete="delete" attribute tells pdocrud to delete all the posts of the
user when the user is delete. This will be executed automatically by the
delete method of $userMapper :

  
    $mapperBuilder->register( PDOMapperSchema::fromFile(SCHEMA_PATH.'/user.xml'));

    $mapperBuilder->register( PDOMapperSchema::fromFile(SCHEMA_PATH.'/post.xml'));

    $userMapper = $mapperBuilder->getMapper( 'User' );

    $postMapper = $mapperBuilder->getMapper( 'Post' );

  
    // 1. load the user with the given user name :

    $user = $userMapper->selectOne( 
        $userMapper->getSchema()->getField( 'uid' ).' = :uid',  
        array( ':uid' => 'Zelda' ) );

    // 2. delete the user and all its related posts :

    $userMapper->delete( $user );

  
Again all the details are encapsulated into the relation and the developer do
not have to care about the details !

##  3. Appendices

###  3.1 Class diagrams

PDO mapper class diagram :

![][1]

PDO factory class diagram :

![][2]

PDO SQL script class diagram :

![][3]

  

   [1]: pdomapper.png
   [2]: pdofactory.png
   [3]: pdosqlscript.png

