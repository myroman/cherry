<?php
class ModelCatalogFeedback extends Model {
	// public function add($data) {
	// 	$this->event->trigger('pre.admin.review.add', $data);

	// 	$this->db->query("INSERT INTO " . DB_PREFIX . "review SET author = '" . $this->db->escape($data['author']) . "', product_id = '" . (int)$data['product_id'] . "', text = '" . $this->db->escape(strip_tags($data['text'])) . "', rating = '" . (int)$data['rating'] . "', status = '" . (int)$data['status'] . "', date_added = NOW()");

	// 	$review_id = $this->db->getLastId();

	// 	$this->cache->delete('product');

	// 	$this->event->trigger('post.admin.review.add', $review_id);

	// 	return $review_id;
	// }

	// public function update($review_id, $data) {
	// 	$this->event->trigger('pre.admin.review.edit', $data);

	// 	$this->db->query("UPDATE " . DB_PREFIX . "review SET author = '" . $this->db->escape($data['author']) . "', product_id = '" . (int)$data['product_id'] . "', text = '" . $this->db->escape(strip_tags($data['text'])) . "', rating = '" . (int)$data['rating'] . "', status = '" . (int)$data['status'] . "', date_modified = NOW() WHERE review_id = '" . (int)$review_id . "'");

	// 	$this->cache->delete('product');

	// 	$this->event->trigger('post.admin.review.edit', $review_id);
	// }

	// public function delete($review_id) {
	// 	$this->event->trigger('pre.admin.review.delete', $review_id);

	// 	$this->db->query("DELETE FROM " . DB_PREFIX . "review WHERE review_id = '" . (int)$review_id . "'");

	// 	$this->cache->delete('product');

	// 	$this->event->trigger('post.admin.review.delete', $review_id);
	// }

	// public function get($review_id) {
	// 	$query = $this->db->query("SELECT DISTINCT *, (SELECT pd.name FROM " . DB_PREFIX . "product_description pd WHERE pd.product_id = r.product_id AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS product FROM " . DB_PREFIX . "review r WHERE r.review_id = '" . (int)$review_id . "'");

	// 	return $query->row;
	// }

	public function getList($data = array()) {
		$sql = "SELECT f.`id` as feedback_id, f.`fullname`, f.`email`, f.`phone`, f.`message`, f.`createdate` as date_added " .
			"FROM oc_feedback f " .
			"WHERE 1=1";

		if (!empty($data['filter_fullname'])) {
			$sql .= " AND f.`fullname` LIKE '" . $this->db->escape($data['filter_fullname']) . "%' ";
		}

		if (!empty($data['filter_date_added'])) {
			$sql .= " AND DATE(f.createdate) = DATE('" . $this->db->escape($data['filter_date_added']) . "') ";
		}

		$sort_data = array(
			'f.fullname',
			'f.createdate'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY f.createdate";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getTotals($data = array()) {
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "feedback f WHERE 1=1 ";

		if (!empty($data['filter_fullname'])) {
			$sql .= " AND f.`fullname` LIKE '" . $this->db->escape($data['filter_fullname']) . "%' ";
		}

		if (!empty($data['filter_date_added'])) {
			$sql .= " AND DATE(f.createdate) = DATE('" . $this->db->escape($data['filter_date_added']) . "') ";
		}
		
		$query = $this->db->query($sql);

		return $query->row['total'];
	}
}