<?php

include('class/SQJson.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $event = new SQJson('assets/event.json');

    if (isset_var($_POST['action']) === 'insert') {
        $_id = microtime(true);
        $_POST['_id'] = $_id;
        $_POST['event_id'] = $_id;
        $_POST['event_created'] = date('Y-m-d H:i:s');

        unset($_POST['id']);
        unset($_POST['action']);

        $result = $event->save($_POST);

        echo json_encode(array(
            'status' => ($result === false) ? 'failed' : 'success'
        ));
    } else if (isset_var($_POST['action']) === 'update') {
        $_id = floatval(isset_var($_POST['id']));
        $_POST['_id'] = $_id;
        $_POST['event_id'] = $_id;
        $_POST['event_modified'] = date('Y-m-d H:i:s');

        unset($_POST['id']);
        unset($_POST['action']);

        $result = $event->save($_POST);

        echo json_encode(array(
            'status' => ($result === false) ? 'failed' : 'success'
        ));
    } else if (isset_var($_POST['action']) === 'delete') {
        $result = $event->drop(array('_id' => isset_var($_POST['id'])));

        echo json_encode(array(
            'status' => ($result === false) ? 'failed' : 'success'
        ));
    } else if (isset_var($_POST['action']) === 'select') {
        $rows = $event->show(array('where' => array('_id' => isset_var($_POST['id']))));

        echo json_encode(array(
            'status' => 'success',
            'data' => isset_var($rows[0])
        ));
    } else {
        $rows = $event->show();

        echo json_encode(array(
            'status' => 'success',
            'data' => $rows
        ));
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