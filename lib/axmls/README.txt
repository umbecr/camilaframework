
                                adodb-xmlschema
     _________________________________________________________________

   Originally written by [1]Richard Tango-Lowy with many fine additions
   and improvements by [2]Dan Cech.

   For more information or to [3]report bugs or [4]enhancements, or to
   [5]download the latest version, visit the [6]adodb-xmlschema
   Sourceforge Project Page.

Introduction

   adodb-xmlschema is a class that allows the user to quickly and easily
   build or upgrade a database on almost any RDBMS using the excellent
   [7]ADODB database library and a simple XML formatted schema file.

   This library is dual-licensed under a BSD-style license and under the
   [8]GNU Lesser Public License. See the LICENSE file for more
   information.

Features

     * Darned easy to install (included with all newer versions of
       ADODB).
     * Create, upgrade and remove schemas on any platform supported by
       ADODB.
     * Extract a schema from an existing database.
     * Embed RDBMS-specific sections in a schema.
     * Embed arbitrary SQL in a schema.
     * Embed data in a schema.

Installation

   To install adodb-xmlschema, simply copy the adodb-xmlschema.inc.php
   file and the xsl directory into your ADODB directory.

Documentation

   See the docs directory for online documentation and tutorials.

News

  Version 1.0.2

     * Table prefixing is now working correctly.
     * Support for uninstalling databases with the RemoveSchema command.
     * You can now embed default data within the schema for population of
       tables.
     * Many other fixes

  Version 1.0.1

     * The PHP XSLT module is now optional, and is only required for DTD
       conversions.
     * It's now possible to execute the SQL inline (during parsing).
     * Added a Using AXMLS tutorial (browse to index.html in the docs
       directory).

  Version 1.0

     * Thanks to Dan, AXMLS now uses XSLT to convert between DTD schema
       versions. XSLT will also allow us to create other transformations
       against the schema format in the future.
     * Indexes are now part of the table object; they should no longer be
       declared seperately in the schema XML.
     * Upgrading is now transparent. If you apply a schema to an existing
       table, the table gets upgraded to the new schema. This has been
       tested on Oracle and MySQL, but doesn't yet work on PostgreSQL
       (Very soon, very soon).
     * In addition to parsing files, adoSchema now has methods for
       parsing XML Schema strings. This should give us more room to
       extend the library, and give you, the developer, room to
       manipulate the schema string should you feel the need.
     * Check out the new Getting Started tutorial (browse to index.html
       in the docs directory) and improved autodocs. An Advanced User's
       Guide is in the works.
     _________________________________________________________________

Thanks

   Thanks to John Lim for giving us ADODB, and for the hard work that
   keeps it on top of things. And particulary for the datadict code that
   made xmlschema possible.
     _________________________________________________________________

   $Id: README.txt,v 1.5 2004/07/27 17:09:06 dcech Exp $

References

   1. mailto:richtl@arscognita.com
   2. mailto:dcech@phpwerx.net
   3. http://sourceforge.net/tracker/?group_id=81768&atid=563961
   4. http://sourceforge.net/tracker/?group_id=81768&atid=563964
   5. http://sourceforge.net/project/showfiles.php?group_id=81768
   6. http://sourceforge.net/projects/adodb-xmlschema/
   7. http://php.weblogs.com/ADODB
   8. http://opensource.org/licenses/lgpl-license.php
