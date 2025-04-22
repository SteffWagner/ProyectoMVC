
# 📊 ProyectoMVC — Sistema de Administración de Proyectos

¡Bienvenid@! Este es un sistema web diseñado para la gestión de proyectos, tareas, recursos y presupuestos, desarrollado como parte del curso de Implementación y Mantenimiento de Software 🛠️.

El sistema fue creado 100% con ❤️ por **Estefania Wagner** & **Allan Umaña**.

---

## 🚀 Tecnologías utilizadas

- **Frontend:** HTML, CSS, JavaScript
- **Backend:** PHP
- **Base de datos:** MySQL
- **Servidor local:** AMPPS
- **Gestión de versiones:** Git + GitHub
- **Control de dependencias:** Composer (solo en entorno local)
- **Diseño gráfico personalizado:** Fondos e ilustraciones originales propias en formato SVG ✨

---

## 📚 Funcionalidades principales

- ✅ Crear y editar proyectos
- ✅ Gestionar recursos (humanos, técnicos, financieros)
- ✅ Asignar tareas y responsables
- ✅ Controlar presupuestos y gastos
- ✅ Generar reportes visuales e imprimibles
- ✅ Módulo de seguridad y mantenimiento
- ✅ Sistema de login con roles (admin y colaborador)

---

## 🛠️ Instalación y Ejecución Local con AMPPS

### 🔽 Requisitos previos
- Tener [AMPPS](https://www.ampps.com/) instalado
- Tener un navegador y un editor como VS Code

### 📁 Clonar el repositorio
```bash
git clone https://github.com/SteffWagner/ProyectoMVC.git
```

### 📂 Mover a carpeta www de AMPPS
En macOS:
```
/Applications/AMPPS/www/
```
En Windows:
```
C:/Program Files (x86)/Ampps/www/
```

### 🧱 Importar la base de datos
1. Abrir `phpMyAdmin`
2. Crear una nueva base de datos llamada `proyecto_mvc`
3. Importar `mydb150.sql` desde el repositorio

### ⚙️ Configurar conexión
Editar `config/Test_conexion.php` con:
```php
$host = "localhost";
$user = "root";
$pass = "";
$db = "proyecto_mvc";
```

### 🚀 Ejecutar el sistema
Ingresar en navegador a:
```
http://localhost/ProyectoMVC/
```

---

## 🧪 Capturas del sistema

> [Agregar capturas aquí en GitHub o link a carpeta]

---

## 👥 Autores

- **Estefania Wagner** – GitHub: [@SteffWagner](https://github.com/SteffWagner)
- **Allan Umaña** – GitHub: [@AJedoc](https://github.com/AJedoc)

---

## 📄 Licencia

Este proyecto está licenciado bajo la **Licencia MIT**.  
Consultá el archivo [LICENSE](LICENSE) para más información.

---

## 📌 Notas finales

Este proyecto fue realizado con esfuerzo, persistencia y mucho amor por el desarrollo de software.  
¡Gracias por visitar y probar nuestro trabajo! 💪🌟
