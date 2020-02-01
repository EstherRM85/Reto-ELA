<?php

echo $args['before_widget'];

$PeepSoProfile=PeepSoProfile::get_instance();
$PeepSoUser = $PeepSoProfile->user;

?>

  <div class="ps-widget--profile__wrapper ps-widget--external">
    <!-- Title of Profile Widget -->
    <?php
    if ( ! empty( $instance['title'] ) ) {
      echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ). $args['after_title'];
    }
    ?>

    <div class="ps-widget--profile">

    <?php
    if($instance['user_id'] >0)
    {
      $user  = $instance['user'];

      $cover         = NULL;
      $size          = 750;
      $cover_hash    = get_user_meta( $user->get_id(), 'peepso_cover_hash', TRUE);

      if ( $cover_hash ) {
            $cover_hash = $cover_hash . '-';
      }
      $filename = $cover_hash . 'cover.jpg';
      if(file_exists($user->get_image_dir() . $filename)) {
            $cover = $user->get_image_url() . $filename;
            if (is_int($size) && $size > 0) {
                $filename_scaled = $cover_hash . 'cover-' . $size . '.jpg';
                if (!file_exists($user->get_image_dir() . $filename_scaled)) {
                    $si = new PeepSoSimpleImage();
                    $si->png_to_jpeg($user->get_image_dir() . $filename);
                    $si->load($user->get_image_dir() . $filename);
                    $si->resizeToWidth($size);
                    $si->save($user->get_image_dir() . $filename_scaled, IMAGETYPE_JPEG);
                }

                $cover = $user->get_image_url() . $filename_scaled;
            }
      }

      if ( empty( $cover ) ) {
        $cover = wbtm_render_peepso_member_cover_image();
      }

      if($instance['user_id'] > 0 && $instance['user_id'] == get_current_user_id()) {
        $user->profile_fields->load_fields();
        $stats = $user->profile_fields->profile_fields_stats;
      }

      if(isset($instance['show_cover']) && 1 == intval($instance['show_cover'])) {
      ?>
      <div class="ps-widget--profile__cover">
        <div class="ps-widget--profile__cover-image" style="background:url(<?php echo $cover; ?>) no-repeat center center;"></div>
        <a class="ps-widget--profile__cover-wrapper" href="<?php echo $user->get_profileurl();?>">
          <div class="ps-widget--profile__cover-header">
            <!-- Avatar -->
            <div class="ps-widget--profile__cover-avatar">
              <div class="ps-avatar ps-avatar--widget" href="<?php echo $user->get_profileurl();?>">
                <img alt="<?php echo $user->get_fullname();?> avatar" title="<?php echo $user->get_profileurl();?>" src="<?php echo $user->get_avatar();?>">
              </div>
            </div>

            <!-- Name, edit profile -->
            <div class="ps-widget--profile__cover-details" data-hover-card="<?php echo $user->get_id() ?>">
              <?php
              //[peepso]_[action]_[WHICH_PLUGIN]_[WHERE]_[WHAT]_[BEFORE/AFTER]
              do_action('peepso_action_render_user_name_before', $user->get_id());

              echo $user->get_fullname();

              //[peepso]_[action]_[WHICH_PLUGIN]_[WHERE]_[WHAT]_[BEFORE/AFTER]
              do_action('peepso_action_render_user_name_after', $user->get_id());
              ?>
            </div>
          </div>
        </a>
        <div class="ps-widget--profile__cover-notif">
          <?php echo $instance['toolbar']; ?>
        </div>
      </div>
      <?php } else { ?>

      <div class="ps-widget--profile__header">
        <!-- Avatar -->
        <div class="ps-widget--profile__avatar">
          <a class="ps-avatar" href="<?php echo $user->get_profileurl();?>">
            <img alt="<?php echo $user->get_fullname();?> avatar" title="<?php echo $user->get_profileurl();?>" src="<?php echo $user->get_avatar();?>">
          </a>
        </div>

        <!-- Name, edit profile -->
        <div class="ps-widget--profile__details">
          <a class="ps-user-name" href="<?php echo $user->get_profileurl();?>">
            <?php
            //[peepso]_[action]_[WHICH_PLUGIN]_[WHERE]_[WHAT]_[BEFORE/AFTER]
            do_action('peepso_action_render_user_name_before', $user->get_id());

            echo $user->get_fullname();

            //[peepso]_[action]_[WHICH_PLUGIN]_[WHERE]_[WHAT]_[BEFORE/AFTER]
            do_action('peepso_action_render_user_name_after', $user->get_id());
            ?>
          </a>

          <?php echo $instance['toolbar']; ?>
        </div>
      </div>
      <?php
      }
      //[peepso]_[action]_[WHICH_PLUGIN]_[WHERE]_[WHAT]_[BEFORE/AFTER]
      do_action('peepso_action_widget_profile_name_after', $instance['user_id']);
      ?>

      <!-- Profile Completeness -->
      <?php

      if(isset($stats) && $stats['fields_all'] > 0) :

        $style = '';
        if ($stats['completeness'] >= 100) {
          $style.='display:none;';
        }
        ?>
        <div class="ps-progress-status ps-completeness-status" style="<?php echo $style;?>">
          <?php
            echo $stats['completeness_message'];
            do_action('peepso_action_render_profile_completeness_message_after', $stats);
          ?>
        </div>
        <div class="ps-progress-bar ps-completeness-bar" style="<?php echo $style;?>">
          <span style="width:<?php echo $stats['completeness'];?>%"></span>
        </div>

      <?php endif; ?>

      <!-- Profile Links -->
      <span class="ps-widget--profile__title"><?php _e('My Profile', 'peepso-core'); ?></span>
      <div class="ps-widget--profile__menu">
        <?php

        // Profile Submenu extra links
        $instance['links']['peepso-core-preferences'] = array(
          'href' => $user->get_profileurl() . 'about/preferences/',
          'icon' => 'ps-icon-edit',
          'label' => __('Preferences', 'peepso-core'),
        );

        // @todo #2274 this has to be peepso_navigation_profile
//                if(class_exists('PeepSoPMP')) {
//                    $instance['links']['peepso-pmp'] = array(
//                        'href' => pmpro_url("account"),
//                        'label' => __('Membership', 'peepso-pmp'),
//                        'icon' => 'ps-icon-vcard',
//                    );
//                }

        $instance['links']['peepso-core-logout'] = array(
          'href' => PeepSo::get_page('logout'),
          'icon' => 'ps-icon-off',
          'label' => __('Log Out', 'peepso-core'),
          'widget'=>TRUE,
        );

        if (isset($instance['show_community_links']) && $instance['show_community_links'] === 1) {
          $instance['community_links']['peepso-core-logout'] = $instance['links']['peepso-core-logout'];
          unset($instance['links']['peepso-core-logout']);
        }

        foreach($instance['links'] as $id => $link)
        {
          if(!isset($link['label']) || !isset($link['href']) || !isset($link['icon'])) {
            var_dump($link);
          }

          $class = isset($link['class']) ? $link['class'] : '' ;

          $href = $user->get_profileurl(). $link['href'];
          if('http' == substr(strtolower($link['href']), 0,4)) {
            $href = $link['href'];
          }

          echo '<a href="' . $href . '" class="' . $class . '"><span class="' . $link['icon'] . '"></span> ' . $link['label'] . '</a>';
        }
        ?>
      </div>

      <?php if (isset($instance['show_community_links']) && $instance['show_community_links'] === 1) { ?>
        <!-- Community Links -->
        <span class="ps-widget--profile__title"><?php _e('Community', 'peepso-core'); ?></span>
        <div class="ps-widget--profile__menu">
          <?php
          foreach($instance['community_links'] as $link)
          {
              if(FALSE == $link['widget'] ) {
                continue;
              }

              $class = isset($link['class']) ? $link['class'] : '' ;
              echo '<a href="' . $link['href'] . '" class="' . $class . '"><span class="' . $link['icon'] . '"></span> ' . $link['label'] . '</a>';

          }
          ?>
        </div>
        <?php
        }
      } else {
      ?>
      <form class="ps-form ps-form--login ps-form--login-widget" action="" onsubmit="return false;" method="post" name="login" id="form-login-me">
        <div class="ps-form__container">
          <div class="ps-form__row">
            <div class="ps-form__field ps-form__field--group">
              <div class="ps-input__prepend">
                <i class="ps-icon-user"></i>
              </div>
              <input class="ps-input ps-full" type="text" name="username" placeholder="<?php _e('Username', 'peepso-core'); ?>" mouseev="true"
                 autocomplete="off" keyev="true" clickev="true" />
            </div>
          </div>

          <div class="ps-form__row">
            <div class="ps-form__field ps-form__field--group">
              <div class="ps-input__prepend">
                <i class="ps-icon-lock"></i>
              </div>
              <input class="ps-input ps-full" type="password" name="password" placeholder="<?php _e('Password', 'peepso-core'); ?>" mouseev="true"
                 autocomplete="off" keyev="true" clickev="true" />
            </div>
          </div>

          <?php include_once( ABSPATH . 'wp-admin/includes/plugin.php' ); ?>
          <?php if( is_plugin_active('two-factor-authentication/two-factor-login.php') ) { ?>
          <div class="ps-form__row">
            <div class="ps-form__field ps-form__field--group">
              <div class="ps-input__prepend">
                <i class="ps-icon-clock"></i>
              </div>
              <input class="ps-input ps-full" type="text" name="two_factor_code" placeholder="<?php _e('TFA code', 'peepso-core'); ?>" mouseev="true"
                 autocomplete="off" keyev="true" clickev="true" data-ps-extra="1" />
            </div>
          </div>
          <?php } ?>

          <div class="ps-form__row">
            <div class="ps-form__field">
              <div class="ps-checkbox">
                <input type="checkbox" alt="<?php _e('Remember Me', 'peepso-core'); ?>" value="yes" name="remember" id="remember2" <?php echo PeepSo::get_option('site_frontpage_rememberme_default', 0) ? ' checked':'';?>>
                <label for="remember2"><?php _e('Remember Me', 'peepso-core'); ?></label>
              </div>
            </div>
          </div>

          <?php
          $disable_registration = intval(PeepSo::get_option('site_registration_disabled', 0));

          // PeepSo/peepso#2906 hide "resend activation" until really necessary
          $hide_resend_activation = TRUE;
          ?>

          <?php if(0 === $disable_registration) { ?>
          <div class="ps-form__row">
            <div class="ps-form__field">
              <a class="ps-link ps-link--register" href="<?php echo PeepSo::get_page('register'); ?>"><?php _e('Register', 'peepso-core'); ?></a>
            </div>
          </div>
          <?php } ?>

          <div class="ps-form__row">
            <div class="ps-form__field">
              <a class="ps-link ps-link--recover" href="<?php echo PeepSo::get_page('recover'); ?>"><?php _e('Forgot Password', 'peepso-core'); ?></a>
            </div>
          </div>

          <?php if(0 === $disable_registration) { ?>
          <div class="ps-form__row ps-js-register-activation" style="display: none;">
            <div class="ps-form__field">
              <a class="ps-link ps-link--activation" href="<?php echo PeepSo::get_page('register'); ?>?resend"><?php _e('Resend activation code', 'peepso-core'); ?></a>
            </div>
          </div>
          <?php } ?>

          <div class="ps-form__row">
            <div class="ps-form__field ps-form__field--submit">
              <button type="submit" class="ps-btn ps-btn-login">
                <span><?php _e('Login', 'peepso-core'); ?></span>
                <img style="display:none" src="<?php echo PeepSo::get_asset('images/ajax-loader.gif'); ?>">
              </button>
            </div>
          </div>
        </div>

        <input type="hidden" name="option" value="ps_users">
        <input type="hidden" name="task" value="-user-login">
        <input type="hidden" name="redirect_to" value="<?php echo PeepSo::get_page('redirectlogin'); ?>" />
        <?php
          // Remove ID attribute from nonce field.
          $nonce = wp_nonce_field('ajax-login-nonce', 'security', true, false);
          $nonce = preg_replace( '/\sid="[^"]+"/', '', $nonce );
          echo $nonce;
        ?>

        <?php do_action('peepso_action_render_login_form_after'); ?>
      </form>
      <?php do_action('peepso_after_login_form'); ?>

      <script>
        (function() {
          function initLoginForm( $ ) {
            $('.ps-form--login-widget').off('submit').on('submit', function( e ) {
              e.preventDefault();
              e.stopPropagation();
              peepso.login.submit( e.target );
            });
          }

          // naively check if jQuery exist to prevent error
          var timer = setInterval(function() {
            if ( window.jQuery ) {
              clearInterval( timer );
              initLoginForm( window.jQuery );
            }
          }, 1000 );
        })();
      </script>

      <?php
      }
      ?>

    </div>
  </div>

<?php
echo $args['after_widget'];
// EOF
