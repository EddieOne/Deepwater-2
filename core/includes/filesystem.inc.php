<?php
// A class to hold common filesystem methods
class filesystem {
	// currisvly delete directories and any files within
	public static function del_dir($dir) {
		$files = array_diff(scandir($dir), array('.','..'));
		foreach ($files as $file) {
			(is_dir("$dir/$file")) ? self::del_dir("$dir/$file") : unlink("$dir/$file");
		}
		return rmdir($dir);
  	} 
	// copy a directory recurressivly, overwrite duplicates
	public static function copy_dir($source, $dest){
		foreach($iterator = new RecursiveIteratorIterator(
		new RecursiveDirectoryIterator($source, RecursiveDirectoryIterator::SKIP_DOTS),
		RecursiveIteratorIterator::SELF_FIRST) as $item) {
			if($item->isDir()){
				if(!file_exists($dest . DIRECTORY_SEPARATOR . $iterator->getSubPathName())){
					mkdir($dest . DIRECTORY_SEPARATOR . $iterator->getSubPathName());
				}
			}else{
				copy($item, $dest . DIRECTORY_SEPARATOR . $iterator->getSubPathName());
			}
		}
	}
	 private function unzip($zip_file, $destination){
		$zip = new ZipArchive;
		if ($zip->open($zip_file) === TRUE) {
			$zip->extractTo($destination);
			$zip->close();
			return $comment;
		} else {
			return false;
		}
	}
}
?>