<?php
class ControllerTotalShipping extends Controller
{
    public function index()
    {
        if ($this->config->get('shipping_status') && $this->config->get('shipping_estimator') && $this->cart->hasShipping()) {
            $this->load->language('total/shipping');

            $data['heading_title'] = $this->language->get('heading_title');

            $data['text_shipping'] = $this->language->get('text_shipping');
            $data['text_shipping_method'] = $this->language->get('text_shipping_method');
            $data['text_select'] = $this->language->get('text_select');
            $data['text_none'] = $this->language->get('text_none');
            $data['text_loading'] = $this->language->get('text_loading');

            $data['entry_country'] = $this->language->get('entry_country');
            $data['entry_zone'] = $this->language->get('entry_zone');
            $data['entry_city'] = $this->language->get('entry_city');
            $data['entry_handout'] = $this->language->get('entry_handout');
            $data['entry_addressnotes'] = $this->language->get('entry_addressnotes');
            $data['entry_address'] = $this->language->get('entry_address');
            $data['entry_workhours'] = $this->language->get('entry_workhours');
            $data['entry_postcode'] = $this->language->get('entry_postcode');

            $data['button_quote'] = $this->language->get('button_quote');
            $data['button_shipping'] = $this->language->get('button_shipping');
            $data['button_cancel'] = $this->language->get('button_cancel');

            if (isset($this->session->data['shipping_address']['country_id'])) {
                $data['country_id'] = $this->session->data['shipping_address']['country_id'];
            } else {
                $data['country_id'] = $this->config->get('config_country_id');
            }

            if (isset($this->session->data['shipping_address']['parcelshopcityid'])) {
                $data['parcelshopcityid'] = $this->session->data['shipping_address']['parcelshopcityid'];
            } else {
                $data['parcelshopcityid'] = '';
            }
            if (isset($this->session->data['shipping_address']['parcelshopid'])) {
                $data['parcelshopid'] = $this->session->data['shipping_address']['parcelshopid'];
            } else {
                $data['parcelshopid'] = '';
            }

            $this->load->model('localisation/country');

            $data['countries'] = $this->model_localisation_country->getCountries();

            if (isset($this->session->data['shipping_address']['zone_id'])) {
                $data['zone_id'] = $this->session->data['shipping_address']['zone_id'];
            } else {
                $data['zone_id'] = '';
            }

            if (isset($this->session->data['shipping_address']['postcode'])) {
                $data['postcode'] = $this->session->data['shipping_address']['postcode'];
            } else {
                $data['postcode'] = '';
            }

            if (isset($this->session->data['shipping_method'])) {
                $data['shipping_method'] = $this->session->data['shipping_method']['code'];
            } else {
                $data['shipping_method'] = '';
            }

            if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/total/shipping.tpl')) {
                return $this->load->view($this->config->get('config_template') . '/template/total/shipping.tpl', $data);
            } else {
                return $this->load->view('default/template/total/shipping.tpl', $data);
            }
        }
    }

    public function quote()
    {
        $log = new Log('test.log');
        $this->load->language('total/shipping');

        $json = array();

        if (!$this->cart->hasProducts()) {
            $json['error']['warning'] = $this->language->get('error_product');
        }
        $products = $this->cart->getProducts();
        $quantity = 0;
        foreach($products as $product) {
            $quantity += $products[0]['quantity'];
        }

        if (!$this->cart->hasShipping()) {
            $json['error']['warning'] = sprintf($this->language->get('error_no_shipping'), $this->url->link('information/contact'));
        }
        
        if ($this->request->post['country_id'] == '') {
            $json['error']['country'] = $this->language->get('error_country');
        }
        
        if (!isset($this->request->post['city']) || $this->request->post['city'] == '') {
            $json['error']['parcelshopcity'] = $this->language->get('error_city');
        }
        
        if (!isset($this->request->post['parcelshopid']) || $this->request->post['parcelshopid'] == '') {
            $json['error']['parcelshop'] = $this->language->get('error_parcelshop');
        }

        if ($quantity > 18) {
            $json['error']['warning'] = $this->language->get('error_max_parcel_reached');
        }                

        if (!$json) {
            $this->load->model('localisation/country');
            $country_info = $this->model_localisation_country->getCountry($this->request->post['country_id']);
            if ($country_info) {
                $country = $country_info['name'];
                $iso_code_2 = $country_info['iso_code_2'];
                $iso_code_3 = $country_info['iso_code_3'];
                $address_format = $country_info['address_format'];
            } else {
                $country = '';
                $iso_code_2 = '';
                $iso_code_3 = '';
                $address_format = '';
            }

            $zone = '';
            $zone_code = '';

            $this->session->data['shipping_address'] = array(
                'firstname' => '',
                'lastname' => '',
                'company' => '',
                'address_1' => '',
                'address_2' => '',
                'postcode' => '',
                'city' => $this->request->post['city'],
                'country_id' => $this->request->post['country_id'],
                'country' => $country,
                'iso_code_2' => $iso_code_2,
                'iso_code_3' => $iso_code_3,
                'address_format' => $address_format,
                'parcelshopid' => $this->request->post['parcelshopid'],
                'zone_id' => 1,
                'zone' => $zone
            );

            $quote_data = array();

            $this->load->model('extension/extension');
            
            $results = $this->model_extension_extension->getExtensions('shipping');
            foreach ($results as $result) {
                
                if ($this->config->get($result['code'] . '_status')) {
                    $this->load->model('shipping/' . $result['code']);
                    $this->session->data['shipping_address']['productQuantity'] = $quantity;
                    
                    try {
                        $quote = $this->{'model_shipping_' . $result['code']}->getQuote($this->session->data['shipping_address']);
                        if ($quote) {
                            $quote_data[$result['code']] = array(
                                'title' => $quote['title'],
                                'quote' => $quote['quote'],
                                'sort_order' => $quote['sort_order'],
                                'error' => $quote['error'],
                            );
                        }
                    }
                    catch (Exception $ex) {
                        $log->write('ERROR: ' . $ex);
                    }
                }
            }

            $sort_order = array();

            foreach ($quote_data as $key => $value) {
                $sort_order[$key] = $value['sort_order'];
            }

            array_multisort($sort_order, SORT_ASC, $quote_data);
            $this->session->data['shipping_methods'] = $quote_data;

            if ($this->session->data['shipping_methods']) {
                $json['shipping_method'] = $this->session->data['shipping_methods'];
            } else {
                $log->write('error no shipping');
                $json['error']['warning'] = sprintf($this->language->get('error_no_shipping'), $this->url->link('information/contact'));
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function shipping()
    {
        $this->load->language('total/shipping');

        $json = array();

        if (!empty($this->request->post['shipping_method'])) {
            $shipping = explode('.', $this->request->post['shipping_method']);

            if (!isset($shipping[0]) || !isset($shipping[1]) || !isset($this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]])) {
                $json['warning'] = $this->language->get('error_shipping');
            }
        } else {
            $json['warning'] = $this->language->get('error_shipping');
        }

        if (!$json) {
            $shipping = explode('.', $this->request->post['shipping_method']);

            $this->session->data['shipping_method'] = $this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]];

            $this->session->data['success'] = $this->language->get('text_success');

            $json['redirect'] = $this->url->link('checkout/cart');
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
    
    public function country()
    {
        $json = array();

        $this->load->model('localisation/country');
        $this->load->model('shipping/hermes');
        
        $country_info = $this->model_localisation_country->getCountry($this->request->get['country_id']);
        if ($country_info) {
            $this->load->model('localisation/zone');
            $psCities = $this->model_shipping_hermes->getParcelShopCities();
            $json = array(
                'country_id' => $country_info['country_id'],
                'name' => $country_info['name'],
                'iso_code_2' => $country_info['iso_code_2'],
                'iso_code_3' => $country_info['iso_code_3'],
                'address_format' => $country_info['address_format'],
                'postcode_required' => $country_info['postcode_required'],
                'cities' => $psCities,
                'status' => $country_info['status'],
            );
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    private function mapParcelShop($value)
    {
        return array(
            'id' => $value['id'],
            'address' => $value['address']
        );
    }

    public function parcelShops() {
        $this->load->model('shipping/hermes');
        $city = $this->request->get['city'];
        $rows = $this->model_shipping_hermes->getParcelShopsByCity($city);
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode(array_map(array($this, 'mapParcelShop'), $rows)));
    }

    public function parcelShopDetails() {
        $this->load->model('shipping/hermes');
        $id = $this->request->get['id'];
        if ($id == 0) {
            return "{}";
        }
        $model = $this->model_shipping_hermes->getParcelShopById($id);

        $this->response->addHeader('Content-Type: application/json');
        $json = array(
            'id' => $model['id'],
            'addressnotes' => $model['addressnotes'],
            'address' => $model['address'],
            'schedulejson' => $model['schedulejson']
        );
        $this->response->setOutput(json_encode($json));
    }

    public function saveParcelShop() {
        $log = new Log('test.log');

        if (isset($this->request->post['parcelshopcityid']) && isset($this->request->post['parcelshopid'])) {
            $log->write('total selected'); 
            $log->write('parcelshopcityid: ' . $this->request->post['parcelshopcityid']);		
            $log->write('parcelshopid: ' . $this->request->post['parcelshopid']);
    
            $this->session->data['shipping_address']['parcelshopcityid'] = $this->request->post['parcelshopcityid'];
            $this->session->data['shipping_address']['parcelshopid'] = $this->request->post['parcelshopid'];          
        }

        $this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode(array()));
    }

}
