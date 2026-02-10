# Frontend
> instalacion de tailwind
```
docker compose exec frontend npm install tailwindcss @tailwindcss/postcss postcss
```

> .postcssrc.json en raiz /
```
{
    "plugins": {
      "@tailwindcss/postcss": {}
    }
}
```
> en src/style.scss
```
/* You can add global styles to this file, and also import other style files */
@use 'tailwindcss';

@use 'primeicons/primeicons.css';
@use '../public/assets/layout/layout.scss';
@use '../public/assets/demo/demo.scss';

```
