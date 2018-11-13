<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <title>KodeSain | Free Code Tutorials</title>

        <!-- stylesheet -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://kendo.cdn.telerik.com/2018.3.1017/styles/kendo.common-material.min.css">
        <link rel="stylesheet" href="https://kendo.cdn.telerik.com/2018.3.1017/styles/kendo.rtl.min.css">
        <link rel="stylesheet" href="https://kendo.cdn.telerik.com/2018.3.1017/styles/kendo.material.min.css">
        <link rel="stylesheet" href="https://kendo.cdn.telerik.com/2018.3.1017/styles/kendo.material.mobile.min.css">
        <link rel="stylesheet" href="assets/style.css">

        <!-- javascript -->
        <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment-with-locales.min.js"></script>
        <script src="https://kendo.cdn.telerik.com/2018.3.1017/js/kendo.all.min.js"></script>
        <script src="https://kendo.cdn.telerik.com/2018.3.1017/js/kendo.timezones.min.js"></script>
        <script src="assets/script.js"></script>
    </head>
    <body>
        <div class="card">
            <div class="card-header">EVENT PLANNER</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12 text-right mb-3">
                        <button type="button" class="k-primary" onclick="window.location.href = 'formEntry.php';">New</button>
                    </div>
                    <div class="col-12">
                        <div id="dataGrid"></div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            $(document).ready(function () {
                $("#dataGrid").kendoGrid({
                    dataSource: {
                        transport: {
                            read: {
                                type: "POST",
                                url: "services.php",
                                data: {},
                                dataType: "json",
                                contentType: "application/x-www-form-urlencoded"
                            }
                        },
                        schema: {
                            data: function (response) {
                                return (response === null) ? [] : response.data;
                            }
                        },
                        pageSize: 20
                    },
                    height: 550,
                    groupable: false,
                    sortable: true,
                    filterable: true,
                    pageable: {
                        refresh: true,
                        pageSizes: true,
                        buttonCount: 5
                    },
                    columns: [
                        {
                            field: "event_code",
                            title: "Code",
                            width: 150
                        }, {
                            field: "event_name",
                            title: "Name"
                        }, {
                            field: "event_status",
                            title: "Status",
                            width: 150
                        }, {
                            command: [{
                                    name: "update",
                                    text: "",
                                    iconClass: "k-icon k-i-edit",
                                    click: function (e) {
                                        e.preventDefault();
                                        var row = this.dataItem($(e.target).closest("tr"));

                                        window.location.href = "formEntry.php?id=" + row._id;
                                    }
                                }, {
                                    name: "delete",
                                    text: "",
                                    iconClass: "k-icon k-i-close",
                                    click: function (e) {
                                        e.preventDefault();
                                        var row = this.dataItem($(e.target).closest("tr"));

                                        kendo.confirm("Are you sure to delete?").then(function () {
                                            $.post("services.php", {"action": "delete", "id": row._id}, function (result) {
                                                $("#dataGrid").data("kendoGrid").dataSource.read();
                                                $("#dataGrid").data("kendoGrid").refresh();
                                            });
                                        });
                                    }
                                }],
                            title: "&nbsp;",
                            width: 150
                        }
                    ],
                    dataBound: function () {
                        $("#dataGrid .k-command-cell .k-button").attr("style", "min-width: inherit");
                    }
                });
            });
        </script>
    </body>
</html>