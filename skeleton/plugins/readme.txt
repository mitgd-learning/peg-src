You can write plugins that modify the behavior of peg and place them
on this directory. They will be automatically loaded.


How to write your first plugin:
================================================================================


1. Create a file named MyPlugin.php

2. Add the following content to MyPlugin.php:

----------------------------COPY AFTER THIS LINE--------------------------------
<?php
// Your plugin must be part of the Peg\Lib\Plugins namespace
namespace Peg\Lib\Plugins;

use Peg\Lib\Application;

// Your plugin must implement Peg\Lib\Plugins\Base
class MyPlugin extends \Peg\Lib\Plugins\Base
{
    // On this method you can add any kind of code to modify peg behavior.
    // In this case we are just changing the application name from peg to gen.
    public function OnInit(){
        Application::GetCLIParser()->application_name = "gen";
    }
}
--------------------------END COPY BEFORE THIS LINE-----------------------------

3. Execute the "peg" command on your extension 
   root directory and see what happens.