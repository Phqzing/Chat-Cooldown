<?php

namespace Phqzing;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\Player;

class ChatCooldown extends PluginBase implements Listener {
  
  public $cd = [];
  
  public function onEnable(){
    @mkdir($this->getDataFolder());
    $this->saveDefaultConfig();
    $this->getResource("config.yml");
    $this->getServer()->getPluginManager()->registerEvents($this, $this);
    $this->getScheduler()->scheduleRepeatingTask(new ChatTask($this), 20);
  }
  
  
  public function onChat(PlayerChatEvent $ev){
    $player = $ev->getPlayer();
    $cooldown = $this->getConfig()->get("cooldown");
    $message = $this->getConfig()->get("message");
    
    if(!$player->hasPermission("bypass.chat.cooldown")){
      if(isset($this->cd[$player->getName()])){
        $ev->setCancelled();
        $message = str_replace("{time}", $this->cd[$player->getName()], $message);
        $player->sendMessage($message);
      }else{
        $this->cd[$player->getName()] = $cooldown;
      }
    }
  }
}