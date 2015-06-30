<?php
use Drupal\library\Library;
state("user.jaeho.name", "JaeHo Song");
state("user.jaeho.email", "thruthesky@gmail.com");
state("user.jaeho.address", "Pampanga, Philippines");
state("user.thruthesky.name", "Thru, T. Sky");
state("user.thruthesky.address", "GimHae City, Republic of Korea");
$configs = getStateGroup('user');
print_r($configs);
$configs = getStateGroup('user.thruthesky');
print_r($configs);
echo "My name is: " . state("user.jaeho.name");