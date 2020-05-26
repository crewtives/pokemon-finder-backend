---
title: API Reference

language_tabs:
- bash
- javascript

includes:

search: true

toc_footers:
- <a href='http://github.com/mpociot/documentarian'>Documentation Powered by Documentarian</a>
---
<!-- START_INFO -->
# Info

Welcome to the generated API reference.
[Get Postman Collection](http://localhost/docs/collection.json)

<!-- END_INFO -->

#Autenticacion


APIs para administrar autenticacion
<!-- START_4a6a89e9e0eaea9c72ceea57315f2c42 -->
## Autenticar usuario

Servicio para poder autenticar usuarios

> Example request:

```bash
curl -X POST \
    "http://localhost/api/authenticate" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"name":"voluptates","email":"quasi","password":"ab"}'

```

```javascript
const url = new URL(
    "http://localhost/api/authenticate"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "name": "voluptates",
    "email": "quasi",
    "password": "ab"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "token": "token",
    "user": {}
}
```

### HTTP Request
`POST api/authenticate`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `name` | string |  required  | parametro para poder registrar/identificar al usuario
        `email` | string |  required  | parametro para poder registrar/identificar al usuario
        `password` | string |  required  | parametro para poder completar la autenticación
    
<!-- END_4a6a89e9e0eaea9c72ceea57315f2c42 -->

<!-- START_de8c14bf8c667d39af01ea1e6fcc847a -->
## Tomar user logueado

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
Servicio para poder tomar datos del usuario logueado

> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/getUser" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/getUser"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "user": {}
}
```

### HTTP Request
`GET api/getUser`


<!-- END_de8c14bf8c667d39af01ea1e6fcc847a -->

#Pokemons


APIs para administrar pokemons
<!-- START_b622fad7b219e6c845195a7d7fdf19e4 -->
## Tomar pokemon

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
Servicio para poder traer informacion de un pokemon

> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/getPokemon" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"pokemon_id":"corrupti"}'

```

```javascript
const url = new URL(
    "http://localhost/api/getPokemon"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "pokemon_id": "corrupti"
}

fetch(url, {
    method: "GET",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "0": {},
    "specie": {},
    "stats": []
}
```

### HTTP Request
`GET api/getPokemon`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `pokemon_id` | string |  optional  | parametro para poder identificar el Pokemon
    
<!-- END_b622fad7b219e6c845195a7d7fdf19e4 -->

<!-- START_e012813b199b95ae205169c2753c8455 -->
## Tomar pokemons del usuario

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
Servicio para poder traer todos los pokemons de un usuario

> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/getUserPokemons" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/getUserPokemons"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "user_id": 1,
    "pokemon_id": 1,
    "level": 1,
    "experience": 0,
    "pokemonApi": {}
}
```

### HTTP Request
`GET api/getUserPokemons`


<!-- END_e012813b199b95ae205169c2753c8455 -->

<!-- START_eebc2f91fd10e5e1f6cd0b124aa27501 -->
## Guardar pokemon

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
Servicio para poder generar y guardar un pokemon

En este servicio se aplican las formulas para poder crear stats aleatorios, con la formula de pokeapi
de esta anera garantizamos que un pokemon no sea igual a otro
Tambien tiene la regla de individual value para poder agregar descripciones especiales a cada stat

formulas:
https://www.dragonflycave.com/mechanics/stats

caracterisiticas
https://bulbapedia.bulbagarden.net/wiki/Characteristic

> Example request:

```bash
curl -X POST \
    "http://localhost/api/saveUserPokemon" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"pokemon_id":"nobis","user_id":"alias"}'

```

```javascript
const url = new URL(
    "http://localhost/api/saveUserPokemon"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "pokemon_id": "nobis",
    "user_id": "alias"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "user_id": 1,
    "pokemon_id": 1,
    "level": 1,
    "experience": 0,
    "stats": [],
    "pokemonApi": {}
}
```

### HTTP Request
`POST api/saveUserPokemon`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `pokemon_id` | string |  optional  | parametro para poder identificar el Pokemon
        `user_id` | string |  optional  | parametro para poder asignar el pokemon al usuairo
    
<!-- END_eebc2f91fd10e5e1f6cd0b124aa27501 -->

#Pokemons iniciales


APIs para administrar pokemons iniciales
<!-- START_2745e7e9f71d451d94ac67fcd1ff126d -->
## Tomar pokemons iniciales

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
Servicio para poder traer los pokemones iniciales

> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/getStartupPokemons" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/getStartupPokemons"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "pokemons": []
}
```

### HTTP Request
`GET api/getStartupPokemons`


<!-- END_2745e7e9f71d451d94ac67fcd1ff126d -->


