# 📊 ProyectoMVC — Project Management System

Welcome! This is a web-based system designed to manage projects, tasks, resources, and budgets, developed as part of the Software Implementation and Maintenance course 🛠️.

The system was built with ❤️ by **Estefania Wagner** & **Allan Umaña**.

---

## 🚀 **Technologies Used**

- **Frontend:** HTML, CSS, JavaScript   
- **Backend:** PHP
- **Database:** MySQL
- **Version Control:** Git + GitHub
- **Local Server:** AMPPS
- **Custom Visual Design:** All illustrations and background visuals were custom-designed by us ✨ using SVG format, ensuring both creativity and performance.

---
 
## 📚 **Key Features**

 - ✅ Create and manage projects
 - ✅ Manage resources (human, technical, financial, etc)
 - ✅ Assign tasks and responsibilities
 - ✅ Budget and expense tracking
 - ✅ Report generation
 - ✅ Security and maintenance modules
 - ✅ User login with role-based access (admin, collaborator)

 ---


## 🛠️ Installation & Local Execution with AMPPS

### 🔽 Requirements
- [AMPPS](https://www.ampps.com/) installed
- A web browser and text editor (VS Code recommended)

### 📁 Clone the repository
```bash
git clone https://github.com/SteffWagner/ProyectoMVC.git
```

### 📂 Move to AMPPS www folder
On macOS:
```
/Applications/AMPPS/www/
```
On Windows:
```
C:/Program Files (x86)/Ampps/www/
```

### 🧱 Import the database
1. Open `phpMyAdmin`
2. Create a new database named `ProyectoMVC`
3. Import `mydb150.sql` from the repository

### ⚙️ Configure database connection
Edit the file `config/Test_conexion.php`:
```php
$host = "localhost";
$user = "root";
$pass = "mysql";
$db = "mybd";
```

### 🚀 Run the system
Open your browser and go to:
```
http://localhost/ProyectoMVC/ 
``` 
or

``` 
http://localhost:8080/ProyectoMVC/
```


---

## 🐙 **Contributions**

Suggestions and improvements are always welcome via issues or pull requests!

---

## 👩🏼🧑🏻 **Authors**

- **Estefania Wagner** – GitHub: [@SteffWagner](https://github.com/SteffWagner)
- **Allan Umaña** – GitHub: [@AJedoc](https://github.com/AJedoc)

This project was built with love, dedication, and relentless persistence.
                                                                                                                                                                                    -🎯 "We won’t stop until everything is perfect."
                                                                                                                            
---                                                                                                                           
## 📝 **License**

This project is licensed under the MIT License.
See the [LICENSE](LICENSE) file for more information

---

## 💡 **Notes**

All illustrations and background visuals used in the system were custom-designed by us using SVG format, ensuring both creativity and performance.

This project was built with effort, persistence, and a lot of love for software development.
Thank you for visiting and trying out our work!  💪🌟
