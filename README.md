# Backend del proyecto

Al clonar el proyecto y con tu terminar navega hacia la carpeta que debio generarte
```bash
cd ./x-project-backend
```

Luego ejecuta esta serie de comandos
```bash
# Instalamos las dependencias
composer install

# Generamos el Key de Laravel
php .\artisan key:generate
```

Ya con eso podemos encender el servidor de desarrollo
```bash
php .\artisan serve
```
# ENDPOINTS

# ENDPOINTS MODULO 1: 

dirección: [ADMIN] /api/inventario 

request body: product, categoría, tipo, precio unit, stock, foto 

método: POST  

dependiente:  

Ninguno  

validaciones:   

Debe haber productos para mostrar. 

No puede bajar de un mínimo de cantidad de producto (depende del tipo de producto). 

  

Le permite al administrador ver el inventario de producto.  

----------------------------------------------------------------------------------------------  

dirección: [ADMIN] /api/inventario/agregar-producto 

request body: nombre, categoría, descripción, foto 

método: GET 

dependiente:  

Ninguno  

validaciones:   

Debe validar que no haya un producto igual al que se va a agregar. 

Requiere asegurar que tenga nombre, categoría y foto. 

  

Le permite al administrador agregar un nuevo producto.  

---------------------------------------------------------------------------------------------- 

dirección: [ADMIN] /api/inventario/editar-producto/{id} 

request body: nombre, categoría, descripción, foto 

método: POST 

dependiente:  

Ninguno  

validaciones:   

El producto que se va a editar debe existir. 

  

Le permite al administrador editar los datos de un producto existente.  

---------------------------------------------------------------------------------------------- 

dirección: [ADMIN] /api/inventario/agregar-tipo 

request body: producto, tipo, precio unit, cantidad x caja, foto 

método: GET  

dependiente:  

Ninguno  

validaciones:   

Debe validar que no haya un tipo de producto igual al que se va a agregar. 

Requiere asegurar que tenga producto, tipo, precio unit, cantidad x caja, foto.  

Le permite al administrador agregar un tipo de producto nuevo. 

----------------------------------------------------------------------------------------------dirección: [ADMIN] /api/inventario/editar-tipo/{id} 

request body: producto, tipo, precio unit, cantidad x caja, foto 

método: GET  

dependiente:  

Ninguno  

validaciones:   

El producto que se va a editar debe existir.  

Le permite al administrador editar un tipo de producto existente.  

---------------------------------------------------------------------------------------------- 