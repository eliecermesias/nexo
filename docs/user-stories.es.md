# Nexo / Nexalvia - Historias de usuario iniciales

**Código base:** NX-BASE-001  
**Versión:** 0.1  
**Estado:** Borrador inicial versionado  

---

## Convención de códigos

Formato:

```text
NX-{MODULO}-{NUMERO}
```

Módulos sugeridos:

- `BASE`: Base del proyecto
- `PER`: Personal
- `CONF`: Configuración
- `COM`: Comercial
- `DOC`: Documentos
- `INT`: Integraciones
- `SEC`: Seguridad

---

# Épica 1: Base del proyecto

## NX-BASE-001 - Documentar especificación inicial

Como desarrollador del proyecto, quiero tener la especificación funcional y técnica versionada en GitHub para orientar el desarrollo guiado por especificaciones.

### Criterios de aceptación

- Existe el archivo `docs/spec.es.md`.
- Existe el archivo `docs/user-stories.es.md`.
- Existe el archivo `docs/development-rules.es.md`.
- La documentación describe módulos, entidades, reglas de negocio y criterios técnicos.
- La documentación puede ser usada como entrada para Codex, GitHub Issues y ClickUp.

---

## NX-BASE-002 - Definir propiedad de datos por equipo

Como arquitecto del sistema, quiero que las entidades principales pertenezcan a un equipo para proteger los datos y habilitar colaboración futura.

### Criterios de aceptación

- Las entidades principales incluyen `team_id`.
- Las entidades principales incluyen `created_by`.
- Las consultas se filtran por equipo activo.
- Las rutas funcionales usan `{current_team}`.
- Las policies validan pertenencia al equipo.

---

## NX-BASE-003 - Crear README inicial

Como desarrollador, quiero un README claro para documentar propósito, stack, instalación y flujo de trabajo del proyecto.

### Criterios de aceptación

- Existe `README.md`.
- Describe el objetivo del proyecto.
- Describe el stack técnico.
- Incluye comandos básicos de instalación y ejecución.
- Incluye flujo de ramas y trazabilidad.

---

# Épica 2: Personal

## NX-PER-001 - Gestionar perfil del usuario

Como usuario autenticado, quiero administrar mis datos personales y profesionales para que sean usados en los documentos generados.

### Criterios de aceptación

- El usuario puede registrar identificación, nombre completo, teléfono, dirección, ciudad y país.
- El usuario puede registrar información tributaria básica.
- El usuario puede cargar logo o firma.
- Los datos pertenecen al equipo activo o al usuario según la decisión arquitectónica.
- El usuario no puede acceder a perfiles de equipos a los que no pertenece.

---

# Épica 3: Configuración

## NX-CONF-001 - Gestionar empresas

Como usuario autenticado, quiero administrar empresas cliente para asociarlas a cotizaciones, propuestas, cuentas de cobro y facturas.

### Criterios de aceptación

- El usuario puede crear una empresa.
- El usuario puede listar empresas del equipo activo.
- El usuario puede editar una empresa mediante modal.
- El usuario puede ver el detalle mediante modal.
- El usuario puede eliminar o inactivar una empresa mediante confirmación.
- El listado se muestra en tabla dinámica.
- Cada acción tiene icono y tooltip.
- El usuario no puede ver empresas de equipos a los que no pertenece.

---

## NX-CONF-002 - Gestionar monedas

Como usuario autenticado, quiero administrar monedas para definir los valores de servicios y documentos comerciales.

### Criterios de aceptación

- El usuario puede crear monedas.
- Cada moneda tiene código, nombre, símbolo y estado.
- El usuario puede editar monedas mediante modal.
- El usuario puede activar o inactivar monedas.
- Solo monedas activas aparecen al crear servicios o documentos.
- Las monedas se filtran por equipo activo.

---

## NX-CONF-003 - Gestionar bancos

Como usuario autenticado, quiero administrar bancos para registrar las entidades financieras donde recibiré pagos.

### Criterios de aceptación

- El usuario puede crear bancos.
- El usuario puede listar bancos en tabla dinámica.
- El usuario puede editar bancos mediante modal.
- El usuario puede activar o inactivar bancos.
- Los bancos pertenecen al equipo activo.

---

## NX-CONF-004 - Gestionar cuentas bancarias

Como usuario autenticado, quiero administrar mis cuentas bancarias para asociarlas como destino de pago en documentos generados.

### Criterios de aceptación

- El usuario puede crear cuentas bancarias.
- Cada cuenta bancaria pertenece a un banco.
- Cada cuenta bancaria tiene tipo, número, titular, identificación y moneda.
- El usuario puede marcar una cuenta como predeterminada.
- Solo puede existir una cuenta predeterminada por equipo.
- El usuario no puede ver cuentas bancarias de equipos a los que no pertenece.

---

## NX-CONF-005 - Gestionar servicios o ítems

Como usuario autenticado, quiero administrar servicios o ítems comerciales para reutilizarlos en documentos comerciales.

### Criterios de aceptación

- El usuario puede crear servicios.
- Cada servicio tiene nombre, descripción, valor, moneda y estado.
- El usuario puede editar servicios mediante modal.
- Solo servicios activos aparecen al crear documentos.
- Los servicios pertenecen al equipo activo.

---

## NX-CONF-006 - Gestionar consecutivos

Como usuario autenticado, quiero configurar consecutivos por tipo de documento para generar documentos con numeración única y controlada.

### Criterios de aceptación

- El usuario puede crear consecutivos por tipo de documento.
- El consecutivo incluye prefijo, año, longitud, número inicial, número final y número actual.
- El sistema genera números con ceros a la izquierda.
- El sistema impide repetir consecutivos activos por equipo y tipo de documento.
- El sistema impide generar documentos si el consecutivo llegó al número final.
- El consecutivo no se reutiliza aunque el documento sea anulado.

---

# Épica 4: Documentos

## NX-DOC-001 - Gestionar plantillas de documentos

Como usuario autenticado, quiero administrar plantillas para personalizar el formato de cotizaciones, propuestas, cuentas de cobro y facturas.

### Criterios de aceptación

- El usuario puede crear plantillas.
- Cada plantilla se asocia a un tipo de documento.
- El usuario puede definir una plantilla predeterminada.
- Solo puede existir una plantilla predeterminada activa por equipo y tipo de documento.
- La plantilla permite variables dinámicas.
- El usuario puede previsualizar la plantilla.

---

## NX-DOC-002 - Gestionar documentos requeridos por empresa

Como usuario autenticado, quiero configurar documentos requeridos por empresa para saber qué debo entregar con cada documento generado.

### Criterios de aceptación

- El usuario puede asociar documentos requeridos a una empresa.
- El usuario puede indicar si el documento es obligatorio.
- El usuario puede asociar el requerimiento a un tipo de documento.
- El sistema muestra documentos pendientes al generar documentos comerciales.

---

## NX-DOC-003 - Adjuntar documentos

Como usuario autenticado, quiero adjuntar documentos a documentos generados para conservar evidencias y requisitos entregados.

### Criterios de aceptación

- El usuario puede subir archivos.
- El archivo queda asociado al documento generado.
- El sistema registra nombre, tipo, tamaño y ruta.
- El sistema permite descargar o eliminar adjuntos.
- El usuario no puede acceder a adjuntos de equipos a los que no pertenece.

---

# Épica 5: Comercial

## NX-COM-001 - Crear cotizaciones

Como usuario autenticado, quiero crear cotizaciones para presentar valores de servicios a una empresa cliente.

### Criterios de aceptación

- El usuario selecciona una empresa.
- El usuario selecciona una moneda.
- El usuario agrega uno o varios ítems.
- El sistema calcula subtotal y total.
- El sistema asigna consecutivo al generar la cotización.
- La cotización inicia en estado borrador o generado.
- El usuario puede generar PDF.
- El usuario no puede ver cotizaciones de equipos a los que no pertenece.

---

## NX-COM-002 - Crear propuestas

Como usuario autenticado, quiero crear propuestas comerciales para presentar una oferta formal de servicios a una empresa.

### Criterios de aceptación

- El usuario selecciona empresa, plantilla, moneda e ítems.
- El sistema permite agregar texto descriptivo o alcance.
- El sistema asigna consecutivo al generar la propuesta.
- La propuesta puede pasar por estados generado, enviado, aprobado, rechazado o anulado.
- El usuario puede generar PDF.

---

## NX-COM-003 - Crear cuentas de cobro

Como usuario autenticado, quiero crear cuentas de cobro para solicitar formalmente el pago de servicios prestados.

### Criterios de aceptación

- El usuario selecciona empresa.
- El usuario selecciona cuenta bancaria de pago.
- El usuario agrega ítems cobrables.
- El sistema asigna consecutivo.
- El sistema permite adjuntar documentos requeridos.
- El sistema muestra documentos pendientes por entregar.
- La cuenta de cobro puede cambiar a estado pagado o anulado.

---

## NX-COM-004 - Crear facturas

Como usuario autenticado, quiero registrar facturas para controlar documentos de cobro emitidos a empresas cliente.

### Criterios de aceptación

- El usuario puede crear factura.
- La factura tiene empresa, fecha, ítems, total y estado.
- El sistema asigna consecutivo propio de factura.
- La factura puede pasar a enviada, pagada o anulada.
- El usuario puede generar PDF.

---

# Épica 6: Seguridad

## NX-SEC-001 - Restringir datos por equipo

Como usuario autenticado, quiero que el sistema solo muestre información del equipo activo para proteger la privacidad de los datos.

### Criterios de aceptación

- Todas las consultas filtran por `team_id`.
- Todas las creaciones asignan el equipo activo.
- Las ediciones validan pertenencia al equipo.
- Las eliminaciones validan pertenencia al equipo.
- El sistema responde 403 cuando un usuario intenta acceder a datos de otro equipo.

---

# Épica 7: Integraciones

## NX-INT-001 - Configurar integración con GitHub

Como usuario autenticado, quiero conectar GitHub para relacionar historias de usuario con issues, ramas y pull requests.

### Criterios de aceptación

- El usuario puede registrar o autorizar GitHub.
- El sistema almacena tokens de forma cifrada.
- El usuario puede asociar un repositorio.
- El sistema guarda referencia de issue, rama y pull request.
- El usuario puede desactivar la integración.

---

## NX-INT-002 - Configurar integración con ClickUp

Como usuario autenticado, quiero conectar ClickUp para enviar historias de usuario como tareas y controlar el avance del proyecto.

### Criterios de aceptación

- El usuario puede registrar o autorizar ClickUp.
- El sistema almacena tokens de forma cifrada.
- El usuario puede configurar workspace, space, folder o list.
- El sistema puede crear tareas desde historias de usuario.
- El sistema puede guardar el ID de tarea ClickUp.
- El sistema puede actualizar campos personalizados.
- El usuario puede desactivar la integración.
