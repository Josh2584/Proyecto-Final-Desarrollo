@echo off
set "BASE=%~dp0.."
for %%I in ("%BASE%") do set "BASE=%%~fI"
subst V: "%BASE%" 2>nul
"V:\tools\httpd-2.4.67-260504-Win64-VS18\Apache24\bin\httpd.exe" -f "V:/tools/httpd-2.4.67-260504-Win64-VS18/Apache24/conf/apache-proxy.conf"
