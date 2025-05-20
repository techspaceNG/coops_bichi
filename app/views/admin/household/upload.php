<?php
// Admin Household Payments Upload View
?>
<div class="container-fluid px-4">
    <h1 class="mt-4">Upload Household Payments</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="/Coops_Bichi/public/admin/dashboard">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="/Coops_Bichi/public/admin/household">Household Purchases</a></li>
        <li class="breadcrumb-item active">Upload Payments</li>
    </ol>
    
    <div class="row">
        <div class="col-xl-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-upload me-1"></i>
                    Upload Household Payment Data
                </div>
                <div class="card-body">
                    <form action="/Coops_Bichi/public/admin/household/process-upload" method="post" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="file" class="form-label">Select File (CSV or Excel)</label>
                            <input type="file" class="form-control" id="file" name="file" accept=".csv, .xlsx" required>
                            <div class="form-text">
                                Upload a CSV or Excel file with payment data.
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Upload File</button>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-xl-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-info-circle me-1"></i>
                    File Format Instructions
                </div>
                <div class="card-body">
                    <p>The file should contain the following columns:</p>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Column</th>
                                <th>Description</th>
                                <th>Required</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>coop_no</td>
                                <td>Member's Cooperative Number</td>
                                <td>Yes</td>
                            </tr>
                            <tr>
                                <td>amount</td>
                                <td>Payment Amount</td>
                                <td>Yes</td>
                            </tr>
                            <tr>
                                <td>payment_date</td>
                                <td>Date of Payment (YYYY-MM-DD)</td>
                                <td>No</td>
                            </tr>
                            <tr>
                                <td>reference</td>
                                <td>Payment Reference</td>
                                <td>No</td>
                            </tr>
                        </tbody>
                    </table>
                    
                    <div class="mt-3">
                        <h6>Sample CSV Format:</h6>
                        <pre class="bg-light p-2">
coop_no,amount,payment_date,reference
COOPS001,5000,2023-08-15,REF12345
COOPS002,7500,2023-08-15,REF12346
                        </pre>
                    </div>
                    
                    <div class="alert alert-info mt-3">
                        <strong>Note:</strong> The system will automatically update the household purchase balance for each member based on the payment amount.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> 