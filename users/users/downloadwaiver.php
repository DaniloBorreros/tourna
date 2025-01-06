<?php
// Path to the waiver file
$file = '../waiver.pdf';

// Check if the file exists
if (file_exists($file)) {
    // Set headers to force the download
    header('Content-Description: File Transfer');
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="waiver.pdf"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file));

    // Read the file and output it to the browser
    readfile($file);
    exit;
} else {
    echo "File not found.";
}
?>
