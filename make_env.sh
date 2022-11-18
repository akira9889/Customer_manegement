#!/bin/bash
set -eu

cat << EOT > .env
#PHPの設定
WEB_PORT=80
DB_PORT=3306
DB_HOST=db
DB_NAME=customer_manegement
DB_USER=root
DB_PASS=pass

EOT
