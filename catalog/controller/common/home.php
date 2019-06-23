<?php
class ControllerCommonHome extends Controller {
	public function index() {
		$this->document->setTitle($this->config->get('config_meta_title'));
		$this->document->setDescription($this->config->get('config_meta_description'));
		$this->document->setKeywords($this->config->get('config_meta_keyword'));

		if (isset($this->request->get['route'])) {
			$this->document->addLink(HTTP_SERVER, 'canonical');
		}

		$data['baseurl'] = HTTPS_SERVER;
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$data['feedback'] = $this->load->controller('common/feedback');

		$cherryFeatures = array();		
		array_push($cherryFeatures, 
			array(
				'title' => 'Простота использования', 
				'description'=>'Любой человек без специальных знаний и навыков может
				эффективно использовать огнетушитель ВИШНЯ.'
			),
			array(
				'title' => 'Самосрабатывающее устройство', 
				'description'=>'При проявлениях возгарания, огнетушитель ВИШНЯ
				срабатывает автоматически, обеспечивая максимальную
				защиту помещения от пожара.'
			),
			array(
				'title' => 'Высокая эффективность', 
				'description'=>'Огнетушитель ВИШНЯ способен локализовать возгорание
				в помещении объемом 36 м.куб.'
			),
			array(
				'title' => 'Не требует технического обслуживания', 
				'description'=>'Вам не потребуется перезаряжать огнетушитель ВИШНЯ
				и производить любые другие действия в течение 5 лет.'
			),
			array(
				'title' => 'Безопасность для окружающих', 
				'description'=>'Огнетушитель ВИШНЯ не причиняет вреда здоровью
				людей, имуществу и окружающей среде.'
			)
		);
		$data['cherryFeatures'] = $cherryFeatures;

		$usageList = array();
		array_push($usageList,
			array(
				'title' => 'Ручной режим:',
				'description' => 'Огнетушитель следует забросить в очаг возгарания.
				Активация устройства произойдет автоматически в
				течении 1 секунды от прямого контакта с пламенем.',
				'imageUrl' => $data['baseurl'] . 'image/catalog/usage-1.jpg'
			),
			array(
				'title' => 'Автоматический режим:',
				'description' => 'Заранее расположите огнетушитель в потенциально
				опасном месте возникновения очага возгарания.
				Огнетушитель активизируется в течении 1 секунды от
				прямого контакта с пламенем.',
				'imageUrl' => $data['baseurl'] . 'image/catalog/usage-2.jpg'
			)
		);

		$data['usageList'] = $usageList;
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/home.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/common/home.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/common/home.tpl', $data));
		}
	}
}