<?php
class ModelShippingHermes extends Model
{
    public function getParcelShopCities() {
        
        $sql = "SELECT DISTINCT `city` FROM " . DB_PREFIX . "hermes_parcelshops ORDER BY `city`";
        $query = $this->db->query($sql);
        $rows = $query->rows;
        $result = array_map(array($this, 'getCity'), $rows);
        
        return $result;
    }

    private function getCity($row){
        return $row['city'];
    }

    public function getParcelShopsByCity($city) {
        
        $sql = "SELECT * FROM " . DB_PREFIX . "hermes_parcelshops WHERE `city`='" . $city . "' ORDER BY `address`";
        $query = $this->db->query($sql);
		return $query->rows;
    }

    public function getParcelShopById($id) {
        $sql = "SELECT * FROM " . DB_PREFIX . "hermes_parcelshops WHERE `id`=" . $id;
        $query = $this->db->query($sql);
		return $query->row;
    }

    public function getPriceByParcelShop($parcelShopCode) {
        $sql = "SELECT * FROM " . DB_PREFIX . "hermes_price WHERE `parcelShopCode`=" . $parcelShopCode;
        $query = $this->db->query($sql);
		return $query->row;
    }

    //TODO: implement
    public function getQuote($address)
    {
        $log = new Log('test.log');
        $log->write(json_encode($address));

        if (!isset($address['id']) || $address['id'] == 0) {
            return NULL;
        }

        $parcelShop = $this->getParcelShopById($address['id']);
        if ($parcelShop == NULL) {
            return NULL;
        }

        $priceRecord = $this->getPriceByParcelShop($parcelShop['parcelShopCode']);
        $log->write('Got price record' . json_encode($priceRecord));
        $log->write($priceRecord['price']);
        $cost = $priceRecord['price'];

        $this->load->language('shipping/hermes');

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int) $this->config->get('hermes_geo_zone_id') . "' AND country_id = '" . (int) $address['country_id'] . "' AND (zone_id = '" . (int) $address['zone_id'] . "' OR zone_id = '0')");

        $method_data = array();
        //$description = $this->currency->format($this->tax->calculate($cost, $this->config->get('hermes_tax_class_id'), $this->config->get('config_tax')));
        $quote_data['hermes'] = array(
            'code' => 'hermes.hermes',
            'title' => $text,
            'cost' => $cost,
            //'tax_class_id' => $this->config->get('hermes_tax_class_id'),
            'text' => 'No tax!',
        );

        $text = $this->language->get('text_description') . " hey!";
        $method_data = array(
            'code' => 'hermes',
            'title' => $this->language->get('text_title'),
            'quote' => $quote_data,
            'sort_order' => $this->config->get('hermes_sort_order'),
            'error' => false,
        );

        // if ($status) {
        //     $cost = 0;
        //     $weight = $this->cart->getWeight();
        //     $sub_total = $this->cart->getSubTotal();

        //     $rates = explode(',', $this->config->get('hermes_rate'));

        //     foreach ($rates as $rate) {
        //         $data = explode(':', $rate);

        //         if ($data[0] >= $weight) {
        //             if (isset($data[1])) {
        //                 $cost = $data[1];
        //             }

        //             break;
        //         }
        //     }

        //     $rates = explode(',', $this->config->get('hermes_insurance'));

        //     foreach ($rates as $rate) {
        //         $data = explode(':', $rate);

        //         if ($data[0] >= $sub_total) {
        //             if (isset($data[1])) {
        //                 $insurance = $data[1];
        //             }

        //             break;
        //         }
        //     }

        //     $quote_data = array();

        //     if ((float) $cost) {
        //         $text = $this->language->get('text_description');

        //         if ($this->config->get('hermes_display_weight')) {
        //             $text .= ' (' . $this->language->get('text_weight') . ' ' . $this->weight->format($weight, $this->config->get('config_weight_class_id')) . ')';
        //         }

        //         if ($this->config->get('hermes_display_insurance') && (float) $insurance) {
        //             $text .= ' (' . $this->language->get('text_insurance') . ' ' . $this->currency->format($insurance) . ')';
        //         }

        //         if ($this->config->get('hermes_display_time')) {
        //             $text .= ' (' . $this->language->get('text_time') . ')';
        //         }

        //         $quote_data['hermes'] = array(
        //             'code' => 'hermes.hermes',
        //             'title' => $text,
        //             'cost' => $cost,
        //             'tax_class_id' => $this->config->get('hermes_tax_class_id'),
        //             'text' => $this->currency->format($this->tax->calculate($cost, $this->config->get('hermes_tax_class_id'), $this->config->get('config_tax'))),
        //         );

        //         $method_data = array(
        //             'code' => 'hermes',
        //             'title' => $this->language->get('text_title'),
        //             'quote' => $quote_data,
        //             'sort_order' => $this->config->get('hermes_sort_order'),
        //             'error' => false,
        //         );
        //     }
        // }

        return $method_data;
    }

}
