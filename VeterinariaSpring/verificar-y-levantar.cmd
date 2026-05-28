@echo off
setlocal
cd /d "%~dp0"
if "%~1"=="" (
    powershell -NoProfile -ExecutionPolicy Bypass -File "%~dp0scripts\verificar-y-levantar.ps1"
) else (
    powershell -NoProfile -ExecutionPolicy Bypass -File "%~dp0scripts\verificar-y-levantar.ps1" -ProjectDir "%~1"
)
pause
