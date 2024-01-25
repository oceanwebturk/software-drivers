<?php

namespace OWT\SoftWareDrivers;

use OceanWT\Config;
use OceanWT\Support\ServiceProvider;

class SoftWareServiceProvider extends ServiceProvider{
 /**
  * @return void
  */
 public function boot(): void
 {
  Config::addPath(__DIR__."/../","software-drivers-conf");
  $this->cli->command("software:publish",[$this,'software_publish'],[
   "description"=>"Software Drivers Publisher"
  ]);
 }

 /**
  * @param  array  $params
  */
 public function software_publish(array $params=[])
 {
  $path=realpath(__DIR__."/../")."/";
  $dockerCompose=$path."docker-compose.yml";
  switch(isset($params[2])){
    case 'docker':
     $GLOBALS['docker_files']=[
      $dockerCompose=>config("software-drivers-conf::software")->publish_root_path."docker-compose.yml"
     ];
     $GLOBALS['message']="
";
     copy($dockerCompose,config("software-drivers-conf::software")->publish_root_path."docker-compose.yml");
     array_map(function($file){
      $path=realpath(__DIR__."/../")."/";
      if(!is_dir(config("software-drivers-conf::software")->publish_root_path."docker/")){
       mkdir(config("software-drivers-conf::software")->publish_root_path."docker/");
      }
      copy($file,config("software-drivers-conf::software")->publish_root_path.str_replace($path,"",$file));
      $GLOBALS['docker_files'][$file]=config("software-drivers-conf::software")->publish_root_path.str_replace($path,"",$file);
     },glob($path."docker/*"));
     array_map(function($file){
      $GLOBALS['message'].="  Published ".$file."\n";
     },$GLOBALS['docker_files']);
    $this->command->write($GLOBALS['message']);
    break;
  }
 }
}
