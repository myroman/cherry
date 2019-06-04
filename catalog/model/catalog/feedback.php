<?php
class ModelCatalogFeedback extends Model
{
    public function addFeedback($data) {
        $sql = "INSERT INTO " . DB_PREFIX . "feedback SET " . 
        "`fullname` = '" . $this->db->escape($data['fullname']) . "'" . 
        ",`email` = '" . $this->db->escape($data['email']) . "'" . 
        ",`phone` = '" . $this->db->escape($data['phone']) . "'" .         
        ",`message` = '" . $this->db->escape($data['message']) . "'" .      
        ",`createdate` = NOW()";

        $this->db->query($sql);

		$id = $this->db->getLastId();
        $data['id'] = $id;
    }
}