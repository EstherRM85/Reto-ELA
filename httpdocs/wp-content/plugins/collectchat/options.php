<div class="wrap">
  <h2><?php _e( 'Collect.chat - Settings', 'collectchat'); ?> <a class="add-new-h2" target="_blank" href="<?php echo esc_url( "https://help.collect.chat/article/show/56885-add-to-wordpress" ); ?>"><?php _e( 'Read Tutorial', 'collectchat'); ?></a> <a class="add-new-h2" target="_blank" href="<?php echo esc_url( "https://www.youtube.com/watch?v=hUCeDRk5LhI" ); ?>"><?php _e( 'Watch Tutorial', 'collectchat'); ?></a></h2>

  <hr />
  <div id="poststuff">
  <div id="post-body" class="metabox-holder columns-2">
    <div id="post-body-content">
      <div class="postbox">
        <div class="inside">
          <form name="dofollow" action="options.php" method="post">

            <?php 
            settings_fields( 'collectchat-settings-group' ); 
            $settings = get_option( 'collectchat-plugin-settings' );          
            $script = (array_key_exists('script', $settings) ? $settings['script'] : '');
            $showOn = (array_key_exists('showOn', $settings) ? $settings['showOn'] : 'all');

            ?>

            <h3 class="cc-labels"><?php _e( 'Instructions: ', 'collectchat'); ?></h3>

            <p>1. <?php _e( 'If you are not an existing Collect.chat user, <a href="https://collect.chat/getstarted" target="_blank">Click here to register</a>', 'collectchat'); ?></p>

            <p>2. <?php _e( 'Design your Chatbot using <a href="https://collect.chat/bots" target="_blank">Drag & Drop Dashboard</a>', 'collectchat'); ?></p>

            <p>3. <?php _e( 'Copy the code snippet from Dashboard > Publish and paste it here', 'collectchat'); ?></p>
            <h3 class="cc-labels" for="script"><?php _e( 'Chatbot Snippet:', 'collectchat'); ?></h3>

            <textarea style="width:98%;" rows="10" cols="57" id="script" name="collectchat-plugin-settings[script]"><?php echo esc_html( $script ); ?></textarea>

            <p>
              <h3>Show Above Chatbot On: </h3>
              <input type="radio" name="collectchat-plugin-settings[showOn]"" value="all" id="all" <?php checked('all', $showOn); ?>> <label for="all"><?php _e( 'All Pages', 'collectchat'); ?> </label> 
              <input type="radio" name="collectchat-plugin-settings[showOn]"" value="home" id="home" <?php checked('home', $showOn); ?>> <label for="home"><?php _e( 'Homepage Only', 'collectchat'); ?> </label> 
              <input type="radio" name="collectchat-plugin-settings[showOn]"" value="nothome" id="nothome" <?php checked('nothome', $showOn); ?>> <label for="nothome"><?php _e( 'All Pages except Homepage', 'collectchat'); ?> </label>
              <input type="radio" name="collectchat-plugin-settings[showOn]"" value="none" id="none" <?php checked('none', $showOn); ?>> <label for="none"><?php _e( 'No Pages', 'collectchat'); ?> </label>
            </p>

            <p class="submit">
              <input class="button button-primary" type="submit" name="Submit" value="<?php _e( 'Save settings', 'collectchat'); ?>" />
            </p>
            <p><?php _e( 'Note: You can insert different bots to specific pages or posts from respective edit sections. <a href="https://help.collect.chat/article/show/76319-in-wordpress-how-can-i-add-a-different-chatbot-for-a-different-page" target="_blank">Learn more</a>', 'collectchat'); ?></p>

          </form>
        </div>
    </div>
    </div>

    <?php require_once(CC_PLUGIN_DIR . '/sidebar.php'); ?>
    </div>
  </div>
</div>
