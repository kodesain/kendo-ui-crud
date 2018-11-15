$.ajaxSetup({
    dataType: "json",
    async: true,
    beforeSend: function (jqXHR, settings) {
        jqXHR.setRequestHeader("X-Apps-Token", "");
    }
});

function isJSON(str) {
    if (typeof str !== "string") {
        return false;
    }

    try {
        JSON.parse(str);
        return true;
    } catch (error) {
        return false;
    }
}