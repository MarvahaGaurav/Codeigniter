<?php
defined("BASEPATH") or exit("No direct script access allowed");

require_once 'BaseModel.php';


class ProductRelated extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
        $this->tableName = "products_related";
    }

    /**
     * Get related products info
     *
     * @param array $params
     * @return void
     */
    public function get($params = [])
    {
        $this->db->select('title, p.product_id, subtitle, image, how_to_specity')
            ->from($this->tableName)
            ->join("products as p", "p.product_id={$this->tableName}.related_product_id")
            ->where("{$this->tableName}.product_id", $params['product_id']);

        $query = $this->db->get();

        $result = $query->result_array();

        if (!empty($result)) {
            $this->load->model("ProductGallery");
            $images = $this->ProductGallery->get(array_column($result, 'product_id'));
            $this->load->helper(['db', 'debuging']);
            $result = getDataWith($result, $images, 'product_id', 'product_id', 'images', 'image');
        }

        return $result;
    }
}
