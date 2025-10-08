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
docker exec -it vuejs_app bash 
```
>Para ver los logs
```
docker logs -f nodejs_app
docker logs -f vuejs_app
```
>Dar permisos al usuario actual para desarrollo
```
sudo chown -R $USER:$USER uegg_backend uegg_frontend
```
> migracion a la base datos
```
psql -h 10.1.10.24 -p 5432 -U dba -d uegg -f dump-uegg-202510041609.sql
```
>
```

```