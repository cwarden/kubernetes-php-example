FROM php:7.0-cli

RUN mkdir /app
WORKDIR /app
COPY . /app
CMD ["php", "/app/run.php"]

