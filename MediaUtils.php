<?php

final class MediaUtils{

  public static function deleteResourceFile(array $attrs){

    $fhash = $attrs['hash'];
    $ext = $attrs['ext'];

    $folder = substr($fhash, 0, 2);
    // The remaining string is the name of the image file
    $file_name = substr($fhash, 2);
    $root = self::getApplicationRoot();
    $path = $root.DIRECTORY_SEPARATOR.
    'mediastore'.DIRECTORY_SEPARATOR.$folder;
    $path = $path.DIRECTORY_SEPARATOR.$file_name.'.'.$ext;
  
    if(self::pathExists($path)){
      try{
        unlink($path);
        rmdir(dirname($path));
      }catch(Exception $ex){
        // ignore
      }
    }
  }

  public static function readRandomCharacters($number_of_characters) {

      // NOTE: To produce the character string, we generate a random byte string
      // of the same length, select the high 5 bits from each byte, and
      // map that to 32 alphanumeric characters. This could be improved (we
      // could improve entropy per character with base-62, and some entropy
      // sources might be less entropic if we discard the low bits) but for
      // reasonable cases where we have a good entropy source and are just
      // generating some kind of human-readable secret this should be more than
      // sufficient and is vastly simpler than trying to do bit fiddling.

      $map = array_merge(range('a', 'z'), range('2', '7'));

      $result = '';
      $bytes = self::readRandomBytes($number_of_characters);
      for ($ii = 0; $ii < $number_of_characters; $ii++) {
        $result .= $map[ord($bytes[$ii]) >> 3];
      }

      return $result;
    }

    public static function readRandomBytes($number_of_bytes) {
        $number_of_bytes = (int)$number_of_bytes;
        if ($number_of_bytes < 1) {
          throw new Exception('You must generate at least 1 byte of entropy.');
        }

        // Try to use `openssl_random_psuedo_bytes()` if it's available. This source
        // is the most widely available source, and works on Windows/Linux/OSX/etc.

        if (function_exists('openssl_random_pseudo_bytes')) {
          $strong = true;
          $data = openssl_random_pseudo_bytes($number_of_bytes, $strong);

          if (!$strong) {
            // NOTE: This indicates we're using a weak random source. This is
            // probably OK, but maybe we should be more strict here.
          }

          if ($data === false) {
            throw new Exception(
              'openssl_random_pseudo_bytes() failed to generate entropy!');
          }

          if (strlen($data) != $number_of_bytes) {
            throw new Exception(
              sprintf(
                'openssl_random_pseudo_bytes() returned an unexpected number of '.
                'bytes (got %d, expected %d)!'
                , strlen($data)
                , $number_of_bytes));
          }

          return $data;
        }


        // Try to use `/dev/urandom` if it's available. This is usually available
        // on non-Windows systems, but some PHP config (open_basedir) and chrooting
        // may limit our access to it.

        $urandom = @fopen('/dev/urandom', 'rb');
        if ($urandom) {
          $data = @fread($urandom, $number_of_bytes);
          @fclose($urandom);
          if (strlen($data) != $number_of_bytes) {
            throw new Exception(
              '/dev/urandom',
              'Failed to read random bytes!');
          }
          return $data;
        }

        // (We might be able to try to generate entropy here from a weaker source
        // if neither of the above sources panned out, see some discussion in
        // T4153.)

        // We've failed to find any valid entropy source. Try to fail in the most
        // useful way we can, based on the platform.

        throw new Exception(
          pht(
            'ProskoolFileSystem::readRandomBytes() requires the PHP OpenSSL extension '.
            'or access to "/dev/urandom". Install or enable the OpenSSL '.
            'extension, or make sure "/dev/urandom" is accessible.'));
      }

  public static function removePath($path){

    if (PHP_OS === 'WINNT'){
      $command = sprintf("rd /s /q %s", escapeshellarg($path));
      passthru($command);
    }else{
      $command = sprintf("rm -rf %s", escapeshellarg($path));
      passthru($command);
    }

  }
  public static function handleMediaFileUpload($filename){

      $file_hash = '';
      $file_name =  '';
      $file_size = 0;

      if(self::checkResourceFileExists($filename)){
        // We have an uploaded image
        // Read the content of the image file
        // $data = (string)file_get_contents('php://input');
        $data = file_get_contents($_FILES[$filename]['tmp_name']);
        if(!$data || !strlen($data)){
          throw new Exception(
          'Failed to read the media resource content '.
          'of the uploaded file '.$filename);
        }

        // Create a has of the data content to be used for the file
        // storage folder and name
        $file_size = strlen($data);
        $file_hash_x = self::readRandomCharacters(40);
        // the first two characters will form the storage folder name
        // while the remaining characters will become the actual name of the
        // file on storage
        $folder = substr($file_hash_x, 0,2);
        $new_filename = substr($file_hash_x, 2);
        // get the extension of the file from the name
        $extensions = explode('.', $_FILES[$filename]['name']);
        $extension = $extensions[count($extensions) - 1];
        $file_name = self::normalizeFilename($_FILES[$filename]['name']);

        if(!$extension){
          throw new Exception(
          'Unable to determine media resource file extension');
        }

        $app_root = self::getApplicationRoot();
        // We will create the sub folder within the photobucket folder
        // if it does not exists we create it as well as the subfolder
        // where the folder will be stored

        $storage_path = $app_root.
        DIRECTORY_SEPARATOR.'mediastore'.DIRECTORY_SEPARATOR.$folder;

        if(!self::pathExists($storage_path)){
          if(!mkdir($storage_path, 0777, true)){
                throw new Exception(
                'Unable to create media resource storage path '.$storage_path);
              }
        }

        // Next write the file to the newly created folder, force the
        // the extension to be lowercase
        $extension = strtolower($extension);
        $file_path = $storage_path.
        DIRECTORY_SEPARATOR."{$new_filename}.{$extension}";

        if(!file_put_contents($file_path, $data)){
          // We have issues writing the files to the storage
          throw new Exception(
          'Unable to write media resource binary data to the path '.$file_path);
        }

        $file_hash = $file_hash_x;
      }

      return array($file_hash, $file_name, $extension, $file_size);

    }

  private static function checkResourceFileExists($filename){
    return (isset($_FILES[$filename])
    && $_FILES[$filename]['error'] !== UPLOAD_ERR_NO_FILE);
    }

    public static function hashID() {
      return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
// 32 bits for "time_low"
mt_rand(0, 0xffff), mt_rand(0, 0xffff),
// 16 bits for "time_mid"
mt_rand(0, 0xffff),
// 16 bits for "time_hi_and_version",
// four most significant bits holds version number 4
                mt_rand(0, 0x0fff) | 0x4000,
// 16 bits, 8 bits for "clk_seq_hi_res",
// 8 bits for "clk_seq_low",
// two most significant bits holds zero and one for variant DCE1.1
                mt_rand(0, 0x3fff) | 0x8000,
// 48 bits for "node"
                mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
    }

    public static function getApplicationRoot(){
      return dirname(__FILE__);
  }

  public static function pathExists($path) {
    return file_exists($path) || is_link($path);
  }

  public static function normalizeFileName($file_name) {
      $pattern = "@[\\x00-\\x19#%&+!~'\$\"\/=\\\\?<> ]+@";
      $file_name = preg_replace($pattern, '_', $file_name);
      $file_name = preg_replace('@_+@', '_', $file_name);
      $file_name = trim($file_name, '_');
      return $file_name;
    }
}
