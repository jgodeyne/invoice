<?php
include_once '../ppa/entity_class.php';
include_once '../common/date_functions.php';

class Job extends Entity implements EntityInterface {
	private $request_date;
	private $expected_delivery_datetime;
	private $client_reference;
	private $description;
	private $number_of_units;
	private $unit;
	private $unit_price;
	private $discount_percentage;
	private $fixed_price;
	private $total_price;
	private $delivery_date;
	private $client_id;
	private $invoice_id;
	private $status;
	private $executor_id;
	private $delivery_date_exec;
	private $price_exec;
	
	public function getProperties() {
		return get_object_vars($this);
	}
	
	public function setFromPost($post) {
		$this->request_date = convertEuroToUSDate(htmlspecialchars($post['request_date']));
		$this->expected_delivery_datetime = convertEuroToUSDateTime(htmlspecialchars($post['expected_delivery_datetime']));
		$this->client_reference = htmlspecialchars($post['client_reference']);
		$this->description = htmlspecialchars($post['description']);
		$this->number_of_units = str_replace(",",".",htmlspecialchars($post['number_of_units']));
		$this->unit = htmlspecialchars($post['unit']);
		$this->unit_price = str_replace(",", ".", htmlspecialchars($post['unit_price']));
		$this->discount_percentage = str_replace(",", ".", htmlspecialchars($post['discount_percentage']));
		$this->fixed_price = str_replace(",", ".", htmlspecialchars($post['fixed_price']));
		$this->client_id = htmlspecialchars($post['client_id']);
		$this->executor_id = htmlspecialchars($post['executor_id']);
		$this->delivery_date_exec = convertEuroToUSDate(htmlspecialchars($post['delivery_date_exec']));
		$this->price_exec = str_replace(",", ".", htmlspecialchars($post['price_exec']));
	}
	
	public function close() {
		$this->delivery_date = date("Y-m-d");
		$this->status = "CLOSED";
		$this->save();
	}
	
	public function invoiced() {
		$this->status = "INVOICED";
		$this->save();
	}

	public function save() {
		if(!$this->getId()) {
			$this->status = 'OPEN';
		}
		parent::save();
	}
		
	/**
	 * Getter and Setters
	 */

	public function getRequestDate()
	{
	    return convertUSToEuroDate($this->request_date);
	}

	public function setRequestDate($request_date)
	{
	    $this->request_date = convertEuroToUSDate($request_date);
	}

	public function getExpectedDeliveryDatetime()
	{
	    return convertUSToEuroDateTime($this->expected_delivery_datetime);
	}

	public function setExpectedDeliveryDatetime($expected_delivery_datetime)
	{
	    $this->expected_delivery_datetime = convertEuroToUSDateTime($expected_delivery_datetime);
	}

	public function getClientReference()
	{
	    return $this->client_reference;
	}

	public function setClientReference($client_reference)
	{
	    $this->client_reference = $client_reference;
	}

	public function getDescription()
	{
	    return $this->description;
	}

	public function setDescription($description)
	{
	    $this->description = $description;
	}

	public function getNumberOfUnits()
	{
		return $this->number_of_units;
	}

	public function setNumberOfUnits($number_of_units)
	{
	    $this->number_of_units = $number_of_units;
	}

	public function getUnit()
	{
	    return $this->unit;
	}

	public function setUnit($unit)
	{
	    $this->unit = $unit;
	}

	public function getUnitPrice()
	{
	    return $this->unit_price;
	}

	public function setUnitPrice($unit_price)
	{
	    $this->unit_price = $unit_price;
	}

	public function getDiscountPercentage()
	{
	    return $this->discount_percentage;
	}

	public function setDiscountPercentage($discount_percentage)
	{
	    $this->discount_percentage = $discount_percentage;
	}

	public function getFixedPrice()
	{
	    return $this->fixed_price;
	}

	public function setFixedPrice($fixed_price)
	{
	    $this->fixed_price = $fixed_price;
	}

	public function getTotalPrice()
	{
	    return $this->total_price;
	}

	public function setTotalPrice($total_price)
	{
	    $this->total_price = $total_price;
	}

	public function getDeliveryDate()
	{
	    return convertUSToEuroDate($this->delivery_date);
	}

	public function getClientId()
	{
	    return $this->client_id;
	}

	public function setClientId($client_id)
	{
	    $this->client_id = $client_id;
	}

	public function getInvoiceId()
	{
	    return $this->invoice_id;
	}

	public function setInvoiceId($invoice_id)
	{
	    $this->invoice_id = $invoice_id;
	}

	public function getStatus()
	{
	    return $this->status;
	}
	
	public function setExecutorId($executor_id) {
		$this->executor_id = $executor_id;
	}
	
	public function getExecutorId() {
		return $this->executor_id;
	}

	public function getDeliveryDateExec()
	{
	    return convertUSToEuroDate($this->delivery_date_exec);
	}
	
	public function setDeliveryDateExec($delivery_date_exec)
	{
	    $this->delivery_date_exec = convertEuroToUSDate($delivery_date_exec);
	}
	
	public function getPriceExec() {
		return $this->price_exec;
	}
	
	public function setPriceExec($price_exec) {
		$this->price_exec = $price_exec;
	}
}