# ENTORNO DE DESARROLLO

> Git
```
git checkout AngularNodejs_idg
git submodule update --init --recursive
```
> luego ejecuta el siguiente comando
```
docker compose up --build
docker compose up --build -d
docker compose ps
docker compose start
docker compose stop
```
> Para entrar por modo shall
```
docker exec -it backend_idg bash 
docker exec -it frontend_idg bash 
```
> Para ver sus logs
```
docker logs -f backend_idg
docker logs -f frontend_idg 
```
> Para dar los permisos
```
sudo chown -R $USER:$USER uegg_backend uegg_frontend
```
> 
```

```