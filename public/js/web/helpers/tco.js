(function ($) {

    var tco = {
        existingEnergyPricePerKw: 0.00,
        existingHoursPerYear: 0.00,
        existingLedSourceLifeTime: 0.00,
        existingNumberOfLightSource: 0.00,
        existingNumberOfLuminaries: 0.00,
        existingPricePerLightSource: 0.00,
        existingPriceToChangeLightSource: 0.00,
        existingWattage: 0.00,
        newEnergyPricePerKw: 0.00,
        newHoursPerYear: 0.00,
        newLedSourceLifeTime: 0.00,
        newNumberOfLightSource: 0.00,
        newNumberOfLuminaries: 0.00,
        newPricePerLightSource: 0.00,
        newPriceToChangeLightSource: 0.00,
        newWattage: 0.00,
        yearlyEnergyConsumptionExisting: function () {
            existingEnergyConsumed = (this.existingHoursPerYear * this.existingWattage * this.existingNumberOfLightSource) / 1000;

            return existingEnergyConsumed;
        },
        yearlyEnergyConsumptionNew: function () {
            newEnergyConsumed = (this.newHoursPerYear * this.newWattage * this.newNumberOfLightSource) / 1000;

            return newEnergyConsumed;
        },
        yearlySavingInKwH: function () {
            return this.yearlyEnergyConsumptionExisting() - this.yearlyEnergyConsumptionNew();
        },
        yearlySavingInEuro: function () {
            return this.yearlySavingInKwH() * this.existingEnergyPricePerKw;
        },
        yearlySavingInCo2Emissions: function () {
            return this.yearlySavingInKwH() / 2000;
        },
        yearlySavingInMaintainance: function () {
            var hours = this.existingHoursPerYear / this.existingLedSourceLifeTime;
            var value = this.existingNumberOfLuminaries * this.existingNumberOfLightSource;
            var totalLuminaries = this.existingPricePerLightSource + this.existingPriceToChangeLightSource;
            yearlyMaintainance = ((hours) * value) * (totalLuminaries);

            return yearlyMaintainance;
        },
        yearlyTotal: function () {
            return this.yearlySavingInEuro() + this.yearlySavingInMaintainance();
        },
        returnOnInvestment: function () {
            newLuminaryPrice = 2000;
            priceOfInstallation = 1000;
            var total = (newLuminaryPrice + priceOfInstallation) / this.yearlyTotal();
            return parseFloat(total.toFixed(2));
        },
        setTcoParams: function (params) {
            this.existingEnergyPricePerKw = parseFloat(params.existing_energy_price_per_kw);
            this.existingHoursPerYear = parseFloat(params.existing_hours_per_year);
            this.existingLedSourceLifeTime = parseFloat(params.existing_led_source_life_time);
            this.existingNumberOfLightSource = parseFloat(params.existing_number_of_light_source);
            this.existingNumberOfLuminaries = parseFloat(params.existing_number_of_luminaries);
            this.existingPricePerLightSource = parseFloat(params.existing_price_per_light_source);
            this.existingPriceToChangeLightSource = parseFloat(params.existing_price_to_change_light_source);
            this.existingWattage = parseFloat(params.existing_wattage);
            this.newEnergyPricePerKw = parseFloat(params.new_energy_price_per_kw);
            this.newHoursPerYear = parseFloat(params.new_hours_per_year);
            this.newLedSourceLifeTime = parseFloat(params.new_led_source_life_time);
            this.newNumberOfLightSource = parseFloat(params.new_number_of_light_source);
            this.newNumberOfLuminaries = parseFloat(params.new_number_of_luminaries);
            this.newPricePerLightSource = parseFloat(params.new_price_per_light_source);
            this.newPriceToChangeLightSource = parseFloat(params.new_price_to_change_light_source);
            this.newWattage = parseFloat(params.new_wattage);
        }
    }

    window.tcoCalc = {
        yearlyEnergyConsumptionExisting: tco.yearlyEnergyConsumptionExisting,
        yearlyEnergyConsumptionNew: tco.yearlyEnergyConsumptionNew,
        yearlySavingInKwH: tco.yearlySavingInKwH,
        yearlySavingInEuro: tco.yearlySavingInEuro,
        yearlySavingInCo2Emissions: tco.yearlySavingInCo2Emissions,
        yearlySavingInMaintainance: tco.yearlySavingInMaintainance,
        yearlyTotal: tco.yearlyTotal,
        returnOnInvestment: tco.returnOnInvestment,
        setTcoParams: tco.setTcoParams
    }

})($);