# Sử dụng image chính thức của PHP làm parent image
FROM php:8.1-apache

# Thiết lập thư mục làm việc trong container
WORKDIR /var/www/html

# Copy các tập tin từ thư mục hiện tại vào container tại đường dẫn /var/www/html
COPY . .

# Thay đổi quyền thực thi cho bin/magento
RUN chmod +x bin/magento

# Cài đặt các gói cần thiết
RUN apt-get update && apt-get install -y --no-install-recommends \
    git \
    unzip \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libicu-dev \
    libxml2-dev \
    libzip-dev \
    libxslt-dev \
    default-libmysqlclient-dev \
    && rm -rf /var/lib/apt/lists/*

# Install wait-for-it
RUN curl -LJO https://github.com/vishnubob/wait-for-it/raw/master/wait-for-it.sh && \
    mv wait-for-it.sh /usr/local/bin/wait-for-it && \
    chmod +x /usr/local/bin/wait-for-it
    
# Cài đặt các extension PHP cần thiết
RUN docker-php-ext-install -j$(nproc) \
    soap \
    gd \
    intl \
    sockets \
    zip \
    xsl \
    pdo_mysql

# Kích hoạt các extension PHP
RUN docker-php-ext-enable pdo_mysql

# Cài đặt Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Tạo thư mục .composer và thiết lập thông tin xác thực cho Composer
RUN mkdir -p /root/.composer
COPY auth.json /root/.composer/auth.json

# Cài đặt các dependency của Magento bằng Composer
RUN composer install --no-dev --prefer-dist --optimize-autoloader --ignore-platform-req=ext-xsl --ignore-platform-req=ext-pdo_mysql

# Mở cổng 80 để container có thể truy cập từ bên ngoài
EXPOSE 80

# Định nghĩa các biến môi trường
ENV APACHE_DOCUMENT_ROOT /var/www/html

# Cấu hình máy chủ web Apache
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Khởi động Apache trong chế độ foreground
CMD ["apache2-foreground"]
