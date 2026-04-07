FROM dockware/essentials:2.0

RUN sudo apt-get update && sudo apt-get install -y xvfb libgtk2.0-0 libgtk-3-0 libgbm-dev libnotify-dev libnss3 libxss1 libasound2 libxtst6 xauth xvfb

COPY ./config /var/www/html/config
COPY ./e2e/docker/dockware/shopware.yaml /var/www/html/config/packages/shopware.yaml

RUN sudo chmod -R 777 /var/www/html/config
RUN sudo rm -rf /var/www/html/config/packages/redis.yaml
