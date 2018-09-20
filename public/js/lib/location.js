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
        console.log(location)
        var autoCompleteOptions = {
            serviceUrl: domain2 + "/xhttp/cities",
                params: {
                    param: location,
                    query: $selectCity.val()
                },
                dataType: 'json',
                showNoSuggestionNotice: true,
                noSuggestionNotice: "No result found",
                onSelect: function (suggestion) {
                    $("#city-id").val(suggestion.data)
                },
                transformResult: function (response) {
                    // console.log(response);
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