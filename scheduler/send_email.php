#!/opt/php/bin/php
<?php
include_once "/home/kiam/lib/db_config.php";
/*$mysql_host = 'localhost';
$mysql_user = 'kiam';
$mysql_password = '49155f27kiam';
$mysql_db = 'kiam';
$self_con=mysql_connect($mysql_host,$mysql_user,$mysql_password) or die(mysql_error());
mysql_select_db($mysql_db) or die(mysql_error());
mysql_query("set names utf8");*/

$date = date("Y-m-d H:i:00"); //"2019-02-11 10:30:00";
$sql_all = "select * from gn_mail where reservation ='$date' and up_date is null";
$resul_all = mysqli_query($self_con, $sql_all);
while ($row_all = mysqli_fetch_array($resul_all)) {
    $from = $row_all['sender'];
    $to = $row_all['receiver'];
    $subject = $row_all['title'];
    $body = $row_all['content'];
    $file = $row_all['file'];
    $config = array(
        'host' => 'smtp.cafe24.com',
        'port' => 587,
        'smtp_id' => 'admin@ilearning11.cafe24.com',
        'smtp_pw' => '5614614a!@',
        'debug' => 1,
        'charset' => 'utf-8',
        'ctype' => 'text/plain'
    );
    $sendmail = new Sendmail($config);

    if ($file != "") {
        $realpath = "/home/ilearning3/www/" . $file;
        $name_arr = explode("__", $realpath);
        if (count($name_arr) > 1) {
            $name = strstr($file, "__");
            $name = substr($name, 2, strlen($name) - 1);
        } else {
            $name_arr = explode("/", $realpath);
            $name = $name_arr[count($name_arr) - 1];
        }
        $ctype = "application/octet-stream";
        $sendmail->attach($realpath, $name, $ctype);
    }
    echo "---------------" . $from . "=> " . $to . "---------------------";
    $sendmail->send_mail($to, $from, $subject, $body);

    $sql = "update gn_mail set up_date=now() where idx='{$row_all['idx']}'";
    mysqli_query($self_con, $sql);
}
mysqli_close($self_con);


class Sendmail
{

    var $host = "ssl://smtp.gmail.com";
    var $port = 465;
    var $smtp_id = "example@gmail.com";
    var $smtp_pw = "password";

    var $debug = 1;
    var $charset = "UTF-8";
    var $ctype = "text/plain";


    var $fp;
    var $lastmsg;
    var $parts = array();


    function Sendmail($data = false)
    {

        if ($data != false) {
            if (is_array($data)) {
                $this->host = !empty($data['host']) ? $data['host'] : $this->host;
                $this->port = !empty($data['port']) ? $data['port'] : $this->port;
                $this->smtp_id = !empty($data['smtp_id']) ? $data['smtp_id'] : $this->smtp_id;
                $this->smtp_pw = !empty($data['smtp_pw']) ? $data['smtp_pw'] : $this->smtp_pw;
                $this->debug = !empty($data['debug']) ? $data['debug'] : $this->debug;
                $this->charset = !empty($data['charset']) ? $data['charset'] : $this->charset;
                $this->ctype = !empty($data['ctype']) ? $data['ctype'] : $this->ctype;
            }
        }
    }

    function dialogue($code, $cmd)
    {
        fputs($this->fp, $cmd . "\r\n");
        $line = fgets($this->fp, 1024);
        preg_match("/^([0-9]+).(.*)$/", $line, $matches);
        $this->lastmsg = $matches[0];
        if ($this->debug) {
            echo htmlspecialchars($cmd) . "
                " . $this->lastmsg . "
                ";
            flush();
        }
        if ($matches[1] != $code) return false;
        return true;
    }
    function connect($host = '')
    {
        if ($this->debug) {
            echo "SMTP(" . $host . ") Connecting...";
            flush();
        }
        if (!$host) $host = $this->host;
        if (!$this->fp = fsockopen($host, $this->port, $errno, $errstr, 10)) {
            $this->lastmsg = "SMTP(" . $host . ") failed to connect to server.[" . $errno . ":" . $errstr . "]";
            return false;
        }
        $line = fgets($this->fp, 1024);
        preg_match("/^([0-9]+).(.*)$/", $line, $matches);
        $this->lastmsg = $matches[0];
        if ($matches[1] != "220") return false;
        if ($this->debug) {
            echo $this->lastmsg . "";
            flush();
        }
        $this->dialogue(250, "HELO phpmail");
        return true;
    }
    function close()
    {
        $this->dialogue(221, "QUIT");
        fclose($this->fp);
        return true;
    }
    function smtp_send($email, $from, $data, $cc_mail, $bcc_mail, $rel_to = false)
    {

        $id = $this->smtp_id;
        $pwd = $this->smtp_pw;



        if (!$mail_from = $this->get_email($from)) return false;
        if (!$rcpt_to = $this->get_email($email)) return false;



        if (!$this->dialogue(334, "AUTH LOGIN")) {
            return false;
        }
        if (!$this->dialogue(334, base64_encode($id)))  return false;
        if (!$this->dialogue(235, base64_encode($pwd)))  return false;
        if (!$this->dialogue(250, "MAIL FROM:" . $mail_from)) return false;
        if (!$this->dialogue(250, "RCPT TO:" . $rcpt_to)) {
            $this->dialogue(250, "RCPT TO:");
            $this->dialogue(354, "DATA");
            $this->dialogue(250, ".");
            return false;
        }

        if ($rel_to == false) {
            $rel_to = $email;
        }


        $this->dialogue(354, "DATA");
        $mime = "Message-ID: <" . $this->get_message_id() . ">\r\n";
        $mime .= "From: " . $from . "\r\n";
        $mime .= "To: " . $rel_to . "\r\n";

        if ($cc_mail != false) {

            $mime .= "Cc: " . $cc_mail . "\r\n";
        }
        if ($bcc_mail != false) $mime .= "Bcc: " . $bcc_mail . "\r\n";

        fputs($this->fp, $mime);
        fputs($this->fp, $data);
        $this->dialogue(250, ".");
    }
    function get_message_id()
    {
        $id = date("YmdHis", time());
        mt_srand((float) microtime() * 1000000);
        $randval = mt_rand();
        $id .= $randval . "@phpmail";
        return $id;
    }
    function get_boundary()
    {
        $uniqchr = uniqid(time());
        $one = strtoupper($uniqchr[0]);
        $two = strtoupper(substr($uniqchr, 0, 8));
        $three = strtoupper(substr(strrev($uniqchr), 0, 8));
        return "----=_NextPart_000_000{$one}_{$two}.{$three}";
    }

    function attach($path, $name = "", $ctype = "application/octet-stream")
    {
        if (is_file($path)) {
            $fp = fopen($path, "r");
            $message = fread($fp, filesize($path));
            fclose($fp);
            $this->parts[] = array("ctype" => $ctype, "message" => $message, "name" => $name);
        } else return false;
    }
    function build_message($part)
    {
        $msg = "Content-Type: " . $part['ctype'];
        if ($part['name']) $msg .= "; name=\"" . $part['name'] . "\"";
        $msg .= "\r\nContent-Transfer-Encoding: base64\r\n";
        $msg .= "Content-Disposition: attachment; filename=\"" . $part['name'] . "\"\r\n\r\n";
        $msg .= chunk_split(base64_encode($part['message']));
        return $msg;
    }

    function build_data($subject, $body)
    {
        $boundary = $this->get_boundary();
        $attcnt = sizeof($this->parts);
        $mime = "Subject: " . $subject . "\r\n";
        $mime .= "Date: " . date("D, j M Y H:i:s T", time()) . "\r\n";
        $mime .= "MIME-Version: 1.0\r\n";
        if ($attcnt > 0) {
            $mime .= "Content-Type: multipart/mixed; boundary=\"" . $boundary . "\"\r\n\r\n" .
                "This is a multi-part message in MIME format.\r\n\r\n";
            $mime .= "--" . $boundary . "\r\n";
        }
        $mime .= "Content-Type: " . $this->ctype . "; charset=\"" . $this->charset . "\"\r\n" .
            "Content-Transfer-Encoding: base64\r\n\r\n" . chunk_split(base64_encode($body));

        if ($attcnt > 0) {
            $mime .= "\r\n\r\n--" . $boundary;
            for ($i = 0; $i < $attcnt; $i++) {
                $mime .= "\r\n" . $this->build_message($this->parts[$i]) . "\r\n\r\n--" . $boundary;
            }
            $mime .= "--\r\n";
        }

        return $mime;
    }
    function get_mx_server($email)
    {

        if (!preg_match("/([\._0-9a-zA-Z-]+)@([0-9a-zA-Z-]+\.[a-zA-Z\.]+)/", $email, $matches)) return false;
        getmxrr($matches[2], $host);
        if (!$host) $host[0] = $matches[2];
        return $host;
    }
    function get_email($email)
    {
        if (!preg_match("/([\._0-9a-zA-Z-]+)@([0-9a-zA-Z-]+\.[a-zA-Z\.]+)/", $email, $matches)) return false;
        return "<" . $matches[0] . ">";
    }
    function send_mail($to, $from, $subject, $body, $cc_mail = false, $bcc_mail = false)
    {
        //$from.=" <".$this->smtp_id.">";
        if (!is_array($to)) {
            $rel_to = $to;
            $to = explode(",", $to);
        } else {
            $rel_to = implode(',', $to);
        }
        $data = $this->build_data($subject, $body);
        if ($this->host == "auto") {
            foreach ($to as $email) {
                if ($host = $this->get_mx_server($email)) {
                    for ($i = 0, $max = count($host); $i < $max; $i++) {
                        if ($conn = $this->connect($host[$i])) break;
                    }
                    if ($conn) {
                        $this->smtp_send($email, $from, $data, $cc_mail, $bcc_mail);
                        $this->close();
                    }
                }
            }
        } else {
            foreach ($to as $key => $email) {
                $this->connect($this->host);
                $this->smtp_send($email, $from, $data, $cc_mail, $bcc_mail, $rel_to);
                $this->close();
            }

            if ($cc_mail != false) {
                $this->cc_email($rel_to, $from, $data, $cc_mail, $bcc_mail);
            }
            if ($bcc_mail != false) {
                $this->bcc_email($rel_to, $from, $data, $cc_mail, $bcc_mail);
            }
        }
    }

    function cc_email($rel_to, $from, $data, $cc_mail, $bcc_mail)
    {
        if (!is_array($cc_mail)) $cc = explode(",", $cc_mail);

        foreach ($cc as $email) {
            $this->connect($this->host);
            $this->smtp_send($email, $from, $data, $cc_mail, $bcc_mail, $rel_to);
            $this->close();
        }
    }
    function bcc_email($rel_to, $from, $data, $cc_mail, $bcc_mail)
    {

        if (!is_array($bcc_mail)) $bcc = explode(",", $bcc_mail);

        foreach ($bcc as $email) {
            $this->connect($this->host);
            $this->smtp_send($email, $from, $data, $cc_mail, $bcc_mail, $rel_to);
            $this->close();
        }
    }
}
?>