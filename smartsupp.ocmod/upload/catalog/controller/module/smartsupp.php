<?php
/**
 * @author Tomáš Blatný
 */

use Cart\Customer;
use Smartsupp\ChatGenerator;


require __DIR__ . '/../../../admin/smartsupp/vendor/autoload.php';

class ControllerModuleSmartsupp extends Controller
{

	const SETTING_NAME = 'smartsupp';

	private $error = array();

	public function index()
	{
		$this->load->language('module/smartsupp');

		$this->load->model('setting/setting');
		$settings = $this->model_setting_setting->getSetting(self::SETTING_NAME);

		$data = array(
			'chat' => NULL,
			'customCode' => NULL,
		);
		if (isset($settings[self::SETTING_NAME . 'chatId'])) {
			$chat = new ChatGenerator($settings[self::SETTING_NAME . 'chatId']);
			if ($this->customer->isLogged()) {
				$chat->setName($this->customer->getFirstName() . ' ' . $this->customer->getLastName());
				$chat->setEmail($this->customer->getEmail());
			}
			$data['chat'] = $chat->render();
			$data['customCode'] = isset($settings[self::SETTING_NAME . 'customCode']) ? $settings[self::SETTING_NAME . 'customCode'] : NULL;
		}

		return $this->load->view('module/smartsupp.tpl', $data);
	}

}
