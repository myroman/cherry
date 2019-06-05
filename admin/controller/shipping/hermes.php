<?php
class ControllerShippingHermes extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('shipping/hermes');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');
		$this->load->model('shipping/hermes');
		$log = new Log('test.log');
		
		if ($this->request->server['REQUEST_METHOD'] == 'POST' && isset($this->request->post['hermes_updateprices'])) {
			$log->write('updating prices');
			$this->model_shipping_hermes->updatePrices();
			
			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/shipping', 'token=' . $this->session->data['token'], 'SSL'));
		}
		else if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$log->write('updating hermes settings');
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


		$data['entry_updateprices'] = $this->language->get('entry_updateprices');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');

		$data['help_updateprices'] = $this->language->get('help_updateprices');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_refresh_handout'] = $this->language->get('button_refresh_handout');
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

		$data['hermes_updateprices'] = 1;
		
		// if (isset($this->request->post['hermes_updateprices'])) {
		// 	$data['hermes_updateprices'] = $this->request->post['hermes_display_weight'];
		// } else {
		// 	$data['hermes_display_weight'] = $this->config->get('hermes_display_weight');
		// }

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