# Estrategia de Pruebas de Nexo

## Principios de Prueba

- Usar TDD para reglas de dominio y flujos críticos.
- Los feature tests deben cubrir flujos visibles para el usuario.
- Los unit tests deben cubrir cálculos puros de dominio y servicios de validación.
- Las pruebas de seguridad y autorización son obligatorias para acceso a documentos.
- Todo cambio debe probarse programáticamente.

## Tipos de Prueba

- Unit tests.
- Feature tests.
- Integration tests.
- Architecture tests.
- Domain tests.
- Application use case tests.
- Security tests.
- File upload tests.
- PDF generation tests.
- PDF merge tests.
- Compliance validation tests.
- Sequence generation tests.
- Financial calculation tests.
- Authorization tests.
- Regression tests.

## Escenarios Prioritarios

### Compliance

Given una empresa cliente exige RUT, certificado de seguridad social y orden de compra,
When se valida una cuenta de cobro sin un documento obligatorio,
Then el estado de cumplimiento es `MissingRequiredDocuments`.

### Radicación de PaymentClaim

Given una cuenta de cobro tiene requisitos bloqueantes pendientes,
When el usuario intenta radicarla,
Then el sistema bloquea la radicación y muestra las razones.

### Descargas Seguras

Given un documento pertenece a otro usuario,
When el usuario autenticado solicita la descarga,
Then el sistema niega el acceso.

### Cálculo Financiero

Given items de servicio con impuestos, descuentos y retenciones,
When se calculan los totales,
Then subtotal, impuestos, descuentos, retenciones y total final son correctos.

### Generación de Consecutivos

Given existe un contador para tipo documental y año,
When se genera un nuevo número de documento,
Then el siguiente valor es único y queda registrado en historial.
