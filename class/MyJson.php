<?php

class MyJson {

    public $file;

    public function __construct($file) {
        $this->file = $file;
    }

    public function show(array $param = array()) {
        $select = array();
        if ($this->isset_var($param['select'])) {
            $select = array_values($param['select']);
        }

        $where = array();
        if ($this->isset_var($param['where'])) {
            $where = $param['where'];
        }

        $order = NULL;
        if ($this->isset_var($param['order'])) {
            $order = $param['order'];
        }

        try {
            $last = array();

            if (file_exists($this->file)) {
                $json = file_get_contents($this->file);
                $last = json_decode($json, true);

                if (is_array($last)) {
                    if (!empty($select)) {
                        $l = array();
                        foreach ($last as $key => $value) {
                            $e = array();
                            foreach ($select as $k => $v) {
                                $e[$v] = $this->isset_var($value[$v]);
                            }

                            array_push($l, $e);
                        }

                        $last = $l;
                    }
                } else {
                    $last = array();
                }

                if (!empty($where)) {
                    $l = array();
                    foreach ($last as $key => $value) {
                        $n = 0;
                        foreach ($where as $k => $v) {
                            if ($this->isset_var($value[$k]) == $v) {
                                $n++;
                            }
                        }

                        if ($n == count($where)) {
                            array_push($l, $value);
                        }
                    }

                    $last = $l;
                }

                if ($order != NULL || $order != '') {
                    $this->array_sort($last, $order);
                }
            }

            return $last;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function drop(array $param = array()) {
        try {
            $last = array();

            if (file_exists($this->file)) {
                $json = file_get_contents($this->file);
                $last = json_decode($json, true);

                if (is_array($last)) {
                    if (!empty($param)) {
                        foreach ($last as $key => $value) {
                            $n = 0;
                            foreach ($param as $k => $v) {
                                if ($this->isset_var($value[$k]) == $v) {
                                    $n++;
                                }
                            }

                            if ($n == count($param)) {
                                unset($last[$key]);
                            }
                        }
                    }
                } else {
                    $last = array();
                }
            }

            $new = json_encode(array_values($last));
            if (file_put_contents($this->file, $new)) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function save(array $data = array()) {
        try {
            $last = array();
            $exist = false;

            if (file_exists($this->file)) {
                $json = file_get_contents($this->file);
                $last = json_decode($json, true);

                if (is_array($last)) {
                    foreach ($last as $key => $value) {
                        if ($this->isset_var($value['_id']) == $this->isset_var($data['_id'])) {
                            $last[$key] = $data;
                            $exist = true;
                            break;
                        }
                    }
                } else {
                    $last = array();
                }
            }

            if ($exist == false) {
                array_push($last, $data);
            }

            $new = json_encode(array_values($last));
            if (file_put_contents($this->file, $new)) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    private function array_sort(&$arr, $col, $dir = SORT_ASC) {
        $sort_col = array();
        foreach ($arr as $key => $row) {
            $sort_col[$key] = $row[$col];
        }

        array_multisort($sort_col, $dir, $arr);
    }

    private function isset_var(&$var, $val = '') {
        if (gettype($var) === 'boolean') {
            return isset($var) ? $var : $val;
        } else if (gettype($var) === 'array') {
            return isset($var) ? $var : $val;
        } else {
            return isset($var) ? trim($var) : $val;
        }
    }

}

?>
