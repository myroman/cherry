<?php
class ControllerInformationContacts extends Controller {

	public function index() {

		$this->document->setTitle('Контакты');

		$data['heading_title'] = 'Контакты';

		$data['continue'] = $this->url->link('common/home');

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');
		$data['feedback'] = $this->load->controller('common/feedback');
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/information/contacts.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/information/contacts.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/information/contacts.tpl', $data));
		}
	}
}
