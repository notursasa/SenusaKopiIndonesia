<?php

function generateId($prefix, $table, $column) {
    global $conn;
    
    $prefixLength = strlen($prefix);
    $totalLength = 13;
    
    $query = "SELECT $column FROM $table WHERE $column LIKE '$prefix%' ORDER BY $column DESC LIMIT 1";
    $result = mysqli_query($conn, $query);
    
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $lastId = $row[$column];
        
        $number = (int) substr($lastId, $prefixLength);
        $newNumber = $number + 1;
    } else {
        $newNumber = 1;
    }
    
    $numberLength = $totalLength - $prefixLength;
    $newId = $prefix . str_pad($newNumber, $numberLength, "0", STR_PAD_LEFT);
    
    return $newId;
}

function formatRupiah($angka) {
    return "Rp " . number_format($angka, 0, ',', '.');
}

function cleanInput($data) {
    global $conn;
    return mysqli_real_escape_string($conn, htmlspecialchars($data));
}
?>