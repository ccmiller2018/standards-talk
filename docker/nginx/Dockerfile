# Offical Docker Image for Nginx
# https://hub.docker.com/_/nginx
FROM nginx

# Set Current Directory
WORKDIR /var/www/vhost/

# Vhost Config
COPY ./config/default.conf /etc/nginx/conf.d/default.conf
COPY ./config/vhost.conf /etc/nginx/conf.d/vhost.conf

# Self-signed SSL Certificates (see README.md)
COPY ./certs/ssl.crt /etc/nginx/ssl/ssl.crt
COPY ./certs/ssl.key /etc/nginx/ssl/ssl.key
