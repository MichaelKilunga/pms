@extends('reports.app')
@section('name')
    <script>
        if (response.rows > 0) {
            $('.reportsTable').DataTable().destroy(); // Destroy the old table
            $('.reportsTable').DataTable({
                paging: true, // Enable paging
                searching: true, // Enable search bar
                ordering: true, // Enable column sorting
                info: true, // Enable information display
                lengthMenu: [10, 25, 50, 100], // Dropdown for records per page
                pageLength: 10, // Default number of records per page
                dom: 'Bfrtip', // Add Buttons to the table
                buttons: [
                    {
                        extend: 'csvHtml5',
                        title: '', // Leave title blank to customize it manually
                        text: 'Download CSV',
                        className: 'btn btn-primary reportsDownloadButton',
                        exportOptions: {
                            format: {
                                body: function(data, row, column, node) {
                                    return $('<div>').html(data).text() // Convert HTML to plain text
                                        .replace(/\s+/g, ' ') // Replace multiple spaces with a single space
                                        .replace(/\u00A0/g, '') // Remove non-breaking spaces (&nbsp;)
                                        .replace(/TSh|TZS|,/g, '') // Remove currency symbols and commas
                                        .trim(); // Remove leading and trailing spaces
                                }
                            }
                        },
                        customize: function(csv) {
                            let reportType = "Sales Report"; // Replace with dynamic report type if needed
                            let dateRange =
                            "From: 2024-04-01 To: 2024-04-30"; // Replace dynamically if needed

                            let customHeader = `"Company Name","${reportType}","${dateRange}"\n`;
                            return customHeader + csv; // Prepend custom header to CSV file
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        title: '', // Leave title blank to customize it manually
                        text: 'Download PDF',
                        className: 'btn btn-secondary reportsDownloadButton',
                        orientation: 'landscape', // Landscape orientation for PDF
                        pageSize: 'A4', // A4 page size
                        customize: function(doc) {
                            let reportType = "Sales Report"; // Replace dynamically if needed
                            let dateRange =
                            "From: 2024-04-01 To: 2024-04-30"; // Replace dynamically if needed
                            let logoUrl =
                            "https://yourwebsite.com/path-to-logo.png"; // Replace with your logo URL

                            // Add logo
                            doc.content.unshift({
                                image: logoUrl,
                                width: 100,
                                alignment: 'center',
                                margin: [0, 0, 0, 10] // Top margin
                            });

                            // Add report type
                            doc.content.unshift({
                                text: reportType,
                                fontSize: 14,
                                bold: true,
                                alignment: 'center',
                                margin: [0, 10, 0, 10] // Margins: left, top, right, bottom
                            });

                            // Add date range
                            doc.content.unshift({
                                text: dateRange,
                                fontSize: 12,
                                italics: true,
                                alignment: 'center',
                                margin: [0, 0, 0, 10]
                            });
                        }
                    }
                ],
                columnDefs: columnDefs,
                error: function(settings, helpPage, message) {
                    console.error('DataTables Error:', message);
                }
            });
        }
    </script>
@endsection
