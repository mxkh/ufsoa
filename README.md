UmberFirm SOA
=====

#Docker configuration:

***Before you start, you should not install docker https://www.docker.com/what-docker
and register for an account on https://hub.docker.com/ and then follow these steps:***

* Run `sh init.sh`
* Run `docker-compose -f docker-compose-mac.yml up` if your host machine is macOS or `docker-compose up` for others
* Add to your host file `0.0.0.0 ufsoa.dev`
* Got to http://ufsoa.dev:8080 in your browser

#For MacOS users only

After you running compose up, you must wait ~2-5 minutes before as unison finished synchronise all app data and run `docker-compose exec php chown -R sysuf:sysuf /srv/www/ufsoa`

#Symfony setup

* Run composer `docker-compose exec --user=sysuf symfony composer install`

#Application commands

* For running console commands you can use "docker-compose exec", for example: `docker-compose exec --user=sysuf symfony bin/console --version`

#SSH

* If you want connect to container via SSH you can use "phpremote" user with password "remote" and "2222" port, for example: `ssh -p 2222 phpremote@localhost`

#PHPStorm

For setting up remote PHP interpreter go to Preferences - Languages & Frameworks - PHP:
![Step 1]
(https://s3.eu-central-1.amazonaws.com/mkhartanovych/docker-php-remote-1.jpg)
![Step 2]
(https://s3.eu-central-1.amazonaws.com/mkhartanovych/docker-php-remote-2.jpg)

#Elasticsearch

* Elasticsearch HQ plugin http://localhost:9200/_plugin/hq
* Elasticsearch Kopf plugin http://localhost:9200/_plugin/kopf/

#DoctrineFixtures + test database
read docs here http://symfony.com/doc/current/bundles/DoctrineFixturesBundle/index.html

to init test database and load fixtures there run following commands
* `docker-compose exec --user=sysuf symfony bin/console doctrine:database:drop --force --env=test`
* `docker-compose exec --user=sysuf symfony bin/console doctrine:database:create --env=test`
* `docker-compose exec --user=sysuf symfony bin/console doctrine:migrations:migrate --env=test`
* `docker-compose exec --user=sysuf symfony bin/console doctrine:fixtures:load --env=test`

to load fixtures to the dev database run following commands
* `docker-compose exec --user=sysuf symfony bin/console doctrine:migrations:migrate --env=dev`
* `docker-compose exec --user=sysuf symfony bin/console doctrine:fixtures:load --env=dev`

##WARNING DANGER ALARM PIUPIU 
make note: `doctrine:fixtures:load` will purge your data.