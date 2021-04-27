<?php
include_once APPPATH . 'third_party/eden/vendor/autoload.php';

class Mailboxlib {
    public $pop;
    public function __construct()
    {
        Eden::DECORATOR;
        date_default_timezone_set('GMT');
        $this->pop = eden('mail')->imap(
            'mail.anamoly.se',
            'info@anamoly.se',
            '',
            995,
            false
        );
        //$this->pop
        //->setActiveMailbox('INBOX');
    }
    public function tearDown()
    {
        $this->pop->disconnect();
    }
    function totalEmails(){
        return $this->pop->getEmailTotal();
    }
    function setMailboxes($name){
        $this->pop->setActiveMailbox($name);
    }

    public function getEmails($start=0, $limit = 10)
    {
        //return $this->pop->getEmails($start, $limit);

        return $emails = $this->pop
        ->getEmails($start, $limit);
    }
    function getEmailDetails($uids){

        return $this->pop->getUniqueEmails($uids, true);
    }
    function getMailboxes(){
        return $this->pop->getMailboxes();
    }
    function deleteEmail($id){
        $this->pop->remove($id, true);
    }
    function send($to,$subject){
        $smtp = eden('mail')->smtp(
            'mail.anamoly.se',
            'info@anamoly.se',
            '',
            465,
            true);
            $smtp->setSubject('Welcome!')
    ->setBody('<p>Hello you!</p>', true)
    ->setBody('Hello you!')
    ->addTo('subhashsanghani@gmail.com')
    ->send();
    $smtp->disconnect();
    //->addAttachment('file.jpg', '/path/to/file.jpg', 'mime-type')
    }
}
