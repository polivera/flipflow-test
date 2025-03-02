## Github Link
https://github.com/polivera/flipflow-test

# Comandos
### Guardar producto
```shell
php artisan app:save-product-list --url=<url>
```
Ej: `php artisan app:save-product-list --url=https://www.carrefour.es/supermercado/congelados/cat21449123/c`

### Leer productos guardados
```shell
php artisan app:show-product-list --url=<url>
```
Ej: `php artisan app:show-product-list --url=https://www.carrefour.es/supermercado/congelados/cat21449123/c`

# Proceso y decisiones
Lo primero que intenté hacer es tener un comando que haga el fetch a la página de congelados y me devuelva el contenido, y hacerlo rápido todo en un mismo archivo para tener una idea de los problemas que me podía llegar a encontrar. Por ejemplo (y esto es lo que me encontré):
- Bloqueo de request por ser muy genérica. Lo solucione básicamente copiando las headers de mi browser al hacer la request.
- Bloqueo por no manejar cookies. Lo solucioné usando el cookie jar en guzzle.
  Viendo esto, y pensando que quizá iba a tener que usar librerías externas para crawling y scraping empecé a plantear la estructura del proyecto usando DDD para que después sea más fácil cambiar lógicas especificas por librerías.
  Trato de no usar librerías si no es necesario y para esto no use por 2 razones:
  1. Falta de conocimiento de este tipo de librerías
  2. Al ser un scope tan pequeño (1 sólo sitio) es algo manejable. Si fuesen más creo que es preferible usar una librería.
     Al llegar a la parte de hacer scraping me di cuenta que debería tener una solución por sitio por si quería agregar alguno sitio más si tenía tiempo, entonces decidí usar un factory con un config que, dependiendo el dominio, me entregue el scraper correspondiente.
     Al hacer la parte de guardar los productos en la base de datos decidí usar int para currency dado que SQLite no tiene decimal.

La función `mb_convert_encoding` está con un `@` dado que el usarla con `HTML-ENTITIES` está deprecado y tuve problemas de encoding al mostrar la data. Este problema se puede deber a que use `TEXT` en lugar de `BLOB` en la base de datos.

Al momento de generar la imagen de docker me arrepentí de no usar librerías ya que me surgió el problema `TLS fingerprinting` que no pasó con las pruebas locales.

# Limitaciones
1. Chequear si la página ya está en la db y si ya tiene la última version.
2. Lógica de reintento cuando falla la carga del sitio.
3. Si bien alcanza para los 5 productos, el crawler no trae todo el contenido.
4. Sólo funciona con `www.carrefour.es` dado que no tiene otro scraper configurado.
5. Si el fetch se hace sobre una url con path inválido, es decir https://www.carrefour.es/supermercado/congelados/idinvalido/c, el registro en la db se guarda igual, esto debería corregirse. Son registros inútiles en la DB
6. Se guarda todo el contenido y no la parte que interesa específicamente. Cambiar eso reduciría los costes de espacio.
7. No se usaron librerías para crawling y scraping como `spatie/crawler` y `Roach-PHP`.
8. Dockerfile no puede hacer fetch porque es bloqueada por cloudflare.

# Rediseño de solución
1. Leer las páginas para crawling desde una cola de mensajes.
2. Chequear policies para que no le hagan ban al crawler.
    1. Si las policies tienen un horario determinado se devuelve la url a la cola de mensajes.
3. Usar cola de mensajes una vez guardado el contenido de la página para ejecutar el proceso de scraping.
    1. Si hay que seguir urls el scraper puede insertar esas urls en la cola de mensajes del punto 1.
4. Usar object storage en lugar de la db para guardar el contenido de las páginas.
5. Paginado en el listado de productos

# Medidas contra Datadome o Cloudflare
Es la primera vez que me topo con esto, pero según estuve investigando hay algunas opciones
- Imitar las headers de un navegador real. ( no me salio en el contenedor :( )
- Habilitar cookies. ( esto si me sirvió en local )
- Utilizar varias IP y rotarlas.
- Hacer crawling en patrones variados, con delays variados, como si fuera un humano y no usando cada link en orden.

# Trabajo con punteros
Salvo punteros en Go, nunca trabajé a nivel profesional. En proyectos personales hace poco estuve trabajando en un proyecto de librerías en C. La primera para manejo básico de arenas y la segunda para abrir una ventana y poder interactuar con teclado, mouse y gamepad usando wayland en linux. Este es el repo: https://github.com/polivera/ortilib

#  Proyecto con implementación de protocolos, manipulación de paquetes o fingerprinting.
Nunca trabajé en este tipo de proyectos lamentablemente.
