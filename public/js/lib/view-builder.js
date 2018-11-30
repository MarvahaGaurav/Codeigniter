(function(){
    var optionsViewBuilder = function(data, defaultText) {
        
        var html = data.reduce(function(accumulator, currentValue){
            return accumulator + '<option value="' + currentValue.id + '">' + currentValue.text + "</option>";
        }, '<option value="">'+ defaultText +'</option>');

        return html;
    } 

    window.optionsViewBuilder = optionsViewBuilder;
})($);