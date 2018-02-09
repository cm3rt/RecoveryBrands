<?php

namespace RecoveryBrands;

class ObjToArray
{


    public static function convertMe($obj, &$arr){
        if(!is_object($obj) && !is_array($obj)){
            $arr = $obj;
            return $arr;
        }

        foreach ($obj as $key => $value)
        {
            if (!empty($value))
            {
                $arr[$key] = array();
                ObjToArray::convertMe($value, $arr[$key]);
            }
            else
            {
                $arr[$key] = $value;
            }
        }
        return $arr;
    }
}
