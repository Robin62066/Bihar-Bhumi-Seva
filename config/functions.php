<?php
function theme_url($file = '')
{
    return base_url('assets/front/' . $file);
}

function base_url($file = '')
{
    global $config;
    $url = $config['base_url'];
    return $url . $file;
}

function site_url($file = '')
{
    global $config;
    $url = $config['base_url'];
    return $url . $file;
}

function image_not_found()
{
    return site_url('assets/front/img/default.png');
}

function admin_url($file = '')
{
    global $config;
    $url = $config['base_url'];
    return $url . $config['admin_folder'] . '/' . $file;
}

function redirect($url, $msg = '', $msg_type = 'info')
{
    if ($msg != '') {
        $key = $msg_type . "_msg";
        set_flashdata($key, $msg);
    }
    header('location: ' . $url);
    die;
}

function appload()
{
    include ROOT_PATH . '/config/autoload.php';
}

function upload_dir($file = '')
{
    global $config;
    $url = $config['upload_folder'] . DIRECTORY_SEPARATOR;
    return $url . $file;
}

function view($file, $data = [])
{
    extract($data);
    ob_start();
    $file = rtrim($file, '.php') . '.php';
    include("views/$file");
    $resp = ob_get_clean();
    return $resp;
}
function front_view($file, $data = [])
{
    extract($data);
    ob_start();
    $file = rtrim($file, '.php') . '.php';
    include(ROOT_PATH . $file);
    $resp = ob_get_clean();
    return $resp;
}

function do_upload(string $name, bool $crop = false)
{
    $file_name = '';
    if (isset($_FILES[$name]['name']) && $_FILES[$name]['name'] != '') {
        $extension = pathinfo($_FILES[$name]['name'], PATHINFO_EXTENSION);
        $chkFolder = ROOT_PATH . upload_dir(date("Y/m/"));
        if (!is_dir($chkFolder)) {
            mkdir($chkFolder);
        }
        $file_name = date("Y/m/") . time() . '-' . bin2hex(random_bytes(10));
        $newfilename = $file_name . '.' . $extension;
        $thumbfile  =  $file_name . "_thumb." . $extension;
        $target_path =  ROOT_PATH . upload_dir($newfilename);
        move_uploaded_file($_FILES[$name]['tmp_name'], $target_path);

        if ($crop) {
            // Resize image
            $destinationPath =  ROOT_PATH . upload_dir($thumbfile);
            resizeImageUpdated($target_path, $destinationPath, 300, 400);
            $newfilename = $thumbfile;
        }
    }
    return $newfilename;
}

function input_post($name)
{
    return isset($_POST[$name]) ? $_POST[$name] : null;
}

function input_get($name)
{
    return isset($_GET[$name]) ? $_GET[$name] : null;
}


function set_value($name, $default_value = '')
{
    if (is_array($name)) {
        echo 'array';
    }
    print_r($_POST['form[aadhar_number]']);
    return isset($_POST[$name]) ?? '1234';
}



function sendSMS($name, $otp, $mobile, $action = 'signup')
{
    $signup_msg = "Dear $name, Mobile Verification OTP with BIHARI BHUMI SEVA CONSULTANCY PRIVATE LIMITED is $otp Please don't share with any other.";

    $forget_msg = "Dear $name, Your password for login with BIHARI BHUMI SEVA CONSULTANCY PRIVATE LIMITED is $otp. Kindly change your password to keep your account secure.";

    $createdMsg = "Dear $name, Welcome to BIHARI BHUMI SEVA CONSULTANCY PRIVATE LIMITED. Your Account has been created with username $mobile and password $otp. Kindly login to continue.";

    $templateId = 0;
    $msg = null;
    if ($action  == 'signup') {
        $templateId = '1207170262446664069';
        $msg = $signup_msg;
    } else if ($action == 'usermsg') {
        $templateId = '1207171230540172073';
        $msg = $createdMsg;
    } else {
        $templateId = '1207170262022493050';
        $msg = $forget_msg;
    }
    $msg = urlencode($msg);

    $url = "https://www.smsgatewayhub.com/api/mt/SendSMS?APIKey=RXY5qjzHr0GzfB5BxV0L3A&senderid=BBSCPT&channel=2&DCS=0&flashsms=0&number=91$mobile&text=$msg&route=31&EntityId=1201170184808947472&dlttemplateid=$templateId";
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    $resp = curl_exec($ch);
    curl_close($ch);
    return $resp;
}

function resize_image($filename, $newwidth = 160, $newheight = 160)
{
    // Content type
    header('Content-Type: image/jpeg');

    // Get new sizes
    list($width, $height) = getimagesize($filename);

    // Load
    $thumb = imagecreatetruecolor($newwidth, $newheight);
    $source = imagecreatefromjpeg($filename);

    // Resize
    imagecopyresized($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

    // Output
    imagejpeg($thumb);
}

function resizeImageUpdated($sourcePath, $destinationPath, $newWidth, $newHeight)
{
    // Get original image dimensions and type
    list($width, $height, $type) = getimagesize($sourcePath);

    // Calculate aspect ratio
    $aspectRatio = $width / $height;
    if ($newWidth / $newHeight > $aspectRatio) {
        $newWidth = $newHeight * $aspectRatio;
    } else {
        $newHeight = $newWidth / $aspectRatio;
    }

    // Create a blank canvas for the resized image
    $resizedImage = imagecreatetruecolor($newWidth, $newHeight);

    // Create image resource from source file
    switch ($type) {
        case IMAGETYPE_JPEG:
            $sourceImage = imagecreatefromjpeg($sourcePath);
            break;
        case IMAGETYPE_PNG:
            $sourceImage = imagecreatefrompng($sourcePath);
            // Preserve transparency for PNG
            imagealphablending($resizedImage, false);
            imagesavealpha($resizedImage, true);
            break;
        case IMAGETYPE_GIF:
            $sourceImage = imagecreatefromgif($sourcePath);
            break;
        default:
            throw new Exception("Unsupported image type");
    }

    // Resize the image
    imagecopyresampled($resizedImage, $sourceImage, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

    // Save the resized image to the destination path
    switch ($type) {
        case IMAGETYPE_JPEG:
            imagejpeg($resizedImage, $destinationPath, 90); // Quality 90
            break;
        case IMAGETYPE_PNG:
            imagepng($resizedImage, $destinationPath);
            break;
        case IMAGETYPE_GIF:
            imagegif($resizedImage, $destinationPath);
            break;
    }

    // Free memory
    imagedestroy($sourceImage);
    imagedestroy($resizedImage);

    return true;
}
