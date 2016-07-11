<?php

class Logger {

    // The file name and the file pointer 
    private $fname, $fp;

    function __construct($logfile) {

        if (empty($logfile)) {

            print("FATAL! Logger constructor called with no paramter!\n");
            exit(1);
        }

        $this->fname = $logfile;

        // open
        $this->fp = fopen($this->fname, 'a') or exit("Unable to open: $this->fname");
    }


    public function wr($message) {

        if (!is_resource($this->fp)) {

            exit("The logfile is not open???");
        }

        // define script name
        $script_name = pathinfo($_SERVER['PHP_SELF'], PATHINFO_FILENAME);

        // define current time and suppress E_WARNING if using the system TZ settings
        // (don't forget to set the INI setting date.timezone)
        $time = @date('[d/M/Y H:i:s]');

        // write current time, script name and message to the log file
        fwrite($this->fp, "$time ($script_name) $message" . PHP_EOL);
    }

    // close log file (it's always a good idea to close a file when you're done with it)
    public function close() {

        fclose($this->fp);
    }
}

?>

