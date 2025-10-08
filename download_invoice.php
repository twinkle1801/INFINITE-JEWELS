<?php
session_start();
require('fpdf.php');
include "db.php";

// Check order_id
if (!isset($_GET['order_id'])) {
    die("Order ID not found.");
}
$order_id = intval($_GET['order_id']);

// Fetch order info with user
$sql_order = "SELECT o.*, u.username, u.email 
              FROM orders o 
              JOIN users u ON o.user_id = u.id 
              WHERE o.order_id = ?";
$stmt_order = $conn->prepare($sql_order);
$stmt_order->bind_param("i", $order_id);
$stmt_order->execute();
$order = $stmt_order->get_result()->fetch_assoc();

if (!$order) {
    die("Order not found.");
}

// Fetch order items
$sql_items = "SELECT * FROM order_items WHERE order_id = ?";
$stmt_items = $conn->prepare($sql_items);
$stmt_items->bind_param("i", $order_id);
$stmt_items->execute();
$res_items = $stmt_items->get_result();
$order_items = [];
while ($row = $res_items->fetch_assoc()) {
    $order_items[] = $row;
}

// Fetch bill/payment info
$sql_bill = "SELECT * FROM bill_payment WHERE order_id = ?";
$stmt_bill = $conn->prepare($sql_bill);
$stmt_bill->bind_param("i", $order_id);
$stmt_bill->execute();
$bill = $stmt_bill->get_result()->fetch_assoc();

// Custom PDF Class
class PDF extends FPDF {
    function Header() {
        // Get page width
$pageWidth = $this->GetPageWidth();

// Set logo width
$logoWidth = 90; // aap adjust kar sakte ho

// Calculate X position to center the logo
$logoX = ($pageWidth - $logoWidth) / 2;

// Logo - centered
$this->Image("admin/logo.jpg", $logoX, 7, $logoWidth, 0); 
$this->Ln(30); // spacing after logo

// Invoice title
$this->SetFont('Arial','B',18);
$this->SetTextColor(44,62,80);
$this->Cell(0,10,"INVOICE",0,1,'C');
$this->Ln(5);

    }
    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial','I',11);
        $this->SetTextColor(128,128,128);
        $this->Cell(0,10,"Thank you for shopping with Infinite Jewels...!!!",0,0,'C');
    }
}

// Create PDF
$pdf = new PDF();
$pdf->AddPage();
$pdf->SetFont('Arial','',12);
$pdf->SetTextColor(0,0,0);

// Customer & Order Info
$pdf->SetFont('Arial','B',12);
$pdf->Cell(0,10,"Customer Information:",0,1);
$pdf->SetFont('Arial','',11);
$pdf->Cell(100,8,"Name: ".$order['name'],0,1);
$pdf->Cell(100,8,"Email: ".$order['email'],0,1);
$pdf->Cell(100,8,"Phone: ".$order['phone'],0,1);
$pdf->MultiCell(0,8,"Address: ".$order['address'],0,1);
$pdf->Ln(5);

$pdf->SetFont('Arial','B',12);
$pdf->Cell(0,10,"Order Information:",0,1);
$pdf->SetFont('Arial','',11);
$pdf->Cell(100,8,"Order ID: ".$order['order_id'],0,1);
$pdf->Cell(100,8,"Order Date: ".$order['created_at'],0,1);
$pdf->Ln(5);

// Product Table Header
$pdf->SetFont('Arial','B',12);
$pdf->SetFillColor(230,230,230);
$pdf->Cell(80,10,"Product",1,0,'C',true);
$pdf->Cell(30,10,"Quantity",1,0,'C',true);
$pdf->Cell(40,10,"Price",1,0,'C',true);
$pdf->Cell(40,10,"Subtotal",1,1,'C',true);

// Product Rows
$pdf->SetFont('Arial','',11);
$fill = false;
$total = 0;
foreach ($order_items as $item) {
    $pdf->SetFillColor(245,245,245);
    $pdf->Cell(80,10,$item['product_name'],1,0,'C',$fill);
    $pdf->Cell(30,10,$item['quantity'],1,0,'C',$fill);
    $pdf->Cell(40,10,"Rs.".number_format($item['price'],2),1,0,'C',$fill);
    $pdf->Cell(40,10,"Rs.".number_format($item['subtotal'],2),1,1,'C',$fill);
    $total += $item['subtotal'];
    $fill = !$fill;
}

// Total Row
$pdf->SetFont('Arial','B',12);
$pdf->SetFillColor(200,230,200);
$pdf->Cell(150,10,"Grand Total",1,0,'R',true);
$pdf->Cell(40,10,"Rs.".number_format($total,2),1,1,'C',true);
$pdf->Ln(10);

// Payment Info
if ($bill) {
    $pdf->SetFont('Arial','B',12);
    $pdf->Cell(0,10,"Payment Information:",0,1);
    $pdf->SetFont('Arial','',11);

    $pdf->SetFillColor(240,248,255);
    $pdf->Cell(95,10,"Method: ".$bill['payment_method'],1,0,'L',true);
    $pdf->Cell(95,10,"Status: ".ucfirst($bill['payment_status']),1,1,'L',true);
    $pdf->Cell(190,10,"Billing Date: ".$bill['billing_date'],1,1,'L',true);
}

// Output PDF
$pdf->Output("I","invoice_".$order['order_id'].".pdf");
?>
