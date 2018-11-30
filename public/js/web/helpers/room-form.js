(function($){
    $roomDimensionUnits = $(".room-dimension-units");
    $roomDimensionUnits.on("change", function() {
        var self = this,
            $self = $(this),
            value = $self.val();

        $roomDimensionUnits.val(value);
    });
})($);