<div class="inner-container">
    <div class="container">

        <!-- breadcrumb -->
        <ul class="breadcrumb">
            <li><a href="javascript:void(0)">Home</a></li>
            <li><a href="<?php echo base_url("home/projects") ?>">Project</a></li>
            <li><a href="<?php echo base_url("home/projects/".encryptDecrypt($roomData['project_id'], "encrypt")."/levels/".$roomData['level']."/rooms") ?>">Rooms</a></li>
            <li><a href="<?php echo base_url("home/projects/".encryptDecrypt($roomData['project_id'], "encrypt")."/levels/".$roomData['level']."/rooms/results") ?>">Result</a></li>
            <li class="active">TCO</li>
        </ul>
        <!-- //breadcrumb -->

        <div class="page-heading">
            <h1 class="page-title">TCO : <?php echo !empty($roomData['reference_name'])?$roomData['reference_name']:$roomData['name'] ?></h1>
            <p class="prj-description">SG Lighting has vast experience of and expertise in a wide range of different
                types of projects, such as schools, hospitals, offices, industry, retail and outdoor lighting. Under
                each type of project in the overview below, there are references to the various areas, as well as
                product recommendations.</p>
        </div>

        <!-- Caption before section -->
        <!-- <div class="section-title clearfix">
            <h3 class="pull-left">Price Comparison</h3>
            <div class="button-wrapper-two pull-right">
                <a href="javascript:void(0)" class="custom-btn btn-width save">
                    Show Competitor Price
                </a>
            </div>
        </div> -->
        <div class="section-title clearfix">
            <h3 class="pull-left">Price Comparison</h3>
            <div class="button-wrapper-two pull-right">
            <label class="switch">
                 Show Competitor Price
                <input type="checkbox" id="competitor_show">
            </label>
            </div>
        </div>
        <!-- Caption before section -->

        <!-- Project list table -->
        <?php echo form_open('', ['id' => 'tco-form']) ?>
        <div class="table-responsive table-wrapper" id="scrollbar-inner">
            <table cellspacing="0" class="table-custom">
                <thead>
                    <tr>
                        <th class="price-comparison-txt"></th>
                        <th>Existing</th>
                        <th>New</th>
                        <th>Competitor</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="op-semibold">Number Of Luminaries</td>
                        <td>
                            <div class="price-comparison">
                                <input type="text" class="number-only-field tco-calc-fields restrict-characters" data-restrict-to="10" readonly name="existing_number_of_luminaries" value="<?php echo isset($tcoData['existing_number_of_luminaries'])?$tcoData['existing_number_of_luminaries']:$roomData['luminaries_count_x'] * $roomData['luminaries_count_y'] ?>" >
                            </div>
                        </td>
                        <td>
                            <div class="price-comparison">
                                <input type="text" class="number-only-field tco-calc-fields restrict-characters" data-restrict-to="10" readonly name="new_number_of_luminaries" value="<?php echo isset($tcoData['new_number_of_luminaries'])?$tcoData['new_number_of_luminaries']:$roomData['luminaries_count_x'] * $roomData['luminaries_count_y'] ?>" >
                            </div>
                        </td>
                        <td>
                            <div class="price-comparison competitor" style="display: none;">
                                <input type="text" value=""  >
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="op-semibold">Wattage</td>
                        <td>
                            <div class="price-comparison">
                                <input type="text" class="number-only-field tco-calc-fields restrict-characters" data-restrict-to="10" name="existing_wattage" value="<?php echo isset($tcoData['existing_wattage'])?$tcoData['existing_wattage']:'' ?>" >
                            </div>
                        </td>
                        <td>
                            <div class="price-comparison">
                                <input type="text" class="number-only-field tco-calc-fields restrict-characters" data-restrict-to="10"  readonly name="new_wattage" value="<?php echo isset($tcoData['new_wattage'])?$tcoData['new_wattage']:(double)$productData['wattage'] ?>" >
                            </div>
                        </td>
                        <td>
                            <div class="price-comparison competitor" style="display: none;">
                                <input type="text" value="" >
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="op-semibold">Led Source Life Time</td>
                        <td>
                            <div class="price-comparison">
                                <input type="text" class="number-only-field tco-calc-fields restrict-characters" data-restrict-to="10" name="existing_led_source_life_time" value="<?php echo isset($tcoData['existing_led_source_life_time'])?$tcoData['existing_led_source_life_time']:'' ?>" >
                            </div>
                        </td    >
                        <td>
                            <div class="price-comparison">
                                <input type="text" name="new_led_source_life_time" class="number-only-field tco-calc-fields restrict-characters" data-restrict-to="10" readonly value="<?php echo isset($productData['lifetime_hours'])&&!empty($productData['lifetime_hours'])?$productData['lifetime_hours']:$tcoData['new_led_source_life_time'] ?>" >
                            </div>
                        </td>
                        <td>
                            <div class="price-comparison competitor" style="display: none;">
                                <input type="text" value="" >
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="op-semibold">Hours Per Year</td>
                        <td>
                            <div class="price-comparison">
                                <input type="text" class="number-only-field tco-calc-fields restrict-characters" data-restrict-to="10" name="existing_hours_per_year" value="<?php echo isset($tcoData['existing_hours_per_year'])?$tcoData['existing_hours_per_year']:'' ?>" >
                            </div>
                        </td>
                        <td>
                            <div class="price-comparison">
                                <input type="text" class="number-only-field tco-calc-fields restrict-characters" data-restrict-to="10" name="new_hours_per_year" value="<?php echo isset($tcoData['new_hours_per_year'])?$tcoData['new_hours_per_year']:'' ?>" >
                            </div>
                        </td>
                        <td>
                            <div class="price-comparison competitor" style="display: none;">
                                <input type="text" value="" >
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="op-semibold">Energy Price Per Kw</td>
                        <td>
                            <div class="price-comparison">
                                <input type="text" class="number-only-field tco-calc-fields restrict-characters" data-restrict-to="10" name="existing_energy_price_per_kw" value="<?php echo isset($tcoData['existing_energy_price_per_kw'])?$tcoData['existing_energy_price_per_kw']:'' ?>" >
                            </div>
                        </td>
                        <td>
                            <div class="price-comparison">
                                <input type="text" class="number-only-field tco-calc-fields restrict-characters" data-restrict-to="10" name="new_energy_price_per_kw" value="<?php echo isset($tcoData['new_energy_price_per_kw'])?$tcoData['new_energy_price_per_kw']:'' ?>" >
                            </div>
                        </td>
                        <td>
                            <div class="price-comparison competitor" style="display: none;">
                                <input type="text" value="" >
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="op-semibold">Number Of Light Source</td>
                        <td>
                            <div class="price-comparison">
                                <input type="text" class="number-only-field tco-calc-fields restrict-characters" data-restrict-to="10" name="existing_number_of_light_source" value="<?php echo isset($tcoData['existing_number_of_light_source'])?$tcoData['existing_number_of_light_source']:'' ?>" >
                            </div>
                        </td>
                        <td>
                            <div class="price-comparison">
                                <input type="text" name="new_number_of_light_source" value="<?php echo isset($tcoData['new_number_of_light_source'])?$tcoData['new_number_of_light_source']:1 ?>" >
                            </div>
                        </td>
                        <td>
                            <div class="price-comparison competitor" style="display: none;">
                                <input type="text" value="" >
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="op-semibold">Price Per Light Source</td>
                        <td>
                            <div class="price-comparison">
                                <input type="text" class="number-only-field tco-calc-fields restrict-characters" data-restrict-to="10" name="existing_price_per_light_source" value="<?php echo isset($tcoData['existing_price_per_light_source'])?$tcoData['existing_price_per_light_source']:'' ?>" >
                            </div>
                        </td>
                        <td>
                            <div class="price-comparison">
                                <input type="text" class="number-only-field tco-calc-fields restrict-characters" data-restrict-to="10" readonly name="new_price_per_light_source" value="<?php echo isset($tcoData['new_price_per_light_source'])?$tcoData['new_price_per_light_source']:0 ?>" >
                            </div>
                        </td>
                        <td>
                            <div class="price-comparison competitor" style="display: none;">
                                <input type="text" value="" >
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="op-semibold">Price To Change Light Source</td>
                        <td>
                            <div class="price-comparison">
                                <input type="text" class="number-only-field tco-calc-fields restrict-characters" data-restrict-to="10" name="existing_price_to_change_light_source" value="<?php echo isset($tcoData['existing_price_to_change_light_source'])?$tcoData['existing_price_to_change_light_source']:'' ?>" >
                            </div>
                        </td>
                        <td>
                            <div class="price-comparison">
                                <input type="text" class="number-only-field tco-calc-fields restrict-characters" data-restrict-to="10" readonly name="new_price_to_change_light_source" value="<?php echo isset($tcoData['new_price_to_change_light_source'])?$tcoData['new_price_to_change_light_source']:0 ?>" >
                            </div>
                        </td>
                        <td>
                            <div class="price-comparison competitor" style="display: none;">
                                <input type="text" value="" >
                            </div>
                        </td>
                    </tr>
                    <?php if (isset($tcoData['roi'])) { ?>
                    <tr>
                        <td class="op-semibold">ROI</td>
                        <td>
                            <div class="price-comparison">
                                <input type="text" name="roi" value="<?php echo isset($tcoData['roi'])?$tcoData['roi']:'' ?>" >
                            </div>
                        </td>
                        <td>
                            <div class="price-comparison">
                            </div>
                        </td>
                        <td>
                            <div class="price-comparison competitor" style="display: none;">
                            </div>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
        <!-- //Project list table -->
        <div class="table-responsive table-wrapper clearfix concealable" id="calculation-section">
            <table class="table-custom">
                <thead>
                    <tr>
                        <th colspan="10">Calculation</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="op-semibold">Yearly Energy Consumption Existing</td>
                        <td class="calculated-value" id="yearly-energy-consumption-existing"></td>
                    </tr>
                    <tr>
                        <td class="op-semibold">Yearly Energy Consumption New</td>
                        <td class="calculated-value" id="yearly-energy-consumption-new"></td>
                    </tr>
                    <tr>
                        <td class="op-semibold">Yearly Saving In Kw H</td>
                        <td class="calculated-value" id="yearly-saving-in-kw-h"></td>
                    </tr>
                    <tr>
                        <td class="op-semibold">Yearly Saving In Euro</td>
                        <td class="calculated-value" id="yearly-saving-in-euro"></td>
                    </tr>
                    <tr>
                        <td class="op-semibold">Yearly Saving In Co2 Emissions</td>
                        <td class="calculated-value" id="yearly-saving-in-co2-emissions"></td>
                    </tr>
                    <tr>
                        <td class="op-semibold">Yearly Saving In Maintainance</td>
                        <td class="calculated-value" id="yearly-saving-in-maintainance"></td>
                    </tr>
                    <tr>
                        <td class="op-semibold">Yearly Total</td>
                        <td class="calculated-value" id="yearly-total"></td>
                    </tr>
                    <tr>
                        <td class="op-semibold">Return On Investment</td>
                        <td class="calculated-value" id="return-on-investment"></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <!-- Caption before section -->
        <div class="section-title clearfix">
            <div class="button-wrapper">
                <!-- <a href="javascript:void(0)" class="custom-btn btn-margin btn-width save">
                    Next Room
                </a> -->
                <input type="submit" value="Done" class="custom-btn btn-margin btn-width save">
            </div>
        </div>
        <?php echo form_close() ?>
        <!-- Caption before section -->

        <!-- no record found -->
        <!-- <div class="no-record text-center">
                    <img src="../../images/no-found-note.png" alt="Note Paper">
                    <p>You have no room.</p>
                    <p>Tap on <a href="login.html" class="page-link">Add Room</a> button to add a room.</p>
                </div> -->
        <!-- no record found -->

    </div>
</div>