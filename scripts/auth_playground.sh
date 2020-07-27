# install security bundle
composer require symfony/security-bundle

# enable new security style (optional)
# config/packages/security.yaml
security:
    enable_authenticator_manager: true

# create a User class
php bin/console make:user

# prepare, run migration

# test password encoding
php bin/console security:encode-password

# create dummy database users
composer require orm-fixtures --dev
php bin/console make:fixtures

# empty database and reload all fixture classes
php bin/console doctrine:fixtures:load

# create a new security voter class
php bin/console make:voter

# generate login form
php bin/console make:auth

# generate registration form
php bin/console make:registration-form