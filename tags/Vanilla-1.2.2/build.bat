:checkAntHome
if not defined ANT_HOME goto noAntHome
set ANT_EXEC=%ANT_HOME%\bin\ant.bat
goto checkJavaHome

:noAntHome
echo WARNING: You have not set the ANT_HOME environment variable. Ant is required to build Vanilla.
echo Checking for Ant in tools folder...
set ANT_HOME=%CD%\tools\ant
set ANT_EXEC=%ANT_HOME%\bin\ant.bat
goto checkJavaHome

:checkJavaHome
if not defined JAVA_HOME goto noJavaHome
goto build

:noJavaHome
echo WARNING: You have not set the JAVA_HOME environment variable. JDK is required to build Vanilla.
echo Checking for JDK in tools folder...
set JAVA_HOME=%CD%\tools\jdk
goto build

:build
%ANT_EXEC% update && %ANT_EXEC%