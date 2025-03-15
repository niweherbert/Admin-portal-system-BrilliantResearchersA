<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Materials Request Form</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/handsontable/dist/handsontable.full.min.css">
    <script src="https://cdn.jsdelivr.net/npm/handsontable/dist/handsontable.full.min.js"></script>
    <style>
        #materialsTable {
            width: 100%;
            margin-top: 20px;
        }
    </style>
</head>
<body>

    <img src="SHDR.PNG.png" alt="Company Logo" style="width: 100px; height: auto;">
    <h2>Materials Request</h2>
    <form id="materialsForm" action="/views/generate_pdf.php" method="post">
        <label>Project Name: <input type="text" name="project_name"></label> <br> <br>
        <label>Location: <input type="text" name="location"></label><br> <br>
        <label>Requisition Number: <input type="text" name="req_number"></label> <br><br>
        <label>Date: <input type="date" name="date"></label>

        <div id="materialsTable"></div>
        
        <input type="hidden" name="table_data" id="table_data">
        <br><br>
        <button type="button" id="addRow">Add Row</button>
        <button type="submit">Generate PDF</button>
    </form>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const container = document.getElementById('materialsTable');
            const hot = new Handsontable(container, {
                data: [
                    ['', '', '', '', '', '']
                ],
                colHeaders: ['Description', 'Material Name', 'Unit', 'Quantity', 'Unit Price', 'Total Amount'],
                columns: [
                    { data: 0 },
                    { data: 1 },
                    { data: 2 },
                    { data: 3, type: 'numeric' },
                    { data: 4, type: 'numeric' },
                    { data: 5, type: 'numeric', readOnly: true }
                ],
                afterChange: function(changes, source) {
                    if (source === 'edit') {
                        changes.forEach(([row, prop, oldValue, newValue]) => {
                            if (prop === 3 || prop === 4) {
                                const quantity = hot.getDataAtCell(row, 3);
                                const unitPrice = hot.getDataAtCell(row, 4);
                                const total = quantity * unitPrice;
                                hot.setDataAtCell(row, 5, total);
                            }
                        });
                    }
                },
                minSpareRows: 1,
                contextMenu: true,
                rowHeaders: true,
                licenseKey: 'non-commercial-and-evaluation'
            });

            document.getElementById('addRow').addEventListener('click', function() {
                hot.alter('insert_row');
            });

            document.getElementById('materialsForm').addEventListener('submit', function() {
                const tableData = hot.getData();
                document.getElementById('table_data').value = JSON.stringify(tableData);
            });
        });
    </script>

</body>
</html>