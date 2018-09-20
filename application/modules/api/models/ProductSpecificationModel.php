<?php 
defined("BASEPATH") OR exit("No direct script access allowed");

class ProductSpecificationModel extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
        $this->tableName = "product_gallery";
    }

    /**
     * Product Specification Listing/Details
     *
     * @param  array $params
     * @return array
     */
    public function get($params = [])
    {
        $this->db->select(
            'SQL_CALC_FOUND_ROWS id, articlecode as article_code, title,
        luminaire_class, ingress_protection_rating, mouting, '
        );
    }

    /**
     * Get details
     *
     * @return void
     */
    public function details()
    {

    }
}