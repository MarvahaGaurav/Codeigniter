(function ($) {

    var fetchLocation = function (url, parent, source, target, events) {
        var parent = parent || "body";
        var source = source || ".country";
        var target = target || ".cities";
        var events = events || "change";

        $(parent).on(events, source, function () {
            var $self = $(this),
                selfValue = $self.val();
            $.ajax({
                url: domain + url,
                method: "GET",
                data: {
                    param: selfValue
                },
                dataType: "json",
                success: function (response) {
                    if ( response.success ) {
                        var data = response.data
                        data = data.map(function(row){
                            return {id: row.id, text: row.name};
                        });
                        $(target).html(optionsViewBuilder(data, "Select a city"));
                    }
                }, 
                error: function() {

                }
            });
        });
    }

    window.fetchLocation = fetchLocation;
})($);