# Sử dụng image chính thức của PHP làm parent image
FROM php:8.1-apache

# Thiết lập thư mục làm việc trong container
WORKDIR /var/www/html

# Copy các tập tin từ thư mục hiện tại vào container tại đường dẫn /var/www/html
COPY . .

# Cài đặt các gói cần thiết
RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        git \
        unzip \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
        libicu-dev \
        libxml2-dev \
        libzip-dev \
    && rm -rf /var/lib/apt/lists/* \
# Cài đặt các extension PHP cần thiết
    && docker-php-ext-install -j$(nproc) \
        soap \
        gd \
        intl \
        sockets \
        zip

# Cài đặt Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Cài đặt các dependency của Magento bằng Composer
RUN composer install --no-dev --prefer-dist --optimize-autoloader

# Mở cổng 80 để container có thể truy cập từ bên ngoài
EXPOSE 80

# Định nghĩa các biến môi trường
ENV APACHE_DOCUMENT_ROOT /var/www/html

# Cấu hình máy chủ web Apache
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Khởi động Apache trong chế độ foreground
CMD ["apache2-foreground"]
