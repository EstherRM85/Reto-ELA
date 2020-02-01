<?php
global $wbtm_reign_settings;
$mainbody_class  = '';
$header_position = isset( $wbtm_reign_settings[ 'reign_peepsoextender' ][ 'header_position' ] ) ? $wbtm_reign_settings[ 'reign_peepsoextender' ][ 'header_position' ] : 'inside';
$header_position = apply_filters( 'wbtm_rth_manage_header_position', $header_position );
if ( 'inside' !== $header_position ) {
  $mainbody_class = 'wb-grid';
}
$user = PeepSoUser::get_instance(PeepSoProfileShortcode::get_instance()->get_view_user_id());

$can_edit = FALSE;
if($user->get_id() == get_current_user_id() || current_user_can('edit_users')) {
  $can_edit = TRUE;
}

if(!$can_edit) {
  PeepSo::redirect(PeepSo::get_page('activity'));
} else {

  $PeepSoProfile = PeepSoProfile::get_instance();
  ?>

  <div class="peepso ps-page-profile">
    <?php PeepSoTemplate::exec_template('general', 'navbar'); ?>

    <?php PeepSoTemplate::exec_template('profile', 'focus', array('current'=>'about')); ?>

    <section id="mainbody" class="ps-page-unstyled <?php echo esc_attr( $mainbody_class ); ?>">
      <?php
      if ( 'inside' !== $header_position ) {
        do_action( 'wbcom_before_content_section' );
      }
      ?>
      <section id="component" role="article" class="ps-clearfix">


        <?php if($can_edit) { PeepSoTemplate::exec_template('profile', 'profile-about-tabs', array('tabs' => $tabs, 'current_tab'=>'account'));} ?>

        <div class="ps-form ps-js-profile-list">
          <div class="ps-form__container">
            <?php if (strlen($PeepSoProfile->edit_form_message())) { ?>
              <div class="ps-alert ps-alert-success">
                <?php echo $PeepSoProfile->edit_form_message(); ?>
              </div>
            <?php } ?>

            <div class="ps-form__separator"><?php _e('Your Account', 'peepso-core'); ?></div>

            <?php $PeepSoProfile->edit_form(); ?>

            <div class="ps-form__row">
              <div class="ps-form__field">
                <?php _e('Fields marked with an asterisk (<span class="required-sign">*</span>) are required.', 'peepso-core'); ?>
              </div>
            </div>

            <?php if(PeepSo::get_option('site_registration_allowdelete', 0)) { ?>
              <div class="ps-form__separator"><?php _e('Profile Deletion', 'peepso-core'); ?></div>

              <div class="ps-form__row">
                <p><?php _e('Deleting your account will disable your profile and remove your name and photo from most things you\'ve shared. Some information may still be visible to others, such as your name in their friends list and messages you sent.', 'peepso-core'); ?></p>

                <?php $PeepSoProfile->delete_form(); ?>
              </div>
            <?php } ?>

            <?php if(PeepSo::get_option('gdpr_enable', 1)) { ?>
              <div class="ps-form__separator"><?php _e('Export Your Community Data', 'peepso-core'); ?></div>
              <div class="ps-form__row">
                <?php $PeepSoProfile->request_data_form(); ?>
              </div>
            <?php } ?>
          </div>
        </div>
      </section><!--end component-->
      <?php
      if ( 'inside' !== $header_position ) {
        do_action( 'wbcom_after_content_section' );
      }
      ?>
    </section><!--end mainbody-->
  </div><!--end row-->
<?php }