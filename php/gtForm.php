<?php

class gtForm{
    private $ctrls;
    private $hiddens;

    function __construct(){
        $this->ctrls = Array();
        $this->hiddens = Array();
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

    function addHidden($name, $value){
        $this->hiddens[$name] = $value;
    }

    function build(){
        $wip = '<div id="gtForm">';

        # Add Controls
        foreach($this->ctrls as $ctrl){
            if( $ctrl[0] == "section" ){
                $wip .= "<h4>".$ctrl[2]."</h4>";
            }else{
                $wip .= '<div class="my-3">';
                $wip .= '<label for="'.$ctrl[0].'" class="form-label">'.$ctrl[2].'</label>';
                $wip .= $this->createControl($ctrl[1], $ctrl[0], $ctrl[3]);
                $wip .= '</div>';
            }
        }
        # Add Hiddens
        foreach( $this->hiddens as $key => $value ){
            $wip .= '<input type="hidden" id="'.$key.'" name="'.$key.'" value="'.$value.'">';
        }

        $wip .= '</div>';
        return $wip;
    }

    function debug(){
        echo "<pre>";
        print_r($this->ctrls);
        echo "</pre>";
    }

    function createControl($type, $id, $value){
        $wip = '';

        switch($type){
            case "product-category":
                require_once('gtDb.php');
                require_once('gtMap.php');
                $db = new gtDb();
                $db->where(_SQL_CAT_ACCOUNT, $_SESSION['accountID']);
                $db->orderBy(_SQL_CAT_NAME, 'ASC');
                $options = $db->get(_SQL_CAT);

                    $opts = '';
                    foreach($options as $option){
                        $selected = '';
                        if( $option[_SQL_CAT_ID] == $value ){
                            $selected = ' selected';
                        }
                        $opts.='<option value="'.$option[_SQL_CAT_ID].'"'.$selected.'>'.$option[_SQL_CAT_NAME].'</option>';
                    }
                $wip = "<select class='form-select' id='$id' name='$id'>$opts</select>";
                break;
            case "list-measure":
                $wip = "<select class='form-select' id='$id' name='$id'>";
                global $_UM;
                foreach($_UM as $key => $value){
                    $wip.= "<option value='$key'".($value==$key?' selected':'').">$value</option>";
                }
                $wip.= "</select>";
                break;
            default:
                $wip = "<input type='text' id='$id' name='$id' class='form-control' value='$value'>";
        }
        return $wip;
    }

}
