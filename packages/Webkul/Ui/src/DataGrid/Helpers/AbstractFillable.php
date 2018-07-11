<?php

namespace Webkul\Ui\DataGrid\Helpers;

abstract class AbstractFillable
{   
    const NO_RESULT = null;

    protected $fillable = [];

    abstract protected function setFillable();

    public function __construct($args)
    {
        $error = false;
        $this->setFillable();

        foreach ($args as $key => $value){
            $this->{$key} = $value;
            // switch($value){
            //     case (in_array(gettype($value), ["array", "object", "function"])):
            //         $error = $this->fieldValidate(
            //             $key,
            //             $value,
            //             [$this->fillable[$key]['allowed']]
            //         );
            //         break;
            //     default:
            //         $error = $this->fieldValidate(
            //             $key,
            //             $value
            //         );
            // }
            // if($error) throw new \Exception($error);
        }
        // foreach($this->fillable as $fill){
        //     if(isset($args[$fill])){
        //         $this->{$fill} = [$args[$fill]];
        //     }
        // }
    }

    private function fieldValidate($key, $value, $allowed = ['string', 'integer', 'float', "boolean"], $merge = false)
    {
        $error = false;
        if( in_array($key, $this->fillable) || 
            array_filter(
                array_keys($this->fillable) , function($value){
                    return gettype($value) === "string";
                }
            )
        ){
            if(in_array(gettype($value), $allowed)){
                // if($merge){
                //     if(!$this->{$key}) $this->{$key} = [];
                //     $this->{$key}[] = $value;
                // }else 
                // $this->{$key} = $value;
            }else{
                dump(gettype($value));
                dump($value);
                $error = 'Allowed params are not valid as per allowed condition! Key - ' . $key;
            }
        }else{
            dump(in_array($key, $this->fillable));
            dump($value);
            $error = 'Not Allowed field! Key - ' . $key;
        }

        return $error ?: false;
    }

    public function __set($key, $value){
        $error = false;
        switch('$value'){ //no need to match this
            case (in_array(gettype($value), ["array", "object", "function"])):
                $error = $this->fieldValidate(
                    $key,
                    $value,
                    [$this->fillable[$key]['allowed']]
                );
                break;
            default:
                $error = $this->fieldValidate(
                    $key,
                    $value
                );
                break;
        }

        if($error) throw new \Exception($error);
        $this->{$key} = $value;
    }

    public function __get($key){
        if(in_array($key, $this->fillable)){
            return property_exists($this, $key) ? $this->{$key} : false;
        }else
            return self::NO_RESULT;
    }
}
