<?php 
defined("BASEPATH") or exit("No direct script access allowed");

require_once "BaseController.php";

class RoomController extends BaseController
{


    public function __construct()
    {
        parent::__construct();
        $this->load->helper("location");
    }

    public function fetch($application_id = "")
    {
        $application_id = encryptDecrypt($application_id, 'decrypt');

        if (!isset($application_id) || empty($application_id)) {
            json_dump(
                [
                    "success" => false,
                    "message" => $this->lang->line("internal_server_error")
                ]
            );
        }

        $this->load->model("UtilModel");
        $data = $this->UtilModel->selectQuery("id, title as text", "rooms", ["where" => ['application_id' => $application_id]]);

        if (empty($data)) {
            json_dump(
                [
                    "success" => false,
                    "message" => $this->lang->line("no_records_found")
                ]
            );
        }

        $data = array_map(
            function ($room) {
                $room['id'] = encryptDecrypt($room['id']);
                return $room;
            },
            $data
        );

        json_dump(
            [
                "success" => true,
                "message" => $this->lang->line("room_fetched"),
                "data" => $data
            ]
        );

    }

    public function applicationRooms()
    {

        $application_id = $this->input->get("application_id");

        $application_id = encryptDecrypt($application_id, 'decrypt');

        if (!isset($application_id) || empty($application_id)) {
            json_dump(
                [
                    "success" => false,
                    "message" => $this->lang->line("internal_server_error")
                ]
            );
        }

        $this->load->model("UtilModel");
        $data = $this->UtilModel->selectQuery(
            "application_id, body, icon, image, language_code, lux_values,
         maintainance_factor, reference_height, reflection_values_ceiling, reflection_values_floor,
        reflection_values_wall, room_id, slug, sub_title, title, ugr, uo",
            "rooms",
            ["where" => ['application_id' => $application_id]]
        );

        if (empty($data)) {
            json_dump(
                [
                    "success" => false,
                    "message" => $this->lang->line("no_records_found")
                ]
            );
        }

        $data = array_map(
            function ($room) {
                $room['room_id'] = encryptDecrypt($room['room_id']);
                return $room;
            },
            $data
        );

        json_dump(
            [
                "success" => true,
                "message" => $this->lang->line("room_fetched"),
                "data" => $data
            ]
        );

    }
}