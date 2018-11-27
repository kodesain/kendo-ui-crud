<?php
include('json.php');

$template = new json('db/template.json');
$row = array();
$rows = array();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_id = (isset_var($_POST['template_id']) == '') ? microtime(true) : floatval(isset_var($_POST['template_id']));

    $data = array(
        '_id' => $_id,
        'template_id' => $_id,
        'template_type' => isset_var($_POST['template_type']),
        'template_name' => isset_var($_POST['template_name']),
        'template_html' => isset_var($_POST['template_html'])
    );

    if ($template->save($data)) {
        header('Location: template.php');
        exit;
    }
} else {
    if (isset_var($_GET['id']) != '') {
        $show = $template->show(array('where' => array('_id' => isset_var($_GET['id']))));
        $row = isset_var($show[0]);
    }

    $rows = $template->show(array('select' => array('template_id', 'template_type', 'template_name'), 'order' => array('template_type')));
}

function isset_var(&$var, $val = '') {
    if (gettype($var) === 'boolean') {
        return isset($var) ? $var : $val;
    } else if (gettype($var) === 'array') {
        return isset($var) ? $var : $val;
    } else {
        return isset($var) ? trim($var) : $val;
    }
}
?>
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
        <div class="container-fluid">
            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-header">TEMPLATE MANAGER</div>
                        <div class="card-body">
                            <form id="formEntry" method="post">
                                <input type="hidden" name="template_id" id="template_id" value="<?php echo isset_var($row['template_id']); ?>">

                                <div class="form-group row">
                                    <label class="col-md-2 col-form-label">Template Type</label>
                                    <div class="col-md-10">
                                        <select name="template_type" id="template_type" required>
                                            <option value="HEADER">HEADER</option>
                                            <option value="SLIDER">SLIDER</option>
                                            <option value="CONTENT">CONTENT</option>
                                            <option value="SIDEBAR">SIDEBAR</option>
                                            <option value="FOOTER">FOOTER</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-2 col-form-label">Name</label>
                                    <div class="col-md-10">
                                        <input type="text" class="k-textbox text-capitalize" name="template_name" id="template_name" placeholder="Name" value="<?php echo isset_var($row['template_name']); ?>" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-2 col-form-label">HTML Syntax</label>
                                    <div class="col-md-10">
                                        <textarea class="k-textbox" name="template_html" id="template_html" placeholder="HTML Syntax" required><?php echo isset_var($row['template_html']); ?></textarea>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-2 col-form-label">Background</label>
                                    <div class="col-md-10">
                                        <input type="file" class="" name="template_background" id="template_background">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-12 text-right">
                                        <button type="submit" id="btnSave" class="k-primary">Save</button>
                                        <button type="button" id="btnCancel">Cancel</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card">
                        <div id="dataGrid"></div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            $(document).ready(function () {
                $("#template_name, #template_html").attr("placeholder", "");
                $("#template_name, #template_html").attr("style", "width: 100%;");
                $("#template_html").attr("style", "width: 100%; height: 500px;");

                $("#template_name").focus();
                $("#template_type").kendoComboBox();
                $("#template_background").kendoUpload({
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

                var template_type = $("#template_type").data("kendoComboBox");
                var template_background = $("#template_background").data("kendoUpload");

                template_type.value("<?php echo isset_var($row['template_type']); ?>");
                template_background.clearAllFiles();

                $("#btnSave").kendoButton({
                    icon: "save"
                });

                $("#btnCancel").kendoButton({
                    icon: "cancel",
                    click: function (e) {
                        window.location.href = "template.php";
                    }
                });

                $("#dataGrid").kendoGrid({
                    dataSource: {
                        data: JSON.parse('<?php echo json_encode($rows); ?>'),
                        pageSize: 10
                    },
                    scrollable: true,
                    sortable: true,
                    filterable: true,
                    pageable: true,
                    columns: [
                        {field: "template_type", title: "Type"},
                        {field: "template_name", title: "Template"},
                        {
                            command: [{
                                    name: "show",
                                    text: "",
                                    iconClass: "k-icon k-i-search",
                                    click: function (e) {
                                        e.preventDefault();
                                        var row = this.dataItem($(e.target).closest("tr"));

                                        window.location.href = "template.php?id=" + row.template_id;
                                    }
                                }],
                            title: "&nbsp;",
                            width: 100
                        }
                    ]
                });
            });
        </script>
    </body>
</html>