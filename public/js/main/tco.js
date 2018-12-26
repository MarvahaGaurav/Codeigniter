requirejs.config({
  baseUrl: "public/js",
  waitSeconds: 60,
  paths: {
    jquery: "jquery.min",
    bootstrap: "bootstrap.min",
    common: "web/common",
    jqueryScrollbar: "plugin/jquery.scrollbar.min",
    jqueryValidator: 'jquery.validate.min',
    helper: 'web/helpers/tco'
  },
  shim: {
    //dependencies
    bootstrap: ['jquery'],
    common: ['bootstrap'],
    jqueryScrollbar: ['jquery'],
    jqueryValidator: ['jquery'],
    helper: ['common']
  }
});

requirejs(
  ["jquery", "bootstrap", "common", "jqueryScrollbar", "jqueryValidator", "helper"],
  function ($) {
    var $exisitingEnergyPrice = $("input[name='existing_energy_price_per_kw']"),
      $newEnergyPrice = $("input[name='new_energy_price_per_kw']"),
      $existingHoursPerYear = $("input[name='existing_hours_per_year']"),
      $newHoursPerYear = $("input[name='new_hours_per_year']"),
      $tcoForm = $("#tco-form"),
      $yearlyEnergyConsumptionExisting = $("#yearly-energy-consumption-existing"),
      $yearlyEnergyConsumptionNew = $("#yearly-energy-consumption-new"),
      $yearlySavingInKwH = $("#yearly-saving-in-kw-h"),
      $yearlySavingInEuro = $("#yearly-saving-in-euro"),
      $yearlySavingInCo2Emissions = $("#yearly-saving-in-co2-emissions"),
      $yearlySavingInMaintainance = $("#yearly-saving-in-maintainance"),
      $yearlyTotal = $("#yearly-total"),
      $returnOnInvestment = $("#return-on-investment"),
      $calculationSection = $("#calculation-section");

    $exisitingEnergyPrice.on('keyup change', function () {
      var self = this,
        $self = $(self),
        value = $self.val();
      $newEnergyPrice.val(value);
    });

    $newEnergyPrice.on('keyup change', function () {
      var self = this,
        $self = $(self),
        value = $self.val();
      $exisitingEnergyPrice.val(value);
    });

    $existingHoursPerYear.on('keyup change', function () {
      var self = this,
        $self = $(self),
        value = $self.val();
      $newHoursPerYear.val(value);
    });

    $newHoursPerYear.on('keyup change', function () {
      var self = this,
        $self = $(self),
        value = $self.val();
      $existingHoursPerYear.val(value);
    });

    var validationRules = {
      project_room_id: {
        required: true
      },
      existing_number_of_luminaries: {
        required: true
      },
      existing_wattage: {
        required: true
      },
      existing_led_source_life_time: {
        required: true
      },
      existing_hours_per_year: {
        required: true
      },
      existing_energy_price_per_kw: {
        required: true
      },
      existing_number_of_light_source: {
        required: true
      },
      existing_price_per_light_source: {
        required: true
      },
      existing_price_to_change_light_source: {
        required: true
      },
      new_number_of_luminaries: {
        required: true
      },
      new_wattage: {
        required: true
      },
      new_led_source_life_time: {
        required: true
      },
      new_hours_per_year: {
        required: true
      },
      new_energy_price_per_kw: {
        required: true
      },
      new_number_of_light_source: {
        required: true
      },
      new_price_per_light_source: {
        required: true
      },
      new_price_to_change_light_source: {
        required: true
      }
    };

    $(".tco-calc-fields").on('keyup', function () {
      var self = this,
        $self = $(self),
        formData = getFormData($tcoForm),
        calculations = {};

      tcoCalc.setTcoParams(formData);


      calculations.yearlyEnergyConsumptionExisting = tcoCalc.yearlyEnergyConsumptionExisting();
      calculations.yearlyEnergyConsumptionNew = tcoCalc.yearlyEnergyConsumptionNew();
      calculations.yearlySavingInKwH = tcoCalc.yearlySavingInKwH();
      calculations.yearlySavingInEuro = tcoCalc.yearlySavingInEuro();
      calculations.yearlySavingInCo2Emissions = tcoCalc.yearlySavingInCo2Emissions();
      calculations.yearlySavingInMaintainance = tcoCalc.yearlySavingInMaintainance();
      calculations.yearlyTotal = tcoCalc.yearlyTotal();
      calculations.returnOnInvestment = tcoCalc.returnOnInvestment();

      if (calculations.returnOnInvestment > 0 && calculations.returnOnInvestment != Infinity) {
        $calculationSection.removeClass("concealable");
        $yearlyEnergyConsumptionExisting.html(calculations.yearlyEnergyConsumptionExisting);
        $yearlyEnergyConsumptionNew.html(calculations.yearlyEnergyConsumptionNew);
        $yearlySavingInKwH.html(calculations.yearlySavingInKwH);
        $yearlySavingInEuro.html(calculations.yearlySavingInEuro);
        $yearlySavingInCo2Emissions.html(calculations.yearlySavingInCo2Emissions);
        $yearlySavingInMaintainance.html(calculations.yearlySavingInMaintainance);
        $yearlyTotal.html(calculations.yearlyTotal);
        $returnOnInvestment.html(calculations.returnOnInvestment);
      } else {
        $calculationSection.addClass("concealable");
      }
    });

    $(function () {
      checkval(); // this is launched on load
      $('#competitor_show').click(function () {
        checkval(); // this is launched on checkbox click
      });

    });

    function checkval() {

      if ($('#competitor_show').is(':checked')) {

        $('.competitor').css("display", "block");
      } else {
        $('.competitor').css("display", "none");
      }

    }

    $("#tco-form").validate({
      rules: validationRules
    });
  },
  function () {

  }
);
