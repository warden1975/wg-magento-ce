<?php

namespace interfaces;

interface mapper {
    function getHeaders();
    function getExtractedValue(array $data);
    function tryValueByKey(array $data, $key);
}