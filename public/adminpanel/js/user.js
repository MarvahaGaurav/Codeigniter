// checkbox
$(document).ready(function() {
    $(".check").change(function() {
        if (this.checked) {
            $(".select-comm").each(function() {
                this.checked = true;
            })
        } else {
            $(".select-comm").each(function() {
                this.checked = false;
            })
        }
    });

    $(".select-comm").click(function() {
        if ($(this).is(":checked")) {
            var isAllChecked = 0;
            $(".select-comm").each(function() {
                if (!this.checked)
                    isAllChecked = 1;
            })
            if (isAllChecked == 0) { $(".check").prop("checked", true); }
        } else {
            $(".check").prop("checked", false);
        }
    });
});
//close checkbox