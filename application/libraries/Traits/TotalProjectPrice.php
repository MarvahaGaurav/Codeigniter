<?php

/**
 * Total price for projects
 */
trait TotalProjectPrice
{
    private $priceData;
    private $productCharges;
    private function quotationTotalPrice($userType, $projectId, $level = 0)
    {
        $this->load->model(['ProjectQuotation', 'ProjectRoomProducts', 'ProjectTechnicianCharges']);
        $params['project_id'] = $projectId;
        if ((int)$level > 0) {
            $params['level'] = $level;
        }
        if (in_array((int)$userType, [PRIVATE_USER, BUSINESS_USER], true)) {
            $this->priceData = $this->ProjectQuotation->approvedProjectQuotationPrice($params);
        } else {
            $this->priceData = $this->ProjectTechnicianCharges->totalCharges($params);
        }
        $this->productCharges = $this->ProjectRoomProducts->totalProductCharges([
            'project_id' => $projectId
        ]);

        return $this->totalPriceCalculation();
    }

    private function totalPriceCalculation()
    {
        if (empty($this->priceData)) {
            return (object) [];
        } else {
            if (empty($this->productCharges)) {
                $this->productCharges = [];
            }
            $mainProductData = array_values(array_filter($this->productCharges, function ($product) {
                return (int)$product['type'] === PROJECT_ROOM_MAIN_PRODUCT;
            }));
            $accessoryProduct = array_values(array_filter($this->productCharges, function ($product) {
                return (int)$product['type'] === PROJECT_ROOM_ACCESSORY_PRODUCT;
            }));

            $mainProductPrice = isset($mainProductData[0])&&!empty($mainProductData[0])?sprintf('%.2f', (double)$mainProductData[0]['total_product_price']):0.00;
            $accessoryProductPrice = isset($accessoryProduct[0])&&!empty($accessoryProduct[0])?sprintf('%.2f', (double)$accessoryProduct[0]['total_product_price']):0.00;

            $subTotal = sprintf("%.2f", $mainProductPrice + $accessoryProductPrice + 
            get_percentage($this->priceData['price_per_luminaries'] + $this->priceData['installation_charges'], $this->priceData['discount_price']) 
            + $this->priceData['additional_product_charges']);

            $total = sprintf("%.2f", $mainProductPrice + $accessoryProductPrice + get_percentage(
                get_percentage($this->priceData['price_per_luminaries'] + $this->priceData['installation_charges'], $this->priceData['discount_price']) 
                + $this->priceData['additional_product_charges'],
                $this->priceData['discount']
            ));
            $totalPrice = [
                "price_per_luminaries" => $this->priceData['price_per_luminaries'],
                "installation_charges" => $this->priceData['installation_charges'],
                "discount_price" => $this->priceData['discount_price'],
                "additional_product_charges" => $this->priceData['additional_product_charges'],
                "discount" => $this->priceData['discount'],
                "main_product_price" => $mainProductPrice,
                "accessory_product_price" => $accessoryProductPrice,
                "subtotal" => $subTotal,
                "total" => $total,
                "discounted_price" => sprintf("%.2f", $subTotal - $total)
            ];

            return $totalPrice;
        }
    }
}