<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contract Details</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <style>
        .contract-card {
            border-left: 4px solid #4e73df;
        }

        .detail-label {
            font-weight: 600;
            color: #6c757d;
        }

        .detail-value {
            font-weight: 500;
        }

        .witness-section {
            background-color: #f8f9fa;
            border-radius: 5px;
        }

        .document-badge {
            font-size: 0.85rem;
        }
    </style>
</head>

<body>
    <div class="container py-4">
        <div class="card contract-card shadow mb-4">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom-0">
                <h4 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-file-contract mr-2"></i>Lease Agreement #CN-00528
                </h4>
                <div>
                    <span class="badge badge-success badge-pill py-2 px-3">Active</span>
                </div>
            </div>
            <div class="card-body">
                <!-- Basic Information Section -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h5 class="font-weight-bold text-primary mb-3">
                            <i class="fas fa-info-circle mr-2"></i>Basic Information
                        </h5>
                        <div class="row mb-2">
                            <div class="col-5 detail-label">Contract Type:</div>
                            <div class="col-7 detail-value">Residential Lease</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-5 detail-label">Created On:</div>
                            <div class="col-7 detail-value">Nov 15, 2023</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-5 detail-label">Has Family:</div>
                            <div class="col-7 detail-value">Yes <span class="text-muted">(2 dependents)</span></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h5 class="font-weight-bold text-primary mb-3">
                            <i class="fas fa-calendar-alt mr-2"></i>Lease Period
                        </h5>
                        <div class="row mb-2">
                            <div class="col-5 detail-label">Start Date:</div>
                            <div class="col-7 detail-value">Dec 1, 2023</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-5 detail-label">End Date:</div>
                            <div class="col-7 detail-value">Nov 30, 2024</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-5 detail-label">Duration:</div>
                            <div class="col-7 detail-value">12 months</div>
                        </div>
                    </div>
                </div>

                <!-- Tenant and Property Section -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h5 class="font-weight-bold text-primary mb-3">
                            <i class="fas fa-user-tie mr-2"></i>Tenant Details
                        </h5>
                        <div class="d-flex align-items-center mb-3">
                            <div class="rounded-circle bg-light p-3 mr-3">
                                <i class="fas fa-user text-primary fa-2x"></i>
                            </div>
                            <div>
                                <h6 class="font-weight-bold mb-0">John M. Smith</h6>
                                <p class="text-muted mb-1">Tenant ID: T-00142</p>
                                <p class="mb-0">
                                    <i class="fas fa-phone-alt mr-1 text-muted"></i> (555) 123-4567
                                </p>
                            </div>
                        </div>
                        <div class="pl-5">
                            <div class="row mb-2">
                                <div class="col-5 detail-label">Email:</div>
                                <div class="col-7 detail-value">john.smith@example.com</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-5 detail-label">ID Number:</div>
                                <div class="col-7 detail-value">A123456789</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h5 class="font-weight-bold text-primary mb-3">
                            <i class="fas fa-home mr-2"></i>Property Details
                        </h5>
                        <div class="d-flex align-items-center mb-3">
                            <div class="rounded-circle bg-light p-3 mr-3">
                                <i class="fas fa-building text-primary fa-2x"></i>
                            </div>
                            <div>
                                <h6 class="font-weight-bold mb-0">Sunset Apartments</h6>
                                <p class="text-muted mb-1">Unit #B-205</p>
                                <p class="mb-0">
                                    <i class="fas fa-map-marker-alt mr-1 text-muted"></i> 123 Main St, Springfield
                                </p>
                            </div>
                        </div>
                        <div class="pl-5">
                            <div class="row mb-2">
                                <div class="col-5 detail-label">Property Owner:</div>
                                <div class="col-7 detail-value">Robert Johnson</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-5 detail-label">Monthly Rent:</div>
                                <div class="col-7 detail-value">$1,250.00</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-5 detail-label">Deposit Paid:</div>
                                <div class="col-7 detail-value">$1,250.00 <span class="text-success">(Paid)</span></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Witness and Documents Section -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="witness-section p-3 mb-4">
                            <h5 class="font-weight-bold text-primary mb-3">
                                <i class="fas fa-user-friends mr-2"></i>Witness Information
                            </h5>
                            <div class="row mb-2">
                                <div class="col-5 detail-label">Full Name:</div>
                                <div class="col-7 detail-value">Michael Anderson</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-5 detail-label">Phone:</div>
                                <div class="col-7 detail-value">(555) 987-6543</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-5 detail-label">Relationship:</div>
                                <div class="col-7 detail-value">Notary Public</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h5 class="font-weight-bold text-primary mb-3">
                            <i class="fas fa-file-alt mr-2"></i>Contract Documents
                        </h5>
                        <div class="d-flex align-items-center mb-3">
                            <div class="mr-3 text-primary">
                                <i class="fas fa-file-pdf fa-2x"></i>
                            </div>
                            <div>
                                <h6 class="font-weight-bold mb-0">Lease_Agreement_CN-00528.pdf</h6>
                                <p class="text-muted mb-0">Signed on Nov 20, 2023</p>
                            </div>
                        </div>
                        <div>
                            <button class="btn btn-outline-primary btn-sm document-badge mr-2">
                                <i class="fas fa-download mr-1"></i> Download
                            </button>
                            <button class="btn btn-outline-secondary btn-sm document-badge">
                                <i class="fas fa-eye mr-1"></i> Preview
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-white border-top-0 pt-0">
                <div class="d-flex justify-content-between">
                    <button class="btn btn-secondary">
                        <i class="fas fa-arrow-left mr-1"></i> Back to List
                    </button>
                    <div>
                        <button class="btn btn-outline-danger mr-2">
                            <i class="fas fa-times mr-1"></i> Terminate
                        </button>
                        <button class="btn btn-primary">
                            <i class="fas fa-edit mr-1"></i> Edit Contract
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>