:checkAntHome
if not defined ANT_HOME goto noAntHome
set EXEC=%ANT_HOME%\bin\ant.bat
goto build

:noAntHome
echo WARNING: You have not set the ANT_HOME environment variable. Ant is require to build vanilla.
set EXEC=ant

:build
%EXEC%