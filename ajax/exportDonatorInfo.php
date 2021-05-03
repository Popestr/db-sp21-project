<?php

session_start();
require_once('./config.php');

// adapted from https://dev.to/madeinmilwaukee/php-mysqli-and-csv-export-from-database-5a3j

$q = "SELECT u.email, SUM(p.amount) as \"total pixels bought\"
FROM `Pixel_Purchases` pp 
LEFT OUTER JOIN `Purchases` p on p.purchase_id=pp.purchase_id 
LEFT OUTER JOIN `Pixel_Charities` pc on pc.pixel_id=pp.pixel_id
LEFT OUTER JOIN `Users` u on u.id=p.purchaser_id
LEFT OUTER JOIN `Charity_Admins` ca on ca.charity_id=pc.charity_id
WHERE pc.charity_id=?
AND ca.admin_id=?
GROUP BY u.email ";
$stmt = $con->prepare($q);
$stmt->bind_param('ii', $_GET["cid"], $_SESSION["id"]);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
   //These next 3 Lines Set the CSV header line if needed
   $data = $result->fetch_assoc();
   $csv[] = array_keys($data);
   $result->data_seek(0);
   //SET THE CSV BODY LINES
    while ($data = $result->fetch_assoc()) {
        $csv[] = array_values($data);
    }
    header('Content-Type: application/csv');
    header('Content-Disposition: attachment; filename="export-'.time().'.csv";');
    //You could save the file on your server
    //but we want to download it directly so we use php://output
    $f = fopen('php://output', 'w');
    foreach ($csv as $line) {
        fputcsv($f, $line, ',');
    }
    exit;
} else {
    echo 'No data to export. Either you are not the admin of this charity, or your charity has not recieved any donations.';
}


?>