<?php
//INCLUDES-----------------------------------------
require_once($_SERVER['DOCUMENT_ROOT'].'/../server/Config/MainConfig.php');
require_once(SERVROOT.'Lib/Common.php');
require_once(SERVROOT.'Lib/db_functions.php');
//-------------------------------------------------

function GetOrders()
{
	connect_to_db();
	$orders = do_query("SELECT o.*, c.Name,
		CASE WHEN three.id IS NOT NULL THEN 'Error'
			WHEN one.id IS NOT NULL THEN 'In-Progress'
			WHEN zero.id IS NOT NULL THEN 'New'
			WHEN two.id IS NOT NULL THEN 'Shipped'
			WHEN five.id IS NOT NULL THEN 'Back-Order'
			ELSE 'None'
		END AS 'status' 
		FROM Orders o JOIN Customers c on c.Id = o.CustomerId
		LEFT OUTER JOIN Shipments zero on zero.OrderId = o.id and zero.status = 0
		LEFT OUTER JOIN Shipments one on one.OrderId = o.id and one.status = 1
		LEFT OUTER JOIN Shipments two on two.OrderId = o.id and two.status = 2
		LEFT OUTER JOIN Shipments three on three.OrderId = o.id and three.status = 3
		LEFT OUTER JOIN Shipments five on five.OrderId = o.id and five.status = 5
		ORDER BY o.date DESC","","");
	close_db();
	return $orders;
}

function GetOrder($orderId)
{
	connect_to_db();
	$order = do_query("SELECT o.*, c.Name as CustomerName, c.Address, c.City, c.State, c.Zip, c.Phone, c.Email, c.LastFour, c.StripeCustomerId
	FROM Orders o JOIN Customers c ON c.id = o.CustomerId WHERE o.Id = ?","i", [$orderId]);
	close_db();
	return $order;
}

function GetOrderByCode($orderCode)
{
	connect_to_db();
	$order = do_query("SELECT o.*, c.Name as CustomerName, c.Address, c.City, c.State, c.Zip, c.Phone, c.Email, c.LastFour, c.StripeCustomerId,
	oi.total as subtotal,
	oi.itemTax + s.shipTax as taxTotal,
	s.shipTotal
	FROM Orders o JOIN Customers c ON c.id = o.CustomerId 
	LEFT OUTER JOIN (SELECT i.OrderId, SUM(i.taxAmount) as itemTax, SUM(i.quantity * p.price) as total
		FROM OrderItems i JOIN Products p on p.id = i.productid GROUP BY i.orderId) as oi ON oi.OrderId = o.id
	LEFT OUTER JOIN (SELECT orderId, SUM(cost) as shipTotal, SUM(taxAmount) shipTax 
		FROM Shipments GROUP BY id) as s on s.OrderId = o.id
	WHERE o.code = ?","s", [$orderCode]);
	close_db();
	return $order;
}

function CreateOrder($order)
{
	connect_to_db();
	$params[] = $order["customerId"];
	$params[] = $order["stripeChargeId"];
	$order = false;
	
	$existing = do_query("SELECT * FROM Customers WHERE Id = ?","i", $params);
	if($existing)
	{
		$id = do_query("INSERT INTO Orders (CustomerId, StripeChargeId, Date) VALUES(?, ?, NOW())", "ss", $params);
		$code = strtoupper(hash('crc32', $id));
		do_query("UPDATE Orders SET Code = ? WHERE Id = ?", "si", array($code, $id));
		$order = do_query("SELECT * FROM Orders WHERE Id = ?","i", array($id));
	}
	
	close_db();
	return $order;
}

function UpdateOrder($order)
{
	connect_to_db();
	$existing = do_query("SELECT * FROM Orders WHERE Id = ?","i", array($order["id"]));
	
	if($existing)
	{
		$params[] = $order["refundAmount"];
		$params[] = $order["stripeChargeId"];
		$params[] = $order["id"];	
		
		do_query("UPDATE Orders SET RefundAmount = ?, StripeChargeId = ? WHERE Id = ?","ssi", $params);
	}
	close_db();
	
	return ($existing)? true : false;
}

function DeleteOrder($orderId)
{
	connect_to_db();
	//TODO: Cascade delete to orderItems?
	do_query("DELETE FROM Orders WHERE Id = ?","i", array($orderId));
	close_db();
	return true; //TODO: some sort of error handling?
}

function GetOrderSummary($startDate, $endDate, $groupBy)
{
	$baseItems = "YEAR(o.date) as year, SUM(p.price) as sales, SUM(oi.taxamount) as tax
	FROM orderitems oi 
	JOIN orders o on o.id = oi.orderId 
	JOIN products p on p.id = oi.productid 
	WHERE o.date BETWEEN ? AND ? ";
	
	$baseShip = "YEAR(o.date) as year, COALESCE(SUM(s.cost), 0) as ship, COALESCE(SUM(s.taxamount), 0) as shipTax
	FROM orders o 
	LEFT OUTER JOIN shipments s on s.orderid = o.id
	WHERE o.date BETWEEN ? AND ? ";
	
	$groupClause = "";
	$itemSelect = "";
	$shipSelect = "";
	$whereClause = "";
	switch($groupBy)
	{
		case "day": 
			$groupClause = "GROUP BY YEAR(o.date), MONTH(o.date), DAY(o.date) with rollup) ";
			$shipSelect = "(SELECT MONTH(o.date) as month, DAY(o.date) as day, ";
			$itemSelect = "(SELECT MONTH(o.date) as month, DAY(o.date) as day, o.date, ";
			$whereClause = "AND ships.month = items.month AND ships.day = items.day;";
			break;
		case "month": 
			$groupClause = "GROUP BY YEAR(o.date), MONTH(o.date) with rollup) ";
			$shipSelect = "(SELECT MONTH(o.date) as month, ";
			$itemSelect = "(SELECT CONCAT(MONTH(o.date),'-',YEAR(o.date)) as date, MONTH(o.date) as month, ";
			$whereClause = "AND ships.month = items.month;";
			break;
		case "week": 
			$groupClause = "GROUP BY YEAR(o.date), WEEK(o.date) with rollup)";
			$shipSelect = "(SELECT WEEK(o.date) as week, ";
			$itemSelect = "(SELECT  WEEK(o.date) as week, 
				DATE_SUB(DATE_ADD(MAKEDATE(YEAR(o.date), 1), INTERVAL WEEK(o.date) WEEK),
  				INTERVAL WEEKDAY(DATE_ADD(MAKEDATE(YEAR(o.date), 1), INTERVAL WEEK(o.date) WEEK)) -1 DAY) as date,";
  			$whereClause = "AND ships.week = items.week;";
			break;
	}
	
	$query = "SELECT items.date, items.sales, items.tax, ships.ship, ships.shipTax FROM 
		".$itemSelect.$baseItems.$groupClause." as items JOIN ".$shipSelect.$baseShip.$groupClause." as ships 
		ON ships.year = items.year ".$whereClause;
		
	connect_to_db();
	$params[] = $startDate;
	$params[] = $endDate;
	$params[] = $startDate;
	$params[] = $endDate;
	$summary = false;
	
	$summary = do_query($query,"ssss", $params);
	close_db();
	return $summary;
}

function ValidateOrder($data)
{
	if(is_array($data))
	{
		$order = "";
		$order['id'] = null;
		$order['stripeChargeId'] = null;
		$order['refundAmount'] = null;
		
		if(array_key_exists("id", $data))
			$order["id"] = ValidateIntParam($data["id"]);
		if(array_key_exists("customerId", $data))
			$order["customerId"] = ValidateIntParam($data["customerId"]);
		if(array_key_exists("stripeChargeId", $data))
			$order["stripeChargeId"] = SanitizeString($data["stripeChargeId"], 100);
		if(array_key_exists("refundAmount", $data))
			$order["refundAmount"] = ValidateFloatParam($data["refundAmount"], 2);
				
		if(array_key_exists("customerId", $order))
			return $order;
	}

	return false;
}

?>
