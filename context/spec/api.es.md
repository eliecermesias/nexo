# Especificación API de Nexo

## Propósito

La API expondrá recursos seleccionados de Nexo para futuras integraciones, dashboards, asistentes y flujos externos.

## Alcance Inicial

- Client companies.
- Services.
- Quotes.
- Proposals.
- Payment claims.
- Compliance requirements.
- Uploaded documents metadata.
- Document packages metadata.
- Bank accounts.
- Payment methods.
- Audit events.

## Principios API

- Usar rutas versionadas, por ejemplo `/api/v1`.
- Usar Laravel API Resources.
- Exigir autenticación y autorización en cada endpoint.
- Validar owner scope en cada consulta.
- Nunca exponer rutas privadas de storage.
- Usar URLs temporales firmadas para descargas autorizadas.
- Aplicar rate limiting en endpoints sensibles.
- Retornar respuestas de error consistentes.

## Endpoints de Ejemplo

| Method | Path | Propósito |
| --- | --- | --- |
| GET | `/api/v1/client-companies` | Listar empresas cliente. |
| POST | `/api/v1/client-companies` | Crear empresa cliente. |
| GET | `/api/v1/payment-claims/{paymentClaim}` | Consultar una cuenta de cobro. |
| POST | `/api/v1/payment-claims/{paymentClaim}/documents` | Cargar metadata y archivo de documento requerido. |
| POST | `/api/v1/payment-claims/{paymentClaim}/validate-compliance` | Validar estado de cumplimiento. |
| POST | `/api/v1/payment-claims/{paymentClaim}/document-packages` | Generar paquete documental. |

## Requisitos de Seguridad

- La API requiere autenticación.
- Las Policies deben autorizar cada recurso.
- La prevención de IDOR es obligatoria.
- Las descargas usan URLs temporales firmadas.
- Los errores de validación no deben filtrar detalles internos.
