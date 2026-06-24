# Nexo / Nexalvia

Sistema web para gestionar cotizaciones, propuestas, cuentas de cobro y facturas generadas por una persona o equipo hacia empresas cliente.

## Estado del proyecto

El proyecto se encuentra en fase inicial de especificación y definición técnica.

Documentación base:

- `docs/spec.es.md`
- `docs/user-stories.es.md`
- `docs/development-rules.es.md`

## Stack técnico

- PHP 8.3+
- Laravel 13
- MySQL
- Blade
- Livewire
- Flux
- Tailwind CSS
- Laravel Fortify
- PHPUnit
- Laravel Pint

## Módulos previstos

- Comercial
  - Cotizaciones
  - Propuestas
  - Cuentas de cobro
  - Facturas
- Personal
  - Perfil
  - Datos personales
  - Documentos adjuntos
- Documentos
  - Plantillas
  - Repositorio
  - Documentos requeridos
- Configuración
  - Empresas
  - Monedas
  - Bancos
  - Cuentas bancarias
  - Consecutivos
- Integraciones
  - GitHub
  - ClickUp

## Reglas base de arquitectura

El proyecto usa contexto de equipos. Las entidades funcionales deben usar:

```text
team_id
created_by
```

Flujo recomendado:

```text
Livewire Component o Controller
→ Validation / Form Request
→ Service
→ Policy
→ Model
```

## Flujo de trabajo

Formato de historias:

```text
NX-{MODULO}-{NUMERO}
```

Ejemplos:

```text
NX-CONF-001
NX-COM-001
NX-DOC-001
```

Formato de ramas:

```text
feature/NX-CONF-001-company-crud
docs/NX-BASE-001-specifications
fix/NX-CONF-002-consecutive-validation
```

## Instalación local

Pendiente de validar según entorno final.

Comandos base sugeridos:

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
npm install
npm run dev
php artisan serve
```

## Notas para desarrollo con Codex

Antes de generar código, leer:

1. `docs/spec.es.md`
2. `docs/user-stories.es.md`
3. `docs/development-rules.es.md`

No crear funcionalidades sin historia asociada y sin criterios de aceptación.
