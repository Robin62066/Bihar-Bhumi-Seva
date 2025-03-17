<?php
include "config/autoload.php";
session()->remove('app_id');
if (isset($_POST['button'])) {
    if (input_post('iaccept')) {
        $form = $_POST['form'];
        $form['user_id'] = user_id();
        $form['attachments'] = do_upload('pdf');
        $form['created'] = date("Y-m-d H:i:s");
        $form['updated'] = date("Y-m-d H:i:s");
        $form['status'] = 0;
        $db->insert('ai_mutation_applications', $form);
        redirect(site_url('online-mutation.php'), "Mutation Application Submitted Successfully", 'success');
    } else {
        set_flashdata('error_msg', 'You must accept terms');
    }
}
include "common/header.php";
?>
<style>
    .mutation {
        background-color: white;
        width: 45%;
        margin: 50px auto;
        padding: 20px;
        box-shadow: 1px 1px 10px grey;
    }


    .form-container h2 {
        text-align: center;
        margin-bottom: 20px;
        color: #f39c12;
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-group label {
        display: block;
        color: black;
        font-size: 16px;
        margin-bottom: 5px;
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
        width: 100%;
        padding: 10px;

        border: 1px solid #bbb;
        border-radius: 5px;
        color: black;
        font-size: 14px;
    }

    .form-group :focus,
    .form-group :focus,
    .form-group :focus {
        border-color: #f39c12;
        outline: none;
    }

    .submit-button {
        background-color: #27ae60;
        color: white;
        padding: 15px 30px;
        border: none;
        border-radius: 5px;
        float: center;
        font-size: 16px;
        cursor: pointer;
        text-align: center;
    }

    .submit-button:hover {
        background-color: #2ecc71;
    }

    .footer {
        text-align: center;
        margin-top: 30px;
        color: #bbb;
    }

    .footer a {
        color: #f39c12;
        text-decoration: none;
        font-weight: bold;
    }

    @media all and (max-width:620px) {
        .mutation {
            width: 100%;
        }
    }
</style>
<div class="mutation">
    <div class="form-container">
        <h2>Mutation Application Form</h2>
        <form id="mutationForm">
            <!-- Name Field -->
            <div class="form-group">
                <label for="name">Deed Number</label>
                <input type="text" id="name" name="name" placeholder="Enter Deed Number" required>

                <p>Scan Your Deed Registry Paper and create a PDF file and upload it.Get Mutation receipt sent to your email & whatsapp within 72 Hours.</p>
            </div>
            <div class="form-group">
                <label for="name">Add E-Mail Address</label>
                <input type="text" id="name" name="name" placeholder="E-Mail Address" required>
            </div>
            <div class="form-group">
                <label for="name">Add WhatsaApp Number</label>
                <input type="text" id="name" name="name" placeholder="WhatsaApp Number" required>
            </div>
            <div class="form-group mt-1">
                <select name="date" required>
                    <option value="" disabled selected class="form-control">Select Mutation Year</option>
                    <option value="2018-19">2018-19</option>
                    <option value="2019-20">2020-20</option>
                    <option value="2020-21">2020-20</option>
                    <option value="2021-22">2021-22</option>
                    <option value="2022-23">2022-23</option>
                    <option value="2023-24">2023-24</option>
                    <option value="2024-25">2024-25</option>
                </select>
            </div>
            <div class="form-group">
                <label class="file-upload-box">
                    Upload your file
                    <input type="file" name="file" id="fileInput" onchange="showFileName()">
                </label>

            </div>
            <div action="/action_page.php">
                <input type="checkbox" id="vehicle1" name="vehicle1" value="Bike">
                <label for="vehicle1">Terms & condition </label><br>
            </div>


            <!-- Submit Button -->
            <div class="form-group mt-2">
                <button type="button" class="submit-button">Submit</button>
            </div>
        </form>
    </div>

    <div class="footer">
        <p>For support, contact us at <a href="mailto:support@biharbhumiseva.in">support@biharbhumiseva.in</a></p>
    </div>
</div>
<?php
include "common/footer.php";
