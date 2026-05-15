# Especificación de Seguridad de Nexo

## Alcance OWASP

Nexo debe cubrir controles inspirados en OWASP Top 10 y OWASP ASVS para autenticación, sesiones, control de acceso, validación de entradas, output encoding, seguridad en carga de archivos, almacenamiento seguro, API, logging, manejo de errores, validación de lógica de negocio, protección de datos y configuración segura.

## Requisitos Core de Seguridad

1. Exigir validación estricta de ownership en cada registro.
2. Usar Laravel Policies para autorización.
3. Proteger contra IDOR validando owner scope antes de leer, escribir, descargar o generar PDFs.
4. Usar protección CSRF en formularios web.
5. Usar Eloquent y Query Builder de forma segura para prevenir SQL injection.
6. Validar y sanitizar entradas de usuario.
7. Guardar documentos cargados fuera del public path.
8. Usar discos privados y URLs temporales firmadas para descargas.
9. Registrar cargas, descargas, generación, combinación y eliminación de documentos.
10. Ejecutar validaciones de dependencias como `composer audit`.

## Seguridad en File Upload

- Validar extensión.
- Validar MIME type real.
- Validar tamaño.
- Renombrar archivos.
- Evitar ejecución de archivos cargados.
- Guardar archivos fuera del public path.
- Usar discos privados.
- Asociar archivos al owner y contexto de negocio.
- Autorizar descargas.
- Registrar cargas y descargas.
- Prevenir path traversal.
- Prevenir sobrescritura.
- Evitar exposición de rutas internas.

## Seguridad en PDF

- Sanitizar datos de plantilla antes de renderizar.
- Prevenir HTML injection en PDFs generados.
- Validar PDFs cargados.
- Proteger PDFs combinados con autorización.
- Guardar hash para integridad del paquete.
- Registrar usuario y fecha de generación.
- Versionar paquetes generados.

## Casos de Abuso

- Un usuario intenta descargar documentos de otro usuario.
- Un usuario cambia un ID en la URL para acceder a otra cuenta de cobro.
- Se carga un archivo malicioso como soporte documental.
- Una plantilla contiene HTML inseguro.
- Se reutiliza un documento requerido vencido.

## Casos de Prueba de Seguridad

- Usuarios no autorizados no acceden a otro owner scope.
- Archivos con MIME inválido son rechazados.
- Archivos demasiado grandes son rechazados.
- Archivos privados no se descargan sin autorización.
- Documentos obligatorios faltantes bloquean radicación cuando el requisito es bloqueante.
