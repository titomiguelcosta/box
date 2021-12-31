<?php

namespace Deployer;

require 'recipe/common.php';

set('application', 'box');
set('repository', 'git@github.com:titomiguelcosta/box.git');
set('git_tty', true);

// Shared files/dirs between deploys 
set('shared_files', ['.env.local']);
set('shared_dirs', ['var/log', 'var/sessions', 'vendor']);

set('writable_dirs', []);
set('allow_anonymous_stats', false);


host('titomiguelcosta.com')
    ->user('ubuntu')
    ->stage('prod')
    ->set('deploy_path', '/mnt/websites/box')
    ->set('writable_mode', 'acl');


// Tasks
desc('Update docker');
task('docker', function () {
    run('docker-compose up --force-recreate --build -d');
    run('docker image prune -f');
    run('docker-compose exec -it box-php-fpm composer install --verbose --prefer-dist --no-progress --no-interaction --optimize-autoloader');
    run('docker-compose exec -it box-php-fpm php bin/console cache:clear --env=prod');
});

desc('Deploy your project');
task('deploy', [
    'deploy:info',
    'deploy:prepare',
    'deploy:lock',
    'deploy:release',
    'deploy:update_code',
    'deploy:shared',
    'deploy:writable',
    'deploy:vendors',
    'deploy:clear_paths',
    'docker',
    'deploy:symlink',
    'deploy:unlock',
    'cleanup',
    'success'
]);

// [Optional] If deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');
