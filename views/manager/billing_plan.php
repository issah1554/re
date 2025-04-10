    <div class="container-fluid">
        <div class="row">
            <!-- Main Content -->
            <main class="col-md-12 ms-sm-auto col-lg-12 px-md-4 py-4">
                <div
                    class="d-flex justify-content-end flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <button type="button" class="btn btn-sm btn-primary" data-toggle="modal"
                            data-target="#newContractModal">
                            <i class="fas fa-plus me-1"></i> New Contract
                        </button>
                    </div>
                </div>

                <!-- Contracts Section -->
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span><i class="fas fa-file-contract me-2"></i> Active Contracts</span>
                        <div class="input-group" style="width: 300px;">
                            <input type="text" class="form-control form-control-sm" placeholder="Search contracts...">
                            <button class="btn btn-sm btn-outline-secondary" type="button">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Contract #</th>
                                        <th>Tenant</th>
                                        <th>Property</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th>Monthly Rent</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>CT-2023-001</td>
                                        <td>John Smith</td>
                                        <td>Apartment 101</td>
                                        <td>2023-01-15</td>
                                        <td>2024-01-14</td>
                                        <td>$1,200.00</td>
                                        <td><span class="contract-status status-active">Active</span></td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary me-1"><i
                                                    class="fas fa-eye"></i></button>
                                            <button class="btn btn-sm btn-outline-secondary me-1"><i
                                                    class="fas fa-edit"></i></button>
                                            <button class="btn btn-sm btn-outline-danger"><i
                                                    class="fas fa-trash"></i></button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>CT-2023-002</td>
                                        <td>Sarah Johnson</td>
                                        <td>Apartment 205</td>
                                        <td>2023-03-01</td>
                                        <td>2024-02-28</td>
                                        <td>$1,350.00</td>
                                        <td><span class="contract-status status-active">Active</span></td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary me-1"><i
                                                    class="fas fa-eye"></i></button>
                                            <button class="btn btn-sm btn-outline-secondary me-1"><i
                                                    class="fas fa-edit"></i></button>
                                            <button class="btn btn-sm btn-outline-danger"><i
                                                    class="fas fa-trash"></i></button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>CT-2023-003</td>
                                        <td>Michael Brown</td>
                                        <td>Apartment 302</td>
                                        <td>2023-05-10</td>
                                        <td>2024-05-09</td>
                                        <td>$1,500.00</td>
                                        <td><span class="contract-status status-active">Active</span></td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary me-1"><i
                                                    class="fas fa-eye"></i></button>
                                            <button class="btn btn-sm btn-outline-secondary me-1"><i
                                                    class="fas fa-edit"></i></button>
                                            <button class="btn btn-sm btn-outline-danger"><i
                                                    class="fas fa-trash"></i></button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <nav aria-label="Contracts pagination">
                            <ul class="pagination justify-content-end">
                                <li class="page-item disabled">
                                    <a class="page-link" href="#" tabindex="-1">Previous</a>
                                </li>
                                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                <li class="page-item"><a class="page-link" href="#">2</a></li>
                                <li class="page-item"><a class="page-link" href="#">3</a></li>
                                <li class="page-item">
                                    <a class="page-link" href="#">Next</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </main>
        </div>
        <!-- Add this modal code just before the closing </body> tag in the previous template -->

        <!-- New Contract Modal -->
        <div class="modal fade" id="newContractModal" tabindex="-1" aria-labelledby="newContractModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-light border-bottom">
                        <h5 class="modal-title" id="newContractModalLabel">
                            <i class="fas fa-file-contract me-2 text-primary"></i> New Contract
                        </h5>
                        <span aria-hidden="true" type="button" class="close" data-bs-dismiss="modal" aria-label="Close">&times;</span>
                    </div>
                    <div class="modal-body">
                        <form id="contractForm">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="tenantSelect" class="form-label fw-bold">Tenant</label>
                                    <select class="form-control" id="tenantSelect" required>
                                        <option value="" selected disabled>Select Tenant</option>
                                        <option value="1">John Smith</option>
                                        <option value="2">Sarah Johnson</option>
                                        <option value="3">Michael Brown</option>
                                        <option value="4">Emily Davis</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="propertySelect" class="form-label fw-bold">Property</label>
                                    <select class="form-control" id="propertySelect" required>
                                        <option value="" selected disabled>-- Select Property --</option>
                                        <option value="101">Apartment 101</option>
                                        <option value="205">Apartment 205</option>
                                        <option value="302">Apartment 302</option>
                                        <option value="410">Apartment 410</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="startDate" class="form-label fw-bold">Start Date</label>
                                    <input type="date" class="form-control" id="startDate" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="endDate" class="form-label fw-bold">Months</label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        id="endDate"
                                        placeholder="e.g., 12 "
                                        oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);"
                                        required>
                                </div>
                                <div class="col-md-4">
                                    <label for="contractType" class="form-label fw-bold">Contract Type</label>
                                    <select class="form-control" id="contractType" required>
                                        <option value="" selected disabled>-- Select Property --</option>
                                        <option value="resident">Resident</option>
                                        <option value="commercial">Commercial Lease</option>
                                    </select>
                                </div>

                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="contractFile" class="form-label">Upload Contract Document <span class="text-danger"> (PDF)</span></label>
                                        <div class="custom-file">
                                            <input type="file" name="contract_file" id="contractFile" class="custom-file-input" accept="image/png, image/jpeg, image/jpg">
                                            <label class="custom-file-label" for="avatar">Choose a document</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 d-flex align-items-center">
                                    <div class="form-check mt-3">
                                        <input type="checkbox" class="form-check-input" id="hasFamily">
                                        <label class="form-check-label" for="hasFamily">Has Family?</label>
                                    </div>
                                </div>

                            </div>

                            <hr>
                            <div class="text-primary">Witness Details</div>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="witnessFname" class="form-label">First Name</label>
                                    <input type="text" class="form-control" id="witnessFname" placeholder="Witness First Name">
                                </div>
                                <div class="col-md-4">
                                    <label for="witnessLname" class="form-label">Last Name</label>
                                    <input type="text" class="form-control" id="witnessLname" placeholder="Witness Last Name">
                                </div>
                                <div class="col-md-4">
                                    <label for="witnessPhone" class="form-label">Witness Phone</label>
                                    <input 
                                    type="tel" 
                                    class="form-control" 
                                    id="witnessPhone" 
                                    placeholder="e.g., 0722334455"
                                    oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);">
                                </div>

                            </div>

                            <div class="row mb-3">

                            </div>
                        </form>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" id="saveContractBtn">
                            <i class="fas fa-save me-1"></i> Save Contract
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- JavaScript to handle the modal -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Form submission handling
                document.getElementById('saveContractBtn').addEventListener('click', function() {
                    const form = document.getElementById('contractForm');
                    if (form.checkValidity()) {
                        // Here you would handle the form submission (AJAX call to your backend)
                        alert('Contract saved successfully!');
                        // Close the modal
                        var modal = bootstrap.Modal.getInstance(document.getElementById('newContractModal'));
                        modal.hide();
                    } else {
                        form.reportValidity();
                    }
                });
            });
        </script>
    </div>