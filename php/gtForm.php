<?php
class gtForm{
    private $ctrls;

    function __construct(){
        $this->ctrls = Array();
    }

    function addControl($opts){

        $name = (isset($opts['name'])?$opts['name']:'');
        $type = (isset($opts['type'])?$opts['type']:'text');
        $label= (isset($opts['label'])?$opts['label']:$name);
        $value= (isset($opts['value'])?$opts['value']:'');

        if( $name !== '' ){
            $this->ctrls[] = Array($name, $type, $label, $value);
        }
    }



    function build(){
        $wip = '<div id="myForm">';
        foreach($this->ctrls as $ctrl){

            $wip .= '<div class="my-3">';
            $wip .= '<label for="'.$ctrl[0].'" class="form-label">'.$ctrl[2].'</label>';
            $wip .= '<input id="'.$ctrl[0].'" type="'.$ctrl[1].'" class="form-control" value="'.$ctrl[3].'">';
            $wip .= '</div>';

        }

        $wip .= '</div>';
        return $wip;
    }

    function debug(){
        echo "<pre>";
        print_r($this->ctrls);
        echo "</pre>";
    }

}
