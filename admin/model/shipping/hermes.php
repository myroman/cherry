<?php
class ModelShippingHermes extends Model
{
    public function populateParcelShops()
    {
        $log = new Log('test.log');
        $log->write('populating parcel shops');
        
        // create tables
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
            `schedulejson` VARCHAR(1000) NOT NULL,
            `services` VARCHAR(200) NOT NULL,
            `zipcode` VARCHAR(10) NULL,
			PRIMARY KEY (`id`)
          )
          CHARACTER SET utf8 COLLATE utf8_bin");

        // request hermes API or load json
        $filepath = DIR_JSONFILES . 'parcelShops.json';

        if (!file_exists($filepath)) {
            $log->write('cannot find the json ' . $filepath);
            return;
        }

        $jsondata = file_get_contents($filepath);
        
        $json = json_decode($jsondata);
        foreach ($json->GetParcelShopsResult as $item) {
            $sql = $this->createInsertParcelEntrySql($item);
            $this->db->query($sql);
        }
        $log->write('filled new parcel shops');
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
