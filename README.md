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
- (Opcional) Postman para probar los endpoints
