<?php

namespace Phqzing;

use pocketmine\scheduler\Task;
use pocketmine\Server;

class ChatTask extends Task {
  
  private $plugin;
  
  public function __construct(ChatCooldown $plugin){
    $this->plugin = $plugin;
  }
  
  
  public function onRun(int $tick):void{
    
    foreach(Server::getInstance()->getOnlinePlayers() as $online){
      if(isset($this->plugin->cd[$online->getName()])){
        
        $this->plugin->cd[$online->getName()]--;
        
        if($this->plugin->cd[$online->getName()] === 0){
          unset($this->plugin->cd[$online->getName()]);
        }
      }
    }
  }
}