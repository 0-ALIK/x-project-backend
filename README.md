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


# Modulo 3 - endpoints

## Login
### <span style="background-color:#67DA30; color: white; padding: 2px 5px; border-radius: 50px;">POST</span> api/login  
valida los campos del inicio de sesión y crea una sesión. 
#### Body data 
| Data | Validaciones |
| ------------ | ------------ |
| `correo` - *obligatorio* | no hay |
| `contraseña` - *obligatorio* | no hay |

#### Validaciones
|Validaciones|
|------------|
|Crear la sesion en base al usuario que este inciando sesion(ya sea admin ,empresa o empleado)|

#### Ejemplo de respuesta (application/json)
```
{id: “xd”} No sé qué va a haber en la sesión 
```

## Empresa

### <span style="background-color:#67DA30; color: white; padding: 2px 5px; border-radius: 50px;">POST</span> api/empresas  `AUTH ADMIN`
Registra a una empresa 


#### Body data (multipart/form-data)
| Data | Validaciones |
| ------------ | ------------ |
| `nombre` - *obligatorio* | Que sea unico |
| `RUC` - *obligatorio* | Que sea unico |
| `teléfono` - *obligatorio* | Solo numeros |
| `correo` - *obligatorio* | Que sea unico |
| `contraseña` - *obligatorio* | Aqui no hay ninguna |
| `documento` - *obligatorio* | Solo formato PDF y imagenes|

#### Validaciones
|Validaciones|
|------------|
|Validar que los campos no estén vacíos|
|Que no haya correo, ruc y teléfono repetidos|

#### Ejemplo de respuesta (application/json)
```
{ 
    id: ”…”, 
    nombre: “nombre de empresa”, 
    ruc: “…”, 
    correo: “…”, 
    telefono: “…”, 
    foto: “link de la foto”, 
    archivoRegistro: “link del archivo pdf”, 
    razonSocial: “...”, 
    detalles: “...”, 
} 
```

---
### <span style="background-color:#67DA30; color: white; padding: 2px 5px; border-radius: 50px;">GET</span> api/empresas `AUTH ADMIN`
devuelve todas las empresas 
#### Params
| Param | Descripcion|
| ------------ | ------------ |
| `límite`| cantidad de los clientes que va a traer (por defecto 10) |
| `página`| paginacion|
| `cliente`| busca la empresa a la cual pertenece un cliente con su id |
| `empresa`| busca por el nombre de la empresa – se ignora el params limite|
| `ruc`| busca por el ruc de la empresa – se ignora el params limite  |
| `correo`|  busca por el correo de la empresa – se ignora el params limite|
| `teléfono`| busca por el telefono de la empresa – se ignora el params limite|
| `pedidos`| filtra por la cantidad de pedidos de la empresa (mayor, menor)|

#### Validaciones
|Validaciones|
|------------|
|validar que tenga sesión abierta de admin |

#### Ejemplo de respuesta (application/json)
```
[ 
    { 
        Id: “...”, 
        nombre: “nombre de empresa”, 
        ruc: “.....”, 
        correo: “....”, 
        telefono: “....”, 
        Foto: “link de la foto”, 
        archivoRegistro:  “link del archivo pdf”, 
        TotalPedidos: 69 
    }, 
    {aquí la otra empresa},  
    {aquí la otra empresa},  
    ... 
] 
```
---
### <span style="background-color:#67DA30; color: white; padding: 2px 5px; border-radius: 50px;">GET</span> api/empresas/{id} `AUTH ADMIN, EMPRESA, EMPLEADO`

devuelve todos los datos de una empresa 
#### Validaciones
|Validaciones|
|------------|
|validar que tenga sesión abierta  |

#### Ejemplo de respuesta (application/json)
```
{ 
    Id:”…” 
    nombre: “nombre de empresa”, 
    ruc: “…”, 
    correo: “…”, 
    telefono: “…”, 
    Foto: “link de la foto”, 
    archivoRegistro: “link del archivo pdf”, 
    RazonSocial: “...”, 
    Detalles: “...”, 
} 
```
---
### <span style="background-color:#67DA30; color: white; padding: 2px 5px; border-radius: 50px;">PUT</span> api/empresas/{id} `AUTH ADMIN, EMPRESA`
Edita la información de una empresa 

#### Body data 
| Data | Validaciones |
| ------------ | ------------ |
| `id` - *obligatorio* | Que sea unico |
| `nombre` - *obligatorio* | Que sea unico |
| `RUC` - *obligatorio* | Que sea unico |
| `teléfono` - *obligatorio* | Solo numeros |
| `correo` - *obligatorio* | Que sea unico |
| `contraseña` - *obligatorio* | Aqui no hay ninguna |
| `razonSocial` - *opcional* | no hay|

#### Validaciones
|Validaciones|
|------------|
|validar que los campos no estén vacíos |
|que no haya correo, ruc y teléfono repetidos |
|Tener sesión iniciada de empresa |
|Que el id de la sesión de la empresa corresponda al id de la empresa a editar|

#### Ejemplo de respuesta (application/json)
```
{ 
    Id:”…”, 
    nombre: “nombre de empresa”, 
    ruc: “…”, 
    correo: “…”, 
    telefono: “…”, 
    Foto: “link de la foto”, 
    archivoRegistro: “link del archivo pdf”, 
    RazonSocial: “...”, 
    Detalles: “...”
} 
```

---
### <span style="background-color:#67DA30; color: white; padding: 2px 5px; border-radius: 50px;">DELETE</span> api/empresas/{id} `AUTH ADMIN, EMPRESA`
Elimina una empresa 

#### Validaciones
|Validaciones|
|------------|
|Tener sesión iniciada de empresa o admin |
|Que el id de la sesión de la empresa corresponda al id de la empresa a eliminar |

#### Ejemplo de respuesta (application/json)
```
{ 
    Id:”…”, 
    nombre: “nombre de empresa”, 
    ruc: “…”, 
    correo: “…”, 
    telefono: “…”, 
    Foto: “link de la foto”, 
    archivoRegistro: “link del archivo pdf”, 
    RazonSocial: “...”, 
    Detalles: “...”
} 
```

---

## Clientes
### <span style="background-color:#67DA30; color: white; padding: 2px 5px; border-radius: 50px;">GET</span> api/clientes `AUTH ADMIN`
devuelve todos los clientes (empleado de las empresas) 
#### Params
| Param | Descripcion|
| ------------ | ------------ |
| `límite`| cantidad de los clientes que va a traer (por defecto 10) |
| `página`| paginacion|
| `cedula`| busca por la cedula del cliente – se ignora el params limite|
| `cliente`| busca por el nombre del cliente – se ignora el params limite |
| `sexo`|  filtra por sexo (M y F)|
| `correo`| busca por el correo del cliente – se ignora el params limite|
| `empresa`| busca por la empresa del cliente – se ignora el params limite|
| `pedidos`| filtra por la cantidad de pedidos del cliente (mayor, menor) |

#### Validaciones
|Validaciones|
|------------|
|Tener sesión iniciada de admin |

#### Ejemplo de respuesta (application/json)
```
[ 
    { 
    Id: “...”, 
    Nombre: “”, 
    Apellido: “”, 
    Cedula: “”, 
    genero: “”, 
    Telefono: “”, 
    Correo: ””, 
    Frecuencia: ??, 
    TotalPedidos: ?? ,
    Empresa:  { 
        Id:””, 
        Nombre: “” 
        }, 
    }, 
    { Informacion del cliente 2 }, 
    …. 
] 

```
---
### <span style="background-color:#67DA30; color: white; padding: 2px 5px; border-radius: 50px;">GET</span> api/clientes/{id} `AUTH ADMIN, EMPRESA, EMPLEADO`
devuelve todos los datos del cliente 
#### Validaciones
|Validaciones|
|------------|
|Tener sesión iniciada |

#### Ejemplo de respuesta (application/json)
```
{ 
    Id: “...”, 
    Nombre: “”, 
    Apellido: “”, 
    Cedula: “”, 
    genero: “”, 
    Telefono: “”, 
    Correo: ””,
    Foto: “link de la foto”,  
    Detalles: “”, 
    Direccion:[ 
        { 
            Provincia: “”, 
            Ciudad: “”, 
            Detalles: ‘” 
        }, 
        {direccion 2},
        ...
    ] 
} 

```

---
### <span style="background-color:#67DA30; color: white; padding: 2px 5px; border-radius: 50px;">POST</span> api/clientes/{id} `AUTH ADMIN, EMPRESA`
Registra a un cliente a la empresa que lo está registrando. 

#### Body data (multipart/form-data)
| Data | Validaciones |
| ------------ | ------------ |
| `nombre` - *obligatorio* | Aqui no hay ninguna |
| `apellido` - *obligatorio* | Aqui no hay ninguna |
| `cedula` - *obligatorio* | Que sea unico |
| `detalles` | Aqui no hay ninguna |
| `provincia` - *obligatorio* | que exista en la bd |
| `ciudad` - *obligatorio* | que exista en la bd |
| `direccion` - *obligatorio* | Aqui no hay ninguna |
| `genero` - *obligatorio* | Solo M o F |
| `teléfono` - *obligatorio* | Solo numeros |
| `correo` - *obligatorio* | Que sea unico |
| `contraseña` - *obligatorio* | Aqui no hay ninguna |
| `foto` | Solo formato imagenes|

#### Validaciones
|Validaciones|
|------------|
|Tener sesión iniciada de empresa |
|validar que los campos no estén vacíos, |
|que no haya correo, cedula y teléfonos repetidos |

#### Ejemplo de respuesta (application/json)
```
{ 
    Id: “...”, 
    Nombre: “”, 
    Apellido: “”, 
    Cedula: “”, 
    genero: “”, 
    Telefono: “”, 
    Correo: ””, 
    Foto: “link de la foto”,
    Detalles: “”, 
    Empresa: { 
        Id:” …”, 
        nombre: “”, 
        ruc: “…”, 
        correo: “…”, 
        telefono: “…”, 
        Foto: “link de la foto”, 
        archivoRegistro: “link del archivo pdf”, 
        RazonSocial: “...”, 
        Detalles: “...”, 
    }, 
    Direccion:[ 
        { 
            Provincia: “”, 
            Ciudad: “”, 
            Detalles: ‘” 
        }, 
        {direccion 2},
        ...
    ] 
} 

```
---
### <span style="background-color:#67DA30; color: white; padding: 2px 5px; border-radius: 50px;">PUT</span> api/clientes/{id} `AUTH ADMIN, EMPRESA, EMPLEADO`

Edita la información de un cliente 
#### Body data (multipart/form-data)
| Data | Validaciones |
| ------------ | ------------ |
| `nombre` - *obligatorio* | Aqui no hay ninguna |
| `apellido` - *obligatorio* | Aqui no hay ninguna |
| `cedula` - *obligatorio* | Que sea unico |
| `detalles` | Aqui no hay ninguna |
| `provincia` - *obligatorio* | que exista en la bd |
| `ciudad` - *obligatorio* | que exista en la bd |
| `direccion` - *obligatorio* | Aqui no hay ninguna |
| `genero` - *obligatorio* | Solo M o F |
| `teléfono` - *obligatorio* | Solo numeros |
| `correo` - *obligatorio* | Que sea unico |
| `contraseña` - *obligatorio* | Aqui no hay ninguna |
| `foto` | Solo formato imagenes|

#### Validaciones
|Validaciones|
|------------|
|Tener sesión iniciada de empresa |
|validar que los campos no estén vacíos, |
|que no haya correo, cedula y teléfonos repetidos |
|Que la sesion del cliente corresponda al cliente que va a editar o que corresponda a la empresa del cliente. |

#### Ejemplo de respuesta (application/json)
```
{ 
    Id: “...”, 
    Nombre: “”, 
    Apellido: “”, 
    Cedula: “”, 
    genero: “”, 
    Telefono: “”, 
    Correo: ””, 
    Foto: “link de la foto”,
    Detalles: “”, 
    Empresa: { 
        Id:” …”, 
        nombre: “”, 
        ruc: “…”, 
        correo: “…”, 
        telefono: “…”, 
        Foto: “link de la foto”, 
        archivoRegistro: “link del archivo pdf”, 
        RazonSocial: “...”, 
        Detalles: “...”, 
    }, 
    Direccion:[ 
        { 
            Provincia: “”, 
            Ciudad: “”, 
            Detalles: ‘” 
        }, 
        {direccion 2},
        ...
    ] 
} 

```
---
### <span style="background-color:#67DA30; color: white; padding: 2px 5px; border-radius: 50px;">DELETE</span> api/clientes/{id} `AUTH ADMIN, EMPRESA, EMPLEADO`
Elimina un cliente 

#### Validaciones
|Validaciones|
|------------|
|Tener sesión iniciada de empresa o de cliente|
|Que la sesión del cliente corresponda al cliente va a eliminar o que corresponda a la empresa del cliente. |

#### Ejemplo de respuesta (application/json)
```
{ 
    Id: “...”, 
    Nombre: “”, 
    Apellido: “”, 
    Cedula: “”, 
    genero: “”, 
    Telefono: “”, 
    Correo: ””, 
    Foto: “link de la foto”,
    Detalles: “”, 
    Empresa: { 
        Id:” …”, 
        nombre: “”, 
        ruc: “…”, 
        correo: “…”, 
        telefono: “…”, 
        Foto: “link de la foto”, 
        archivoRegistro: “link del archivo pdf”, 
        RazonSocial: “...”, 
        Detalles: “...”, 
    }, 
    Direccion:[ 
        { 
            Provincia: “”, 
            Ciudad: “”, 
            Detalles: ‘” 
        }, 
        {direccion 2},
        ...
    ] 
} 
```
---

## Solicitudes
### <span style="background-color:#67DA30; color: white; padding: 2px 5px; border-radius: 50px;">GET</span> api/solicitudes `AUTH ADMIN`

Muestra todas las solicitudes de aprobación de las empresas

#### Params
| Param | Descripcion|
| ------------ | ------------ |
| `límite`| cantidad de los clientes que va a traer (por defecto 10) |
| `página`| paginacion|
| `empresa`| busca por el nombre de la empresa – se ignora el params limite|

#### Validaciones
|Validaciones|
|------------|
|Tener sesión iniciada de admin|

#### Ejemplo de respuesta (application/json)
```
[ 
    { 
        Id:”…”, 
        nombre: “nombre de empresa”, 
        telefono: “…”, 
        Foto: “link de la foto”, 
        archivoRegistro: “link del archivo pdf”, 
    }, 
    { Solicitud 2 }, 
    ...
] 
```

---
### <span style="background-color:#67DA30; color: white; padding: 2px 5px; border-radius: 50px;">POST</span> api/solicitudes `AUTH ADMIN`

Cambia el estado del “estado” de la empresa 
#### Body data (multipart/form-data)
| Data | Validaciones |
| ------------ | ------------ |
| `id` - *obligatorio* | Aqui no hay ninguna |

#### Validaciones
|Validaciones|
|------------|
|Tener sesión iniciada de admin|

#### Ejemplo de respuesta (application/json)
```
no se :v
```
## Sucursal
### <span style="background-color:#67DA30; color: white; padding: 2px 5px; border-radius: 50px;">GET</span> api/sucursal `AUTH ADMIN, EMPRESA, EMPLEADO`
devuelve todas las sucursales
#### Params
| Param | Descripcion|
| ------------ | ------------ |
| `empresa`| devuelve todas las sucursales de una empresa|

#### Validaciones
|Validaciones|
|------------|
|Tener sesión iniciada|

#### Ejemplo de respuesta (application/json)
```
[ 

    { 
        Id: “...”, 
        Nombre: “...”, 
        Provincia: “...”, 
        Ciudad: ””, 
        Direccion: “...”, 
        Foto: “link de la foto?”, 
        Telefono: “....” 
    }, 
    {sucursal 2}, 
] 
```

---
### <span style="background-color:#67DA30; color: white; padding: 2px 5px; border-radius: 50px;">POST</span> api/sucursal/{id} `AUTH ADMIN, EMPRESA`
Registra a una sucursal a la empresa que lo está registrando. 

#### Body data (multipart/form-data)
| Data | Validaciones |
| ------------ | ------------ |
| `nombre` - *obligatorio* | Que sea unico |
| `provincia` - *obligatorio* | que exista en la bd |
| `ciudad` - *obligatorio* | que exista en la bd |
| `direccion` - *obligatorio* | Aqui no hay ninguna |
| `teléfono` - *obligatorio* | Solo numeros |
| `correo` - *obligatorio* | Que sea unico |
| `foto???` - *obligatorio* | imagenes|

#### Validaciones
|Validaciones|
|------------|
|Tener sesión iniciada de una empresa |
|que no haya correo y teléfonos repetidos |
|validar que los campos no estén vacíos|

#### Ejemplo de respuesta (application/json)
```
{ 
     Id: “...”, 
     Nombre: “...”, 
     Provincia: “...”, 
     Ciudad: ””, 
     Direccion: “...”, 
     Foto: “link de la foto?”, 
     Telefono: “....” 
}, 
```

---
### <span style="background-color:#67DA30; color: white; padding: 2px 5px; border-radius: 50px;">PUT</span> api/sucursal/{id} `AUTH ADMIN, EMPRESA`
Edita la información de una sucursal 

#### Body data (multipart/form-data)
| Data | Validaciones |
| ------------ | ------------ |
| `nombre` - *obligatorio* | Que sea unico |
| `provincia` - *obligatorio* | que exista en la bd |
| `ciudad` - *obligatorio* | que exista en la bd |
| `direccion` - *obligatorio* | Aqui no hay ninguna |
| `teléfono` - *obligatorio* | Solo numeros |
| `correo` - *obligatorio* | Que sea unico |
| `foto???` - *obligatorio* | imagenes|

#### Validaciones
|Validaciones|
|------------|
|Tener sesión iniciada de una empresa |
|que no haya correo, teléfonos repetidos |
|validar que los campos no estén vacíos|
|Que la sucursal corresponda a la empresa que lo esta editando |

#### Ejemplo de respuesta (application/json)
```
{ 
     Id: “...”, 
     Nombre: “...”, 
     Provincia: “...”, 
     Ciudad: ””, 
     Direccion: “...”, 
     Foto: “link de la foto?”, 
     Telefono: “....” 
}, 
```
---
### <span style="background-color:#67DA30; color: white; padding: 2px 5px; border-radius: 50px;">PUT</span> api/sucursal/{id} `AUTH ADMIN, EMPRESA`
Elimina una sucursal 

#### Validaciones
|Validaciones|
|------------|
|Tener sesión iniciada de una empresa |
|Que la sucursal corresponda a la empresa que lo está elimando  |

#### Ejemplo de respuesta (application/json)
```
{ 
     Id: “...”, 
     Nombre: “...”, 
     Provincia: “...”, 
     Ciudad: ””, 
     Direccion: “...”, 
     Foto: “link de la foto?”, 
     Telefono: “....” 
}, 
```
