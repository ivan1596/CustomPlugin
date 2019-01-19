<?php
  /** Plugin declaration
   * extends MantisPlugin
   */
class CustomPluginPlugin extends MantisPlugin
 {
   # Declare your plugin here
   # Properties will be used by Mantis plugin's system
   function register()
    {
      $this->name = 'CustomPlugin';
      $this->description = 'A custom plugin to modify Mantis Look and Functionality';
      $this->page = '';
      $this->version = '0.0.1';
      $this->requires = array(
        "MantisCore" => "2.0.0",
      );
      $this->author = 'Ivan Bollici';
      $this->contact = 'bollicivan@gmail.com';
      $this->url = 'i.bollici.it';
    }

    # Hooked functions runs when the event is triggered
    
      function hooks() {
        return array(
          'EVENT_MENU_MAIN'      => 'show_custom_menu',
        );
      }
    
      function config() {
        return array(
          'admin_own_threshold'   => DEVELOPER,
          'view_others_threshold' => MANAGER,
          'admin_threshold'       => ADMINISTRATOR,
         
        );
      }

      function show_custom_menu() {
        return array(
          array(
            'title' => 'plugin',
            'access_level' => plugin_config_get( 'admin_own_threshold' ),
            'url' => plugin_page( 'custom_plugin_page' ),
            'icon' => 'fa-random'
          )
        );
      }
    
   
 }
?>