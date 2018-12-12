<?php

function convertToSEO($deger) {
    $turkce=array("ş","Ş","ı","(",")","'","&#39;"," - ","ü","Ü","ö","Ö","ç","Ç","!"," ","/","*","?","ş","Ş","ı","ğ","Ğ","İ","ö","Ö","Ç","ç","ü","Ü");
    $duzgun=array("s","s","i","","","-","-","-","u","u","o","o","c","c","","-","-","-","","s","s","i","g","g","i","o","o","c","c","u","u");
    $deger= str_replace($turkce,$duzgun,trim($deger));
    $deger = preg_replace("@[^a-z0-9\-_]+@i","",$deger);
    return mb_convert_case($deger,MB_CASE_LOWER);
}

function get_readable_date($date)
{
    setlocale(LC_ALL, "tr_TR.UTF-8");
    return strftime('%d %B %Y %A %H:%M', strtotime($date));
}

function rrmdir($src)
{
    $dir = opendir($src);
    while (false !== ($file = readdir($dir))) {
        if (($file != '.') && ($file != '..')) {
            $full = $src . '/' . $file;
            if (is_dir($full)) {
                rrmdir($full);
            } else {
                unlink($full);
            }
        }
    }
    closedir($dir);
    rmdir($src);

    return true;
}

function get_active_user()
{
    $t = &get_instance();

    $user = $t->session->userdata("user");

    if ($user)
        return $user;
    else
        return false;
}

function get_username($id)
{
    $t = &get_instance();

    $t->load->model("user_model");

    $user = $t->user_model->get(
        array(
            "id"    => $id
        )
    );

    return($user->full_name);
}

function get_departmentName($id)
{
    $t = &get_instance();

    $t->load->model("department_model");

    $department = $t->department_model->get(
        array(
            "id"    => $id
        )
    );

    return($department->title);
}

function get_userRoleName($id)
{
    $t = &get_instance();

    $t->load->model("user_role_model");

    $department = $t->user_role_model->get(
        array(
            "id"        => $id,
            "isActive"  => 1
        )
    );

    return($department->title);
}

function send_email($toEmail = "", $subject = "", $message = "")
{
    $t = get_instance();

    $t->load->model("email_model");

    $email_settings = $t->email_model->get(
        array(
            "isActive" => 1
        )
    );

    $config = array(
        "protocol" => $email_settings->protocol,
        "smtp_host" => $email_settings->host,
        "smtp_port" => $email_settings->port,
        "smtp_user" => $email_settings->user,
        "smtp_pass" => $email_settings->password,
        "starttls" => true,
        "charset" => "utf-8",
        "mailtype" => "html",
        "wordwrap" => true,
        "newline" => "\r\n",
    );

    $t->load->library("email", $config);

    $t->email->from($email_settings->from, $email_settings->user_name);
    $t->email->to($toEmail);
    $t->email->subject($subject);
    $t->email->message($message);

    return $t->email->send();
}

?>