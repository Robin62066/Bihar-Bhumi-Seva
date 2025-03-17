<?php
include "./config/autoload.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($_POST['submit']) {
        print_r($_POST);
        $form = $_POST['form'];
        die;
        if (isset($_FILES['resume']['name']) && $_FILES['resume']['name'] != '') {
            $form['resume'] = do_upload('resume');
        }
        $db->insert('ai_resume_upload', $user);
        session()->set_flashdata("success", "Successfully submited");
    }
}


include './common/header.php';
?>
<style>
    .upload-container {
        border: 2px dashed #ccc;
        height: 120px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        cursor: pointer;
    }

    .upload-container input[type=file] {
        display: none;
    }

    .upload-icon {
        font-size: 3rem;
        color: #ccc;
    }

    .all-inputs {
        padding-left: 12px;
    }

    .all-inputs1 {
        padding-left: 0px;
    }

    .card {
        border: none;
        box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        margin-bottom: 15px;
    }

    .card-header {

        color: black;
        padding: 15px;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-radius: 5px;
    }

    .card-header:hover {
        background-color: rgba(65, 196, 168, 0.83);
    }

    .card-header button {
        text-decoration: none;
        color: black;
        width: 100%;
        text-align: left;
        border: none;
        background: none;
        padding: 0;
    }

    .card-body {
        padding: 15px;
        color: black;
        background-color: #f8f9fa;
        border-left: 3px solid #007bff;
        border-radius: 5px;
    }

    .btn-apply {
        background-color: #28a745;
        color: white;
        font-weight: bold;
    }

    .btn-apply:hover {
        background-color: #218838;
    }

    .icon {
        transition: transform 0.3s ease;
    }

    .collapsed .icon {
        transform: rotate(0deg);
    }

    .expanded .icon {
        transform: rotate(180deg);
    }
</style>

<div class="container py-4">
    <?php
    include "common/alert.php";
    ?>
    <h2 class="mb-3">Upload Your Resume</h2>
    <p class="mb-3">Apply for exciting job opportunities with Bihar Bhumi Seva!</p>
    <form action="resume_save.php" method="POST" enctype="multipart/form-data">
        <div class="px-4">
            <div class="row mb-3">
                <label for="designationSelect" class="form-label">Avilable Roles - खाली पद <b class="text-danger">*</b></label>
                <div class="all-inputs">
                    <select class="form-select" id="designationSelect" name="designation" required>
                        <option selected>Select a designation</option>
                        <option value="Land Advocate(भूमि अधिवक्ता)">Land Advocate(भूमि अधिवक्ता)</option>
                        <option value="Data Entry Operator(डेटा एन्ट्री ऑपरेटर)">Data Entry Operator(डेटा एन्ट्री ऑपरेटर)</option>
                        <option value="Project Coordinator(प्रोजेक्ट कोऑर्डिनेटर)">Project Coordinator(प्रोजेक्ट कोऑर्डिनेटर)</option>
                        <option value="Customer Service Executive">Customer Service Executive</option>
                        <option value="Field and Operations Roles">Field and Operations Roles</option>
                        <option value="Call Center Representative">Call Center Representative</option>
                        <option value="Digital Marketing Specialist">Digital Marketing Specialist</option>
                        <option value="Social Media Manager">Social Media Manager</option>
                        <option value="Technical and IT Roles">Technical and IT Roles</option>
                        <option value="Sales and Marketing Roles">Sales and Marketing Roles</option>
                    </select>
                </div>
            </div>
            <div class="row mb-3">
                <label for="fullname" class="form-label">Full Name<b class="text-danger">*</b></label>
                <div class="all-inputs">
                    <input type="text" class="form-control bg-light" id="fullname" name="fullname" placeholder="Enter Your Name" required>
                </div>
            </div>
            <div class="row mb-3">
                <label for="email" class="form-label">Email address<b class="text-danger">*</b></label>
                <div class="all-inputs">
                    <input type="email" class="form-control bg-light" id="email" name="email" placeholder="Enter Your Email" required>
                </div>
            </div>
            <div class="row mb-3">
                <label for="mobile" class="form-label">Mobile No<b class="text-danger">*</b></label>
                <div class="all-inputs">
                    <input type="number" class="form-control bg-light" id="mobile" name="mobile" placeholder="Enter Your Number" required>
                </div>
            </div>
            <div class="row mb-3">
                <label for="resume" class="form-label">
                    <p>Upload Resume<b class="text-danger">*</b></p>
                    <!-- <label for="resume"> -->
                    <div class="all-inputs1">
                        <div class="upload-container form-label" for="resume">
                            <i class="upload-icon bi bi-cloud-upload"> </i>
                            <input type="file" id="resume" name="resume" class="form-control" accept=".jpg,.jpeg,.png,.gif,.pdf,.doc,.docx" required>
                            <p>Drop your file here or click to upload</p>
                        </div>
                    </div>
                </label>
            </div>
            <button type="submit" class="btn btn-primary" name="upload">Submit</button>
        </div>
    </form>

    <div class="note mt-3 mb-4">
        <p>Note: Ensure your resume is up to date before submitting.</p>
    </div>
    <h1 class="mb-4 mt-4">Careers at Bihar Bhumi Seva</h1>

 
    <div class="accordion" id="careersAccordion">

        <!-- Administrative Roles -->
        <div class="card">
            <div class="card-header" data-bs-toggle="collapse" data-bs-target="#administrativeRoles">
                <button class="collapsed" type="button">
                    Administrative Roles
                </button>
                <i class="bi bi-plus"></i>
            </div>
            <div id="administrativeRoles" class="collapse" data-bs-parent="#careersAccordion">
                <div class="card-body">
                    Operations Manager, Branch Manager, Office Administrator, HR Executive, Data Entry Operator
                    <br>
                    <button class="btn btn-apply mt-2" data-bs-toggle="modal" data-bs-target="#property">APPLY</button>
                    <!-- <a href="#">Property</a> -->
                </div>
            </div>
        </div>

        <!-- Customer Service Roles -->
        <div class="card">
            <div class="card-header" data-bs-toggle="collapse" data-bs-target="#customerServiceRoles">
                <button class="collapsed" type="button">
                    Customer Service Roles
                </button>
                <i class="bi bi-plus"></i>
            </div>
            <div id="customerServiceRoles" class="collapse" data-bs-parent="#careersAccordion">
                <div class="card-body">
                    Customer Service Executive, Call Center Representative, Client Relationship Manager
                    <br>
                    <button class="btn btn-apply mt-2" data-bs-toggle="modal" data-bs-target="#property">APPLY</button>
                </div>
            </div>
        </div>

        <!-- Technical and IT Roles -->
        <div class="card">
            <div class="card-header" data-bs-toggle="collapse" data-bs-target="#technicalRoles">
                <button class="collapsed" type="button">
                    Technical and IT Roles
                </button>
                <i class="bi bi-plus"></i>
            </div>
            <div id="technicalRoles" class="collapse" data-bs-parent="#careersAccordion">
                <div class="card-body">
                    Software Engineer, Web Developer, IT Support Specialist, System Administrator
                    <br>
                    <button class="btn btn-apply mt-2" data-bs-toggle="modal" data-bs-target="#property">APPLY</button>
                </div>
            </div>
        </div>

        <!-- Field and Operations Roles -->
        <div class="card">
            <div class="card-header" data-bs-toggle="collapse" data-bs-target="#fieldRoles">
                <button class="collapsed" type="button">
                    Field and Operations Roles
                </button>
                <i class="bi bi-plus"></i>
            </div>
            <div id="fieldRoles" class="collapse" data-bs-parent="#careersAccordion">
                <div class="card-body">
                    Field Officer, Logistics Coordinator, Maintenance Supervisor
                    <br>
                    <button class="btn btn-apply mt-2" data-bs-toggle="modal" data-bs-target="#property">APPLY</button>
                </div>
            </div>
        </div>

        <!-- Legal and Compliance Roles -->
        <div class="card">
            <div class="card-header" data-bs-toggle="collapse" data-bs-target="#legalRoles">
                <button class="collapsed" type="button">
                    Legal and Compliance Roles
                </button>
                <i class="bi bi-plus"></i>
            </div>
            <div id="legalRoles" class="collapse" data-bs-parent="#careersAccordion">
                <div class="card-body">
                    Legal Advisor, Compliance Officer, Contract Manager
                    <br>
                    <button class="btn btn-apply mt-2" data-bs-toggle="modal" data-bs-target="#property">APPLY</button>
                </div>
            </div>
        </div>

        <!-- Sales and Marketing Roles -->
        <div class="card">
            <div class="card-header" data-bs-toggle="collapse" data-bs-target="#selesRole">
                <button class="collapsed" type="button">
                    Sales and Marketing Roles
                </button>
                <i class="bi bi-plus"></i>
            </div>
            <div id="selesRole" class="collapse" data-bs-parent="#careersAccordion">
                <div class="card-body">
                    Sales Executive (Land Services Packages), Marketing Manager, Business Development Executive, Franchise Coordinator, Public Relations Officer
                    <br>
                    <button class="btn btn-apply mt-2" data-bs-toggle="modal" data-bs-target="#property">APPLY</button>
                </div>
            </div>
        </div>

        <!-- Training and Development Roles -->
        <div class="card">
            <div class="card-header" data-bs-toggle="collapse" data-bs-target="#traningRoles">
                <button class="collapsed" type="button">
                    Training and Development Roles
                </button>
                <i class="bi bi-plus"></i>
            </div>
            <div id="traningRoles" class="collapse" data-bs-parent="#careersAccordion">
                <div class="card-body">
                    Training Coordinator, Land Services Trainer, Employee Development Officer
                    <br>
                    <button class="btn btn-apply mt-2" data-bs-toggle="modal" data-bs-target="#property">APPLY</button>
                </div>
            </div>
        </div>

        <!--Other Specialized Roles -->
        <div class="card">
            <div class="card-header" data-bs-toggle="collapse" data-bs-target="#otherRoles">
                <button class="collapsed" type="button">
                    Other Specialized Roles
                </button>
                <i class="bi bi-plus"></i>
            </div>
            <div id="otherRoles" class="collapse" data-bs-parent="#careersAccordion">
                <div class="card-body">
                    Project Manager (Land Digitization Projects), Research Analyst (Land Policy and Trends), Quality Assurance Officer, Digital Marketing Specialist, Social Media Manager
                    <br>
                    <button class="btn btn-apply mt-2" data-bs-toggle="modal" data-bs-target="#property">APPLY</button>

                </div>
            </div>
        </div>

    </div>

</div>

<!-- Bootstrap Modal -->
<div class="modal fade" id="property" data-bs-keyboard="true" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-center">
                <h2 class="modal-title fs-5 fw-bold" id="staticBackdropLabel">Apply for exciting job opportunities with Bihar Bhumi Seva!</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="text-center mb-4">अब अपने पसंदीदा जिले में जमीन खरीदने का सपना पूरा करें। अपनी जानकारी दर्ज करें और हम आपकी मदद करेंगे।</p>

                <!-- Form -->
                <form id="jobApplicationForm" method="POST" enctype="multipart/form-data">
                    <div class="px-4">
                        <div class="row mb-3">
                            <label for="designationSelect" class="form-label">Available Roles - खाली पद <b class="text-danger">*</b></label>
                            <div class="all-inputs">
                                <select class="form-select" id="designationSelect" name="designation" required>
                                    <option selected>Select a designation</option>
                                    <option value="Land Advocate(भूमि अधिवक्ता)">Land Advocate(भूमि अधिवक्ता)</option>
                                    <option value="Data Entry Operator(डेटा एन्ट्री ऑपरेटर)">Data Entry Operator(डेटा एन्ट्री ऑपरेटर)</option>
                                    <option value="Project Coordinator(प्रोजेक्ट कोऑर्डिनेटर)">Project Coordinator(प्रोजेक्ट कोऑर्डिनेटर)</option>
                                    <option value="Customer Service Executive">Customer Service Executive</option>
                                    <option value="Field and Operations Roles">Field and Operations Roles</option>
                                    <option value="Call Center Representative">Call Center Representative</option>
                                    <option value="Digital Marketing Specialist">Digital Marketing Specialist</option>
                                    <option value="Social Media Manager">Social Media Manager</option>
                                    <option value="Technical and IT Roles">Technical and IT Roles</option>
                                    <option value="Sales and Marketing Roles">Sales and Marketing Roles</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="fullname" class="form-label">Full Name<b class="text-danger">*</b></label>
                            <div class="all-inputs">
                                <input type="text" class="form-control bg-light" id="fullname" name="fullname" placeholder="Enter Your Name" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="email" class="form-label">Email address<b class="text-danger">*</b></label>
                            <div class="all-inputs">
                                <input type="email" class="form-control bg-light" id="email" name="email" placeholder="Enter Your Email" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="mobile" class="form-label">Mobile No<b class="text-danger">*</b></label>
                            <div class="all-inputs">
                                <input type="number" class="form-control bg-light" id="mobile" name="mobile" placeholder="Enter Your Number" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="resume" class="form-label">Upload Resume<b class="text-danger">*</b></label>
                            <div class="all-inputs1">
                                <input type="file" id="resume" name="resume" class="form-control" accept=".jpg,.jpeg,.png,.gif,.pdf,.doc,.docx" required>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary btn-submit w-100">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>



<script>
    $(document).ready(function() {
        $("#jobApplicationForm").on("submit", function(e) {
            e.preventDefault(); // Prevent default form submission

            var formData = new FormData(this); // Get form data

            $.ajax({
                url: "submit.php", // Backend file
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    alert(response); // Show success message
                    $("#property").modal("hide"); // Close modal on success
                    $("#jobApplicationForm")[0].reset(); // Reset form
                },
                error: function() {
                    alert("Error submitting the form.");
                }
            });
        });
    });
</script>

<?php
include './common/footer.php';
?>