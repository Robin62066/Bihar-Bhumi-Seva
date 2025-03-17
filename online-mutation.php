<?php

use Razorpay\Api\Api;

include "config/autoload.php";
session()->remove('app_id');

if (isset($_POST['mutation'])) {
    if (!is_login()) {
        redirect(site_url('online-mutation.php'), "You must login to continue", 'error');
    }

    $user = $db->select('ai_users', ['id' => user_id()], 1)->row();
    if (input_post('iaccept')) {
        $form                = $_POST['form'];
        $form['user_id']     = user_id();
        $form['documents']   = do_upload('pdf');
        $form['created']     = date("Y-m-d H:i:s");
        $form['updated']     = date("Y-m-d H:i:s");
        $form['status']      = 0;
        $db->insert('ai_mutations_app', $form);
        $app_id = $db->id();
        $receipt_id = "BS" . str_pad(time() . $app_id, 10, '0', STR_PAD_LEFT);

        // Update mutation status
        $db->update('ai_mutations_app', ['case_no' => $receipt_id], ['id' => $app_id], 1);

        $amount            = 199;                                                           // Rs 5
        $api               = new Api(RAZOR_KEY_ID, RAZOR_KEY_SECRET);
        $sb                = [];
        $sb['created']     = date("Y-m-d H:i:s");
        $sb['amount']      = $amount;
        $sb['status']      = 0;
        $sb['receipt_id']  = $receipt_id;
        $sb['cust_name']   = $user->first_name;
        $sb['cust_mobile'] = $user->mobile_number;
        $sb['cust_email']  = $user->email_id;
        $sb['notes']       = 'mutations-app';
        $sb['user_id']     = $user_id;
        $db->insert("ai_orders", $sb);
        $id = $db->id();

        $item = $api->order->create(['receipt' => $receipt_id, 'amount' => $amount * 100, 'currency' => 'INR', 'notes' => array('key1' => 'value3')]);
        if ($item->status == 'created') {
            $rzp_id = $item->id;
            $db->update('ai_orders', ['rzp_order_id' => $rzp_id], ['id' => $id]);
            $sb['rzp_order_id'] = $rzp_id;
            $db->update('ai_mutations', ['order_id' => $id], ['id' => $app_id]);
        }
        session()->set('app_id', $app_id);
        redirect('apply-confirm.php', "Application Details Saved successfully. Please pay to Proceed");
    } else {
        set_flashdata('error_msg', 'You must accept terms');
    }
}

include "common/header.php";
?>
<style>
    .mutation {
        background-color: white;
        padding: 20px;
        border: 1px solid rgb(219, 178, 168);
        border-radius: 5px;
    }


    .form-container h2 {
        text-align: center;
        margin-bottom: 20px;
        color: darkcyan;
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
        padding: 10px 22px;
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
        color: darkcyan;
        text-decoration: none;
        font-weight: bold;
    }

    @media all and (max-width:620px) {
        .mutation {
            width: 100%;
        }
    }
</style>
<style>
    ol li {
        margin-bottom: 20px;
    }

    .qlist-overview {
        font-size: 16px;
    }

    .answer {
        padding: 10px;
        background-color: #eee;
        border-radius: 3px;
        font-size: 14px;
    }

    ol li .question {
        cursor: pointer;
    }

    ol li .answer {
        display: none;
    }
</style>
<div class="container">
    <div class="p-3 bg-white">
        <?= front_view("common/alert"); ?>
        <h1 class="h5 mb-4 text-center text-success">Online Mutations Bihar</h1>
        <div class="row mb-4">
            <div class="col-sm-6">
                <div class="card p-4 text-center">
                    <h5 class="text-success">Quick Apply Mutation</h5>
                    <hr />
                    <p>You can aplly for mutation using this quick apply option in 2 mins </p>
                    <button data-bs-toggle="modal" data-bs-target="#basic" class="btn btn-primary btn-lg btn-success">Apply Now</button>
                </div>

            </div>
            <div class="col-sm-6">
                <div class="card p-4 mb-3 text-center">
                    <h5 class="text-primary">Apply By Form Online</h5>
                    <hr />
                    <p>In the next few steps, you will need to upload all required documents and we will process it.</p>
                    <a href="apply-mutation.php?step=1" class="btn btn-primary btn-lg btn-primary">Click Here</a>
                </div>

            </div>
        </div>
        <div class="mb-3">
            <div class="mb-4">
                <h5 class="text-center">Frequently Asked Questions</h5>
            </div>
            <ol class="qlist-overview">
                <li>
                    <div class="question"><b>ऑनलाइन दाखिल ख़ारिज करने के बाद कितने दिनों में शुद्धि पत्र आता हैं?</b></div>
                    <div class="answer">
                        दाखिल खरीक करने के बाद एक से दो महीने लग सकते हैं।
                    </div>
                </li>
                <li>
                    <div class="question"><b>ऑनलाइन दाखिल खारिज करने में लगने वाले जरूरी दस्तावेज?</b></div>
                    <div class="answer">
                        1. जमीन का फुल दस्तावेज (एक ही PDF File में) <br />
                        2. आधार कार्ड (जिनके नाम पे जमीन है ) <br />
                        3. मोबाइल नंबर (बेचने वाले और खरीदने वाले दोनों के) <br />
                        4. ईमेल ID
                    </div>
                </li>
                <li>
                    <div class="question"><b>ऑनलाइन म्युटेशन Reject होने के बाद फिर से कैसे अप्लाई करें?</b></div>
                    <div class="answer">दुबारा अप्लाई करने के लिए आपको सेम वहीं स्टेप फॉलो करना जैसे आपने पहले आवेदन किया था , सिर्फ Document Date को एक दिन आगे या पीछे कर देना हैं।</div>
                </li>
                <li>
                    <div class="question"><b>बैनामा कितने वर्ष के लिये मान्य है?</b></div>
                    <div class="answer">बैनामा कम से कम 12 वर्ष के लिये मान्य है |</div>
                </li>
                <li>
                    <div class="question"><b>रजिस्ट्री कैंसिल कैसे होती है?</b></div>
                    <div class="answer">यदि आपके नाम से जमीन है और उस जमीन को अन्य कोई व्यक्ति फर्जी कागजात तैयार करके जमीन को बेच देता है या धोखाधड़ी से रजिस्ट्री करता है पहले से ही जो जमीन आपके नाम पर है तो ऐसी स्थिति में रजिस्ट्री को कैंसिल करवाया जा सकता है। </div>
                </li>
                <li>
                    <div class="question"><b>क्या बैनामा खारिज हो सकता है?</b></div>
                    <div class="answer">जी हाँ, नए नियमो के आधार पर फर्जी बैनामा निरस्त कराने के लिए व्यक्ति को रजिस्ट्री कार्यालय में बैठने वाले सहायक महानिरीक्षक निबंधन के कार्यालय में शिकायत दर्ज करवानी होगी और फिर महानिरीक्षक निबंधन बैनामा करने वालों को नोटिस जारी कर अपना पक्ष रखने का को तलब करेंगे |</div>
                </li>
                <li>
                    <div class="question"><b>दाखिल खारिज ऑनलाइन बिहार स्टेटस कैसे देखे?</b></div>
                    <div class="answer">दाखिल खारिज के ऑनलाइन स्टेटस देखने के लिए सबसे पहले आपको बिहार भूमि एवं राजस्व विभाग की आधिकारिक वेबसाइट पर जाना है | यहां पर आपको दाखिल खारिज आवेदन स्थिति पर जाना है, इसके बाद एक न्यू पेज ओपन होगा जहां पर आपको सारी जानकारी दे देनी तब आपके सामने दाखिल खारिज स्टेटस प्रदर्शित हो जाएगा |</div>
                </li>
                <li>
                    <div class="question"><b>दाखिल खारिज कैसे देखें बिहार?</b></div>
                    <div class="answer">ऑनलाइन दाखिल खारिज देखने के लिए आपको बिहार भूमि एवं राजस्व विभाग की ऑफिशल पोर्टल पर जाना होगा वहां पर जाकर आप अपने दाखिल खारिज के बारे में जानकारी प्राप्त कर सकते हैं |</div>
                </li>
                <li>
                    <div class="question"><b>दाखिल खारिज कराने में कितना पैसा लगता है?</b></div>
                    <div class="answer">बिहार भूमि एवं राजस्व विभाग के दफ्तर में दाखिल खारिज के लिए आवेदन करते हैं तो सर्किल अफसर के द्वारा दाखिल ख़ारिज बिहार की फीस बीस रूपये लेकर खाता पुस्तिका देने का प्रावधान है।</div>
                </li>
                <li>
                    <div class="question"><b>जमीन रजिस्ट्री के कितने दिन बाद दाखिल खारिज होता है?</b></div>
                    <div class="answer">जमीन रजिस्ट्री के 45 दिन बाद दाखिल खारिज होता है |</div>
                </li>

            </ol>
        </div>
        <div id="basic" class="modal fade" aria-hidden="true">
            <div class="modal-dialog model-md">
                <div class="modal-content">
                    <div class="modal-header">Quick Apply
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mutation">
                            <form action="" method="post" enctype="multipart/form-data">
                                <div class="form-container">
                                    <h2>Mutation Application Form</h2>
                                    <form id="mutationForm">
                                        <!-- Name Field -->
                                        <div class="form-group">
                                            <label for="name">Deed Number</label>
                                            <input type="text" name="form[deed_no]" placeholder="Enter Deed Number" required>

                                            <p>Scan Your Deed Registry Paper and create a PDF file and upload it.Get Mutation receipt sent to your email & whatsapp within 72 Hours.</p>
                                        </div>
                                        <div class="form-group">
                                            <label>Add E-Mail Address</label>
                                            <input type="text" name="form[email_id]" placeholder="E-Mail Address" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Add WhatsaApp Number</label>
                                            <input type="number" name="form[whatsapp]" placeholder="WhatsaApp Number" required>
                                        </div>
                                        <div class="form-group mt-1">
                                            <select name="form[years]" required>
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
                                                <input type="file" name="documents" id="fileInput" accept=".pdf" onchange="showFileName()" required\>
                                            </label>

                                        </div>
                                        <div action="/action_page.php">
                                            <input type="checkbox" id="vehicle1" name="iaccept" value="Accept" required>
                                            <label for="vehicle1">Terms & condition </label><br>
                                        </div>

                                        <?php
                                        if (is_login()) {
                                        ?>
                                            <!-- Submit Button -->
                                            <div class="form-group mt-2">
                                                <input type="hidden" name="mutation" value="1">
                                                <button type="submit" class="submit-button">Submit</button>
                                            </div>
                                        <?php
                                        } else {
                                        ?>
                                            <div class="alert alert-danger p-2">You must be login to Apply for Mutations.</div>
                                        <?php
                                        }
                                        ?>


                                    </form>
                                </div>

                                <div class="footer">
                                    <p>For support, contact us at <a href="mailto:support@biharbhumiseva.in">support@biharbhumiseva.in</a></p>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('.question').click(function() {
                $(this).parent().find('.answer').slideToggle();
            })
        });
    </script>
</div>
<?php
include "common/footer.php";
?>