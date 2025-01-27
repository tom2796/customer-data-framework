#!/bin/bash

set -eu

cp -r .github/ci/files/config config
cp -r .github/ci/files/templates templates
cp -r .github/ci/files/bin bin
cp -r .github/ci/files/kernel kernel
cp -r .github/ci/files/public public
cp -r .github/ci/files/var var

mkdir var/config
cp .github/ci/files/extensions.template.php var/config/extensions.php
cp .github/ci/files/.env ./

if [ ${DEPENDENCIES:-lowest} = "highest" ]; then
    composer require pimcore/pimcore:"11.x-dev as 10.99.99" --no-update
fi
