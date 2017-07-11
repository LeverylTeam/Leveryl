@echo off
REM Modified for Leveryl by @CortexPE. (All I ask of you is, Please don't remove this line. Or you'll have to deal with a DMCA Take Down.)

REM Start Script options [WARNING: If you don't know these, Don't attempt to change it]
REM ----------------------------

REM Set to "yes" to automatically start the server up. DEFAULT: "no"
set DO_LOOP="yes"

REM Transparency options [WARNING: THIS HAS ONLY BEEN TESTED ON Windows 10] DEFAULT: "off"
REM Set to "off" to disable...
REM Available Values: ["off","low","medium","high"]
set TRANSPARENCY_LEVEL="high"

REM Console Window Size: DEFAULT: CONSOLE_COLUMNS=88 | CONSOLE_ROWS=32
set CONSOLE_COLUMNS=88
set CONSOLE_ROWS=32

REM Console Font: DEFAULT: "Consolas"
set CONSOLE_FONT="Consolas"
REM ----------------------------

title Leveryl
cd /d %~dp0
tasklist /FI "IMAGENAME eq mintty.exe" 2>NUL | find /I /N "mintty.exe">NUL
if %ERRORLEVEL% equ 0 (
    goto :loop
) else (
    goto :start
)

:loop
tasklist /FI "IMAGENAME eq mintty.exe" 2>NUL | find /I /N "mintty.exe">NUL
if %ERRORLEVEL% equ 0 (
    goto :loop
) else (
	goto :start
)

:start
if exist bin\php\php.exe (
    set PHP_BINARY=bin\php\php.exe
) else (
    set PHP_BINARY=php
)
if exist PocketMine-MP.phar (
    set POCKETMINE_FILE=PocketMine-MP.phar
) else if exist plugins\Leveryl\Leveryl*.phar (
	REM If a DevTools build exists
    set POCKETMINE_FILE=.\plugins\Leveryl\Leveryl*.phar
) else if exist Leveryl*.phar (
    set POCKETMINE_FILE=Leveryl*.phar
) else (
    if exist src\pocketmine\PocketMine.php (
        set POCKETMINE_FILE=src\pocketmine\PocketMine.php
    ) else (
        msg * "ERROR: Couldn't find a valid Leveryl installation..."
        pause
        exit 1
    )
)

if exist bin\php\php_wxwidgets.dll (
    %PHP_BINARY% %POCKETMINE_FILE% --enable-gui %*
) else (
    if exist bin\mintty.exe (
		if exist assets\leveryl.ico (
			start "" bin\mintty.exe -o Columns=%CONSOLE_COLUMNS% -o Rows=%CONSOLE_ROWS% -o AllowBlinking=0 -o FontQuality=3 -o Font=%CONSOLE_FONT% -o FontHeight=10 -o CursorType=0 -o CursorBlinks=1 -o Transparency=%TRANSPARENCY_LEVEL% -h error -t "Leveryl" -i assets/leveryl.ico -w max %PHP_BINARY% %POCKETMINE_FILE% --enable-ansi %*
		) else (
			start "" bin\mintty.exe -o Columns=%CONSOLE_COLUMNS% -o Rows=%CONSOLE_ROWS% -o AllowBlinking=0 -o FontQuality=3 -o Font=%CONSOLE_FONT% -o FontHeight=10 -o CursorType=0 -o CursorBlinks=1 -o Transparency=%TRANSPARENCY_LEVEL% -h error -t "Leveryl" -i bin/pocketmine.ico -w max %PHP_BINARY% %POCKETMINE_FILE% --enable-ansi %*
		)
    ) else (
        %PHP_BINARY% -c bin\php %POCKETMINE_FILE% %*
    )
)

if %DO_LOOP% == "yes" (
	goto :loop
)