<?php 
function adminer_object() 
{
   require "./plugins/fc-sqlite-connection-without-credentials.php";
   require "./plugins/plugin.php";
  
   $plugins = array(new FCSqliteConnectionWithoutCredentials());
    
   return new AdminerPlugin($plugins);
}

require "./adminer.php";