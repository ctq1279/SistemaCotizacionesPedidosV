# 🧾 Sistema de Cotizaciones y Pedidos

Sistema web desarrollado en Laravel para la gestión integral de cotizaciones, ventas, productos, materiales, proveedores y usuarios, orientado a optimizar procesos comerciales y administrativos.

---

## 🚀 Características principales

* 📦 Gestión de productos y materiales
* 🧑‍💼 Administración de clientes y proveedores
* 🧾 Generación de cotizaciones con exportación a PDF
* 💰 Registro de pedidos y ventas
* 🔐 Sistema de usuarios, roles y permisos
* 📊 Base estructurada para reportes
* ✅ Validaciones mediante Form Requests
* 🧪 Pruebas unitarias y de integración

---

## 🏗️ Arquitectura del sistema

El proyecto sigue una arquitectura basada en MVC utilizando Laravel:

* **Models** → lógica de negocio y acceso a datos
* **Controllers** → manejo de flujo de la aplicación
* **Requests** → validación de datos
* **Views (Blade)** → interfaz de usuario
* **Migrations** → estructura de base de datos

---

## 🧩 Módulos del sistema

### 📁 Gestión de Clientes

* CRUD completo de clientes
* Relación con pedidos y cotizaciones

### 📦 Productos

* Registro y edición de productos
* Asociación con materiales
* Cálculo de costos

### 🧱 Materiales

* Gestión de insumos
* Relación con productos (tabla intermedia)

### 🧾 Cotizaciones

* Creación y edición de cotizaciones
* Cálculo de costos
* Generación de PDF

### 🛒 Pedidos / Ventas

* Registro de ventas
* Asociación con clientes

### 🚚 Proveedores

* Gestión de proveedores
* Relación con compras

### 👥 Usuarios y Roles

* Control de acceso basado en roles
* Gestión de permisos

---

## 🛠️ Tecnologías utilizadas

* PHP 8+
* Laravel
* MySQL
* Blade (templating engine)
* DOMPDF (generación de PDF)
* JavaScript / HTML / CSS

---

## ⚙️ Instalación

### 1. Clonar repositorio

```bash
git clone https://github.com/ctq1279/SistemaCotizacionesPedidosV.git
cd SistemaCotizacionesPedidosV
```

---

### 2. Instalar dependencias

```bash
composer install
npm install
```

---

### 3. Configurar entorno

```bash
cp .env.example .env
php artisan key:generate
```

Configura tu base de datos en el archivo `.env`

---

### 4. Migraciones y seeders

```bash
php artisan migrate --seed
```

---

### 5. Ejecutar servidor

```bash
php artisan serve
```

---

## 🧪 Pruebas

```bash
php artisan test
```

Incluye:

* pruebas unitarias
* pruebas de funcionalidades

---

## 📂 Estructura del proyecto

```
app/
 ├── Models/
 ├── Http/
 │   ├── Controllers/
 │   ├── Requests/
database/
 ├── migrations/
 ├── seeders/
resources/
 ├── views/
routes/
 ├── web.php
```

---

## 🔐 Seguridad

* Validaciones mediante Form Requests
* Control de acceso con roles y permisos
* Protección contra datos inválidos

---

## 📈 Estado del proyecto

🚧 En desarrollo activo

Módulos principales implementados y en mejora continua.

