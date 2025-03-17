<?php
include_once "config/autoload.php";

$api = new RestApi();
$db = db_connect();
$eko  = new Eko();

$m = isset($_GET['m']) ? $_GET['m'] : '';
$data = json_decode(file_get_contents("php://input"), true);
if ($data == null) $data = [];

switch ($m) {
    case 'bhumi-login':
        if ($api->checkINPUT(['user_id', 'pin'], $data)) {
            $user_id    = $data['user_id'];
            $access_key = md5($data['pin']);
            $user = $db->select("ai_bhumi_login", ['user_id' => $user_id, 'access_key' => $access_key], 1)->row();
            if (is_object($user) && $user->status == 1) {
                $api->setOK('Login Success',);
                session()->set('bhumilogin', $user);
            } else if (is_object($user) && $user->status != 1) {
                $api->setError("Your Account has been disabled");
                $api->setData($user);
            } else {
                $api->setError("Invalid Login PIN. Try again");
            }
        }
        break;
    case 'search-mutation':
        if ($api->checkINPUT(['search_by'], $data)) {
            $search_by = $data['search_by'];
            $item = null;
            if ($search_by == 'case_no') {
                $item = $db->select('ai_mutations_app', ['case_no' => $data['case_no']], 1)->row();
            } else if ($search_by == 'deed_no') {
                $item = $db->select('ai_mutations_app', ['deed_no' => $data['deed_no']], 1)->row();
            }
            $sql = $db->sql();

            if ($item) {
                if ($item->attachment != null) {
                    $item->attachment = base_url(upload_dir($item->attachment));
                } else {
                    $item->attachment = '';
                }
                $api->setData($item);
                $api->setOK('Success' . $sql);
            } else {
                $api->setError("No Record Found");
            }
        }
        break;
    case 'user-login':
        if ($api->checkINPUT(['mobile', 'password'], $data)) {
            $mobile = $data['mobile'];
            $password = $data['password'];
            $user = $db->select('ai_users', ['mobile_number' => $mobile, 'passwd' => $password], 1)->row();

            if (is_object($user)) {
                $type = $user->user_type;
                set_userdata('user', $user);
                $api->setOK("Login success, Redirecting.");
            } else {
                $api->setError("Mobile number and Password is invalid");
            }
        }
        break;
    case 'user-signup':

        if ($api->checkINPUT(['first_name', 'last_name', 'email_id', 'mobile', 'passwd'], $data)) {
            extract($data);

            // Check duplicate mobile
            $check_resp = $db->select('ai_users', ['mobile_number' => $mobile], 1);
            if ($check_resp->count() > 0) {
                $api->setError('Mobile already registered. Use different details.');
                break;
            }

            $user = [
                'id' => time(),
                'first_name'    => $first_name,
                'last_name'     => $last_name,
                'user_type'     => 1,
                'email_id'      => $email_id,
                'created'       => date("Y-m-d H:i:s"),
                'status'        => 1,
                'kyc_status'    => KYC_PENDING,
                'mobile_otp'    => $otp,
                'mobile_verified' => 1,
                'mobile_number' => $mobile,
                'passwd'        => $passwd,
                'mobile_otp'    => '1234'
            ];
            $db->insert('ai_users', $user);
            $id = $db->id();
            $user = $db->select('ai_users', ['id' => $id], 1)->row();
            $api->setData($user);
            $api->setOK("Account created successfully. Redirecting");
            set_userdata('user', $user);
        }
        break;
    case 'get-signup-otp':
        if ($api->checkINPUT(['mobile'], $data)) {
            $mobile = $data['mobile'];
            $name = $data['first_name'] ?? 'Guest';
            $item = $db->select('ai_users', ['mobile_number' => $mobile], 1, false, 'AND', 'id')->row();
            if (is_object($item)) {
                $api->setError("Mobile number already registered.");
            } else {
                $otp = rand(1111, 9999);
                sendSMS($name, $otp, $mobile);
                $api->setOK("OTP Sent on your mobile $mobile");
                $api->setData(['otp' => $otp]);
            }
        }
        break;
    case 'get-otp':
        if ($api->checkINPUT(['mobile'], $data)) {
            $mobile = $data['mobile'];
            $item = $db->select('ai_users', ['mobile_number' => $mobile], 1, false, 'AND', 'id, first_name, last_name, mobile_number, email_id, status, created')->row();

            if (is_object($item)) {
                if ($item->status == 1) {
                    $otp = rand(1111, 9999);
                    $sb = [];
                    $sb['mobile_otp'] = $otp;
                    $sb['otp_type'] = 'login';
                    $sb['otp_at'] = date("Y-m-d H:i:s");
                    $db->update('ai_users', $sb, ['id' => $item->id]);
                    sendSMS($item->first_name, $otp, $mobile, 'signup');
                    $api->setOK('OTP sent successfully');
                } else {
                    $api->setError('Account is not active. Contact admin.');
                }
            } else {
                $api->setError('Mobile number not registered');
            }
        }
        break;
    case 'verify-otp':
        if ($api->checkINPUT(['mobile', 'otp'], $data)) {
            $mobile = $data['mobile'];
            $otp    = $data['otp'];
            $us = $db->select('ai_users', ['mobile_number' => $mobile], 1)->row();
            if (is_object($us)) {
                if ($us->mobile_otp == $otp) {
                    $api->setOK("Login Succesful.");
                    set_userdata('user', $us);
                } else {
                    $api->setError("Validation failed. OTP not matching.");
                }
            } else {
                $api->setError("Verification failed. Account does not exists");
            }
        }
        break;
    case 'send-password':
        if ($api->checkINPUT(['name', 'mobile', 'passwd'], $data)) {
            $name = $data['name'];
            $mobile = $data['mobile'];
            $passwd = $data['passwd'];
            sendSMS($name, $passwd, $mobile, 'usermsg');
            $api->setOK("Password sent successfully");
        }
        break;
    case 'add-mutations':
        if ($api->checkINPUT(['fname', 'guardian', 'relation', 'case_year', 'village', 'address', 'state_id', 'dist_id', 'pincode', 'lat', 'lng'], $data)) {
            if ($data['fname'] == '' || $data['guardian'] == '' || $data['case_year'] == '' || $data['village'] == '' || $data['address'] == '' || $data['state_id'] == '' || $data['dist_id'] == '' || $data['pincode'] == '') {
                $api->setError("Please fill all details");
                break;
            }
            $sb = $data;
            $sb['step'] = 1;
            $sb['created'] = date("Y-m-d H:i:s");
            $sb['updated'] = date("Y-m-d H:i:s");
            $sb['step'] = 1;
            $sb['ip_address'] = $_SERVER['SERVER_ADDR'];
            $sb['amount'] = 500;
            $sb['order_id'] = 0;
            $sb['pay_status'] = 0;
            $sb['pay_date'] = null;

            $db->insert("ai_mutations", $sb);
            $id = $db->id();

            $item = new stdClass();
            $item->id = $id;
            $item->step = 2;
            $api->setData($item);
            session()->set('app_id', $id);
            $api->setOK("Details Saved Successfully.");
            session()->set_flashdata('info', 'Application details saved successfully');
        }
        break;
    case 'update-mutations-step2':
        if ($api->checkINPUT(['app_id', 'doc_type'], $data)) {
            if ($data['doc_type'] == '') {
                $api->setError("Please select document type");
                break;
            }
            $app_id = session()->app_id;
            $doc_type = $data['doc_type'];
            $item_str = $data['inputs'];
            if ($item_str == '') $item_str = '[]';
            add_mutation_data($app_id, 'doc_type', $doc_type);
            add_mutation_data($app_id, 'doc_details', $item_str);

            $db->update('ai_mutations', ['step' => 2], ['id' => $app_id]);
            $order = new stdClass();
            $order->step = 3;
            $order->app_id = $app_id;
            $api->setData($order);
            $api->setOK("Document details updated successfully");
            session()->set_flashdata('info', 'Document details updated successfully');
        }
        break;
    case 'update-mutations-step3':
        if ($api->checkINPUT(['app_id', 'inputs'], $data)) {
            $app_id = session()->app_id;
            $item_str = $data['inputs'];
            if ($item_str == '') $item_str = '[]';
            add_mutation_data($app_id, 'buyer_details', $item_str);
            $db->update('ai_mutations', ['step' => 3], ['id' => $app_id]);

            $order = new stdClass();
            $order->step = 4;
            $order->app_id = $app_id;
            $api->setData($order);
            $api->setOK("Buyer details updated successfully");
            session()->set_flashdata('info', 'Buyer details updated successfully');
        }
        break;
    case 'update-mutations-step4':
        if ($api->checkINPUT(['app_id', 'inputs'], $data)) {
            $app_id = session()->app_id;
            $item_str = $data['inputs'];
            if ($item_str == '') $item_str = '[]';
            add_mutation_data($app_id, 'seller_details', $item_str);
            $db->update('ai_mutations', ['step' => 4], ['id' => $app_id]);

            $order = new stdClass();
            $order->step = 5;
            $order->app_id = $app_id;
            $api->setData($order);
            $api->setOK("Seller details updated successfully");
            session()->set_flashdata('info', 'Seller details updated successfully');
        }
        break;
    case 'update-mutations-step5':
        if ($api->checkINPUT(['app_id', 'dist_id', 'circle', 'sub_division', 'mauja', 'halka', 'thana'], $data)) {

            $app_id = $data['app_id'];
            foreach ($data as $key => $value) {
                if ($key == 'app_id') continue;
                add_mutation_data($app_id, $key, $value);
            }
            $db->update('ai_mutations', ['step' => 5], ['id' => $app_id]);

            $order = new stdClass();
            $order->step = 6;
            $order->app_id = $app_id;
            $api->setData($order);
            $api->setOK("Plot details updated successfully");
            session()->set_flashdata('info', 'Plot details updated successfully');
        }
        break;
    case 'add-wishlist':
        if ($api->checkINPUT(['pid'], $data)) {
            $pid = $data['pid'];
            if (is_login()) {
                $chk = $db->select("ai_wishlist", ['pid' => $pid, 'user_id' => user_id()], 1)->row();
                if ($chk == null) {
                    $sb = [];
                    $sb['pid']      = $pid;
                    $sb['user_id']  = user_id();
                    $sb['created']  = date("Y-m-d H:i:s");

                    $db->insert("ai_wishlist", $sb);
                    $api->setOK("Property Item added to your wishlist.");
                } else {
                    $api->setError("Item already in your wishlist");
                }
            } else {
                $api->setError("You must login to add into wishlist");
            }
        }
        break;
    case 'remove-wishlist':
        if ($api->checkINPUT(['pid'], $data)) {
            $pid = $data['pid'];
            if (is_login()) {
                $chk = $db->select("ai_wishlist", ['pid' => $pid, 'user_id' => user_id()], 1)->row();
                if (is_object($chk)) {
                    $db->delete("ai_wishlist", ['id' => $chk->id]);
                    $api->setOK("Property removed from your wishlist.");
                } else {
                    $api->setError("Item not in your wishlist");
                }
            } else {
                $api->setError("You must login to add into wishlist");
            }
        }
        break;
    case 'send-sms-enquiry':
        if ($api->checkINPUT(['pid'], $data)) {
            $pid = $data['pid'];
            if (is_login()) {
                $api->setOK("API not linked. We will active it soon.");
            } else {
                $api->setError("You must login to send sms");
            }
        }
        break;
    case 'site-update':
        if ($api->checkINPUT(['prop_id', 'field_name', 'field_value'], $data)) {
            $id = $data['prop_id'];
            $sb = [];
            $sb[$data['field_name']] = $data['field_value'];
            $db->update('ai_sites', $sb, ['id' => $id]);
            $api->setOK("Details updated");
            $api->setData($data['field_name']);
            set_flashdata('success', "Details updated");
        }
        break;
    case 'reset-userid':
        if ($api->checkINPUT(['pan_no'], $data)) {
            $pan_no = strtoupper($data['pan_no']);
            $us = $db->select('ai_users', ['pan_number' => $pan_no], 1)->row();
            if (is_object($us)) {
                sendSMS($us->first_name, $us->passwd, $us->mobile_number, "forget-password");
                $api->setOK("Your mobile no is: " . $us->mobile_number . ', Password sent on your mobile.');
            } else {
                $api->setError("PAN Number does not exists.");
            }
        }
        break;
    case 'reset-password':
        if ($api->checkINPUT(['mobile'], $data)) {
            $mobile = $data['mobile'];
            $us = $db->select('ai_users', ['mobile_number' => $mobile], 1)->row();
            if (is_object($us)) {
                sendSMS($us->first_name, $us->passwd, $mobile, "forget-password");
                $api->setOK("Password sent on your mobile no");
            } else {
                $api->setError("Mobile no does not exists.");
            }
        }
        break;
    case 'userinfo':
        if ($api->checkINPUT(['user_id'], $data)) {
            $user_id  = $data['user_id'];
            $user = $db->select('ai_users', ['id' => $user_id], 1)->row();
            $api->setOK();
            $api->setData($user);
        }
        break;
    case 'get-pan-details':
        if ($api->checkINPUT(['user_id', 'pan'], $data)) {
            $user_id = $data['user_id'];
            $pan     = strtoupper($data['pan']);

            $chkIfUserExists = $db->select('ai_users', ['pan_number' => $pan], 1)->row();
            if (is_object($chkIfUserExists)) {
                $api->setError("PAN Already registered with us.");
                break;
            }

            $resp = $eko->verify_pan($pan);
            if ($resp->response_status_id == -1 && $resp->status == 0) {
                $resp_data = $resp->data;
                $api->setData($resp_data);
                $api->setOK('Please verify your PAN Details');
            } else {
                $api->setError("Error: " . $resp->message);
            }
        }
        break;
    case 'pan-verify':
        $api->setData($data);
        if ($api->checkINPUT(['user_id', 'pan_number', 'first_name', 'last_name', 'user_type'], $data)) {
            $user_id = $data['user_id'];

            $sb = [];
            $sb['user_type']    = $data['user_type'];
            $sb['first_name']   = $data['first_name'];
            $sb['middle_name']  = $data['middle_name'];
            $sb['last_name']    = $data['last_name'];
            $sb['pan_verified'] = 1;
            $sb['pan_number']   = $data['pan_number'];
            $sb['pan_name']     = $data['first_name'] . ' ' . $data['middle_name'] . ' ' . $data['last_name'];
            $db->update("ai_users", $sb, ['id' => $user_id]);
            $api->setOK('PAN Details updated successfully');
        }
        break;

    case 'aadhar-otp':
        if ($api->checkINPUT(['aadhar_name', 'aadhar_no'], $data)) {
            $aadhar_name = strtoupper($data['aadhar_name']);
            $aadhar_no = $data['aadhar_no'];

            $resp = $eko->aadharInit($aadhar_no, $aadhar_name);
            if ($resp->response_status_id == 0 && $resp->status == 0) {
                $access_key = $resp->data->access_key;
                $resp = $eko->getAadharOTP($aadhar_no, $access_key);
                if ($resp->response_status_id == 0 || $resp->status == 0) {
                    $msg = $resp->data->message;
                    $api->setOK($msg);
                    $api->setData($access_key);
                } else {
                    $api->setError($resp->message);
                }
            } else {
                $api->setError("Error: " . $resp->message);
            }
        }
        break;

    case 'verify-aadhar-otp':
        if ($api->checkINPUT(['aadhar_no', 'access_key', 'otp'], $data)) {
            $aadhar_no = $data['aadhar_no'];
            $access_key = $data['access_key'];
            $otp        = $data['otp'];
            $resp = $eko->getAadharDetails($aadhar_no, $access_key, $otp);
            if ($resp->response_status_id == 0 || $resp->status == 0) {
                $resp_data = $resp->data;
                $api->setOK();
                $api->setData($resp_data);
            } else {
                $api->setError($resp->message);
            }
        }
        break;

    case 'upadate-aadhar-info':
        if ($api->checkINPUT(['user_id', 'aadhar_name', 'gender', 'dob', 'address'], $data)) {
            $user_id = $data['user_id'];

            $sb = [];
            $sb['aadhar_name'] = $data['aadhar_name'];
            $sb['address'] = $data['address'];
            $sb['gender'] = $data['gender'];
            $sb['dob'] =    $data['dob'];
            $sb['aadhar_verified'] = 1;
            $sb['father_name'] = $data['father_name'];
            $sb['aadhar_number'] = $data['maskedAadhaarNumber'];
            $sb['kyc_status'] = 1;
            $db->update('ai_users', $sb, ['id' => $user_id]);
            $api->setOK("Aadhar Verified Successfully");
        }
        break;

    case 'districts':
        if ($api->checkINPUT(['state_id'], $data)) {
            $state_id = $data['state_id'];
            $zones = $db->select('ai_districts', ['state_id' => $state_id], false, 'dist_name ASC')->result();
            $api->setOK();
            $api->setData($zones);
        }
        break;

    case 'zones':
        if ($api->checkINPUT(['dist_id'], $data)) {
            $dist_id = $data['dist_id'];
            $zones = $db->select('ai_zones', ['dist_id' => $dist_id], false, 'zone_name ASC')->result();
            $api->setOK();
            $api->setData($zones);
        }
        break;
    case 'save-proceed':
        if (!is_login()) {
            $api->setError('You must login to continue');
        } else {
            if ($api->checkINPUT(['dist_id', 'zone_id', 'membership'], $data)) {
                $dist_id = $data['dist_id'];
                $zone_id = $data['zone_id'];
                $user_id = user_id();

                $us = $db->select('ai_users', ['id' => $user_id], 1)->row();


                $sb = [];
                $sb['dist_id'] = $dist_id;
                $sb['zone_id'] = $zone_id;
                $sb['user_id'] = $user_id;
                $sb['lat'] = $data['lat'];
                $sb['lng'] = $data['lng'];
                $sb['property_for'] = 'Sell';
                $sb['status']  = -1;
                $sb['owner_id'] = $us->user_type == USER_LAND_OWNER ? $user_id : 0;
                $sb['broker_id'] = $us->user_type == USER_BROKER ? $user_id : 0;
                $sb['munsi_id'] = $us->user_type == USER_MUNSI ? $user_id : 0;
                $sb['membership'] = $data['membership'];
                $sb['created'] = date("Y-m-d H:i:s");

                $db->insert('ai_sites', $sb);
                $lastid = $db->id();
                $api->setOK('Property Added, Please continue to fill more details.');
                $api->setData($lastid);
            }
        }
        break;
    case 'admin-users':
        if (is_admin_login()) {
            $users = $db->select('ai_admin')->result();
            $api->setOK();
            $api->setData($users);
        } else {
            $api->setError("You must login to view");
        }
        break;
    case 'save-admin-user':
        if (is_admin_login()) {
            if ($api->checkINPUT(['username', 'password', 'role'], $data)) {

                $sb = [];
                $sb['username'] = $data['username'];
                $sb['first_name'] = $data['first_name'];
                $sb['email_id'] = $data['email_id'];
                $sb['password'] = $data['password'];
                $sb['role']     = $data['role'];
                $sb['status']   = $data['status'];

                $action = $data['action'];
                if ($action == 'add') {
                    $username = $data['username'];
                    $us = $db->select('ai_admin', ['username' => $username], 1)->row();
                    if (is_object($us)) {
                        $api->setError("Username already exists.");
                        break;
                    }
                    $sb['permissions'] = $data['permissions'];
                    $db->insert("ai_admin", $sb);
                    $api->setOK("New admin account created");
                } else {
                    $db->update("ai_admin", $sb, ['id' => $data['id']]);
                    $api->setOK("Admin account updated");
                }
            }
        } else {
            $api->setError("Access not allowed");
        }
        break;
    case 'del-admin-user':
        if (is_admin_login()) {
            if ($api->checkINPUT(['id'], $data)) {
                $id = $data['id'];
                if ($id == 1) {
                    $api->setError("You cannot delete Master Admin");
                } else {
                    $db->delete('ai_admin', ['id' => $id]);
                    $api->setOK("Admin Deleted Successfully");
                }
            }
        } else {
            $api->setError("Access not allowed");
        }
        break;
    case 'update-permission':
        if (is_admin_login()) {
            if ($api->checkINPUT(['id', 'permissions'], $data)) {
                $id = $data['id'];
                $permission = $data['permissions'];
                $db->update('ai_admin', ['permissions' => $permission], ['id' => $id]);
                $api->setOK("Permission Updated");
            }
        } else {
            $api->setError("Access not allowed");
        }
        break;
    case 'get-leads-otp':
        if ($api->checkINPUT(['mobile'], $data)) {
            $mobile = $data['mobile'];
            $item = $db->select('ai_users', ['mobile_number' => $mobile], 1, false, 'AND', 'id, first_name, last_name, mobile_number, email_id, status, created')->row();
            if (is_object($item)) {
                if ($item->status == 1) {
                    $otp = rand(1111, 9999);
                    $sb = [];
                    $sb['mobile_otp'] = $otp;
                    $sb['otp_type'] = 'leads';
                    $sb['otp_at'] = date("Y-m-d H:i:s");
                    $db->update('ai_users', $sb, ['id' => $item->id]);
                    sendSMS($item->first_name, $otp, $mobile);
                    $api->setData(['otp' => $otp, 'mobile' => $mobile, 'name' => $item->first_name]);
                    $api->setOK('OTP sent successfully');
                } else {
                    $api->setError('Account is not active. Contact admin.');
                }
            } else {
                $otp = rand(1111, 9999);
                $user = [
                    'id' => time(),
                    'first_name'    => 'Guest',
                    'user_type'     => 'Guest',
                    'created'       => date("Y-m-d H:i:s"),
                    'status'        => 1,
                    'kyc_status'    => KYC_PENDING,
                    'mobile_otp'    => $otp,
                    'mobile_verified' => 1,
                    'mobile_number' => $mobile
                ];
                $db->insert('ai_users', $user);
                $id = $db->id();
                $user = $db->select('ai_users', ['id' => $id], 1)->row();
                sendSMS($user->first_name, $otp, $mobile);
                $api->setOK("OTP Sent on your mobile $mobile");
                $api->setData(['otp' => $otp, 'mobile' => $mobile, 'name' => $user->first_name]);
            }
        }
        break;
}

$api->render();
