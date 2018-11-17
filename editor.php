<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // image Browser
    if (isset_var($_GET['image']) === 'read') {
        $path = isset_var($_POST['path']);

        echo json_encode(list_files('upload/images/' . $path));
    } else if (isset_var($_GET['image']) === 'create') {
        $name = isset_var($_POST['name']);
        $type = isset_var($_POST['type']);
        $path = isset_var($_POST['path']);
        $filename = $path . $name;

        if ($type === 'd') {
            if (!is_dir('upload/images/' . $filename)) {
                mkdir('upload/images/' . $filename, 0777, true);
            }

            if (!is_dir('upload/thumbs/' . $filename)) {
                mkdir('upload/thumbs/' . $filename, 0777, true);
            }

            echo json_encode(array(
                'name' => $name,
                'size' => 0,
                'type' => $type
            ));
        }
    } else if (isset_var($_GET['image']) === 'destroy') {
        $name = isset_var($_POST['name']);
        $type = isset_var($_POST['type']);
        $path = isset_var($_POST['path']);
        $filename = $path . $name;

        if ($type === 'd') {
            remove_directory('upload/images/' . $filename);
            remove_directory('upload/thumbs/' . $filename);
        } else {
            unlink('upload/images/' . $filename);
            unlink('upload/thumbs/' . $filename);
        }

        echo json_encode(array());
    } else if (isset_var($_GET['image']) === 'upload') {
        $path = isset_var($_POST['path']);
        $upload = upload_file('upload/images/' . $path, array('gif', 'jpg', 'png'));

        if ($upload['status']) {
            $file = basename($upload['file']);
            $filepath = 'upload/images/' . $path . $file;
            $thumbpath = 'upload/thumbs/' . $path . $file;

            $status = create_thumbnail($filepath, $thumbpath, 75, 75);
            if ($status) {
                echo json_encode(array(
                    'name' => basename($upload['file']),
                    'size' => filesize($upload['file']),
                    'type' => 'f'
                ));
            } else {
                echo json_encode(array());
            }
        } else {
            echo json_encode(array());
        }
    }

    // file Browser
    if (isset_var($_GET['file']) === 'read') {
        $path = isset_var($_POST['path']);

        echo json_encode(list_files('upload/files/' . $path));
    } else if (isset_var($_GET['file']) === 'create') {
        $name = isset_var($_POST['name']);
        $type = isset_var($_POST['type']);
        $path = isset_var($_POST['path']);
        $filename = $path . $name;

        if ($type === 'd') {
            if (!is_dir('upload/files/' . $filename)) {
                mkdir('upload/files/' . $filename, 0777, true);
            }

            echo json_encode(array(
                'name' => $name,
                'size' => 0,
                'type' => $type
            ));
        }
    } else if (isset_var($_GET['file']) === 'destroy') {
        $name = isset_var($_POST['name']);
        $type = isset_var($_POST['type']);
        $path = isset_var($_POST['path']);
        $filename = $path . $name;

        if ($type === 'd') {
            remove_directory('upload/files/' . $filename);
        } else {
            unlink('upload/files/' . $filename);
        }

        echo json_encode(array());
    } else if (isset_var($_GET['file']) === 'upload') {
        $path = isset_var($_POST['path']);
        $upload = upload_file('upload/files/' . $path, array());

        if ($upload['status']) {
            echo json_encode(array(
                'name' => basename($upload['file']),
                'size' => filesize($upload['file']),
                'type' => 'f'
            ));
        } else {
            echo json_encode(array());
        }
    }
} else {
    if (isset_var($_GET['image']) === 'show') {
        $filepath = 'upload/images/' . isset_var($_GET['path']);
        header('Content-Type: ' . get_mimetype($filepath));

        echo file_get_contents($filepath);
    } else if (isset_var($_GET['image']) === 'thumbnail') {
        $filepath = 'upload/thumbs/' . isset_var($_GET['path']);
        header('Content-Type: ' . get_mimetype($filepath));

        echo file_get_contents($filepath);
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

function list_files($dir = 'upload/') {
    $result = array();

    foreach (glob($dir . '*', GLOB_ONLYDIR) as $filename) {
        $file = array();
        $file['name'] = basename($filename);
        $file['type'] = 'd';
        $file['size'] = 0;

        $result[] = $file;
    }

    foreach (glob($dir . '*.*') as $filename) {
        $file = array();
        $file['name'] = basename($filename);
        $file['type'] = 'f';
        $file['size'] = filesize($filename);

        $result[] = $file;
    }

    return $result;
}

function remove_directory($src) {
    $dir = opendir($src);

    while (false !== ( $file = readdir($dir))) {
        if (( $file != '.' ) && ( $file != '..' )) {
            $full = $src . '/' . $file;
            if (is_dir($full)) {
                remove_directory($full);
            } else {
                unlink($full);
            }
        }
    }

    closedir($dir);
    rmdir($src);
}

function get_mimetype($filepath) {
    if (!preg_match('/\.[^\/\\\\]+$/', $filepath)) {
        return finfo_file(finfo_open(FILEINFO_MIME_TYPE), $filepath);
    }

    switch (strtolower(preg_replace('/^.*\./', '', $filepath))) {
        // START MS Office 2007 Docs
        case 'docx':
            return 'application/vnd.openxmlformats-officedocument.wordprocessingml.document';
        case 'docm':
            return 'application/vnd.ms-word.document.macroEnabled.12';
        case 'dotx':
            return 'application/vnd.openxmlformats-officedocument.wordprocessingml.template';
        case 'dotm':
            return 'application/vnd.ms-word.template.macroEnabled.12';
        case 'xlsx':
            return 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
        case 'xlsm':
            return 'application/vnd.ms-excel.sheet.macroEnabled.12';
        case 'xltx':
            return 'application/vnd.openxmlformats-officedocument.spreadsheetml.template';
        case 'xltm':
            return 'application/vnd.ms-excel.template.macroEnabled.12';
        case 'xlsb':
            return 'application/vnd.ms-excel.sheet.binary.macroEnabled.12';
        case 'xlam':
            return 'application/vnd.ms-excel.addin.macroEnabled.12';
        case 'pptx':
            return 'application/vnd.openxmlformats-officedocument.presentationml.presentation';
        case 'pptm':
            return 'application/vnd.ms-powerpoint.presentation.macroEnabled.12';
        case 'ppsx':
            return 'application/vnd.openxmlformats-officedocument.presentationml.slideshow';
        case 'ppsm':
            return 'application/vnd.ms-powerpoint.slideshow.macroEnabled.12';
        case 'potx':
            return 'application/vnd.openxmlformats-officedocument.presentationml.template';
        case 'potm':
            return 'application/vnd.ms-powerpoint.template.macroEnabled.12';
        case 'ppam':
            return 'application/vnd.ms-powerpoint.addin.macroEnabled.12';
        case 'sldx':
            return 'application/vnd.openxmlformats-officedocument.presentationml.slide';
        case 'sldm':
            return 'application/vnd.ms-powerpoint.slide.macroEnabled.12';
        case 'one':
            return 'application/msonenote';
        case 'onetoc2':
            return 'application/msonenote';
        case 'onetmp':
            return 'application/msonenote';
        case 'onepkg':
            return 'application/msonenote';
        case 'thmx':
            return 'application/vnd.ms-officetheme';
        //END MS Office 2007 Docs
    }

    return finfo_file(finfo_open(FILEINFO_MIME_TYPE), $filepath);
}

function upload_file($dir = 'upload/', array $extension = array()) {
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }

    reset($_FILES);
    $temp = current($_FILES);
    if (is_uploaded_file($temp['tmp_name'])) {
        header('Access-Control-Allow-Origin: *');

        // Sanitize input
        if (preg_match('/([^\w\s\d\-_~,;:\[\]\(\).])|([\.]{2,})/', $temp['name'])) {
            return array(
                'status' => false,
                'message' => 'Invalid file name'
            );
        }

        // Verify extension
        if (!empty($extension)) {
            if (!in_array(strtolower(pathinfo($temp['name'], PATHINFO_EXTENSION)), $extension)) {
                return array(
                    'status' => false,
                    'message' => 'Invalid extension'
                );
            }
        }

        // Accept upload if there was no origin, or if it is an accepted origin
        $filetowrite = $dir . $temp['name'];
        move_uploaded_file($temp['tmp_name'], $filetowrite);

        // Respond to the successful upload with JSON.
        // Use a location key to specify the path to the saved image resource.
        // {location : '/your/uploaded/image/file'}
        return array(
            'status' => true,
            'file' => $filetowrite
        );
    } else {
        // Notify editor that the upload failed
        return array(
            'status' => false,
            'message' => 'Server Error'
        );
    }
}

function create_thumbnail($filepath, $thumbpath, $thumbnail_width, $thumbnail_height, $background = false) {
    list($original_width, $original_height, $original_type) = getimagesize($filepath);

    if ($original_width > $original_height) {
        $new_width = $thumbnail_width;
        $new_height = intval($original_height * $new_width / $original_width);
    } else {
        $new_height = $thumbnail_height;
        $new_width = intval($original_width * $new_height / $original_height);
    }

    $dest_x = intval(($thumbnail_width - $new_width) / 2);
    $dest_y = intval(($thumbnail_height - $new_height) / 2);

    if ($original_type === 1) {
        $imgt = "ImageGIF";
        $imgcreatefrom = "ImageCreateFromGIF";
    } else if ($original_type === 2) {
        $imgt = "ImageJPEG";
        $imgcreatefrom = "ImageCreateFromJPEG";
    } else if ($original_type === 3) {
        $imgt = "ImagePNG";
        $imgcreatefrom = "ImageCreateFromPNG";
    } else {
        return false;
    }

    $old_image = $imgcreatefrom($filepath);
    $new_image = imagecreatetruecolor($thumbnail_width, $thumbnail_height); // creates new image, but with a black background
    // figuring out the color for the background
    if (is_array($background) && count($background) === 3) {
        list($red, $green, $blue) = $background;
        $color = imagecolorallocate($new_image, $red, $green, $blue);
        imagefill($new_image, 0, 0, $color);
        // apply transparent background only if is a png image
    } else if ($background === 'transparent' && $original_type === 3) {
        imagesavealpha($new_image, TRUE);
        $color = imagecolorallocatealpha($new_image, 0, 0, 0, 127);
        imagefill($new_image, 0, 0, $color);
    }

    imagecopyresampled($new_image, $old_image, $dest_x, $dest_y, 0, 0, $new_width, $new_height, $original_width, $original_height);
    $imgt($new_image, $thumbpath);

    return file_exists($thumbpath);
}

?>