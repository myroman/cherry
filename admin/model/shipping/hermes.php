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
			`parcelShopCode` VARCHAR(100) COLLATE utf8_bin NOT NULL,
			PRIMARY KEY (`id`)
		  )");

        // request hermes API or load json
        $filepath = DIR_JSONFILES . 'test.json';

        if (!file_exists($filepath)) {
            $log->write('cannot find the json ' . $filepath);
            return;
        }

        $jsondata = file_get_contents($filepath);
        //parse json
        $json = json_decode($jsondata);
        $insertSql = '';
        foreach ($json->GetParcelShopsResult as $item) {
            // $log->write('Address:' . $item->Address);
            $sql = $this->createInsertParcelEntrySql($item);
            $insertSql = $insertSql . $sql . "
            ";

        }
        $log->write($insertSql);

        $log->write('finished parsing');

//fill the table

    }

    private function createInsertParcelEntrySql($entry)
    {
        $key = $entry->ParcelShopCode;
        $result =
            "INSERT INTO `oc_hermes_parcelshops` (createdate, parcelShopCode)
        SELECT UTC_TIMESTAMP(), '" . $key . "' WHERE NOT EXISTS (SELECT * FROM `oc_hermes_parcelshops`WHERE parcelShopCode= '" . $key . "');";

        //

        return $result;
    }
}
