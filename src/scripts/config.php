<?php
use Drupal\library\Config;
Config::set('abc', 'def', 'ghi');
Config::set('abc', 'key', 'value');
Config::set('abc', 'name', 'Jae Ho Song');
Config::set('abc', 'name', 'thruthesky');
echo Config::get('abc', 'name');
