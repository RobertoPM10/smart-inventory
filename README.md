# Smart Inventory & POS System (Clean Architecture + TDD)

Un sistema profesional de **Punto de Venta e Inventario** desarrollado con **Laravel**, diseñado bajo los principios de **Clean Architecture**, **SOLID** y **Desarrollo Guiado por Pruebas (TDD)**. 

El proyecto expone una **API REST desacoplada** preparada para ser consumida por cualquier cliente (SPA, Mobile, Frontend independiente) e incluye un panel administrativo reactivo como cliente integrado.

---

## 🚀 Características Principales

* **Dominio Desacoplado:** La lógica de negocio vive independientemente de la base de datos y del framework.
* **Procesamiento de Ventas Atómico:** Transacciones ACID para garantizar consistencia de inventario y datos.
* **Cálculo Dinámico de Totales:** Soporte en el dominio para validación de stock, subtotales y motor de descuentos.
* **Manejo Global de Excepciones:** API estandarizada que responde con códigos de estado HTTP apropiados (`422 Unprocessable Entity`) y estructuras JSON limpias ante fallos de reglas de negocio.
* **Frontend Reactivo Integrado:** Interfaz moderna y ligera construida con **Tailwind CSS** y **Alpine.js** que consume la API REST en tiempo real.

---

## 🛠️ Stack Tecnológico & Arquitectura

* **Backend:** PHP 8.4+ / Laravel 13
* **Base de Datos:** SQLite (Entorno local y pruebas aisladas)
* **Frontend:** Blade, Tailwind CSS, Alpine.js
* **Testing:** PHPUnit / Pest (100% Test Coverage en reglas de negocio y endpoints)

### Estructura del Proyecto (Clean Architecture)

```text
app/
├── Domain/          # Core del Negocio (Entidades, Interfaces de Repositorio, Excepciones)
├── Infrastructure/  # Implementaciones Técnicas (Repositorios Eloquent, Persistencia DB)
├── Application/     # Casos de Uso (Flujos de trabajo y orquestación)
└── Http/            # Capa de Presentación (Controladores API/Web, Requests)