<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

  public function __construct() {
    parent::__construct(); 
    $this->load->model('mdata');
    $this->load->helper('url');
		$this->load->helper('security');
		
		$this->load->library('form_validation');
		$this->load->library('session');
		$this->load->library('user_agent');
  }
	public function index()
	{
		$this->slice->view('index');
	}
  public function contactapi() {
    $data = array('image' => $this->input->post("image"));
    $content = json_encode($data);

  
    $url = "http://ec2-3-133-154-174.us-east-2.compute.amazonaws.com:5000/predict";    
    
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER,
            array("Content-type: application/json"));
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $content);

    $json_response = curl_exec($curl);

    $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

    if ( $status != 200 ) {
        die("Error: call to URL $url failed with status $status, response $json_response, curl_error " . curl_error($curl) . ", curl_errno " . curl_errno($curl));
    }


    curl_close($curl);

    $response = $json_response;
    print_r ($response); 
  }
  function insert() {
    $this->form_validation->set_rules('lat', 'GPS LATITUDE', 'trim|required|xss_clean');
    $this->form_validation->set_rules('lon', 'GPS LONGITUDE', 'trim|required|xss_clean');
    if ($this->form_validation->run() == FALSE) {
			echo validation_errors();
		}else {
      // latitude and longitude in DMS format
      $latDMS = $this->input->post('lat');
      $lonDMS = $this->input->post('lon');

      // convert DMS to decimal degrees
      list($latDegrees, $latMinutes, $latSeconds) = explode(",", $latDMS);
      list($lonDegrees, $lonMinutes, $lonSeconds) = explode(",", $lonDMS);

      $latDecimal = $latDegrees + ($latMinutes / 60) + ($latSeconds / 3600);
      $lonDecimal = $lonDegrees + ($lonMinutes / 60) + ($lonSeconds / 3600);

      
      $exifData = array (
        "lat"=>$latDecimal,
        "lon"=>$lonDecimal
      );

      
      
      if ($this->mdata->insert($exifData)) {
        //success
      }
    }
  }
  function markers() {
    $result = $this->mdata->fetchMarkers();
    if(!$this->mdata->fetchMarkers()) {
      echo "EMPTY";
    }else {
      $results = $this->mdata->fetchMarkers();
      $data = json_decode(json_encode($results), true);
      header('Content-Type: application/json; charset=utf-8');
      echo json_encode($data);
    }
  }
}
