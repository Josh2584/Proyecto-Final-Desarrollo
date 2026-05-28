# Proyecto-Final-Desarrollo
==========================

Este proyecto es una aplicacion veterinaria hecha con Spring Boot y Thymeleaf.
Usa Oracle como base de datos principal, RabbitMQ para mensajeria, Redis para cache y Apache HTTP Server como proxy para entrar a la aplicacion por el puerto 8090.

1. Programas necesarios
-----------------------

El usuario debe tener instalado manualmente:

- Java JDK 21 o compatible con el proyecto.
- Maven, si quiere levantar el proyecto desde CMD usando mvn spring-boot:run.
- Spring Tools Suite, si quiere abrir y ejecutar el proyecto desde interfaz grafica.
- Oracle Database XE 21c.

La aplicacion esta configurada para conectarse a Oracle en:

jdbc:oracle:thin:@//localhost:1521/xepdb1

Usuario de base de datos usado por el proyecto:

Veterinaria

Antes de usar la aplicacion, Oracle debe estar abierto/levantado y la base debe tener ejecutados los scripts SQL del proyecto.
El archivo .bat NO instala Oracle automaticamente. Oracle se debe instalar aparte porque requiere configuracion propia de base de datos, usuario, contrasena y scripts.

2. Archivo .bat incluido en el proyecto
---------------------------------------

El proyecto ya incluye el archivo para facilitar el arranque:

VeterinariaSpring\verificar-y-levantar.cmd

Y tambien incluye su script interno en:

VeterinariaSpring\scripts\verificar-y-levantar.ps1

Por eso, despues de clonar o copiar el proyecto, el usuario puede entrar directamente a la carpeta VeterinariaSpring y ejecutar el .bat.

3. Que descarga automaticamente el .bat
---------------------------------------

El archivo verificar-y-levantar.cmd llama a un script PowerShell que revisa si existen las herramientas necesarias dentro de la carpeta del proyecto o junto a ella.
Si no existen, las descarga automaticamente.

Descarga y prepara:

- Redis 5.0.14.1
- RabbitMQ Server 4.3.1
- Erlang/OTP 27.3.4.11, necesario para RabbitMQ
- Apache HTTP Server 2.4.67

Tambien verifica:

- Java disponible en PATH
- Maven disponible en PATH
- Oracle escuchando en el puerto 1521

4. Que levanta automaticamente
------------------------------

El .bat levanta estos servicios/procesos:

- Redis en el puerto 6379
- RabbitMQ en el puerto 5672
- Panel de RabbitMQ en el puerto 15672
- Spring Boot en el puerto 8080, si Maven esta disponible en PATH
- Apache como proxy en el puerto 8090

Si Maven no esta en PATH, el script puede levantar Redis, RabbitMQ y Apache, pero Spring Boot se debe ejecutar desde Spring Tools dando Run al archivo principal:

src/main/java/com/granmalo/veterinaria/VeterinariaApplication.java

5. Como levantar todo con el .bat
---------------------------------

Forma recomendada si el .bat ya esta dentro del proyecto:

cd /d "C:\Ruta\Del\Proyecto\VeterinariaSpring"
verificar-y-levantar.cmd

Ejemplo generico:

cd /d "C:\Proyectos\Proyecto-Final-Desarrollo\VeterinariaSpring"
verificar-y-levantar.cmd

Tambien se puede ejecutar desde otra ubicacion indicando la ruta de VeterinariaSpring:

cd /d "C:\Ruta\Donde\Esta\El\Bat"
verificar-y-levantar.cmd "C:\Proyectos\Proyecto-Final-Desarrollo\VeterinariaSpring"

6. URLs de acceso
-----------------

Aplicacion usando Apache:

http://127.0.0.1:8090/login

Aplicacion directa de Spring Boot:

http://127.0.0.1:8080/login

Panel de RabbitMQ:

http://127.0.0.1:15672

Usuario RabbitMQ:

guest

Contrasena RabbitMQ:

guest

7. Contrasenas de la aplicacion
-------------------------------

Cajero: 2569d
Veterinario: 4723c
Cirujano: 5937b
Admin: 1234a

8. Como bajar los procesos del proyecto
---------------------------------------

Si se quiere cerrar todo lo que levanto el proyecto, abrir CMD o PowerShell como usuario normal y ejecutar:

taskkill /F /IM java.exe
taskkill /F /IM redis-server.exe
taskkill /F /IM erl.exe
taskkill /F /IM epmd.exe
taskkill /F /IM httpd.exe

Si alguno dice que no existe el proceso, no pasa nada: significa que ese servicio ya estaba apagado.

9. Notas importantes
--------------------

- Oracle debe estar instalado y corriendo antes de usar pantallas que consultan datos.
- Redis y RabbitMQ pueden ser descargados y levantados por el .bat.
- Apache funciona como entrada por el puerto 8090 y redirige hacia Spring Boot en el puerto 8080.
- Si el puerto 6379, 5672, 8080 o 8090 ya esta ocupado, hay que cerrar el proceso que lo ocupa antes de volver a ejecutar el .bat.
