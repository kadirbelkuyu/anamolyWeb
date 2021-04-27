<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Mailbox extends MY_Controller
{
    protected $controller;
    public function __construct()
    {
        parent::__construct();
        $this->controller = "admin/".$this->router->fetch_class();
        if (!_is_admin() && !_is_sub_admin("admin/mailbox")) {
            redirect("login");
            exit();
        }

        $this->load->library("mailboxlib");
    }
    function validate_emails($str)
    {
        $emails = explode(",",$str);
        foreach($emails as $email){
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return false;
            }
        }
        return true;
    }
    function delete($id){
        $this->mailboxlib->deleteEmail($id);
    }
    function send(){
        header('Content-Type: application/json');
        $responce = array();

        $post = $this->input->post();
        $this->load->library('form_validation');
        $this->form_validation->set_rules('subject', _l("Subject"),'trim|required');
        $this->form_validation->set_rules('body', _l("Body"),'trim|required');
        $this->form_validation->set_rules('to', '"Email address"', 'trim|required|callback_validate_emails');

        if ($this->form_validation->run() == false) {
            if ($this->form_validation->error_string() != "") {
               $responce["response"] = false;
               $responce["message"] = _error_message($this->form_validation->error_string(),"error");
            }
        } else {
            $post = $this->input->post();
            $emails = explode(",",$post["to"]);
            $subject = $post["subject"];
            $message = $post["body"];

            $upload = $this->do_upload();
            if ($upload["response"]) {
                $options = get_options(array("email_sender","sendgrid_key","name"));

                $config['protocol'] = "smtp";
                $config['smtp_crypto'] = "ssl";
                $config['smtp_host'] = "mailanamoly.se";
                $config['smtp_port'] = "465";
                $config['smtp_user'] = "noreply@anamoly.se";  // First Less secure apps enable on security your email : https://myaccount.google.com/lesssecureapps?pli=1
                $config['smtp_pass'] = "Mz#O^F0mbnG!njGe";
                $config['charset'] = "utf-8";
                $config['mailtype'] = "html";
                $config['newline'] = "\r\n";
                $config['wordwrap'] = TRUE;
                $this->email->initialize($config);

                $this->email->from("info@anamoly.se", $options["name"]);
                $this->email->to($emails);
                $this->email->subject($subject);
                $this->email->message($message);
                $attachmets = $upload["data"];
                foreach($attachmets  as $attachment){
                    $this->email->attach("./uploads/mails/".$attachment["file_name"],"attachment",$attachment["orig_name"]);
                }
                $res = $this->email->send();
                if ($res) {
                    $insert = array(
                        "uid" => strtotime(date("Y-m-d H:i:s")),
                        "date_timestamp" => strtotime(date("Y-m-d H:i:s")),
                        "mail_subject" => $subject,
                        "mail_from" => $options["email_sender"],
                        "mail_to" => $post["to"],
                        "mail_body" => $message,
                        "mail_type" => "sent",
                        "has_attachment" => (empty($attachment)) ? "0" : "1"
                    );
                    $mail_id = $this->common_model->data_insert("mailbox",$insert,true);
                    foreach ($attachmets  as $attachment) {
                        $insert_attachment = array(
                            "mail_id"=>$mail_id,
                            "filename"=>$attachment["file_name"],
                            "orig_name"=>$attachment["orig_name"],
                            "filetype"=>$attachment["file_ext"],
                            "file_size"=>$attachment["file_size"],
                            "mime_type"=>$attachment["file_type"],

                        );
                        $this->common_model->data_insert("mail_attachments",$insert_attachment,false);
                    }
                    $responce["response"] = true;
                    $responce["message"] = _success_message(_l("Email Send Successfully"));
                } else {
                    $responce["response"] = false;
                    $responce["message"] = _error_message(_l("Failed to Send Email"));
                }
            }else{
                $responce["response"] = false;
                    $responce["message"] = $upload["message"];
            }
        }
        echo json_encode($responce);
    }
    function inbox(){
        $this->load->library('pagination');

        $this->data["mailBox"] = $this->mailboxlib->getMailboxes();
        $get = $this->input->get();
        if(isset($get["folder"])){
            $this->data["active_mailbox"] = $get["folder"];
            $this->mailboxlib->setMailboxes($get["folder"]);
        }else{
            $this->data["active_mailbox"] = "INBOX";
            $this->mailboxlib->setMailboxes("INBOX");
        }
        $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;


        $this->data["data"] = $this->mailboxlib->getEmails(($page - 1) * 10,10);

        $this->mailboxlib->setMailboxes($this->data["active_mailbox"]);
        $config['base_url'] = site_url($this->controller."/inbox");
        $config['total_rows'] = $this->mailboxlib->totalEmails();
        $config['per_page'] = 10;
        $config["uri_segment"] = 4;
        $config['use_page_numbers']  = TRUE;
        $config['full_tag_open'] = "<ul class='pagination'>";
        $config['full_tag_close'] ="</ul>";
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = "<li class='disabled'><li class='active'><a href='#'>";
        $config['cur_tag_close'] = "<span class='sr-only'></span></a></li>";
        $config['next_tag_open'] = "<li>";
        $config['next_tagl_close'] = "</li>";
        $config['prev_tag_open'] = "<li>";
        $config['prev_tagl_close'] = "</li>";
        $config['first_tag_open'] = "<li>";
        $config['first_tagl_close'] = "</li>";
        $config['last_tag_open'] = "<li>";
        $config['last_tagl_close'] = "</li>";
        $config['reuse_query_string'] = TRUE;
        $this->pagination->initialize($config);
        $this->data["page_links"] = $this->pagination->create_links();


        $this->data["page_content"] = $this->load->view($this->controller."/inbox",$this->data,true);
        $this->data["page_script"] = $this->load->view($this->controller."/compose_script",$this->data,true);
        $this->load->view(BASE_TEMPLATE,$this->data);
    }
    function details($uid){
        $this->data["mailBoxes"] = $this->mailboxlib->getMailboxes();
        $mailbox = $this->input->get("mailbox");
        if($mailbox == NULL){
            return;
        }
        if(isset($mailbox)){
            $this->data["active_mailbox"] = $mailbox;
            $this->mailboxlib->setMailboxes($mailbox);
        }else{
            $this->data["active_mailbox"] = "INBOX";
            $this->mailboxlib->setMailboxes("INBOX");
        }
        $this->data["uid"] = $uid;
        $this->data["mailbox"] = $mailbox;
        $this->mailboxlib->setMailboxes($mailbox);
        $this->data["data"] = $this->mailboxlib->getEmailDetails($uid);
        $this->load->view($this->controller."/details",$this->data);
    }
    function read($uid){
        $this->data["mailBoxes"] = $this->mailboxlib->getMailboxes();
        $mailbox = $this->input->get("mailbox");
        if($mailbox == NULL){
            return;
        }
        if(isset($mailbox)){
            $this->data["active_mailbox"] = $mailbox;
            $this->mailboxlib->setMailboxes($mailbox);
        }else{
            $this->data["active_mailbox"] = "INBOX";
            $this->mailboxlib->setMailboxes("INBOX");
        }
        $this->data["uid"] = $uid;
        $this->data["mailbox"] = $mailbox;
        $this->mailboxlib->setMailboxes($mailbox);
        $this->data["data"] = $this->mailboxlib->getEmailDetails($uid);
        $this->data["page_content"] = $this->load->view($this->controller."/read",$this->data,true);
        $this->data["page_script"] = $this->load->view($this->controller."/mailbox_script",$this->data,true);
        $this->load->view(BASE_TEMPLATE,$this->data);
    }
    function iframe($uid){
        $mailbox = $this->input->get("mailbox");
        if($mailbox == NULL){
            return;
        }
        $this->mailboxlib->setMailboxes($mailbox);
        $this->data["data"] = $this->mailboxlib->getEmailDetails($uid);

        $this->session->set_userdata("reademail",$this->data["data"]);
        $this->load->view($this->controller."/iframe",$this->data);
    }
    function filedownload($filename){
        $filename = _decrypt_val($filename);
        $data = $this->session->userdata("reademail");
        $attachment = $data["attachment"][$filename];
        $filetype = key($attachment);
        $filedata = $attachment[$filetype];

        header('Content-Description: File Transfer');
        header('Content-Type: '.$filetype);
        header('Content-Disposition: attachment; filename='.$filename);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . strlen($filedata));
        ob_clean();
        flush();
        echo $filedata;
        exit;
    }
    public function do_upload()
        {
                $config['upload_path']          = './uploads/mails';
                if(!is_dir($config['upload_path'])){
                    mkdir($config['upload_path']);
                }
                $config['allowed_types']        = 'gif|jpg|jpeg|png|psd|pdf|xls|ppt|pptx|gzip|php|php4|php3|txt|text|tar|tgz|zip|rar|mp3|mp4|wav|doc|docx|3gp|7zip|vcf';
                $config['max_size']             = 20480;
                $config["encrypt_name"] = true;
                $this->load->library('upload', $config);
                $this->upload->initialize($config);
                $responce = array();
                foreach ($_FILES as $fieldname => $fileObject)  //fieldname is the form field name
                {
                    if (!empty($fileObject['name']))
                    {

                        if (!$this->upload->do_upload($fieldname))
                        {
                            $errors = $this->upload->display_errors();
                            return array("response"=>false,"message"=>$errors);
                        }
                        else
                        {
                            $responce[] = $this->upload->data();
                        }
                    }
                }
                return array("response"=>true,"data"=>$responce);
        }
        function test(){
            //$this->mailboxlib->send("subhashsanghani@gmail.com","test");
            $config['protocol'] = "smtp";
                $config['smtp_crypto'] = "ssl";
                $config['smtp_host'] = "mail.anamoly.se";
                $config['smtp_port'] = "465";
                $config['smtp_user'] = "noreply@anamoly.se";  // First Less secure apps enable on security your email : https://myaccount.google.com/lesssecureapps?pli=1
                $config['smtp_pass'] = "Mz#O^F0mbnG!njGe";
                $config['charset'] = "utf-8";
                $config['mailtype'] = "html";
                $config['newline'] = "\r\n";
                $config['wordwrap'] = TRUE;
                $this->email->initialize($config);

                $this->email->from("info@anamoly.se", APP_NAME);
                $this->email->to("subhashsanghani@gmail.com");
                $this->email->subject("test");
                $this->email->message("This is test message");
                $res = $this->email->send();
        }
}
