<?php
class ControllerShippingHermes extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('shipping/hermes');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('hermes', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/shipping', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_all_zones'] = $this->language->get('text_all_zones');
		$data['text_none'] = $this->language->get('text_none');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');

		$data['entry_rate'] = $this->language->get('entry_rate');
		$data['entry_insurance'] = $this->language->get('entry_insurance');
		$data['entry_display_weight'] = $this->language->get('entry_display_weight');
		$data['entry_display_insurance'] = $this->language->get('entry_display_insurance');
		$data['entry_display_time'] = $this->language->get('entry_display_time');
		$data['entry_tax_class'] = $this->language->get('entry_tax_class');
		$data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');

		$data['help_rate'] = $this->language->get('help_rate');
		$data['help_insurance'] = $this->language->get('help_insurance');
		$data['help_display_weight'] = $this->language->get('help_display_weight');
		$data['help_display_insurance'] = $this->language->get('help_display_insurance');
		$data['help_display_time'] = $this->language->get('help_display_time');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		$data['tab_general'] = $this->language->get('tab_general');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_shipping'),
			'href' => $this->url->link('extension/shipping', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('shipping/hermes', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['action'] = $this->url->link('shipping/hermes', 'token=' . $this->session->data['token'], 'SSL');

		$data['cancel'] = $this->url->link('extension/shipping', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->post['hermes_rate'])) {
			$data['hermes_rate'] = $this->request->post['hermes_rate'];
		} elseif ($this->config->get('hermes_rate')) {
			$data['hermes_rate'] = $this->config->get('hermes_rate');
		} else {
			$data['hermes_rate'] = '10:15.99,12:19.99,14:20.99,16:21.99,18:21.99,20:21.99,22:26.99,24:30.99,26:34.99,28:38.99,30:42.99,35:52.99,40:62.99,45:72.99,50:82.99,55:92.99,60:102.99,65:112.99,70:122.99,75:132.99,80:142.99,85:152.99,90:162.99,95:172.99,100:182.99';
		}

		if (isset($this->request->post['hermes_insurance'])) {
			$data['hermes_insurance'] = $this->request->post['hermes_insurance'];
		} elseif ($this->config->get('hermes_insurance')) {
			$data['hermes_insurance'] = $this->config->get('hermes_insurance');
		} else {
			$data['hermes_insurance'] = '150:0,500:12,1000:24,1500:36,2000:48,2500:60';
		}

		if (isset($this->request->post['hermes_display_weight'])) {
			$data['hermes_display_weight'] = $this->request->post['hermes_display_weight'];
		} else {
			$data['hermes_display_weight'] = $this->config->get('hermes_display_weight');
		}

		if (isset($this->request->post['hermes_display_insurance'])) {
			$data['hermes_display_insurance'] = $this->request->post['hermes_display_insurance'];
		} else {
			$data['hermes_display_insurance'] = $this->config->get('hermes_display_insurance');
		}

		if (isset($this->request->post['hermes_display_time'])) {
			$data['hermes_display_time'] = $this->request->post['hermes_display_time'];
		} else {
			$data['hermes_display_time'] = $this->config->get('hermes_display_time');
		}

		if (isset($this->request->post['hermes_tax_class_id'])) {
			$data['hermes_tax_class_id'] = $this->request->post['hermes_tax_class_id'];
		} else {
			$data['hermes_tax_class_id'] = $this->config->get('hermes_tax_class_id');
		}

		$this->load->model('localisation/tax_class');

		$data['tax_classes'] = $this->model_localisation_tax_class->getTaxClasses();

		if (isset($this->request->post['hermes_geo_zone_id'])) {
			$data['hermes_geo_zone_id'] = $this->request->post['hermes_geo_zone_id'];
		} else {
			$data['hermes_geo_zone_id'] = $this->config->get('hermes_geo_zone_id');
		}

		$this->load->model('localisation/geo_zone');

		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['hermes_status'])) {
			$data['hermes_status'] = $this->request->post['hermes_status'];
		} else {
			$data['hermes_status'] = $this->config->get('hermes_status');
		}

		if (isset($this->request->post['hermes_sort_order'])) {
			$data['hermes_sort_order'] = $this->request->post['hermes_sort_order'];
		} else {
			$data['hermes_sort_order'] = $this->config->get('hermes_sort_order');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('shipping/hermes.tpl', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'shipping/hermes')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}