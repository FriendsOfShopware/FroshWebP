#!/usr/bin/env bash

commit=$1
if [ -z ${commit} ]; then
    commit=$(git tag --sort=-creatordate | head -1)
    if [ -z ${commit} ]; then
        commit="master";
    fi
fi

# Remove old release
rm -rf FroshWebP FroshWebP-*.zip

# Build new release
mkdir -p FroshWebP
git archive ${commit} | tar -x -C FroshWebP
composer install --no-dev -n -o -d FroshWebP
( find ./FroshWebP -type d -name ".git" && find ./FroshWebP -name ".gitignore" && find ./FroshWebP -name ".gitmodules" ) | xargs rm -r
zip -r FroshWebP-${commit}.zip FroshWebP
