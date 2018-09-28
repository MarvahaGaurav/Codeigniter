(function ($) {
    var $selectCity = $("#select-city");
    var domain = location.protocol + '//' + location.hostname + (location.port ? ':' + location.port : '') + '/admin';
    var domain2 = location.protocol + '//' + location.hostname + (location.port ? ':' + location.port : '');
    
    if ( $selectCity.attr('data-country') && $selectCity.attr('data-country').length > 0 ) {
        var cityOptions = {
            location: $selectCity.attr('data-country')
        };
        setAutoComplete($selectCity, cityOptions);
    }
    function setAutoComplete($element, options){
        options = options || {};
        var location = options.location || "en";
        var autoCompleteOptions = {
            serviceUrl: domain2 + "/xhttp/cities",
                params: {
                    param: location,
                    query: $selectCity.val()
                },
                ajaxSettings: {
                    beforeSend: function () {
                        $selectCity.siblings('.city-loader').addClass('city-loader-show').removeClass('concealable');
                    },
                    success: function () {
                        $selectCity.siblings('.city-loader').removeClass('city-loader-show').addClass('concealable');
                    }
                },
                dataType: 'json',
                showNoSuggestionNotice: true,
                noSuggestionNotice: "No result found",
                onSelect: function (suggestion) {
                    $("#city-id").val(suggestion.data)
                },
                transformResult: function (response) {
                   return ( {
                        suggestions: response.data.map(function(data) {
                            return {value: data.name, data: data.id}
                        })
                    })
                }
        };
        $element.autocomplete(autoCompleteOptions);
    }
    
    var fetchLocation = function (url, parent, source, target, events) {
        var parent = parent || "body";
        var source = source || ".country";
        var target = target || ".cities";
        var events = events || "change";

        $(parent).on(events, source, function () {
            var $self = $(this),
                selfValue = $self.val();
            $selectCity.val('');
            if (!selfValue || (typeof selfValue == "string" && selfValue.length == 0)) {
                return 0;
            }
            var cityOptions = {
                location: selfValue
            };
            setAutoComplete($selectCity, cityOptions);
            return 0;
        });
    }

    window.fetchLocation = fetchLocation;
})($);