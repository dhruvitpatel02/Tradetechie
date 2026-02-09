FROM php:8.2-cli

# Install PDO MySQL
RUN docker-php-ext-install pdo pdo_mysql

WORKDIR /app
COPY . /app

# Railway uses PORT env variable
CMD ["sh", "-c", "php -S 0.0.0.0:${PORT:-8080} -t /app"]
