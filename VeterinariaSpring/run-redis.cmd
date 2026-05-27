@echo off
set "BASE=%~dp0.."
for %%I in ("%BASE%") do set "BASE=%%~fI"
"%BASE%\tools\Redis-x64-5.0.14.1\redis-server.exe" --bind 127.0.0.1 --port 6379
