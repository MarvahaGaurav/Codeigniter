<?php

trait QuickCalc {
    /**
     * Fetch quick calc
     *
     * @return void
     */
    private function fetchQuickCalcData($data, $uld)
    {
        $this->load->helper(['quick_calc']);
        $request_data = [
            "authToken" => DIALUX_AUTH_TOKEN,
            "roomLength" => floatval($data['length']),
            "roomWidth" => floatval($data['width']),
            "roomHeight" => floatval($data['height']),
            "roomType" => $data['name'],
            "workingPlaneHeight" => floatval($data['working_plane_height']),
            "suspension" => isset($data['suspension_height']) ? floatval($data['suspension_height']) : 0,
            "illuminance" => $data['lux_value'],
            "luminaireCountInX" => floatval($data['luminaries_count_x']),
            "luminaireCountInY" => floatval($data['luminaries_count_y']),
            "rhoCeiling" => floatval($data['rho_ceiling']),
            "rhoWall" => floatval($data['rho_wall']),
            "rhoFloor" => floatval($data['rho_floor']),
            "maintenanceFactor" => floatval($data['maintainance_factor']),
            "uldUri" => $uld
        ];

        $response = hitCulrQuickCal($request_data);

        return $response;
    }
}
