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

    public function getPriceByParcelShop($parcelShopCode, $configNumber) {
        $sql = "SELECT * FROM " . DB_PREFIX . "hermes_price WHERE `parcelShopCode`=" . $parcelShopCode . " AND `configNumber` = " . $configNumber;
        $query = $this->db->query($sql);
		return $query->row;
    }

    public function getQuote($address)
    {        
        $log = new Log('test.log');
        $log->write('product quantity: ' . $address['productQuantity']);

        if (!isset($address['parcelshopid']) || $address['parcelshopid'] == 0) {
            return NULL;
        }

        //config number is basically number of product items
        $configNumber = $address['productQuantity'];

        $parcelShop = $this->getParcelShopById($address['parcelshopid']);
        if ($parcelShop == NULL) {            
            return NULL;
        }

        $priceRecord = $this->getPriceByParcelShop($parcelShop['parcelShopCode'], $address['productQuantity']);
        if ($priceRecord == NULL) {
            $log->write('INFO: cannot lookup for parce code ' . $parcelShop['parcelShopCode'] . ' and quantity ' . $address['productQuantity']);
            return NULL;
        }
        $log->write('Got price ' . $priceRecord['price'] . ', details' . json_encode($priceRecord));
        $cost = $priceRecord['price'];

        $this->load->language('shipping/hermes');
        $title = $this->language->get('text_description');
        $method_data = array();
        $quote_data['hermes'] = array(
            'code' => 'hermes.hermes',
            'title' => $title,
            'cost' => $cost,
            'text' => $this->currency->format($cost)
        );
        
        $method_data = array(
            'code' => 'hermes',
            'title' => $this->language->get('text_title'),
            'quote' => $quote_data,
            'sort_order' => $this->config->get('hermes_sort_order'),
            'error' => false
        );
        return $method_data;
    }

}
