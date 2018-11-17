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
                <form id="formEntry">
                    <input type="hidden" name="event_id" id="event_id">
                    <input type="hidden" name="event_user" id="event_user">
                    <input type="hidden" name="event_created" id="event_created">
                    <input type="hidden" name="event_modified" id="event_modified">

                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Code</label>
                        <div class="col-md-5">
                            <input type="text" class="k-textbox text-uppercase" name="event_code" id="event_code" placeholder="Code" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Event Name</label>
                        <div class="col-md-10">
                            <input type="text" class="k-textbox text-capitalize" name="event_name" id="event_name" placeholder="Event Name" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Start</label>
                        <div class="col-md-10">
                            <input type="text" class="" name="event_start" id="event_start" placeholder="Start" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Finish</label>
                        <div class="col-md-10">
                            <input type="text" class="" name="event_finish" id="event_finish" placeholder="Finish" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Information</label>
                        <div class="col-md-10">
                            <textarea class="k-textbox text-capitalize" name="event_info" id="event_info" placeholder="Information" required></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Status</label>
                        <div class="col-md-10">
                            <select name="event_status" id="event_status" required>
                                <option value="AKTIF">AKTIF</option>
                                <option value="NON AKTIF">NON AKTIF</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Upload Files</label>
                        <div class="col-md-10">
                            <input type="file" class="" name="event_files" id="event_files">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-12 text-right">
                            <button type="button" id="btnSave" class="k-primary">Save</button>
                            <button type="button" id="btnCancel">Cancel</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <script>
            $(document).ready(function () {
                $("#event_code, #event_name, #event_info").attr("placeholder", "");
                $("#event_code, #event_name, #event_info").attr("style", "width: 100%;");
                $("#event_info").attr("style", "width: 100%; height: 500px;");

                $("#event_code").focus();

                $("#event_start").kendoDateTimePicker({
                    format: "dd/MM/yyyy HH:mm",
                    dateInput: true
                });

                $("#event_finish").kendoDateTimePicker({
                    format: "dd/MM/yyyy HH:mm",
                    dateInput: true
                });

                $("#event_info").kendoEditor({
                    resizable: {
                        content: true,
                        toolbar: true
                    },
                    tools: [
                        "insertImage",
                        "insertFile"
                    ],
                    imageBrowser: {
                        messages: {
                            dropFilesHere: "Drop files here"
                        },
                        transport: {
                            read: "editor.php?image=read",
                            destroy: {
                                url: "editor.php?image=destroy",
                                type: "POST"
                            },
                            create: {
                                url: "editor.php?image=create",
                                type: "POST"
                            },
                            thumbnailUrl: "editor.php?image=thumbnail",
                            uploadUrl: "editor.php?image=upload",
                            imageUrl: "editor.php?image=show&path={0}"
                        }
                    },
                    fileBrowser: {
                        messages: {
                            dropFilesHere: "Drop files here"
                        },
                        transport: {
                            read: "editor.php?file=read",
                            destroy: {
                                url: "editor.php?file=destroy",
                                type: "POST"
                            },
                            create: {
                                url: "editor.php?file=create",
                                type: "POST"
                            },
                            uploadUrl: "editor.php?file=upload",
                            fileUrl: "editor.php?file=show&path={0}"
                        }
                    }
                });

                $("#event_status").kendoComboBox();

                $("#event_files").kendoUpload({
                    async: {
                        saveUrl: "files.php?action=save",
                        removeUrl: "files.php?action=remove",
                        autoUpload: true
                    },
                    multiple: true,
                    validation: {
                        allowedExtensions: [".gif", ".jpg", ".png"],
                        maxFileSize: 1000000
                    },
                    success: function (e) {
                        e.files[0].name = e.response.location;
                    }
                });

                var event_start = $("#event_start").data("kendoDateTimePicker");
                var event_finish = $("#event_finish").data("kendoDateTimePicker");
                var event_info = $("#event_info").data("kendoEditor");
                var event_status = $("#event_status").data("kendoComboBox");
                var event_files = $("#event_files").data("kendoUpload");

                event_start.value(new Date());
                event_finish.value(new Date());
                event_info.value("");
                event_status.value("");
                event_files.clearAllFiles();

                $("#event_id").val("");
                $("#event_user").val("");
                $("#event_created").val("");
                $("#event_modified").val("");

                var id = "<?php echo isset($_GET['id']) ? trim($_GET['id']) : ''; ?>";

                if (id !== "") {
                    $.post("services.php", {"action": "select", "id": id}, function (result) {
                        var data = (typeof result.data !== "undefined" && result.data !== null && result.data !== "") ? result.data : [];

                        $(".k-textbox").each(function () {
                            $(this).val(data[this.name]);
                        });

                        event_start.value(moment(data["event_start"]).toDate());
                        event_finish.value(moment(data["event_finish"]).toDate());
                        event_info.value(data["event_info"]);
                        event_status.value(data["event_status"]);
                        event_files.clearAllFiles();

                        $("#event_id").val(data["event_id"]);
                        $("#event_user").val(data["event_user"]);
                        $("#event_created").val(data["event_created"]);
                        $("#event_modified").val(data["event_modified"]);

                        if (isJSON(data["event_files"])) {
                            $("#event_files").data("kendoUpload").destroy();
                            $("#event_files").detach().insertBefore(".k-upload");
                            $("#event_files").next().remove();

                            $("#event_files").kendoUpload({
                                async: {
                                    saveUrl: "files.php?action=save",
                                    removeUrl: "files.php?action=remove",
                                    autoUpload: true
                                },
                                multiple: true,
                                validation: {
                                    allowedExtensions: [".gif", ".jpg", ".png"],
                                    maxFileSize: 1000000
                                },
                                success: function (e) {
                                    e.files[0].name = e.response.location;
                                },
                                files: JSON.parse(data["event_files"])
                            });

                            event_files = $("#event_files").data("kendoUpload");
                        }
                    });
                }

                $("#btnSave").kendoButton({
                    icon: "save",
                    click: function (e) {
                        if ($("#formEntry").kendoValidator().data("kendoValidator").validate()) {
                            kendo.confirm("Are you sure to save?").then(function () {
                                var data = {};

                                $(".k-textbox").each(function () {
                                    data[this.name] = $(this).val();
                                });

                                data["event_start"] = moment(event_start.value()).format("YYYY-MM-DD HH:mm:ss");
                                data["event_finish"] = moment(event_finish.value()).format("YYYY-MM-DD HH:mm:ss");
                                data["event_info"] = event_info.value();
                                data["event_status"] = event_status.value();
                                data["event_files"] = JSON.stringify(event_files.getFiles());

                                data["event_id"] = $("#event_id").val();
                                data["event_user"] = $("#event_user").val();
                                data["event_created"] = $("#event_created").val();
                                data["event_modified"] = $("#event_modified").val();

                                data["id"] = data["event_id"];
                                data["action"] = (data["event_id"] === '') ? "insert" : "update";

                                $.post("services.php", data, function (result) {
                                    $("#btnCancel").click();
                                });

                                $("#btnSave").kendoButton({
                                    enable: false
                                });

                                $("body").css("cursor", "progress");
                            });
                        }
                    }
                });

                $("#btnCancel").kendoButton({
                    icon: "cancel",
                    click: function (e) {
                        window.location.href = "index.php";
                    }
                });
            });
        </script>
    </body>
</html>