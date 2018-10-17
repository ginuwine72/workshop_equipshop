<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$summary = $this->db
			->select('
				COUNT(id) AS sum_order,
				SUM(total_quantity) AS sum_quantity,
				SUM(total_price) AS sum_price,
				')
				// COUNT(IF(LENGTH(tracking_number),1, NULL)) AS sum_success
			->get('tb_order')
			->row_array();

		$orders = $this->db
			->select('
					*,
					COUNT(id) AS sum_order,
					SUM(total_quantity) AS sum_quantity,
					SUM(total_price) AS sum_price
				')
			->group_by('YEAR(created)-MONTH(created)-DATE(created)')
			->order_by('created','desc')
			->limit('30')
			->get('tb_order')
			->result_array();

		$this->data['summary'] = $summary;
		$this->data['orders'] = $orders;
		$this->data['body'] = $this->load->view('admin/dashboard/index',$this->data,TRUE);
		$this->load->view('_layouts/admin',$this->data);
	}

}
