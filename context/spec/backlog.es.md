# Backlog de Nexo

## Épicas

| Epic | Objetivo | Prioridad | Complejidad |
| --- | --- | --- | --- |
| User Management | Configurar datos y preferencias del propietario. | Alta | Media |
| Client Companies | Gestionar empresas cliente y configuración documental. | Alta | Media |
| Services Catalog | Gestionar servicios, tarifas, impuestos y retenciones. | Alta | Media |
| Quotes | Crear y administrar cotizaciones. | Media | Media |
| Proposals | Crear y versionar propuestas comerciales. | Media | Media |
| Payment Claims | Generar y administrar cuentas de cobro. | Alta | Alta |
| Compliance Matrix | Configurar documentos requeridos por empresa cliente. | Alta | Alta |
| Uploaded Documents | Cargar, validar, aprobar y trazar documentos. | Alta | Alta |
| PDF Packages | Generar y combinar paquetes documentales finales. | Alta | Alta |
| Finance | Gestionar cuentas bancarias, métodos de pago, divisas y saldos. | Alta | Media |
| Sequences | Generar consecutivos internos y evitar duplicados. | Alta | Media |
| Audit Trail | Trazar eventos críticos de negocio y seguridad. | Alta | Media |
| API | Exponer recursos seleccionados de forma segura. | Baja | Alta |

## Historias Iniciales

### Configurar Requisitos Documentales

Como usuario,
quiero configurar documentos requeridos por empresa cliente,
para que cada cuenta de cobro cumpla las reglas de radicación del cliente.

Criterios de aceptación:

- Given existe una empresa cliente, when agrego una regla documental, then queda disponible para futuras cuentas de cobro.
- Given un requisito es obligatorio y bloqueante, when falta el documento, then se bloquea la radicación.

### Cargar Documento Requerido

Como usuario,
quiero cargar evidencia para un documento requerido,
para completar el cumplimiento documental de una cuenta de cobro.

Criterios de aceptación:

- Given una cuenta de cobro tiene requisitos pendientes, when cargo un archivo válido, then el requisito queda cumplido.
- Given un archivo tiene MIME type inválido, when lo cargo, then el sistema lo rechaza.

### Generar Paquete Documental

Como usuario,
quiero generar un paquete PDF final,
para radicar todos los documentos requeridos ante la empresa cliente.

Criterios de aceptación:

- Given todos los requisitos bloqueantes están cumplidos, when genero el paquete, then se crea un PDF versionado.
- Given falta un documento requerido, when genero el paquete, then la generación es rechazada.
