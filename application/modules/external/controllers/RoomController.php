<?php
defined("BASEPATH") or exit("No direct script access allowed");

require_once "BaseController.php";

use GuzzleHttp\Client as GuzzleClient;

class RoomController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("Category");
    }

    public function insertRooms_post()
    {
        try {
            $applications = $this->UtilModel->selectQuery('application_id, language_code', 'applications');
            $insertData = [];
            foreach ($this->language_code as $language) {
                $applicationByLanguage = array_filter($applications, function ($data) use ($language) {
                    return $data['language_code'] === $language;
                });

                $applicationIds = array_column($applicationByLanguage, 'application_id');
                foreach ($applicationIds as $applicationId) {
                    $response = get_sg_data("{$language}/applications/{$applicationId}/rooms");
                    $data = json_decode($response, true);

                    if (empty($data)) {
                        continue;
                    }

                    $roomData = array_map(function ($room) use ($language, $applicationId) {
                        $data['language_code'] = $language;
                        $data['application_id'] = $applicationId;
                        $data['title'] = $room['title'];
                        $data['sub_title'] = $room['subTitle'];
                        $data['room_id'] = $room['id'];
                        $data['image'] = !empty($room['images'])?array_shift($room['images']):'';
                        $data['created_at'] = $this->datetime;
                        $data['updated_at'] = $this->datetime;

                        return $data;
                    }, $data);


                    $insertData = array_merge($insertData, $roomData);
                }
            }
            if (!empty($insertData)) {
                $this->UtilModel->insertBatch('rooms', $insertData);
            }
            $this->response($insertData);
        } catch (\Exception $error) {
            $this->response([
                
            ]);
        }
    }

    public function storeRoomDetails_post()
    {
        try {
            $rooms = $this->UtilModel->selectQuery('room_id, language_code', 'rooms');
            $roomSpecData = [];
            $mountingTypesProducts  = [];
            $relatedProductData = [];
            
            foreach ($this->language_code as $language) {
                $roomsByLanguage = array_filter($rooms, function ($data) use ($language) {
                    return $data['language_code'] === $language;
                });

                $roomIds = array_unique(array_column($roomsByLanguage, 'room_id'));

                foreach ($roomIds as $roomId) {
                    $response = get_sg_data("{$language}/rooms/{$roomId}");
                    $data = json_decode($response, true);
                    if (!empty($data)) {
                        $roomData = [
                            'room_id' => $roomId,
                            'ugr' => $data['data']['ugr'],
                            'uo' => $data['data']['uo'],
                            'reflection_values_wall' => $data['data']['reflectionValues']['wall'],
                            'reflection_values_ceiling' => $data['data']['reflectionValues']['ceiling'],
                            'reflection_values_floor' => $data['data']['reflectionValues']['floor'],
                            'maintainance_factor' => $data['data']['maintenanceFactor'],
                            'lux_values' => $data['data']['luxValues'],
                            'reference_height' => $data['data']['referenceHeight'],
                            'body' => $data['body']
                        ];
                        $productsByMountingTypes = array_filter($data['products'], function ($data) {
                            return $data['type'] !== "Related products";
                        });
                        $relatedProducts = array_filter($data['products'], function ($data) {
                            return $data['type'] === "Related products";
                        });

                        $productData = array_map(function ($product) use ($roomId) {
                            $data['room_id'] = $roomId;
                            $data['product_id'] = $product['id'];
                            $data['title'] = $product['title'];
                            $data['type'] = mounting_type_str_to_num($product['type']);
                            $data['type_string'] = $product['type'];
                            $data['created_at'] = $this->datetime;
                            $data['updated_at'] = $this->datetime;
                            return $data;
                        }, $productsByMountingTypes);

                        $relatedProducts = array_map(function ($product) use ($roomId) {
                            $data['room_id'] = $roomId;
                            $data['product_id'] = $product['id'];
                            $data['created_at'] = $this->datetime;
                            $data['updated_at'] = $this->datetime;
                            return $data;
                        }, $relatedProducts);

                        $roomSpecData = array_merge($roomSpecData, [$roomData]);
                        $relatedProductData = array_merge($relatedProductData, $relatedProducts);
                        $mountingTypesProducts = array_merge($mountingTypesProducts, $productData);
                    }
                }
            }
            $this->db->trans_begin();
            $this->UtilModel->insertBatch('related_products_room', $relatedProductData);
            $this->UtilModel->insertBatch('room_products', $mountingTypesProducts);
            $this->UtilModel->updateBatch('rooms', $roomSpecData, 'room_id');
            $this->db->trans_commit();
            $this->response($roomSpecData);
        } catch (\Exception $error) {
            $this->db->trans_rollback();
            $this->response([

            ]);
        }
    }

    // private function
}
