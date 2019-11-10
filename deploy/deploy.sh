language: php
php:
- 7.0

script:
# Override default Travis script action [phpunit]
- php -l *.php

branches:
  only:
  # Enable Travis hook on tags (there is regular expression for semver tag)*
  - "/\\d\\.\\d\\.\\d/"

# Enable Travis deployment
deploy:
  # Use script as a deployment tool
  provider: script
  script: deploy/deploy.sh
  # Restrict deployment only for tags
  on:
    tags: true

# Deployment script requires few enviromnet variables
env:
  global:
  - SVN_REPOSITORY={PLUGIN SVN REPOSITORY URL}
  - secure: {ENCRYPTED SVN ACCOUNT USERNAME}
  - secure: {ENCRYPTED SVN ACCOUNT PASSWORD}
