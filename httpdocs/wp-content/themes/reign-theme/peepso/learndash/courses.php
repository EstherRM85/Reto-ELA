<?php
global $wbtm_reign_settings;
$mainbody_class  = '';
$header_position = isset( $wbtm_reign_settings[ 'reign_peepsoextender' ][ 'header_position' ] ) ? $wbtm_reign_settings[ 'reign_peepsoextender' ][ 'header_position' ] : 'inside';
$header_position = apply_filters( 'wbtm_rth_manage_header_position', $header_position );
if ( 'inside' !== $header_position ) {
    $mainbody_class = 'wb-grid';
}

global $wbcom_peepso_learndash_global_functions;
$user        = PeepSoUser::get_instance( $displayed_user );
$peepso_user = PeepSoUser::get_instance( PeepSoProfileShortcode::get_instance()->get_view_user_id() );
$options               = PeepSoConfigSettings::get_instance();
$course_column         = '';
$column_layout         = $options->get_option( 'peepso_ld_course_column' );
$featured_image_enable = $options->get_option( 'peepso_ld_profile_featured_image_enable' );
$empty_image_enable    = $options->get_option( 'peepso_ld_profile_featured_image_enable_if_empty' );
if ( $column_layout ) {
	$course_column = $column_layout .'-column';
} else {
	$course_column = 'single-column';
}
?>
<div class="peepso ps-page-profile">
	<?php PeepSoTemplate::exec_template( 'general', 'navbar' ); ?>
	<?php PeepSoTemplate::exec_template( 'profile', 'focus', array( 'current' => $courses_slug ) ); ?>
	<section id="mainbody" class="ps-page-unstyled <?php echo esc_attr( $mainbody_class ); ?>">
		<?php
        if ( 'inside' !== $header_position ) {
            do_action( 'wbcom_before_content_section' );
        }
        ?>
		<section id="component" role="article" class="clearfix">
			<div class="ps-tabs__wrapper">
                <div class="ps-tabs ps-tabs--arrows">
                	<?php 
                	foreach ( $submenus as $key => $value ) {
						?>
							<div class="ps-tabs__item <?php if( $key === $current_tab) echo 'current' ?>"><a href="<?php echo $user->get_profileurl(). $courses_slug . '/'. $value['href']; ?>"><?php echo esc_html( $value['label'] ); ?></a></div>
						<?php
						}
					?>                   
                </div>
            </div>
			<div class="peepso-learndash-wrapper">
				<?php
				$uid = $peepso_user->get_id();
				if ( 'view-certificates' === $current_tab ) {
					$my_courses = $wbcom_peepso_learndash_global_functions->peepso_ld_get_mycourses( $uid );
					$cer_count  = 0;
					if ( ! empty( $my_courses ) ) {
						?>
						<div class='peepso-ld-course-listing <?php echo $course_column; ?>'>
							<?php
							foreach ( $my_courses as $course ) :
								$certificate_link = learndash_get_course_certificate_link( $course->ID, $uid );
								$certificate_id   = learndash_get_setting( $course->ID, 'certificate' );
								if ( ! empty( $certificate_link ) ) {
								?>
								<div class='peepso-ld-course-info'>
									<div class="peepso-ld-course-thumbnail">
										<?php
										$certificate_thumb = get_the_post_thumbnail( $certificate_id );
										if ( $certificate_thumb ) {
										?>
												<a target="_blank" href="<?php echo esc_attr( $certificate_link ); ?>"><?php echo $certificate_thumb; ?></a>
										<?php
										} else {
												?>
												<a href="<?php echo esc_attr( $certificate_link ); ?>"><div class="certificate_icon_large"></div></a>
										<?php
										}
										?>
									</div>
									<div class="peepso-ld-course-info-details">
										<?php echo '<h3 class="peepso-ld-entry-title entry-title"><a href="' . esc_attr( get_permalink( $course->ID ) ) . '"  rel="bookmark">' . get_the_title( $course->ID ) . '</a></h3>'; ?>
												<?php
												do_action( 'peepso_ld_show_course_price', $course->ID, $my_courses );
												$total_students = learndash_course_progress(
													array(
														'array' => true,
														'course_id' => $course->ID,
														'user_id' => $uid,
													)
												);
										?>
									</div>
								</div>
							<?php
							$cer_count++;
								} endforeach;
							if ( 0 === $cer_count ) {
							?>
								<div class="ps-alert ps-alert-notice">
								<?php esc_html_e( 'No Certificate Found.', 'peepso-learndash' ); ?>
								</div>
							<?php } ?>
						</div>
						<?php
					} else {
					?>
						<div class="ps-alert ps-alert-notice">
								<?php esc_html_e( 'No Certificate Found.', 'peepso-learndash' ); ?>
						</div>
						<?php
					}
				} elseif ( 'instructing-' . $courses_slug === $current_tab ) {
					$excluded_courses_ids   = array();
					$ld_instructing_courses = $wbcom_peepso_learndash_global_functions->peepso_ld_get_instructing_courses( $uid );
					if ( $ld_instructing_courses ) {
					?>
					<div class='peepso-ld-course-listing <?php echo $course_column; ?>'>
						<?php foreach ( $ld_instructing_courses as $course ) :
							$thumbnail_html = get_the_post_thumbnail( $course->ID, array( 500, 300 ) );
							if ( ( $featured_image_enable && ! empty( $thumbnail_html ) ) || $empty_image_enable ) {
								$course_info_class = '';
							} else {
								$course_info_class = 'peepso-ld-course-info-full';
							}
						 ?>
							<div class='peepso-ld-course-info <?php echo $course_info_class; ?>'>
								<?php								
								if ( $featured_image_enable && !empty( $thumbnail_html ) ) {
								?>
									<div class="peepso-ld-course-thumbnail">
										<a href="<?php echo esc_url( get_permalink( $course->ID ) ); ?>" rel="bookmark">
											<?php
												echo $thumbnail_html;
											?>
											<div class="thumb-overlay"></div>
											<div class="thumb-overlay-cross"></div>
										</a>
									</div>
								<?php } elseif ( $empty_image_enable ) { ?>
									<div class="peepso-ld-course-thumbnail-empty">
									</div>
								<?php } ?>
								<div class="peepso-ld-course-info-details">
									<?php echo '<h3 class="peepso-ld-entry-title entry-title"><a href="' . esc_url( get_permalink( $course->ID ) ) . '"  rel="bookmark">' . get_the_title( $course->ID ) . '</a></h3>'; ?>
									<div class="peepso-lm-course-total-students">
										<?php
										do_action( 'peepso_ld_show_course_price', $course->ID, $ld_instructing_courses );
										$course_access_list = learndash_get_course_meta_setting( $course->ID, 'course_access_list' );
										?>
											<strong><?php esc_html_e( 'Total Students : ', 'peepso-learndash' ); ?></strong><span class="peepso-lm-value-result"><b><?php echo count( $course_access_list ); ?></b></span>
									</div>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
					<?php
					} else {
					?>
						<div class="ps-alert ps-alert-notice">
								<?php esc_html_e( 'No Courses Found.', 'peepso-learndash' ); ?>
						</div>
					<?php
					}
				} elseif ( 'my-quizzes' === $current_tab ) {
					$my_quizzes = $wbcom_peepso_learndash_global_functions->peepso_ld_get_myquizzes( $uid );
					if ( ! empty( $my_quizzes ) ) {
					?>
						<div class='peepso-ld-course-listing <?php echo $course_column; ?>'>
							<?php foreach ( $my_quizzes as $quiz => $quiz_data ) :
									$thumbnail_html = get_the_post_thumbnail( $quiz_data['quiz'], array( 500, 300 ) );
									if ( ( $featured_image_enable && ! empty( $thumbnail_html ) ) || $empty_image_enable ) {
										$course_info_class = '';
									} else {
										$course_info_class = 'peepso-ld-course-info-full';
									}
								?>
								<div class='peepso-ld-course-info <?php echo esc_attr( $course_info_class ); ?>'>
									<?php									
									if ( $featured_image_enable && !empty( $thumbnail_html ) ) { ?>
										<div class="peepso-ld-course-thumbnail">
											<a href="<?php echo esc_url( get_permalink( $quiz_data['quiz'] ) ); ?>" rel="bookmark">
												<?php
													echo $thumbnail_html;
												?>
												<div class="thumb-overlay"></div>
												<div class="thumb-overlay-cross"></div>
											</a>
										</div>
									<?php } elseif ( $empty_image_enable ) { ?>
										<div class="peepso-ld-course-thumbnail-empty">
										</div>
									<?php } ?>	
									<div class="peepso-ld-course-info-details">
										<?php echo '<h3 class="peepso-ld-entry-title entry-title">' . get_the_title( $quiz_data['quiz'] ) . '</h3>'; ?>
										<div class="peepso-lm-quiz-details">
											<div class="peepso-lm-detail-row">
												<label><?php esc_html_e( 'Total Points : ', 'peepso-learndash' ); ?><span class="peepso-lm-value-result"><b><?php echo esc_attr( $quiz_data['total_points'] ); ?></b></span></label>
											</div>
											<div class="peepso-lm-detail-row">
												<label><?php esc_html_e( 'Quiz Started : ', 'peepso-learndash' ); ?><span class="peepso-lm-value-result"><b><?php echo esc_attr( date("l jS \of F Y h:i:s A", $quiz_data['started'] ) ); ?></b></span></label>
											</div>
											<div class="peepso-lm-detail-row">
												<label><?php esc_html_e( 'Quiz Completed : ', 'peepso-learndash' ); ?><span class="peepso-lm-value-result"><b><?php echo esc_attr( date("l jS \of F Y h:i:s A", $quiz_data['completed'] ) ); ?></b></span></label>
											</div>
										</div>
									</div>
								</div>
							<?php endforeach; ?>
						</div>
					<?php
					} else {
					?>
						<div class="ps-alert ps-alert-notice">
								<?php esc_html_e( 'No Quiz Found.', 'peepso-learndash' ); ?>
						</div>
					<?php
					}
				} elseif ( 'my-assignments' === $current_tab ) {
					$ld_assignments = $wbcom_peepso_learndash_global_functions->peepso_ld_get_assignments( $uid );
					if ( ! empty( $ld_assignments ) ) {
				?>
						<div class='peepso-ld-course-listing <?php echo $course_column; ?>'>
							<?php
							foreach ( $ld_assignments as $ld_assignment ) {
								$course_id = get_post_meta( $ld_assignment->ID, 'course_id', true );
								$points    = get_post_meta( $ld_assignment->ID, 'points', true );
								$status    = get_post_meta( $ld_assignment->ID, 'approval_status', true );
							?>
								<div class='peepso-ld-course-info'>
										<div class="peepso-lm-quiz-details">
												<?php echo '<h3 class="peepso-ld-entry-title entry-title"><a href="' . esc_url( get_permalink( $ld_assignment->ID ) ) . '"  rel="bookmark">' . get_the_title( $ld_assignment->ID ) . '</a></h3>'; ?>
												<div class="peepso-lm-assignment-details">
													<div class="peepso-lm-detail-row">
														<label><?php esc_html_e( 'Related Course : ', 'peepso-learndash' ); ?><span class="peepso-lm-value-result"><b><a href="<?php echo esc_url( get_permalink( $course_id ) ); ?>"><?php echo get_the_title( $course_id ); ?></a></b></span></label>
													</div>
													<div class="peepso-lm-detail-row">
														<label><?php esc_html_e( 'Points : ', 'peepso-learndash' ); ?><span class="peepso-lm-value-result"><b><?php echo esc_attr( $points ); ?></b></span></label>
													</div>
													<div class="peepso-lm-detail-row">
														<label><?php esc_html_e( 'Approved : ', 'peepso-learndash' ); ?><span class="peepso-lm-value-result"><b><?php ( $status == 1 ) ? esc_html_e( 'Yes', 'peepso-learndash' ) : esc_html_e( 'No', 'peepso-learndash' ); ?></b></span></label>
													</div>
												</div>
										</div>
								</div>
							<?php } ?>
						</div>
						<?php
					} else { ?>
						<div class="ps-alert ps-alert-notice">
								<?php esc_html_e( 'No Assignment Found.', 'peepso-learndash' ); ?>
						</div>
					<?php }
				} else {
					$ld_courses = $wbcom_peepso_learndash_global_functions->peepso_ld_get_mycourses( $uid );
					if ( ! empty( $ld_courses ) ) {
					?>
					<div class='peepso-ld-course-listing <?php echo $course_column; ?>'>
						<?php foreach ( $ld_courses as $course ) :
								$meta = get_post_meta( $course->ID, '_sfwd-courses', true );
				                $course_content = @$meta['sfwd-courses_course_short_description'];

				                if(!strlen($course_content)) {
				                    $course_content = get_the_excerpt($course->ID);
				                }

				                if(!strlen($course_content)) {
				                    $course_content = $course->post_content;
				                }

				                $course_content = strip_shortcodes($course_content);

				                $limit = intval( apply_filters( 'peepso_ld_profile_content_length', 50) );
				                $course_content = wp_trim_words($course_content, $limit,'&hellip;');

				                if(0 == $limit) {
				                    $course_content = FALSE;
				                }

				                $thumbnail_html = get_the_post_thumbnail( $course->ID, array( 500, 300 ) );
								if ( ( $featured_image_enable && ! empty( $thumbnail_html ) ) || $empty_image_enable ) {
									$course_info_class = '';
								} else {
									$course_info_class = 'peepso-ld-course-info-full';
								}
							?>
							<div class='peepso-ld-course-info <?php echo esc_attr( $course_info_class ); ?>'>
								<?php								
								if ( $featured_image_enable && ! empty( $thumbnail_html ) ) { ?>
									<div class="peepso-ld-course-thumbnail">
										<a href="<?php echo esc_url( get_permalink( $course->ID ) ); ?>" rel="bookmark">
											<?php
												echo $thumbnail_html;
											?>
											<div class="thumb-overlay"></div>
											<div class="thumb-overlay-cross"></div>
										</a>
									</div>
								<?php } elseif ( $empty_image_enable ) { ?>
									<div class="peepso-ld-course-thumbnail-empty">
									</div>
								<?php } ?>
								<div class="peepso-ld-course-info-details">
									<?php echo '<h3 class="peepso-ld-entry-title entry-title"><a href="' . esc_url( get_permalink( $course->ID ) ) . '"  rel="bookmark">' . get_the_title( $course->ID ) . '</a></h3>'; ?>
										<?php
										do_action( 'peepso_ld_show_course_price', $course->ID, $ld_courses );
											$ld_course_progress = learndash_course_progress(
												array(
													'array' => true,
													'course_id' => $course->ID,
													'user_id' => $peepso_user->get_id(),
												)
											);
									if ( $course_content ) { ?>
										<div class="peepso-lm-course-description">
											<?php echo $course_content; ?>
										</div>
									<?php } ?>
									<div class="peepso-lm-course-progress-bar">
										<label><?php esc_html_e( 'Learning Progress : ', 'peepso-learndash' ); ?><span class="peepso-lm-value-result"><b><?php echo esc_attr( $ld_course_progress['percentage'] ); ?>%</b></span></label>
										<div class="peepso-lm-value">
											<?php
												echo do_shortcode( "[learndash_course_progress course_id=$course->ID user_id=$uid]" );
											?>
										</div>
									</div>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
				<?php
					} else {
				?>
						<div class="ps-alert ps-alert-notice">
								<?php esc_html_e( 'No Courses Found.', 'peepso-learndash' ); ?>
						</div>
				<?php
					}
				}
				?>
			</div>
		</section><!--end component-->
        <?php
        if ( 'inside' !== $header_position ) {
            do_action( 'wbcom_after_content_section' );
        }
        ?>
	</section><!--end mainbody-->
</div><!--end row-->