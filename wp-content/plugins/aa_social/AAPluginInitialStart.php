<?php

/**
 * Class AAPluginInitialStart
 */
class AAPluginInitialStart {

	protected $_plugin_name;

	protected $_page_title;
	protected $_menu_title;
	protected $_capability = 'manage_options';
	protected $_menu_slug;
	protected $_positon;
	protected $_notices_option;
	protected $_plugin_options;

	/**
	 * @param string $name
	 * @param null $pagetitle
	 * @param null $menutitle
	 * @param null $menuslug
	 * @param string $position
	 */
	public function __construct( $name = "AA Plugin", $pagetitle = null, $menutitle = null, $menuslug = null, $position = '58' )
	{
		$this->_plugin_name    = $name;
		$this->_page_title     = $pagetitle !== null ? $pagetitle : $this->_plugin_name;
		$this->_menu_title     = $menutitle !== null ? $menutitle : $this->_plugin_name;
		$this->_menu_slug      = $menuslug !== null ? $menuslug : str_replace( " ", "_", strtolower( $this->_plugin_name ) );
		$this->_positon        = $position;
		$this->_notices_option = "aa_" . $this->_menu_slug . "_notices_options";
		$this->_plugin_options = "aa_" . $this->_menu_slug . "_plugin_options";

		// Admin Script
		add_action( 'admin_enqueue_scripts', array( $this, 'registerPluginEnqueScript' ) );
		// Front Script
		add_action( 'wp_enqueue_scripts', array( $this, 'registerPluginEnqueScript' ) );
		add_action( 'admin_menu', array( $this, 'createAdminPage' ) );

		// Set plugin notices
		$notices = get_option( $this->_notices_option );
		if ( ! $notices )
			add_option( $this->_notices_option, array() );

		$plugin_options = get_option( $this->_plugin_options );
		if ( ! $plugin_options )
			add_option( $this->_plugin_options, array() );
	}

	public function getOptions( $option = null )
	{
		if ( get_option( $this->_plugin_options ) ) {
			if ( $option !== null ) {
				$op = get_option( $this->_plugin_options );
				return $op[ $option ];
			}

			return get_option( $this->_plugin_options );
		}

		return false;
	}

	public function setOption( $option_key, $option_value )
	{
		$option = get_option( $this->_plugin_options );

		if ( get_option( $this->_plugin_options ) !== false ) {
			$option[ $option_key ] = $option_value;
			update_option( $this->_plugin_options, $option );
		}

	}

	public function getNotices( $notice = null )
	{
		if ( get_option( $this->_notices_option ) ) {
			if ( $notice !== null ){
				$n = get_option( $this->_notices_option );
				return $n[ $notice ];
			}

			return get_option( $this->_notices_option );
		}

		return false;
	}

	/**
	 * Add notices
	 * aa_aa_plugin_notices_options
	 */
	public function setPluginNotice( $notice = 'some_notice', $message = 'Some message', $type = 'updated' )
	{
		$get_noticesinfo = get_option( $this->_notices_option );
		$new_option      = array();

		// If notice not exists set it for all users
		if ( ! array_key_exists( $notice, $get_noticesinfo ) ) {
			$new_option[ $notice ] = array(
				'message'        => $message,
				'excluded_users' => array(),
				'type'           => $type
			);

			$get_noticesinfo = array_merge( $get_noticesinfo, $new_option );
			update_option( $this->_notices_option, $get_noticesinfo );
		}

		add_action( 'admin_notices', array( $this, 'renderNotices' ), 2 );
	}

	/**
	 * Render notices
	 */
	public function renderNotices()
	{
		$notices      = get_option( $this->_notices_option );
		$current_user = get_current_user_id();

		foreach ( $notices as $notice_key => $notice_val ) {
			if ( array_search( $current_user, $notice_val[ 'excluded_users' ] ) === false ) {
				$attributes = "data-current-user='{$current_user}' id='{$notice_key}' data-plugin-notice={$this->_notices_option}";
				$class      = "{$notice_val[ 'type' ]} {$this->_notices_option}-notice notice is-dismissible aa-plugin-notice-container";
				?>
				<div <?php echo $attributes ?> class="<?php echo $class ?>">
					<p><?php echo $notice_val[ 'message' ] ?></p>
				</div>
				<?php
			}
		}
	}

	public function reactivateNotice( $notice )
	{
		//@Template Todo: reactivate notice for current user
	}

	/**
	 * Register style and script files
	 */
	public function registerPluginEnqueScript()
	{
		$plugindir = plugin_dir_url( __DIR__ ) . basename( __DIR__ );
		wp_enqueue_style( 'AAPluginStyle'.$this->_menu_slug, $plugindir . '/style/style.css' );
		wp_enqueue_script( 'AAPluginScript'.$this->_menu_slug, $plugindir . '/js/script.js', array( 'jquery' ), false, true );

		$data = array(
			'site_url'     => get_site_url(),
			'ajax_url'     => admin_url( 'admin-ajax.php' ),
			'template_uri' => get_template_directory_uri(),
		);
		wp_localize_script( 'AAPluginScript'.$this->_menu_slug, 'aa_ajax_var', $data );
	}

	/**
	 * Initialize admin page
	 */
	public function createAdminPage()
	{
		add_menu_page(
			$this->_page_title,
			$this->_menu_title,
			$this->_capability,
			$this->_menu_slug,
			array( $this, 'renderListing' ), '', $this->_positon
		);
		do_action( 'do_the_creation_page' );
	}

	//@Template Todo: Think about connect subpage to exemplar object of class
	public function addSubpage( $page_title )
	{
		$filtered_title = str_replace( " ", "_", strtolower( $page_title ) );
		add_submenu_page(
			$this->_menu_slug,
			$page_title,
			$page_title,
			$this->_capability,
			$filtered_title,
			array( $this, 'renderListing' )
		);
	}

	/**
	 * Render base page
	 */
	public function renderListing()
	{
		include( 'views/default_view.php' );
	}

	/**
	 * Getting table info for generate fields
	 *
	 * @param $table_name
	 *
	 * @return mixed
	 */
	public static function describe( $table_name )
	{
		global $wpdb;
		$table = $wpdb->prefix . $table_name;

		return $wpdb->get_results( "DESCRIBE $table", ARRAY_A );
	}

	/**
	 * Get saved data from table
	 * @return bool|mixed
	 */
	public static function tableData( $table_name )
	{
		global $wpdb;
		$table  = $wpdb->prefix . $table_name;
		$result = $wpdb->get_results( "SELECT * from $table", ARRAY_A );
		if ( ! empty( $result ) ) {
			return $result;
		}

		return false;
	}

	/**
	 * Generator tables
	 *
	 * @param $table_name
	 * @param $args
	 * @param null $primary
	 */
	public static function createTable( $table_name, $args, $primary = null )
	{
		global $wpdb;
		$table_name         = $wpdb->prefix . $table_name;
		$combine_query      = "";
		$primary_key_exists = $primary ? "id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, " : null;

		foreach ( $args as $field ) {
			$var_val = strpos( $field[ 1 ], 'varchar' ) > - 1 ? '(255)' : '';
			$combine_query .= $field[ 0 ] . " " . strtoupper( $field[ 1 ] ) . $var_val . " NOT NULL, ";
		}

		$q = "CREATE TABLE IF NOT EXISTS $table_name ( " . $primary_key_exists . substr( $combine_query, 0, - 2 ) . ")
			ENGINE = InnoDB CHARACTER SET utf8 COLLATE utf8_general_ci;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $q );
	}

	public static function isLocalhost()
	{
		return ( $_SERVER[ 'REMOTE_ADDR' ] === '127.0.0.1' || $_SERVER[ 'REMOTE_ADDR' ] === 'localhost' ) ? 1 : 0;
	}
}