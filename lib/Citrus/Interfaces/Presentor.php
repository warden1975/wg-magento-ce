<?php
/**
 */

interface Citrus_Interfaces_Presentor {
    public function build();
    public function addChild(Citrus_Interfaces_Builder $var);
    public function count();
}