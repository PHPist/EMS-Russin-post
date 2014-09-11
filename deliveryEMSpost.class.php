<?
/*
deliveryEMSpost Object
(
   	[from] => Array
        (
            [value] => city--penza
            [name] => ПЕНЗА
            [type] => cities
        )

    [to] => Array
        (
            [value] => city--vladikavkaz
            [name] => ВЛАДИКАВКАЗ
            [type] => cities
        )
    [weight] => 1.5
    [sity_list] => Array
		(
			[0] => Array
				(
					[value] => city--abakan
					[name] => АБАКАН
					[type] => cities
				)
	)
    [region_list] => Array
        (
            [0] => Array
                (
                    [value] => region--respublika-adygeja
                    [name] => АДЫГЕЯ РЕСПУБЛИКА
                    [type] => regions
                )
	) 
    [error] => 
    [calc] => Array
        (
            [stat] => ok
            [price] => 890
            [term] => Array
                (
                    [min] => 3
                    [max] => 5
                )

        )
)
*/

class deliveryEMSpost {
	
	public $from;
	public $to;
	public $weight;
	public $sity_list;
	public $region_list;
	
	
	public $error;
	public $calc;
	
	function __construct($from, $to, $weight){
		$this->from['value'] = $from;
		$this->to['value'] = $to;
		$this->weight = $weight;
		$this->get_ems_calc();
		$this->get_ems_city();
		$this->get_ems_regions();
		$this->get_data_del_sity();
		}
	
	
	
	protected function get_data_del_sity(){
		foreach ($this->sity_list as $key=>$val){
			if (in_array($this->from['value'], $val)){$this->from =  $val;}
			if (in_array($this->to['value'], $val)){$this->to =  $val;}
		}
		foreach ($this->region_list as $key=>$val){
			if (in_array($this->from['value'], $val)){$this->from =  $val;}
			if (in_array($this->to['value'], $val)){$this->to =  $val;}
		}
		
		
		}
	

	protected function get_ems_calc(){
		$emsInfo = $this->curl_query(array(
											'method' => 'ems.calculate',
											'from' => $this->from['value'],
											'to' => $this->to['value'],
											'weight' => $this->weight,
											));
		$emsInfo = $this->objectToArray(json_decode($emsInfo));
			
			if ($emsInfo['rsp']['stat'] == 'ok'){
				$this->calc = $emsInfo['rsp'];
				}
			if ($emsInfo['rsp']['stat'] == 'fail'){
				
				$this->error['ems_api'][2] = $emsInfo['rsp']['err']['msg'];
				}
		}
	

	protected function get_ems_city(){
		
			$city = $this->curl_query(array(
										'method'=>'ems.get.locations',
										'type'=>'cities',
										'plain'=>'true'
										));
										
			$city = $this->objectToArray(json_decode($city));
			if ($city['rsp']['stat'] == 'ok'){
				$this->sity_list =  $city['rsp']['locations'];
				}
			if ($city['rsp']['stat'] == 'fail'){
				$this->error['ems_api'][0] = $city['rsp']['err']['msg'];
				}
	
			}
	
	protected function get_ems_regions(){
			$regions = $this->curl_query(array(
										'method'=>'ems.get.locations',
										'type'=>'regions',
										'plain'=>'true'
										));
			$regions = $this->objectToArray(json_decode($regions));
			
			if ($regions['rsp']['stat'] == 'ok'){
				$this->region_list = $regions['rsp']['locations'];
				}
			if ($regions['rsp']['stat'] == 'fail'){
				$this->error['ems_api'][1] = $regions['rsp']['err']['msg'];
				}
			}
	
	private function curl_query($param){
				$link = 'http://emspost.ru/api/rest/';
				$link_get = $link.'?'.http_build_query($param);
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $link_get);
				curl_setopt($ch, CURLOPT_HEADER, false);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_TIMEOUT, 10);
				curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
				return  $string = curl_exec($ch);
			}
			
	private function objectToArray($object) {
				if( !is_object($object) && !is_array($object)) {
					return $object;
				}
				if( is_object($object )) {
					$object = get_object_vars($object);
				}
				return array_map(__CLASS__.'::objectToArray', $object);
			}		
		
	 	
	}


?>