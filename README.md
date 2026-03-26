# Sistema de Alquiler de Autos

Aplicacion desarrollada en Laravel 12 para administrar una empresa de alquiler de autos. El sistema incluye autenticacion, roles, gestion de autos, clientes, alquileres y pagos.

## Stack tecnico

- PHP 8.2
- Laravel 12
- MySQL
- Blade
- Eloquent ORM

## Configuracion de base de datos

El proyecto esta configurado para MySQL desde [`.env`](./.env):

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=car_rental
DB_USERNAME=root
DB_PASSWORD=saenz0909.
```

## Ejecucion

1. Instalar dependencias:

```bash
composer install
```

2. Ejecutar migraciones y seeders:

```bash
php artisan migrate --seed
```

3. Iniciar el servidor:

```bash
php artisan serve
```

## Usuarios creados por seeder

- `admin@carrental.test` / `password`
- `empleado@carrental.test` / `password`

## Estructura del codigo

### Modelos

Los modelos estan en [`app/Models`](./app/Models):

- `User.php`: usuario autenticable y relacion con rol.
- `Role.php`: roles del sistema (`admin`, `empleado`).
- `Auto.php`: flota de vehiculos.
- `Cliente.php`: clientes del negocio.
- `Alquiler.php`: relacion entre cliente y auto.
- `Pago.php`: pagos asociados a un alquiler.

### Controladores

Los controladores estan en [`app/Http/Controllers`](./app/Http/Controllers):

- `AuthController.php`: login y logout.
- `AutoController.php`: CRUD de autos.
- `ClienteController.php`: CRUD de clientes.
- `AlquilerController.php`: CRUD de alquileres y control del estado del auto.
- `PagoController.php`: CRUD de pagos y validacion de saldo pendiente.

### Middleware

- [`RoleMiddleware.php`](./app/Http/Middleware/RoleMiddleware.php): restringe rutas por rol.

### Rutas

- [`routes/web.php`](./routes/web.php): autenticacion, panel principal y modulos web.
- [`routes/api.php`](./routes/api.php): endpoints basicos de consulta.

### Vistas

Las vistas estan en [`resources/views`](./resources/views):

- `layouts/app.blade.php`: layout principal.
- `inicio.blade.php`: panel principal.
- `auth/login.blade.php`: formulario de acceso.
- `autos/*`, `clientes/*`, `alquileres/*`, `pagos/*`: CRUD de cada modulo.

## Relaciones del dominio

- Un `Role` tiene muchos `User`.
- Un `User` pertenece a un `Role`.
- Un `Cliente` tiene muchos `Alquiler`.
- Un `Auto` tiene muchos `Alquiler`.
- Un `Alquiler` pertenece a un `Cliente`.
- Un `Alquiler` pertenece a un `Auto`.
- Un `Alquiler` tiene muchos `Pago`.
- Un `Pago` pertenece a un `Alquiler`.

## Reglas de negocio

- Un auto puede estar `disponible`, `alquilado` o `mantenimiento`.
- Un alquiler puede estar `activo`, `finalizado` o `cancelado`.
- Un pago puede estar `pendiente` o `pagado`.
- El estado `alquilado` del auto se controla desde alquileres.
- No se permite asignar un auto no disponible a un alquiler activo.
- No se permite registrar pagos mayores al saldo pendiente.

## Archivos importantes

- [`config/car_rental.php`](./config/car_rental.php): estados, roles y metodos de pago.
- [`bootstrap/app.php`](./bootstrap/app.php): alias del middleware `role`.
- [`database/seeders/DatabaseSeeder.php`](./database/seeders/DatabaseSeeder.php): crea usuarios base.
- [`database/seeders/RoleSeeder.php`](./database/seeders/RoleSeeder.php): crea roles.

## Flujo general

1. El usuario inicia sesion en `AuthController`.
2. Entra al panel principal con estadisticas y alquileres recientes.
3. El modulo de autos administra la flota.
4. El modulo de clientes administra datos de clientes.
5. El modulo de alquileres conecta cliente y auto, calcula total estimado y actualiza estados.
6. El modulo de pagos registra cobros y controla el saldo del alquiler.

## Mantenimiento

Si cambias configuracion o vistas:

```bash
php artisan optimize:clear
```

Si agregas nuevas migraciones:

```bash
php artisan migrate
```
