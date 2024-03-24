FROM nginx:latest
COPY ./default.conf /etc/nginx/conf.d/
COPY ./upstream.conf /etc/nginx/conf.d/

RUN cat /etc/nginx/conf.d/upstream.conf

RUN mkdir -p /var/www/project/var

RUN chown -R nginx:nginx /var/www/project \
    && chmod -R 755 /var/www/project/var

RUN usermod -u 1000 www-data