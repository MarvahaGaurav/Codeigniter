<?php

class Tco
{
    private $existingNumberOfLuminaries;
    private $existingWattage;
    private $existingLedSourceLifeTime;
    private $existingHoursPerYear;
    private $existingEnergyPricePerKw;
    private $existingNumberOfLightSource;
    private $existingPricePerLightSource;
    private $existingPriceToChangeLightSource;
    private $newNumberOfLuminaries;
    private $newWattage;
    private $newLedSourceLifeTime;
    private $newHoursPerYear;
    private $newEnergyPricePerKw;
    private $newNumberOfLightSource;
    private $newPricePerLightSource;
    private $newPriceToChangeLightSource;
    private $roi;

    public function __construct()
    {
        $this->existingNumberOfLuminaries = 0;
        $this->existingWattage = 0;
        $this->existingLedSourceLifeTime = 0;
        $this->existingHoursPerYear = 0;
        $this->existingEnergyPricePerKw = 0;
        $this->existingNumberOfLightSource = 0;
        $this->existingPricePerLightSource = 0;
        $this->existingPriceToChangeLightSource = 0;
        $this->newNumberOfLuminaries = 0;
        $this->newWattage = 0;
        $this->newLedSourceLifeTime = 0;
        $this->newHoursPerYear = 0;
        $this->newEnergyPricePerKw = 0;
        $this->newNumberOfLightSource = 0;
        $this->newPricePerLightSource = 0;
        $this->newPriceToChangeLightSource = 0;
        $this->roi = 0;
    }

    public function setTcoParams($params)
    {
        $this->existingNumberOfLuminaries = $params['existing_number_of_luminaries'];
        $this->existingWattage = $params['existing_wattage'];
        $this->existingLedSourceLifeTime = $params['existing_led_source_life_time'];
        $this->existingHoursPerYear = $params['existing_hours_per_year'];
        $this->existingEnergyPricePerKw = $params['existing_energy_price_per_kw'];
        $this->existingNumberOfLightSource = $params['existing_number_of_light_source'];
        $this->existingPricePerLightSource = $params['existing_price_per_light_source'];
        $this->existingPriceToChangeLightSource = $params['existing_price_to_change_light_source'];
        $this->newNumberOfLuminaries = $params['new_number_of_luminaries'];
        $this->newWattage = $params['new_wattage'];
        $this->newLedSourceLifeTime = $params['new_led_source_life_time'];
        $this->newHoursPerYear = $params['new_hours_per_year'];
        $this->newEnergyPricePerKw = $params['new_energy_price_per_kw'];
        $this->newNumberOfLightSource = $params['new_number_of_light_source'];
        $this->newPricePerLightSource = $params['new_price_per_light_source'];
        $this->newPriceToChangeLightSource = $params['new_price_to_change_light_source'];
    }

    public function setExistingNumberOfLuminaries($existingNumberOfLuminaries)
    {
        $this->existingNumberOfLuminaries = $existingNumberOfLuminaries;
    }

    public function getExistingNumberOfLuminaries()
    {
        return $this->existingNumberOfLuminaries;
    }

    public function setExistingWattage($existingWattage)
    {
        $this->existingWattage = $existingWattage;
    }

    public function getExistingWattage()
    {
        return $this->existingWattage;
    }

    public function setExistingLedSourceLifeTime($existingLedSourceLifeTime)
    {
        $this->existingLedSourceLifeTime = $existingLedSourceLifeTime;
    }

    public function getExistingLedSourceLifeTime()
    {
        return $this->existingLedSourceLifeTime;
    }

    public function setExistingHoursPerYear($existingHoursPerYear)
    {
        $this->existingHoursPerYear = $existingHoursPerYear;
    }

    public function getExistingHoursPerYear()
    {
        return $this->existingHoursPerYear;
    }

    public function setExistingEnergyPricePerKw($existingEnergyPricePerKw)
    {
        $this->existingEnergyPricePerKw = $existingEnergyPricePerKw;
    }

    public function getExistingEnergyPricePerKw()
    {
        return $this->existingEnergyPricePerKw;
    }

    public function setExistingNumberOfLightSource($existingNumberOfLightSource)
    {
        $this->existingNumberOfLightSource = $existingNumberOfLightSource;
    }

    public function getExistingNumberOfLightSource()
    {
        return $this->existingNumberOfLightSource;
    }

    public function setExistingPricePerLightSource($existingPricePerLightSource)
    {
        $this->existingPricePerLightSource = $existingPricePerLightSource;
    }

    public function getExistingPricePerLightSource()
    {
        return $this->existingPricePerLightSource;
    }

    public function setExistingPriceToChangeLightSource($existingPriceToChangeLightSource)
    {
        $this->existingPriceToChangeLightSource = $existingPriceToChangeLightSource;
    }

    public function getExistingPriceToChangeLightSource()
    {
        return $this->existingPriceToChangeLightSource;
    }

    public function setNewNumberOfLuminaries($newNumberOfLuminaries)
    {
        $this->newNumberOfLuminaries = $newNumberOfLuminaries;
    }

    public function getNewNumberOfLuminaries()
    {
        return $this->newNumberOfLuminaries;
    }

    public function setNewWattage($newWattage)
    {
        $this->newWattage = $newWattage;
    }

    public function getNewWattage()
    {
        return $this->newWattage;
    }

    public function setNewLedSourceLifeTime($newLedSourceLifeTime)
    {
        $this->newLedSourceLifeTime = $newLedSourceLifeTime;
    }

    public function getNewLedSourceLifeTime()
    {
        return $this->newLedSourceLifeTime;
    }

    public function setNewHoursPerYear($newHoursPerYear)
    {
        $this->newHoursPerYear = $newHoursPerYear;
    }

    public function getNewHoursPerYear()
    {
        return $this->newHoursPerYear;
    }

    public function setNewEnergyPricePerKw($newEnergyPricePerKw)
    {
        $this->newEnergyPricePerKw = $newEnergyPricePerKw;
    }

    public function getNewEnergyPricePerKw()
    {
        return $this->newEnergyPricePerKw;
    }

    public function setNewNumberOfLightSource($newNumberOfLightSource)
    {
        $this->newNumberOfLightSource = $newNumberOfLightSource;
    }

    public function getNewNumberOfLightSource()
    {
        return $this->newNumberOfLightSource;
    }

    public function setNewPricePerLightSource($newPricePerLightSource)
    {
        $this->newPricePerLightSource = $newPricePerLightSource;
    }

    public function getNewPricePerLightSource()
    {
        return $this->newPricePerLightSource;
    }

    public function setNewPriceToChangeLightSource($newPriceToChangeLightSource)
    {
        $this->newPriceToChangeLightSource = $newPriceToChangeLightSource;
    }

    public function getNewPriceToChangeLightSource()
    {
        return $this->newPriceToChangeLightSource;
    }

    public function setRoi($roi)
    {
        $this->roi = $roi;
    }

    public function getRoi()
    {
        return $this->roi;
    }


    public function yearlyEnergyConsumptionExisting()
    {
        $existingEnergyConsumed = ($this->existingHoursPerYear * $this->existingWattage * $this->existingNumberOfLightSource) / 100;

        return $existingEnergyConsumed;
    }

    public function yearlyEnergyConsumptionNew()
    {
        $newEnergyConsumed = ($this->newHoursPerYear * $this->newWattage * $this->newNumberOfLightSource) / 100;

        return $newEnergyConsumed;
    }

    public function yearlySavingInKwH()
    {
        return $this->yearlyEnergyConsumptionExisting() - $this->yearlyEnergyConsumptionNew();
    }

    public function yearlySavingInEuro()
    {
        return $this->yearlySavingInKwH() * 0.3;
    }

    public function yearlySavingInCo2Emissions()
    {
        return $this->yearlySavingInKwH() * 2000;
    }

    public function yearlySavingInMaintainance()
    {
        $yearlyMaintainance = (($this->existingHoursPerYear / $this->existingLedSourceLifeTime) * $this->existingNumberOfLuminaries * $this->existingNumberOfLightSource) * ($this->existingPricePerLightSource + $this->existingPriceToChangeLightSource);

        return $yearlyMaintainance;
    }

    public function yearlyTotal()
    {
        return $this->yearlySavingInEuro() + $this->yearlySavingInMaintainance();
    }

    public function returnOnInvestment()
    {
        $newLuminaryPrice = 2000;
        $priceOfInstallation = 1000;
        return ($newLuminaryPrice + $priceOfInstallation) / $this->yearlyTotal();
    }

}

