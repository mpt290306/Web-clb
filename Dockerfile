# Sử dụng image PHP chính thức với Apache (server chạy web)
FROM php:8.2-apache

RUN apt-get update && apt-get install -y --no-install-recommends libonig-dev \
    && docker-php-ext-install mbstring \
    && rm -rf /var/lib/apt/lists/*

# Sao chép toàn bộ nội dung thư mục hiện tại vào thư mục web của Apache
COPY . /var/www/html/
COPY apache-security.conf /etc/apache2/conf-available/site-security.conf
COPY start-web.sh /usr/local/bin/start-web.sh
RUN a2enconf site-security

# Cấp quyền cho thư mục web (để tránh lỗi truy cập)
RUN chown -R www-data:www-data /var/www/html

# Render routes web requests to port 10000 by default.
RUN sed -i 's/Listen 80/Listen 10000/' /etc/apache2/ports.conf \
    && sed -i 's/<VirtualHost \*:80>/<VirtualHost *:10000>/' /etc/apache2/sites-available/000-default.conf

# Mở cổng HTTP mà Render dùng
EXPOSE 10000

# Chạy Apache ở chế độ foreground
CMD ["sh", "/usr/local/bin/start-web.sh"]
