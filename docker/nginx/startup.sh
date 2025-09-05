#!/bin/bash

if [ ! -f /etc/nginx/ssl/doglink.test.crt ]; then
    openssl genrsa -out "/etc/nginx/ssl/doglink.test.key" 2048
    openssl req -new -key "/etc/nginx/ssl/doglink.test.key" -out "/etc/nginx/ssl/doglink.test.csr" -subj "/CN=doglink.test/O=doglink.test/C=UK"
    openssl x509 -req -days 365 -in "/etc/nginx/ssl/doglink.test.csr" -signkey "/etc/nginx/ssl/doglink.test.key" -out "/etc/nginx/ssl/doglink.test.crt"
    chmod 644 /etc/nginx/ssl/doglink.test.key
fi

# Start crond in background
crond -l 2 -b

# Start nginx in foreground
nginx
