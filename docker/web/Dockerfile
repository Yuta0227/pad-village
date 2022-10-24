FROM node:14.2-alpine as node
FROM nginx:1.18-alpine
LABEL maintainer="ucan-lab <yes@u-can.pro>"
SHELL ["/bin/bash", "-oeux", "pipefail", "-c"]
# SHELL ["/bin/ash", "-oeux", "pipefail", "-c"]


ENV TZ=UTC

RUN apk update && \
  apk add --update --no-cache --virtual=.build-dependencies g++

# node command
COPY --from=node /usr/local/bin /usr/local/bin
# npm command
COPY --from=node /usr/local/lib /usr/local/lib
# yarn command
COPY --from=node /opt /opt
# nginx config file
COPY ./etc/nginx/conf.d/default.conf /etc/nginx/conf.d/default.conf
# nginx.confを取り込めるようにする
COPY ./etc/nginx/nginx.conf /etc/nginx/nginx.conf

CMD "envsubst '\"$PORT"' < /etc/nginx/conf.d/default.conf > /etc/nginx/conf.d/default.conf" && 'nginx -g daemon off;'

WORKDIR /work/backend
 