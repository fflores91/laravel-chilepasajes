# Desafío Técnico - Chilepasajes 

Este breve proyecto es una solución al desafío técnico para consumir la API DONKI de la NASA. La aplicación extrae información sobre los instrumentos utilizados en las mediciones y los identificadores de las actividades, procesándola para exponer varios endpoints RESTful.

La solución sigue una arquitectura limpia y los principios SOLID, utilizando:

- **Dominio:** Definición de contratos (interfaces) en `app/Contracts`.
- **Infraestructura/Servicios:** Implementación en `app/Services/NasaDonkiService.php` para consumir y procesar la data de la API de la NASA.
- **Presentación:** Controladores en `app/Http/Controllers` que exponen los endpoints y consumen la lógica a través de inyección de dependencias.

## Requerimientos

- PHP 8.x
- Composer
- Laravel 12.x
- Git y una cuenta en GitHub
- (Opcional) Postman u otro servicio como Insomnia para probar los endpoints

## Instalación

1. **Clonar el repositorio** (o descargar el código fuente):
   ```bash
   git clone https://github.com/fflores91/laravel-chilepasajes.git
   cd laravel-chilepasajes
   ```
2. **Instalar dependencias**: 
    ```bash
    composer install
    ```
3. **Configurar el entorno**:
    - Copiar el archivo `.env.example` a `.env`
    - Abrir el archivo .env y configurar la variable `NASA_API_KEY`, por ejemplo: 
    ```bash
    NASA_API_KEY=ukAfziyZthbSMl9RbVd7KlkhHMu1y0J0l4o8W0MC
    ```
    - Generar la clave de la aplicación:
    ```bash 
    php artisan key:generate
    ```
4. **Ajustar el rango de fechas** (opcional):

    El servicio utiliza por defecto el rango de fechas sugeridos 2016-03-01 a 2016-03-31 para capturar eventos históricos que incluyan, por ejemplo, el instrumento `"MODEL: SWMF"` y actividades como `"IPS-001"`, `"HSS-001"` o `"GST-001"`.
    Puedes modificar estas fechas en `app/Services/NasaDonkiService.php` según lo que necesites.
