FROM camillebaronnet/hosting
MAINTAINER Camille Baronnet <docker@camillebaronnet.fr>

ENV VERSION 2.2
ENV VERSIONFULL 2.2.2-1981
ENV TERM xterm

ENV ZPUSH_URL zpush_default
ENV ZIMBRA_HOST localhost

RUN apt-get install -y wget

RUN cd /home/www/public && \
	wget -O - "http://download.z-push.org/final/${VERSION}/z-push-${VERSIONFULL}.tar.gz" | tar --strip-components=1 -x -z

RUN cd /tmp && \
	wget -O - "http://downloads.sourceforge.net/project/zimbrabackend/Release62/zimbra62.tgz?use_mirror=freefr" | tar --strip-components=1 -x -z && \
	mv /tmp/z-push-2 /home/www/public/backend/zimbra

RUN mkdir /home/logs && mkdir /home/logs/z-push && mkdir /var/lib/z-push
RUN chmod -R 777 /home/logs && chmod -R 777 /var/lib/z-push

RUN sed -i "s/#ZPUSH_HOST#/$ZPUSH_URL/" /home/www/public/config.php
RUN sed -i "s/#ZIMBRA_HOST#/$ZIMBRA_HOST/" /home/www/public/config.php


COPY autodiscover/ /home/www/public/autodiscover/
COPY config.php /home/www/public/config.php
COPY default.vhost /etc/apache2/sites-enabled/000-default

COPY ./startup.sh /root/startup.sh
CMD ["/bin/bash", "/root/startup.sh"]
