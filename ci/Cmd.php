<?php

class cmd
{
    protected $status;
    protected $cmd;
    protected $stdout;
    protected $stderr;
    protected $out;
    protected $err;

    public static function make($cmd, $stdout = true, $strerr = true)
    {
        return new static($cmd, $stdout, $strerr);
    }

    public function __construct($cmd, $stdout = true, $strerr = true)
    {
        $this->cmd = is_array($cmd) ? implode(' ', $cmd) : $cmd;
        $this->stdout = $stdout;
        $this->stderr = $strerr;
    }

    public function cmd() 
    {
        return $this->cmd;
    }
    
    public function out() 
    {
        return $this->out;
    }
    
    public function err() 
    {
        return $this->err;
    }
    
    public function status() 
    {
        return $this->status;
    }
    
    public function success() 
    {
        return $this->status === 0;
    }
    
    public function __invoke()
    {
        if ($this->cmd === null || $this->cmd === false || $this->cmd === '') {
            throw new LogicException();
        }

        $this->status = null;
        $this->out = null;
        $this->err = null;

        $proc = proc_open($this->cmd, [['pipe', 'r'], ['pipe', 'w'], ['pipe', 'w']], $pipes);
        while (($line = fgets($pipes[1])) !== false) {
            if ($this->stdout) {
                fwrite(STDOUT, $line);
                flush();
            }
            $this->out .= $line;
        }
        while (($line = fgets($pipes[2])) !== false) {
            if ($this->stderr) {
                fwrite(STDERR, $line);
                flush();
            }
            $this->err .= $line;
        }
        fclose($pipes[0]);
        fclose($pipes[1]);
        fclose($pipes[2]);
        $this->status = proc_close($proc);
        return $this;
    }
}
