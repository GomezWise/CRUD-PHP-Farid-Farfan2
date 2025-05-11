# Plataforma Básica de Gestión de Tareas Colaborativa

Este proyecto consiste en una plataforma web que permite a múltiples usuarios crear y gestionar tareas, colaborar en tiempo real y registrar cambios de manera histórica.

---

## 🚀 Tecnologías utilizadas

### Backend:
- PHP
- MySQL (Base de datos relacional)
- WebSockets (implementación sugerida para notificaciones en tiempo real)
- Apache (Servidor local con XAMPP)

### Frontend:
- HTML, CSS y JavaScript
- Bootstrap 5 (para diseño responsivo)
- Axios (para consumir endpoints REST)
- Socket.IO (propuesta para comunicación en tiempo real)

---

## 📦 Instalación

1. Clona este repositorio:
```bash
git clone https://github.com/GomezWise/CRUD-PHP-Farid-Farfan2.git
```

2. Configura tu entorno local:
   - Importa el archivo `.sql` de la base de datos en MySQL.
   - Configura el archivo `conexion.php` con tus credenciales.

3. Ejecuta el servidor local:
   - Si usas XAMPP, coloca el proyecto en la carpeta `htdocs`.
   - Asegúrate de que Apache y MySQL estén activos.

4. Abre en el navegador:
```
http://localhost/CRUD-PHP-Farid-Farfan2/login.php
```

---

## 📂 Estructura del Proyecto

- `/controllers/`: Lógica del backend (login, tareas, usuarios).
- `/models/`: Conexión a base de datos.
- `/views/`: Archivos HTML y formularios.
- `/api/`: Endpoints RESTful para interacción con frontend.

---

## 📌 Funcionalidades

### ✅ Básicas
- Registro e inicio de sesión de usuarios.
- Crear, editar, eliminar y visualizar tareas propias.
- Compartir tareas con otros usuarios.
- Historial de modificaciones por tarea.

### 🔁 Tiempo real (propuesta implementada parcialmente)
- Uso de Socket.IO (prototipo) para emitir notificaciones cuando se actualiza una tarea compartida.

---

## 📡 API - Endpoints Disponibles

### 🔐 Autenticación
- `POST /api/login`
  - Parámetros: `correo`, `contrasena`

### 👤 Usuarios
- `POST /api/registro` *(propuesta si se desea añadir registro)*

### 📝 Tareas
- `POST /api/tareas/crear`  
  - Crea nueva tarea.

- `GET /api/tareas?id_usuario={id}`  
  - Lista todas las tareas del usuario.

- `GET /api/tareas/{id}`  
  - Obtiene información específica de una tarea.

- `PUT /api/tareas/actualizar/{id}`  
  - Edita una tarea.

- `DELETE /api/tareas/eliminar/{id}` *(opcional si decides agregarlo)*

- `GET /api/tareas/compartidas?id_usuario={id}`  
  - Lista tareas compartidas.

---

## 🗃️ Base de Datos

Tablas principales:
- `usuarios`
- `tareas`
- `tareas_compartidas`
- `historial_modificaciones`

Relaciones:
- Un usuario puede tener muchas tareas.
- Una tarea puede estar compartida con muchos usuarios.
- Cada tarea tiene un historial de modificaciones.

---


## 🌐 Demo en Vivo

*En desarrollo o pendiente de despliegue en Heroku/Vercel.*

---

## 📝 Retos Encontrados

- Implementar comunicación en tiempo real.
- Sincronización entre tareas propias y compartidas de forma efectiva.
- Documentar y estructurar código en PHP puro sin MVC.

---

## 📖 Decisiones Técnicas

- Se usó PHP puro para mostrar dominio de lógica backend.
- MySQL por su compatibilidad y facilidad para relaciones complejas.
- Bootstrap para una implementación rápida del diseño.
- Socket.IO y Node.js en prototipo separado para notificaciones en tiempo real.

---

## 🧑‍💻 Autor

**Farid Farfan**  
Desarrollador Junior Web 
