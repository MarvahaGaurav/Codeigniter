<?php
defined("BASEPATH") or exit("No direct script access allowed");

require_once 'BaseModel.php';

class ProjectRoomTcoValue extends BaseModel
{
    private $datetime;
    private $timestamp;
    public function __construct()
    {
        parent::__construct();
        $this->tableName = 'project_room_tco_values';
        $this->datetime = date("Y-m-d H:i:s");
        $this->timestamp = time();
    }

    public function insert($data)
    {
        $tcoData = [
            'project_room_id' => $data['project_room_id'],
            'existing_number_of_luminaries' => $data['existing_number_of_luminaries'],
            'existing_wattage' => $data['existing_wattage'],
            'existing_led_source_life_time' => $data['existing_led_source_life_time'],
            'existing_hours_per_year' => $data['existing_hours_per_year'],
            'existing_energy_price_per_kw' => $data['existing_energy_price_per_kw'],
            'existing_number_of_light_source' => $data['existing_number_of_light_source'],
            'existing_price_per_light_source' => $data['existing_price_per_light_source'],
            'existing_price_to_change_light_source' => $data['existing_price_to_change_light_source'],
            'new_number_of_luminaries' => $data['new_number_of_luminaries'],
            'new_wattage' => $data['new_wattage'],
            'new_led_source_life_time' => $data['new_led_source_life_time'],
            'new_hours_per_year' => $data['new_hours_per_year'],
            'new_energy_price_per_kw' => $data['new_energy_price_per_kw'],
            'new_number_of_light_source' => $data['new_number_of_light_source'],
            'new_price_per_light_source' => $data['new_price_per_light_source'],
            'new_price_to_change_light_source' => $data['new_price_to_change_light_source'],
            'roi' => $data['roi'],
            'created_at' => $this->datetime,
            'created_at_timestamp' => $this->timestamp,
            'updated_at' => $this->datetime,
            'updated_at_timestamp' => $this->timestamp,
        ];

        $this->db->set($tcoData)
            ->insert($this->tableName);
    }

    public function get($projectRoomIds)
    {
        $this->db->select("project_room_id, existing_number_of_luminaries, existing_wattage, existing_led_source_life_time,
            existing_hours_per_year, existing_energy_price_per_kw, existing_number_of_light_source,
            existing_price_per_light_source, existing_price_to_change_light_source, new_number_of_luminaries, new_wattage,
            new_led_source_life_time, new_hours_per_year, new_energy_price_per_kw, new_number_of_light_source, 
            new_price_per_light_source, new_price_to_change_light_source, roi, created_at, created_at_timestamp, updated_at, updated_at_timestamp")
            ->from($this->tableName)
            ->where_in('project_room_id', $projectRoomIds);

        $query = $this->db->get();

        $result = $query->result_array();

        return $result;
    }

}
