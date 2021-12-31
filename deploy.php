<?php

namespace Deployer;

require 'recipe/common.php';

set('application', 'box');
set('repository', 'git@github.com:titomiguelcosta/box.git');
set('git_tty', true);

// Shared files/dirs between deploys 
set('shared_files', []);
set('shared_dirs', []);
set('writable_dirs', []);
set('allow_anonymous_stats', false);

set('composer_options', '{{composer_action}} --verbose --prefer-dist --no-progress --no-interaction --optimize-autoloader');

host('titomiguelcosta.com')
    ->user('ubuntu')
    ->stage('prod')
    ->set('deploy_path', '/mnt/websites/box')
    ->set('writable_mode', 'acl');

// Tasks
desc('Update docker');
task('docker', function () {
    run('cd {{release_path}} && docker-compose up --force-recreate --build --no-deps -d');
    run('docker image prune -f');
    run('cd {{release_path}} && docker-compose exec -T php-fpm composer install --verbose --prefer-dist --no-progress --no-interaction --optimize-autoloader');
    run('cd {{release_path}} && docker-compose exec -T php-fpm php bin/console cache:clear');
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
