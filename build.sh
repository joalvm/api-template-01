#!/bin/bash

EXISTS_ENV_FILE=1

# Determinar el sistema operativo y asignar la ruta del directorio actual
if [[ "$OSTYPE" == "linux-gnu"* ]]; then
    CURRENT_DIR=$(pwd)
elif [[ "$OSTYPE" == "cygwin" || "$OSTYPE" == "msys" ]]; then
    CURRENT_DIR=$(cygpath -w $(pwd))
else
    echo "Sistema operativo no soportado"
    exit 1
fi

# Verificar si existe el archivo .env si no existe copiar el de .env.example
if [ ! -f .env ]; then
    cp .env.example .env

    # Preguntar por el nombre de la aplicación y cambiar el nombre en el archivo .env
    read -p "Nombre de la aplicación: " APP_NAME
    sed -i "s/APP_NAME=company-name/APP_NAME=$APP_NAME/g" .env

    EXISTS_ENV_FILE=0
fi

# Cargo las variables de entorno
source .env

IMAGE_NAME=$APP_NAME:php8.2-apache
NETWORK_NAME=$APP_NAME-net
API_CONTAINER_NAME=$APP_NAME-api
DB_CONTAINER_NAME=$APP_NAME-db

# Verificar la red ya existe
if [ ! "$(docker network ls | grep $NETWORK_NAME)" ]; then
    docker network create $NETWORK_NAME --driver=bridge
fi

# Construir la imagen de docker
docker build \
    -t $IMAGE_NAME \
    --build-arg APP_NAME=$APP_NAME \
    --build-arg WWWGROUP=$WWWGROUP \
    --build-arg WWWUSER=$WWWUSER \
    --build-arg FILESYSTEM_ROOT=$FILESYSTEM_ROOT \
    .

# Eliminar el contenedor existente si existe
if [ "$(docker ps -a | grep $API_CONTAINER_NAME)" ]; then
    docker rm -f $API_CONTAINER_NAME
fi

# Iniciar el contenedor docker
docker run \
    --name $API_CONTAINER_NAME \
    -p $APP_PORT:80 \
    -v $CURRENT_DIR:/var/www/html \
    --network $NETWORK_NAME \
    -d \
    $IMAGE_NAME

# Instalar las dependencias de composer si no existe la carpeta vendor en el directorio actual sino actualizar las dependencias
if [ ! -d vendor ]; then
    docker exec -it $API_CONTAINER_NAME composer install -o
else
    docker exec -it $API_CONTAINER_NAME composer update -o
fi

# Generar la llave de la aplicación en caso no haya existido el archivo .env
if [ $EXISTS_ENV_FILE -eq 0 ]; then
    docker exec -it $API_CONTAINER_NAME php artisan key:generate
fi

# Verificar si existe el contenedor de la base de datos, sino crearlo
if [ ! "$(docker ps -a | grep $DB_CONTAINER_NAME)" ]; then
    docker run \
        --name $DB_CONTAINER_NAME \
        -e POSTGRES_PASSWORD=postgres \
        -e POSTGRES_USER=postgres \
        --network=$NETWORK_NAME \
        -p $DB_PORT:5432 \
        -d \
        postgres:15-alpine
fi

# Verificar si existe la base de datos, sino crearla
if [ ! "$(docker exec -it $DB_CONTAINER_NAME psql -U postgres -lqt | cut -d \| -f 1 | grep $DB_DATABASE)" ]; then
    docker exec -it $API_CONTAINER_NAME php artisan db:generate
fi
