<?php
class ControllerCommonContentTop extends Controller {
	public function index() {
		$this->load->model('design/layout');

		if (isset($this->request->get['route'])) {
			$route = (string)$this->request->get['route'];
		} else {
			$route = 'common/home';
		}

		$layout_id = 0;

		if ($route == 'product/category' && isset($this->request->get['path'])) {
			$this->load->model('catalog/category');

			$path = explode('_', (string)$this->request->get['path']);

			$layout_id = $this->model_catalog_category->getCategoryLayoutId(end($path));
		}

		if ($route == 'product/product' && isset($this->request->get['product_id'])) {
			$this->load->model('catalog/product');

			$layout_id = $this->model_catalog_product->getProductLayoutId($this->request->get['product_id']);
		}

		if ($route == 'information/information' && isset($this->request->get['information_id'])) {
			$this->load->model('catalog/information');

			$layout_id = $this->model_catalog_information->getInformationLayoutId($this->request->get['information_id']);
		}

		if (!$layout_id) {
			$layout_id = $this->model_design_layout->getLayout($route);
		}

		if (!$layout_id) {
			$layout_id = $this->config->get('config_layout_id');
		}

		$this->load->model('extension/module');

		$data['modules'] = array();

		$modules = $this->model_design_layout->getLayoutModules($layout_id, 'content_top');

		foreach ($modules as $module) {
			$part = explode('.', $module['code']);

			if (isset($part[0]) && $this->config->get($part[0] . '_status')) {
				$data['modules'][] = $this->load->controller('module/' . $part[0]);
			}

			if (isset($part[1])) {
				$setting_info = $this->model_extension_module->getModule($part[1]);

				if ($setting_info && $setting_info['status']) {
					$data['modules'][] = $this->load->controller('module/' . $part[0], $setting_info);
				}
			}
		}

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
				'description'=>'Огнетушитель ВИШНЯ способен локализовать возгарание
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
				течении1 сек от прямого контакта с пламенем.',
				'imageUrl' => '/cherry/image/catalog/usage-1.jpg'
			),
			array(
				'title' => 'Автоматический режим:',
				'description' => 'Заранее расположите огнетушитель в потенциально
				опасном месте возникновения очага возгарания.
				Огнетушитель активизируется в течении 1 сек от
				прямого контакта с пламенем.',
				'imageUrl' => '/cherry/image/catalog/usage-2.jpg'
			)
		);

		$data['usageList'] = $usageList;

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/content_top.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/common/content_top.tpl', $data);
		} else {
			return $this->load->view('default/template/common/content_top.tpl', $data);
		}
	}
}