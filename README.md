# ENTORNO DE DESARROLLO

> Iniciar nodejs por primera vez 
```
docker compose exec backend npm init -y
docker compose exec backend npm install express pg dotenv ws cors
```
> Dockerfile.nodejs.dev
```
FROM node:20
RUN npm install -g nodemon
WORKDIR /app
EXPOSE 3000
```
> Iniciar nodejs por primera angular
```
FROM node:24
RUN npm install -g @angular/cli
WORKDIR /app
EXPOSE 4300
CMD ["tail", "-f", "/dev/null"]
```
> Para iniciar el proyecto angular
```
docker compose exec frontend ng new frontend
```
> variable envairoment
```
docker compose exec frontend ng g environments
```
> Para dar los permisos
```
sudo chown -R $USER:$USER backend frontend
```

> 
```

```
> luego ejecuta el siguiente comando
```
docker compose up --build
docker compose up --build -d
docker compose ps
docker compose start
docker compose stop
```
> Para entrar por modo shell
```
docker exec -it backend bash 
docker exec -it frontend bash 
```
> Para ver sus logs
```
docker logs -f backend
docker logs -f frontend
docker compose logs -f 
```