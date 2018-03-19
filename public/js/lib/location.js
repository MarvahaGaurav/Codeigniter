(function ($) {
    var $selectCity = $("#select-city");
    var domain = location.protocol + '//' + location.hostname + (location.port ? ':' + location.port : '') + '/admin';
    var domain2 = location.protocol + '//' + location.hostname + (location.port ? ':' + location.port : '');
    
    if ( $selectCity.attr('data-country').length > 0 ) {
        var cityOptions = {
            location: $selectCity.attr('data-country')
        };
        setAutoComplete($selectCity, cityOptions);
    }
    function setAutoComplete($element, options){
        options = options || {};
        var location = options.location || "en";
        var autoCompleteOptions = {
            url: function(search) {
                return domain2 + "/xhttp/cities?param=" + location + "&query=" + search;
            },
            listLocation: "data",
            getValue: function(data) {
                return data.name
            },
            ajaxSettings: {
                dataType: "json",
                success: function(response) {
                    if ( ! response.success ) {
                        $("#city-id").val('');
                        $("ul.nolistdata").show();                      
                    } else {
                        $("ul.nolistdata").hide();
                    }
                }
            },
            list: {
                maxNumberOfElements: 8,
                match: {
                    enabled: true
                },
                sort: {
                    enabled: true
                },
                onSelectItemEvent: function() {
                    var value = $element.getSelectedItemData().id;
                    $("#city-id").val(value);
                }
            },
            theme: "square",
            requestDelay: 70,
            placeholder: "Please start typing your city..."
        };
        $element.easyAutocomplete(autoCompleteOptions);
    }
    
    var fetchLocation = function (url, parent, source, target, events) {
        var parent = parent || "body";
        var source = source || ".country";
        var target = target || ".cities";
        var events = events || "change";

        $(parent).on(events, source, function () {
            var $self = $(this),
                selfValue = $self.val();
            $.ajax({
                url: domain2 + url,
                method: "GET",
                data: {
                    param: selfValue
                },
                dataType: "json",
                beforeSend: function() {
                    $selectCity.prop("disabled", false);
                    $selectCity.val("");
                },
                success: function (response) {
                    if ( response.success ) {
                        var data = response.data
                        data = data.map(function(row){
                            return {id: row.id, text: row.name};
                        });
                        var cityOptions = {
                            location: selfValue
                        };
                        setAutoComplete($selectCity, cityOptions);
                        // $(target).html(optionsViewBuilder(data, "Select a city"));
                    }
                }, 
                error: function() {

                }
            });
        });
    }

    window.fetchLocation = fetchLocation;
})($);