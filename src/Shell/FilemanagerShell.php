<?php
namespace App\Shell;
use Cake\Console\Shell;

use Cake\Mailer\Email;
use Cake\ORM\TableRegistry;
use Cake\Datasource\ConnectionManager;

use Google\Cloud\Storage\StorageClient;
use League\Flysystem\Filesystem;
use Superbalist\Flysystem\GoogleStorage\GoogleStorageAdapter;

class FilemanagerShell extends Shell
{
    public function main()
    {
        $this->deleteBackup();
    }
	
	public function deleteBackup()
    {
		$directory = "archive";
		$storageClient = new StorageClient([
			'projectId' => 'gym-management-system-188906',
			'keyFilePath' => WWW_ROOT .'/nghome/gym-management-system-188906-8be3c1fa2801.json',
		]);
		$bucket = $storageClient->bucket('gym-management-system-188906.appspot.com');

		$adapter = new GoogleStorageAdapter($storageClient, $bucket);
		$filesystem = new Filesystem($adapter);

		$filesystem = new Filesystem($adapter);
		$files = $filesystem->listContents("/$directory", true); //Listing
		foreach($files as $file)
		{
			if($file['type'] == "file")
			{
				$created_date = strtotime(date('Y-m-d H:i:s', $file['timestamp']));
				$today_date = strtotime(date('Y-m-d H:i:s'));
				
				$diff = abs($created_date - $today_date);
				$years = floor($diff / (365*60*60*24));
				$months = floor(($diff - $years * 365*60*60*24) 
                               / (30*60*60*24));
							   
				$days = floor(($diff - $years * 365*60*60*24 -  
						$months*30*60*60*24)/ (60*60*24));
				// debug(date('Y-m-d H:i:s', $file['timestamp']));
				// debug(date('Y-m-d H:i:s'));
				// debug($days);
				
				if($days >= 30)
				{
					$delete = $filesystem->delete($file['path']);
				}
			}
		}
	}
}