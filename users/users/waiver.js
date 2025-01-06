function previewPDF(input) {
    var file = input.files[0];

    // Check if the file is a PDF
    if (file && file.type === 'application/pdf') {
        var fileName = file.name;
        document.getElementById('fileName').textContent = 'Selected file: ' + fileName;

        // Create a URL for the selected PDF file
        var fileURL = URL.createObjectURL(file);

        // Display the PDF in the iframe
        var pdfFrame = document.getElementById('pdfPreview');
        pdfFrame.src = fileURL;
        pdfFrame.style.display = 'block';  // Show the iframe for preview
    } else {
        document.getElementById('fileName').textContent = 'Please select a valid PDF file.';
        input.value = ''; // Clear the input if the file is not a PDF
        document.getElementById('pdfPreview').style.display = 'none';  // Hide the iframe if invalid
    }
}