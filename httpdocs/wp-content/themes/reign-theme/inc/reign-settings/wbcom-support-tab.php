<?php
if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( !class_exists( 'Reign_Wbcom_Support_Tab' ) ) :

	/**
	 * @class Reign_Wbcom_Support_Tab
	 */
	class Reign_Wbcom_Support_Tab {

		/**
		 * The single instance of the class.
		 *
		 * @var Reign_Wbcom_Support_Tab
		 */
		protected static $_instance	 = null;
		protected static $_slug		 = 'wbcom-support';

		/**
		 * Main Reign_Wbcom_Support_Tab Instance.
		 *
		 * Ensures only one instance of Reign_Wbcom_Support_Tab is loaded or can be loaded.
		 *
		 * @return Reign_Wbcom_Support_Tab - Main instance.
		 */
		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		/**
		 * Reign_Wbcom_Support_Tab Constructor.
		 */
		public function __construct() {
			$this->init_hooks();
		}

		/**
		 * Hook into actions and filters.
		 */
		private function init_hooks() {
			add_filter( 'alter_reign_admin_tabs', array( $this, 'alter_reign_admin_tabs' ), 50, 1 );
			add_action( 'render_content_after_form', array( $this, 'render_get_started_with_customization_section' ), 10, 1 );

			add_action( 'admin_menu', array( $this, 'add_reign_setting_submenu' ), 50 );

		}

		public function add_reign_setting_submenu() {
			add_submenu_page(
				'reign-settings',
				__( 'Support', 'reign' ),
				__( 'Support', 'reign' ),
				'manage_options',
				admin_url( 'admin.php?page=reign-options&tab=' . self::$_slug )
			);
		}

		public function alter_reign_admin_tabs( $tabs ) {
			$tabs[ self::$_slug ] = __( 'Support', 'reign' );
			return $tabs;
		}

		public function render_get_started_with_customization_section( $tab ) {
			if( $tab != self::$_slug ) { return; }
			?>
			<style type="text/css">
				div#poststuff {
					display: none;
				}

				.reign_support_faq{
					background: #fff;
    				padding: 40px;
    				overflow: hidden;
				}

				.reign_support_faq .panel_left{
					float: left;
					width: 65%;
				}	

				.reign_support_faq ul.anchor-nav{
					margin:0;
				    background: #F8F8F8;
				    padding: 5% 5% 5% 5%;
				    font-size: 16px;
				    line-height: 1.8;
				    list-style: none;
				}

				.reign_support_faq ul.anchor-nav li{
					border-bottom: dotted 1px #ddd;  
				    padding: 20px 0;
				}	
				.reign_support_faq ul.anchor-nav li:first-child{
					padding-top: 0;
				}
				.reign_support_faq ul.anchor-nav li:last-child{
					padding-right: 0;
				}

				.reign_support_faq ul.anchor-nav li a{
					text-decoration: none;
				}
				.reign_support_faq p{
					font-size: 16px;
				}

				.reign_support_faq h2{
					font-size: 18px;
					line-height: 1.3em;
					margin:0;
				}	
				.reign_support_faq .support_link_Section{
					position: relative;
					padding-top: 40px;
					border-bottom: 2px #f1f1f1 solid;
					padding-bottom: 40px;
				}

				.reign_support_faq .panel-right{
					float: right;
					width: 30%;
				}
				.reign_support_faq .panel-right img{
					max-width: 100%;
				}

				@media (max-width: 769px) {
				.reign_support_faq .panel-right, .reign_support_faq .panel_left{
					width: 100%;
					}
				}





			</style>
			
			<script type="text/javascript">
				
								// Select all links with hashes
				$(document).ready(function(){
				  // Add smooth scrolling to all links
				  $("a").on('click', function(event) {

				    // Make sure this.hash has a value before overriding default behavior
				    if (this.hash !== "") {
				      // Prevent default anchor click behavior
				      event.preventDefault();

				      // Store hash
				      var hash = this.hash;

				      // Using jQuery's animate() method to add smooth page scroll
				      // The optional number (800) specifies the number of milliseconds it takes to scroll to the specified area
				      $('html, body').animate({
				        scrollTop: $(hash).offset().top
				      }, 800, function(){
				   
				        // Add hash (#) to URL when done scrolling (default click behavior)
				        window.location.hash = hash;
				      });
				    } // End if
				  });
				});
			</script>


			<div class="reign_support_faq">
				<div class="panel_left">
					<ul id="top" class="anchor-nav">
						<li><a href="#faq-demo"><?php _e( 'How to import demo data?', 'reign' ); ?></a></li>
						<li><a href="#faq-terms"><?php _e( 'Terms and conditions?', 'reign' ); ?></a></li>
						<li><a href="#faq-custom"><?php _e( 'Need help with custom development?', 'reign' ); ?></a></li>
						<li><a href="#faq-form"><?php _e( 'Contact us on support forum?', 'reign' ); ?></a></li>
					</ul>

					<div id="faq-demo" class="support_link_Section">
						<h2><?php _e( 'How to import demo data?', 'reign' ); ?></h2>
						<p><?php _e( 'Reign comes with the support of "Wbcom Demo Importer" Plugin to make importing demo data a better experience than ever.', 'reign' ); ?></p>
						<p><?php _e( 'Watch the video below for getting quick idea.', 'reign' ); ?></p>
						<iframe width="560" height="315" src="https://www.youtube.com/embed/QDky6rOxLnk" frameborder="0" allowfullscreen=""></iframe>
					</div>

					<div id="faq-terms" class="support_link_Section">
						<h2><?php _e( 'Terms and conditions?', 'reign' ); ?></h2>
						<p><?php _e( 'Please refer the following link to read our Terms And Conditions.', 'reign' ); ?></p>
						<p><a href="https://wbcomdesigns.com/terms-and-conditions/" target="_blank"><?php _e( 'Terms And Conditions', 'reign' ); ?></a></p>
					</div>

					<div id="faq-custom" class="support_link_Section">
						<h2><?php _e( 'Need help with custom development?', 'reign' ); ?></h2>
						<p><?php _e( 'In case you need additional help you can contact us for Custom Development. Our team will love to help you.', 'reign' ); ?></p>
						<p><a href="https://wbcomdesigns.com/contact/" target="_blank" title="Custom Development by Wbcom Designs"><?php _e( 'Go for custom development', 'reign' ); ?></a></p>
					</div>

					<div id="faq-form" class="support_link_Section">
						<h2><?php _e( 'Contact us on support forum?', 'reign' ); ?></h2>
						<p><?php _e( 'In case you have any question, you can visit our support desk.', 'reign' ); ?></p>
						<p><a href="https://support.wbcomdesigns.com/" target="_blank" title="Custom Development by Wbcom Designs"><?php _e( 'Get support', 'reign' ); ?></a></p>
					</div>

				</div>


				<div class="panel-right">
					<img src="<?php echo get_template_directory_uri(); ?>/inc/reign-settings/imgs/reign-img.jpg" alt="Support Reign"> 
				</div>
			</div>


			<?php
		}

	}

	endif;

/**
 * Main instance of Reign_Wbcom_Support_Tab.
 * @return Reign_Wbcom_Support_Tab
 */
Reign_Wbcom_Support_Tab::instance();