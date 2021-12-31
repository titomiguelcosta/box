<?php

namespace Deployer;

require 'recipe/common.php';

set('application', 'box:api');
set('repository', 'https://github.com/titomiguelcosta/box.git');
set('shared_dirs', ['var/log', 'var/sessions', 'vendor']);
set('git_tty', false);

host('titomiguelcosta.com')
    ->user('ubuntu')
    ->stage('prod')
    ->set('deploy_path', '/mnt/websites/box')
    ->set('writable_mode', 'acl');

task('deploy', function () {
    run('git pull origin master');
    run('docker-compose up --force-recreate --build -d');
    run('docker image prune -f');
    run('docker-compose exec -it box-php-fpm composer install --verbose --prefer-dist --no-progress --no-interaction --optimize-autoloader');
    run('docker-compose exec -it box-php-fpm php bin/console cache:clear --env=prod');
});
