# Matcha

## Prerequisite:
- Neo4j needs to be installed: 
  - **Mac**: `brew install neo4j`
  - **Linux**: Download Neo4j Community and run the `neo4j` file found inside the `bin` directory
  
- Apache Server and PHP:
  The easiest way is to install an *AMP package.
  - **Mac**: MAMP
  - **Linux**: LAMP

- You will need `composer.phar` to manage the php packages for Matcha. If its not in the root directory,
  - [Download Composer](https://getcomposer.org/download/) - See 'Manual Download' section for the precompiled `composer.phar`
  - Put it in the root directory of Matcha
  - Run `php composer.phar install`
  
- You will need to install GeoIP
  - [GeoIP Github Page](https://github.com/maxmind/GeoIP2-php)
  - [Download GeoIP Database](http://dev.maxmind.com/geoip/geoip2/geolite2/) - Put the database in your `htdocs` directory or whichever directory is your root.
  - The sample code from the GeoIP Github page should work, so use that for testing.
  
## Config changes: 
**Neo4j Config file:**
`neo4j.conf`
> Uncomment line 9, change the location if needed. 

> **EG :** dbms.directories.data=/nfs/zfs-student-6/users/kbamping/goinfre/matcha_db

**Location :**
  - **Mac**: ~/.brew/Cellar/neo4j/3.0.6/libexec/conf/neo4j.conf
  - **Linux**: /etc/neo4j/conf/neo4j.conf (not nessessarry to change this on Linux)
