pdocrud
=======

An ORM based on PDO written years ago

#  PDO-based Object Relational Mapper

**IDEAS :**
  
- add triggers on relations for the other member of the relation (default: keep) :
    - ondelete="(keep|delete)" done
    - onupdate="(keep|update)"
- use camelizer and pluralizer classes to compute missing table or field names
- DatabaseToSchema class to convert an existing database table to a PDOMapperSchema
  
DRAFT - WORKING COPY !!!!

  
This document describes a simple ORM implementation based on PHP Data Object.

  
@version     2007-12-10

@copyright   2001-2012 Universite catholique de Louvain (UCL)

@author      Frederic Minne <zefredz@claroline.net>

@license     http://creativecommons.org/licenses/by-nc-sa/2.0/be/

             CreativeCommons Attribution-Noncommercial-Share Alike 2.0

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

The ORM architecture described here is aimed to provide the following features
:

  

  1. a lightweight easy to use and understand object-oriented ORM architecture 
  2. based on PDO, simplexml and other PHP 5 powerfull features 
  3. CRUD (Create Read Update Delete) objects based on PHP classes with no need to implement the SQL queries 
  4. basic relations : 

    1. hasone : object has another object mapped from the db mapped to one of his attribute 
    2. hasmany : object has many objects mapped from the db mapped to one of his attribute 

  

###  Acronyms and conventions

**PDO **: PHP Data Object
**ORM **: Object-Relationnal Mapper
**DSN **: Data Source Name
**CRUD **: Create Read Update Delete
  
code source are in courier new

###  Warning

**At this time the basic operations select(One/All), create, update, delete, hasOne and hasMany are already working. Planned feature : hasAndBelongsToOne/Many based on a n:m relation table.**
  
Note that the database and the tables used by the PDO-based ORM must exist in
the DBMS.

