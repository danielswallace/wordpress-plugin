<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://github.com/danielswallace
 * @since      1.0.0
 *
 * @package    Wordpress_Plugin
 * @subpackage Wordpress_Plugin/admin/partials
 */

 include_once 'includes/user_list_table.php';

 $user_list_table = new User_List_Table();
?>

<div class="wrap">
<div id="icon-users" class="icon32"></div>
<h2>Response List</h2>
<?php
            $user_list_table->prepare_items();
            $user_list_table->display(); ?>
</div>



<!-- This file should primarily consist of HTML with a little bit of PHP. -->
