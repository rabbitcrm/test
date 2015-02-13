<?php 

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class up extends BCZ_Controller {

	private $filters = '';

	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$data['content']="upload";
		

//  $inputFileType = 'Excel2007';

$data['content1']=$this->mapData(base_url().'assets/uploadsdocs//upload.csv');

print_r($data['content1']);
	
			$this->load->view('FirstLayoutView', $data);
		
	}

	function mapData($file) {
    // Open the Text File
  $fh_csv = fopen($file);
$fh_tmp = fopen($file);

while( false !== ($line = fgets($fh_csv)) )

    $enclosed = ''; // or whatever your field is enclosed with
    $delimiter = ','; // or whatever your delimiter is

    $columns  = explode($enclosed.$delimiter.$enclosed, trim($line, $enclosed));

    // the $objPost->field_X signifies the index for that field [0,1,2,3,+++]
    $data = array(
       'field_1' => trim(@$columns[@$objPost->field_1], $enclosed),
       'field_2' => trim(@$columns[@$objPost->field_2], $enclosed),
       'field_3' => trim(@$columns[@$objPost->field_3], $enclosed),
       'field_4' => trim(@$columns[@$objPost->field_4], $enclosed),
    );

    // write line to temp csv file, tab delimited with new line
    $str = implode("\t", $data) . "\n";
print_r($str);
    @fwrite($fh_tmp, $str); // write line to temp file

@fclose($fh_csv);
@fclose($fh_tmp);

// import from temp csv file into database
echo $sql    = sprintf("LOAD DATA LOCAL INFILE '%s'
            INTO TABLE `%s`
            FIELDS TERMINATED BY '\\t'
            LINES TERMINATED BY '\\n'
            (field_1, field_2, field_3, field_4)",
            "TEMP FILE",
            "DATABASE TABLE NAME");

$query  = $this->db->query( $sql );

// delete temp file

}
}
