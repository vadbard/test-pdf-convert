# Запуск проекта

1. Перейти в корень проекта
2. Выполнить
```sh
docker build ./docker/php -t pdf_converter
docker run -it -v ./src:/var/www/app pdf_converter composer install
docker run -it -v ./src:/var/www/app pdf_converter php artisan key:generate
```
3. Пример запуска
```sh
docker run -it -v ./src:/var/www/app pdf_converter php artisan convert:pdf ./example.pdf
```