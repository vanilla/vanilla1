The last development version is available at http://lussumo.com/svn/vanilla/trunk/.

The content of the "src" folder can be used for testing and development.

For production, you probably will want to build Vanilla;
the build process will compress js and css files and produce a zip archive.
Vanilla is built using Ant (http://ant.apache.org/).


Building Vanilla on Ubuntu
==========================

- Open a terminal;
- Install Ant and Subversion:<code>sudo apt-get install subversion sun-java6-jdk ant ant-optional php5-cli</code>
- Check-out the trunk:<code>svn co http://lussumo-vanilla.googlecode.com/svn/trunk/ vanilla-dev</code>
- Build Vanilla:
	
	cd vanilla-dev
	svn up
	ant

It might fail if Ant cannot find a recent a jdk version of Java.


Building Vanilla on Windows
===========================

- Install a Java development kit 
  (http://java.sun.com/javase/downloads/index.jsp,version 1.5 or higher);
  
- Install Ant (http://ant.apache.org/bindownload.cgi). 
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