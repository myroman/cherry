<?php
class ModelShippingHermes extends Model
{
    private $log;
    private $dumplog;

    public function populateParcelShops()
    {
        $this->log = new Log('test.log');
        $this->dumplog = new Log('dump.log');
        $this->log->write('populating parcel shops');
        
        // create tables oc_hermes_parcelshops
        $this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "hermes_parcelshops` (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`createdate` DATETIME NOT NULL,
			`modifydate` DATETIME NULL,
            `updateTimestamp` VARCHAR(50) NOT NULL,
			`parcelShopCode` VARCHAR(100) NOT NULL,
            `parcelShopName` NVARCHAR(100) NOT NULL,
            `address` NVARCHAR(500) NOT NULL,
            `city` NVARCHAR(100) NOT NULL,
            `addressnotes` NVARCHAR(1000) NOT NULL,
            `maxParcelOverallSize` DECIMAL(8, 2) NULL,
            `maxParcelValue` DECIMAL(8, 2) NULL,
            `maxParcelWeight` DECIMAL(8, 2) NULL,
            `paymentType` VARCHAR(100) NULL,
            `region` NVARCHAR(100) NOT NULL,
            `schedulejson` VARCHAR(3000) NOT NULL,
            `services` VARCHAR(200) NOT NULL,
            `zipcode` VARCHAR(10) NULL,
			PRIMARY KEY (`id`)
          )
          CHARACTER SET utf8 COLLATE utf8_bin");

        // request hermes API or load json
        $filepath = DIR_JSONFILES . 'parcelShops.json';

        if (!file_exists($filepath)) {
            $this->log->write('cannot find the json ' . $filepath);
            return;
        }

        $jsondata = file_get_contents($filepath);
        
        $json = json_decode($jsondata);
        foreach ($json->GetParcelShopsResult as $item) {
            $sql = $this->createInsertParcelEntrySql($item);
            $this->db->query($sql);
        }
        $this->log->write('filled new parcel shops');
    }

    public function populatePrices()
    {
        $this->log = new Log('test.log');
        $this->dumplog = new Log('dump.log');
        $this->log->write('populating prices');
        
        // create tables oc_hermes_delivery_price
        $this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "hermes_price` (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`createdate` DATETIME NOT NULL,
			`parcelShopCode` VARCHAR(100) NOT NULL,
            `price` DECIMAL(8, 2) NULL,
            `currencyCode` VARCHAR(3) NULL,
            `error` BIT(1) NULL,
            `errorMsg` VARCHAR(1000) NULL, 
            `errorCategory` INT(11) NULL,           
			PRIMARY KEY (`id`)
          )
          CHARACTER SET utf8 COLLATE utf8_bin");

        $deletesql = "DELETE FROM `" . DB_PREFIX . "hermes_price`";
        $this->db->query($deletesql);

        // request hermes API or load json
        
        $downloadedResult = $this->downloadPricesForParcelShops();
        $pricesJson = $downloadedResult['response'];
        $requestToParcelShopCodes = $downloadedResult['requestParcelCodeMap'];

        $this->log->write($requestToParcelShopCodes);
        foreach ($pricesJson as $respItem) {
            $sql = $this->createInsertPriceSql($respItem, $requestToParcelShopCodes);
            $this->log->write("rid:" . $respItem->RequestId . ", price sql: " . $sql);
            $this->db->query($sql);
        }
        $this->log->write('filled delivery prices');
    }

    public function getParcelShops() {        
        $sql = "SELECT * FROM " . DB_PREFIX . "hermes_parcelshops LIMIT 100";
        $query = $this->db->query($sql);
		return $query->rows;
    }

    private function downloadPricesForParcelShops() {
        $pickupCode = "437";
        $deliveryProductId = "1";
        $cashOnDelivery = "3200";
        $insuranceAmount = "500";
        $now = date(DATE_ATOM,time());
        $weight = "3200";
        $height = $width = $length = "20";

        $parcelShops = $this->getParcelShops();
        
        $payloadRequests = array();
        $requestId = time();
        
        foreach($parcelShops as $item) {
            $productApps = array();
            array_push($productApps, array(
                'HandOutAddress' => array(
                    '__type' => 'ParcelShopLocation:#B2C.API.DTO',
                    'Code' => $item['parcelShopCode']
                ),
                'PickupAddress' => array(
                    '__type' => 'DistributionCenterLocation:#B2C.API.DTO',
                    'Code' => $pickupCode
                ),
                'Product' => array(
                    'Id' => $deliveryProductId
                )
            ));    
            $req = array(
                'RequestId' => $requestId,
                'ProductApplications' => $productApps,
                'BusinessUnitCode' => "1000",
                'CashOnDelivery' => array(
                    'Value' => $cashOnDelivery,
                    'CurrencyCode' => 'RUB'
                ),
                'Insurance' => array(
                    'Value' => $insuranceAmount,
                    'CurrencyCode' => 'RUB'
                ),
                'CommitmentDate' => $now,
                'InvoicingDate' => $now,
                'Weight' => $weight,
                'Height' => $height,
                'Width' => $width,
                'Length' => $length
            );
            array_push($payloadRequests, $req);
            $requestToParcelShopCodes[$requestId] = $item['parcelShopCode'];
            $requestId = $requestId + 1;
        }

        $payload = array(
            'productPriceCalculationRequests' => $payloadRequests
        );

        //call hermes API
        $response = $this->calculateHermesApi($payload);
        $jsonResponse = json_decode($response);
        return array(
            'response' => $jsonResponse,
            'requestParcelCodeMap' => $requestToParcelShopCodes
        );
    }

    private function calculateHermesApi($payload) {
        $url = "https://test-api.hermesrussia.ru/Calculator/RestService.svc/rest/CalculateProductPrice";
        $this->dumplog->write("Request to hermes API:\n" . json_encode($payload));

        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json; charset=utf-8', 
            'Authorization: Basic dGVzdGxvZ2luOnRlc3RwYXNzd29yZA==')
        );        
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch,CURLOPT_HEADER,0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_ENCODING, '');
        // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($ch);
        if(curl_errno($ch)){
            $this->log->write('Request failed: ' . curl_error($ch));
            return NULL;
        }
        curl_close($ch);

        //Hermes replies with BOM, which we should trim
        //https://stackoverflow.com/questions/12509855/curl-gets-response-with-utf-8-bom
        $__BOM = pack('CCC', 239, 187, 191);
        while(0 === strpos($response, $__BOM)) {
            $response = substr($response, 3);
        }            
        $this->dumplog->write("Response:\n" . $response);
        return $response;
    }

    private function createInsertPriceSql($entry, $requestToParcelShopCodes) {
        $parcelShopCode = $requestToParcelShopCodes[$entry->RequestId];

        if ($entry->ErrorCode != 0) {
            $this->log->write("error cat1: " . $entry->ErrorMessage);
            return "INSERT INTO `oc_hermes_price` (`createdate`,`parcelshopcode`,`error`,`errormsg`,`errorCategory`)" .
            "SELECT UTC_TIMESTAMP()" .
            ",'" . $parcelShopCode .
            "'," . $entry->ErrorCode .
            ",'" . $entry->ErrorMessage . 
            "'," . 0;
        }

        $productPrice = $entry->ProductPrices[0];
        if ($productPrice->ErrorCode != 0) {
            $this->log->write("error cat2: " . $productPrice->ErrorMessage);

            return "INSERT INTO `oc_hermes_price` (`createdate`,`parcelshopcode`,`error`,`errormsg`,`errorCategory`)" .
            "SELECT UTC_TIMESTAMP()" .
            ",'" . $parcelShopCode .
            "'," . $productPrice->ErrorCode .
            ",'" . $productPrice->ErrorMessage .
            "'," . 1;
        }
        
        $price = $productPrice->Price->Value;
        $currencyCode = $productPrice->Price->CurrencyCode;
        $result = "INSERT INTO `oc_hermes_price` (`createdate`,`parcelshopcode`,`price`,`currencycode`)" .
        "SELECT UTC_TIMESTAMP()" .
        ",'" . $parcelShopCode .
        "'," . $price .
        ",'" . $currencyCode .
        "'";
        
        return $result;
    }
    
    private function extractNotes($params) {
        foreach($params as $item) {
            if ($item->Name == "AddressInfo")
                return "'" . $item->Value . "'";
        }
        return "NULL";
    }
    private function createInsertParcelEntrySql($entry)
    {
        $updateTimestamp = substr($entry->UpdateTimestamp,6, strlen($entry->UpdateTimestamp) - 8);
        $parcelShopCode = $entry->ParcelShopCode;
        $addressNotes = "NULL";
        $addressNotes = $this->extractNotes($entry->ExtraParams);
        $schedulejson = json_encode($entry->Schedule);
        $services = json_encode($entry->Services);
        $MaxParcelOverallSize = "NULL";
        if (isset($entry->MaxParcelOverallSize)) $MaxParcelOverallSize = (string)$entry->MaxParcelOverallSize;
        $MaxParcelValue = "NULL";
        if (isset($entry->MaxParcelValue)) $MaxParcelValue = (string)$entry->MaxParcelValue;
        $MaxParcelWeight = "NULL";
        if (isset($entry->MaxParcelWeight)) $MaxParcelWeight = (string)$entry->MaxParcelWeight;
        $result =
            "INSERT INTO `oc_hermes_parcelshops` (createdate,updateTimestamp,parcelShopCode,parcelshopname,`address`,city,addressnotes" . 
            ",maxparceloverallsize,maxparcelvalue,maxparcelweight" . 
            ",paymenttype,region,services,zipcode, schedulejson)" .
        "
        SELECT UTC_TIMESTAMP()" . 
        ",'" . $updateTimestamp .
        "','" . $parcelShopCode . 
        "','" . $entry->ParcelShopName . 
        "','" . $entry->Address . 
        "','" . $entry->City . 
        "'," . $addressNotes . 
        "," . $MaxParcelOverallSize . 
        "," . $MaxParcelValue . 
        "," . $MaxParcelWeight . 
        ",'" . $entry->PaymentType . 
        "','". $entry->Region .         
        "','" . $services . 
        "','" . $entry->ZipCode . 
        "','" . $schedulejson . 
        "' WHERE NOT EXISTS (SELECT * FROM `oc_hermes_parcelshops`WHERE parcelShopCode= '" . $parcelShopCode . "');";
        return $result;
    }
}
