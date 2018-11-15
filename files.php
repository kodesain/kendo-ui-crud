<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset_var($_GET['action']) === 'save') {
        $imageFolder = 'upload/';

        if (!is_dir($imageFolder)) {
            mkdir($imageFolder, 0777, true);
        }

        reset($_FILES);
        $temp = current($_FILES);
        if (is_uploaded_file($temp['tmp_name'])) {
            header('Access-Control-Allow-Origin: *');

            // Sanitize input
            if (preg_match('/([^\w\s\d\-_~,;:\[\]\(\).])|([\.]{2,})/', $temp['name'])) {
                header('HTTP/1.1 400 Invalid file name.');
                return;
            }

            // Verify extension
            if (!in_array(strtolower(pathinfo($temp['name'], PATHINFO_EXTENSION)), array('gif', 'jpg', 'png'))) {
                header('HTTP/1.1 400 Invalid extension.');
                return;
            }

            // Accept upload if there was no origin, or if it is an accepted origin
            $filetowrite = $imageFolder . $temp['name'];
            move_uploaded_file($temp['tmp_name'], $filetowrite);

            // Respond to the successful upload with JSON.
            // Use a location key to specify the path to the saved image resource.
            // {location : '/your/uploaded/image/file'}
            echo json_encode(array('location' => $filetowrite));
        } else {
            // Notify editor that the upload failed
            header('HTTP/1.1 500 Server Error');
        }
    } else if (isset_var($_GET['action']) === 'remove') {
        /**/
    } else {
        /**/
    }
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