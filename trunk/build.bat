:checkAntHome
if not defined ANT_HOME goto noAntHome
set ANT_EXEC=%ANT_HOME%\bin\ant.bat
goto build

:noAntHome
echo WARNING: You have not set the ANT_HOME environment variable. Ant is require to build vanilla.
set ANT_EXEC=ant

:build
%ANT_EXEC% update && %ANT_EXEC%