# Prettier per formattare JS e CSS (il pacchetto non ha dipendenze)
FROM node:22-alpine

ADD https://registry.npmjs.org/prettier/-/prettier-3.3.3.tgz /tmp/prettier.tgz
RUN mkdir -p /opt/prettier \
 && tar -xzf /tmp/prettier.tgz -C /opt/prettier --strip-components=1 \
 && rm /tmp/prettier.tgz

WORKDIR /app
ENTRYPOINT ["node", "/opt/prettier/bin/prettier.cjs"]
