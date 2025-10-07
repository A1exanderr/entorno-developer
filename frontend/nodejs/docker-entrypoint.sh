#!/bin/bash

# Si no existe angular.json, asumimos que no hay proyecto
if [ ! -f "angular.json" ]; then
  echo "No se encontró un proyecto de Angular. Creando uno nuevo..."
  ng new my-app --directory=./ --skip-git --skip-install --strict=false
  # Instalar dependencias del nuevo proyecto
  #npm install
fi
npm install
# Ejecutar ng serve
exec ng serve --host 0.0.0.0