<?php
class session implements SessionHandlerInterface
{
    private $savePath;

    public function open($savePath, $sessionName)
    {
        $this->savePath = $savePath;
        if (!is_dir($this->savePath)) {
            mkdir($this->savePath, 0777);
        }

        return true;
    }

    public function close()
    {
        return true;
    }

    public function read($id)
    {
        return (string)@file_get_contents("$this->savePath/sess_$id");
    }

    public function write($id, $data)
    {
        return file_put_contents("$this->savePath/sess_$id", $data) === false ? false : true;
    }

    public function destroy($id)
    {
        $file = "$this->savePath/sess_$id";
        if (file_exists($file)) {
            unlink($file);
        }

        return true;
    }

	public function gc($maxlifetime){
		foreach (glob("$this->savePath/sess_*") as $file) {
			if(is_readable($file)){
				if (filemtime($file) + $maxlifetime < time() && file_exists($file) && is_writable($file)) {
					unlink($file);
          	}
			}
		}
		return true;
	}
}