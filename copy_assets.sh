#!/bin/bash

# Create necessary directories
mkdir -p public/assets/vendor/{fonts,libs,js,css}
mkdir -p public/assets/img/{avatars,favicon}
mkdir -p public/assets/css

# Copy vendor assets
cp -r "/C:/Users/Oussama/Downloads/materio-bootstrap-html-admin-template-free-main/assets/vendor"/* public/assets/vendor/
cp -r "/C:/Users/Oussama/Downloads/materio-bootstrap-html-admin-template-free-main/assets/css"/* public/assets/css/
cp -r "/C:/Users/Oussama/Downloads/materio-bootstrap-html-admin-template-free-main/assets/img"/* public/assets/img/
cp -r "/C:/Users/Oussama/Downloads/materio-bootstrap-html-admin-template-free-main/assets/js"/* public/assets/js/ 