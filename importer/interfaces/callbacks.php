<?php

namespace interfaces;

interface callbacks {
    public function getCategoryIds($data);
    public function getAttribute($data, $key);
    public function getAttributeFromHtml($data, $key);
}