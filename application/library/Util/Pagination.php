<?php

class Util_Pagination implements ArrayAccess {
    private $pageSize = 0;
    private $pageNum = 0;
    private $totalPageNum = 0 ;
    private $totalItemNum = 0;

    public function offsetSet($offset, $value) {
        $this->{$offset} = $value;
    }

    public function offsetExists($offset) {
        return isset($this->{$offset});
    }

    public function offsetUnset($offset) {
        return ;
    }

    public function offsetGet($offset) {
        return isset($this->{$offset}) ? $this->{$offset} : NULL;
    }
} 