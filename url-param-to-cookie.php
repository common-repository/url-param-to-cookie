<?php
/**
 * Plugin Name: URL Param to Cookie Plugin
 * Plugin URI: 
 * Description: Reads specified param from url and stores it in cookie.
 * Version: 1.0
 * Author: Shaheed Abdol
 * Author URI: www.shaheedabdol.co.za
 */

 class UrlParamToCookie {
	public $PARAM_NAME = '';
	public $COOKIE_NAME = '';
	public $PATH = '/';
	public $DOMAIN = '';
	public $VALIDITY = 0;

	public function __construct () {
		$options = get_option('url_param_options');
		if (isset($options['param_name'])) {
			$this->PARAM_NAME = $options['param_name'];
			$this->COOKIE_NAME = $this->PARAM_NAME;
		}
		if (isset($options['cookie_name'])) {
			if (!empty($options['cookie_name'])) $this->COOKIE_NAME = $options['cookie_name'];
		}
		if (isset($options['path'])) {
			if (!empty($options['path'])) $this->PATH = $options['path'];
		}
		if (isset($options['domain'])) {
			$this->DOMAIN = $options['domain'];
		}
		if (isset($options['validity'])) {
			try {
				$this->VALIDITY = intval($options['validity']);
			} catch (Exception $e) {
				error_log('Error while parsing integer value for session expiry' . print_r($e, true));
				$this->VALIDITY = 0;
			}
		}
		add_action('init', array($this, 'handle_request'));
	}

	public function handle_request($wp) {
		if (!isset($this->PARAM_NAME)) { return; }
		if (isset($_REQUEST[$this->PARAM_NAME])) {
			$value = esc_attr($_REQUEST[$this->PARAM_NAME]);

			if (!empty($value)) {
				if (session_status() != PHP_SESSION_ACTIVE) { session_start(); }
				$COOKIE[$this->PARAM_NAME] = $value;
				setcookie($this->COOKIE_NAME, $value, ($this->VALIDITY > 0 ? time() + $this->VALIDITY : 0), $this->PATH, $this->DOMAIN);
			}
		}
	}
}

class UrlParamToCookieOptionsPage {
	private $options;

	public function __construct() {
		add_action('admin_menu', array($this, 'add_plugin_page'));
		add_action('admin_init', array($this, 'page_init'));
	}

	public function add_plugin_page() {
		add_options_page(
			'Settings Admin', 'UrlParamToCookie Settings',
			'manage_options', 'my-setting-admin',
			array($this, 'create_admin_page')
		);
	}

	public function create_admin_page() {
		$this->options = get_option('url_param_options');
		?>
		<div class="wrap">
			<h1>UrlParamToCookie Settings</h1>
			<form method="post" action="options.php">
			<?php
				settings_fields('url_param_option_group');
				do_settings_sections('my-setting-admin');
				?><hr/><?php
				submit_button();
			?>
			</form>
		</div>
		<?php
	}

	public function page_init() {
		register_setting(
			'url_param_option_group', 'url_param_options', array($this, 'sanitize')
		);
		add_settings_section(
			'setting_section_id', '', function() { print '<hr/>'; }, 'my-setting-admin'
		);
		add_settings_field(
			'param_name', 'URL Param Name (required)',
			array($this, 'settings_field_callback'),
			'my-setting-admin', 'setting_section_id',
			array('setting' => 'param_name', 'label_for' => 'URL PARAM NAME',
			'desc' => 'This is the name of the url param we want to store in a cookie.',
			'default' => '')
		);
		add_settings_field(
			'cookie_name', 'Cookie Name',
			array($this, 'settings_field_callback'),
			'my-setting-admin', 'setting_section_id',
			array('setting' => 'cookie_name', 'label_for' => 'COOKIE NAME',
			'desc' => 'Optional - if left blank, will be the same as url param name.',
			'default' => '')
		);
		add_settings_field(
			'path', 'Path for cookie',
			array($this, 'settings_field_callback'),
			'my-setting-admin', 'setting_section_id',
			array('setting' => 'path', 'label_for' => 'PATH',
			'desc' => 'Optional - if left alone will be global (/) for the whole domain.',
			'default' => '/')
		);
		add_settings_field(
			'domain', 'Domain for cookie',
			array($this, 'settings_field_callback'),
			'my-setting-admin', 'setting_section_id',
			array('setting' => 'domain', 'label_for' => 'DOMAIN',
			'desc' => 'Optional - if left blank will be the same as originating site domain.',
			'default' => '')
		);
		add_settings_field(
			'validity', 'Validity seconds for cookie',
			array($this, 'settings_field_callback'),
			'my-setting-admin', 'setting_section_id',
			array('setting' => 'validity', 'label_for' => 'VALIDITY SECONDS',
			'desc' => 'Optional - (time in seconds for cookie validity) if left blank it will default to 0 (expire when sessions ends / browser closes).',
			'default' => '0')
		);
	}

	public function sanitize( $input ) {
		$new_input = array();
		if (is_array($input)) {
			foreach ($input as $key => $value) {
				$new_input[$key] = sanitize_text_field($value);
			}
		}
		return $new_input;
	}

	public function settings_field_callback($args) {
		printf(
			'<input type="text" id="' . $args['setting'] . '" name="url_param_options['. $args['setting'] . ']" value="%s" />
			<p>' . $args['desc'] . '</p>',
			isset($this->options[$args['setting']]) ? esc_attr($this->options[$args['setting']]) : $args['default']
		);
	}
}

$url_param_instance = is_admin() ? new UrlParamToCookieOptionsPage() : new UrlParamToCookie();