<?php

$config = array();

$path = __DIR__."/../settings.json";
if (File::exists($path)) {
    $file = File::get($path);
    $plugin_config = json_decode($file);
    $config = (array)$plugin_config;
}

return $config;