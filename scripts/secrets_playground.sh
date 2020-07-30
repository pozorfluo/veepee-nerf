# generate cryptograpic keys
php bin/console secrets:generate-keys

# set a secret env value
php bin/console secrets:set APICOMMERCE_TOKEN

# list existing secrets
php bin/console secrets:list --reveal

# decrypt secrets
bin/console secrets:decrypt-to-local

# list env variables set by symfony
 php bin/console debug:container --env-vars