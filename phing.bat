@echo off
@setlocal
set PHING_PATH=vendor/bin/
"%PHING_PATH%phing.bat" %*
@endlocal