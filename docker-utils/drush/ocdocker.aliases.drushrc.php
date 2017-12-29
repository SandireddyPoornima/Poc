<?php

if (!isset($drush_major_version)) {
    $drush_version_components = explode('.', DRUSH_VERSION);
    $drush_major_version = $drush_version_components[0];
}
// Site oncorps2, environment dev
$docker_machine_ip = '127.0.0.1';
$deployments = array(
    'bbh'=>array(
        'root' => '/var/www/html/oncorps_ace/docroot',
        'uri' => 'bbh.oncorps.local',
        'remote-host' => $docker_machine_ip,
        'remote-user' => 'root',
    ),
    'demo'=>array(
        'root' => '/var/www/html/oncorps_ace/docroot',
        'uri' => 'demo.oncorps.local',
        'remote-host' => $docker_machine_ip,
        'remote-user' => 'root',
    ),
    'dev'=>array(
        'root' => '/var/www/html/oncorps_ace/docroot',
        'uri' => 'dev.oncorps.local',
        'remote-host' => $docker_machine_ip,
        'remote-user' => 'root',
    ),
    'dell'=>array(
        'root' => '/var/www/html/oncorps_ace/docroot',
        'uri' => 'dell.oncorps.local',
        'remote-host' => $docker_machine_ip,
        'remote-user' => 'root',
    ),
    'ey'=>array(
        'root' => '/var/www/html/oncorps_ace/docroot',
        'uri' => 'ey.oncorps.local',
        'remote-host' => $docker_machine_ip,
        'remote-user' => 'root',
    ),
    'on'=>array(
        'root' => '/var/www/html/oncorps_ace/docroot',
        'uri' => 'on.oncorps.local',
        'remote-host' => $docker_machine_ip,
        'remote-user' => 'root',
    ),
    'preview'=>array(
        'root' => '/var/www/html/oncorps_ace/docroot',
        'uri' => 'preview.oncorps.local',
        'remote-host' => $docker_machine_ip,
        'remote-user' => 'root',
    ),
);
foreach($deployments as $key=>$deployment){
    $aliases[$key]=$deployment + array('ssh-options' => '-p 49100',);
    $aliases["{$key}-internal"] = $deployment;
}

