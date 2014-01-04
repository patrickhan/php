<?php
class ModelTotalDiscount extends Model {
	public function getTotal(&$total_data, &$total, &$taxes) {
	$tot = $this->cart->getSubTotal();
	$dc = $this->db->query("select * from discount where amount <= '$tot' order by amount DESC");
		if ($dc->num_rows) {
		$tt = $dc->row;
		
		if ($tt['type'] == '1') {
		$num = ($tt['cost']/100) * $tot;
		}
		else
		{
		$num = $tt['cost'];
		}
		
			$this->load->language('total/low_order_fee');
		 	
			$this->load->model('localisation/currency');
			
			$total_data[] = array( 
        		'title'      => 'Discount',
        		'text'       => $this->currency->format($num),
        		'value'      => $num,
				'sort_order' => 10
			);
			
			$total -= $num;
		}
	}
}
?>