$.ajaxSetup({
    dataType: "json",
    async: true,
    beforeSend: function (jqXHR, settings) {
        jqXHR.setRequestHeader("X-Apps-Token", "");
    }
});