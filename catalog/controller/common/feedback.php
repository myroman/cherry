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
            $jsonstr = file_get_contents('php://input');
            $data = json_decode($jsonstr, true);       

            $error = $this->validateRequest($data);
            if ($error != '') {
                $this->response->addHeader('Content-Type: application/json');
		        $this->response->setOutput(json_encode(array('error' => $error)));
                return;
            }           

            $log->write('Adding feedback: ' . json_encode($jsonstr));
            $this->load->model('catalog/feedback');
            $this->model_catalog_feedback->addFeedback($data);

            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode(array('success' => true)));

            return;
        }
    }

    function validateRequest($data) {
        if (!isset($data['fullname']) || $data['fullname'] == '') {
            return 'Введите свое имя';
        }
        if (!isset($data['email']) || $data['email'] == '') {
            return 'Введите email';
        }

        if (!isset($data['phone']) || $data['phone'] == '') {
            return 'Введите телефон';
        }

        if (!isset($data['message']) || $data['message'] == '') {
            return 'Введите сообщение';
        }
        return '';
    }
}