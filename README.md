# Template API

## Comando para iniciar desarrollo

```bash
./build.sh
```

> *la credenciales son: 
    - email: ``developer@correo.com``
    - password:**``123456789``**


## Parametros globales de consulta GET

### `schema`

- **Tipo:** ``Array<string>``
- **Descripción:** Permita filtrar las keys del esquema, para obtener solo las keys que se necesitan usan la ``notación dot`` para acceder a keys dentro de otros objetos.
- **Default:** `None`

```curl
/cities?schema[]=id&schema[]=state.id&schema[]=state.name
```

eso permite obtener solo:

```json
[
    {
        "id": 1,
        "state": {
            "id": 2,
            "name": "Alabama"
        }
    }
]
```

#### `paginate`

- **Tipo:** ``Boolean``
- **Descripción:** Indica si la colección retorna paginada o no.
- **Default:** true

```curl
/cities?paginate=false
```

#### page

- **Tipo:** ``Integer``
- **Descripción:** Funciona junto al parámetro "paginate" e indica la página a ser consultada.
- **Default:** 1

```curl
/cities?page=2
```

#### per_page

- **Tipo:** ``Integer``
- **Descripción:** Funciona junto al parámetro "paginate" e indica la cantidad de recursos que vienen.
- **Default:** 10

```curl
/cities?page=2&per_page=50
```

#### sort

- **Tipo:** ``Object``
- **Descripción:** Indica la manera en la que será ordenada el resultado. La key usa la `notación dot` para acceder a keys dentro de otros objetos.
- **Default:** `None`

```curl
/cities?sort[state.id]=desc
```

#### contains

- **Tipo:** ``Object``
- **Descripción:** Indica la búsqueda de recursos. Contiene dos keys "items" y "text". 
  - key ``items``: Especifica un array de items del esquema, siguiendo también la ``notación dot``. 
  - key ``text``: El texto para filtrar en todos los items del esquema seleccionados.
- **Default:** `None`

```curl
/cities?contains[items][]=state.name&contains[items][]=state.abbr&contains[text]=alabama
```
