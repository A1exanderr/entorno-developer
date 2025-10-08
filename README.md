# DESARROLLO NODEJS y VUEJS
>git
```
git checkout VueJsNodeJs
git submodule update --init --recursive
```
>luego ejecuta el siguiente comando
```
docker compose up --build
docker compose up --build -d
docker compose ps
docker compose start
docker compose stop
```
> Para crear proyectos entre por shell
```
docker exec -it nodejs_app bash 
docker exec -it angular_app bash 
```
>creacion de proyectos
```
ng new hola_mundo


ng s --host 0.0.0.0
php artisan serve --host 0.0.0.0
```
>para desarrollo dar permisos
```
sudo chown -R $USER:$USER frontend/angular backend/laravel
```
>comandos necesarios
```
ng g environments
```
>Para ver los logs
```
docker logs -f nodejs_app
docker logs -f angular_app
```