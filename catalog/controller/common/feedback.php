<?php
class ControllerCommonFeedback extends Controller {
	public function index() {
        $data['abc'] = 1;
        $this->load->language('common/feedback');
        $data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');
        $data['heading_title'] = $this->language->get('heading_title');

        $log = new Log('test.log');
        $log->write('rendering');       

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/feedback.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/common/feedback.tpl', $data);
		} else {
			return $this->load->view('default/template/common/feedback.tpl', $data);
		}
    }

    public function send() {
        $log = new Log('test.log');       

        if ($this->request->server['REQUEST_METHOD'] == 'POST') {
            $log->write('posting feedback');

            if (isset($this->request->post['fullname'])) {
                $log->write('fullname:' . $this->request->post['fullname']);
            }
        }

        $result = array(
            'success' => 'true'
        );

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($result));
    }
}