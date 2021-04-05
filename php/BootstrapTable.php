<?php

/**
 * Helper class for generating bootstrap styled HTML table
 * @access public
 * @author Fayaz Khan <pakxpertz@gmail.com>
 * @license http://www.gnu.org/licenses/gpl-3.0.en.html GPLv3
 * @copyright (c) 2015, MaxTech Developers
 */
class BootstrapTableHelper{
   
    /**
     * Table Columns holder
     * @var array Array conatining columns
     */
    private $columns = array();
   
    /**
     * Table rows holder
     * @var array Multi-dimensional Array containing table data
     */
    private $rows = array();
   
    /**
     * Table attributes holder (Classes etc)
     * @var array
     */
    private $attributes = array();
   
    /**
     * Makes table responsive or not
     * @var boolean True means responsive false means not
     */
    private $responsive = FALSE;
   
    /**
     * Construct table options
     * @param string $id ID of this table
     * @param array $attributes HTML Attributes for this table
     */
    public function __construct($id = "", $attributes = array()) {
        if(!empty($id)){
            $this->attributes["id"] = $id;
        }
       
        if(!empty($attributes)){
            foreach($attributes as $k => $v){
                $this->attributes[$k] = $v;
            }
        }
       
        $this->attributes["class"][] = "table";
    }
   
    /**
     * Add table-hover css class to this table
     * @return \BootstrapTableHelper
     */
    public function hover(){
        $this->attributes["class"][] = "table-hover";
        return $this;
    }
   
    /**
     * Add table-striped css class to this table
     * @return \BootstrapTableHelper
     */
    public function striped(){
        $this->attributes["class"][] = "table-striped";
        return $this;
    }
   
    /**
     * Add table-bordered css class to this table<br />
     * Make this table's rows and columns bordered
     * @return \BootstrapTableHelper
     */
    public function bordered(){
        $this->attributes["class"][] = "table-bordered";
        return $this;
    }
   
    /**
     * Make this table Responsive
     * @return \BootstrapTableHelper
     */
    public function responsive(){
        $this->responsive = TRUE;
        return $this;
    }
   
    /**
     * Add condensed css class to this table
     * @return \BootstrapTableHelper
     */
    public function condensed(){
        $this->attributes["class"][] = "table-condensed";
        return $this;
    }
   
    /**
     * Add row of data to this table
     * @param array $data Data in 1D array
     * @return \BootstrapTableHelper
     */
    public function addRow($data = array()){
        $this->rows[] = $data;
        return $this;
    }
   
    /**
     * Add Columns (Table header/footer) to this table
     * @param array $data Columns in 1D array
     * @return \BootstrapTableHelper
     */
    public function columns($data = array()){
        $this->columns = $data;
        return $this;
    }
   
    /**
     * Add one column to the table
     * @param string $column Coulmn name
     * @return \BootstrapTableHelper
     */
    public function addColumn($column){
        $this->columns[] = $column;
        return $this;
    }
   
    /**
     * Construct Table
     * @return string Returns HTML code of table
     */
    public function __toString() {
        return $this->make();
    }
   
    /**
     * Generate HTML for this table and return it
     * @return string Returns HTML Code of table
     */
    public function make(){
        $columns = "";
        foreach($this->columns as $col){
            $columns .= "<th scope='col'>$col</th>";
        }
        $table = "";
        $table .= ($this->responsive)?"<div class='table-responsive'>":"";
        $table .= "<table";
        foreach($this->attributes as $name => $value){
            if(is_array($value)){
                $table .= " $name='";
                foreach($value as $v){
                    $table .= " $v";
                }
                $table .= "'";
            }else{
                $table .= " $name='$value'";
            }
        }
        $table .= ">";
        $table .= "<thead>";
        $table .= $columns;
        $table .= "</thead>";
        $table .= "<tbody>";
        foreach($this->rows as $row){
            $table .= "<tr class='table-light'>";
            foreach($row as $column){
                $table .= "<td>$column</td>";
            }
            $table .= "</tr>";
        }
        $table .= "</tbody>";
     
        $table .= "</table>";
        $table .= ($this->responsive)?"</div>":"";
        return $table;
    }
}
?>
