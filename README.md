Vanilla 1 Development Has Ended
===============================

Please use the [Vanilla Porter](http://vanillaforums.org/addon/porter-core) to upgrade to [Vanilla 2](http://vanillaforums.org/addon/vanilla-core). See the [upgrade instructions](https://github.com/vanilla/vanilla#migrating-to-vanilla) for more information. 

Vanilla 1 is no longer vetted for security issues or otherwise maintained in any way. This repository is for historical reference only.


Building Vanilla 1
==================

For production, you probably will want to build Vanilla;
the build process will compress js and css files and produce a zip archive.
Vanilla is built using Ant (http://ant.apache.org/).


On OS X 10.6
------------

Ant and subversion are installed by default.

- Open a terminal;
- Check-out the trunk:

	svn co http://lussumo-vanilla.googlecode.com/svn/trunk/ vanilla-dev

- Build Vanilla:

        cd vanilla-dev
        svn up
        ant


On Ubuntu
---------

Like on OSX 10.6, but you will have first to install Ant and subversion:

- Install Ant and Subversion:

	sudo apt-get install subversion sun-java6-jdk ant ant-optional


On Windows
----------

- Install a Java Development Kit or put its files in a folder called "jdk" in the tools folder
  (<http://java.sun.com/javase/downloads/index.jsp>, version 1.5 or higher);
  
- Install Ant or put its files in a folder called "ant" in the tools folder
  (http://ant.apache.org/bindownload.cgi).
  You just need to unzip the binary version anywhere 
  on your computer and set some environment variables 
  (on Windows Vista, open the control panel and search for "environment variables").
  Follow the instructions from the http://ant.apache.org/manual/install.html#installing;
  
- Install a Subversion client and checkout vanilla. With somethin like TortoiseSVN, 
  you would create a folder for Vanilla, right click on it and select "SVN checkout..."
  in the contextual menu; set the "URL of the repository" to 
  http://lussumo-vanilla.googlecode.com/svn/trunk/ and click "ok".
  
- build the package by clicking on build.bat in the vanilla folder.
  the package is created in dist/

Each time you want to create an updated package, simple click on build.bat.
It will download the last update for you.


Note about extensions
---------------------

Vanilla will only include in the build the extensions that are explicity set to be.

To be added, the extension need a build script with a dist target. Here a generic build script
that can be used:

	<?xml version="1.0" encoding="UTF-8"?>

	<project name="ExtensionName" default="build">
    
		<description>
            Package the ExtensionName extension
    	</description>
	
		<property name="antlib.dir" location="../../../tools/ant-library/"/>
		<import file="${antlib.dir}/extension.xml"/>
	
	</project>


If you want to use your own Ant script, Vanilla build script will call the dist target and
will have set the build.dir, dist.dir, package.name and task.compressor.defined properties.
It expects the extension to be built in the location set in the  build.dir property and
a zip archive to be created in one set in dist.dir property. Look for details at extension.xml
<http://code.google.com/p/lussumo-vanilla/source/browse/trunk/tools/ant-library/extension.xml>. 

Then you should add to the vanilla build script at the end of the build target:

	<buildExtension name="ExtensionName"/>

ExtensionName should be the folder name that host your extension in the extensions folder. 


Releasing new version (for maintainers)
=======================================

The ant script release-build.xml will build the new release, create a list of changed files
since the last release, tag it and upload the the new release.

First, set your Google code username and password by creating a 
"svn-credential.properties" file. Use svn-credentials.properties-tmp for template.

Then edit the current and previous version numbers in src/appg/version.php and commit it;
e.g, for version 1.1.7:

http://code.google.com/p/lussumo-vanilla/source/diff?spec=svn815&r=815&format=side&path=/trunk/src/appg/version.php

To just build the new release and and the list of changes:

	ant -f release-build.xml

To also tag it and upload it on Google code:

	ant -f release-build.xml release
