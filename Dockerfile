FROM umberfirm/ubuntu16.04

WORKDIR /srv/www/ufsoa

COPY . /srv/www/ufsoa

VOLUME /srv/www/ufsoa

RUN chmod -R 0777 /srv/www/ufsoa/var

ENTRYPOINT ["/usr/bin/tail", "-f", "/dev/null"]
