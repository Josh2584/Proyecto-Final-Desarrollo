[CmdletBinding()]
param(
    [string]$ProjectDir = $env:VETERINARIA_PROJECT_DIR
)

$ErrorActionPreference = "Stop"

if ([string]::IsNullOrWhiteSpace($ProjectDir)) {
    $candidate = Split-Path -Parent $PSScriptRoot
    if (Test-Path -LiteralPath (Join-Path $candidate "pom.xml")) {
        $ProjectDir = $candidate
    } elseif (Test-Path -LiteralPath (Join-Path $candidate "VeterinariaSpring\pom.xml")) {
        $ProjectDir = Join-Path $candidate "VeterinariaSpring"
    } else {
        throw "No se encontro VeterinariaSpring. Copia este .cmd y la carpeta scripts dentro de VeterinariaSpring, o ejecuta: set VETERINARIA_PROJECT_DIR=RUTA_COMPLETA_DE_VeterinariaSpring"
    }
}

$ProjectDir = (Resolve-Path -LiteralPath $ProjectDir).Path
$BaseDir = Split-Path -Parent $ProjectDir
$ToolsDir = Join-Path $BaseDir "tools"
$RuntimeDir = Join-Path $BaseDir "runtime"
$DownloadsDir = Join-Path $RuntimeDir "downloads"
$LogDir = Join-Path $RuntimeDir "launcher-logs"

$RedisDir = Join-Path $ToolsDir "Redis-x64-5.0.14.1"
$RabbitDir = Join-Path $ToolsDir "rabbitmq_server-4.3.1"
$ErlangDir = Join-Path $ToolsDir "erl-27"
$ApacheDir = Join-Path $ToolsDir "httpd-2.4.67-260504-Win64-VS18"

$RedisZip = Join-Path $DownloadsDir "Redis-x64-5.0.14.1.zip"
$RabbitZip = Join-Path $DownloadsDir "rabbitmq-server-windows-4.3.1.zip"
$ApacheZip = Join-Path $DownloadsDir "httpd-2.4.67-260504-Win64-VS18.zip"
$ErlangInstaller = Join-Path $DownloadsDir "otp_win64_27.3.4.11.exe"

$RedisUrl = "https://github.com/tporadowski/redis/releases/download/v5.0.14.1/Redis-x64-5.0.14.1.zip"
$RabbitUrl = "https://github.com/rabbitmq/rabbitmq-server/releases/download/v4.3.1/rabbitmq-server-windows-4.3.1.zip"
$ApacheUrl = "https://www.apachelounge.com/download/VS17/binaries/httpd-2.4.67-260504-Win64-VS17.zip"
$ErlangUrl = "https://github.com/erlang/otp/releases/download/OTP-27.3.4.11/otp_win64_27.3.4.11.exe"

function Write-Step($Message) {
    Write-Host ""
    Write-Host "==> $Message" -ForegroundColor Cyan
}

function Write-Ok($Message) {
    Write-Host "OK: $Message" -ForegroundColor Green
}

function Write-Warn($Message) {
    Write-Host "AVISO: $Message" -ForegroundColor Yellow
}

function Ensure-Directory($Path) {
    if (!(Test-Path -LiteralPath $Path)) {
        New-Item -ItemType Directory -Path $Path -Force | Out-Null
    }
}

function Command-Exists($Command) {
    return $null -ne (Get-Command $Command -ErrorAction SilentlyContinue)
}

function Port-Is-Open($Port) {
    return $null -ne (Get-NetTCPConnection -LocalPort $Port -State Listen -ErrorAction SilentlyContinue)
}

function Download-File($Url, $OutFile) {
    Ensure-Directory (Split-Path -Parent $OutFile)
    if (Test-Path -LiteralPath $OutFile) {
        Write-Ok "Ya existe $(Split-Path -Leaf $OutFile)"
        return
    }
    Write-Host "Descargando: $Url"
    Invoke-WebRequest -Uri $Url -OutFile $OutFile
}

function Expand-Zip($ZipFile, $Destination) {
    if (Test-Path -LiteralPath $Destination) {
        Write-Ok "Ya existe $Destination"
        return
    }
    Ensure-Directory (Split-Path -Parent $Destination)
    $Temp = Join-Path $DownloadsDir ("extract-" + [Guid]::NewGuid().ToString("N"))
    Ensure-Directory $Temp
    Expand-Archive -LiteralPath $ZipFile -DestinationPath $Temp -Force
    $items = Get-ChildItem -LiteralPath $Temp
    if ($items.Count -eq 1 -and $items[0].PSIsContainer) {
        Move-Item -LiteralPath $items[0].FullName -Destination $Destination
    } else {
        Ensure-Directory $Destination
        Move-Item -LiteralPath (Join-Path $Temp "*") -Destination $Destination -Force
    }
    Remove-Item -LiteralPath $Temp -Recurse -Force
}

function Expand-ApacheZip($ZipFile, $Destination) {
    if (Test-Path -LiteralPath (Join-Path $Destination "Apache24\bin\httpd.exe")) {
        Write-Ok "Ya existe Apache en $Destination"
        return
    }
    Ensure-Directory $Destination
    $Temp = Join-Path $DownloadsDir ("extract-apache-" + [Guid]::NewGuid().ToString("N"))
    Ensure-Directory $Temp
    Expand-Archive -LiteralPath $ZipFile -DestinationPath $Temp -Force

    $apache24 = Get-ChildItem -LiteralPath $Temp -Recurse -Directory | Where-Object { $_.Name -eq "Apache24" } | Select-Object -First 1
    if (!$apache24) {
        Remove-Item -LiteralPath $Temp -Recurse -Force
        throw "No se encontro la carpeta Apache24 dentro del ZIP de Apache."
    }

    $targetApache24 = Join-Path $Destination "Apache24"
    if (Test-Path -LiteralPath $targetApache24) {
        Remove-Item -LiteralPath $targetApache24 -Recurse -Force
    }
    Move-Item -LiteralPath $apache24.FullName -Destination $targetApache24
    Remove-Item -LiteralPath $Temp -Recurse -Force
}

function Wait-Port($Port, $Seconds) {
    $limit = (Get-Date).AddSeconds($Seconds)
    while ((Get-Date) -lt $limit) {
        if (Port-Is-Open $Port) {
            return $true
        }
        Start-Sleep -Seconds 1
    }
    return (Port-Is-Open $Port)
}

function Ensure-ErlangIni() {
    $iniFiles = @(
        (Join-Path $ErlangDir "bin\erl.ini"),
        (Join-Path $ErlangDir "erts-15.2.7.8\bin\erl.ini")
    )
    foreach ($ini in $iniFiles) {
        if (Test-Path -LiteralPath $ini) {
            $bindir = (Join-Path $ErlangDir "erts-15.2.7.8\bin") -replace "\\", "\\"
            $rootdir = $ErlangDir -replace "\\", "\\"
            @"
[erlang]
Bindir=$bindir
Progname=erl
Rootdir=$rootdir
"@ | Set-Content -LiteralPath $ini -Encoding ASCII
        }
    }
}

function Ensure-ApacheConfig() {
    $apache24 = Join-Path $ApacheDir "Apache24"
    $confDir = Join-Path $apache24 "conf"
    $conf = Join-Path $confDir "apache-proxy.conf"
    Ensure-Directory $confDir
    Ensure-Directory (Join-Path $RuntimeDir "apache")
    $serverRoot = ($apache24 -replace "\\", "/")
    $runtimeApache = ((Join-Path $RuntimeDir "apache") -replace "\\", "/")
    $moduleLines = @()
    foreach ($module in @(
        "mod_mpm_winnt.so",
        "mod_authz_core.so",
        "mod_authz_host.so",
        "mod_dir.so",
        "mod_mime.so",
        "mod_log_config.so",
        "mod_proxy.so",
        "mod_proxy_http.so",
        "mod_headers.so"
    )) {
        if (Test-Path -LiteralPath (Join-Path $apache24 "modules\$module")) {
            $moduleName = ($module -replace "^mod_", "" -replace "\.so$", "") + "_module"
            $moduleLines += "LoadModule $moduleName modules/$module"
        }
    }
    $moduleBlock = $moduleLines -join "`r`n"
    @"
ServerRoot "$serverRoot"
Listen 8090
ServerName localhost:8090

$moduleBlock

ErrorLog "$runtimeApache/error.log"
CustomLog "$runtimeApache/access.log" common

ProxyRequests Off
ProxyPreserveHost On
<Location />
    Require all granted
</Location>
ProxyPass / http://127.0.0.1:8080/
ProxyPassReverse / http://127.0.0.1:8080/
"@ | Set-Content -LiteralPath $conf -Encoding ASCII
}

function Start-CmdHidden($Name, $Command, $LogFile) {
    Ensure-Directory (Split-Path -Parent $LogFile)
    Write-Host "Levantando $Name..."
    $safeName = ($Name -replace "[^A-Za-z0-9_-]", "-").ToLowerInvariant()
    $runner = Join-Path (Split-Path -Parent $LogFile) ("run-$safeName.cmd")
    @"
@echo off
$Command
"@ | Set-Content -LiteralPath $runner -Encoding ASCII
    Start-Process -FilePath "cmd.exe" -ArgumentList "/s /c `"`"$runner`" > `"$LogFile`" 2>&1`"" -WindowStyle Hidden
}

Ensure-Directory $ToolsDir
Ensure-Directory $RuntimeDir
Ensure-Directory $DownloadsDir
Ensure-Directory $LogDir

Write-Step "Verificando Java"
if (Command-Exists "java") {
    java -version
    Write-Ok "Java detectado"
} else {
    Write-Warn "Java no esta en PATH. Instala JDK 21 o agrega JAVA_HOME/PATH."
}

Write-Step "Verificando Maven"
if (Command-Exists "mvn") {
    mvn -version
    Write-Ok "Maven detectado"
} else {
    Write-Warn "Maven no esta en PATH. Spring Tools puede tener Maven integrado, pero para este script se recomienda instalar Maven."
}

Write-Step "Verificando Oracle"
if (Port-Is-Open 1521) {
    Write-Ok "Oracle parece estar escuchando en el puerto 1521"
} else {
    Write-Warn "Oracle no parece estar escuchando en 1521. Abre Oracle antes de usar pantallas que consultan la base."
}

Write-Step "Verificando Redis"
if (!(Test-Path -LiteralPath (Join-Path $RedisDir "redis-server.exe"))) {
    Download-File $RedisUrl $RedisZip
    Expand-Zip $RedisZip $RedisDir
}
Write-Ok "Redis disponible"

Write-Step "Verificando RabbitMQ"
if (!(Test-Path -LiteralPath (Join-Path $RabbitDir "sbin\rabbitmq-server.bat"))) {
    Download-File $RabbitUrl $RabbitZip
    Expand-Zip $RabbitZip $RabbitDir
}
Write-Ok "RabbitMQ disponible"

Write-Step "Verificando Erlang"
if (!(Test-Path -LiteralPath (Join-Path $ErlangDir "bin\erl.exe"))) {
    Write-Warn "No se encontro Erlang portable en tools\erl-27."
    Download-File $ErlangUrl $ErlangInstaller
    Write-Warn "Se descargo el instalador de Erlang en runtime\downloads. Intentando instalacion silenciosa..."
    $installRoot = $ErlangDir
    Start-Process -FilePath $ErlangInstaller -ArgumentList "/S", "/D=$installRoot" -Wait
    if (!(Test-Path -LiteralPath (Join-Path $ErlangDir "bin\erl.exe"))) {
        Write-Warn "No se pudo confirmar Erlang portable en tools\erl-27. Si RabbitMQ no levanta, instala Erlang manualmente ejecutando: $ErlangInstaller"
    } else {
        Ensure-ErlangIni
        Write-Ok "Erlang instalado"
    }
} else {
    Ensure-ErlangIni
    Write-Ok "Erlang portable disponible"
}

Write-Step "Verificando Apache"
if (!(Test-Path -LiteralPath (Join-Path $ApacheDir "Apache24\bin\httpd.exe"))) {
    Download-File $ApacheUrl $ApacheZip
    Expand-ApacheZip $ApacheZip $ApacheDir
}
Ensure-ApacheConfig
Write-Ok "Apache disponible"

Write-Step "Levantando servicios"
if (Port-Is-Open 6379) {
    Write-Ok "Redis ya esta levantado en 6379"
} else {
    Start-CmdHidden "Redis" "`"$RedisDir\redis-server.exe`" --bind 127.0.0.1 --port 6379" (Join-Path $LogDir "redis.log")
    Wait-Port 6379 10 | Out-Null
}

if (Port-Is-Open 5672) {
    Write-Ok "RabbitMQ ya esta levantado en 5672"
} else {
    $rabbitCommand = "set `"ERLANG_HOME=$ErlangDir`" && set `"PATH=$ErlangDir\bin;$ErlangDir\erts-15.2.7.8\bin;%PATH%`" && set `"RABBITMQ_BASE=$RuntimeDir\rabbitmq431`" && set `"RABBITMQ_NODENAME=rabbit@localhost`" && set `"USERPROFILE=$RuntimeDir\rabbitmq431`" && `"$RabbitDir\sbin\rabbitmq-server.bat`""
    Start-CmdHidden "RabbitMQ" $rabbitCommand (Join-Path $LogDir "rabbitmq.log")
    Wait-Port 5672 45 | Out-Null
}

if ((Test-Path -LiteralPath (Join-Path $RabbitDir "sbin\rabbitmq-plugins.bat")) -and (Port-Is-Open 5672)) {
    $env:ERLANG_HOME = $ErlangDir
    $env:PATH = "$ErlangDir\bin;$ErlangDir\erts-15.2.7.8\bin;$env:PATH"
    $env:RABBITMQ_BASE = Join-Path $RuntimeDir "rabbitmq431"
    $env:RABBITMQ_NODENAME = "rabbit@localhost"
    try {
        & (Join-Path $RabbitDir "sbin\rabbitmq-plugins.bat") enable rabbitmq_management | Out-Null
        Write-Ok "Panel de RabbitMQ habilitado"
    } catch {
        Write-Warn "No se pudo habilitar rabbitmq_management automaticamente: $($_.Exception.Message)"
    }
}

if (Port-Is-Open 8080) {
    Write-Ok "Spring Boot ya esta levantado en 8080"
} else {
    if (Command-Exists "mvn") {
        Start-CmdHidden "Spring Boot" "cd /d `"$ProjectDir`" && mvn spring-boot:run -Dspring-boot.run.arguments=--spring.cache.type=simple" (Join-Path $LogDir "spring.log")
        Wait-Port 8080 60 | Out-Null
    } else {
        Write-Warn "No se puede levantar Spring por CMD porque Maven no esta en PATH. Ejecuta VeterinariaApplication.java desde Spring Tools."
    }
}

if (Port-Is-Open 8090) {
    Write-Ok "Apache ya esta levantado en 8090"
} else {
    $apacheConf = Join-Path $ApacheDir "Apache24\conf\apache-proxy.conf"
    $apacheConfForCmd = $apacheConf -replace "\\", "/"
    Start-CmdHidden "Apache" "`"$ApacheDir\Apache24\bin\httpd.exe`" -f `"$apacheConfForCmd`"" (Join-Path $LogDir "apache.log")
    Wait-Port 8090 15 | Out-Null
}

Write-Step "Resultado"
foreach ($port in @(6379, 5672, 15672, 8080, 8090)) {
    if (Port-Is-Open $port) {
        Write-Ok "Puerto $port activo"
    } else {
        Write-Warn "Puerto $port no activo"
    }
}

Write-Host ""
Write-Host "URLs:" -ForegroundColor Cyan
Write-Host "Aplicacion por Apache: http://127.0.0.1:8090/login"
Write-Host "Aplicacion directa:     http://127.0.0.1:8080/login"
Write-Host "RabbitMQ panel:         http://127.0.0.1:15672  guest / guest"
Write-Host ""
Write-Host "Logs del arranque: $LogDir"
