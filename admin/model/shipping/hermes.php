<?php

class ModelShippingHermes extends Model
{
    private $log;
    private $dumplog;
    const MAX_CONFIGS = 18;
    const PARCEL_PAGE_SIZE = 10;
    const CHERRY_WEIGHT = 3300;
    public function updatePrices()
    {
        $this->log = new Log('test.log');
        $this->dumplog = new Log('dump.log');
        $this->log->write('Updating prices');

        $this->log->write('Create table and/or delete rows');
        // create tables oc_hermes_delivery_price
        $this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "hermes_price` (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`createdate` DATETIME NOT NULL,
			`parcelShopCode` VARCHAR(100) NOT NULL,
            `price` DECIMAL(8, 2) NULL,
            `currencyCode` VARCHAR(3) NULL,
            `configNumber` int(11) NULL,
            `error` BIT(1) NULL,
            `errorMsg` VARCHAR(1000) NULL,
            `errorCategory` INT(11) NULL,
			PRIMARY KEY (`id`)
          )
          CHARACTER SET utf8 COLLATE utf8_bin");

        $deletesql = "DELETE FROM `" . DB_PREFIX . "hermes_price`";
        $this->db->query($deletesql);

        $shippingConfigs = array();
        for ($configNumber = 1; $configNumber <= self::MAX_CONFIGS; $configNumber++) {
            $shippingConfigs[$configNumber] = $this->getShippingConfiguration($configNumber);
        }
        $this->log->write(json_encode($shippingConfigs));
        // request hermes API or load json
        $pageNumber = 1;
        do {

            $this->log->write("Processing parcelshop prices pageNumber = " . $pageNumber . ", pageSize=" . self::PARCEL_PAGE_SIZE);
            $parcelShops = $this->getParcelShopsPaged(self::PARCEL_PAGE_SIZE, $pageNumber);
            $parcelShopCodes = array_map(array($this, 'getParcelShopCode'), $parcelShops);
            if (count($parcelShops) == 0) {
                $this->log->write('last page, break');
                break;
            }
            $this->log->write("Fetched " . count($parcelShops));
            $this->dumplog->write("Will get prices for " . json_encode($parcelShopCodes));

            $downloadedResult = $this->downloadPricesForParcelShops($parcelShopCodes, $shippingConfigs);
            $pricesJson = $downloadedResult['response'];
            $requestToParcelShopCodes = $downloadedResult['requestParcelCodeMap'];

            $models = array();
            foreach ($pricesJson as $respItem) {
                $model = $this->convertResponseToModel($respItem, $requestToParcelShopCodes);
                array_push($models, $model);

            }

            $totalPricesPerParcel = $this->_group_by($models, 'parcelcode_confignumber');
            // $this->log->write('groups: ' . json_encode($totalPricesPerParcel));
            foreach ($totalPricesPerParcel as $key => $prices) {
                $totalPrice = 0;

                $hasError = false;
                $badEntries = array();
                foreach ($prices as $priceModel) {
                    if (!isset($priceModel['errorCode'])) {
                        $totalPrice += $priceModel['price'];
                    } else {
                        array_push($badEntries, $priceModel);
                    }
                }

                if (sizeof($badEntries) == 0) {
                    $totalPriceModel = $prices[0]; //prices belong to the same group, so all except amount is the same
                    $totalPriceModel['price'] = $totalPrice;
                    $sql = $this->createInsertPriceSql($totalPriceModel, $requestToParcelShopCodes);
                    // $this->log->write('sql: ' . $sql);
                    $this->db->query($sql);
                } else {
                    $this->log->write('bad entries:' . json_encode($badEntries));
                    foreach ($badEntries as $item) {
                        $sql = $this->createInsertPriceSql($item, $requestToParcelShopCodes);
                        // $this->log->write('sql: ' . $sql);
                        $this->db->query($sql);
                    }
                }
            }

            $pageNumber = $pageNumber + 1;
            // break;
        } while (count($parcelShops) == self::PARCEL_PAGE_SIZE);

        $this->log->write('Updated all prices');
    }

    public function _group_by($array, $key)
    {
        $return = array();
        foreach ($array as $val) {
            $return[$val[$key]][] = $val;
        }
        return $return;
    }

    public function getParcelShops()
    {
        $sql = "SELECT * FROM " . DB_PREFIX . "hermes_parcelshops";
        $query = $this->db->query($sql);
        return $query->rows;
    }
    public function getParcelShopsPaged($pageSize, $pageNumber)
    {
        $offset = $pageSize * ($pageNumber - 1);
        $sql = "SELECT * FROM " . DB_PREFIX . "hermes_parcelshops LIMIT " . $offset . ", " . $pageSize;
        $query = $this->db->query($sql);
        return $query->rows;
    }

    private function getParcelShopCode($parcelShop)
    {
        return $parcelShop['parcelShopCode'];
    }

    private function getBox4Info($cherriesInside)
    {
        return array(
            'width' => 44,
            'height' => 22,
            'length' => 44,
            'weight' => self::CHERRY_WEIGHT * $cherriesInside,
        );
    }

    private function getBox9Info($cherriesInside)
    {
        return array(
            'width' => 65,
            'height' => 65,
            'length' => 23,
            'weight' => self::CHERRY_WEIGHT * $cherriesInside,
        );
    }

    private function getShippingConfiguration($cherriesNumber)
    {
        $cherryWidth = 22;
        $cherryHeight = 22;
        $cherryLength = 22;

        $oneCherry = array(
            'width' => $cherryWidth,
            'height' => $cherryHeight,
            'length' => $cherryLength,
            'weight' => self::CHERRY_WEIGHT,
        );

        $twoCherries = array(
            'width' => $cherryWidth * 2,
            'height' => $cherryHeight,
            'length' => $cherryLength,
            'weight' => self::CHERRY_WEIGHT * 2,
        );

        switch ($cherriesNumber) {
            case 1:
                return array($oneCherry);
            case 2:
                return array($twoCherries);
            case 3:
                return array($this->getBox4Info(3));
            case 4:
                return array($this->getBox4Info(4));
            case 5:
                return array($this->getBox4Info(3), $twoCherries);
            case 6:
                return array($this->getBox4Info(4), $twoCherries);
            case 7:
                return array($this->getBox4Info(4), $this->getBox4Info(3));
            case 8:
                return array($this->getBox4Info(4), $this->getBox4Info(4));
            case 9:
                return array($this->getBox9Info(9));
            case 10:
                return array($this->getBox9Info(9), $oneCherry);
            case 11:
                return array($this->getBox9Info(9), $twoCherries);
            case 12:
                return array($this->getBox9Info(9), $this->getBox4Info(3));
            case 13:
                return array($this->getBox9Info(9), $this->getBox4Info(4));
            case 14:
                return array($this->getBox4Info(4), $this->getBox4Info(4), $this->getBox4Info(4), $twoCherries);
            case 15:
                return array($this->getBox4Info(4), $this->getBox4Info(4), $this->getBox4Info(4), $this->getBox4Info(3));
            case 16:
                return array($this->getBox4Info(4), $this->getBox4Info(4), $this->getBox4Info(4), $this->getBox4Info(4));
            case 17:
                return array($this->getBox9Info(9), $this->getBox9Info(8));
            case 18:
                return array($this->getBox9Info(9), $this->getBox9Info(9));

            default:{
                    $this->log->write('ERROR: No configuration for number' . $cherriesNumber);
                    throw new Exception('Error when updating hermes prices. No configuration for number' . $cherriesNumber);
                }
        }
    }

    private function downloadPricesForParcelShops($parcelShopCodes, $shippingConfigs)
    {
        $pickupCode = "437";
        $deliveryProductId = "1";
        $cashOnDelivery = "3200";
        $insuranceAmount = "500";
        $now = date(DATE_ATOM, time());
        $payloadRequests = array();
        $requestId = time();

        foreach ($parcelShopCodes as $parcelShopCode) {
            $productApps = array();
            array_push($productApps, array(
                'HandOutAddress' => array(
                    '__type' => 'ParcelShopLocation:#B2C.API.DTO',
                    'Code' => $parcelShopCode,
                ),
                'PickupAddress' => array(
                    '__type' => 'DistributionCenterLocation:#B2C.API.DTO',
                    'Code' => $pickupCode,
                ),
                'Product' => array(
                    'Id' => $deliveryProductId,
                ),
            ));

            foreach ($shippingConfigs as $configNumber => $shipConfig) {
                foreach ($shipConfig as $parcelSize) {

                    //ship config is a list of ParcelSize
                    $req = array(
                        'RequestId' => $requestId,
                        'ProductApplications' => $productApps,
                        'BusinessUnitCode' => "1000",
                        'CashOnDelivery' => array(
                            'Value' => $cashOnDelivery,
                            'CurrencyCode' => 'RUB',
                        ),
                        'Insurance' => array(
                            'Value' => $insuranceAmount,
                            'CurrencyCode' => 'RUB',
                        ),
                        'CommitmentDate' => $now,
                        'InvoicingDate' => $now,
                        'Weight' => $parcelSize['weight'],
                        'Height' => $parcelSize['height'],
                        'Width' => $parcelSize['width'],
                        'Length' => $parcelSize['length'],
                    );
                    array_push($payloadRequests, $req);
                    $requestToParcelShopCodes[$requestId] = array(
                        'parcelShopCode' => $parcelShopCode,
                        'configNumber' => $configNumber,
                    );
                    $requestId = $requestId + 1;
                }
            }
        }

        $payload = array(
            'productPriceCalculationRequests' => $payloadRequests,
        );

        //call hermes API
        $response = $this->calculateHermesApi($payload);
        $jsonResponse = json_decode($response);
        return array(
            'response' => $jsonResponse,
            'requestParcelCodeMap' => $requestToParcelShopCodes,
        );
    }

    private function calculateHermesApi($payload)
    {
        $url = "https://test-api.hermesrussia.ru/Calculator/RestService.svc/rest/CalculateProductPrice";
        $this->log->write("Request to hermes API");
        $this->dumplog->write("Request to hermes API:\n" . json_encode($payload));

        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json; charset=utf-8',
            'Authorization: Basic dGVzdGxvZ2luOnRlc3RwYXNzd29yZA==')
        );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_ENCODING, '');
        // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            $this->log->write('Request failed: ' . curl_error($ch));
            return null;
        }
        curl_close($ch);

        //Hermes replies with BOM, which we should trim
        //https://stackoverflow.com/questions/12509855/curl-gets-response-with-utf-8-bom
        $__BOM = pack('CCC', 239, 187, 191);
        while (0 === strpos($response, $__BOM)) {
            $response = substr($response, 3);
        }
        $this->dumplog->write("Response:\n" . $response);
        return $response;
    }

    private function convertResponseToModel($entry, $requestToParcelShopCodes)
    {
        $requestInfo = $requestToParcelShopCodes[$entry->RequestId];
        $parcelShopCode = $requestInfo['parcelShopCode'];
        $configNumber = $requestInfo['configNumber'];

        if ($entry->ErrorCode != 0) {
            $this->log->write("error cat1: " . $entry->ErrorMessage);
            return array(
                'requestId' => $entry->RequestId,
                'parcelcode_confignumber' => $parcelShopCode . '_' . $configNumber,
                'parcelShopCode' => $parcelShopCode,
                'configNumber' => $configNumber,
                'errorCode' => $entry->ErrorCode,
                'errorMessage' => $entry->ErrorMessage,
                'errorCategory' => 0,
            );
        }

        $productPrice = $entry->ProductPrices[0];
        if ($productPrice->ErrorCode != 0) {
            $this->log->write("error cat2: " . $productPrice->ErrorMessage);
            return array(
                'requestId' => $entry->RequestId,
                'parcelcode_confignumber' => $parcelShopCode . '_' . $configNumber,
                'parcelShopCode' => $parcelShopCode,
                'configNumber' => $configNumber,
                'errorCode' => $entry->ErrorCode,
                'errorMessage' => $productPrice->ErrorMessage,
                'errorCategory' => 1,
            );
        }

        return array(
            'requestId' => $entry->RequestId,
            'parcelcode_confignumber' => $parcelShopCode . '_' . $configNumber,
            'parcelShopCode' => $parcelShopCode,
            'configNumber' => $configNumber,
            'price' => $productPrice->Price->Value,
            'currencyCode' => $productPrice->Price->CurrencyCode
        );

        return $result;
    }

    private function createInsertPriceSql($entry, $requestToParcelShopCodes)
    {
        $requestInfo = $requestToParcelShopCodes[$entry['requestId']];
        $parcelShopCode = $requestInfo['parcelShopCode'];
        $configNumber = $requestInfo['configNumber'];

        if (isset($entry['errorCode'])) {
            // $this->log->write("error cat1: " . $entry['errorMessage']);
            return "INSERT INTO `oc_hermes_price` (`createdate`,`parcelshopcode`,`configNumber`,`error`,`errormsg`,`errorCategory`)" .
                "SELECT UTC_TIMESTAMP()" .
                ",'" . $entry['parcelShopCode'] .
                "'," . $entry['configNumber'] .
                "," . $entry['errorCode'] .
                ",'" . $entry['errorMessage'] .
                "'," . $entry['errorCategory'];
        }

        $result = "INSERT INTO `oc_hermes_price` (`createdate`,`parcelshopcode`,`configNumber`,`price`,`currencycode`)" .
            "SELECT UTC_TIMESTAMP()" .
            ",'" . $entry['parcelShopCode'] .
            "'," . $entry['configNumber'] .
            "," . $entry['price'] .
            ",'" . $entry['currencyCode'] .
            "'";

        return $result;
    }

    public function updateParcelShops()
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

        $resp = $this->getParcelShopsFromApi('1000');
        $json = json_decode($resp);
        foreach ($json->GetParcelShopsResult as $item) {            
            $sql = $this->createInsertParcelEntrySql($item);
            // $this->log->write('sql: ' . $sql);
            $this->db->query($sql);
        }
        $this->log->write('filled new parcel shops');
    }

    private function getParcelShopsFromApi($businessUnitCode)
    {
        $payload = array(
            'businessUnitCode' => $businessUnitCode
        );
        $url = "https://test-api.hermes-dpd.ru/WS/RestService.svc/rest/GetParcelShops";
        $this->log->write("Request to GetParcelShops");
        $this->dumplog->write("Request to GetParcelShops:\n" . json_encode($payload));

        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json; charset=utf-8',
            'Authorization: Basic dGVzdGxvZ2luOnRlc3RwYXNzd29yZA==')
        );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_ENCODING, '');
        // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            $this->log->write('Request failed: ' . curl_error($ch));
            return null;
        }
        curl_close($ch);

        //Hermes replies with BOM, which we should trim
        //https://stackoverflow.com/questions/12509855/curl-gets-response-with-utf-8-bom
        $__BOM = pack('CCC', 239, 187, 191);
        while (0 === strpos($response, $__BOM)) {
            $response = substr($response, 3);
        }
        $this->dumplog->write("Response GetParcelShops:\n" . $response);
        return $response;
    }

    private function extractNotes($params)
    {
        foreach ($params as $item) {
            if ($item->Name == "AddressInfo") {
                return "'" . $item->Value . "'";
            }

        }
        return "NULL";
    }
    private function createInsertParcelEntrySql($entry)
    {
        $updateTimestamp = substr($entry->UpdateTimestamp, 6, strlen($entry->UpdateTimestamp) - 8);
        $parcelShopCode = $entry->ParcelShopCode;
        $addressNotes = "NULL";
        $addressNotes = $this->extractNotes($entry->ExtraParams);
        $schedulejson = json_encode($entry->Schedule);
        $services = json_encode($entry->Services);
        $MaxParcelOverallSize = "NULL";
        if (isset($entry->MaxParcelOverallSize)) {
            $MaxParcelOverallSize = (string) $entry->MaxParcelOverallSize;
        }

        $MaxParcelValue = "NULL";
        if (isset($entry->MaxParcelValue)) {
            $MaxParcelValue = (string) $entry->MaxParcelValue;
        }

        $MaxParcelWeight = "NULL";
        if (isset($entry->MaxParcelWeight)) {
            $MaxParcelWeight = (string) $entry->MaxParcelWeight;
        }

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
        "','" . $entry->Region .
        "','" . $services .
        "','" . $entry->ZipCode .
            "','" . $schedulejson .
            "' WHERE NOT EXISTS (SELECT * FROM `oc_hermes_parcelshops`WHERE parcelShopCode= '" . $parcelShopCode . "');";
        return $result;
    }
}
